<?php

namespace App\Repository;

use App\Entity\ReportComment;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method ReportComment|null find($id, $lockMode = null, $lockVersion = null)
 * @method ReportComment|null findOneBy(array $criteria, array $orderBy = null)
 * @method ReportComment[]    findAll()
 * @method ReportComment[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ReportCommentRepository extends ServiceEntityRepository
{
 public function __construct(ManagerRegistry $registry)
 {
  parent::__construct($registry, ReportComment::class);
 }

 // /**
 //  * @return ReportComment[] Returns an array of ReportComment objects
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
public function findOneBySomeField($value): ?ReportComment
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
