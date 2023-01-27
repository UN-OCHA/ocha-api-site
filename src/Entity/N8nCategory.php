<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use App\Controller\N8nCategoriesController;
use App\Repository\N8nCategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    operations: [
        // Get.
        new Get(
            uriTemplate: '/n8n/templates/categories/{id}',
            openapi: new OpenApiOperation(
              summary: 'Get an n8n category',
              description: 'Get an n8n category',
              tags: [
                  'n8n',
              ],
            ),
        ),
        // Get.
        new GetCollection(
            uriTemplate: '/n8n/templates/categories',
            controller: N8nCategoriesController::class,
            read: false,
            openapi: new OpenApiOperation(
              summary: 'Get an n8n categories',
              description: 'Get an n8n categories',
              tags: [
                  'n8n',
              ],
            ),
        ),
    ]
)]
#[ORM\Entity(repositoryClass: N8nCategoryRepository::class)]
class N8nCategory
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['workflow'])]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['workflow'])]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: N8nWorkflow::class, mappedBy: 'categories')]
    private Collection $workflows;

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
            $workflow->addCategory($this);
        }

        return $this;
    }

    public function removeWorkflow(N8nWorkflow $workflow): self
    {
        if ($this->workflows->removeElement($workflow)) {
            $workflow->removeCategory($this);
        }

        return $this;
    }
}
