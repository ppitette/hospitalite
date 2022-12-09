<?php

namespace App\Entity;

use App\Repository\InscriptionRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Bridge\Doctrine\Validator\Constraints\UniqueEntity;
use Symfony\Component\Validator\Constraints as Assert;

#[ORM\Entity(repositoryClass: InscriptionRepository::class)]
#[UniqueEntity(fields: 'numInsc', message: 'Numéro d\'inscription déjà enregistré.')]
class Inscription
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column]
    private ?int $numInsc = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $inscritAt = null;

    /**
     * Lien table Parametres : Domaine = entite
     */
    #[ORM\Column]
    private ?int $entite = null;

    #[ORM\Column]
    private ?bool $couple = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $conjoint = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $minDest = null;

    #[ORM\Column]
    private ?bool $nouveau = null;

    #[ORM\Column]
    private ?bool $horsEffectif = null;

    #[ORM\Column]
    private ?bool $listeAtt = null;

    #[ORM\Column]
    private ?bool $voyAller = null;

    #[ORM\Column]
    private ?bool $voyRetour = null;

    /**
     * Lien table Parametres : Domaine = situat
     */
    #[ORM\Column]
    private ?int $situation = null;

    /**
     * Lien table Parametres : Domaine = connu
     */
    #[ORM\Column]
    private ?int $connuHosp = null;

    #[ORM\Column(length: 50, nullable: true)]
    #[Assert\Length(max: 50, maxMessage: 'Pas plus de {{ limit }} caractères')]
    private ?string $connuHospQui = null;

    #[ORM\Column]
    private ?bool $hebHosp = null;

    /**
     * Lien table Parametres : Domaine = hotel
     */
    #[ORM\Column]
    private ?int $prefHeberg = null;

    #[ORM\Column]
    private ?bool $hebSingle = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $hebPerso = null;

    /**
     * Lien table Parametres : Domaine = partch
     */
    #[ORM\Column]
    private ?int $partageChambre = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $partageChambreNom = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $envoiAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $retourAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $valideAt = null;

    #[ORM\Column(nullable: true)]
    private ?\DateTimeImmutable $desisteAt = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $personneUrgence = null;

    /**
     * Lien table Parametres : Domaine = chserv
     */
    #[ORM\Column]
    private ?int $serviceChambre = null;

    #[ORM\Column]
    private ?bool $porte = null;

    #[ORM\Column]
    private ?bool $voiture = null;

    #[ORM\Column]
    private ?bool $gardeNuit = null;

    #[ORM\Column]
    private ?bool $piscine = null;

    #[ORM\Column]
    private ?bool $animation = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $instrument = null;

    #[ORM\Column]
    private ?bool $tenue = null;

    /**
     * Lien table Parametres : Domaine = hotel
     */
    #[ORM\Column]
    private ?int $hebHotel = null;

    #[ORM\Column(length: 5, nullable: true)]
    private ?string $hebChambre = null;

    /**
     * Lien table Parametres : Domaine = resp
     */
    #[ORM\Column]
    private ?int $trnsResp = null;

    /**
     * Lien table Parametres : Domaine = transp
     */
    #[ORM\Column]
    private ?int $trnsCar = null;

    #[ORM\Column(length: 4, nullable: true)]
    private ?string $trnsPlace = null;

    /**
     * Lien table Parametres : Domaine = siege
     */
    #[ORM\Column]
    private ?int $trnsSiege = null;

    #[ORM\Column(type: Types::TEXT, nullable: true)]
    private ?string $srvsDepart = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $srvsEmbarq = null;

    #[ORM\Column(length: 255, nullable: true)]
    private ?string $srvsVoyage = null;

    /**
     * Lien table Parametres : Domaine = resp
     */
    #[ORM\Column]
    private ?int $peleResp = null;

    /**
     * Lien table Parametres : Domaine = groupe
     */
    #[ORM\Column]
    private ?int $groupe = null;

    #[ORM\Column(length: 30, nullable: true)]
    private ?string $service = null;

    #[ORM\Column(length: 10, nullable: true)]
    private ?string $srvsChambre = null;

    #[ORM\Column]
    private ?bool $srvsLinge = null;

    #[ORM\Column]
    private ?bool $permAccueil = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $permAccueilLib = null;

    #[ORM\Column]
    private ?bool $permNuit = null;

    #[ORM\Column(length: 25, nullable: true)]
    private ?string $permNuitLib = null;

    #[ORM\Column]
    private ?bool $srvsEau = null;

    #[ORM\Column]
    private ?bool $srvsAscenseur = null;

    #[ORM\Column]
    private ?bool $srvsHygiene = null;

    #[ORM\Column]
    private ?bool $srvsPiscines = null;

    #[ORM\Column]
    private ?bool $srvsMenage = null;

    #[ORM\Column]
    private ?bool $srvsMedicament = null;

    #[ORM\Column(length: 50, nullable: true)]
    private ?string $srvsCovid = null;

    /**
     * Lien table Parametres : Domaine = etat
     */
    #[ORM\Column(length: 20, nullable: true)]
    private ?string $currentPlace = null;

    #[ORM\OneToOne(mappedBy: 'inscription', cascade: ['persist', 'remove'])]
    private ?Personne $personne = null;

    #[ORM\ManyToOne(inversedBy: 'inscriptions')]
    private ?Pelerinage $pelerinage = null;

    public function __construct()
    {
        $this->entite = 0;
        $this->situation = 0;
        $this->prefHeberg = 0;
        $this->partageChambre = 0;
        $this->connuHosp = 0;
        $this->serviceChambre = 0;
        $this->groupe = 0;
        $this->hebHotel = 0;
        $this->trnsCar = 0;
        $this->trnsSiege = 0;
        $this->trnsResp = 0;
        $this->peleResp = 0;
        $this->srvsLinge = false;
        $this->permAccueil = false;
        $this->permNuit = false;
        $this->srvsEau = false;
        $this->srvsAscenseur = false;
        $this->srvsPiscines = false;
        $this->srvsHygiene = false;
        $this->srvsMenage = false;
        $this->srvsMedicament = false;
    }

    public function getVoyage(): string
    {
        $voyage = 'XX';

        if ($this->voyAller) {
            if ($this->voyRetour) {
                $voyage = 'AR';
            } else {
                $voyage = 'A-';
            }
        } else {
            if ($this->voyRetour) {
                $voyage = '-R';
            } else {
                $voyage = '--';
            }
        }

        return $voyage;
    }

    public function getId(): ?int
    {
        return $this->id;
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

    public function getInscritAt(): ?\DateTimeImmutable
    {
        return $this->inscritAt;
    }

    public function setInscritAt(?\DateTimeImmutable $inscritAt): self
    {
        $this->inscritAt = $inscritAt;

        return $this;
    }

    public function getEntite(): ?int
    {
        return $this->entite;
    }

    public function setEntite(int $entite): self
    {
        $this->entite = $entite;

        return $this;
    }

    public function isCouple(): ?bool
    {
        return $this->couple;
    }

    public function setCouple(bool $couple): self
    {
        $this->couple = $couple;

        return $this;
    }

    public function getConjoint(): ?string
    {
        return $this->conjoint;
    }

    public function setConjoint(?string $conjoint): self
    {
        $this->conjoint = $conjoint;

        return $this;
    }

    public function getMinDest(): ?string
    {
        return $this->minDest;
    }

    public function setMinDest(?string $minDest): self
    {
        $this->minDest = $minDest;

        return $this;
    }

    public function isNouveau(): ?bool
    {
        return $this->nouveau;
    }

    public function setNouveau(bool $nouveau): self
    {
        $this->nouveau = $nouveau;

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

    public function isListeAtt(): ?bool
    {
        return $this->listeAtt;
    }

    public function setListeAtt(bool $listeAtt): self
    {
        $this->listeAtt = $listeAtt;

        return $this;
    }

    public function isVoyAller(): ?bool
    {
        return $this->voyAller;
    }

    public function setVoyAller(bool $voyAller): self
    {
        $this->voyAller = $voyAller;

        return $this;
    }

    public function isVoyRetour(): ?bool
    {
        return $this->voyRetour;
    }

    public function setVoyRetour(bool $voyRetour): self
    {
        $this->voyRetour = $voyRetour;

        return $this;
    }

    public function getSituation(): ?int
    {
        return $this->situation;
    }

    public function setSituation(int $situation): self
    {
        $this->situation = $situation;

        return $this;
    }

    public function getConnuHosp(): ?int
    {
        return $this->connuHosp;
    }

    public function setConnuHosp(int $connuHosp): self
    {
        $this->connuHosp = $connuHosp;

        return $this;
    }

    public function getConnuHospQui(): ?string
    {
        return $this->connuHospQui;
    }

    public function setConnuHospQui(?string $connuHospQui): self
    {
        $this->connuHospQui = $connuHospQui;

        return $this;
    }

    public function isHebHosp(): ?bool
    {
        return $this->hebHosp;
    }

    public function setHebHosp(bool $hebHosp): self
    {
        $this->hebHosp = $hebHosp;

        return $this;
    }

    public function getPrefHeberg(): ?int
    {
        return $this->prefHeberg;
    }

    public function setPrefHeberg(int $prefHeberg): self
    {
        $this->prefHeberg = $prefHeberg;

        return $this;
    }

    public function isHebSingle(): ?bool
    {
        return $this->hebSingle;
    }

    public function setHebSingle(bool $hebSingle): self
    {
        $this->hebSingle = $hebSingle;

        return $this;
    }

    public function getHebPerso(): ?string
    {
        return $this->hebPerso;
    }

    public function setHebPerso(?string $hebPerso): self
    {
        $this->hebPerso = $hebPerso;

        return $this;
    }

    public function getPartageChambre(): ?int
    {
        return $this->partageChambre;
    }

    public function setPartageChambre(int $partageChambre): self
    {
        $this->partageChambre = $partageChambre;

        return $this;
    }

    public function getPartageChambreNom(): ?string
    {
        return $this->partageChambreNom;
    }

    public function setPartageChambreNom(?string $partageChambreNom): self
    {
        $this->partageChambreNom = $partageChambreNom;

        return $this;
    }

    public function getEnvoiAt(): ?\DateTimeImmutable
    {
        return $this->envoiAt;
    }

    public function setEnvoiAt(?\DateTimeImmutable $envoiAt): self
    {
        $this->envoiAt = $envoiAt;

        return $this;
    }

    public function getRetourAt(): ?\DateTimeImmutable
    {
        return $this->retourAt;
    }

    public function setRetourAt(?\DateTimeImmutable $retourAt): self
    {
        $this->retourAt = $retourAt;

        return $this;
    }

    public function getValideAt(): ?\DateTimeImmutable
    {
        return $this->valideAt;
    }

    public function setValideAt(?\DateTimeImmutable $valideAt): self
    {
        $this->valideAt = $valideAt;

        return $this;
    }

    public function getDesisteAt(): ?\DateTimeImmutable
    {
        return $this->desisteAt;
    }

    public function setDesisteAt(?\DateTimeImmutable $desisteAt): self
    {
        $this->desisteAt = $desisteAt;

        return $this;
    }

    public function getPersonneUrgence(): ?string
    {
        return $this->personneUrgence;
    }

    public function setPersonneUrgence(?string $personneUrgence): self
    {
        $this->personneUrgence = $personneUrgence;

        return $this;
    }

    public function getServiceChambre(): ?int
    {
        return $this->serviceChambre;
    }

    public function setServiceChambre(int $serviceChambre): self
    {
        $this->serviceChambre = $serviceChambre;

        return $this;
    }

    public function isPorte(): ?bool
    {
        return $this->porte;
    }

    public function setPorte(bool $porte): self
    {
        $this->porte = $porte;

        return $this;
    }

    public function isVoiture(): ?bool
    {
        return $this->voiture;
    }

    public function setVoiture(bool $voiture): self
    {
        $this->voiture = $voiture;

        return $this;
    }

    public function isGardeNuit(): ?bool
    {
        return $this->gardeNuit;
    }

    public function setGardeNuit(bool $gardeNuit): self
    {
        $this->gardeNuit = $gardeNuit;

        return $this;
    }

    public function isPiscine(): ?bool
    {
        return $this->piscine;
    }

    public function setPiscine(bool $piscine): self
    {
        $this->piscine = $piscine;

        return $this;
    }

    public function isAnimation(): ?bool
    {
        return $this->animation;
    }

    public function setAnimation(bool $animation): self
    {
        $this->animation = $animation;

        return $this;
    }

    public function getInstrument(): ?string
    {
        return $this->instrument;
    }

    public function setInstrument(?string $instrument): self
    {
        $this->instrument = $instrument;

        return $this;
    }

    public function isTenue(): ?bool
    {
        return $this->tenue;
    }

    public function setTenue(bool $tenue): self
    {
        $this->tenue = $tenue;

        return $this;
    }

    public function getHebHotel(): ?int
    {
        return $this->hebHotel;
    }

    public function setHebHotel(int $hebHotel): self
    {
        $this->hebHotel = $hebHotel;

        return $this;
    }

    public function getHebChambre(): ?string
    {
        return $this->hebChambre;
    }

    public function setHebChambre(?string $hebChambre): self
    {
        $this->hebChambre = $hebChambre;

        return $this;
    }

    public function getTrnsResp(): ?int
    {
        return $this->trnsResp;
    }

    public function setTrnsResp(int $trnsResp): self
    {
        $this->trnsResp = $trnsResp;

        return $this;
    }

    public function getTrnsCar(): ?int
    {
        return $this->trnsCar;
    }

    public function setTrnsCar(int $trnsCar): self
    {
        $this->trnsCar = $trnsCar;

        return $this;
    }

    public function getTrnsPlace(): ?string
    {
        return $this->trnsPlace;
    }

    public function setTrnsPlace(?string $trnsPlace): self
    {
        $this->trnsPlace = $trnsPlace;

        return $this;
    }

    public function getTrnsSiege(): ?int
    {
        return $this->trnsSiege;
    }

    public function setTrnsSiege(int $trnsSiege): self
    {
        $this->trnsSiege = $trnsSiege;

        return $this;
    }

    public function getSrvsDepart(): ?string
    {
        return $this->srvsDepart;
    }

    public function setSrvsDepart(?string $srvsDepart): self
    {
        $this->srvsDepart = $srvsDepart;

        return $this;
    }

    public function getSrvsEmbarq(): ?string
    {
        return $this->srvsEmbarq;
    }

    public function setSrvsEmbarq(?string $srvsEmbarq): self
    {
        $this->srvsEmbarq = $srvsEmbarq;

        return $this;
    }

    public function getSrvsVoyage(): ?string
    {
        return $this->srvsVoyage;
    }

    public function setSrvsVoyage(?string $srvsVoyage): self
    {
        $this->srvsVoyage = $srvsVoyage;

        return $this;
    }

    public function getPeleResp(): ?int
    {
        return $this->peleResp;
    }

    public function setPeleResp(int $peleResp): self
    {
        $this->peleResp = $peleResp;

        return $this;
    }

    public function getGroupe(): ?int
    {
        return $this->groupe;
    }

    public function setGroupe(int $groupe): self
    {
        $this->groupe = $groupe;

        return $this;
    }

    public function getService(): ?string
    {
        return $this->service;
    }

    public function setService(?string $service): self
    {
        $this->service = $service;

        return $this;
    }

    public function getSrvsChambre(): ?string
    {
        return $this->srvsChambre;
    }

    public function setSrvsChambre(?string $srvsChambre): self
    {
        $this->srvsChambre = $srvsChambre;

        return $this;
    }

    public function isSrvsLinge(): ?bool
    {
        return $this->srvsLinge;
    }

    public function setSrvsLinge(bool $srvsLinge): self
    {
        $this->srvsLinge = $srvsLinge;

        return $this;
    }

    public function isPermAccueil(): ?bool
    {
        return $this->permAccueil;
    }

    public function setPermAccueil(bool $permAccueil): self
    {
        $this->permAccueil = $permAccueil;

        return $this;
    }

    public function getPermAccueilLib(): ?string
    {
        return $this->permAccueilLib;
    }

    public function setPermAccueilLib(?string $permAccueilLib): self
    {
        $this->permAccueilLib = $permAccueilLib;

        return $this;
    }

    public function isPermNuit(): ?bool
    {
        return $this->permNuit;
    }

    public function setPermNuit(bool $permNuit): self
    {
        $this->permNuit = $permNuit;

        return $this;
    }

    public function getPermNuitLib(): ?string
    {
        return $this->permNuitLib;
    }

    public function setPermNuitLib(?string $permNuitLib): self
    {
        $this->permNuitLib = $permNuitLib;

        return $this;
    }

    public function isSrvsEau(): ?bool
    {
        return $this->srvsEau;
    }

    public function setSrvsEau(bool $srvsEau): self
    {
        $this->srvsEau = $srvsEau;

        return $this;
    }

    public function isSrvsAscenseur(): ?bool
    {
        return $this->srvsAscenseur;
    }

    public function setSrvsAscenseur(bool $srvsAscenseur): self
    {
        $this->srvsAscenseur = $srvsAscenseur;

        return $this;
    }

    public function isSrvsHygiene(): ?bool
    {
        return $this->srvsHygiene;
    }

    public function setSrvsHygiene(bool $srvsHygiene): self
    {
        $this->srvsHygiene = $srvsHygiene;

        return $this;
    }

    public function isSrvsPiscines(): ?bool
    {
        return $this->srvsPiscines;
    }

    public function setSrvsPiscines(bool $srvsPiscines): self
    {
        $this->srvsPiscines = $srvsPiscines;

        return $this;
    }

    public function isSrvsMenage(): ?bool
    {
        return $this->srvsMenage;
    }

    public function setSrvsMenage(bool $srvsMenage): self
    {
        $this->srvsMenage = $srvsMenage;

        return $this;
    }

    public function isSrvsMedicament(): ?bool
    {
        return $this->srvsMedicament;
    }

    public function setSrvsMedicament(bool $srvsMedicament): self
    {
        $this->srvsMedicament = $srvsMedicament;

        return $this;
    }

    public function getSrvsCovid(): ?string
    {
        return $this->srvsCovid;
    }

    public function setSrvsCovid(?string $srvsCovid): self
    {
        $this->srvsCovid = $srvsCovid;

        return $this;
    }

    public function getCurrentPlace(): ?string
    {
        return $this->currentPlace;
    }

    public function setCurrentPlace(?string $currentPlace): self
    {
        $this->currentPlace = $currentPlace;

        return $this;
    }

    public function getPersonne(): ?Personne
    {
        return $this->personne;
    }

    public function setPersonne(?Personne $personne): self
    {
        // unset the owning side of the relation if necessary
        if ($personne === null && $this->personne !== null) {
            $this->personne->setInscription(null);
        }

        // set the owning side of the relation if necessary
        if ($personne !== null && $personne->getInscription() !== $this) {
            $personne->setInscription($this);
        }

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
