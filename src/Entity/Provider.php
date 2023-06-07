<?php

namespace App\Entity;

use App\Repository\ProviderRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ProviderRepository::class)]
class Provider
{
    #[ORM\Id]
    #[ORM\Column]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prefix = null;

    #[ORM\Column(length: 255)]
    private ?string $expand = null;

    #[ORM\OneToMany(mappedBy: 'provider', targetEntity: OchaPresence::class)]
    private Collection $ochaPresences;

    #[ORM\OneToMany(mappedBy: 'Provider', targetEntity: OchaPresenceExternalId::class)]
    private Collection $ochaPresenceExternalIds;

    public function __construct()
    {
        $this->ochaPresences = new ArrayCollection();
        $this->ochaPresenceExternalIds = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): ?self
    {
        $this->id = $id;

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;

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

    public function getExpand(): ?string
    {
        return $this->expand;
    }

    public function setExpand(string $expand): self
    {
        $this->expand = $expand;

        return $this;
    }

    /**
     * @return Collection<int, OchaPresence>
     */
    public function getOchaPresences(): Collection
    {
        return $this->ochaPresences;
    }

    public function addOchaPresence(OchaPresence $ochaPresence): self
    {
        if (!$this->ochaPresences->contains($ochaPresence)) {
            $this->ochaPresences->add($ochaPresence);
            $ochaPresence->setProvider($this);
        }

        return $this;
    }

    public function removeOchaPresence(OchaPresence $ochaPresence): self
    {
        if ($this->ochaPresences->removeElement($ochaPresence)) {
            // set the owning side to null (unless already changed)
            if ($ochaPresence->getProvider() === $this) {
                $ochaPresence->setProvider(null);
            }
        }

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
            $ochaPresenceExternalId->setProvider($this);
        }

        return $this;
    }

    public function removeOchaPresenceExternalId(OchaPresenceExternalId $ochaPresenceExternalId): self
    {
        if ($this->ochaPresenceExternalIds->removeElement($ochaPresenceExternalId)) {
            // set the owning side to null (unless already changed)
            if ($ochaPresenceExternalId->getProvider() === $this) {
                $ochaPresenceExternalId->setProvider(null);
            }
        }

        return $this;
    }

}
