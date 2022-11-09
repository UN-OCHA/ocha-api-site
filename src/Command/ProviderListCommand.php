<?php

namespace App\Command;

use App\Repository\ProviderRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

#[AsCommand(
    name: 'app:list-provider',
    description: 'Add a short description for your command',
)]
class ProviderListCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private ProviderRepository $providers
    ) {
        parent::__construct();
    }

    /**
     * {@inheritdoc}
     */
    protected function configure(): void
    {
        $this
            ->setHelp($this->getCommandHelp())
        ;
    }

    /**
     * This optional method is the first one executed for a command after configure()
     * and is useful to initialize properties based on the input arguments and options.
     */
    protected function initialize(InputInterface $input, OutputInterface $output): void
    {
        // SymfonyStyle is an optional feature that Symfony provides so you can
        // apply a consistent look to the commands of your application.
        // See https://symfony.com/doc/current/console/style.html
        $this->io = new SymfonyStyle($input, $output);
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $providers = $this->providers->findAll();

        $table = new Table($output);
        $table->setHeaders(['Id', 'Name', 'Prefix', 'Expand']);

        foreach ($providers as $provider) {
            /** @var \App\Entity\User $user */
            $table->addRow([
                $provider->getId(),
                $provider->getName(),
                $provider->getPrefix(),
                $provider->getExpand(),
            ]);
        }
        $table->render();

        return Command::SUCCESS;
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return 'The <info>%command.name%</info> command list providers in the database';
    }
}
