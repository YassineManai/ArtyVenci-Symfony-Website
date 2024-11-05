<?php

namespace App\Entity;
use App\Entity\Event;
use Doctrine\ORM\Mapping as ORM;



 use App\Repository\ParticipationRepository;
#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
   
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name:"Id_Participation", type:"integer", nullable:false)]
    private $idParticipation;

    
    #[ORM\ManyToOne(targetEntity : "Event")]
    #[ORM\JoinColumn(name:"Id_Event", referencedColumnName:"Id_Event")]
    private $idEvent;

  
     #[ORM\ManyToOne(targetEntity : "User")]
     #[ORM\JoinColumn(name:"Id_User", referencedColumnName:"Id_User")]
    private $idUser;

    public function getIdParticipation(): ?int
    {
        return $this->idParticipation;
    }

    public function getIdEvent() 
    {
        return $this->idEvent;
    }

    public function setIdEvent(?Event $idEvent): static
    {
        $this->idEvent = $idEvent;

        return $this;
    }

    public function getIdUser()
    {
        return $this->idUser;
    }

    public function setIdUser(?User $idUser): static
    {
        $this->idUser = $idUser;

        return $this;
    }


}
