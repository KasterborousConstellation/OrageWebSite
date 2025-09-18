<?php

namespace App\Entity;

use App\Repository\ReponseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ReponseRepository::class)]
class Reponse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 1000)]
    private ?string $libele = null;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'wrongAnswers')]
    private Collection $wrongAnswer;

    /**
     * @var Collection<int, Question>
     */
    #[ORM\ManyToMany(targetEntity: Question::class, mappedBy: 'correctAnswers')]
    private Collection $correctAnswers;

    public function __construct()
    {
        $this->wrongAnswer = new ArrayCollection();
        $this->correctAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLibele(): ?string
    {
        return $this->libele;
    }

    public function setLibele(string $libele): static
    {
        $this->libele = $libele;

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getWrongAnswer(): Collection
    {
        return $this->wrongAnswer;
    }

    public function addWrongAnswer(Question $wrongAnswer): static
    {
        if (!$this->wrongAnswer->contains($wrongAnswer)) {
            $this->wrongAnswer->add($wrongAnswer);
            $wrongAnswer->addWrongAnswer($this);
        }

        return $this;
    }

    public function removeWrongAnswer(Question $wrongAnswer): static
    {
        if ($this->wrongAnswer->removeElement($wrongAnswer)) {
            $wrongAnswer->removeWrongAnswer($this);
        }

        return $this;
    }

    /**
     * @return Collection<int, Question>
     */
    public function getCorrectAnswers(): Collection
    {
        return $this->correctAnswers;
    }

    public function addCorrectAnswer(Question $correctAnswer): static
    {
        if (!$this->correctAnswers->contains($correctAnswer)) {
            $this->correctAnswers->add($correctAnswer);
            $correctAnswer->addCorrectAnswer($this);
        }

        return $this;
    }

    public function removeCorrectAnswer(Question $correctAnswer): static
    {
        if ($this->correctAnswers->removeElement($correctAnswer)) {
            $correctAnswer->removeCorrectAnswer($this);
        }

        return $this;
    }
}
