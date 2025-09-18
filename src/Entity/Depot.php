<?php

namespace App\Entity;

use App\Repository\DepotRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: DepotRepository::class)]
class Depot
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $heure_depot = null;

    #[ORM\Column(length: 1024)]
    private ?string $pathPDF = null;

    #[ORM\Column]
    private ?int $version = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getHeureDepot(): ?\DateTimeImmutable
    {
        return $this->heure_depot;
    }

    public function setHeureDepot(\DateTimeImmutable $heure_depot): static
    {
        $this->heure_depot = $heure_depot;

        return $this;
    }

    public function getPathPDF(): ?string
    {
        return $this->pathPDF;
    }

    public function setPathPDF(string $pathPDF): static
    {
        $this->pathPDF = $pathPDF;

        return $this;
    }

    public function getVersion(): ?int
    {
        return $this->version;
    }

    public function setVersion(int $version): static
    {
        $this->version = $version;

        return $this;
    }
}
