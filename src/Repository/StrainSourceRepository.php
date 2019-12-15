<?php

namespace App\Repository;

use App\Entity\StrainSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StrainSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method StrainSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method StrainSource[]    findAll()
 * @method StrainSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StrainSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StrainSource::class);
    }

    // /**
    //  * @return StrainSource[] Returns an array of StrainSource objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('s.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?StrainSource
    {
        return $this->createQueryBuilder('s')
            ->andWhere('s.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
