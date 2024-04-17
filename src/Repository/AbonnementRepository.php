<?php

namespace App\Repository;

use App\Entity\Abonnement;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @extends ServiceEntityRepository<Abonnement>
 *
 * @method Abonnement|null find($id, $lockMode = null, $lockVersion = null)
 * @method Abonnement|null findOneBy(array $criteria, array $orderBy = null)
 * @method Abonnement[]    findAll()
 * @method Abonnement[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AbonnementRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Abonnement::class);
    }

//    /**
//     * @return Abonnement[] Returns an array of Abonnement objects
//     */
//    public function findByExampleField($value): array
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->orderBy('a.id', 'ASC')
//            ->setMaxResults(10)
//            ->getQuery()
//            ->getResult()
//        ;
//    }

//    public function findOneBySomeField($value): ?Abonnement
//    {
//        return $this->createQueryBuilder('a')
//            ->andWhere('a.exampleField = :val')
//            ->setParameter('val', $value)
//            ->getQuery()
//            ->getOneOrNullResult()
//        ;
//    }

public function findByFilters($minPrix, $maxPrix, $minDuree, $maxDuree)
{
    $qb = $this->createQueryBuilder('a');

    if ($minPrix !== null && $maxPrix !== null) {
        $qb->andWhere('a.prix BETWEEN :minPrix AND :maxPrix')
            ->setParameter('minPrix', $minPrix)
            ->setParameter('maxPrix', $maxPrix);
    } elseif ($minPrix !== null) {
        $qb->andWhere('a.prix >= :minPrix')
            ->setParameter('minPrix', $minPrix);
    } elseif ($maxPrix !== null) {
        $qb->andWhere('a.prix <= :maxPrix')
            ->setParameter('maxPrix', $maxPrix);
    }

    if ($minDuree !== null && $maxDuree !== null) {
        $qb->andWhere('a.duree BETWEEN :minDuree AND :maxDuree')
            ->setParameter('minDuree', $minDuree)
            ->setParameter('maxDuree', $maxDuree);
    } elseif ($minDuree !== null) {
        $qb->andWhere('a.duree >= :minDuree')
            ->setParameter('minDuree', $minDuree);
    } elseif ($maxDuree !== null) {
        $qb->andWhere('a.duree <= :maxDuree')
            ->setParameter('maxDuree', $maxDuree);
    }
      dd( $qb->getQuery());
}


public function findByCategoryWithMemberCount(): array
{
    return $this->createQueryBuilder('a')
        ->select('a.category as category, COUNT(a.id) as memberCount')
        ->groupBy('a.category')
        ->getQuery()
        ->getResult();
}
}
