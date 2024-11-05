<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\EventRepository;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{




    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "Id_Event", type: "integer", nullable: false)]
    private $idEvent;


    #[ORM\Column(name: "E_Name", type: "string", length: 255, nullable: false)]

    private $eName;


    #[ORM\Column(name: "Place", type: "string", length: 255, nullable: false)]
    private $place;


    #[ORM\Column(name: "E_Date", type: "date",  nullable: false)]
    private $eDate;


    #[ORM\Column(name: "Ticket_Price", type: "float",  nullable: false, precision: 10, scale: 0)]
    private $ticketPrice;

    #[ORM\Column(name: "image", type: "string", length: 500, nullable: false)]
    private $image;

    public function getIdEvent(): ?int
    {
        return $this->idEvent;
    }

    public function getEName(): ?string
    {
        return $this->eName;
    }

    public function setEName(string $eName): static
    {
        $this->eName = $eName;

        return $this;
    }

    public function getPlace(): ?string
    {
        return $this->place;
    }

    public function setPlace(string $place): static
    {
        $this->place = $place;

        return $this;
    }

    public function getEDate(): ?\DateTimeInterface
    {
        return $this->eDate;
    }

    public function setEDate(\DateTimeInterface $eDate): static
    {
        $this->eDate = $eDate;

        return $this;
    }

    public function getTicketPrice(): ?float
    {
        return $this->ticketPrice;
    }

    public function setTicketPrice(float $ticketPrice): static
    {
        $this->ticketPrice = $ticketPrice;

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
}
