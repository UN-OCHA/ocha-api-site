<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use App\Controller\OchaPresenceReplaceExternalIdsController;
use App\Dto\BatchResponses;
use App\Repository\OchaPresenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use NetBrothers\VersionBundle\Traits\VersionColumn;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  security: "is_granted('ROLE_USER')",
  normalizationContext: ['groups' => ['ochapresence_read']],
  denormalizationContext: ['groups' => ['ochapresence_write']],
  extraProperties: [
    'standard_put' => TRUE,
  ],
)]
#[Get()]
#[GetCollection()]
#[Post(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')")]
#[Post(
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')",
    uriTemplate: '/ocha_presences/{id}/external_ids',
    controller: OchaPresenceReplaceExternalIdsController::class,
    input: OchaPresence::class,
    output: BatchResponses::class,
    openapi: new OpenApiOperation(
      summary: 'Adds and replaces external ids for a given provider and year',
      description: 'Adds and replaces external ids for a given provider and year',
      tags: [
          'OchaPresence',
      ],
    ),
)]
#[Put(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')")]
#[Delete(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')")]
#[Patch(
    inputFormats: [
        'jsonld' => ['application/merge-patch+json'],
    ],
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')"
)]
#[ORM\Entity(repositoryClass: OchaPresenceRepository::class)]
class OchaPresence
{
    use VersionColumn;

    #[ORM\Id]
    #[ORM\Column(length: 10)]
    #[Groups(['ochapresence_read', 'ochapresence_write', 'ochapresence_external_read'])]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ochapresence_read', 'ochapresence_write', 'ochapresence_external_read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ochapresence_read', 'ochapresence_write'])]
    private ?string $officeType = null;

    #[ORM\OneToMany(mappedBy: 'ochaPresence', targetEntity: OchaPresenceExternalId::class, cascade: ['persist', 'remove'])]
    #[Groups(['ochapresence_read', 'ochapresence_write'])]
    private Collection $ochaPresenceExternalIds;

    #[ORM\ManyToMany(targetEntity: Country::class, inversedBy: 'ochaPresences')]
    #[Groups(['ochapresence_read', 'ochapresence_write'])]
    private Collection $countries;

    public function __construct()
    {
        $this->ochaPresenceExternalIds = new ArrayCollection();
        $this->countries = new ArrayCollection();
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

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): self
    {
        $this->name = $name;

        return $this;
    }

    public function getOfficeType(): ?string
    {
        return $this->officeType;
    }

    public function setOfficeType(string $officeType): self
    {
        $this->officeType = $officeType;

        return $this;
    }

    public function getOchaPresenceExternalIds()
    {
        return $this->ochaPresenceExternalIds->getValues();
    }

    /**
     * Only one should ever exist.
     */
    public function getOchaPresenceExternalIdByProviderAndYear(string $provider, string $year) : OchaPresenceExternalId|null
    {
        /** @var \App\Entity\OchaPresenceExternalId[] $external_ids */
        $external_ids = $this->ochaPresenceExternalIds->getValues();

        foreach ($external_ids as $external_id) {
            /** @var \App\Entity\ExternalLookup $external */
            foreach ($external_id->getExternalIds() as $external) {
                if ($external->getProvider() == $provider && $external_id->getYear() == $year) {
                    return $external_id;
                }
            }
        }

        return NULL;
    }

    public function addOchaPresenceExternalId(OchaPresenceExternalId $ochaPresenceExternalId): self
    {
        if (!$this->ochaPresenceExternalIds->contains($ochaPresenceExternalId)) {
            $this->ochaPresenceExternalIds->add($ochaPresenceExternalId);
            $ochaPresenceExternalId->setOchaPresence($this);
        }

        return $this;
    }

    public function removeOchaPresenceExternalId(OchaPresenceExternalId $ochaPresenceExternalId): self
    {
        if ($this->ochaPresenceExternalIds->removeElement($ochaPresenceExternalId)) {
            // set the owning side to null (unless already changed)
            if ($ochaPresenceExternalId->getOchaPresence() === $this) {
                $ochaPresenceExternalId->setOchaPresence(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Country>
     */
    public function getCountries(): Collection
    {
        return $this->countries;
    }

    public function addCountry(Country $country): static
    {
        if (!$this->countries->contains($country)) {
            $this->countries->add($country);
        }

        return $this;
    }

    public function removeCountry(Country $country): static
    {
        $this->countries->removeElement($country);

        return $this;
    }

}
