<?php

namespace App\Repository;

use App\Entity\Promoter;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Promoter|null find($id, $lockMode = null, $lockVersion = null)
 * @method Promoter|null findOneBy(array $criteria, array $orderBy = null)
 * @method Promoter[]    findAll()
 * @method Promoter[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PromoterRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Promoter::class);
    }

    // /**
    //  * @return Promoter[] Returns an array of Promoter objects
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
    public function findOneBySomeField($value): ?Promoter
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
