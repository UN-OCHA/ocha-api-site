<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OchaPresenceExternalIdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ApiResource()]
#[ORM\Entity(repositoryClass: OchaPresenceExternalIdRepository::class)]
class OchaPresenceExternalId
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ochaPresenceExternalIds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?OchaPresence $OchaPresence = null;

    #[ORM\ManyToOne(inversedBy: 'ochaPresenceExternalIds')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Provider $Provider = null;

    #[ORM\Column(length: 4)]
    private ?string $year = null;

    #[ORM\ManyToMany(targetEntity: ExternalLookup::class, inversedBy: 'ochaPresenceExternalIds')]
    private Collection $ExternalIds;

    public function __construct()
    {
        $this->ExternalIds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOchaPresence(): ?OchaPresence
    {
        return $this->OchaPresence;
    }

    public function setOchaPresence(?OchaPresence $OchaPresence): self
    {
        $this->OchaPresence = $OchaPresence;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->Provider;
    }

    public function setProvider(?Provider $Provider): self
    {
        $this->Provider = $Provider;

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

    /**
     * @return Collection<int, ExternalLookup>
     */
    public function getExternalIds(): Collection
    {
        return $this->ExternalIds;
    }

    public function addExternalId(ExternalLookup $externalId): self
    {
        if (!$this->ExternalIds->contains($externalId)) {
            $this->ExternalIds->add($externalId);
        }

        return $this;
    }

    public function removeExternalId(ExternalLookup $externalId): self
    {
        $this->ExternalIds->removeElement($externalId);

        return $this;
    }

}
