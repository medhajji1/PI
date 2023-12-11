<?php

namespace App\Entity;

use App\Repository\PaiementRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use App\Repository\ContratRepository;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: ContratRepository::class)]
class Contrat
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column(name: 'idContrat', type: 'integer', options: ['unsigned' => true, 'autoincrement' => true])]
    private int $idcontrat;

    #[ORM\Column(name: 'date', type: 'date', nullable: false)]
    #[Assert\NotBlank(message: 'Please enter a date.')]
    private \DateTimeInterface $date;

    #[ORM\Column(name: 'idReservation', type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: 'Please enter a reservation ID.')]
    #[Assert\Positive(message: 'The reservation ID should be a positive integer.')]
    private int $idreservation;

    #[ORM\Column(name: 'idProprietaire', type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: 'Please enter a proprietaire ID.')]
    #[Assert\Positive(message: 'The proprietaire ID should be a positive integer.')]
    private int $idproprietaire;

    #[ORM\Column(name: 'idLocataire', type: 'integer', nullable: false)]
    #[Assert\NotBlank(message: 'Please enter a locataire ID.')]
    #[Assert\Positive(message: 'The locataire ID should be a positive integer.')]
    private int $idlocataire;

    #[ORM\Column(name: 'motif', type: 'string', length: 255, nullable: false)]
    #[Assert\NotBlank(message: 'Please enter a motif.')]
    #[Assert\Length(max: 255, maxMessage: 'The motif cannot be longer than {{ limit }} characters.')]
    private string $motif;

    public function getIdcontrat(): ?int
    {
        return $this->idcontrat;
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

    public function getIdreservation(): ?int
    {
        return $this->idreservation;
    }

    public function setIdreservation(int $idreservation): self
    {
        $this->idreservation = $idreservation;

        return $this;
    }

    public function getIdproprietaire(): ?int
    {
        return $this->idproprietaire;
    }

    public function setIdproprietaire(int $idproprietaire): self
    {
        $this->idproprietaire = $idproprietaire;

        return $this;
    }

    public function getIdlocataire(): ?int
    {
        return $this->idlocataire;
    }

    public function setIdlocataire(int $idlocataire): self
    {
        $this->idlocataire = $idlocataire;

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
}
