<?php

namespace App\Entity;

use App\Repository\ParticipationRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ParticipationRepository::class)]
class Participation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $agePele = null;

    #[ORM\Column]
    private ?int $numInsc = null;

    #[ORM\Column(length: 2)]
    private ?string $entite = null;

    #[ORM\Column]
    private ?bool $horsEffectif = null;

    #[ORM\Column]
    private ?bool $desist = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $resp = null;

    #[ORM\Column(length: 100, nullable: true)]
    private ?string $medical = null;

    #[ORM\Column(length: 2)]
    private ?string $voyage = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $groupe = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $hebergement = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Personne $personne = null;

    #[ORM\ManyToOne(inversedBy: 'participations')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Pelerinage $pelerinage = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getAgePele(): ?int
    {
        return $this->agePele;
    }

    public function setAgePele(int $agePele): self
    {
        $this->agePele = $agePele;

        return $this;
    }

    public function getNumInsc(): ?int
    {
        return $this->numInsc;
    }

    public function setNumInsc(int $numInsc): self
    {
        $this->numInsc = $numInsc;

        return $this;
    }

    public function getEntite(): ?string
    {
        return $this->entite;
    }

    public function setEntite(string $entite): self
    {
        $this->entite = $entite;

        return $this;
    }

    public function isHorsEffectif(): ?bool
    {
        return $this->horsEffectif;
    }

    public function setHorsEffectif(bool $horsEffectif): self
    {
        $this->horsEffectif = $horsEffectif;

        return $this;
    }

    public function isDesist(): ?bool
    {
        return $this->desist;
    }

    public function setDesist(bool $desist): self
    {
        $this->desist = $desist;

        return $this;
    }

    public function getResp(): ?string
    {
        return $this->resp;
    }

    public function setResp(?string $resp): self
    {
        $this->resp = $resp;

        return $this;
    }

    public function getMedical(): ?string
    {
        return $this->medical;
    }

    public function setMedical(?string $medical): self
    {
        $this->medical = $medical;

        return $this;
    }

    public function getVoyage(): ?string
    {
        return $this->voyage;
    }

    public function setVoyage(string $voyage): self
    {
        $this->voyage = $voyage;

        return $this;
    }

    public function getGroupe(): ?string
    {
        return $this->groupe;
    }

    public function setGroupe(?string $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getHebergement(): ?string
    {
        return $this->hebergement;
    }

    public function setHebergement(?string $hebergement): self
    {
        $this->hebergement = $hebergement;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): self
    {
        $this->personne = $personne;

        return $this;
    }

    public function getPelerinage(): ?Pelerinage
    {
        return $this->pelerinage;
    }

    public function setPelerinage(?Pelerinage $pelerinage): self
    {
        $this->pelerinage = $pelerinage;

        return $this;
    }
}
