<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxAdmin1Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxAdmin1Repository::class)]
#[ApiResource]
class HdxAdmin1
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hdxAdmin1s')]
    private ?HdxLocation $location_ref = null;

    #[ORM\Column(length: 255)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7, nullable: true)]
    private ?string $centroid_lat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7, nullable: true)]
    private ?string $centroid_lon = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_historical = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $valid_date = null;

    #[ORM\OneToMany(mappedBy: 'admin1_ref', targetEntity: HdxAdmin2::class)]
    private Collection $hdxAdmin2s;

    public function __construct()
    {
        $this->hdxAdmin2s = new ArrayCollection();
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

    public function getLocationRef(): ?HdxLocation
    {
        return $this->location_ref;
    }

    public function setLocationRef(?HdxLocation $location_ref): static
    {
        $this->location_ref = $location_ref;

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

    public function getCentroidLat(): ?string
    {
        return $this->centroid_lat;
    }

    public function setCentroidLat(?string $centroid_lat): static
    {
        $this->centroid_lat = $centroid_lat;

        return $this;
    }

    public function getCentroidLon(): ?string
    {
        return $this->centroid_lon;
    }

    public function setCentroidLon(?string $centroid_lon): static
    {
        $this->centroid_lon = $centroid_lon;

        return $this;
    }

    public function isIsHistorical(): ?bool
    {
        return $this->is_historical;
    }

    public function setIsHistorical(?bool $is_historical): static
    {
        $this->is_historical = $is_historical;

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
     * @return Collection<int, HdxAdmin2>
     */
    public function getHdxAdmin2s(): Collection
    {
        return $this->hdxAdmin2s;
    }

    public function addHdxAdmin2(HdxAdmin2 $hdxAdmin2): static
    {
        if (!$this->hdxAdmin2s->contains($hdxAdmin2)) {
            $this->hdxAdmin2s->add($hdxAdmin2);
            $hdxAdmin2->setAdmin1Ref($this);
        }

        return $this;
    }

    public function removeHdxAdmin2(HdxAdmin2 $hdxAdmin2): static
    {
        if ($this->hdxAdmin2s->removeElement($hdxAdmin2)) {
            // set the owning side to null (unless already changed)
            if ($hdxAdmin2->getAdmin1Ref() === $this) {
                $hdxAdmin2->setAdmin1Ref(null);
            }
        }

        return $this;
    }
}
