<?php

namespace App\Command;

use App\Entity\KeyFigures;
use App\Entity\N8nCategory;
use App\Entity\N8nWorkflow;
use App\Repository\KeyFiguresRepository;
use App\Repository\N8nCategoryRepository;
use App\Repository\N8nWorkflowRepository;
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
    name: 'app:add-template',
    description: 'Add an n8n workflow as template',
)]
class TemplateAddCommand extends Command
{

    protected N8nWorkflowRepository $repository;
    protected N8nCategoryRepository $category_repo;
    protected HttpClientInterface $httpClient;
    protected SymfonyStyle $io;

    public function __construct(N8nWorkflowRepository $repository, N8nCategoryRepository $category_repo, HttpClientInterface $http_client)
    {
        $this->repository = $repository;
        $this->category_repo = $category_repo;
        $this->httpClient = $http_client;
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
          ->addArgument('id', InputArgument::REQUIRED, 'Id')
          ->addArgument('workflow_id', InputArgument::REQUIRED, 'Workflow Id')
          ->addArgument('name', InputArgument::REQUIRED, 'Name')
          ->addArgument('description', InputArgument::REQUIRED, 'Description')
          ->addArgument('categories', InputArgument::OPTIONAL, 'Categories')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $this->io = new SymfonyStyle($input, $output);

        $id = $input->getArgument('id');
        $workflow_id = $input->getArgument('workflow_id');
        $name = $input->getArgument('name');
        $description = $input->getArgument('description');
        $categories = explode(',', $input->getArgument('categories')) ?? [];

        $this->updateWorkflow($id, $workflow_id, $name, $description, $categories);
        $this->io->success('Data updated.');

        return Command::SUCCESS;
    }

  /**
   * Update workflow.
   */
  public function updateWorkflow($id, $workflow_id, $name, $description, $categories) : void {
    $workflow = $this->getDataFromApi('/workflows/' . $workflow_id);

    // Remove keys we do not need.
    $workflow = array_intersect_key($workflow, [
      'meta' => 'meta',
      'nodes' => 'nodes',
      'connections' => 'connections',
    ]);

    /** @var \App\Entity\N8nWorkflow */
    $item = $this->load($id);
    if (!$item) {
      $item = new N8nWorkflow;
    }

    $item->setDescription($description)
        ->setName($name)
        ->setNodes([])
        ->setWorkflow($workflow)
        ->setUser(['username' => 'Attiks']);

    foreach ($categories as $label) {
      $label = trim($label);
      $category = $this->category_repo->findOneBy(['name' => $label]);
      if (!$category) {
        $category = new N8nCategory;
        $category->setName($label);
        $this->category_repo->save($category);
      }
      
      if (!$item->getCategories()->contains($category)) {
        $item->addCategory($category);
      }
    }

    $this->save($item);
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
  public function save(N8nWorkflow $item) {
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
    $endpoint = $_ENV['N8N_WORKFLOW_ENDPOINT'] ?? '';
    $api_key = $_ENV['N8N_WORKFLOW_API_KEY'] ?? '';

    if (empty($endpoint)) {
      return [];
    }

    $headers = [
      'X-N8N-API-KEY' => $api_key,
      'accept' => 'application/json',
    ];

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
