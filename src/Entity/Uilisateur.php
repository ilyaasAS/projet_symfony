<?php

namespace App\Entity;

use App\Repository\UilisateurRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;
use Symfony\Component\Security\Core\User\UserInterface;

#[ORM\Entity(repositoryClass: UilisateurRepository::class)]
#[ORM\UniqueConstraint(name: 'UNIQ_IDENTIFIER_EMAIL', fields: ['email'])]
class Uilisateur implements UserInterface, PasswordAuthenticatedUserInterface
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 180)]
    private ?string $email = null;

    /**
     * @var list<string> The user roles
     */
    #[ORM\Column]
    private array $roles = [];

    /**
     * @var string The hashed password
     */
    #[ORM\Column]
    private ?string $password = null;

    #[ORM\Column]
    private ?string $pseudo = null;

    #[ORM\Column(nullable: true)]
    private ?string $avatar = null;

    #[ORM\Column(nullable: true)]
    private ?string $emploi = null;

    #[ORM\Column(nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(nullable: true)]
    private ?string $siteURL = null;

    #[ORM\Column(length: 500)]
    private ?string $description = null;

    // Ajout de la relation OneToMany avec ItemCollection
    #[ORM\OneToMany(mappedBy: 'utilisateur', targetEntity: ItemCollection::class)]
    
    private $collections;

    public function getId(): ?int
    {
        return $this->id;
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

    /**
     * A visual identifier that represents this user.
     *
     * @see UserInterface
     */
    public function getUserIdentifier(): string
    {
        return (string) $this->email; // Vous pouvez utiliser $this->pseudo si nécessaire
    }

    /**
     * @see UserInterface
     *
     * @return list<string>
     */
    public function getRoles(): array
    {
        $roles = $this->roles;
        // garantir qu'un utilisateur a au moins le rôle ROLE_USER
        $roles[] = 'ROLE_USER';

        return array_unique($roles);
    }

    /**
     * @param list<string> $roles
     */
    public function setRoles(array $roles): static
    {
        $this->roles = $roles;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        return $this->password;
    }

    public function setPassword(string $password): static
    {
        $this->password = $password;

        return $this;
    }

    /**
     * @see UserInterface
     */
    public function eraseCredentials(): void
    {
        // Si vous stockez des données sensibles temporaires sur l'utilisateur, effacez-les ici
        // $this->plainPassword = null;
    }

    /**
     * Get the value of pseudo
     */
    public function getPseudo(): ?string
    {
        return $this->pseudo;
    }

    /**
     * Set the value of pseudo
     *
     * @return  self
     */
    public function setPseudo(?string $pseudo): static
    {
        $this->pseudo = $pseudo;

        return $this;
    }

    /**
     * Get the value of avatar
     */
    public function getAvatar(): ?string
    {
        return $this->avatar;
    }

    /**
     * Set the value of avatar
     *
     * @return  self
     */
    public function setAvatar(?string $avatar): static
    {
        $this->avatar = $avatar;

        return $this;
    }

    /**
     * Get the value of emploi
     */
    public function getEmploi(): ?string
    {
        return $this->emploi;
    }

    /**
     * Set the value of emploi
     *
     * @return  self
     */
    public function setEmploi(?string $emploi): static
    {
        $this->emploi = $emploi;

        return $this;
    }

    /**
     * Get the value of telephone
     */
    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    /**
     * Set the value of telephone
     *
     * @return  self
     */
    public function setTelephone(?string $telephone): static
    {
        $this->telephone = $telephone;

        return $this;
    }

    /**
     * Get the value of siteURL
     */
    public function getSiteURL(): ?string
    {
        return $this->siteURL;
    }

    /**
     * Set the value of siteURL
     *
     * @return  self
     */
    public function setSiteURL(?string $siteURL): static
    {
        $this->siteURL = $siteURL;

        return $this;
    }

    /**
     * Get the value of description
     */
    public function getDescription(): ?string
    {
        return $this->description;
    }

    /**
     * Set the value of description
     *
     * @return  self
     */
    public function setDescription(?string $description): static
    {
        $this->description = $description;

        return $this;
    }

    // Getter pour les collections
    public function getCollections(): iterable
    {
        return $this->collections;
    }
}
