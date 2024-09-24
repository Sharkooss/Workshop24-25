<?php

namespace App\Entity;

use App\Repository\ShopRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ShopRepository::class)]
class Shop
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_shop = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $description_shop = null;

    #[ORM\Column]
    private ?float $price_shop = null;

    #[ORM\Column(nullable: true)]
    private ?int $stock_shop = null;

    #[ORM\OneToMany(targetEntity: Buy::class, mappedBy: 'id_shop')]
    private Collection $buys;

    public function __construct()
    {
        $this->buys = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameShop(): ?string
    {
        return $this->name_shop;
    }

    public function setNameShop(string $name_shop): static
    {
        $this->name_shop = $name_shop;

        return $this;
    }

    public function getDescriptionShop(): ?string
    {
        return $this->description_shop;
    }

    public function setDescriptionShop(?string $description_shop): static
    {
        $this->description_shop = $description_shop;

        return $this;
    }

    public function getPriceShop(): ?float
    {
        return $this->price_shop;
    }

    public function setPriceShop(float $price_shop): static
    {
        $this->price_shop = $price_shop;

        return $this;
    }

    public function getStockShop(): ?int
    {
        return $this->stock_shop;
    }

    public function setStockShop(?int $stock_shop): static
    {
        $this->stock_shop = $stock_shop;

        return $this;
    }

    /**
     * @return Collection<int, Buy>
     */
    public function getBuys(): Collection
    {
        return $this->buys;
    }

    public function addBuy(Buy $buy): static
    {
        if (!$this->buys->contains($buy)) {
            $this->buys->add($buy);
            $buy->setIsShop($this);
        }

        return $this;
    }

    public function removeBuy(Buy $buy): static
    {
        if ($this->buys->removeElement($buy)) {
            // set the owning side to null (unless already changed)
            if ($buy->getIsShop() === $this) {
                $buy->setIsShop(null);
            }
        }

        return $this;
    }
}
