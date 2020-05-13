<?php

namespace App\Repository;

use App\Entity\PombaseId;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method PombaseId|null find($id, $lockMode = null, $lockVersion = null)
 * @method PombaseId|null findOneBy(array $criteria, array $orderBy = null)
 * @method PombaseId[]    findAll()
 * @method PombaseId[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PombaseIdRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, PombaseId::class);
    }

    // /**
    //  * @return PombaseId[] Returns an array of PombaseId objects
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
    public function findOneBySomeField($value): ?PombaseId
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
