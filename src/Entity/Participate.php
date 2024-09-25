<?php

namespace App\Entity;

use App\Repository\ParticipateRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipateRepository::class)]
class Participate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'participates')]
    private ?Users $id_users = null;

    #[ORM\ManyToOne(inversedBy: 'participates')]
    private ?Event $id_event = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $units_amount = null; // Ajout de la propriété

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $ranking = null; // Ajout du ranking aussi si nécessaire

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

    public function getIdEvent(): ?Event
    {
        return $this->id_event;
    }

    public function setIdEvent(?Event $id_event): static
    {
        $this->id_event = $id_event;

        return $this;
    }

    // Getter et setter pour units_amount
    public function getUnitsAmount(): ?int
    {
        return $this->units_amount;
    }

    public function setUnitsAmount(?int $units_amount): static
    {
        $this->units_amount = $units_amount;

        return $this;
    }

    // Getter et setter pour ranking
    public function getRanking(): ?int
    {
        return $this->ranking;
    }

    public function setRanking(?int $ranking): static
    {
        $this->ranking = $ranking;

        return $this;
    }
}
