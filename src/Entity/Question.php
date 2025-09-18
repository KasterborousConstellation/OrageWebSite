<?php

namespace App\Entity;

use App\Repository\QuestionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: QuestionRepository::class)]
class Question
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $formatResponse = null;

    #[ORM\Column(length: 2500)]
    private ?string $intitule = null;

    /**
     * @var Collection<int, Reponse>
     */
    #[Orm\JoinTable(name: 'wrong_answer')]
    #[ORM\ManyToMany(targetEntity: Reponse::class, inversedBy: 'wrongAnswer')]
    private Collection $wrongAnswers;

    /**
     * @var Collection<int, Reponse>
     */
    #[Orm\JoinTable(name: 'correct_answer')]
    #[ORM\ManyToMany(targetEntity: Reponse::class, inversedBy: 'correctAnswers')]
    private Collection $correctAnswers;

    public function __construct()
    {
        $this->wrongAnswers = new ArrayCollection();
        $this->correctAnswers = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFormatResponse(): ?string
    {
        return $this->formatResponse;
    }

    public function setFormatResponse(string $formatResponse): static
    {
        $this->formatResponse = $formatResponse;

        return $this;
    }

    public function getIntitule(): ?string
    {
        return $this->intitule;
    }

    public function setIntitule(string $intitule): static
    {
        $this->intitule = $intitule;

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getWrongAnswers(): Collection
    {
        return $this->wrongAnswers;
    }

    public function addWrongAnswer(Reponse $wrongAnswer): static
    {
        if (!$this->wrongAnswers->contains($wrongAnswer)) {
            $this->wrongAnswers->add($wrongAnswer);
        }

        return $this;
    }

    public function removeWrongAnswer(Reponse $wrongAnswer): static
    {
        $this->wrongAnswers->removeElement($wrongAnswer);

        return $this;
    }

    /**
     * @return Collection<int, Reponse>
     */
    public function getCorrectAnswers(): Collection
    {
        return $this->correctAnswers;
    }

    public function addCorrectAnswer(Reponse $correctAnswer): static
    {
        if (!$this->correctAnswers->contains($correctAnswer)) {
            $this->correctAnswers->add($correctAnswer);
        }

        return $this;
    }

    public function removeCorrectAnswer(Reponse $correctAnswer): static
    {
        $this->correctAnswers->removeElement($correctAnswer);

        return $this;
    }
}
