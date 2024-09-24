<?php

namespace App\Entity;

use App\Repository\BuyRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: BuyRepository::class)]
class Buy
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'buys')]
    private ?users $id_users = null;

    #[ORM\ManyToOne(inversedBy: 'buys')]
    private ?shop $id_shop = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsers(): ?users
    {
        return $this->id_users;
    }

    public function setIdUsers(?users $id_users): static
    {
        $this->id_users = $id_users;

        return $this;
    }

    public function getIsShop(): ?shop
    {
        return $this->id_shop;
    }

    public function setIsShop(?shop $id_shop): static
    {
        $this->id_shop = $id_shop;

        return $this;
    }
}
