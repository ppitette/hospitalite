<?php

namespace App\Repository;

use App\Entity\Personne;
use App\Entity\PersonneSearch;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query\Expr;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Personne>
 *
 * @method Personne|null find($id, $lockMode = null, $lockVersion = null)
 * @method Personne|null findOneBy(array $criteria, array $orderBy = null)
 * @method Personne[]    findAll()
 * @method Personne[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PersonneRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Personne::class);
    }

    public function save(Personne $entity, bool $flush = false): void
    {
        $this->getEntityManager()->persist($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function remove(Personne $entity, bool $flush = false): void
    {
        $this->getEntityManager()->remove($entity);

        if ($flush) {
            $this->getEntityManager()->flush();
        }
    }

    public function findAllNom(string $name): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.nom = :val')
            ->orWhere('p.nomNaiss = :val')
            ->setParameter('val', $name)
            ->orderBy('p.nom', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }

    public function findMySearch(PersonneSearch $search): array
    {
        $query = $this->createQueryBuilder('p');

        if ($search->getNom()) {
            $query = $query
                ->andWhere('p.nom like :nom')
                ->orWhere('p.nomNaiss like :nom')
                ->setParameter('nom', $search->getNom());
        }

        if ($search->getPrenom()) {
            $query = $query
                ->andWhere('p.prenom = :prenom')
                ->setParameter('prenom', $search->getPrenom());
        }

        if ($search->getCommune()) {
            $query = $query
                ->join('p.adresse', 'a', Expr\Join::WITH, 'a.commune = :commune')
                ->andWhere('a.commune = :commune')
                ->setParameter('commune', $search->getCommune());
        }

        if ($search->getTelephone()) {
            $query = $query
                ->andWhere('p.telephone = :telephone')
                ->orWhere('p.mobile = :telephone')
                ->setParameter('telephone', $search->getTelephone());
        }

        if (!$search->getDecede()) {
            $query = $query->andWhere('p.decede = false');
        }

        $query = $query->andWhere($query->expr()->isNull('p.deletedAt'))
            ->orderBy('p.nom', 'ASC')
            ->addOrderBy('p.prenom', 'ASC')
            ->addOrderBy('p.id', 'ASC')
        ;

        return $query->getQuery()->getResult();
    }

    public function findDeces(): array
    {
        return $this->createQueryBuilder('p')
            ->where('p.decede = true')
            ->orderBy('p.dateDeces', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function extractMList(): array
    {
        return $this->createQueryBuilder('p')
            ->select(['p.id', 'p.liste', 'p.nom', 'p.prenom', 'p.courriel', 'p.dateNaiss', 'p.dPele'])
            ->where('p.decede = false')
            ->andWhere('p.lrCourriel = false')
            ->andWhere('p.courriel IS NOT NULL')
            ->andWhere('p.deletedAt IS NULL')
            // ->andWhere('p.liste != :ly')
            // ->setParameter('ly', 'LYCEE')
            ->andWhere('p.liste != :he')
            ->setParameter('he', 'HEFF')
            ->orderBy('p.id', 'ASC')
            ->getQuery()
            ->getResult()
        ;
    }

    public function extractHList(): array
    {
        $an = date('Y') - 5;

        $query = $this->createQueryBuilder('p')
            ->leftJoin('p.adresse', 'a')
            ->leftJoin('p.inscription', 'i')
            ->select(['p.id', 'p.nom', 'p.prenom',
                        'a.remise', 'a.compLoc', 'a.numVoie', 'a.typeVoie', 'a.nomVoie',
                        'a.compVoie', 'a.cPostal', 'a.commune', 'a.pays',
                        'p.telephone', 'p.mobile', 'p.courriel', 'p.lrCourriel',
                        'p.engHosp', 'p.engEgl', 'p.pPele', 'p.nbPele', 'p.dPele',
                        'a.diocese', 'a.paroisse',
                        'p.dateNaiss', 'i.numInsc', 'i.horsEffectif'
            ])
            ->where('p.decede = false')
            ->andWhere('p.liste = :li')
                ->setParameter('li', 'HOSP')
            ->andWhere('p.dPele >= :an')
                ->setParameter('an', $an)
            ->orderBy('p.nom', 'ASC')
            ->addOrderBy('p.prenom', 'ASC')
            ->getQuery()
        ;

        return $query->getResult();
    }
}
