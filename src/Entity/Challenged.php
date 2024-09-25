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
    private ?Users $challenger = null; // L'utilisateur qui lance le défi

    #[ORM\ManyToOne(inversedBy: 'challengeds')]
    private ?Users $opponent = null; // L'utilisateur qui est défié

    #[ORM\ManyToOne(inversedBy: 'challengeds')]
    private ?Challenge $id_challenge = null;

    #[ORM\Column(nullable: true)]
    private ?int $amount = null;

    #[ORM\Column(nullable: true)]
    private ?int $selectedWinnerChallenger = null; // Le gagnant sélectionné par le challenger

    #[ORM\Column(nullable: true)]
    private ?int $selectedWinnerOpponent = null; // Le gagnant sélectionné par l'opponent

    #[ORM\Column(length: 20)]
    private ?string $status = 'pending'; // Champ status ajouté précédemment

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getChallenger(): ?Users
    {
        return $this->challenger;
    }

    public function setChallenger(?Users $challenger): static
    {
        $this->challenger = $challenger;

        return $this;
    }

    public function getOpponent(): ?Users
    {
        return $this->opponent;
    }

    public function setOpponent(?Users $opponent): static
    {
        $this->opponent = $opponent;

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

    public function getSelectedWinnerChallenger(): ?int
    {
        return $this->selectedWinnerChallenger;
    }

    public function setSelectedWinnerChallenger(?int $selectedWinnerChallenger): static
    {
        $this->selectedWinnerChallenger = $selectedWinnerChallenger;

        return $this;
    }

    public function getSelectedWinnerOpponent(): ?int
    {
        return $this->selectedWinnerOpponent;
    }

    public function setSelectedWinnerOpponent(?int $selectedWinnerOpponent): static
    {
        $this->selectedWinnerOpponent = $selectedWinnerOpponent;

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