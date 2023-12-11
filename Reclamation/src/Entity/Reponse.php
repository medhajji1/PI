<?php

    namespace App\Entity;

    use App\Repository\ReponseRepository;
    use Doctrine\Common\Collections\ArrayCollection;
    use Doctrine\Common\Collections\Collection;
    use Doctrine\DBAL\Types\Types;
    use Doctrine\ORM\Mapping as ORM;

    #[ORM\Entity(repositoryClass: ReponseRepository::class)]
    class Reponse
    {
        #[ORM\Id]
        #[ORM\GeneratedValue]
        #[ORM\Column]
        private ?int $id = null;

        #[ORM\Column(type: Types::TEXT)]
        private ?string $message = null;

        #[ORM\ManyToOne(inversedBy: 'reponses')]
        #[ORM\JoinColumn(nullable: false)]
        private ?reclamation $id_reclamation = null;

        public function getId(): ?int
        {
            return $this->id;
        }

        public function getMessage(): ?string
        {
            return $this->message;
        }

        public function setMessage(string $message): self
        {
            $this->message = $message;

            return $this;
        }

        public function getIdReclamation(): ?reclamation
        {
            return $this->id_reclamation;
        }

        public function setIdReclamation(?reclamation $id_reclamation): self
        {
            $this->id_reclamation = $id_reclamation;

            return $this;
        }
    }
