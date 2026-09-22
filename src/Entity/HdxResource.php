<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxResourceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxResourceRepository::class)]
#[ApiResource]
class HdxResource
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hdxResources')]
    private ?HdxDataset $dataset_ref = null;

    #[ORM\Column(length: 255)]
    private ?string $hdx_link = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $filename = null;

    #[ORM\Column(length: 255)]
    private ?string $mime_type = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $update_date = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_hxl = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $api_link = null;

    #[ORM\OneToMany(mappedBy: 'resource_ref', targetEntity: HdxPopulation::class)]
    private Collection $hdxPopulations;

    #[ORM\OneToMany(mappedBy: 'resource_ref', targetEntity: HdxOperationalPresence::class)]
    private Collection $hdxOperationalPresences;

    public function __construct()
    {
        $this->hdxPopulations = new ArrayCollection();
        $this->hdxOperationalPresences = new ArrayCollection();
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

    public function getDatasetRef(): ?HdxDataset
    {
        return $this->dataset_ref;
    }

    public function setDatasetRef(?HdxDataset $dataset_ref): static
    {
        $this->dataset_ref = $dataset_ref;

        return $this;
    }

    public function getHdxLink(): ?string
    {
        return $this->hdx_link;
    }

    public function setHdxLink(string $hdx_link): static
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

    public function getFilename(): ?string
    {
        return $this->filename;
    }

    public function setFilename(string $filename): static
    {
        $this->filename = $filename;

        return $this;
    }

    public function getMimeType(): ?string
    {
        return $this->mime_type;
    }

    public function setMimeType(string $mime_type): static
    {
        $this->mime_type = $mime_type;

        return $this;
    }

    public function getUpdateDate(): ?\DateTimeInterface
    {
        return $this->update_date;
    }

    public function setUpdateDate(\DateTimeInterface $update_date): static
    {
        $this->update_date = $update_date;

        return $this;
    }

    public function isIsHxl(): ?bool
    {
        return $this->is_hxl;
    }

    public function setIsHxl(?bool $is_hxl): static
    {
        $this->is_hxl = $is_hxl;

        return $this;
    }

    public function getApiLink(): ?string
    {
        return $this->api_link;
    }

    public function setApiLink(?string $api_link): static
    {
        $this->api_link = $api_link;

        return $this;
    }

    /**
     * @return Collection<int, HdxPopulation>
     */
    public function getHdxPopulations(): Collection
    {
        return $this->hdxPopulations;
    }

    public function addHdxPopulation(HdxPopulation $hdxPopulation): static
    {
        if (!$this->hdxPopulations->contains($hdxPopulation)) {
            $this->hdxPopulations->add($hdxPopulation);
            $hdxPopulation->setResourceRef($this);
        }

        return $this;
    }

    public function removeHdxPopulation(HdxPopulation $hdxPopulation): static
    {
        if ($this->hdxPopulations->removeElement($hdxPopulation)) {
            // set the owning side to null (unless already changed)
            if ($hdxPopulation->getResourceRef() === $this) {
                $hdxPopulation->setResourceRef(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, HdxOperationalPresence>
     */
    public function getHdxOperationalPresences(): Collection
    {
        return $this->hdxOperationalPresences;
    }

    public function addHdxOperationalPresence(HdxOperationalPresence $hdxOperationalPresence): static
    {
        if (!$this->hdxOperationalPresences->contains($hdxOperationalPresence)) {
            $this->hdxOperationalPresences->add($hdxOperationalPresence);
            $hdxOperationalPresence->setResourceRef($this);
        }

        return $this;
    }

    public function removeHdxOperationalPresence(HdxOperationalPresence $hdxOperationalPresence): static
    {
        if ($this->hdxOperationalPresences->removeElement($hdxOperationalPresence)) {
            // set the owning side to null (unless already changed)
            if ($hdxOperationalPresence->getResourceRef() === $this) {
                $hdxOperationalPresence->setResourceRef(null);
            }
        }

        return $this;
    }
}
