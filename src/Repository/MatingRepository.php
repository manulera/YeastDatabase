<?php

namespace App\Repository;

use App\Entity\Mating;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Mating|null find($id, $lockMode = null, $lockVersion = null)
 * @method Mating|null findOneBy(array $criteria, array $orderBy = null)
 * @method Mating[]    findAll()
 * @method Mating[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MatingRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Mating::class);
    }

    // /**
    //  * @return Mating[] Returns an array of Mating objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Mating
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
