<?php

namespace App\Repository;

use App\Entity\AlleleChunky;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AlleleChunky|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlleleChunky|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlleleChunky[]    findAll()
 * @method AlleleChunky[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlleleChunkyRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlleleChunky::class);
    }

    // /**
    //  * @return AlleleChunky[] Returns an array of AlleleChunky objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?AlleleChunky
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
