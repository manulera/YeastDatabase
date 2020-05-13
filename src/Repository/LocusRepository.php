<?php

namespace App\Repository;

use App\Entity\Locus;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\QueryBuilder;

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
    /**
     * @param string|null $term
     */
    public function getWithSearchQueryBuilder($filter = ''): QueryBuilder
    {
        $qb = $this->createQueryBuilder('locus');
        $filter_terms = explode(' ', $filter);
        $i = 0;
        foreach ($filter_terms as $filter_term) {
            $i++;
            $qb->andWhere("IDENTITY(locus.name) LIKE :filter$i")
                ->setParameter("filter$i", '%' . $filter_term . '%');
        }
        return $qb;
    }

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
