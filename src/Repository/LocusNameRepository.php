<?php

namespace App\Repository;

use App\Entity\LocusName;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method LocusName|null find($id, $lockMode = null, $lockVersion = null)
 * @method LocusName|null findOneBy(array $criteria, array $orderBy = null)
 * @method LocusName[]    findAll()
 * @method LocusName[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class LocusNameRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, LocusName::class);
    }

    // /**
    //  * @return LocusName[] Returns an array of LocusName objects
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
    public function findOneBySomeField($value): ?LocusName
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
