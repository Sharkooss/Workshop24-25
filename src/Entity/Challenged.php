<?php

namespace App\Entity;

use App\Repository\ChallengedRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChallengedRepository::class)]
class Challenged
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'challengeds')]
    private ?users $id_users = null;

    #[ORM\ManyToOne(inversedBy: 'challengeds')]
    private ?Challenge $id_challenge = null;

    #[ORM\Column(nullable: true)]
    private ?int $amount = null;

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

    public function getIdChallenge(): ?Challenge
    {
        return $this->id_challenge;
    }

    public function setIdChallenge(?Challenge $id_challenge): static
    {
        $this->id_challenge = $id_challenge;

        return $this;
    }

    public function getAmount(): ?int
    {
        return $this->amount;
    }

    public function setAmount(?int $amount): static
    {
        $this->amount = $amount;

        return $this;
    }
}
