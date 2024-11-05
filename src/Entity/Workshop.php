<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

use App\Repository\WorkshopRepository;
#[ORM\Entity(repositoryClass: WorkshopRepository::class)]
class Workshop
{


    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name:"Id_Workshop", type:"integer", nullable:false)]
    private $idWorkshop;

  
    #[ORM\Column(name:"Title", type:"string", length:255,nullable:false)]
    private $title;

   
    #[ORM\Column(name:"Details", type:"string", length:255,nullable:false)]
    private $details;

   

     #[ORM\Column(name:"image", type:"string", length:255,nullable:false)]

    private $image;

  
     #[ORM\Column(name:"Id_Event", type:"integer", length:255,nullable:false)]
    private $idEvent;

    public function getIdWorkshop(): ?int
    {
        return $this->idWorkshop;
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

    public function getDetails(): ?string
    {
        return $this->details;
    }

    public function setDetails(string $details): static
    {
        $this->details = $details;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): static
    {
        $this->image = $image;

        return $this;
    }

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function setIdEvent(int $idEvent): static
    {
        $this->idEvent = $idEvent;

        return $this;
    }


}
