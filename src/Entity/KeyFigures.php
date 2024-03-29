<?php

namespace App\Entity;

use ApiPlatform\Doctrine\Orm\Filter\OrderFilter;
use ApiPlatform\Doctrine\Orm\Filter\SearchFilter;
use ApiPlatform\Metadata\ApiFilter;
use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Delete;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use ApiPlatform\Metadata\Link;
use ApiPlatform\Metadata\Patch;
use ApiPlatform\Metadata\Post;
use ApiPlatform\Metadata\Put;
use ApiPlatform\OpenApi\Model\Operation as OpenApiOperation;
use App\Controller\KeyFiguresArchiveController;
use App\Controller\KeyFiguresBatchController;
use App\Dto\ArchiveInput;
use App\Dto\BatchCollection;
use App\Dto\BatchResponses;
use App\Dto\SimpleStringObject;
use App\Filter\JsonFilter;
use App\Repository\KeyFiguresRepository;
use App\Serializer\KeyFigureSerializer;
use App\State\KeyFigures\KeyFiguresBatchProcessor;
use App\State\KeyFigures\KeyFiguresCountriesStateProvider;
use App\State\KeyFigures\KeyFiguresLimitByProviderStateProvider;
use App\State\KeyFigures\KeyFiguresOchaPresenceFiguresStateProvider;
use App\State\KeyFigures\KeyFiguresOchaPresencesStateProvider;
use App\State\KeyFigures\KeyFiguresOchaPresenceYearsStateProvider;
use App\State\KeyFigures\KeyFiguresPutStateProvider;
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
        'standard_put' => TRUE,
    ],
    operations: [
        // Years.
        new GetCollection(
            uriTemplate: '/key_figures/years',
            output: SimpleStringObject::class,
            provider: KeyFiguresYearsStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Get a list years',
              description: 'Get a list of years',
              tags: [
                  'Key Figures',
              ],
              responses: [
                  '200' => [
                      'description' => 'Array of years keyed by year',
                  ],
              ],
            ),
        ),
        // Countries.
        new GetCollection(
            uriTemplate: '/key_figures/countries',
            output: SimpleStringObject::class,
            provider: KeyFiguresCountriesStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Get a list countries',
              description: 'Get a list of countries',
              tags: [
                  'Key Figures',
              ],
              responses: [
                  '200' => [
                      'description' => 'Array of countries keyed by year',
                  ],
              ],
            ),
        ),
        // OCHA Presences.
        new GetCollection(
          uriTemplate: '/key_figures/ocha-presences',
          output: SimpleStringObject::class,
          provider: KeyFiguresOchaPresencesStateProvider::class,
          openapi: new OpenApiOperation(
            summary: 'Get a list OCHA presences',
            description: 'Get a list of OCHA presences',
            tags: [
                'Key Figures',
            ],
            responses: [
                '200' => [
                    'description' => 'Array of OCHA presences',
                ],
            ],
          ),
        ),
        // OCHA Presences.
        new GetCollection(
            uriTemplate: '/key_figures/ocha-presences/{ocha_presence_id}',
            uriVariables: [
                'ocha_presence_id' => new Link(
                    fromClass: OchaPresence::class,
                    fromProperty: 'id'
                )
            ],
            output: SimpleStringObject::class,
            provider: KeyFiguresOchaPresenceFiguresStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Get a list OCHA presences key figures',
              description: 'Get a list of OCHA presences key figures',
              tags: [
                  'Key Figures',
              ],
              responses: [
                  '200' => [
                      'description' => 'Array of OCHA presences key figures',
                  ],
              ],
            ),
          ),
          // OCHA Presence years.
        new GetCollection(
            uriTemplate: '/key_figures/ocha-presences/{ocha_presence_id}/years',
            uriVariables: [
                'ocha_presence_id' => new Link(
                    fromClass: OchaPresence::class,
                    fromProperty: 'id'
                )
            ],
            output: SimpleStringObject::class,
            provider: KeyFiguresOchaPresenceYearsStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Get a list of years for an OCHA presences',
              description: 'Get a list of years for an OCHA presences',
              tags: [
                  'Key Figures',
              ],
              responses: [
                  '200' => [
                      'description' => 'Array of years for an OCHA presences',
                  ],
              ],
            ),
        ),
        // OCHA Presence figures.
        new GetCollection(
            uriTemplate: '/key_figures/ocha-presences/{ocha_presence_id}/{year}/figures',
            uriVariables: [
                'ocha_presence_id' => new Link(
                    fromClass: OchaPresence::class,
                    fromProperty: 'id'
                ),
                'year' => new Link(
                    fromClass: OchaPresenceExternalId::class,
                    fromProperty: 'year'
                )
            ],
            output: KeyFigures::class,
            serialize: KeyFigureSerializer::class,
            provider: KeyFiguresOchaPresenceFiguresStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Get a list figures for an OCHA presences',
              description: 'Get a list figures for an OCHA presences',
              tags: [
                  'Key Figures',
              ],
              responses: [
                  '200' => [
                      'description' => 'Array of figures for an OCHA presences',
                  ],
              ],
            ),
        ),
        // Create or update.
        new Put(
            securityPostDenormalize: "is_granted('ROLE_ADMIN') or is_granted('KEY_FIGURES_UPSERT', object)",
            uriTemplate: '/key_figures/{id}',
            processor: KeyFiguresPutStateProvider::class,
            denormalizationContext: [
                'groups' => ['write'],
            ],
            openapi: new OpenApiOperation(
              summary: 'Create or update a key figure',
              description: 'Create or update a key figure',
              tags: [
                  'Key Figures',
              ],
            ),
        ),
        // Partial update.
        new Patch(
          inputFormats: [
            'jsonld' => ['application/merge-patch+json'],
          ],
          securityPostDenormalize: "is_granted('ROLE_ADMIN') or is_granted('KEY_FIGURES_UPSERT', object)",
          uriTemplate: '/key_figures/{id}',
          denormalizationContext: [
              'groups' => ['write'],
          ],
          openapi: new OpenApiOperation(
            summary: 'Update a key figure',
            description: 'Partially update a key figure',
            tags: [
                'Key Figures',
            ],
          ),
        ),
        // Batch update.
        new Post(
            securityPostDenormalize: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER') or is_granted('KEY_FIGURES_BATCH', object)",
            uriTemplate: '/key_figures/batch',
            input: BatchCollection::class,
            processor: KeyFiguresBatchProcessor::class,
            controller: KeyFiguresBatchController::class,
            output: BatchResponses::class,
            openapi: new OpenApiOperation(
              summary: 'Create or update a key figures in batch',
              description: 'Create or update a key figures in batch, the code example below is not correct, you need to pass an array of objects like you would do for the Put command.',
              tags: [
                  'Key Figures',
              ],
            ),
        ),
        // Archive.
        new Post(
          securityPostDenormalize: "is_granted('ROLE_ADMIN') or is_granted('ROLE_USER')",
          uriTemplate: '/key_figures/archive',
          input: ArchiveInput::class,
          read: false,
          controller: KeyFiguresArchiveController::class,
          output: SimpleStringObject::class,
          openapi: new OpenApiOperation(
            summary: 'Archive records by country and/or year',
            description: 'Archive records by country and/or year',
            tags: [
                'Key Figures',
            ],
          ),
        ),
        // Get.
        new Get(
          provider: KeyFiguresLimitByProviderStateProvider::class,
          uriTemplate: '/key_figures/{id}',
          openapi: new OpenApiOperation(
            summary: 'Get a key figure',
            description: 'Get a key figure',
            tags: [
                'Key Figures',
            ],
          ),
        ),
        // Delete.
        new Delete(
          securityPostDenormalize: "is_granted('ROLE_ADMIN') or is_granted('KEY_FIGURES_DELETE', object)",
          provider: KeyFiguresLimitByProviderStateProvider::class,
          uriTemplate: '/key_figures/{id}',
          openapi: new OpenApiOperation(
            summary: 'Delete a key figure',
            description: 'Delete a key figure',
            tags: [
                'Key Figures',
            ],
          ),
        ),
        // Get.
        new GetCollection(
            uriTemplate: '/key_figures',
            provider: KeyFiguresLimitByProviderStateProvider::class,
            openapi: new OpenApiOperation(
              summary: 'Get a list of key figures',
              description: 'Get a list of key figures',
              tags: [
                  'Key Figures',
              ],
            ),
        ),
    ]
)]
#[ApiFilter(SearchFilter::class, properties: [
    'figureId' => 'exact',
    'externalId' => 'exact',
    'iso3' => 'exact',
    'year' => 'exact',
    'archived' => 'exact',
    'source' => 'exact',
    'tags' => 'exact',
])]
#[ApiFilter(JsonFilter::class, properties: [
    "extra.*" =>  ["type" => "string", "strategy" => "exact"],
])]
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
    #[Assert\Regex("/^-?\d+(\.\d+)?$/")]
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

    #[ORM\Column(nullable: true)]
    #[Groups(['write', 'with_meta'])]
    private array $extra = [];

    #[ORM\Column(nullable: true)]
    #[Groups(['write', 'with_meta'])]
    private ?bool $archived = false;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['write', 'with_meta'])]
    private ?string $valueString = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['write', 'with_meta'])]
    private ?string $valueType = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $unit = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $figureId = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Groups(['write', 'without_meta', 'with_meta'])]
    private ?string $externalId = null;

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
        $this->iso3 = strtolower($iso3);

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

    public function getExtra(): array
    {
        return $this->extra;
    }

    public function setExtra(?array $extra): self
    {
        $this->extra = $extra;

        return $this;
    }

    public function fromValues(array $values): self {
        $this->id = $values['id'];
        $this->figureId = $values['figure_id'];
        $this->externalId = $values['external_id'] ?? NULL;
        $this->iso3 = strtolower($values['iso3']);
        $this->country = $values['country'];
        $this->year = $values['year'];
        $this->name = $values['name'];
        $this->value = $values['value'] ?? '0.00';
        $this->valueString = $values['value_string'] ?? NULL;
        $this->valueType = $values['value_type'] ?? 'numeric';
        $this->url = $values['url'] ?? '';
        $this->source = $values['source'] ?? '';
        $this->description = $values['description'] ?? '';
        $this->tags = $values['tags'] ?? [];
        $this->provider = $values['provider'];
        $this->extra = $values['extra'] ?? [];
        $this->archived = $values['archived'] ?? FALSE;
        $this->unit = $values['unit'] ?? '';

        if (!isset($values['updated'])) {
          $this->updated = NULL;
        }
        elseif (is_string($values['updated'])) {
          $this->updated = new \DateTime($values['updated']);
        }
        else {
          $this->updated = $values['updated'] ?? NULL;
        }

        return $this;
    }

    public function extractValues(): array {
      return [
        'id' => $this->id,
        'figure_id' => $this->figureId ?? '',
        'external_id' => $this->externalId ?? '',
        'iso3' => strtolower($this->iso3),
        'country' => $this->country,
        'year' => $this->year,
        'name' => $this->name,
        'value' => $this->valueString ?? $this->value,
        'value_type' => $this->valueType ?? 'numeric',
        'updated' => $this->updated ?? NULL,
        'url' => $this->url,
        'source' => $this->source,
        'description' => $this->description ?? '',
        'tags' => $this->tags ?? [],
        'provider' => $this->provider,
        'extra' => $this->extra ?? [],
        'archived' => $this->archived ?? FALSE,
        'unit' => $this->unit ?? '',
      ];
  }

    public function isArchived(): ?bool
    {
        return $this->archived;
    }

    public function setArchived(?bool $archived): self
    {
        $this->archived = $archived;

        return $this;
    }

    public function getValueString(): ?string
    {
        return $this->valueString;
    }

    public function setValueString(?string $valueString): self
    {
        $this->valueString = $valueString;

        return $this;
    }

    public function getValueType(): ?string
    {
        return $this->valueType;
    }

    public function setValueType(?string $valueType): self
    {
        $this->valueType = $valueType;

        return $this;
    }

    public function getUnit(): ?string
    {
        return $this->unit;
    }

    public function setUnit(?string $unit): self
    {
        $this->unit = $unit;

        return $this;
    }

    public function getFigureId(): ?string
    {
        return $this->figureId;
    }

    public function setFigureId(?string $figureId): self
    {
        $this->figureId = $figureId;

        return $this;
    }

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(?string $externalId): static
    {
        $this->externalId = $externalId;

        return $this;
    }
}
