<?php

/*
 * This file is part of the Symfony package.
 *
 * (c) Fabien Potencier <fabien@symfony.com>
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace App\Command;

use App\Repository\UserRepository;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Helper\Table;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;

/**
 * A console command that list users.
 */
#[AsCommand(
    name: 'app:list-user',
    description: 'List users'
)]
class UserListCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private UserRepository $users
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
        $users = $this->users->findAll();

        $table = new Table($output);
        $table->setHeaders([
          'Username',
          'Email',
          'Roles',
          'Token',
          'Read',
          'Write',
          'Webhook',
        ]);

        foreach ($users as $user) {
            /** @var \App\Entity\User $user */
            $table->addRow([
                $user->getUsername(),
                $user->getEmail(),
                implode(', ', $user->getRoles()),
                $user->getToken(),
                implode(', ', $user->getCanRead()),
                implode(', ', $user->getCanWrite()),
                $user->getWebhook(),
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
        return 'The <info>%command.name%</info> command list users in the database';
    }
}
