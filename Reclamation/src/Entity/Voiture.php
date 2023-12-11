<?php

namespace App\Entity;

use App\Repository\VoitureRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: VoitureRepository::class)]
class Voiture
{
    #[ORM\Id]
    #[ORM\Column(length: 20)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 20,
        minMessage: 'immatriculation must be at least {{ limit }} characters long',
        maxMessage: 'immatriculation cannot be longer than {{ limit }} characters',
    )]
    private ?string $immatriculation = null;

    #[ORM\Column]
    #[Assert\NotBlank]
    #[Assert\PositiveOrZero]
    private ?int $kilometrage = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'couleur must be at least {{ limit }} characters long',
        maxMessage: 'couleur cannot be longer than {{ limit }} characters',
    )]
    private ?string $couleur = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 255,
        minMessage: 'image url must be at least {{ limit }} characters long',
        maxMessage: 'image url cannot be longer than {{ limit }} characters',
    )]
    private ?string $image = null;

    #[ORM\ManyToOne(inversedBy: 'voitures')]
    #[ORM\JoinColumn(nullable: false, name: 'categorie', referencedColumnName: 'id')]
    #[Assert\NotBlank]
    private ?Category $categorie = null;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: Reservation::class, orphanRemoval: true)]
    private Collection $reservations;

    #[ORM\OneToMany(mappedBy: 'voiture', targetEntity: VoitureRate::class, orphanRemoval: true)]
    private Collection $voitureRates;

    public function __construct()
    {
        $this->reservations = new ArrayCollection();
        $this->voitureRates = new ArrayCollection();
    }

    public function getImmatriculation(): ?string
    {
        return $this->immatriculation;
    }

    public function setImmatriculation(string $immatriculation): self
    {
        $this->immatriculation = $immatriculation;

        return $this;
    }

    public function getKilometrage(): ?int
    {
        return $this->kilometrage;
    }

    public function setKilometrage(int $kilometrage): self
    {
        $this->kilometrage = $kilometrage;

        return $this;
    }

    public function getCouleur(): ?string
    {
        return $this->couleur;
    }

    public function setCouleur(string $couleur): self
    {
        $this->couleur = $couleur;

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

    public function getCategorie(): ?Category
    {
        return $this->categorie;
    }

    public function setCategorie(?Category $categorie): self
    {
        $this->categorie = $categorie;

        return $this;
    }

    /**
     * @return Collection<int, Reservation>
     */
    public function getReservations(): Collection
    {
        return $this->reservations;
    }

    public function addReservation(Reservation $reservation): self
    {
        if (!$this->reservations->contains($reservation)) {
            $this->reservations->add($reservation);
            $reservation->setVoiture($this);
        }

        return $this;
    }

    public function removeReservation(Reservation $reservation): self
    {
        if ($this->reservations->removeElement($reservation)) {
            // set the owning side to null (unless already changed)
            if ($reservation->getVoiture() === $this) {
                $reservation->setVoiture(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->couleur . ' ' . $this->categorie . ' (' . $this->immatriculation . ')';
    }
}
