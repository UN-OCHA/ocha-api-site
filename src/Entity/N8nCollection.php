<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Controller\N8nCollectionsController;
use App\Repository\N8nCollectionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource(
    operations: [
        // Get.
        new Get(
            uriTemplate: '/n8n/templates/collections/{id}',
            openapiContext: [
                'summary' => 'Get an n8n collection',
                'description' => 'Get an n8n collection',
                'tags' => [
                    'n8n',
                ],
            ]
        ),
        // Get.
        new GetCollection(
            uriTemplate: '/n8n/templates/collections',
            controller: N8nCollectionsController::class,
            read: false,
            openapiContext: [
                'summary' => 'Get n8n collections',
                'description' => 'Get n8n collections',
                'tags' => [
                    'n8n',
                ],
            ]
        ),
    ]
)]
#[ORM\Entity(repositoryClass: N8nCollectionRepository::class)]
class N8nCollection
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: N8nWorkflow::class, inversedBy: 'collections')]
    private Collection $workflows;

    #[ORM\Column(nullable: true)]
    private array $nodes = [];

    public function __construct()
    {
        $this->workflows = new ArrayCollection();
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

    /**
     * @return Collection<int, N8nWorkflow>
     */
    public function getWorkflows(): Collection
    {
        return $this->workflows;
    }

    public function addWorkflow(N8nWorkflow $workflow): self
    {
        if (!$this->workflows->contains($workflow)) {
            $this->workflows->add($workflow);
        }

        return $this;
    }

    public function removeWorkflow(N8nWorkflow $workflow): self
    {
        $this->workflows->removeElement($workflow);

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
}
