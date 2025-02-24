<?php

namespace App\Entity;

use App\Repository\AuditInscriptionRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AuditInscriptionRepository::class)]
class AuditInscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50)]
    private ?string $typeAction = null;

    #[ORM\Column]
    private ?\DateTimeImmutable $updated_at = null;

    #[ORM\Column(length: 50)]
    private ?string $matricule = null;

    #[ORM\Column(length: 255)]
    private ?string $nom = null;

    #[ORM\Column(length: 50)]
    private ?string $utilisateur = null;

    #[ORM\Column]
    private ?int $droitAncien = null;

    #[ORM\Column(type: 'integer', nullable: true)]
    private ?int $droitNouveau = null;

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

    public function getUtilisateur(): ?string
    {
        return $this->utilisateur;
    }

    public function setUtilisateur(string $utilisateur): static
    {
        $this->utilisateur = $utilisateur;

        return $this;
    }

    public function getDroitAncien(): ?int
    {
        return $this->droitAncien;
    }

    public function setDroitAncien(?int $droitAncien): static
    {
        $this->droitAncien = $droitAncien;

        return $this;
    }

    public function getDroitNouveau(): ?int
    {
        return $this->droitNouveau;
    }

    public function setDroitNouveau(int $droitNouveau): static
    {
        $this->droitNouveau = $droitNouveau;

        return $this;
    }
}
