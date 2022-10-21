<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\ReliefWebCrisisFiguresRepository;
use App\State\ReliefWebCrisisFiguresCountriesStateProvider;
use App\State\ReliefWebCrisisFiguresIso3StateProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;


#[ORM\Entity(repositoryClass: ReliefWebCrisisFiguresRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_RW_CRISIS')",
    operations: [
        new GetCollection(
            uriTemplate: '/relief_web_crisis_figures/countries',
            output: SimpleStringObject::class,
            provider: ReliefWebCrisisFiguresCountriesStateProvider::class,
            openapiContext: [
                'summary' => 'Get a list of all countries',
                'description' => 'Get a list of all countries',
                'tags' => [
                    'ReliefWeb Crisis Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of countries keyed by iso3 code',
                    ],
                ],
            ],
        ),
        new GetCollection(
            normalizationContext: [
                'groups' => ['key_figures']
            ],
            uriTemplate: '/relief_web_crisis_figures/country/{iso3}',
            provider: ReliefWebCrisisFiguresIso3StateProvider::class,
            uriVariables: ['iso3'],
            openapiContext: [
                'summary' => 'Get ReliefWeb key figures by iso3 code',
                'description' => 'Get ReliefWeb key figures by iso3 code',
                'tags' => [
                    'ReliefWeb Crisis Figures',
                ],
            ],
        ),
    ]
)]

class ReliefWebCrisisFigures
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('key_figures')]
    private ?string $name = null;

    #[ORM\Column]
    #[Groups('key_figures')]
    private ?int $value = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE)]
    #[Groups('key_figures')]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(length: 255)]
    #[Groups('key_figures')]
    private ?string $url = null;

    #[ORM\Column(length: 255)]
    #[Groups('key_figures')]
    private ?string $source = null;

    #[ORM\Column(length: 255)]
    private ?string $description = null;

    #[ORM\Column(length: 3)]
    #[Groups('key_figures')]
    private ?string $language = null;

    #[ORM\OneToMany(mappedBy: 'parent', targetEntity: ReliefWebCrisisFigureValue::class)]
    #[Groups('key_figures')]
    #[SerializedName('values')]
    private Collection $figureValues;

    #[ORM\Column(length: 3)]
    private ?string $iso3 = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

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

    public function getIso3(): ?string
    {
        return $this->iso3;
    }

    public function setIso3(string $iso3): self
    {
        $this->iso3 = $iso3;

        return $this;
    }

    public function fromValues(array $values): self {
        $this->date = $values['date'];
        $this->description = $values['description'];
        $this->iso3 = $values['iso3'];
        $this->country = $values['country'];
        $this->language = $values['language'];
        $this->name = $values['name'];
        $this->value = $values['value'];
        $this->url = $values['url'];
        $this->source = $values['source'];

        return $this;
    }

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }
}
