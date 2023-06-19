<?php

namespace App\Repository;

use App\Entity\Inscription;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Inscription>
 *
 * @method Inscription|null find($id, $lockMode = null, $lockVersion = null)
 * @method Inscription|null findOneBy(array $criteria, array $orderBy = null)
 * @method Inscription[]    findAll()
 * @method Inscription[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class InscriptionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Inscription::class);
    }

    /**
     * @return \Doctrine\ORM\QueryBuilder
     */
    public function findInscrit()
    {
        return $this->createQueryBuilder('i')
            ->join('i.personne', 'p')
            ->orderBy('p.nom', 'ASC')
            ->addOrderBy('p.prenom', 'ASC')
            ->where('i.currentPlace != :et')
            ->setParameter('et', 'insc_desist')
        ;
    }

    /**
     * @return Inscription[] Returns an array of Inscription objects
     */
    public function findInscritEnt(string $ent)
    {
        $query = $this->createQueryBuilder('i')
            ->join('i.personne', 'p')
            ->orderBy('p.nom', 'ASC')
            ->addOrderBy('p.prenom', 'ASC')
            ->where('i.currentPlace != :et')
            ->setParameter('et', 'insc_desist');

        switch ($ent) {
            case 'H':
                $query = $query
                    ->andWhere('i.entite = :ho or i.entite = :hi')
                    ->setParameter('ho', 0)
                    ->setParameter('hi', 1);
                break;
            case 'L':
                $query = $query
                    ->andWhere('i.entite = :le or i.entite = :ly')
                    ->setParameter('le', 2)
                    ->setParameter('ly', 3);
                break;
            case 'P':
                $query = $query
                    ->andWhere('i.entite = :pm')
                    ->setParameter('pm', 4);
        }

        return $query->getQuery()->getResult();
    }

    public function findForPagination(?string $ent = null, bool $desist = true): Query
    {
        $qb = $this->createQueryBuilder('i')
            ->join('i.personne', 'p')
            ->orderBy('p.nom', 'ASC')
            ->addOrderBy('p.prenom', 'ASC')
        ;

        if (!$desist) {
            $qb->where('i.currentPlace != :et')
                ->setParameter('et', 'insc_desist');
        }

        if ($ent) {
            switch ($ent) {
                case 'H':
                    $qb->andWhere('i.entite = :ho or i.entite = :hi')
                        ->setParameter('ho', 0)
                        ->setParameter('hi', 1);
                    break;
                case 'L':
                    $qb->andWhere('i.entite = :le or i.entite = :ly')
                        ->setParameter('le', 2)
                        ->setParameter('ly', 3);
                    break;
                case 'P':
                    $qb->andWhere('i.entite = :pm')
                        ->setParameter('pm', 4);
            }
        }

        return $qb->getQuery();
    }

    public function findInscritHeberg()
    {
        return $this->createQueryBuilder('i')
            ->orderBy('i.numInsc', 'ASC')
            ->where('i.currentPlace != :et')
            ->setParameter('et', 'insc_desist')
        ;
    }

    public function findChambrePart($hotel, $chambre)
    {
        return $this->createQueryBuilder('i')
            ->select('p.prenom')
            ->addSelect('p.nom')
            ->join('i.personne', 'p')
            ->andWhere('i.hebHotel = :hotel')
            ->setParameter('hotel', $hotel)
            ->andWhere('i.hebChambre = :chambre')
            ->setParameter('chambre', $chambre)
            ->andWhere('i.currentPlace != :statut')
            ->setParameter('statut', 'insc_desist')
            ->orderBy('p.nom', 'ASC')
            ->addOrderBy('p.prenom', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function inscVoyage(): array
    {
        $datas = $this->createQueryBuilder('i')
            ->select(['i.numInsc', 'i.entite', 'i.voyAller', 'i.voyRetour'])
            ->andWhere('i.currentPlace != :eta')
            ->setParameter('eta', 'insc_desist')
            ->andWhere('i.currentPlace != :etb')
            ->setParameter('etb', 'insc_refuse')
            ->orderBy('i.numInsc', 'ASC')
            ->getQuery()
            ->getResult()
        ;

        $table['HOSP']['TOT'] = 0;
        $table['HOSP']['IND'] = 0;
        $table['HOSP']['VOY'] = 0;
        $table['HOSP']['ALL'] = 0;
        $table['HOSP']['RET'] = 0;

        $table['LYCE']['TOT'] = 0;
        $table['LYCE']['IND'] = 0;
        $table['LYCE']['VOY'] = 0;
        $table['LYCE']['ALL'] = 0;
        $table['LYCE']['RET'] = 0;

        $table['PMAL']['TOT'] = 0;
        $table['PMAL']['IND'] = 0;
        $table['PMAL']['VOY'] = 0;
        $table['PMAL']['ALL'] = 0;
        $table['PMAL']['RET'] = 0;

        $table['TOT']['TOT'] = 0;
        $table['TOT']['IND'] = 0;
        $table['TOT']['VOY'] = 0;
        $table['TOT']['ALL'] = 0;
        $table['TOT']['RET'] = 0;

        foreach ($datas as $data) {
            switch ($data['entite']) {
                case 0:
                case 1:
                    $table['HOSP']['TOT'] += 1;
                    $table['TOT']['TOT'] += 1;
                    if ($data['voyAller']) {
                        $table['HOSP']['ALL'] += 1;
                        $table['TOT']['ALL'] += 1;
                        if ($data['voyRetour']) {
                            $table['HOSP']['RET'] += 1;
                            $table['HOSP']['VOY'] += 1;
                            $table['TOT']['RET'] += 1;
                            $table['TOT']['VOY'] += 1;
                        }
                    } else {
                        if ($data['voyRetour']) {
                            $table['HOSP']['RET'] += 1;
                            $table['HOSP']['VOY'] += 1;
                            $table['TOT']['RET'] += 1;
                            $table['TOT']['VOY'] += 1;
                        } else {
                            $table['HOSP']['IND'] += 1;
                            $table['TOT']['IND'] += 1;
                        }
                    }
                    break;
                case 2:
                case 3:
                    $table['LYCE']['TOT'] += 1;
                    $table['TOT']['TOT'] += 1;
                    if ($data['voyAller']) {
                        $table['LYCE']['ALL'] += 1;
                        $table['TOT']['ALL'] += 1;
                        if ($data['voyRetour']) {
                            $table['LYCE']['RET'] += 1;
                            $table['LYCE']['VOY'] += 1;
                            $table['TOT']['RET'] += 1;
                            $table['TOT']['VOY'] += 1;
                        }
                    } else {
                        if ($data['voyRetour']) {
                            $table['LYCE']['RET'] += 1;
                            $table['LYCE']['VOY'] += 1;
                            $table['TOT']['RET'] += 1;
                            $table['TOT']['VOY'] += 1;
                        } else {
                            $table['LYCE']['IND'] += 1;
                            $table['TOT']['IND'] += 1;
                        }
                    }
                    break;
                case 4:
                    $table['PMAL']['TOT'] += 1;
                    $table['TOT']['TOT'] += 1;
                    if ($data['voyAller']) {
                        $table['PMAL']['ALL'] += 1;
                        $table['TOT']['ALL'] += 1;
                        if ($data['voyRetour']) {
                            $table['PMAL']['RET'] += 1;
                            $table['PMAL']['VOY'] += 1;
                            $table['TOT']['RET'] += 1;
                            $table['TOT']['VOY'] += 1;
                        }
                    } else {
                        if ($data['voyRetour']) {
                            $table['PMAL']['RET'] += 1;
                            $table['PMAL']['VOY'] += 1;
                            $table['TOT']['RET'] += 1;
                            $table['TOT']['VOY'] += 1;
                        } else {
                            $table['PMAL']['IND'] += 1;
                            $table['TOT']['IND'] += 1;
                        }
                    }
            }
        }
        return $table;
    }

    public function dataGraph01(int $ent, bool $isLa, bool $isHe): int
    {
        $query = $this->createQueryBuilder('i')
            ->select('count(i.id)')
            ->where('i.entite = :ent')
            ->setParameter('ent', $ent)
            ->andWhere('i.listeAtt = :la')
            ->setParameter('la', $isLa)
            ->andWhere('i.horsEffectif = :he')
            ->setParameter('he', $isHe)
            ->andWhere('i.currentPlace != :et')
            ->setParameter('et', 'insc_desist')
        ;

        return $query->getQuery()->getSingleScalarResult();
    }

    public function dataGraph02(int $ent, string $etval): int
    {
        $query = $this->createQueryBuilder('i')
            ->select('count(i.id)')
            ->where('i.entite = :ent')
            ->setParameter('ent', $ent)
            ->andWhere('i.currentPlace = :et')
            ->setParameter('et', $etval)
        ;

        return $query->getQuery()->getSingleScalarResult();
    }

    public function extractInscList(): array
    {
        return $this->createQueryBuilder('i')
            ->join('i.personne', 'p')
            ->join('p.adresse', 'a')
            ->select(['i.numInsc', 'i.inscritAt', 'i.entite', 'i.voyAller', 'i.voyRetour', 'p.civilite', 'p.nom',
                'p.prenom', 'a.id', 'p.telephone', 'p.mobile', 'p.courriel', 'i.conjoint', 'p.dateNaiss', 'a.diocese', 'a.paroisse', ])
            ->orderBy('i.numInsc', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }
}