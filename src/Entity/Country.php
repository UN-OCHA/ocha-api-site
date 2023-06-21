<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\CountryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource()]
#[ORM\Entity(repositoryClass: CountryRepository::class)]
class Country
{
    #[ORM\Id]
    #[ORM\Column(length: 3)]
    #[ApiProperty(identifier: true)]
    #[Groups(['ochapresence_read'])]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ochapresence_read'])]
    private ?string $name = null;

    #[ORM\Column(length: 2)]
    private ?string $iso2 = null;

    #[ORM\Column(length: 3)]
    private ?string $iso3 = null;

    #[ORM\Column(length: 10)]
    private ?string $code = null;

    #[ORM\ManyToMany(targetEntity: OchaPresence::class, mappedBy: 'countries')]
    private Collection $ochaPresences;

    public function __construct()
    {
        $this->ochaPresences = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = strtolower($id);

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

    public function getIso2(): ?string
    {
        return $this->iso2;
    }

    public function setIso2(string $iso2): self
    {
        $this->iso2 = strtolower($iso2);

        return $this;
    }

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(string $iso3): self
    {
        $this->iso3 = strtolower($iso3);

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

    /**
     * @return Collection<int, OchaPresence>
     */
    public function getOchaPresences(): Collection
    {
        return $this->ochaPresences;
    }

    public function addOchaPresence(OchaPresence $ochaPresence): static
    {
        if (!$this->ochaPresences->contains($ochaPresence)) {
            $this->ochaPresences->add($ochaPresence);
            $ochaPresence->addCountry($this);
        }

        return $this;
    }

    public function removeOchaPresence(OchaPresence $ochaPresence): static
    {
        if ($this->ochaPresences->removeElement($ochaPresence)) {
            $ochaPresence->removeCountry($this);
        }

        return $this;
    }

}
