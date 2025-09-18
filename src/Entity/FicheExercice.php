<?php

namespace App\Entity;

use App\Repository\FicheExerciceRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FicheExerciceRepository::class)]
class FicheExercice
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $nomFicheExercice = null;

    #[ORM\Column(length: 1000)]
    private ?string $description = null;

    /**
     * @var Collection<int, Depot>
     *
     */
    #[Orm\JoinTable(name: 'correction_fiche_exercice')]
    #[ORM\ManyToMany(targetEntity: Depot::class)]
    private Collection $correction_depot;

    /**
     * @var Collection<int, Depot>
     *
     */

    #[ORM\ManyToMany(targetEntity: Depot::class)]
    private Collection $fiche_depot;

    /**
     * @var Collection<int, Chapitre>
     */
    #[ORM\ManyToMany(targetEntity: Chapitre::class, inversedBy: 'exercicesneedchapter')]
    private Collection $ChapitreRequis;

    public function __construct()
    {
        $this->correction_depot = new ArrayCollection();
        $this->fiche_depot = new ArrayCollection();
        $this->ChapitreRequis = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomFicheExercice(): ?string
    {
        return $this->nomFicheExercice;
    }

    public function setNomFicheExercice(string $nomFicheExercice): static
    {
        $this->nomFicheExercice = $nomFicheExercice;

        return $this;
    }

    public function getDescription(): ?string
    {
        return $this->description;
    }

    public function setDescription(string $description): static
    {
        $this->description = $description;

        return $this;
    }

    /**
     * @return Collection<int, Depot>
     */
    public function getCorrectionDepot(): Collection
    {
        return $this->correction_depot;
    }

    public function addCorrectionDepot(Depot $correctionDepot): static
    {
        if (!$this->correction_depot->contains($correctionDepot)) {
            $this->correction_depot->add($correctionDepot);
        }

        return $this;
    }

    public function removeCorrectionDepot(Depot $correctionDepot): static
    {
        $this->correction_depot->removeElement($correctionDepot);

        return $this;
    }

    /**
     * @return Collection<int, Depot>
     */
    public function getFicheDepot(): Collection
    {
        return $this->fiche_depot;
    }

    public function addFicheDepot(Depot $ficheDepot): static
    {
        if (!$this->fiche_depot->contains($ficheDepot)) {
            $this->fiche_depot->add($ficheDepot);
        }

        return $this;
    }

    public function removeFicheDepot(Depot $ficheDepot): static
    {
        $this->fiche_depot->removeElement($ficheDepot);

        return $this;
    }

    /**
     * @return Collection<int, Chapitre>
     */
    public function getChapitreRequis(): Collection
    {
        return $this->ChapitreRequis;
    }

    public function addChapitreRequi(Chapitre $chapitreRequi): static
    {
        if (!$this->ChapitreRequis->contains($chapitreRequi)) {
            $this->ChapitreRequis->add($chapitreRequi);
        }

        return $this;
    }

    public function removeChapitreRequi(Chapitre $chapitreRequi): static
    {
        $this->ChapitreRequis->removeElement($chapitreRequi);

        return $this;
    }
}
