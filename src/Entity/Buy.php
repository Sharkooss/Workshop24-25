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
    private ?Users $id_users = null;

    #[ORM\ManyToOne(inversedBy: 'buys')]
    private ?Shop $id_shop = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getIdUsers(): ?Users
    {
        return $this->id_users;
    }

    public function setIdUsers(?Users $id_users): static
    {
        $this->id_users = $id_users;

        return $this;
    }

    public function getIsShop(): ?Shop
    {
        return $this->id_shop;
    }

    public function setIsShop(?Shop $id_shop): static
    {
        $this->id_shop = $id_shop;

        return $this;
    }
}
