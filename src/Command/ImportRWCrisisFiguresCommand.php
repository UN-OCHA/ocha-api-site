<?php

namespace App\Command;

use App\Entity\ReliefWebCrisisFigures;
use App\Entity\ReliefWebCrisisFigureValue;
use App\Repository\ReliefWebCrisisFiguresRepository;
use App\Repository\ReliefWebCrisisFigureValueRepository;
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

    protected ReliefWebCrisisFiguresRepository $repository;
    protected ReliefWebCrisisFigureValueRepository $value_repository;
    protected HttpClientInterface $httpClient;
    protected RouterInterface $router;
    protected SymfonyStyle $io;

    public function __construct(ReliefWebCrisisFiguresRepository $repository, ReliefWebCrisisFigureValueRepository $value_repository, HttpClientInterface $http_client, RouterInterface $router)
    {
        $this->repository = $repository;
        $this->value_repository = $value_repository;
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
            foreach ($iso3s as $iso3) {
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
      // https://raw.githubusercontent.com/reliefweb/crisis-app-data/v2/edition/world/main.json
      $countries = $this->getDataFromApi('main.json');
      
      return array_map(function ($country) {
        return $country['iso3'];
      }, $countries);
    }

  /**
   * Update by iso3.
   *
   * @param string $iso3
   *   The iso3.
   */
  public function updateByIso3(string $iso3) : void {
    $progress = $this->io->createProgressBar();
    $progress->setFormat('with-message');
    $progress->setMessage('Fetching data');
    $progress->start();

    $figures = $this->getDataFromApi('countries/' . $iso3 . '/figures.json');
    $progress->setMaxSteps(count($figures));

    foreach ($figures as $figure) {
      $progress->advance();
      $progress->setMessage('Processing ' . $figure['name'] . ' [' . $iso3 . ']');

      $item = [
        'iso3' => $iso3,
          'date' => new DateTime($figure['date']),
          'description' => $figure['description'],
          'language' => $figure['language'],
          'name' => $figure['name'],
          'value' => $figure['value'],
          'url' => $figure['url'],
          'source' => $figure['source'],
      ];

      if ($existing = $this->loadByIso3AndName($iso3, $figure['name'])) {
          $existing->fromValues($item);
          $this->createKeyFigure($existing, $figure['values']);
      }
      else {
          $fts_key_figure = new ReliefWebCrisisFigures();
          $fts_key_figure->fromValues($item);
  
          $this->createKeyFigure($fts_key_figure, $figure['values']);
      }
    }

    $progress->finish();

  }

  /**
   * Load plan by Iso3 and Name.
   */
  public function loadByIso3AndName($iso3, $name) {
    return $this->repository->findOneBy([
      'iso3' => $iso3,
      'name' => $name,
    ]);
  }

  /**
   * Create a new plan.
   */
  public function createKeyFigure(ReliefWebCrisisFigures $item, array $values) {
    $item->getFigureValues()->clear();
    foreach ($values as $value) {
      try {
        $figure = new ReliefWebCrisisFigureValue();
  
        $figure->setDate(new DateTime($value['date']))
          ->setValue($value['value']);
  
        if (isset($value['url'])) {
          $figure->setUrl($value['url']);
        }
  
        $this->value_repository->save($figure);
        $item->addValue($figure);
      }
      catch (\Exception) {
        // Ignore invalid dates, 2015-12-32T12:00:00Z
      }
    }
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
