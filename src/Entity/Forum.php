<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ForumRepository;
use Symfony\Component\Validator\Constraints\Date;

use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ForumRepository::class)]
class Forum
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idForum=null;

    #[Assert\NotBlank(message: "Le titre ne peut pas etre null")]
    #[ORM\Column(length: 255)]
    private ?string $title=null;

    #[Assert\NotBlank(message: "La description ne peut pas etre null")]
    #[ORM\Column(length: 255)]
    private ?string $description=null;

    #[ORM\Column]
    private ?int $repliesNumber=null;

    #[ORM\Column(name: "date", type: "date", nullable: false)]
    private $date = null;

    public function getIdForum(): ?int
    {
        return $this->idForum;
    }
    public function setIdForum(int $id): static
    {
        $this->idForum = $id;
        
        return $this;
    }

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

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

    public function getRepliesNumber(): ?int
    {
        return $this->repliesNumber;
    }

    public function setRepliesNumber(int $repliesNumber): static
    {
        $this->repliesNumber = $repliesNumber;

        return $this;
    }

    public function getDate():\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

}
