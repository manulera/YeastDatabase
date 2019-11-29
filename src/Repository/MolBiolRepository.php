<?php

namespace App\Repository;

use App\Entity\MolBiol;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method MolBiol|null find($id, $lockMode = null, $lockVersion = null)
 * @method MolBiol|null findOneBy(array $criteria, array $orderBy = null)
 * @method MolBiol[]    findAll()
 * @method MolBiol[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class MolBiolRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, MolBiol::class);
    }

    // /**
    //  * @return MolBiol[] Returns an array of MolBiol objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('m.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?MolBiol
    {
        return $this->createQueryBuilder('m')
            ->andWhere('m.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
