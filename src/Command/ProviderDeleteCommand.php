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
    name: 'app:delete-provider',
    description: 'Delete provider',
)]
class ProviderDeleteCommand extends Command
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
        ;
    }

    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $io = new SymfonyStyle($input, $output);

        $id = $input->getArgument('id');

        // Update existing users.
        $provider = $this->providers->findOneBy(['id' => $id]);
        if (!$provider) {
          return Command::FAILURE;
        }

        $this->entityManager->remove($provider);
        $this->entityManager->flush();

        $io->success('Provider deleted.');

        return Command::SUCCESS;
    }
}
