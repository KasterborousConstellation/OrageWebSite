<?php

namespace App\Entity;

use App\Repository\ChapitreRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChapitreRepository::class)]
class Chapitre
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomChapitre = null;

    #[ORM\ManyToOne(inversedBy: 'cours')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Cours $cours = null;

    /**
     * @var Collection<int, Depot>
     */
    #[ORM\ManyToMany(targetEntity: Depot::class)]
    private Collection $depots;

    /**
     * @var Collection<int, FicheExercice>
     */
    #[ORM\ManyToMany(targetEntity: FicheExercice::class, mappedBy: 'ChapitreRequis')]
    private Collection $exercicesneedchapter;

    public function __construct()
    {
        $this->depots = new ArrayCollection();
        $this->exercicesneedchapter = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomChapitre(): ?string
    {
        return $this->nomChapitre;
    }

    public function setNomChapitre(string $nomChapitre): static
    {
        $this->nomChapitre = $nomChapitre;

        return $this;
    }

    public function getCours(): ?Cours
    {
        return $this->cours;
    }

    public function setCours(?Cours $cours): static
    {
        $this->cours = $cours;

        return $this;
    }

    /**
     * @return Collection<int, Depot>
     */
    public function getDepots(): Collection
    {
        return $this->depots;
    }

    public function addDepot(Depot $depot): static
    {
        if (!$this->depots->contains($depot)) {
            $this->depots->add($depot);
        }

        return $this;
    }

    public function removeDepot(Depot $depot): static
    {
        $this->depots->removeElement($depot);

        return $this;
    }

    /**
     * @return Collection<int, FicheExercice>
     */
    public function getExercicesneedchapter(): Collection
    {
        return $this->exercicesneedchapter;
    }

    public function addExercicesneedchapter(FicheExercice $exercicesneedchapter): static
    {
        if (!$this->exercicesneedchapter->contains($exercicesneedchapter)) {
            $this->exercicesneedchapter->add($exercicesneedchapter);
            $exercicesneedchapter->addChapitreRequi($this);
        }

        return $this;
    }

    public function removeExercicesneedchapter(FicheExercice $exercicesneedchapter): static
    {
        if ($this->exercicesneedchapter->removeElement($exercicesneedchapter)) {
            $exercicesneedchapter->removeChapitreRequi($this);
        }

        return $this;
    }
    public function getLatestDepot(): ?Depot
    {
        $latest = null;
        $version = -1;
        foreach ($this->depots as $depot) {
            if( $depot->getVersion() > $version ) {
                $latest = $depot;
                $version = $depot->getVersion();
            }
        }
        return $latest;
    }
}
