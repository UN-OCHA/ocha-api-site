<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OchaPresenceExternalIdRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;

#[ApiResource(
    security: "is_granted('ROLE_USER')",
    normalizationContext: ['groups' => ['ochapresence_external_read']],
    denormalizationContext: ['groups' => ['ochapresence_external_write']],
  )]
#[Get()]
#[GetCollection()]
#[Post(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')")]
#[Put(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')")]
#[Patch(
    inputFormats: [
        'jsonld' => ['application/merge-patch+json'],
    ],
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')"
)]
#[ORM\Entity(repositoryClass: OchaPresenceExternalIdRepository::class)]
class OchaPresenceExternalId
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'external_lookup_read'])]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'ochaPresenceExternalIds')]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'ochapresence_external_write'])]
    private ?OchaPresence $ochaPresence = null;

    #[ORM\ManyToOne()]
    #[ORM\JoinColumn(nullable: false)]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'ochapresence_external_write'])]
    private ?Provider $provider = null;

    #[ORM\Column(length: 4)]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'ochapresence_external_write'])]
    private ?string $year = null;

    #[ORM\ManyToMany(targetEntity: ExternalLookup::class, inversedBy: 'ochaPresenceExternalIds', cascade: ['persist'])]
    #[Groups(['ochapresence_read', 'ochapresence_external_read', 'ochapresence_external_write'])]
    private Collection $externalIds;

    public function __construct()
    {
        $this->externalIds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getOchaPresence(): ?OchaPresence
    {
        return $this->ochaPresence;
    }

    public function setOchaPresence(?OchaPresence $OchaPresence): self
    {
        $this->ochaPresence = $OchaPresence;

        return $this;
    }

    public function getProvider(): ?Provider
    {
        return $this->provider;
    }

    public function setProvider(?Provider $Provider): self
    {
        $this->provider = $Provider;

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
        return $this->externalIds->getValues();
    }

    public function addExternalId(ExternalLookup $externalId): self
    {
        if (!$this->externalIds->contains($externalId)) {
            $this->externalIds->add($externalId);
        }

        return $this;
    }

    public function removeExternalId(ExternalLookup $externalId): self
    {
        $this->externalIds->removeElement($externalId);

        return $this;
    }

}
