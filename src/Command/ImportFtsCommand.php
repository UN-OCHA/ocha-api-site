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
    name: 'import:fts',
    description: 'Import FTS data for a specific year',
)]
class ImportFtsCommand extends Command
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
            ->addArgument('year', InputArgument::OPTIONAL, 'The year to import, defaults to current year')
            ->addOption(
                'all',
                NULL,
                InputOption::VALUE_NONE,
                'Import all data starting from 2000',
            )
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        ProgressBar::setFormatDefinition('with-message', ' %current%/%max% -- %message%');

        if ($input->getOption('all')) {
            $years = range(2000, date('Y'));
            foreach ($years as $year) {
                $this->updateByYear($year);
            }
            return Command::SUCCESS;
        }

        $year = $input->getArgument('year');
        if (!$year) {
            $year = date('Y');
        }

        $this->updateByYear($year);
        $this->io->success('Data updated.');

        return Command::SUCCESS;
    }

  /**
   * Update by year.
   *
   * @param int $year
   *   The year.
   */
  public function updateByYear(int $year) : void {
    $progress = $this->io->createProgressBar();
    $progress->setFormat('with-message');
    $progress->setMessage('Fetching data');
    $progress->start();

    $plans = $this->getDataFromApi('public/plan/year/' . $year);
    $progress->setMaxSteps(count($plans));

    foreach ($plans as $plan) {
      $progress->advance();
      $progress->setMessage('Processing plan ' . $plan['id'] . ' [' . $year . ']');

      // Skip plans without location.
      if (!isset($plan['locations'][0])) {
          continue;
      }

      // Skip plans without iso3.
      if (!isset($plan['locations'][0]['iso3'])) {
          continue;
      }

      $data_plan = $this->getDataFromApi('public/plan/id/' . $plan['id']);
      $data_flow = $this->getDataFromApi('public/fts/flow', ['planId' => $plan['id']]);

      $figure_types = [
        'original_requirements' => [
          'label' => 'Original requirements',
          'value' => $plan['origRequirements'],
        ],
        'revised_requirements' => [
          'label' => 'Revised requirements',
          'value' => $plan['revisedRequirements'],
        ],
        'total_requirements' => [
          'label' => 'Total requirements',
          'value' => $data_plan['revisedRequirements'],
        ],
        'funding_total' => [
          'label' => 'Funding total',
          'value' => $data_flow['incoming']['fundingTotal'],
        ],
      ];

      foreach ($figure_types as $figure_type => $info) {
        if (!$info['value']) {
          continue;
        }
        
        $id = 'fts_' . strtolower($plan['locations'][0]['iso3']) . '_' . $year . '_' . $figure_type;
        $item = [
          'id' => $id,
          'name' => $info['label'],
          'year' => $year,
          'iso3' => strtolower($plan['locations'][0]['iso3']),
          'country' => $plan['locations'][0]['name'],
          'updated' => new DateTime($plan['updatedAt']),
          'value' => $info['value'],
          'source' => 'FTS',
          'url' => 'https://fts.unocha.org/appeals/' . $plan['id'] . '/summary',
          'description' => $plan['planVersion']['name'] . ' [' . $plan['planVersion']['code'] . ']',
          'tags' => [
            'fts',
            'financial',
          ],
          'provider' => 'fts',
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
    $endpoint = 'https://api.hpc.tools/v1/';

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

    return $results['data'];
  }

}
