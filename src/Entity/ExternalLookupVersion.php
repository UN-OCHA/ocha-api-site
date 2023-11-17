<?php

namespace App\Entity;

use App\Repository\ExternalLookupVersionRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExternalLookupVersionRepository::class)]
class ExternalLookupVersion
{

    #[ORM\Id]
    #[ORM\Column]
    private string $id;

    #[ORM\Column]
    private string $provider;

    #[ORM\Column]
    private string $year;

    #[ORM\Column]
    private string $iso3;

    #[ORM\Column]
    private string $externalId;

    #[ORM\Column]
    private string $name;

    #[ORM\Id]
    #[ORM\Column]
    private int $version;

    #[ORM\Column]
    private DateTime $ts;

    #[ORM\Column]
    private bool $deleted;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): self
    {
        $this->id = $id;

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

    public function getYear(): ?string
    {
        return $this->year;
    }

    public function setYear(string $year): self
    {
        $this->year = $year;

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

    public function getExternalId(): ?string
    {
        return $this->externalId;
    }

    public function setExternalId(string $external_id): self
    {
        $this->externalId = $external_id;

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

    public function getVersion(): ?string
    {
        return $this->version;
    }

    public function setVersion(string $version): self
    {
        $this->version = $version;

        return $this;
    }

    public function getTs(): ?DateTime
    {
        return $this->ts;
    }

    public function setTs(DateTime $ts): self
    {
        $this->ts = $ts;

        return $this;
    }

    public function getDeleted(): ?bool
    {
        return $this->deleted;
    }

    public function setDeleted(bool $deleted): self
    {
        $this->deleted = $deleted;

        return $this;
    }
}
