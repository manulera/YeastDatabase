<?php

namespace App\Repository;

use App\Entity\CustomAlleleAddition;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method CustomAlleleAddition|null find($id, $lockMode = null, $lockVersion = null)
 * @method CustomAlleleAddition|null findOneBy(array $criteria, array $orderBy = null)
 * @method CustomAlleleAddition[]    findAll()
 * @method CustomAlleleAddition[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class CustomAlleleAdditionRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, CustomAlleleAddition::class);
    }

    // /**
    //  * @return CustomAlleleAddition[] Returns an array of CustomAlleleAddition objects
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
    public function findOneBySomeField($value): ?CustomAlleleAddition
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
