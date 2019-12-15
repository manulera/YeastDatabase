<?php

namespace App\Repository;

use App\Entity\CustomStrainSource;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CustomStrainSource|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomStrainSource|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomStrainSource[]    findAll()
 * @method CustomStrainSource[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomStrainSourceRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomStrainSource::class);
    }

    // /**
    //  * @return CustomStrainSource[] Returns an array of CustomStrainSource objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('c.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?CustomStrainSource
    {
        return $this->createQueryBuilder('c')
            ->andWhere('c.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
