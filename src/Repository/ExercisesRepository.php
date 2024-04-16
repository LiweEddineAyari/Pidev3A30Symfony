<?php

namespace App\Repository;

use App\Entity\Exercises;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

class ExercisesRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Exercises::class);
    }
    public function findByTargetAndType(string $target, string $type): array
    {
        return $this->createQueryBuilder('e')
            ->andWhere('e.target = :target')
            ->andWhere('e.type = :type')
            ->setParameter('target', $target)
            ->setParameter('type', $type)
            ->getQuery()
            ->getResult();
    }
    
}
