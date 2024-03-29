<?php

namespace App\Entity;

use ApiPlatform\Metadata\ApiResource;
use App\Repository\ProviderRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;

#[ApiResource()]
#[ORM\Entity(repositoryClass: ProviderRepository::class)]
class Provider
{
    #[ORM\Id]
    #[ORM\Column]
    #[Groups(['ochapresence_read', 'ochapresence_external_read'])]
    private ?string $id = null;

    #[ORM\Column(length: 255)]
    #[Groups(['ochapresence_read', 'ochapresence_external_read'])]
    private ?string $name = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $prefix = null;

    #[ORM\Column(length: 255)]
    private ?string $expand = null;

    public function getId(): ?string
    {
        return $this->id;
    }

    public function setId(string $id): ?self
    {
        $this->id = $id;

        return $this;
    }

    public function getPrefix(): ?string
    {
        return $this->prefix;
    }

    public function setPrefix(?string $prefix): self
    {
        $this->prefix = $prefix;

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

    public function getExpand(): ?string
    {
        return $this->expand;
    }

    public function setExpand(string $expand): self
    {
        $this->expand = $expand;

        return $this;
    }

}
