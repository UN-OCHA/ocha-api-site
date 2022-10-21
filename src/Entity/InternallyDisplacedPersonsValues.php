<?php

namespace App\Entity;

use App\Repository\InternallyDisplacedPersonsValuesRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: InternallyDisplacedPersonsValuesRepository::class)]
class InternallyDisplacedPersonsValues
{
    #[ORM\Id]
    #[ORM\Column(length: 30)]
    private ?string $id = null;

    #[ORM\Column]
    #[Groups('key_figures')]
    private ?int $year = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: '0', nullable: true)]
    #[Groups('key_figures')]
    private ?string $ConflictStockDisplacement = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: '0', nullable: true)]
    #[Groups('key_figures')]
    private ?string $ConflictInternalDisplacements = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: '0', nullable: true)]
    #[Groups('key_figures')]
    private ?string $DisasterInternalDisplacements = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: '0', nullable: true)]
    #[Groups('key_figures')]
    private ?string $DisasterStockDisplacement = null;

    #[ORM\ManyToOne(inversedBy: 'InternallyDisplacedPersonsValues')]
    private ?InternallyDisplacedPersons $parent = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function getYear(): ?int
    {
        return $this->year;
    }

    public function setYear(int $year): self
    {
        $this->year = $year;

        return $this;
    }

    public function getConflictStockDisplacement(): ?string
    {
        return $this->ConflictStockDisplacement;
    }

    public function setConflictStockDisplacement(?string $ConflictStockDisplacement): self
    {
        $this->ConflictStockDisplacement = $ConflictStockDisplacement;

        return $this;
    }

    public function getConflictInternalDisplacements(): ?string
    {
        return $this->ConflictInternalDisplacements;
    }

    public function setConflictInternalDisplacements(?string $ConflictInternalDisplacements): self
    {
        $this->ConflictInternalDisplacements = $ConflictInternalDisplacements;

        return $this;
    }

    public function getDisasterInternalDisplacements(): ?string
    {
        return $this->DisasterInternalDisplacements;
    }

    public function setDisasterInternalDisplacements(?string $DisasterInternalDisplacements): self
    {
        $this->DisasterInternalDisplacements = $DisasterInternalDisplacements;

        return $this;
    }

    public function getDisasterStockDisplacement(): ?string
    {
        return $this->DisasterStockDisplacement;
    }

    public function setDisasterStockDisplacement(?string $DisasterStockDisplacement): self
    {
        $this->DisasterStockDisplacement = $DisasterStockDisplacement;

        return $this;
    }

    public function getParent(): ?InternallyDisplacedPersons
    {
        return $this->parent;
    }

    public function setParent(?InternallyDisplacedPersons $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
