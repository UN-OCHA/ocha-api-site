<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxOperationalPresenceRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxOperationalPresenceRepository::class)]
#[ApiResource]
class HdxOperationalPresence
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hdxOperationalPresences')]
    private ?HdxResource $resource_ref = null;

    #[ORM\ManyToOne(inversedBy: 'hdxOperationalPresences')]
    private ?HdxOrg $org_ref = null;

    #[ORM\ManyToOne(inversedBy: 'hdxOperationalPresences')]
    private ?HdxSector $sector_ref = null;

    #[ORM\ManyToOne(inversedBy: 'hdxOperationalPresences')]
    private ?HdxAdmin2 $admin2_ref = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $valid_date = null;

    #[ORM\Column(nullable: false)]
    private array $orig_data = [];

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId($id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getResourceRef(): ?HdxResource
    {
        return $this->resource_ref;
    }

    public function setResourceRef(?HdxResource $resource_ref): static
    {
        $this->resource_ref = $resource_ref;

        return $this;
    }

    public function getOrgRef(): ?HdxOrg
    {
        return $this->org_ref;
    }

    public function setOrgRef(?HdxOrg $org_ref): static
    {
        $this->org_ref = $org_ref;

        return $this;
    }

    public function getSectorRef(): ?HdxSector
    {
        return $this->sector_ref;
    }

    public function setSectorRef(?HdxSector $sector_ref): static
    {
        $this->sector_ref = $sector_ref;

        return $this;
    }

    public function getAdmin2Ref(): ?HdxAdmin2
    {
        return $this->admin2_ref;
    }

    public function setAdmin2Ref(?HdxAdmin2 $admin2_ref): static
    {
        $this->admin2_ref = $admin2_ref;

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

    public function getOrigData(): array
    {
        return $this->orig_data;
    }

    public function setOrigData(?array $orig_data): static
    {
        $this->orig_data = $orig_data;

        return $this;
    }
}
