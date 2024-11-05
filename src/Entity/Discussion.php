<?php

namespace App\Entity;


use Doctrine\ORM\Mapping as ORM;
use App\Repository\DiscussionRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Symfony\Component\Validator\Constraints as Assert ;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity; 

//use App\Entity\ExecutionContextInterface ;
use Symfony\Component\Validator\Context\ExecutionContextInterface;
use App\Entity\User; 

#[ORM\Entity(repositoryClass: DiscussionRepository::class)]
class Discussion
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: 'integer')]
    private ?int $iddis;
    
    #[ORM\ManyToOne(targetEntity: User::class)]
    #[ORM\JoinColumn(name: 'idreciever', referencedColumnName: 'Id_User')]
    #[Assert\NotBlank(message:"Le contenu de message ne peut pas etre nul")]
    private ?User $receiver;

    #[ORM\Column]
    private ?int $idsender = null;

    #[ORM\Column]
    private ?string $sig = null;

    /**
     * @ORM\OneToMany(targetEntity=Message::class, mappedBy="discussion")
     */
    private $messages;

    public function __construct()
    {
        $this->messages = new ArrayCollection();
    }

    public function getIddis(): ?int
    {
        return $this->iddis;
    }

    public function getReceiver(): ?User
    {
        return $this->receiver;
    }

    public function setReceiver(?User $receiver): self
    {
        $this->receiver = $receiver;
        return $this;
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

    public function getSig(): ?string
    {
        return $this->sig;
    }

    public function setSig(?string $sig): self
    {
        $this->sig = $sig;

        return $this;
    }

    /**
     * @return Collection|Message[]
     */
    public function getMessages(): Collection
    {
        return $this->messages;
    }

    /**
     * @Assert\Callback
     */

}
