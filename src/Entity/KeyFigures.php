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
use App\State\KeyFiguresStateProvider;
use App\State\KeyFiguresYearsStateProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ORM\Entity(repositoryClass: KeyFiguresRepository::class)]
#[ApiResource(
    operations: [
        // All
        new Get(
            security: "is_granted('ROLE_ADMIN')",
            controller: NotFoundAction::class, 
            read: false, 
            output: false,
            host: 'do-not-use',
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN')",
            normalizationContext: [
                'groups' => ['with_meta'],
                'skip_null_values' => FALSE,
            ],
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
            security: "is_granted('ROLE_ADMIN')",
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
            security: "is_granted('ROLE_ADMIN')",
            uriTemplate: '/key_figures',
            openapiContext: [
                'summary' => 'Get a list of key figures',
                'description' => 'Get a list of key figures',
                'tags' => [
                    'Key Figures',
                ],
            ]
        ),

        // FTS
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FTS')",
            normalizationContext: [
                'groups' => ['without_meta'],
                'skip_null_values' => FALSE,
            ],
            uriTemplate: '/fts',
            provider: KeyFiguresStateProvider::class,
            extraProperties: [
                'provider' => 'fts',
            ],
            openapiContext: [
                'summary' => 'Get a list of FTS data by iso3 code',
                'description' => 'Get a list of FTS data by iso3 code',
                'tags' => [
                    'FTS Key Figures',
                ],
            ]
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FTS')",
            uriTemplate: '/fts/years',
            output: SimpleStringObject::class,
            provider: KeyFiguresYearsStateProvider::class,
            extraProperties: [
                'provider' => 'fts',
            ],
            openapiContext: [
                'summary' => 'Get a list years',
                'description' => 'Get a list of years',
                'tags' => [
                    'FTS Key Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of years keyed by year',
                    ],
                ],
            ]
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_FTS')",
            uriTemplate: '/fts/countries',
            output: SimpleStringObject::class,
            provider: KeyFiguresCountriesStateProvider::class,
            extraProperties: [
                'provider' => 'fts',
            ],
            openapiContext: [
                'summary' => 'Get a list countries',
                'description' => 'Get a list of iso3 codes',
                'tags' => [
                    'FTS Key Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of countries keyed by iso3 code',
                    ],
                ],
            ]
        ),

        // IDPs
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_IDPS')",
            normalizationContext: [
                'groups' => ['without_meta'],
                'skip_null_values' => FALSE,
            ],
            uriTemplate: '/idps',
            provider: KeyFiguresStateProvider::class,
            extraProperties: [
                'provider' => 'idps',
            ],
            openapiContext: [
                'summary' => 'Get Internally Displaced Persons key figures',
                'description' => 'Get Internally Displaced Persons key figures',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
            ]
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_IDPS')",
            uriTemplate: '/idps/years',
            output: SimpleStringObject::class,
            provider: KeyFiguresYearsStateProvider::class,
            extraProperties: [
                'provider' => 'idps',
            ],
            openapiContext: [
                'summary' => 'Get a list years',
                'description' => 'Get a list of years',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of years keyed by year',
                    ],
                ],
            ]
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_IDPS')",
            uriTemplate: '/idps/countries',
            output: SimpleStringObject::class,
            provider: KeyFiguresCountriesStateProvider::class,
            extraProperties: [
                'provider' => 'idps',
            ],
            openapiContext: [
                'summary' => 'Get a list countries',
                'description' => 'Get a list of iso3 codes',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of countries keyed by iso3 code',
                    ],
                ],
            ]
        ),

        // ReliefWeb Crisis
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_RW_CRISIS')",
            uriTemplate: '/relief_web_crisis_figures/years',
            output: SimpleStringObject::class,
            provider: KeyFiguresYearsStateProvider::class,
            extraProperties: [
                'provider' => 'rw_crisis',
            ],
            openapiContext: [
                'summary' => 'Get a list years',
                'description' => 'Get a list of years',
                'tags' => [
                    'ReliefWeb Crisis Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of years keyed by year',
                    ],
                ],
            ]
            ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_RW_CRISIS')",
            uriTemplate: '/relief_web_crisis_figures/countries',
            output: SimpleStringObject::class,
            provider: KeyFiguresCountriesStateProvider::class,
            extraProperties: [
                'provider' => 'rw_crisis',
            ],
            openapiContext: [
                'summary' => 'Get a list countries',
                'description' => 'Get a list of iso3 codes',
                'tags' => [
                    'ReliefWeb Crisis Figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of countries keyed by iso3 code',
                    ],
                ],
            ]
        ),
        new GetCollection(
            security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_RW_CRISIS')",
            normalizationContext: [
                'groups' => ['without_meta'],
                'skip_null_values' => FALSE,
            ],
            uriTemplate: '/relief_web_crisis_figures',
            provider: KeyFiguresStateProvider::class,
            extraProperties: [
                'provider' => 'rw_crisis',
            ],
            openapiContext: [
                'summary' => 'Get a list of ReliefWeb crisis figures',
                'description' => 'Get a list of ReliefWeb crisis figures',
                'tags' => [
                    'ReliefWeb Crisis Figures',
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
    #[Groups('with_meta')]
    private ?string $id = null;

    #[ORM\Column(length: 3)]
    #[Groups('without_meta', 'with_meta')]
    private ?string $iso3 = null;

    #[ORM\Column(length: 255)]
    #[Groups('without_meta', 'with_meta')]
    private ?string $country = null;

    #[ORM\Column(length: 4)]
    #[Groups('without_meta', 'with_meta')]
    private ?string $year = null;

    #[ORM\Column(length: 255)]
    #[Groups('without_meta', 'with_meta')]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    #[Groups('without_meta', 'with_meta')]
    private ?string $value = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Groups('without_meta', 'with_meta')]
    private ?\DateTimeInterface $updated = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('without_meta', 'with_meta')]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups('without_meta', 'with_meta')]
    private ?string $source = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups('without_meta', 'with_meta')]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups('without_meta', 'with_meta')]
    private array $tags = [];

    #[ORM\Column(length: 255)]
    #[Groups('with_meta')]
    private ?string $provider = null;

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

    public function getProvider(): ?string
    {
        return $this->provider;
    }

    public function setProvider(string $provider): self
    {
        $this->provider = $provider;

        return $this;
    }

    public function fromValues(array $values): self {
        $this->id = $values['id'];
        $this->iso3 = $values['iso3'];
        $this->country = $values['country'];
        $this->year = $values['year'];
        $this->name = $values['name'];
        $this->value = $values['value'];
        $this->updated = $values['updated'] ?? NULL;
        $this->url = $values['url'];
        $this->source = $values['source'];
        $this->description = $values['description'] ?? '';
        $this->tags = $values['tags'];
        $this->provider = $values['provider'];

        return $this;
    }
}
