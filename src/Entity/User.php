<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Serializer\Annotation\Groups;
use Symfony\Component\Validator\Constraints as Assert;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;


#[ORM\Entity(repositoryClass: UserRepository::class)]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    #[Groups("user")]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "First name cannot be null.")]
    #[Assert\NotBlank(message: "First name cannot be blank.")]
    #[Groups("user")]
    private ?string $first_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Last name cannot be null.")]
    #[Assert\NotBlank(message: "Last name cannot be blank.")]
    #[Groups("user")]
    private ?string $last_name = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Email cannot be null.")]
    #[Assert\NotBlank(message: "Email cannot be blank.")]
    #[Groups("user")]
    private ?string $email = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Password cannot be null.")]
    #[Assert\NotBlank(message: "Password cannot be blank.")]    
    private ?string $password = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Address cannot be null.")]
    #[Assert\NotBlank(message: "Address cannot be blank.")]
    #[Groups("user")]
    private ?string $address = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "City cannot be null.")]
    #[Assert\NotBlank(message: "City cannot be blank.")]
    #[Groups("user")]
    private ?string $city = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotNull(message: "Province cannot be null.")]
    #[Assert\NotBlank(message: "Province cannot be blank.")]
    #[Groups("user")]
    private ?string $province = null;

    #[ORM\Column(length: 10)]
    #[Assert\NotNull(message: "Postal code cannot be null.")]
    #[Assert\NotBlank(message: "Postal code cannot be blank.")]
    #[Groups("user")]
    private ?string $postal_code = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    #[Assert\NotNull(message: "Registration Date cannot be null.")]
    #[Assert\NotBlank(message: "Registration Date cannot be blank.")]
    #[Groups("user")]
    private ?\DateTimeInterface $registration_date = null;

    #[ORM\Column(type: Types::DATE_MUTABLE, nullable: true)]
    #[Groups("user")]
    private ?\DateTimeInterface $birth_date = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Reputation cannot be null.")]
    #[Assert\NotBlank(message: "Reputation cannot be blank.")]
    #[Groups("user")]
    private ?int $reputation = null;

    #[ORM\Column]
    #[Assert\NotNull(message: "Blocked cannot be null.")]
    #[Groups("user")]
    private ?bool $blocked = null;

    #[ORM\ManyToOne(inversedBy: 'users')]
    #[ORM\JoinColumn(nullable: false)]
    #[Assert\NotNull(message: "Library cannot be null, make sure that the provided library exists in the database.")]
    #[Assert\NotBlank(message: "Library cannot be blank, make sure that the provided library exists in the database.")]
    private ?Library $library = null;

    #[ORM\OneToMany(mappedBy: 'user', targetEntity: Loan::class)]
    private Collection $loans;

    public function __construct()
    {
        $this->loans = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstName(): ?string
    {
        return $this->first_name;
    }

    public function setFirstName(string $first_name): static
    {
        $this->first_name = $first_name;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->last_name;
    }

    public function setLastName(string $last_name): static
    {
        $this->last_name = $last_name;

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

    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;
        return $this;
    }

    public function getAddress(): ?string
    {
        return $this->address;
    }

    public function setAddress(?string $address): static
    {
        $this->address = $address;

        return $this;
    }

    public function getCity(): ?string
    {
        return $this->city;
    }

    public function setCity(?string $city): static
    {
        $this->city = $city;

        return $this;
    }

    public function getProvince(): ?string
    {
        return $this->province;
    }

    public function setProvince(?string $province): static
    {
        $this->province = $province;

        return $this;
    }

    public function getPostalCode(): ?string
    {
        return $this->postal_code;
    }

    public function setPostalCode(?string $postal_code): static
    {
        $this->postal_code = $postal_code;

        return $this;
    }

    public function getRegistrationDate(): ?\DateTimeInterface
    {
        return $this->registration_date;
    }

    public function setRegistrationDate(\DateTimeInterface $registration_date): static
    {
        $this->registration_date = $registration_date;

        return $this;
    }

    public function getBirthDate(): ?\DateTimeInterface
    {
        return $this->birth_date;
    }

    public function setBirthDate(?\DateTimeInterface $birth_date): static
    {
        $this->birth_date = $birth_date;

        return $this;
    }

    public function getReputation(): ?int
    {
        return $this->reputation;
    }

    public function setReputation(?int $reputation): static
    {
        $this->reputation = $reputation;

        return $this;
    }

    public function isBlocked(): ?bool
    {
        return $this->blocked;
    }

    public function setBlocked(bool $blocked): static
    {
        $this->blocked = $blocked;

        return $this;
    }

    public function getLibrary(): ?Library
    {
        return $this->library;
    }

    public function setLibrary(?Library $library): static
    {
        $this->library = $library;

        return $this;
    }

    /**
     * @return Collection<int, Loan>
     */
    public function getLoans(): Collection
    {
        return $this->loans;
    }

    public function addLoan(Loan $loan): static
    {
        if (!$this->loans->contains($loan)) {
            $this->loans->add($loan);
            $loan->setUser($this);
        }

        return $this;
    }

    public function removeLoan(Loan $loan): static
    {
        if ($this->loans->removeElement($loan)) {
            if ($loan->getUser() === $this) {
                $loan->setUser(null);
            }
        }

        return $this;
    }

    #[Groups("user")]
    public function getLibraryId(): ?int
    {
        return $this->library->getId();
    }

    #[Groups("user")]
    public function getLoanIds(): array
    {
        return $this->loans->map(function ($loan) {
            return $loan->getId();
        })->toArray();
    }

    /**
     * @return string[]
     */
    public function getRoles(): array
    {
        // You might want to adjust the roles as per your application's needs
        return ['ROLE_USER'];
    }

    public function getSalt(): ?string
    {
        return null;
    }

    public function getUserIdentifier(): string
    {
        return $this->getEmail();
    }

    public function eraseCredentials(): void
    {
        // If you store any temporary, sensitive data on the user, clear it here.
        // $this->plainPassword = null;
    }
}
