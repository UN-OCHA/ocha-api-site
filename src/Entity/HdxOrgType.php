<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxOrgTypeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxOrgTypeRepository::class)]
#[ApiResource]
class HdxOrgType
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description = null;

    #[ORM\OneToMany(mappedBy: 'org_type_ref', targetEntity: HdxOrg::class)]
    private Collection $hdxOrgs;

    public function __construct()
    {
        $this->hdxOrgs = new ArrayCollection();
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

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, HdxOrg>
     */
    public function getHdxOrgs(): Collection
    {
        return $this->hdxOrgs;
    }

    public function addHdxOrg(HdxOrg $hdxOrg): static
    {
        if (!$this->hdxOrgs->contains($hdxOrg)) {
            $this->hdxOrgs->add($hdxOrg);
            $hdxOrg->setOrgTypeRef($this);
        }

        return $this;
    }

    public function removeHdxOrg(HdxOrg $hdxOrg): static
    {
        if ($this->hdxOrgs->removeElement($hdxOrg)) {
            // set the owning side to null (unless already changed)
            if ($hdxOrg->getOrgTypeRef() === $this) {
                $hdxOrg->setOrgTypeRef(null);
            }
        }

        return $this;
    }
}
