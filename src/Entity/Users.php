<?php

namespace App\Entity;

use App\Repository\UsersRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;


#[ORM\Entity(repositoryClass: UsersRepository::class)]
class Users implements UserInterface, PasswordAuthenticatedUserInterface
{
    // Ajoute cette validation pour plainPassword (non stockée dans la base de données)
    private ?string $plainPassword = null;

    #[ORM\Column(length: 255)]
    private ?string $password = null; // Propriété pour stocker le mot de passe haché
    
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    private ?string $name = null;

    #[ORM\Column(length: 255)]
    private ?string $surname_users = null;

    #[ORM\Column(nullable: true)]
    private ?int $point_users = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    private ?\DateTimeInterface $birth_users = null;

    #[ORM\Column(length: 255)]
    private ?string $role_users = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $phone_users = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $email_users = null;

    #[ORM\OneToMany(targetEntity: Friend::class, mappedBy: 'id_users')]
    private Collection $friends;

    #[ORM\OneToMany(targetEntity: Buy::class, mappedBy: 'id_users')]
    private Collection $buys;

    #[ORM\OneToMany(targetEntity: Event::class, mappedBy: 'id_users')]
    private Collection $events;

    #[ORM\OneToMany(targetEntity: Participate::class, mappedBy: 'id_users')]
    private Collection $participates;

    #[ORM\OneToMany(targetEntity: Challenged::class, mappedBy: 'id_users')]
    private Collection $challengeds;

    public function __construct()
    {
        $this->friends = new ArrayCollection();
        $this->buys = new ArrayCollection();
        $this->events = new ArrayCollection();
        $this->participates = new ArrayCollection();
        $this->challengeds = new ArrayCollection();

        $this->role_users = 'USER'; 
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getName(): ?string
    {
        return $this->name;
    }

    public function setName(string $name): static
    {
        $this->name = $name;

        return $this;
    }

    public function getSurnameUsers(): ?string
    {
        return $this->surname_users;
    }

    public function setSurnameUsers(string $surname_users): static
    {
        $this->surname_users = $surname_users;

        return $this;
    }

    public function getPointUsers(): ?int
    {
        return $this->point_users;
    }

    public function setPointUsers(?int $point_users): static
    {
        $this->point_users = $point_users;

        return $this;
    }

    public function getBirthUsers(): ?\DateTimeInterface
    {
        return $this->birth_users;
    }

    public function setBirthUsers(?\DateTimeInterface $birth_users): static
    {
        $this->birth_users = $birth_users;

        return $this;
    }

    public function getRoleUsers(): ?string
    {
        return $this->role_users;
    }

    public function setRoleUsers(string $role_users): static
    {
        $this->role_users = $role_users;

        return $this;
    }

    public function getPhoneUsers(): ?string
    {
        return $this->phone_users;
    }

    public function setPhoneUsers(?string $phone_users): static
    {
        $this->phone_users = $phone_users;

        return $this;
    }

    public function getEmailUsers(): ?string
    {
        return $this->email_users;
    }

    public function setEmailUsers(?string $email_users): static
    {
        $this->email_users = $email_users;

        return $this;
    }

    /**
     * @return Collection<int, Friend>
     */
    public function getFriends(): Collection
    {
        return $this->friends;
    }

    public function addFriend(Friend $friend): static
    {
        if (!$this->friends->contains($friend)) {
            $this->friends->add($friend);
            $friend->setIdUsers($this);
        }

        return $this;
    }

    public function removeFriend(Friend $friend): static
    {
        if ($this->friends->removeElement($friend)) {
            // set the owning side to null (unless already changed)
            if ($friend->getIdUsers() === $this) {
                $friend->setIdUsers(null);
            }
        }

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
            $buy->setIdUsers($this);
        }

        return $this;
    }

    public function removeBuy(Buy $buy): static
    {
        if ($this->buys->removeElement($buy)) {
            // set the owning side to null (unless already changed)
            if ($buy->getIdUsers() === $this) {
                $buy->setIdUsers(null);
            }
        }

        return $this;
    }

    /**
     * @return Collection<int, Event>
     */
    public function getEvents(): Collection
    {
        return $this->events;
    }

    public function addEvent(Event $event): static
    {
        if (!$this->events->contains($event)) {
            $this->events->add($event);
            $event->setIdUsers($this);
        }

        return $this;
    }

    public function removeEvent(Event $event): static
    {
        if ($this->events->removeElement($event)) {
            // set the owning side to null (unless already changed)
            if ($event->getIdUsers() === $this) {
                $event->setIdUsers(null);
            }
        }

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
            $participate->setIdUsers($this);
        }

        return $this;
    }

    public function removeParticipate(Participate $participate): static
    {
        if ($this->participates->removeElement($participate)) {
            // set the owning side to null (unless already changed)
            if ($participate->getIdUsers() === $this) {
                $participate->setIdUsers(null);
            }
        }

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
            $challenged->setIdUsers($this);
        }

        return $this;
    }

    public function removeChallenged(Challenged $challenged): static
    {
        if ($this->challengeds->removeElement($challenged)) {
            // set the owning side to null (unless already changed)
            if ($challenged->getIdUsers() === $this) {
                $challenged->setIdUsers(null);
            }
        }

        return $this;
    }

     /**
     * @Assert\NotBlank(message="Le mot de passe ne doit pas être vide.")
     * @Assert\Length(min=6, minMessage="Le mot de passe doit comporter au moins {{ limit }} caractères.")
     */
    public function getPlainPassword(): ?string
    {
        return $this->plainPassword;
    }

    public function setPlainPassword(?string $plainPassword): self
    {
        $this->plainPassword = $plainPassword;

        return $this;
    }

    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    public function eraseCredentials(): void
    {
        $this->plainPassword = null; // Efface le mot de passe en clair après l'authentification
    }

    // Méthodes requises par UserInterface
    public function getRoles(): array
    {
        return ['ROLE_USER']; // Par défaut, chaque utilisateur a le rôle "ROLE_USER"
    }

    public function getSalt(): ?string
    {
        // Si tu utilises bcrypt, tu n'as pas besoin de salt, il est intégré dans l'algorithme
        return null;
    }
    // Méthodes requises par UserInterface
    public function getUserIdentifier(): string
    {
        return $this->email_users; // Ou le champ que tu utilises comme identifiant
    }
}
