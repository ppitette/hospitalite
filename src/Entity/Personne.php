<?php

namespace App\Entity;

use App\Repository\PersonneRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: PersonneRepository::class)]
#[UniqueEntity(fields: 'courriel', message: 'There is already an account with this email')]
class Personne
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $liste = null;

    #[ORM\Column(length: 6, nullable: true)]
    private ?string $eglise = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $civilite = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nom = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nomNaiss = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $prenom = null;

    #[ORM\Column]
    private ?int $genre = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $telephone = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $mobile = null;

    #[ORM\Column]
    private ?bool $lrCourriel = null;

    #[ORM\Column(length: 255, nullable: true)]
    #[Assert\Email(message: "Le courriel '{{ value }}' n\'est pas valide.")]
    private ?string $courriel = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $courrielRemarque = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateNaiss = null;

    #[ORM\Column]
    private ?bool $decede = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $dateDeces = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 2050)]
    private ?int $engHosp = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 2050)]
    private ?int $engEgl = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 2050)]
    private ?int $pPele = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 100)]
    private ?int $nbPele = null;

    #[ORM\Column]
    #[Assert\Range(min: 0, max: 2050)]
    private ?int $dPele = null;

    #[ORM\Column]
    private ?int $medical = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $medicalAutre = null;

    #[ORM\Column]
    private ?bool $isReferent = null;

    #[ORM\Column(nullable: true)]
    private ?int $adrIdent = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $deletedAt = null;

    /**
     * Calcul de l'age.
     */
    public function getAge(): int
    {
        $age = 0;

        if ($this->dateNaiss) {
            $age = $this->dateNaiss->diff(new \DateTime())->y;
        }

        return $age;
    }

    /**
     * Calcul de l'age à une date précisée.
     */
    public function getAgeDate(\DateTime $date): int
    {
        $age = 0;

        if ($this->dateNaiss) {
            $age = $this->dateNaiss->diff($date)->y;
        }

        return $age;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getListe(): ?string
    {
        return $this->liste;
    }

    public function setListe(?string $liste): self
    {
        $this->liste = $liste;

        return $this;
    }

    public function getEglise(): ?string
    {
        return $this->eglise;
    }

    public function setEglise(?string $eglise): self
    {
        $this->eglise = $eglise;

        return $this;
    }

    public function getCivilite(): ?string
    {
        return $this->civilite;
    }

    public function setCivilite(?string $civilite): self
    {
        $this->civilite = $civilite;

        return $this;
    }

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getNomNaiss(): ?string
    {
        return $this->nomNaiss;
    }

    public function setNomNaiss(?string $nomNaiss): self
    {
        $this->nomNaiss = $nomNaiss;

        return $this;
    }

    public function getPrenom(): ?string
    {
        return $this->prenom;
    }

    public function setPrenom(?string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getGenre(): ?int
    {
        return $this->genre;
    }

    public function setGenre(int $genre): self
    {
        $this->genre = $genre;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    public function getMobile(): ?string
    {
        return $this->mobile;
    }

    public function setMobile(?string $mobile): self
    {
        $this->mobile = $mobile;

        return $this;
    }

    public function isLrCourriel(): ?bool
    {
        return $this->lrCourriel;
    }

    public function setLrCourriel(bool $lrCourriel): self
    {
        $this->lrCourriel = $lrCourriel;

        return $this;
    }

    public function getCourriel(): ?string
    {
        return $this->courriel;
    }

    public function setCourriel(?string $courriel): self
    {
        $this->courriel = $courriel;

        return $this;
    }

    public function getCourrielRemarque(): ?string
    {
        return $this->courrielRemarque;
    }

    public function setCourrielRemarque(?string $courrielRemarque): self
    {
        $this->courrielRemarque = $courrielRemarque;

        return $this;
    }

    public function getDateNaiss(): ?\DateTimeImmutable
    {
        return $this->dateNaiss;
    }

    public function setDateNaiss(?\DateTimeImmutable $dateNaiss): self
    {
        $this->dateNaiss = $dateNaiss;

        return $this;
    }

    public function isDecede(): ?bool
    {
        return $this->decede;
    }

    public function setDecede(bool $decede): self
    {
        $this->decede = $decede;

        return $this;
    }

    public function getDateDeces(): ?\DateTimeImmutable
    {
        return $this->dateDeces;
    }

    public function setDateDeces(?\DateTimeImmutable $dateDeces): self
    {
        $this->dateDeces = $dateDeces;

        return $this;
    }

    public function getEngHosp(): ?int
    {
        return $this->engHosp;
    }

    public function setEngHosp(int $engHosp): self
    {
        $this->engHosp = $engHosp;

        return $this;
    }

    public function getEngEgl(): ?int
    {
        return $this->engEgl;
    }

    public function setEngEgl(int $engEgl): self
    {
        $this->engEgl = $engEgl;

        return $this;
    }

    public function getPPele(): ?int
    {
        return $this->pPele;
    }

    public function setPPele(int $pPele): self
    {
        $this->pPele = $pPele;

        return $this;
    }

    public function getNbPele(): ?int
    {
        return $this->nbPele;
    }

    public function setNbPele(int $nbPele): self
    {
        $this->nbPele = $nbPele;

        return $this;
    }

    public function getDPele(): ?int
    {
        return $this->dPele;
    }

    public function setDPele(int $dPele): self
    {
        $this->dPele = $dPele;

        return $this;
    }

    public function getMedical(): ?int
    {
        return $this->medical;
    }

    public function setMedical(int $medical): self
    {
        $this->medical = $medical;

        return $this;
    }

    public function getMedicalAutre(): ?string
    {
        return $this->medicalAutre;
    }

    public function setMedicalAutre(?string $medicalAutre): self
    {
        $this->medicalAutre = $medicalAutre;

        return $this;
    }

    public function isIsReferent(): ?bool
    {
        return $this->isReferent;
    }

    public function setIsReferent(bool $isReferent): self
    {
        $this->isReferent = $isReferent;

        return $this;
    }

    public function getAdrIdent(): ?int
    {
        return $this->adrIdent;
    }

    public function setAdrIdent(?int $adrIdent): self
    {
        $this->adrIdent = $adrIdent;

        return $this;
    }

    public function getDeletedAt(): ?\DateTimeImmutable
    {
        return $this->deletedAt;
    }

    public function setDeletedAt(\DateTimeImmutable $deletedAt): self
    {
        $this->deletedAt = $deletedAt;

        return $this;
    }
}
