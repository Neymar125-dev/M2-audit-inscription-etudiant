<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
#[UniqueEntity(["matricule"], message: "Ce numero est déjà utilisé")]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(unique: true)]
    #[Assert\NotBlank(
        message: "Le matricule est obligatoire."
    )]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "Le matricule est obligatoire."
    )]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(
        message: "Le matricule est obligatoire."
    )]
    private ?int $droit = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getMatricule(): ?string
    {
        return $this->matricule;
    }

    public function setMatricule(string $matricule): static
    {
        $this->matricule = $matricule;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(string $nom): static
    {
        $this->nom = $nom;

        return $this;
    }

    public function getDroit(): ?int
    {
        return $this->droit;
    }

    public function setDroit(int $droit): static
    {
        $this->droit = $droit;

        return $this;
    }
}
