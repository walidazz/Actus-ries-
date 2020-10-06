<?php

namespace App\Repository;

use App\Entity\ReportArticle;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportArticle|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportArticle|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportArticle[]    findAll()
 * @method ReportArticle[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportArticleRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, ReportArticle::class);
    }

    // /**
    //  * @return ReportArticle[] Returns an array of ReportArticle objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('r.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?ReportArticle
    {
        return $this->createQueryBuilder('r')
            ->andWhere('r.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
