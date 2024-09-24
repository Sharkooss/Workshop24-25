<?php

namespace App\Entity;

use App\Repository\ChallengeRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ChallengeRepository::class)]
class Challenge
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name_challenge = null;

    #[ORM\Column(type: Types::TEXT)]
    private ?string $descritpion_challenge = null;

    #[ORM\OneToMany(targetEntity: Challenged::class, mappedBy: 'id_challenge')]
    private Collection $challengeds;

    public function __construct()
    {
        $this->challengeds = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getNameChallenge(): ?string
    {
        return $this->name_challenge;
    }

    public function setNameChallenge(string $name_challenge): static
    {
        $this->name_challenge = $name_challenge;

        return $this;
    }

    public function getDescritpionChallenge(): ?string
    {
        return $this->descritpion_challenge;
    }

    public function setDescritpionChallenge(string $descritpion_challenge): static
    {
        $this->descritpion_challenge = $descritpion_challenge;

        return $this;
    }

    /**
     * @return Collection<int, Challenged>
     */
    public function getChallengeds(): Collection
    {
        return $this->challengeds;
    }

    public function addChallenged(Challenged $challenged): static
    {
        if (!$this->challengeds->contains($challenged)) {
            $this->challengeds->add($challenged);
            $challenged->setIdChallenge($this);
        }

        return $this;
    }

    public function removeChallenged(Challenged $challenged): static
    {
        if ($this->challengeds->removeElement($challenged)) {
            // set the owning side to null (unless already changed)
            if ($challenged->getIdChallenge() === $this) {
                $challenged->setIdChallenge(null);
            }
        }

        return $this;
    }
}
