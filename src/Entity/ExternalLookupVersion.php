<?php

namespace App\Entity;

use App\Repository\ExternalLookupVersionRepository;
use DateTime;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ExternalLookupVersionRepository::class)]
class ExternalLookupVersion
{

    #[ORM\Id]
    #[ORM\Column(length: 255)]
    public string $id;

    #[ORM\Column(length: 255)]
    public string $provider;

    #[ORM\Column(length: 4)]
    public string $year;

    #[ORM\Column(length: 3)]
    public string $iso3;

    #[ORM\Column(length: 255)]
    public string $externalId;

    #[ORM\Column(length: 255)]
    public string $name;

    #[ORM\Id]
    #[ORM\Column]
    public int $version;

    #[ORM\Column]
    public DateTime $ts;

    #[ORM\Column]
    public bool $deleted;

}
