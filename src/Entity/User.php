<?php

namespace App\Entity;

use App\Repository\UserRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: UserRepository::class)]
#[UniqueEntity(fields: 'email', message: 'There is already an account with this email')]
class User implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180, unique: true)]
    #[
        Assert\NotBlank(message: 'Veuillez entrer une valeur.'),
        Assert\Email(message: "L'email {{ value }} n'est pas valide.")
    ]
    private ?string $email = null;

    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    #[
        Assert\NotBlank(message: 'Veuillez entrer une valeur.'),
        Assert\Regex(pattern: '/^(?=.*[a-zà-ÿ])(?=.*[A-ZÀ-Ý])(?=.*[0-9])(?=.*[^a-zà-ÿA-ZÀ-Ý0-9]).{8,}$/', message: 'Le mot de passe doit être composé de 8 caractères dont au minimum : 1 majuscule, 1 minuscule, 1 chiffre et un caractère spécial (dans un ordre aléatoire).')
    ]
    private ?string $password = null;

    #[ORM\Column(length: 50)]
    private ?string $firstName = null;

    #[ORM\Column(length: 50)]
    private ?string $lastName = null;

    #[ORM\Column]
    private ?bool $isEnable = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $registeredAt = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $mustBeVerifiedBefore = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $registrationToken = null;

    #[ORM\Column]
    private ?bool $isVerified = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $verifiedAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $frgtPswdToken = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $frgtPswdTokenRequestedAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $frgtPswdTokenMustBeVerifiedBefore = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $frgtPswdTokenVerifiedAt = null;

    public function __construct()
    {
        $this->isEnable = false;
        $this->isVerified = false;
        $this->firstName = ' ';
        $this->lastName = ' ';
        $this->mustBeVerifiedBefore = (new \DateTimeImmutable('now'))->add(new \DateInterval('P1D'));
        $this->registeredAt = new \DateTimeImmutable('now');
        $this->roles = ['ROLE_USER'];
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email;
    }

    /**
     * @see UserInterface
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // guarantee every user at least has ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    public function setRoles(array $roles): self
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): string
    {
        return $this->password;
    }

    public function setPassword(string $password): self
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // If you store any temporary, sensitive data on the user, clear it here
        // $this->plainPassword = null;
    }

    public function getFirstName(): ?string
    {
        return $this->firstName;
    }

    public function setFirstName(string $firstName): self
    {
        $this->firstName = $firstName;

        return $this;
    }

    public function getLastName(): ?string
    {
        return $this->lastName;
    }

    public function setLastName(string $lastName): self
    {
        $this->lastName = $lastName;

        return $this;
    }

    public function isIsEnable(): ?bool
    {
        return $this->isEnable;
    }

    public function setIsEnable(bool $isEnable): self
    {
        $this->isEnable = $isEnable;

        return $this;
    }

    public function getRegisteredAt(): ?\DateTimeImmutable
    {
        return $this->registeredAt;
    }

    public function setRegisteredAt(\DateTimeImmutable $registeredAt): self
    {
        $this->registeredAt = $registeredAt;

        return $this;
    }

    public function getMustBeVerifiedBefore(): ?\DateTimeImmutable
    {
        return $this->mustBeVerifiedBefore;
    }

    public function setMustBeVerifiedBefore(\DateTimeImmutable $mustBeVerifiedBefore): self
    {
        $this->mustBeVerifiedBefore = $mustBeVerifiedBefore;

        return $this;
    }

    public function getRegistrationToken(): ?string
    {
        return $this->registrationToken;
    }

    public function setRegistrationToken(?string $registrationToken): self
    {
        $this->registrationToken = $registrationToken;

        return $this;
    }

    public function isIsVerified(): ?bool
    {
        return $this->isVerified;
    }

    public function setIsVerified(bool $isVerified): self
    {
        $this->isVerified = $isVerified;

        return $this;
    }

    public function getVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->verifiedAt;
    }

    public function setVerifiedAt(?\DateTimeImmutable $verifiedAt): self
    {
        $this->verifiedAt = $verifiedAt;

        return $this;
    }

    public function getFrgtPswdToken(): ?string
    {
        return $this->frgtPswdToken;
    }

    public function setFrgtPswdToken(?string $frgtPswdToken): self
    {
        $this->frgtPswdToken = $frgtPswdToken;

        return $this;
    }

    public function getFrgtPswdTokenRequestedAt(): ?\DateTimeImmutable
    {
        return $this->frgtPswdTokenRequestedAt;
    }

    public function setFrgtPswdTokenRequestedAt(?\DateTimeImmutable $frgtPswdTokenRequestedAt): self
    {
        $this->frgtPswdTokenRequestedAt = $frgtPswdTokenRequestedAt;

        return $this;
    }

    public function getFrgtPswdTokenMustBeVerifiedBefore(): ?\DateTimeImmutable
    {
        return $this->frgtPswdTokenMustBeVerifiedBefore;
    }

    public function setFrgtPswdTokenMustBeVerifiedBefore(?\DateTimeImmutable $frgtPswdTokenMustBeVerifiedBefore): self
    {
        $this->frgtPswdTokenMustBeVerifiedBefore = $frgtPswdTokenMustBeVerifiedBefore;

        return $this;
    }

    public function getFrgtPswdTokenVerifiedAt(): ?\DateTimeImmutable
    {
        return $this->frgtPswdTokenVerifiedAt;
    }

    public function setFrgtPswdTokenVerifiedAt(?\DateTimeImmutable $frgtPswdTokenVerifiedAt): self
    {
        $this->frgtPswdTokenVerifiedAt = $frgtPswdTokenVerifiedAt;

        return $this;
    }
}
