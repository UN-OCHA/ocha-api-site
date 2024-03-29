<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use App\Controller\MePatchController;
use App\Repository\UserRepository;
use App\State\User\MeProvidersStateProvider;
use App\State\User\MeStateProvider;
use App\State\User\RegisterStateProvider;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[ApiResource(
    normalizationContext: ['groups' => ['read']],
    denormalizationContext: ['groups' => ['write']],
    cacheHeaders: [
        'max_age' => 60,
        'vary' => ['Authorization']
    ],
    operations: [
        // Me.
        new Get(
            security: "is_granted('ROLE_USER')",
            uriTemplate: '/me',
            provider: MeStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Get profile',
              description: 'Get profile',
              tags: [
                  'User',
              ],
            ),
        ),
        new Patch(
          securityPostDenormalize: "is_granted('ROLE_USER')",
          controller: MePatchController::class,
          uriTemplate: '/me',
          denormalizationContext: [
              'groups' => ['write'],
          ],
          openapi: new OpenApiOperation(
            summary: 'Update user info',
            description: 'Update user info',
            tags: [
                'User',
            ],
          ),
        ),
        new Get(
            security: "is_granted('ROLE_USER')",
            uriTemplate: '/me/providers',
            provider: MeProvidersStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Get list of providers',
              description: 'Get list of providers',
              tags: [
                  'User',
              ],
            ),
        ),
        new Post(
            uriTemplate: '/register',
            processor: RegisterStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Create an account',
              description: 'Create an account',
              tags: [
                  'User',
              ],
            ),
        ),
    ]
)]

class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['read'])]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Groups(['read', 'write'])]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $username = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $fullName = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read'])]
    private ?string $token = null;

    #[ORM\Column(nullable: true)]
    private array $providers = [];

    #[ORM\Column(nullable: true)]
    #[Groups(['read'])]
    private array $canRead = [];

    #[ORM\Column(nullable: true)]
    #[Groups(['read'])]
    private array $canWrite = [];

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['read', 'write'])]
    private ?string $webhook = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(?string $username): self
    {
        $this->username = $username;

        return $this;
    }

    public function getFullName(): ?string
    {
        return $this->fullName;
    }

    public function setFullName(?string $fullName): self
    {
        $this->fullName = $fullName;

        return $this;
    }

    public function getToken(): ?string
    {
        return $this->token;
    }

    public function setToken(?string $token): self
    {
        $this->token = $token;

        return $this;
    }

    public function getProviders(): array
    {
        return $this->providers;
    }

    public function setProviders(?array $providers): self
    {
        $this->providers = $providers;

        return $this;
    }

    public function getCanRead(): array
    {
        return $this->canRead;
    }

    public function setCanRead(?array $canRead): self
    {
        $this->canRead = $canRead;

        return $this;
    }

    public function getCanWrite(): array
    {
        return $this->canWrite;
    }

    public function setCanWrite(?array $canWrite): self
    {
        $this->canWrite = $canWrite;

        return $this;
    }

    public function getWebhook(): ?string
    {
        return $this->webhook;
    }

    public function setWebhook(?string $webhook): self
    {
        $this->webhook = $webhook;

        return $this;
    }

}
