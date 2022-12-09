<?php

namespace App\Service;

use App\Entity\Pelerinage;
use App\Repository\PelerinageRepository;
use Symfony\Component\Yaml\Yaml;

class Parametres
{
    private array $params;
    private string $paramDirectory;
    private PelerinageRepository $pelerinageRepository;

    public function __construct(
        string $paramDirectory,
        PelerinageRepository $pelerinageRepository
    ) {
        $this->paramDirectory = $paramDirectory;
        $this->params = Yaml::parseFile($this->paramDirectory.'/2022d.yaml');
        $this->pelerinageRepository = $pelerinageRepository;
    }

    public function getPele(): Pelerinage
    {
        $peleAbr = $this->params['pele']['0']['abr'];

        return $this->pelerinageRepository->findOneBy(['cle' => $peleAbr]);
    }

    /**
     * @return array<string>
     */
    public function listAbrege(string $categ): array
    {
        $liste = [];

        foreach ($this->params[$categ] as $key => $value) {
            // $lgn [] = array($key, $value['abr'], $value['lib'], $value['cmp']);
            $liste[$value['abr']] = $key;
        }

        return $liste;
    }

    /**
     * @return array<string>
     */
    public function listCateg(string $categ): array
    {
        $liste = [];

        foreach ($this->params[$categ] as $key => $value) {
            // $lgn [] = array($key, $value['abr'], $value['lib'], $value['cmp']);
            $liste[$value['lib']] = $key;
        }

        return $liste;
    }

    /**
     * @return array<string>
     */
    public function tabAbrege(string $categ): array
    {
        $table = [];

        foreach ($this->params[$categ] as $key => $value) {
            // $lgn [] = array($key, $value['abr'], $value['lib'], $value['cmp']);
            $table[$key] = $value['abr'];
        }

        return $table;
    }

    public function getAbrege(string $categ, int $cle): string
    {
        $abrege = $this->params[$categ][$cle]['abr'];

        return $abrege;
    }

    public function getComplement(string $categ, int $cle): string
    {
        $complement = $this->params[$categ][$cle]['cmp'];
        if (!$complement) {
            $complement = '';
        }

        return $complement;
    }

    public function getLibelle(string $categ, int $cle): string
    {
        if (!$cle) {
            $cle = 0;
        }

        $libelle = $this->params[$categ][$cle]['lib'];

        return $libelle;
    }

    public function getCleAbr(string $categ, string $abr): int
    {
        $cle = 99;

        foreach ($this->params[$categ] as $key => $value) {
            if ($value['abr'] === $abr) {
                $cle = $key;
            }
        }

        return $cle;
    }

    public function getLibelleAbr(string $categ, string $abr): string
    {
        $lib = '';

        foreach ($this->params[$categ] as $key => $value) {
            if ($value['abr'] === $abr) {
                $lib = $value['lib'];
            }
        }

        return $lib;
    }

    public function getComplementAbr(string $categ, string $abr): string
    {
        $lib = '';

        foreach ($this->params[$categ] as $key => $value) {
            if ($value['abr'] === $abr) {
                $lib = $value['cmp'];
            }
        }

        return $lib;
    }

    public function getMedicalLib(string $med, ?string $aut): string
    {
        $lib = $this->params['medical'][$med]['lib'];
        $medlib = '';

        switch ($med) {
            case 0:
                $medlib = '';
                break;
            case 1:
            case 2:
            case 3:
            case 4:
            case 5:
                $medlib = $lib;
                break;
            case 6:
            case 7:
                $medlib = '';
                break;
            case 8:
                $medlib = $lib.' : '.$aut;
                break;
            case 9:
                $medlib = $aut;
        }

        return $medlib;
    }
}
