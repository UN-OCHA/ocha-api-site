<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\FtsKeyFiguresRepository;
use App\State\FtsKeyFiguresYearStateProvider;
use App\State\FtsKeyFiguresIso3StateProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FtsKeyFiguresRepository::class)]
#[ApiResource(
    operations: [
        new Get(
            uriTemplate: '/fts/{id}'
        ),
        new GetCollection(
            uriTemplate: '/fts'
        ),
        new GetCollection(
            uriTemplate: '/fts/country/{iso3}',
            provider: FtsKeyFiguresIso3StateProvider::class,
            uriVariables: ['iso3']
        ),
        new GetCollection(
            uriTemplate: '/fts/year/{year}',
            provider: FtsKeyFiguresYearStateProvider::class,
            uriVariables: ['year']
        )
    ]
)]

class FtsKeyFigures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $planId = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $updated = null;

    #[ORM\Column]
    private ?int $year = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $iso3 = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $originalRequirements = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $revisedRequirements = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $totalRequirements = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $fundingTotal = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getPlanId(): ?int
    {
        return $this->planId;
    }

    public function setPlanId(int $planId): self
    {
        $this->planId = $planId;

        return $this;
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

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): self
    {
        $this->code = $code;

        return $this;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(string $iso3): self
    {
        $this->iso3 = $iso3;

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getOriginalRequirements(): ?string
    {
        return $this->originalRequirements;
    }

    public function setOriginalRequirements(string $originalRequirements): self
    {
        $this->originalRequirements = $originalRequirements;

        return $this;
    }

    public function getRevisedRequirements(): ?string
    {
        return $this->revisedRequirements;
    }

    public function setRevisedRequirements(string $revisedRequirements): self
    {
        $this->revisedRequirements = $revisedRequirements;

        return $this;
    }

    public function getTotalRequirements(): ?string
    {
        return $this->totalRequirements;
    }

    public function setTotalRequirements(string $totalRequirements): self
    {
        $this->totalRequirements = $totalRequirements;

        return $this;
    }

    public function getFundingTotal(): ?string
    {
        return $this->fundingTotal;
    }

    public function setFundingTotal(string $fundingTotal): self
    {
        $this->fundingTotal = $fundingTotal;

        return $this;
    }
}
