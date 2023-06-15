<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use App\Repository\OchaPresenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  security: "is_granted('ROLE_USER')",
  normalizationContext: ['groups' => ['ochapresence_read']],
  denormalizationContext: ['groups' => ['ochapresence_write']],
)]
#[Get()]
#[GetCollection()]
#[Post(security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_OCHA_PRESENCE')")]
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

    #[ORM\OneToMany(mappedBy: 'ochaPresence', targetEntity: Country::class, cascade: ['persist'])]
    #[Groups(['ochapresence_read', 'ochapresence_write'])]
    private Collection $countries;

    #[ORM\OneToMany(mappedBy: 'ochaPresence', targetEntity: OchaPresenceExternalId::class, cascade: ['persist'])]
    #[Groups(['ochapresence_read', 'ochapresence_write'])]
    private Collection $ochaPresenceExternalIds;

    public function __construct()
    {
        $this->countries = new ArrayCollection();
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

    public function getCountries()
    {
        return $this->countries->getValues();
    }

    public function addCountry(Country $country): self
    {
        if (!$this->countries->contains($country)) {
            $this->countries->add($country);
            $country->setOchaPresence($this);
        }

        return $this;
    }

    public function removeCountry(Country $country): self
    {
        if ($this->countries->removeElement($country)) {
            // set the owning side to null (unless already changed)
            if ($country->getOchaPresence() === $this) {
                $country->setOchaPresence(null);
            }
        }

        return $this;
    }

    public function getOchaPresenceExternalIds()
    {
        return $this->ochaPresenceExternalIds->getValues();
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

}
