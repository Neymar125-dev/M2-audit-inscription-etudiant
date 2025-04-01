<?php

namespace App\Entity;

use App\Repository\FactureRepository;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: FactureRepository::class)]
#[UniqueEntity(["numero"], message: "Ce numero est déjà utilisé")]
class Facture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(
        message: "Le nom est obligatoire."
    )]
    private ?string $nom = null;

    #[ORM\Column]
    #[Assert\NotBlank(
        message: "Le Montant est obligatoire."
    )]
    private ?int $montant = null;

    #[ORM\Column(unique: true)]
    #[Assert\NotBlank(
        message: "Le numéro de la facture est obligatoire."
    )]
    private ?string $numero = null;

    public function getId(): ?int
    {
        return $this->id;
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

    public function getMontant(): ?int
    {
        return $this->montant;
    }

    public function setMontant(int $montant): static
    {
        $this->montant = $montant;

        return $this;
    }

    public function getNumero(): ?string
    {
        return $this->numero;
    }

    public function setNumero(string $numero): static
    {
        $this->numero = $numero;

        return $this;
    }
}
