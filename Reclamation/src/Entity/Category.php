<?php

namespace App\Entity;

use App\Repository\CategoryRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: CategoryRepository::class)]
class Category
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 30)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 30,
        minMessage: 'Marque must be at least {{ limit }} characters long',
        maxMessage: 'Marque cannot be longer than {{ limit }} characters',
    )]
    private ?string $marque = null;

    #[ORM\Column(length: 40)]
    #[Assert\NotBlank]
    #[Assert\Length(
        min: 3,
        max: 40,
        minMessage: 'Modele must be at least {{ limit }} characters long',
        maxMessage: 'Modele cannot be longer than {{ limit }} characters',
    )]
    private ?string $modele = null;

    #[ORM\OneToMany(mappedBy: 'categorie', targetEntity: Voiture::class)]
    private Collection $voitures;

    public function __construct()
    {
        $this->voitures = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMarque(): ?string
    {
        return $this->marque;
    }

    public function setMarque(string $marque): self
    {
        $this->marque = $marque;

        return $this;
    }

    public function getModele(): ?string
    {
        return $this->modele;
    }

    public function setModele(string $modele): self
    {
        $this->modele = $modele;

        return $this;
    }

    /**
     * @return Collection<int, Voiture>
     */
    public function getVoitures(): Collection
    {
        return $this->voitures;
    }

    public function addVoiture(Voiture $voiture): self
    {
        if (!$this->voitures->contains($voiture)) {
            $this->voitures->add($voiture);
            $voiture->setCategorie($this);
        }

        return $this;
    }

    public function removeVoiture(Voiture $voiture): self
    {
        if ($this->voitures->removeElement($voiture)) {
            // set the owning side to null (unless already changed)
            if ($voiture->getCategorie() === $this) {
                $voiture->setCategorie(null);
            }
        }

        return $this;
    }

    public function __toString() {
        return $this->marque . ' ' . $this->modele;
    }
}
