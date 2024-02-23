<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxSectorRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxSectorRepository::class)]
#[ApiResource]
class HdxSector
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $valid_date = null;

    #[ORM\OneToMany(mappedBy: 'sector_ref', targetEntity: HdxOperationalPresence::class)]
    private Collection $hdxOperationalPresences;

    public function __construct()
    {
        $this->hdxOperationalPresences = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(string $code): static
    {
        $this->code = $code;

        return $this;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getValidDate(): ?\DateTimeInterface
    {
        return $this->valid_date;
    }

    public function setValidDate(?\DateTimeInterface $valid_date): static
    {
        $this->valid_date = $valid_date;

        return $this;
    }

    /**
     * @return Collection<int, HdxOperationalPresence>
     */
    public function getHdxOperationalPresences(): Collection
    {
        return $this->hdxOperationalPresences;
    }

    public function addHdxOperationalPresence(HdxOperationalPresence $hdxOperationalPresence): static
    {
        if (!$this->hdxOperationalPresences->contains($hdxOperationalPresence)) {
            $this->hdxOperationalPresences->add($hdxOperationalPresence);
            $hdxOperationalPresence->setSectorRef($this);
        }

        return $this;
    }

    public function removeHdxOperationalPresence(HdxOperationalPresence $hdxOperationalPresence): static
    {
        if ($this->hdxOperationalPresences->removeElement($hdxOperationalPresence)) {
            // set the owning side to null (unless already changed)
            if ($hdxOperationalPresence->getSectorRef() === $this) {
                $hdxOperationalPresence->setSectorRef(null);
            }
        }

        return $this;
    }
}
