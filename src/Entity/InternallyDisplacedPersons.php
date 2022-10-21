<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use ApiPlatform\Metadata\Get;
use ApiPlatform\Metadata\GetCollection;
use App\Repository\InternallyDisplacedPersonsRepository;
use App\State\InternallyDisplacedPersonsCountriesStateProvider;
use App\State\InternallyDisplacedPersonsIso3StateProvider;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Serializer\Annotation\SerializedName;

#[ORM\Entity(repositoryClass: InternallyDisplacedPersonsRepository::class)]
#[ApiResource(
    security: "is_granted('ROLE_ADMIN') or is_granted('ROLE_IDPS')",
    operations: [
        new Get(
            normalizationContext: [
                'groups' => ['key_figures']
            ],
            uriTemplate: '/idps/country/{id}',
            uriVariables: ['id'],
            openapiContext: [
                'summary' => 'Get Internally displaced persons key figures by iso3 code',
                'description' => 'Get Internally displaced persons key figures by iso3 code',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
            ],
        ),
        new GetCollection(
            uriTemplate: '/idps/iso3/{iso3}',
            uriVariables: ['iso3'],
            output: SimpleStringObject::class,
            provider: InternallyDisplacedPersonsIso3StateProvider::class,
            openapiContext: [
                'summary' => 'Get Internally displaced persons key figures by iso3 code',
                'description' => 'Get Internally displaced persons key figures by iso3 code',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Internally displaced persons key figures by iso3 code',
                    ],
                ],
            ],
        ),        
        new GetCollection(
            uriTemplate: '/idps/countries',
            output: SimpleStringObject::class,
            provider: InternallyDisplacedPersonsCountriesStateProvider::class,
            openapiContext: [
                'summary' => 'Get a list of all countries',
                'description' => 'Get a list of all countries',
                'tags' => [
                    'Internally displaced persons key figures',
                ],
                'responses' => [
                    '200' => [
                        'description' => 'Array of countries keyed by iso3 code',
                    ],
                ],
            ],
        ),

    ]
)]
class InternallyDisplacedPersons
{
    #[ORM\Id]
    #[ORM\Column(length: 3)]
    #[Groups('key_figures')]
    #[SerializedName('iso3')]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Groups('key_figures')]
    private ?string $country = null;

    #[ORM\OneToMany(mappedBy: 'parent', indexBy: 'id', targetEntity: InternallyDisplacedPersonsValues::class)]
    #[Groups('key_figures')]
    #[SerializedName('values')]
    private Collection $InternallyDisplacedPersonsValues;

    public function __construct()
    {
        $this->InternallyDisplacedPersonsValues = new ArrayCollection();
    }

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $iso3): self
    {
        $this->id = $iso3;

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

    /**
     * @return Collection<int, InternallyDisplacedPersonsValues>
     */
    public function getInternallyDisplacedPersonsValues(): Collection
    {
        return $this->InternallyDisplacedPersonsValues;
    }

    public function addInternallyDisplacedPersonsValue(InternallyDisplacedPersonsValues $internallyDisplacedPersonsValue): self
    {
        if (!$this->InternallyDisplacedPersonsValues->contains($internallyDisplacedPersonsValue)) {
            $this->InternallyDisplacedPersonsValues->add($internallyDisplacedPersonsValue);
            $internallyDisplacedPersonsValue->setParent($this);
        }

        return $this;
    }

    public function removeInternallyDisplacedPersonsValue(InternallyDisplacedPersonsValues $internallyDisplacedPersonsValue): self
    {
        if ($this->InternallyDisplacedPersonsValues->removeElement($internallyDisplacedPersonsValue)) {
            // set the owning side to null (unless already changed)
            if ($internallyDisplacedPersonsValue->getParent() === $this) {
                $internallyDisplacedPersonsValue->setParent(null);
            }
        }

        return $this;
    }

    public function fromValues(array $values): self {
        $this->iso3 = $values['iso3'];
        $this->country = $values['country'];

        return $this;
    }    
}
