<?php

namespace App\Repository;

use App\Entity\AlleleDeletion;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method AlleleDeletion|null find($id, $lockMode = null, $lockVersion = null)
 * @method AlleleDeletion|null findOneBy(array $criteria, array $orderBy = null)
 * @method AlleleDeletion[]    findAll()
 * @method AlleleDeletion[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlleleDeletionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, AlleleDeletion::class);
    }

    // /**
    //  * @return AlleleDeletion[] Returns an array of AlleleDeletion objects
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
    public function findOneBySomeField($value): ?AlleleDeletion
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
