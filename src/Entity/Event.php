<?php

namespace App\Entity;

use App\Repository\EventRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: EventRepository::class)]
class Event
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_event = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $description_event = null;

    #[ORM\ManyToOne(inversedBy: 'events')]
    private ?users $id_users = null;

    #[ORM\OneToMany(targetEntity: Participate::class, mappedBy: 'id_event')]
    private Collection $participates;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $cash_prise = null;

    #[ORM\Column(length: 10)]
    private ?string $status = 'open'; // Ajout du champ statut avec une valeur par défaut "open"

    public function __construct()
    {
        $this->participates = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameEvent(): ?string
    {
        return $this->name_event;
    }

    public function setNameEvent(string $name_event): static
    {
        $this->name_event = $name_event;
        return $this;
    }

    public function getDescriptionEvent(): ?string
    {
        return $this->description_event;
    }

    public function setDescriptionEvent(?string $description_event): static
    {
        $this->description_event = $description_event;
        return $this;
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

    public function getCashPrise(): ?int
    {
        return $this->cash_prise;
    }

    public function setCashPrise(?int $cash_prise): static
    {
        $this->cash_prise = $cash_prise;
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

    /**
     * @return Collection<int, Participate>
     */
    public function getParticipates(): Collection
    {
        return $this->participates;
    }

    public function addParticipate(Participate $participate): static
    {
        if (!$this->participates->contains($participate)) {
            $this->participates->add($participate);
            $participate->setIdEvent($this);
        }

        return $this;
    }

    public function removeParticipate(Participate $participate): static
    {
        if ($this->participates->removeElement($participate)) {
            if ($participate->getIdEvent() === $this) {
                $participate->setIdEvent(null);
            }
        }

        return $this;
    }
}
