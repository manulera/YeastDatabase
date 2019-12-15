<?php

namespace App\Repository;

use App\Entity\DeletionBahlerMethod;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method DeletionBahlerMethod|null find($id, $lockMode = null, $lockVersion = null)
 * @method DeletionBahlerMethod|null findOneBy(array $criteria, array $orderBy = null)
 * @method DeletionBahlerMethod[]    findAll()
 * @method DeletionBahlerMethod[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeletionBahlerMethodRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, DeletionBahlerMethod::class);
    }

    // /**
    //  * @return DeletionBahlerMethod[] Returns an array of DeletionBahlerMethod objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?DeletionBahlerMethod
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
