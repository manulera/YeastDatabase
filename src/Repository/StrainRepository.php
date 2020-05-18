<?php

namespace App\Repository;

use App\Entity\Strain;
use App\Entity\StrainSource;
use App\Entity\User;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Common\Persistence\ManagerRegistry;
use Doctrine\ORM\Query\Expr\Join;

/**
 * @method Strain|null find($id, $lockMode = null, $lockVersion = null)
 * @method Strain|null findOneBy(array $criteria, array $orderBy = null)
 * @method Strain[]    findAll()
 * @method Strain[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class StrainRepository extends ServiceEntityRepository
{
    /** @var array */
    private $queryDictionary;

    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, Strain::class);
        $this->queryDictionary = [
            'genotype'  => "strain.genotype LIKE :filter%u",
            'id'        => "strain.id LIKE :filter%u",
            'creator'   => "user.username LIKE :filter%u",
        ];
    }


    // public function findAllQueryBuilder($filter = '')
    // {

    //     $qb = $this->createQueryBuilder('strain');
    //     $filter_terms = explode(' ', $filter);
    //     $i = 0;
    //     foreach ($filter_terms as $filter_term) {
    //         $i++;
    //         $qb->andWhere("strain.genotype LIKE :filter$i")
    //             ->setParameter("filter$i", '%' . $filter_term . '%');
    //     }

    //     return $qb;
    // }

    public function findAllQueryBuilder(array $filterArray = [])
    {
        $i = 0;
        $qb = $this->createQueryBuilder("strain")
            ->leftJoin('strain.source', 'strain_source')
            ->leftJoin('strain_source.creator', 'user');
        foreach ($this->queryDictionary as $key => $value) {
            if (array_key_exists($key, $filterArray) && strlen($filterArray[$key])) {
                $filter_terms = explode(' ', $filterArray[$key]);
                foreach ($filter_terms as $filter_term) {
                    $i++;
                    $query_string = sprintf($this->queryDictionary[$key], $i);
                    $qb->andWhere($query_string)
                        ->setParameter("filter$i", '%' . $filter_term . '%');
                }
            }
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
