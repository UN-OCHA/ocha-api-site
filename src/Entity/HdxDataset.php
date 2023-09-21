<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxDatasetRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxDatasetRepository::class)]
#[ApiResource]
class HdxDataset
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hdx_link = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $title = null;

    #[ORM\Column(length: 255)]
    private ?string $provider_code = null;

    #[ORM\Column(length: 255)]
    private ?string $provider_name = null;

    #[ORM\Column(length: 255)]
    private ?string $api_link = null;

    #[ORM\OneToMany(mappedBy: 'dataset_ref', targetEntity: HdxResource::class)]
    private Collection $hdxResources;

    public function __construct()
    {
        $this->hdxResources = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getHdxLink(): ?string
    {
        return $this->hdx_link;
    }

    public function setHdxLink(?string $hdx_link): static
    {
        $this->hdx_link = $hdx_link;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getProviderCode(): ?string
    {
        return $this->provider_code;
    }

    public function setProviderCode(string $provider_code): static
    {
        $this->provider_code = $provider_code;

        return $this;
    }

    public function getProviderName(): ?string
    {
        return $this->provider_name;
    }

    public function setProviderName(string $provider_name): static
    {
        $this->provider_name = $provider_name;

        return $this;
    }

    public function getApiLink(): ?string
    {
        return $this->api_link;
    }

    public function setApiLink(string $api_link): static
    {
        $this->api_link = $api_link;

        return $this;
    }

    /**
     * @return Collection<int, HdxResource>
     */
    public function getHdxResources(): Collection
    {
        return $this->hdxResources;
    }

    public function addHdxResource(HdxResource $hdxResource): static
    {
        if (!$this->hdxResources->contains($hdxResource)) {
            $this->hdxResources->add($hdxResource);
            $hdxResource->setDatasetRef($this);
        }

        return $this;
    }

    public function removeHdxResource(HdxResource $hdxResource): static
    {
        if ($this->hdxResources->removeElement($hdxResource)) {
            // set the owning side to null (unless already changed)
            if ($hdxResource->getDatasetRef() === $this) {
                $hdxResource->setDatasetRef(null);
            }
        }

        return $this;
    }
}
