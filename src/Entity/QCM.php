<?php

namespace App\Entity;

use App\Repository\QCMRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QCMRepository::class)]
class QCM
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 100)]
    private ?string $nomQCM = null;

    #[ORM\Column]
    private ?float $noteMax = null;

    /**
     * @var Collection<int, TentativeQCM>
     */
    #[ORM\OneToMany(targetEntity: TentativeQCM::class, mappedBy: 'relQCM', orphanRemoval: true)]
    private Collection $triedQCM;

    public function __construct()
    {
        $this->triedQCM = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNomQCM(): ?string
    {
        return $this->nomQCM;
    }

    public function setNomQCM(string $nomQCM): static
    {
        $this->nomQCM = $nomQCM;

        return $this;
    }

    public function getNoteMax(): ?float
    {
        return $this->noteMax;
    }

    public function setNoteMax(float $noteMax): static
    {
        $this->noteMax = $noteMax;

        return $this;
    }

    /**
     * @return Collection<int, TentativeQCM>
     */
    public function getTriedQCM(): Collection
    {
        return $this->triedQCM;
    }

    public function addTriedQCM(TentativeQCM $triedQCM): static
    {
        if (!$this->triedQCM->contains($triedQCM)) {
            $this->triedQCM->add($triedQCM);
            $triedQCM->setRelQCM($this);
        }

        return $this;
    }

    public function removeTriedQCM(TentativeQCM $triedQCM): static
    {
        if ($this->triedQCM->removeElement($triedQCM)) {
            // set the owning side to null (unless already changed)
            if ($triedQCM->getRelQCM() === $this) {
                $triedQCM->setRelQCM(null);
            }
        }

        return $this;
    }
}
