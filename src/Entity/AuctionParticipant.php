<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuctionParticipantRepository ;
use Symfony\Component\Validator\Constraints\Date;

#[ORM\Entity(repositoryClass: AuctionParticipantRepository::class)]
class AuctionParticipant
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_AucPart",type: "integer")]
    private ?int $Id_AucPart=null;

    #[ORM\Column(type: "float")]
    private ?float $prix=null;


    #[ORM\Column(name: "date", type: "datetime" , nullable:true)]
    private  $date = null;

    #[ORM\Column(type: "integer")]
    private $love = '0';
    

    #[ORM\Column(type: "integer")]
    private $rating = '0';

    
    #[ORM\JoinColumn(name:"Id_Participant", referencedColumnName:"Id_User")]
    #[ORM\OneToOne(targetEntity :"User")]
    private ?User $idParticipant;

    
    #[ORM\ManyToOne(targetEntity : "Auction")]
    #[ORM\JoinColumn(name:"Id_Auction", referencedColumnName:"id_Auction")]
    private ?Auction $idAuction;

    public function getPrix(): ?float
    {
        return $this->prix;
    }

    public function setPrix(float $prix): static
    {
        $this->prix = $prix;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    
    public function setDate(): void
    {
        $this->date = new \DateTime();
    }

    public function getLove(): ?int
    {
        return $this->love;
    }

    public function setLove(int $love): static
    {
        $this->love = $love;

        return $this;
    }

    public function getRating(): ?int
    {
        return $this->rating;
    }

    public function setRating(int $rating): static
    {
        $this->rating = $rating;

        return $this;
    }

    public function getIdParticipant(): ?User
    {
        return $this->idParticipant;
    }

    public function setIdParticipant(?User $idParticipant): static
    {
        $this->idParticipant = $idParticipant;

        return $this;
    }

    public function getIdAuction(): ?Auction
    {
        return $this->idAuction;
    }

    public function setIdAuction(?Auction $idAuction): static
    {
        $this->idAuction = $idAuction;

        return $this;
    }

    public function getIdAucPart(): ?int
    {
        return $this->Id_AucPart;
    }

    
   

   


}
