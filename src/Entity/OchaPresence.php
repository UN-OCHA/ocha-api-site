<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\OchaPresenceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource(
  normalizationContext: ['groups' => ['ochapresence_read']],
  denormalizationContext: ['groups' => ['ochapresence_write']],
)]
#[ORM\Entity(repositoryClass: OchaPresenceRepository::class)]
class OchaPresence
{
    #[ORM\Id]
    #[ORM\Column(length: 10)]
    #[Groups(['ochapresence_read', 'ochapresence_write'])]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ochapresence_read', 'ochapresence_write'])]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ochapresence_read', 'ochapresence_write'])]
    private ?string $officeType = null;

    #[ORM\OneToMany(mappedBy: 'ochaPresence', targetEntity: Country::class)]
    #[Groups(['ochapresence_read'])]
    private Collection $countries;

    public function __construct()
    {
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

}
