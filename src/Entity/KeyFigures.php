<?php

namespace App\Entity;

use ApiPlatform\Action\NotFoundAction;
use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\KeyFiguresRepository;
use App\State\KeyFiguresCountriesStateProvider;
use App\State\KeyFiguresYearsStateProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: KeyFiguresRepository::class)]
#[ApiResource(
//    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
    operations: [
        new Get(
            controller: NotFoundAction::class, 
            read: false, 
            output: false,
            host: 'do-not-use',
        ),
        new GetCollection(
            uriTemplate: '/key_figures/years',
            output: SimpleStringObject::class,
            provider: KeyFiguresYearsStateProvider::class,
            openapiContext: [
                'summary' => 'Get a list years',
                'description' => 'Get a list of years',
                'tags' => [
                    'Key Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of years keyed by year',
                    ],
                ],
            ]
        ),
        new GetCollection(
            uriTemplate: '/key_figures/countries',
            output: SimpleStringObject::class,
            provider: KeyFiguresCountriesStateProvider::class,
            openapiContext: [
                'summary' => 'Get a list countries',
                'description' => 'Get a list of iso3 codes',
                'tags' => [
                    'Key Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of countries keyed by iso3 code',
                    ],
                ],
            ]
        ),
        new GetCollection(
            uriTemplate: '/key_figures/numbers',
            openapiContext: [
                'summary' => 'Get a list of key figures',
                'description' => 'Get a list of key figures',
                'tags' => [
                    'Key Figures',
                ],
            ]
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: ['iso3' => 'exact', 'year' => 'exact', 'source' => 'exact', 'tags' => 'exact'])]
#[ApiFilter(OrderFilter::class, properties: ['iso3' => 'ASC', 'year' => 'DESC', 'year' => 'ASC'])]
class KeyFigures
{
    #[ORM\Id]
    #[ORM\Column(length: 255)]
    private ?string $id = null;

    #[ORM\Column(length: 3)]
    private ?string $iso3 = null;

    #[ORM\Column(length: 255)]
    private ?string $country = null;

    #[ORM\Column(length: 4)]
    private ?string $year = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    private ?string $value = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $updated = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $source = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    private array $tags = [];

    public function getId(): ?string
    {
        return $this->id;
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

    public function getCountry(): ?string
    {
        return $this->country;
    }

    public function setCountry(string $country): self
    {
        $this->country = $country;

        return $this;
    }

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

        return $this;
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

    public function getValue(): ?string
    {
        return $this->value;
    }

    public function setValue(string $value): self
    {
        $this->value = $value;

        return $this;
    }

    public function getUpdated(): ?\DateTimeInterface
    {
        return $this->updated;
    }

    public function setUpdated(?\DateTimeInterface $updated): self
    {
        $this->updated = $updated;

        return $this;
    }

    public function getUrl(): ?string
    {
        return $this->url;
    }

    public function setUrl(?string $url): self
    {
        $this->url = $url;

        return $this;
    }

    public function getSource(): ?string
    {
        return $this->source;
    }

    public function setSource(?string $source): self
    {
        $this->source = $source;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(?string $description): self
    {
        $this->description = $description;

        return $this;
    }

    public function getTags(): array
    {
        return $this->tags;
    }

    public function setTags(?array $tags): self
    {
        $this->tags = $tags;

        return $this;
    }

    public function fromValues(array $values): self {
        $this->id = $values['id'];
        $this->iso3 = $values['iso3'];
        $this->country = $values['country'];
        $this->year = $values['year'];
        $this->name = $values['name'];
        $this->value = $values['value'];
        $this->updated = $values['updated'];
        $this->url = $values['url'];
        $this->source = $values['source'];
        $this->description = $values['description'];
        $this->tags = $values['tags'];

        return $this;
    }
}
