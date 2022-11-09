<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Put;
use App\Repository\KeyFiguresRepository;
use App\State\KeyFigures\KeyFiguresCountriesStateProvider;
use App\State\KeyFigures\KeyFiguresLimitByProviderStateProvider;
use App\State\KeyFigures\KeyFiguresYearsStateProvider;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: KeyFiguresRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_USER')",
    extraProperties: [
        'expand' => 'key_figures',
    ],
    operations: [
        // Years.
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
        // Countries.
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
        // Create or update.
        new Put(
            securityPostDenormalize: "is_granted('ROLE_ADMIN') or is_granted('KEY_FIGURES_UPSERT', object)",
            uriTemplate: '/key_figures/{id}',
            denormalizationContext: [
                'groups' => ['write'],
            ],
            openapiContext: [
                'summary' => 'Create or update a key figures',
                'description' => 'Create or update a key figures',
                'tags' => [
                    'Key Figures',
                ],
            ]
        ),
        // Get.
        new Get(
            provider: KeyFiguresLimitByProviderStateProvider::class,
            uriTemplate: '/key_figures/{id}',
            openapiContext: [
                'summary' => 'Get a key figures',
                'description' => 'Get a key figures',
                'tags' => [
                    'Key Figures',
                ],
            ]
        ),
        // Get.
        new GetCollection(
            uriTemplate: '/key_figures',
            provider: KeyFiguresLimitByProviderStateProvider::class,
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
    #[Assert\NotBlank]
    #[Groups(['with_meta'])]
    private ?string $id = null;

    #[ORM\Column(length: 3)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 3, max: 3)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $iso3 = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $country = null;

    #[ORM\Column(length: 4)]
    #[Assert\NotBlank]
    #[Assert\Length(min: 4, max: 4)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $year = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $name = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 20, scale: 2)]
    #[Assert\NotBlank]
    #[Assert\Regex("/^\d+(\.\d+)?$/")]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $value = null;

    #[ORM\Column(type: Types::DATETIMETZ_MUTABLE, nullable: true)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?\DateTimeInterface $updated = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $url = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $source = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $description = null;

    #[ORM\Column(nullable: true)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private array $tags = [];

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Groups(['write', 'with_meta'])]
    private ?string $provider = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId($id): self
    {
        $this->id = $id;

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
