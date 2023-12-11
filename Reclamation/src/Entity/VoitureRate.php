<?php

namespace App\Entity;

use App\Repository\VoitureRateRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: VoitureRateRepository::class)]
class VoitureRate
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\Range(
        min: 0,
        max: 5,
        notInRangeMessage: 'Rating must be between {{ min }} and {{ max }}',
    )]
    private ?int $rating = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'note must be at least {{ limit }} characters long',
        maxMessage: 'note cannot be longer than {{ limit }} characters',
    )]
    private ?string $note = null;

    #[ORM\ManyToOne(inversedBy: 'voitureRates')]
    #[ORM\JoinColumn(nullable: false, name: 'voiture', referencedColumnName: 'immatriculation')]
    private ?Voiture $voiture = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRating(): ?string
    {
        return $this->rating;
    }

    public function getNote(): ?string
    {
        return $this->id;
    }

    public function setRating(string $rating): self
    {
        $this->rating = $rating;

        return $this;
    }

    public function getVoiture(): ?Voiture
    {
        return $this->voiture;
    }

    public function setVoiture(?Voiture $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function setNote(string $note): self
    {
        $this->note = $note;

        return $this;
    }
}
