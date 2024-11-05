<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\MessageRepository;
use Symfony\Component\Validator\Constraints as Assert ;

#[ORM\Entity(repositoryClass: MessageRepository::class)]
class Message
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idmsg;

    #[ORM\Column(nullable: true)]
    private ?int $idsender;

    #[ORM\Column(nullable: true)]
    private ?int $iddis;

    #[Assert\NotBlank(message:"Le contenu de message ne peut pas etre nul")]
    #[ORM\Column(nullable: true)]
    private ?string $content;

    #[ORM\Column(nullable: true)]
    private ?string $reaction;

    #[ORM\Column(nullable: true)]
    private ?int $vu;

    #[ORM\Column(type: 'datetime', nullable: true)]
    private ?\DateTimeInterface $datasent;

    public function getIdmsg(): ?int
    {
        return $this->idmsg;
    }

    public function getIdsender(): ?int
    {
        return $this->idsender;
    }

    public function setIdsender(?int $idsender): self
    {
        $this->idsender = $idsender;

        return $this;
    }

    public function getIddis(): ?int
    {
        return $this->iddis;
    }

    public function setIddis(?int $iddis): self
    {
        $this->iddis = $iddis;

        return $this;
    }

    public function getContent(): ?string
    {
        return $this->content;
    }

    public function setContent(?string $content): self
    {
        $this->content = $content;

        return $this;
    }

    public function getReaction(): ?string
    {
        return $this->reaction;
    }

    public function setReaction(?string $reaction): self
    {
        $this->reaction = $reaction;

        return $this;
    }

    public function getVu(): ?int
    {
        return $this->vu;
    }

    public function setVu(?int $vu): self
    {
        $this->vu = $vu;

        return $this;
    }

    public function getDatasent(): ?\DateTimeInterface
    {
        return $this->datasent;
    }

    public function setDatasent(?\DateTimeInterface $datasent): self
    {
        $this->datasent = $datasent;

        return $this;
    }
}
