<?php
namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PostRepository;
use Symfony\Component\Validator\Constraints\DateTime;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PostRepository::class)]
class Post
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $idPost = null;

    #[Assert\NotBlank(message: "Le Titre ne peut pas etre null")]
    #[ORM\Column(length: 255)]
    private ?string $title=null;

    #[Assert\NotBlank(message: "Le message ne peut pas etre null")]
    #[ORM\Column(length: 255)]
    private ?string $textmessage=null;

    #[ORM\Column]
    private ?int $likeNumber=null;

    #[ORM\Column(name: "TimeofCreation", type: "datetime", nullable: false)]
    private  $timeofcreation = null;

    #[ORM\ManyToOne(targetEntity : "Forum")]
    #[ORM\JoinColumn(name:"id_forum", referencedColumnName:"id_forum")]
    private ?Forum $idForum= null;

    #[ORM\ManyToOne(targetEntity : "User")]
    #[ORM\JoinColumn(name:"id_user", referencedColumnName:"Id_User")]
    private ?User $idUser=null;

    public function getIdPost(): ?string
    {
        return $this->idPost;
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

    public function getTextmessage(): ?string
    {
        return $this->textmessage;
    }

    public function setTextmessage(string $textmessage): static
    {
        $this->textmessage = $textmessage;

        return $this;
    }

    public function getLikeNumber(): ?string
    {
        return $this->likeNumber;
    }

    public function setLikeNumber(string $likeNumber): static
    {
        $this->likeNumber = $likeNumber;

        return $this;
    }

    public function getTimeofcreation(): ?\DateTimeInterface
    {
        return $this->timeofcreation;
    }

    public function setTimeofcreation(\DateTimeInterface $timeofcreation): static
    {
        $this->timeofcreation = $timeofcreation;

        return $this;
    }

    public function getIdForum(): ?Forum
    {
        return $this->idForum;
    }

    public function setIdForum(?Forum $idForum): static
    {
        $this->idForum = $idForum;

        return $this;
    }

    public function getIdUser(): ?User
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }

}
