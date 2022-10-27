<?php

namespace App\Command;

use App\Entity\KeyFigures;
use App\Repository\KeyFiguresRepository;
use DateTime;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\ProgressBar;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\Routing\RouterInterface;
use Symfony\Contracts\HttpClient\HttpClientInterface;

#[AsCommand(
    name: 'import:idps',
    description: 'Import IPDs data',
)]
class ImportIdpsCommand extends Command
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

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);
        ProgressBar::setFormatDefinition('with-message', ' %current%/%max% -- %message%');

        $this->update();
        $this->io->success('Data updated.');

        return Command::SUCCESS;
    }

  /**
   * Update by iso3.
   */
  public function update() : void {
    $progress = $this->io->createProgressBar();
    $progress->setFormat('with-message');
    $progress->setMessage('Fetching data');
    $progress->start();

    $figures = $this->getData();
    $progress->setMaxSteps(count($figures));

    foreach ($figures as $figure) {
      $year = $figure['Year'];
      $iso3 = $figure['ISO3'];

      $progress->advance();
      $progress->setMessage('Processing ' . $figure['Name'] . ' [' . $iso3 . ']');

      $figure_types = [
        'conflict_stock_displacement' => [
          'label' => 'Conflict Stock Displacement',
          'value' => $figure['Conflict Stock Displacement'],
        ],
        'conflict_internal_displacements' => [
          'label' => 'Conflict Internal Displacements',
          'value' => $figure['Conflict Internal Displacements'],
        ],
        'disaster_internal_displacements' => [
          'label' => 'Disaster Internal Displacements',
          'value' => $figure['Disaster Internal Displacements'],
        ],
        'disaster_stock_displacement' => [
          'label' => 'Disaster Stock Displacement',
          'value' => $figure['Disaster Stock Displacement'],
        ],
      ];

      foreach ($figure_types as $figure_type => $info) {
        // Skip empty values.
        if (empty($info['value'])) {
          continue;
        }

        $id = 'idps_' . strtolower($iso3) . '_' . $year . '_' . $figure_type;
        $item = [
          'id' => $id,
          'iso3' => $iso3,
          'country' => $figure['Name'],
          'year' => $year,
          'name' => $info['label'],
          'value' => $info['value'],
          'url' => 'https://data.humdata.org/dataset/idmc-internally-displaced-persons-idps',
          'source' => 'iDMC',
          'tags' => [
            'idps',
          ],
          'provider' => 'idps',
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
   * Load plan by id.
   */
  public function load($id) {
    return $this->repository->findOneBy(['id' => $id]);
  }

  /**
   * Create a new plan.
   */
  public function save(KeyFigures $item) {
    try {
      $this->repository->save($item, TRUE);
    }
    catch (\Exception) {
      // Ignore invalid dates, 2015-12-32T12:00:00Z
    }
  }

  /**
   * Load data from API.
   *
   * @return array
   *   Raw results.
   */
  public function getData() : array {
    $endpoint = 'https://data.humdata.org/dataset/459fc96c-f196-44c1-a0a5-1b5a7b3592dd/resource/0fb4e415-abdb-481a-a3c6-8821e79919be/download/displacement_data.csv';
    return $this->parseCsv($endpoint);
  }

  protected function parseCsv($file) {
    $rows = array_map('str_getcsv', file($file));
    $header = array_shift($rows);
    $csv = array();
    foreach ($rows as $row) {
      if (strpos($row[0], '#') === 0) {
        continue;
      }

      $csv[] = array_combine($header, $row);
    } 

    return $csv;
  }
}
