<?php

namespace App\Repository;

use App\Entity\StrainSourceTag;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method StrainSourceTag|null find($id, $lockMode = null, $lockVersion = null)
 * @method StrainSourceTag|null findOneBy(array $criteria, array $orderBy = null)
 * @method StrainSourceTag[]    findAll()
 * @method StrainSourceTag[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StrainSourceTagRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, StrainSourceTag::class);
    }

    // /**
    //  * @return StrainSourceTag[] Returns an array of StrainSourceTag objects
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
    public function findOneBySomeField($value): ?StrainSourceTag
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
