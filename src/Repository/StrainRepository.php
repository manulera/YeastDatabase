<?php

namespace App\Repository;

use App\Entity\Strain;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;

/**
 * @method Strain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Strain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Strain[]    findAll()
 * @method Strain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StrainRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Strain::class);
    }

    public function findAllQueryBuilder($filter = '')
    {
        $qb = $this->createQueryBuilder('strain');
        $filter_terms = explode(' ', $filter);
        $i = 0;
        foreach ($filter_terms as $filter_term) {
            $i++;
            $qb->andWhere("strain.genotype LIKE :filter$i")
                ->setParameter("filter$i", '%' . $filter_term . '%');
        }

        return $qb;
    }
    // /**
    //  * @return Strain[] Returns an array of Strain objects
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
    public function findOneBySomeField($value): ?Strain
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
