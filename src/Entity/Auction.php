<?php

namespace App\Entity;

use App\Repository\AuctionParticipantRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\AuctionRepository;

#[ORM\Entity(repositoryClass: AuctionRepository::class)]
class Auction
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(type: "integer" , name:"id_Auction")]
    private ?int $idAuction = null;

    #[ORM\Column(length: 150)]
    private ?string $nom = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $dateCloture = null;

    #[ORM\Column(type: "datetime")]
    private ?\DateTimeInterface $dateLancement = null;

    #[ORM\Column(type: "float")]
    private ?float $prixInitial = null;

    #[ORM\Column(name: "prix_final", type: "float", precision: 10, scale: 0, nullable: true)]
    private ?float $prixFinal = null;

    #[ORM\Column(type: "integer")]
    private ?int $idProduit;

    #[ORM\Column(type: "integer")]
    private ?int $idArtist; 


    private ?float $moyRating;

    private ?int $nbreRating;

    public function getIdAuction(): ?int
    {
        return $this->idAuction;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;
        return $this;
    }

    public function getDateCloture(): ?\DateTimeInterface
    {
        return $this->dateCloture;
    }

    public function setDateCloture(?\DateTimeInterface $dateCloture): static
    {
        $this->dateCloture = $dateCloture;

        return $this;
    }

    public function getDateLancement(): ?\DateTimeInterface
    {
        return $this->dateLancement;
    }

    public function setDateLancement(?\DateTimeInterface $dateLancement): static
    {
        $this->dateLancement = $dateLancement;

        return $this;
    }

    public function getPrixInitial(): ?float
    {
        return $this->prixInitial;
    }

    public function setPrixInitial(?float $prixInitial): static
    {
        $this->prixInitial = $prixInitial;

        return $this;
    }

    public function getPrixFinal(): ?float
    {
        return $this->prixFinal;
    }

    public function setPrixFinal(?float $prixFinal): static
    {
        $this->prixFinal = $prixFinal;

        return $this;
    }

    public function getIdProduit(): ?int
    {
        return $this->idProduit;
    }

    public function setIdProduit(int $idProduit): static
    {
        $this->idProduit = $idProduit;

        return $this;
    }

    public function getIdArtist(): ?int
    {
        return $this->idArtist;
    }

    public function setIdArtist(int $idArtist): static
    {
        $this->idArtist = $idArtist;

        return $this;
    }
    




}
