<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\ProductRepository;

#[ORM\Entity(repositoryClass: ProductRepository::class)]
class Product
{

    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: "Id_Product", type: "integer", nullable: false)]
    private ?int $idProduct=null;

    #[ORM\Column(length: 255)]
    private ?string $title=null;

    #[ORM\Column(length: 255)]
    private ?string $description=null;


    #[ORM\Column(name: "ForSale", type: "boolean")]
    private ?bool $forsale=null;

    #[ORM\Column(name: "Price", type: "float", precision: 10, scale: 0)]
    private ?float $price=null;

    #[ORM\Column(name:"CreationDate", type:"string", length:255)]
    private ?String $creationdate=null;

    #[ORM\Column(name: "ProductImage", type: "string", length: 255, nullable: false)]
    private string $productimage;

    #[ORM\ManyToOne(targetEntity: "User")]
    #[ORM\JoinColumn(name: "Id_User", referencedColumnName: "Id_User")]
    private ?User $idUser=null;

    public function getIdProduct(): ?int
    {
        return $this->idProduct;
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

    public function isForsale(): ?bool
    {
        return $this->forsale;
    }

    public function setForsale(bool $forsale): static
    {
        $this->forsale = $forsale;

        return $this;
    }

    public function getPrice(): ?float
    {
        return $this->price;
    }

    public function setPrice(float $price): static
    {
        $this->price = $price;

        return $this;
    }

    public function getCreationdate(): ?string
    {
        return $this->creationdate;
    }

    public function setCreationdate(string $creationdate): static
    {
        $this->creationdate = $creationdate;

        return $this;
    }

    public function getProductimage(): ?string
    {
        return $this->productimage;
    }

    public function setProductimage(string $productimage): static
    {
        $this->productimage = $productimage;

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
