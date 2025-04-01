<?php

namespace App\Entity;

use App\Repository\AuditFactureRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuditFactureRepository::class)]
class AuditFacture
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $typeAction = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $utilisateur = null;

    #[ORM\Column]
    private ?int $montantAncien = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $montantNouveau = null;

    #[ORM\Column(length: 50)]
    private ?string $numero = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getTypeAction(): ?string
    {
        return $this->typeAction;
    }

    public function setTypeAction(string $typeAction): static
    {
        $this->typeAction = $typeAction;

        return $this;
    }

    public function getUpdatedAt(): ?\DateTimeImmutable
    {
        return $this->updated_at;
    }

    public function setUpdatedAt(\DateTimeImmutable $updated_at): static
    {
        $this->updated_at = $updated_at;

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

    public function getUtilisateur(): ?string
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(string $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getMontantAncien(): ?int
    {
        return $this->montantAncien;
    }

    public function setMontantAncien(?int $montantAncien): static
    {
        $this->montantAncien = $montantAncien;

        return $this;
    }

    public function getMontantNouveau(): ?int
    {
        return $this->montantNouveau;
    }

    public function setMontantNouveau(int $montantNouveau): static
    {
        $this->montantNouveau = $montantNouveau;

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
