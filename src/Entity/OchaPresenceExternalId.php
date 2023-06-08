<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OchaPresenceExternalIdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
    normalizationContext: ['groups' => ['ochapresence_external_read']],
    denormalizationContext: ['groups' => ['ochapresence_external_write']],
  )]
#[ORM\Entity(repositoryClass: OchaPresenceExternalIdRepository::class)]
class OchaPresenceExternalId
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ochapresence_read', 'ochapresence_external_read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ochaPresenceExternalIds', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'ochapresence_external_write'])]
    private ?OchaPresence $OchaPresence = null;

    #[ORM\ManyToOne(inversedBy: 'ochaPresenceExternalIds', cascade: ['persist'])]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'ochapresence_external_write'])]
    private ?Provider $Provider = null;

    #[ORM\Column(length: 4)]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'ochapresence_external_write'])]
    private ?string $year = null;

    #[ORM\ManyToMany(targetEntity: ExternalLookup::class, inversedBy: 'ochaPresenceExternalIds', cascade: ['persist'])]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'ochapresence_external_write'])]
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

    public function getExternalIds()
    {
        return $this->ExternalIds->getValues();
    }

    public function setExternalIds(array $external_ids): self
    {
        $this->ExternalIds = $external_ids;

        return $this;
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
