<?php

namespace App\Repository;

use App\Entity\Plasmid;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Plasmid|null find($id, $lockMode = null, $lockVersion = null)
 * @method Plasmid|null findOneBy(array $criteria, array $orderBy = null)
 * @method Plasmid[]    findAll()
 * @method Plasmid[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class PlasmidRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Plasmid::class);
    }

    // /**
    //  * @return Plasmid[] Returns an array of Plasmid objects
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
    public function findOneBySomeField($value): ?Plasmid
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
