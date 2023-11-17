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
    public string $id;

    #[ORM\Column]
    public string $provider;

    #[ORM\Column]
    public string $year;

    #[ORM\Column]
    public string $iso3;

    #[ORM\Column]
    public string $externalId;

    #[ORM\Column]
    public string $name;

    #[ORM\Id]
    #[ORM\Column]
    public int $version;

    #[ORM\Column]
    public DateTime $ts;

    #[ORM\Column]
    public bool $deleted;

}
