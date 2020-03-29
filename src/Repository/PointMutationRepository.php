<?php

namespace App\Repository;

use App\Entity\PointMutation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PointMutation|null find($id, $lockMode = null, $lockVersion = null)
 * @method PointMutation|null findOneBy(array $criteria, array $orderBy = null)
 * @method PointMutation[]    findAll()
 * @method PointMutation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PointMutationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PointMutation::class);
    }

    // /**
    //  * @return PointMutation[] Returns an array of PointMutation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('p.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?PointMutation
    {
        return $this->createQueryBuilder('p')
            ->andWhere('p.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
