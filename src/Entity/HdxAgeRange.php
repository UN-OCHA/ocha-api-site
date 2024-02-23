<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxAgeRangeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxAgeRangeRepository::class)]
#[ApiResource]
class HdxAgeRange
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'age_range_ref', targetEntity: HdxPopulation::class)]
    private Collection $hdxPopulations;

    public function __construct()
    {
        $this->hdxPopulations = new ArrayCollection();
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

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

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
            $hdxPopulation->setAgeRangeRef($this);
        }

        return $this;
    }

    public function removeHdxPopulation(HdxPopulation $hdxPopulation): static
    {
        if ($this->hdxPopulations->removeElement($hdxPopulation)) {
            // set the owning side to null (unless already changed)
            if ($hdxPopulation->getAgeRangeRef() === $this) {
                $hdxPopulation->setAgeRangeRef(null);
            }
        }

        return $this;
    }
}
