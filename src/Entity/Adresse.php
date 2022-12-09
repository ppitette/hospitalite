<?php

namespace App\Entity;

use App\Repository\AdresseRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: AdresseRepository::class)]
class Adresse
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $remise = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $compLoc = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $numVoie = null;

    #[ORM\Column(length: 20, nullable: true)]
    private ?string $typeVoie = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $nomVoie = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $compVoie = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $insee = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $cPostal = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $commune = null;

    #[ORM\Column(length: 70, nullable: true)]
    private ?string $pays = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $paroisse = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $secteur = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $diocese = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8, nullable: true)]
    private ?string $lat = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 10, scale: 8, nullable: true)]
    private ?string $lng = null;

    #[ORM\OneToMany(mappedBy: 'adresse', targetEntity: Personne::class)]
    private Collection $personnes;

    public function __construct()
    {
        $this->personnes = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getRemise(): ?string
    {
        return $this->remise;
    }

    public function setRemise(?string $remise): self
    {
        $this->remise = $remise;

        return $this;
    }

    public function getCompLoc(): ?string
    {
        return $this->compLoc;
    }

    public function setCompLoc(?string $compLoc): self
    {
        $this->compLoc = $compLoc;

        return $this;
    }

    public function getNumVoie(): ?string
    {
        return $this->numVoie;
    }

    public function setNumVoie(?string $numVoie): self
    {
        $this->numVoie = $numVoie;

        return $this;
    }

    public function getTypeVoie(): ?string
    {
        return $this->typeVoie;
    }

    public function setTypeVoie(?string $typeVoie): self
    {
        $this->typeVoie = $typeVoie;

        return $this;
    }

    public function getNomVoie(): ?string
    {
        return $this->nomVoie;
    }

    public function setNomVoie(?string $nomVoie): self
    {
        $this->nomVoie = $nomVoie;

        return $this;
    }

    public function getCompVoie(): ?string
    {
        return $this->compVoie;
    }

    public function setCompVoie(?string $compVoie): self
    {
        $this->compVoie = $compVoie;

        return $this;
    }

    public function getInsee(): ?string
    {
        return $this->insee;
    }

    public function setInsee(?string $insee): self
    {
        $this->insee = $insee;

        return $this;
    }

    public function getCPostal(): ?string
    {
        return $this->cPostal;
    }

    public function setCPostal(?string $cPostal): self
    {
        $this->cPostal = $cPostal;

        return $this;
    }

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(?string $commune): self
    {
        $this->commune = $commune;

        return $this;
    }

    public function getPays(): ?string
    {
        return $this->pays;
    }

    public function setPays(?string $pays): self
    {
        $this->pays = $pays;

        return $this;
    }

    public function getParoisse(): ?string
    {
        return $this->paroisse;
    }

    public function setParoisse(?string $paroisse): self
    {
        $this->paroisse = $paroisse;

        return $this;
    }

    public function getSecteur(): ?string
    {
        return $this->secteur;
    }

    public function setSecteur(?string $secteur): self
    {
        $this->secteur = $secteur;

        return $this;
    }

    public function getDiocese(): ?string
    {
        return $this->diocese;
    }

    public function setDiocese(?string $diocese): self
    {
        $this->diocese = $diocese;

        return $this;
    }

    public function getLat(): ?string
    {
        return $this->lat;
    }

    public function setLat(?string $lat): self
    {
        $this->lat = $lat;

        return $this;
    }

    public function getLng(): ?string
    {
        return $this->lng;
    }

    public function setLng(?string $lng): self
    {
        $this->lng = $lng;

        return $this;
    }

    /**
     * @return Collection<int, Personne>
     */
    public function getPersonnes(): Collection
    {
        return $this->personnes;
    }

    public function addPersonne(Personne $personne): self
    {
        if (!$this->personnes->contains($personne)) {
            $this->personnes->add($personne);
            $personne->setAdresse($this);
        }

        return $this;
    }

    public function removePersonne(Personne $personne): self
    {
        if ($this->personnes->removeElement($personne)) {
            // set the owning side to null (unless already changed)
            if ($personne->getAdresse() === $this) {
                $personne->setAdresse(null);
            }
        }

        return $this;
    }

    public function getLibAdresse(): string
    {
        $adresse = '';

        if ($this->remise) {
            $adresse .= $this->remise."\n";
        }

        if ($this->compLoc) {
            $adresse .= $this->compLoc."\n";
        }

        if ($this->numVoie) {
            $adresse .= $this->numVoie.' ';
        }

        if ($this->typeVoie) {
            $adresse .= $this->typeVoie.' ';
        }

        if ($this->nomVoie) {
            $adresse .= $this->nomVoie."\n";
        }

        if ($this->compVoie) {
            $adresse .= $this->compVoie."\n";
        }

        if ($this->cPostal) {
            $adresse .= $this->cPostal.' ';
        }

        if ($this->commune) {
            $adresse .= $this->commune;
        }

        if ($this->pays && 'France' !== $this->pays) {
            $adresse .= "\n".$this->pays;
        }

        return $adresse;
    }
}
