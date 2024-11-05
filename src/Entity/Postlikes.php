<?php

namespace App\Entity;
use App\Repository\PostlikesRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PostlikesRepository::class)]

class Postlikes
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $idPostlike;

    #[ORM\Column]
    private ?int $likePost=null;

    #[ORM\Column]
    private ?int $user=null;


    #[ORM\ManyToOne(targetEntity : "Post")]
    #[ORM\JoinColumn(name:"post", referencedColumnName:"id_post")]
    private ?Post $post=null;

    public function getIdPostlike(): ?string
    {
        return $this->idPostlike;
    }

    public function getLikePost(): ?string
    {
        return $this->likePost;
    }

    public function setLikePost(string $likePost): static
    {
        $this->likePost = $likePost;

        return $this;
    }

    public function getUser(): ?string
    {
        return $this->user;
    }

    public function setUser(string $user): static
    {
        $this->user = $user;

        return $this;
    }

    public function getPost(): ?Post
    {
        return $this->post;
    }

    public function setPost(?Post $post): static
    {
        $this->post = $post;

        return $this;
    }

}
