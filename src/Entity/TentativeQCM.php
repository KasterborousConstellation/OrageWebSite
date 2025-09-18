<?php

namespace App\Entity;

use App\Repository\TentativeQCMRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: TentativeQCMRepository::class)]
class TentativeQCM
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?float $note = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $at = null;

    /**
     * @var Collection<int, User>
     */
    #[ORM\ManyToMany(targetEntity: User::class, inversedBy: 'tentatives')]
    private Collection $tryQCM;

    #[ORM\ManyToOne(inversedBy: 'triedQCM')]
    #[ORM\JoinColumn(nullable: false)]
    private ?QCM $relQCM = null;

    public function __construct()
    {
        $this->tryQCM = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNote(): ?float
    {
        return $this->note;
    }

    public function setNote(float $note): static
    {
        $this->note = $note;

        return $this;
    }

    public function getAt(): ?\DateTimeImmutable
    {
        return $this->at;
    }

    public function setAt(?\DateTimeImmutable $at): static
    {
        $this->at = $at;

        return $this;
    }

    /**
     * @return Collection<int, User>
     */
    public function getTryQCM(): Collection
    {
        return $this->tryQCM;
    }

    public function addTryQCM(User $tryQCM): static
    {
        if (!$this->tryQCM->contains($tryQCM)) {
            $this->tryQCM->add($tryQCM);
        }

        return $this;
    }

    public function removeTryQCM(User $tryQCM): static
    {
        $this->tryQCM->removeElement($tryQCM);

        return $this;
    }

    public function getRelQCM(): ?QCM
    {
        return $this->relQCM;
    }

    public function setRelQCM(?QCM $relQCM): static
    {
        $this->relQCM = $relQCM;

        return $this;
    }
}
