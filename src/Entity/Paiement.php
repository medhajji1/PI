<?php

namespace App\Entity;

use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\PaiementRepository;
use Symfony\Component\Validator\Constraints as Assert;



#[ORM\Entity(repositoryClass: PaiementRepository::class)]
class Paiement
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private $idpaiement;

    #[ORM\Column(type: "integer", nullable: false)]
    #[Assert\Positive(message: "The amount must be a positive number.")]
    private int $montant;

    #[ORM\Column(type: "date", nullable: false)]
    private \DateTime $date;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "The motive cannot be blank.")]
    private string $motif;

    #[ORM\Column(type: "string", length: 255, nullable: false)]
    #[Assert\NotBlank(message: "The email cannot be blank.")]
    #[Assert\Email(message: "The email '{{ value }}' is not a valid email.")]
    private string $email;


    #[ORM\ManyToOne(targetEntity: "Contrat")]
    #[ORM\JoinColumn(name: "idContrat", referencedColumnName: "idContrat")]
    private ?Contrat $idcontrat;
    
   

    public function getIdpaiement(): ?int
    {
        return $this->idpaiement;
    }

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): self
    {
        $this->montant = $montant;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): self
    {
        $this->date = $date;

        return $this;
    }

    public function getMotif(): ?string
    {
        return $this->motif;
    }

    public function setMotif(string $motif): self
    {
        $this->motif = $motif;

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

    public function getIdcontrat(): ?Contrat
    {
        return $this->idcontrat;
    }

    public function setIdcontrat(?Contrat $idcontrat): self
    {
        $this->idcontrat = $idcontrat;

        return $this;
    }


}
