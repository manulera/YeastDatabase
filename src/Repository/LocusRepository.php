<?php

namespace App\Repository;

use App\Entity\Locus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Locus|null find($id, $lockMode = null, $lockVersion = null)
 * @method Locus|null findOneBy(array $criteria, array $orderBy = null)
 * @method Locus[]    findAll()
 * @method Locus[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocusRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Locus::class);
    }

    // /**
    //  * @return Locus[] Returns an array of Locus objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('l.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Locus
    {
        return $this->createQueryBuilder('l')
            ->andWhere('l.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
