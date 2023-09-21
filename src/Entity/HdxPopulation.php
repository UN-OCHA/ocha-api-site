<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxPopulationRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxPopulationRepository::class)]
#[ApiResource]
class HdxPopulation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hdxPopulations')]
    private ?HdxResource $resource_ref = null;

    #[ORM\ManyToOne(inversedBy: 'hdxPopulations')]
    private ?HdxAdmin2 $admin2_ref = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $valid_date = null;

    #[ORM\ManyToOne(inversedBy: 'hdxPopulations')]
    private ?HdxGender $gender_ref = null;

    #[ORM\ManyToOne(inversedBy: 'hdxPopulations')]
    private ?HdxAgeRange $age_range_ref = null;

    #[ORM\Column]
    private ?int $population = null;

    #[ORM\Column(nullable: false)]
    private array $orig_data = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getResourceRef(): ?HdxResource
    {
        return $this->resource_ref;
    }

    public function setResourceRef(?HdxResource $resource_ref): static
    {
        $this->resource_ref = $resource_ref;

        return $this;
    }

    public function getAdmin2Ref(): ?HdxAdmin2
    {
        return $this->admin2_ref;
    }

    public function setAdmin2Ref(?HdxAdmin2 $admin2_ref): static
    {
        $this->admin2_ref = $admin2_ref;

        return $this;
    }

    public function getValidDate(): ?\DateTimeInterface
    {
        return $this->valid_date;
    }

    public function setValidDate(?\DateTimeInterface $valid_date): static
    {
        $this->valid_date = $valid_date;

        return $this;
    }

    public function getGenderRef(): ?HdxGender
    {
        return $this->gender_ref;
    }

    public function setGenderRef(?HdxGender $gender_ref): static
    {
        $this->gender_ref = $gender_ref;

        return $this;
    }

    public function getAgeRangeRef(): ?HdxAgeRange
    {
        return $this->age_range_ref;
    }

    public function setAgeRangeRef(?HdxAgeRange $age_range_ref): static
    {
        $this->age_range_ref = $age_range_ref;

        return $this;
    }

    public function getPopulation(): ?int
    {
        return $this->population;
    }

    public function setPopulation(int $population): static
    {
        $this->population = $population;

        return $this;
    }

    public function getOrigData(): array
    {
        return $this->orig_data;
    }

    public function setOrigData(?array $orig_data): static
    {
        $this->orig_data = $orig_data;

        return $this;
    }
}
