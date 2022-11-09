<?php

namespace App\Command;

use App\Entity\Provider;
use App\Repository\ProviderRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:add-provider',
    description: 'Add a short description for your command',
)]
class ProviderAddCommand extends Command
{

    public function __construct(
        private EntityManagerInterface $entityManager,
        private ProviderRepository $providers
    ) {
        parent::__construct();
    }

    protected function configure(): void
    {
        $this
            ->addArgument('id', InputArgument::REQUIRED, 'Unique Id')
            ->addArgument('name', InputArgument::REQUIRED, 'Name for swagger')
            ->addArgument('prefix', InputArgument::REQUIRED, 'Prefix for URLs')
            ->addArgument('expand', InputArgument::REQUIRED, 'Which class to expand')
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $id = $input->getArgument('id');
        $name = $input->getArgument('name');
        $prefix = $input->getArgument('prefix');
        $expand = $input->getArgument('expand');

        // Update existing users.
        $provider = $this->providers->findOneBy(['id' => $id]);
        if (!$provider) {
            $provider = new Provider();
            $provider->setId($id);
        }

        $provider->setName($name);
        $provider->setPrefix($prefix);
        $provider->setExpand($expand);

        $this->entityManager->persist($provider);
        $this->entityManager->flush();

        $io->success('Provider added.');

        return Command::SUCCESS;
    }
}
