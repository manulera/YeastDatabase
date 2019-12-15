<?php

namespace App\Repository;

use App\Entity\Oligo;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Oligo|null find($id, $lockMode = null, $lockVersion = null)
 * @method Oligo|null findOneBy(array $criteria, array $orderBy = null)
 * @method Oligo[]    findAll()
 * @method Oligo[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class OligoRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Oligo::class);
    }

    // /**
    //  * @return Oligo[] Returns an array of Oligo objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('o.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Oligo
    {
        return $this->createQueryBuilder('o')
            ->andWhere('o.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
