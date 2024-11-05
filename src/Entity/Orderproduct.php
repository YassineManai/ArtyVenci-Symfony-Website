<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\OrderRepository;

#[ORM\Entity(repositoryClass: OrderRepository::class)]
class Orderproduct
{
    #[ORM\Id]
    #[ORM\GeneratedValue(strategy: "IDENTITY")]
    #[ORM\Column(name: "Id_Order", type: "integer", nullable: false)]
    private ?int $idOrder;

    #[ORM\Column(name: "Price", type: "float", precision: 10, scale: 0, nullable: false)]
    private ?float $price;

    #[ORM\Column(name: "Title", type: "string", length: 255, nullable: false)]
    private ?string $title;

    #[ORM\Column(name: "OrderDate", type: "string", length: 255, nullable: false)]
    private ?string $orderdate;

    #[ORM\Column(name: "Prod_img", type: "string", length: 255, nullable: false)]
    private ?string $prodImg;

    #[ORM\ManyToOne(targetEntity: "Product")]
    #[ORM\JoinColumn(name: "Id_Product", referencedColumnName: "Id_Product")]
    private ?Product $idProduct;


    public function getIdOrder(): ?int
    {
        return $this->idOrder;
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

    public function getTitle(): ?string
    {
        return $this->title;
    }

    public function setTitle(string $title): static
    {
        $this->title = $title;

        return $this;
    }

    public function getOrderdate(): ?string
    {
        return $this->orderdate;
    }

    public function setOrderdate(string $orderdate): static
    {
        $this->orderdate = $orderdate;

        return $this;
    }

    public function getProdImg(): ?string
    {
        return $this->prodImg;
    }

    public function setProdImg(string $prodImg): static
    {
        $this->prodImg = $prodImg;

        return $this;
    }

    public function getIdProduct(): ?Product
    {
        return $this->idProduct;
    }

    public function setIdProduct(?Product $idProduct): static
    {
        $this->idProduct = $idProduct;

        return $this;
    }


}
