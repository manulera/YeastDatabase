<?php

namespace App\Repository;

use App\Entity\Truncation;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Truncation|null find($id, $lockMode = null, $lockVersion = null)
 * @method Truncation|null findOneBy(array $criteria, array $orderBy = null)
 * @method Truncation[]    findAll()
 * @method Truncation[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class TruncationRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Truncation::class);
    }

    // /**
    //  * @return Truncation[] Returns an array of Truncation objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('t.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Truncation
    {
        return $this->createQueryBuilder('t')
            ->andWhere('t.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
