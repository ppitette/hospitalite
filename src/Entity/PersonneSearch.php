<?php

namespace App\Entity;

use Symfony\Component\Validator\Constraints as Assert;

class PersonneSearch
{
    #[Assert\Length(min: 3, max: 30)]
    private ?string $nom = null;

    #[Assert\Length(min: 3, max: 30)]
    private ?string $prenom = null;

    #[Assert\Length(min: 3, max: 30)]
    private ?string $commune = null;

    #[
        Assert\Length(min: 8, max: 20, minMessage: 'Trop court', maxMessage: 'Trop long'),
        Assert\Regex(pattern: '/^[+0-9]*$/', message: 'Nombres seulement')
    ]
    private ?string $telephone = null;

    private bool $decede = false;

    public function getNom(): ?string
    {
        return $this->nom;
    }

    public function setNom(?string $nom): self
    {
        $this->nom = $nom;

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

    public function getCommune(): ?string
    {
        return $this->commune;
    }

    public function setCommune(?string $commune): self
    {
        $this->commune = $commune;

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

    public function getDecede(): ?bool
    {
        return $this->decede;
    }

    public function setDecede(?bool $decede): self
    {
        $this->decede = $decede;

        return $this;
    }
}
