<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiProperty;
use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxLocationRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxLocationRepository::class)]
#[ApiResource]
class HdxLocation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 3)]
    private ?string $code = null;

    #[ORM\Column(length: 255)]
    #[ApiProperty(types: ["https://schema.org/name"])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7, nullable: true)]
    private ?string $centroid_lat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 7, nullable: true)]
    private ?string $centroid_lon = null;

    #[ORM\Column(nullable: true)]
    private ?bool $is_historical = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $valid_date = null;

    #[ORM\OneToMany(mappedBy: 'location_ref', targetEntity: HdxAdmin1::class)]
    private Collection $hdxAdmin1s;

    public function __construct()
    {
        $this->hdxAdmin1s = new ArrayCollection();
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
     * @return Collection<int, HdxAdmin1>
     */
    public function getHdxAdmin1s(): Collection
    {
        return $this->hdxAdmin1s;
    }

    public function addHdxAdmin1(HdxAdmin1 $hdxAdmin1): static
    {
        if (!$this->hdxAdmin1s->contains($hdxAdmin1)) {
            $this->hdxAdmin1s->add($hdxAdmin1);
            $hdxAdmin1->setLocationRef($this);
        }

        return $this;
    }

    public function removeHdxAdmin1(HdxAdmin1 $hdxAdmin1): static
    {
        if ($this->hdxAdmin1s->removeElement($hdxAdmin1)) {
            // set the owning side to null (unless already changed)
            if ($hdxAdmin1->getLocationRef() === $this) {
                $hdxAdmin1->setLocationRef(null);
            }
        }

        return $this;
    }
}
