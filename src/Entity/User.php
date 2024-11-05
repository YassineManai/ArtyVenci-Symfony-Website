<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints\Date;
use App\Repository\UserRepository;
use DateTime;
use Symfony\Bundle\MakerBundle\Str;

#[ORM\Entity(repositoryClass: UserRepository::class)]
class User
{
   
     #[ORM\Id]
     #[ORM\GeneratedValue]
     #[ORM\Column(name:"Id_User", type:"integer", nullable:false)]
    private ?int $idUser = null;



     #[ORM\Column(length: 255)]
    private ?string $username = null;

    #[ORM\Column(length: 255)]
    private ?string $email = null ;

    #[ORM\Column(length: 255)]
    private ?string $password = null;   

    #[ORM\Column(length: 255)]
    private ?string $role = null;

    #[ORM\Column(length: 255)]
    private ?string $firstname = null;

    #[ORM\Column(length: 255)]
    private ?string  $lastname = null;  

    #[ORM\Column(length: 255)]
    private ?string  $adress = null;

    #[ORM\Column(length: 255)]
    private ?string $phone = null;

    #[ORM\Column(length: 255)]
    private ?string $gender  = null;

    #[ORM\Column(length: 255)]
    private ?string $dob = null ;

    #[ORM\Column(length: 255)]
    private ?string $imageurl = null;

    #[ORM\Column(length: 255)]
    private ?string $status = null;

    public function getIdUser(): ?int
    {
        return $this->idUser;
    }
    public function getUsername(): ?string
    {
        return $this->username;
    }

    public function setUsername(string $username): static
    {
        $this->username = $username;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): static
    {
        $this->email = $email;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    public function getRole(): ?string
    {
        return $this->role;
    }

    public function setRole(string $role): static
    {
        $this->role = $role;

        return $this;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): static
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): static
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getAdress(): ?string
    {
        return $this->adress;
    }

    public function setAdress(string $address): static
    {
        $this->adress = $address;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(string $phone): static
    {
        $this->phone = $phone;

        return $this;
    }

    public function getGender(): ?string
    {
        return $this->gender;
    }

    public function setGender(string $gender): static
    {
        $this->gender = $gender;

        return $this;
    }

    public function getDob(): ?string
    {
        return $this->dob;
    }

    public function setDob(string $dob): static
    {
        $this->dob = $dob;

        return $this;
    }

    public function getImageurl(): ?string
    {
        return $this->imageurl;
    }

    public function setImageurl(string $imageurl): static
    {
        $this->imageurl = $imageurl;

        return $this;
    }

    public function getStatus(): ?string
    {
        return $this->status;
    }

    public function setStatus(string $status): static
    {
        $this->status = $status;

        return $this;
    }


}
