<?php

namespace App\Entity;

use App\Repository\ReliefWebCrisisFigureValueRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: ReliefWebCrisisFigureValueRepository::class)]
class ReliefWebCrisisFigureValue
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Groups('key_figures')]
    private ?int $value = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    #[Groups('key_figures')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255, nullable: TRUE)]
    #[Groups('key_figures')]
    private ?string $url = null;

    #[ORM\ManyToOne(inversedBy: 'figureValues')]
    private ?ReliefWebCrisisFigures $parent = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getValue(): ?int
    {
        return $this->value;
    }

    public function setValue(int $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getParent(): ?ReliefWebCrisisFigures
    {
        return $this->parent;
    }

    public function setParent(?ReliefWebCrisisFigures $parent): self
    {
        $this->parent = $parent;

        return $this;
    }
}
