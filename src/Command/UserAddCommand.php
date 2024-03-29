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
    name: 'app:add-user',
    description: 'Creates users and stores them in the database'
)]
class UserAddCommand extends Command
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
            ->addArgument('email', InputArgument::OPTIONAL, 'The email of the new user')
            ->addArgument('full-name', InputArgument::OPTIONAL, 'The full name of the new user')
            ->addOption('can-read', '', InputArgument::OPTIONAL, 'Grant read access')
            ->addOption('can-write', '', InputArgument::OPTIONAL, 'Grant write access')
            ->addOption('webhook', '', InputArgument::OPTIONAL, 'Webhook')
            ->addOption('token', '', InputArgument::OPTIONAL, 'Token')
            ->addOption('admin', null, InputOption::VALUE_NONE, 'If set, the user is created as an administrator')
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
     * This method is executed after initialize() and before execute(). Its purpose
     * is to check if some of the options/arguments are missing and interactively
     * ask the user for those values.
     *
     * This method is completely optional. If you are developing an internal console
     * command, you probably should not implement this method because it requires
     * quite a lot of work. However, if the command is meant to be used by external
     * users, this method is a nice way to fall back and prevent errors.
     */
    protected function interact(InputInterface $input, OutputInterface $output): void
    {
        if (null !== $input->getArgument('username') && null !== $input->getArgument('email') && null !== $input->getArgument('full-name')) {
            return;
        }

        $this->io->title('Add User Command Interactive Wizard');
        $this->io->text([
            'If you prefer to not use this interactive wizard, provide the',
            'arguments required by this command as follows:',
            '',
            ' $ php bin/console app:add-user username email@example.com fullname',
            '',
            'Now we\'ll ask you for the value of all the missing command arguments.',
        ]);

        // Ask for the username if it's not defined
        $username = $input->getArgument('username');
        if (null !== $username) {
            $this->io->text(' > <info>Username</info>: '.$username);
        } else {
            $username = $this->io->ask('Username', null, [$this->validator, 'validateUsername']);
            $input->setArgument('username', $username);
        }

        // Ask for the email if it's not defined
        $email = $input->getArgument('email');
        if (null !== $email) {
            $this->io->text(' > <info>Email</info>: '.$email);
        } else {
            $email = $this->io->ask('Email', null, [$this->validator, 'validateEmail']);
            $input->setArgument('email', $email);
        }

        // Ask for the full name if it's not defined
        $fullName = $input->getArgument('full-name');
        if (null !== $fullName) {
            $this->io->text(' > <info>Full Name</info>: '.$fullName);
        } else {
            $fullName = $this->io->ask('Full Name', null, [$this->validator, 'validateFullName']);
            $input->setArgument('full-name', $fullName);
        }

        $canRead = $input->getArgument('can-read');
        if (null !== $canRead) {
            $this->io->text(' > <info>Comma separated list of providers</info>: '.$canRead);
        } else {
            $canRead = $this->io->ask('Can read', null);
            $input->setArgument('can-read', $canRead);
        }

        $canWrite = $input->getArgument('can-write');
        if (null !== $canWrite) {
            $this->io->text(' > <info>Comma separated list of providers</info>: '.$canWrite);
        } else {
            $canWrite = $this->io->ask('Can write', null);
            $input->setArgument('can-write', $canWrite);
        }

        $webhook = $input->getArgument('webhook');
        if (null !== $webhook) {
            $this->io->text(' > <info>Webhook</info>: '.$webhook);
        } else {
            $webhook = $this->io->ask('Webhook', null);
            $input->setArgument('webhook', $webhook);
        }
    }

    /**
     * This method is executed after interact() and initialize(). It usually
     * contains the logic to execute to complete this command task.
     */
    protected function execute(InputInterface $input, OutputInterface $output): int
    {
        $stopwatch = new Stopwatch();
        $stopwatch->start('add-user-command');

        $plainPassword = bin2hex(random_bytes(32));
        $username = $input->getArgument('username');
        $email = $input->getArgument('email');
        $fullName = $input->getArgument('full-name');

        $can_read = $input->getOption('can-read') ?? '';
        $can_write = $input->getOption('can-write') ?? '';
        $webhook = $input->getOption('webhook') ?? '';
        $token = $input->getOption('token') ?? '';

        $isAdmin = $input->getOption('admin');

        $roles = [
            'ROLE_USER',
        ];

        if ($isAdmin) {
            $roles[] = 'ROLE_ADMIN';
        }

        // Update existing users.
        $user = $this->users->findOneBy(['username' => $username]);
        if (!$user) {
            // make sure to validate the user data is correct
            $this->validateUserData($username, $email, $fullName);

            // create the user and hash its password
            $user = new User();
            $user->setFullName($fullName);
            $user->setUsername($username);
            $user->setEmail($email);
            $user->setToken(bin2hex(random_bytes(16)));

            // See https://symfony.com/doc/5.4/security.html#registering-the-user-hashing-passwords
            $hashedPassword = $this->passwordHasher->hashPassword($user, $plainPassword);
            $user->setPassword($hashedPassword);
        }

        $user->setRoles($roles);
        $user->setProviders([]);
        $user->setCanRead(explode(',', $can_read));
        $user->setCanWrite(explode(',', $can_write));

        if (!empty($webhook)) {
            $user->setWebhook($webhook);
        }

        if (!empty($token)) {
            $user->setToken($token);
        }

        $this->entityManager->persist($user);
        $this->entityManager->flush();

        $this->io->success(sprintf('%s was successfully created: %s (%s)', $isAdmin ? 'Administrator user' : 'User', $user->getUsername(), $user->getEmail()));

        $event = $stopwatch->stop('add-user-command');
        if ($output->isVerbose()) {
            $this->io->comment(sprintf('New user database id: %d / Elapsed time: %.2f ms / Consumed memory: %.2f MB', $user->getId(), $event->getDuration(), $event->getMemory() / (1024 ** 2)));
        }

        $this->io->comment(sprintf('New user database id: %d / Token: %s', $user->getId(), $user->getToken()));
        return Command::SUCCESS;
    }

    private function validateUserData($username, $email, $fullName): void
    {
        // first check if a user with the same username already exists.
        $existingUser = $this->users->findOneBy(['username' => $username]);

        if (null !== $existingUser) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" username.', $username));
        }

        // validate email and fullname if is not this input means interactive.
        $this->validator->validateEmail($email);
        $this->validator->validateFullName($fullName);

        // check if a user with the same email already exists.
        $existingEmail = $this->users->findOneBy(['email' => $email]);

        if (null !== $existingEmail) {
            throw new RuntimeException(sprintf('There is already a user registered with the "%s" email.', $email));
        }
    }

    /**
     * The command help is usually included in the configure() method, but when
     * it's too long, it's better to define a separate method to maintain the
     * code readability.
     */
    private function getCommandHelp(): string
    {
        return <<<'HELP'
            The <info>%command.name%</info> command creates new users and saves them in the database:

              <info>php %command.full_name%</info> <comment>username email fullname</comment>

            By default the command creates regular users. To create administrator users,
            add the <comment>--admin</comment> option:

              <info>php %command.full_name%</info> username email fullname<comment>--admin</comment>

            HELP;
    }
}
