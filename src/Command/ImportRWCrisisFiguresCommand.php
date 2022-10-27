<?php

namespace App\Command;

use App\Entity\KeyFigures;
use App\Repository\KeyFiguresRepository;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\HttpFoundation\Exception\RequestExceptionInterface;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'import:rw-crisis',
    description: 'Import ReliefWeb Crisis data',
)]
class ImportRWCrisisFiguresCommand extends Command
{

    protected KeyFiguresRepository $repository;
    protected HttpClientInterface $httpClient;
    protected RouterInterface $router;
    protected SymfonyStyle $io;

    public function __construct(KeyFiguresRepository $repository, HttpClientInterface $http_client, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->httpClient = $http_client;
        $this->router = $router;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('iso3', InputArgument::OPTIONAL, 'The country to import')
            ->addOption(
                'all',
                NULL,
                InputOption::VALUE_NONE,
                'Import all data',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        ProgressBar::setFormatDefinition('with-message', ' %current%/%max% -- %message%');

        if ($input->getOption('all')) {
            $iso3s = $this->getAllIso3Codes();
            foreach (array_keys($iso3s) as $iso3) {
                $this->updateByIso3($iso3);
            }
            return Command::SUCCESS;
        }

        $iso3 = strtoupper($input->getArgument('iso3'));
        if (!$iso3) {
            return Command::INVALID;
        }

        $this->updateByIso3($iso3);
        $this->io->success('Data updated.');

        return Command::SUCCESS;
    }

    /**
     * Get all iso3 codes.
     */
    public function getAllIso3Codes() {
      static $countries = [];

      if (empty($countries)) {
        // https://raw.githubusercontent.com/reliefweb/crisis-app-data/v2/edition/world/main.json
        $raw_countries = $this->getDataFromApi('main.json');

        foreach ($raw_countries as $raw_country) {
          $countries[$raw_country['iso3']] = $raw_country['name'];
        }
      }

      return $countries;
    }

  /**
   * Update by iso3.
   *
   * @param string $iso3
   *   The iso3.
   */
  public function updateByIso3(string $iso3) : void {
    $country = $this->getAllIso3Codes()[$iso3];

    $progress = $this->io->createProgressBar();
    $progress->setFormat('with-message');
    $progress->setMessage('Fetching data');
    $progress->start();

    $figures = $this->getDataFromApi('countries/' . $iso3 . '/figures.json');
    $progress->setMaxSteps(count($figures));

    foreach ($figures as $figure) {
      $progress->advance();
      $progress->setMessage('Processing ' . $figure['name'] . ' [' . $iso3 . ']');

      foreach ($figure['values'] as $value) {
        if (empty($value['value']) || $value['value'] == 0) {
          continue;
        }

        $year = substr($value['date'], 0, 4);
        $id = 'rw_crisis_' . strtolower($iso3) . '_' . $year . '_' . $figure['name'];

        $date = NULL;
        try {
          $date = new DateTime($value['date']);
        }
        catch (\Exception) {
          // Ignore invalid dates, 2015-12-32T12:00:00Z
        }

        $item = [
          'id' => $id,
          'iso3' => $iso3,
          'country' => $country,
          'year' => $year,
          'updated' => $date,
          'description' => $figure['description'],
          'language' => $figure['language'],
          'name' => $figure['name'],
          'value' => $value['value'],
          'url' => $value['url'] ?? '',
          'source' => $figure['source'],
          'tags' => [
            'rw_crisis',
          ],
          'provider' => 'rw_crisis',
        ];

        if ($existing = $this->load($id)) {
          $existing->fromValues($item);
          $this->save($existing);
        }
        else {
          $new = new KeyFigures();
          $new->fromValues($item);
          $this->save($new);
        }
      }
    }

    $progress->finish();

  }

  /**
   * Load plan by PlanId.
   */
  public function load($id) {
    return $this->repository->findOneBy(['id' => $id]);
  }

  /**
   * Create a new plan.
   */
  public function save(KeyFigures $item) {
    $this->repository->save($item, TRUE);
  }

  /**
   * Load data from API.
   *
   * @param string $path
   *   API path.
   * @param array $query
   *   Query options.
   *
   * @return array
   *   Raw results.
   */
  public function getDataFromApi(string $path, array $query = []) : array {
    $endpoint = 'https://raw.githubusercontent.com/reliefweb/crisis-app-data/v2/edition/world/';

    if (empty($endpoint)) {
      return [];
    }

    $headers = [];
    if (strpos($endpoint, '@') !== FALSE) {
      $auth = substr($endpoint, 8, strpos($endpoint, '@') - 8);
      $endpoint = substr_replace($endpoint, '', 8, strpos($endpoint, '@') - 7);
      $headers['Authorization'] = 'Basic ' . base64_encode($auth);
    }

    // Construct full URL.
    $fullUrl = $endpoint . $path;

    try {
      $response = $this->httpClient->request(
        'GET',
        $fullUrl,
        [
            'headers' => $headers,
            'query' => $query,
        ],
      );
    }
    catch (RequestExceptionInterface $exception) {
    throw $exception;
    }

    $body = $response->getContent() . '';
    $results = json_decode($body, TRUE);

    return $results;
  }

}
