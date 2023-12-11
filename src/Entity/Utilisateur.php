<?php

namespace App\Entity;

use Doctrine\ORM\Mapping as ORM;
use App\Repository\UtilisateurRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Security\Core\User\UserInterface;
use Symfony\Component\Security\Core\User\PasswordAuthenticatedUserInterface;




#[ORM\Entity(repositoryClass: UtilisateurRepository::class)]
#[UniqueEntity(fields: 'email',message: "Ce mail à déjà été utilisé")]
class Utilisateur implements UserInterface,PasswordAuthenticatedUserInterface
{

    #[ORM\Id]
    #[ORM\Column]
    #[Assert\NotNull(message:"Ce champ ne peut être vide")]
    private ?int $cin;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Ce champ ne peut être vide")]
    #[Assert\Regex(pattern: "/^[A-Z][a-z]+$/", message: "Ne doit pas contenir de caractère spéciaux", htmlPattern: "[A-Z]+")]
    private ?string $nom = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank(message:"Ce champ ne peut être vide")]
    #[Assert\Regex(pattern: "/^[A-Z][a-z]+$/", message: "Ne doit pas contenir de caractère spéciaux", htmlPattern: "[A-Z]+")]
    private ?string $prenom = null;

    #[ORM\Column(length: 180, unique: true)]
    #[Assert\NotBlank(message:"Ce champ ne peut être vide")]
    #[Assert\Email(message:"le format saisie ne correspond pas à celui d'un email")]
    private ?string $email = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Ce champ ne peut être vide")]
    private ?string $motdepasse = null;

    #[ORM\Column]
    #[Assert\NotBlank(message:"Ce champ ne peut être vide")]
    private ?string $numerotelephone = null;

    #[ORM\Column(length: 50)]
    #[Assert\NotBlank(message:"Ce champ ne peut être vide")]
    private ?string $typeutilisateur;


    #[ORM\Column(length: 200)]
    private ?string  $image = null;

    public function getCin(): ?int
    {
        return $this->cin;
    }

    public function setCin(int $cin): ?self
    {
         $this->cin = $cin;
        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
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

    public function getMotdepasse(): ?string
    {
        return $this->motdepasse; 
    }

    public function setMotdepasse(string $MotDePasse): self
    {
        $this->motdepasse = $MotDePasse;

        return $this;
    }

    public function getNumerotelephone(): ?string
    {
        return $this->numerotelephone;
    }

    public function setNumerotelephone(string $numerotelephone): self
    {
        $this->numerotelephone = $numerotelephone;

        return $this;
    }

   public function getTypeutilisateur(): ?string
    {
        return $this->typeutilisateur;
    }

    public function setTypeutilisateur(string $typeutilisateur): self
    {
        $this->typeutilisateur = $typeutilisateur;

        return $this;
    }

    public function getImage(): ?string
    {
        return $this->image;
    }

    public function setImage(string $image): self
    {
        $this->image = $image;

        return $this;
    }

    /**
     * @see PasswordAuthenticatedUserInterface
     */
    public function getPassword(): ?string
    {
        // TODO: Implement getPassword() method.
        return $this->motdepasse;
    }

    /*public function setPassword(string $motdepasse): self
    {
        $this->motdepasse = $motdepasse;

        return $this;
    }*/

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
     * Returning a salt is only needed, if you are not using a modern
     * hashing algorithm (e.g. bcrypt or sodium) in your security.yaml.
     *
     * @see UserInterface
     */

    public function getSalt()
    {
        // TODO: Implement getSalt() method.
        return null;
    }


    /**
     * @see UserInterface
     */
    public function eraseCredentials()
    {
        // TODO: Implement eraseCredentials() method.
    }

    /**
     * @deprecated since Symfony 5.3, use getUserIdentifier instead
     */
    public function getUsername()
    {
        // TODO: Implement getUsername() method.
        return (string) $this->email;
    }

    public function __call(string $name, array $arguments)
    {
        // TODO: Implement @method string getUserIdentifier()
    }

    public function getRoles()
    {
        $roles = $this->typeutilisateur;
        // guarantee every user at least has ROLE_USER
        //$roles[] = 'ROLE_USER';

        return array($roles);
       // return (string) $this->typeutilisateur;
    }

   /* public function setRoles(string $typeutilisateur): self
    {
        $this->typeutilisateur = $typeutilisateur;

        return $this;
    }*/
    public function __construct()
    {
        $this->cin = 0;
    }
}
