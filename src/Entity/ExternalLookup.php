<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\ExternalLookupRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ApiFilter(SearchFilter::class, properties: ['provider' => 'exact', 'iso3' => 'exact', 'year' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['name' => 'ASC'])]
#[ORM\Entity(repositoryClass: ExternalLookupRepository::class)]
class ExternalLookup
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $provider = null;

    #[ORM\Column(length: 4)]
    private ?string $year = null;

    #[ORM\Column(length: 3)]
    private ?string $iso3 = null;

    #[ORM\Column]
    private ?string $external_id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToMany(targetEntity: OchaPresenceExternalId::class, mappedBy: 'ExternalIds')]
    private Collection $ochaPresenceExternalIds;

    public function __construct()
    {
        $this->ochaPresenceExternalIds = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

        return $this;
    }

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

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

    public function getExternalId(): ?string
    {
        return $this->external_id;
    }

    public function setExternalId(string $external_id): self
    {
        $this->external_id = $external_id;

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

    /**
     * @return Collection<int, OchaPresenceExternalId>
     */
    public function getOchaPresenceExternalIds(): Collection
    {
        return $this->ochaPresenceExternalIds;
    }

    public function addOchaPresenceExternalId(OchaPresenceExternalId $ochaPresenceExternalId): self
    {
        if (!$this->ochaPresenceExternalIds->contains($ochaPresenceExternalId)) {
            $this->ochaPresenceExternalIds->add($ochaPresenceExternalId);
            $ochaPresenceExternalId->addExternalId($this);
        }

        return $this;
    }

    public function removeOchaPresenceExternalId(OchaPresenceExternalId $ochaPresenceExternalId): self
    {
        if ($this->ochaPresenceExternalIds->removeElement($ochaPresenceExternalId)) {
            $ochaPresenceExternalId->removeExternalId($this);
        }

        return $this;
    }

}
