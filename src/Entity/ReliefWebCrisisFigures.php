<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ReliefWebCrisisFiguresRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: ReliefWebCrisisFiguresRepository::class)]
#[ApiResource(normalizationContext: ['groups' => ['rw_key_figures']])]
class ReliefWebCrisisFigures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('rw_key_figures')]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups('rw_key_figures')]
    private ?int $value = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    #[Groups('rw_key_figures')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups('rw_key_figures')]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    #[Groups('rw_key_figures')]
    private ?string $source = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 3)]
    #[Groups('rw_key_figures')]
    private ?string $language = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: ReliefWebCrisisFigureValue::class)]
    #[Groups('rw_key_figures')]
    #[SerializedName('values')]
    private Collection $figureValues;

    public function __construct()
    {
        $this->figureValues = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getLanguage(): ?string
    {
        return $this->language;
    }

    public function setLanguage(string $language): self
    {
        $this->language = $language;

        return $this;
    }

    /**
     * @return Collection<int, ReliefWebCrisisFigureValue>
     */
    public function getFigureValues(): Collection
    {
        return $this->figureValues;
    }

    public function addValue(ReliefWebCrisisFigureValue $figureValue): self
    {
        if (!$this->figureValues->contains($figureValue)) {
            $this->figureValues->add($figureValue);
            $figureValue->setParent($this);
        }

        return $this;
    }

    public function removeValue(ReliefWebCrisisFigureValue $figureValue): self
    {
        if ($this->figureValues->removeElement($figureValue)) {
            // set the owning side to null (unless already changed)
            if ($figureValue->getParent() === $this) {
                $figureValue->setParent(null);
            }
        }

        return $this;
    }
}
