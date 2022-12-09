<?php

namespace App\Entity;

use App\Repository\PelerinageRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: PelerinageRepository::class)]
class Pelerinage
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 5)]
    private ?string $cle = null;

    #[ORM\Column(length: 50)]
    private ?string $libelle = null;

    #[ORM\Column(length: 20)]
    private ?string $abrege = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $debut = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $fin = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $theme = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $remarque = null;

    public function __toString(): string
    {
        return $this->cle;
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getCle(): ?string
    {
        return $this->cle;
    }

    public function setCle(string $cle): self
    {
        $this->cle = $cle;

        return $this;
    }

    public function getLibelle(): ?string
    {
        return $this->libelle;
    }

    public function setLibelle(string $libelle): self
    {
        $this->libelle = $libelle;

        return $this;
    }

    public function getAbrege(): ?string
    {
        return $this->abrege;
    }

    public function setAbrege(string $abrege): self
    {
        $this->abrege = $abrege;

        return $this;
    }

    public function getDebut(): ?\DateTimeImmutable
    {
        return $this->debut;
    }

    public function setDebut(?\DateTimeImmutable $debut): self
    {
        $this->debut = $debut;

        return $this;
    }

    public function getFin(): ?\DateTimeImmutable
    {
        return $this->fin;
    }

    public function setFin(?\DateTimeImmutable $fin): self
    {
        $this->fin = $fin;

        return $this;
    }

    public function getTheme(): ?string
    {
        return $this->theme;
    }

    public function setTheme(?string $theme): self
    {
        $this->theme = $theme;

        return $this;
    }

    public function getRemarque(): ?string
    {
        return $this->remarque;
    }

    public function setRemarque(?string $remarque): self
    {
        $this->remarque = $remarque;

        return $this;
    }
}
