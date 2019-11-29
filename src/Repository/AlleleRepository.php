<?php

namespace App\Repository;

use App\Entity\Allele;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Allele|null find($id, $lockMode = null, $lockVersion = null)
 * @method Allele|null findOneBy(array $criteria, array $orderBy = null)
 * @method Allele[]    findAll()
 * @method Allele[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AlleleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Allele::class);
    }

    // /**
    //  * @return Allele[] Returns an array of Allele objects
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
    public function findOneBySomeField($value): ?Allele
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
