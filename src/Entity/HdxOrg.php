<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxOrgRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxOrgRepository::class)]
#[ApiResource]
class HdxOrg
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hdx_link = null;

    #[ORM\Column(length: 255)]
    private ?string $acronym = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\ManyToOne(inversedBy: 'hdxOrgs')]
    private ?HdxOrgType $org_type_ref = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $valid_date = null;

    #[ORM\OneToMany(mappedBy: 'org_ref', targetEntity: HdxOperationalPresence::class)]
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

    public function getHdxLink(): ?string
    {
        return $this->hdx_link;
    }

    public function setHdxLink(?string $hdx_link): static
    {
        $this->hdx_link = $hdx_link;

        return $this;
    }

    public function getAcronym(): ?string
    {
        return $this->acronym;
    }

    public function setAcronym(string $acronym): static
    {
        $this->acronym = $acronym;

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

    public function getOrgTypeRef(): ?HdxOrgType
    {
        return $this->org_type_ref;
    }

    public function setOrgTypeRef(?HdxOrgType $org_type_ref): static
    {
        $this->org_type_ref = $org_type_ref;

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
            $hdxOperationalPresence->setOrgRef($this);
        }

        return $this;
    }

    public function removeHdxOperationalPresence(HdxOperationalPresence $hdxOperationalPresence): static
    {
        if ($this->hdxOperationalPresences->removeElement($hdxOperationalPresence)) {
            // set the owning side to null (unless already changed)
            if ($hdxOperationalPresence->getOrgRef() === $this) {
                $hdxOperationalPresence->setOrgRef(null);
            }
        }

        return $this;
    }
}
