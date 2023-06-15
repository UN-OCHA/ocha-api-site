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

use App\Entity\User;
use App\Repository\UserRepository;
use App\Utils\Validator;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\Console\Attribute\AsCommand;
use Symfony\Component\Console\Command\Command;
use Symfony\Component\Console\Exception\RuntimeException;
use Symfony\Component\Console\Input\InputArgument;
use Symfony\Component\Console\Input\InputInterface;
use Symfony\Component\Console\Input\InputOption;
use Symfony\Component\Console\Output\OutputInterface;
use Symfony\Component\Console\Style\SymfonyStyle;
use Symfony\Component\PasswordHasher\Hasher\UserPasswordHasherInterface;
use Symfony\Component\Stopwatch\Stopwatch;

/**
 * A console command that creates users and stores them in the database.
 */
#[AsCommand(
    name: 'app:grant-access',
    description: 'Grant access to user'
)]
class UserGrantCommand extends Command
{
    private SymfonyStyle $io;

    public function __construct(
        private EntityManagerInterface $entityManager,
        private UserPasswordHasherInterface $passwordHasher,
        private Validator $validator,
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
            ->addArgument('username', InputArgument::OPTIONAL, 'The username of the new user')
            ->addArgument('can-read', InputArgument::OPTIONAL, 'Grant read access')
            ->addArgument('can-write', InputArgument::OPTIONAL, 'Grant write access')
            ->addArgument('roles', InputArgument::OPTIONAL, 'Roles')
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
        $username = $input->getArgument('username');
        $can_read = $input->getArgument('can-read') ?? '';
        $can_write = $input->getArgument('can-write') ?? '';
        $new_roles = $input->getArgument('roles') ?? '';

        // Update existing users.
        $user = $this->users->findOneBy(['username' => $username]);
        if (!$user) {
            return Command::FAILURE;
        }

        $read = $user->getCanRead();
        $this->io->info($read);
        $read = array_merge($read, explode(',', $can_read));
        array_unique($read);

        $write = $user->getCanRead();
        $write = array_merge($write, explode(',', $can_write));
        array_unique($write);

        $roles = $user->getRoles();
        $roles = array_merge($roles, explode(',', $new_roles));
        array_unique($roles);

        $user->setCanRead($read);
        $user->setCanWrite($write);
        $user->setRoles($roles);

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully updated', $user->getUsername()));

        return Command::SUCCESS;
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
            The <info>%command.name%</info> allows you to add read/write grants and roles:

              <info>php %command.full_name%</info> <comment>username read write roles</comment>

            HELP;
    }
}
