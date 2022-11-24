<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\N8nWorkflowController;
use App\Controller\N8nWorkflowsController;
use App\Repository\N8nWorkflowRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['workflow']],
    operations: [
        // Get.
        new Get(
            uriTemplate: '/n8n/templates/workflows/{id}',
            controller: N8nWorkflowController::class,
            read: false,
            openapiContext: [
                'summary' => 'Get an n8n workflow',
                'description' => 'Get an n8n workflow',
                'tags' => [
                    'n8n',
                ],
            ]
        ),
        new Get(
            uriTemplate: '/n8n/workflows/templates/{id}',
            controller: N8nWorkflowController::class,
            read: false,
            openapiContext: [
                'summary' => 'Get an n8n workflow',
                'description' => 'Get an n8n workflow',
                'tags' => [
                    'n8n',
                ],
            ]
        ),
        // Get.
        new GetCollection(
            uriTemplate: '/n8n/templates/workflows',
            controller: N8nWorkflowsController::class,
            read: false,
            openapiContext: [
                'summary' => 'Get n8n workflows',
                'description' => 'Get n8n workflows',
                'tags' => [
                    'n8n',
                ],
            ]
        ),
    ]
)]
#[ORM\Entity(repositoryClass: N8nWorkflowRepository::class)]
class N8nWorkflow
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['workflow'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['workflow'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['workflow'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['workflow'])]
    private array $workflow = [];

    #[ORM\Column(nullable: true)]
    #[Groups(['workflow'])]
    private array $user = [];

    #[ORM\Column(nullable: true)]
    #[Groups(['workflow'])]
    private array $nodes = [];

    #[ORM\ManyToMany(targetEntity: N8nCategory::class, inversedBy: 'workflows')]
    #[Groups(['workflow'])]
    private Collection $categories;

    #[ORM\ManyToMany(targetEntity: N8nCollection::class, mappedBy: 'workflows')]
    private Collection $collections;

    #[ORM\Column(nullable: true)]
    #[Groups(['workflow'])]
    private ?\DateTimeImmutable $createdAt = null;

    public function __construct()
    {
        $this->categories = new ArrayCollection();
        $this->collections = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getWorkflow(): array
    {
        return $this->workflow;
    }

    public function setWorkflow(?array $workflow): self
    {
        $this->workflow = $workflow;

        return $this;
    }

    public function getUser(): array
    {
        return $this->user;
    }

    public function setUser(?array $user): self
    {
        $this->user = $user;

        return $this;
    }

    public function getNodes(): array
    {
        return $this->nodes;
    }

    public function setNodes(?array $nodes): self
    {
        $this->nodes = $nodes;

        return $this;
    }

    /**
     * @return Collection<int, N8nCategory>
     */
    public function getCategories(): Collection
    {
        return $this->categories;
    }

    public function addCategory(N8nCategory $category): self
    {
        if (!$this->categories->contains($category)) {
            $this->categories->add($category);
        }

        return $this;
    }

    public function removeCategory(N8nCategory $category): self
    {
        $this->categories->removeElement($category);

        return $this;
    }

    /**
     * @return Collection<int, N8nCollection>
     */
    public function getCollections(): Collection
    {
        return $this->collections;
    }

    public function addCollection(N8nCollection $collection): self
    {
        if (!$this->collections->contains($collection)) {
            $this->collections->add($collection);
            $collection->addWorkflow($this);
        }

        return $this;
    }

    public function removeCollection(N8nCollection $collection): self
    {
        if ($this->collections->removeElement($collection)) {
            $collection->removeWorkflow($this);
        }

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(?\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }
}
