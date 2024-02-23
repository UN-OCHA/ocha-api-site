<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\HdxAdmin2Repository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: HdxAdmin2Repository::class)]
#[ApiResource]
class HdxAdmin2
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'hdxAdmin2s')]
    private ?HdxAdmin1 $admin1_ref = null;

    #[ORM\Column(length: 255, nullable: true)]
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

    #[ORM\OneToMany(mappedBy: 'admin2_ref', targetEntity: HdxPopulation::class)]
    private Collection $hdxPopulations;

    #[ORM\OneToMany(mappedBy: 'admin2_ref', targetEntity: HdxOperationalPresence::class)]
    private Collection $hdxOperationalPresences;

    public function __construct()
    {
        $this->hdxPopulations = new ArrayCollection();
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

    public function getAdmin1Ref(): ?HdxAdmin1
    {
        return $this->admin1_ref;
    }

    public function setAdmin1Ref(?HdxAdmin1 $admin1_ref): static
    {
        $this->admin1_ref = $admin1_ref;

        return $this;
    }

    public function getCode(): ?string
    {
        return $this->code;
    }

    public function setCode(?string $code): static
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
     * @return Collection<int, HdxPopulation>
     */
    public function getHdxPopulations(): Collection
    {
        return $this->hdxPopulations;
    }

    public function addHdxPopulation(HdxPopulation $hdxPopulation): static
    {
        if (!$this->hdxPopulations->contains($hdxPopulation)) {
            $this->hdxPopulations->add($hdxPopulation);
            $hdxPopulation->setAdmin2Ref($this);
        }

        return $this;
    }

    public function removeHdxPopulation(HdxPopulation $hdxPopulation): static
    {
        if ($this->hdxPopulations->removeElement($hdxPopulation)) {
            // set the owning side to null (unless already changed)
            if ($hdxPopulation->getAdmin2Ref() === $this) {
                $hdxPopulation->setAdmin2Ref(null);
            }
        }

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
            $hdxOperationalPresence->setAdmin2Ref($this);
        }

        return $this;
    }

    public function removeHdxOperationalPresence(HdxOperationalPresence $hdxOperationalPresence): static
    {
        if ($this->hdxOperationalPresences->removeElement($hdxOperationalPresence)) {
            // set the owning side to null (unless already changed)
            if ($hdxOperationalPresence->getAdmin2Ref() === $this) {
                $hdxOperationalPresence->setAdmin2Ref(null);
            }
        }

        return $this;
    }
}
