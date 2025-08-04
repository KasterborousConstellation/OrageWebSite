<?php

namespace App\Entity;

use App\Repository\FaqQuestionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FaqQuestionRepository::class)]
class FaqQuestion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $question_name = null;

    #[ORM\Column(length: 2000)]
    private ?string $description = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getQuestionName(): ?string
    {
        return $this->question_name;
    }

    public function setQuestionName(string $question_name): static
    {
        $this->question_name = $question_name;

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
}
