<?php

namespace App\Repository;

use App\Entity\Article;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\ORM\Query;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method Article|null find($id, $lockMode = null, $lockVersion = null)
 * @method Article|null findOneBy(array $criteria, array $orderBy = null)
 * @method Article[]    findAll()
 * @method Article[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class ArticleRepository extends ServiceEntityRepository
{
  public function __construct(ManagerRegistry $registry)
  {
    parent::__construct($registry, Article::class);
  }

  //https://youtu.be/S9yhk4V1Fcg?t=2165

  /**
   * @return Query
   */
  public function findAllQuery(): Query
  {
    return $this->createQueryBuilder('a')

      ->orderBy('a.createdAt', 'DESC')
      // ->setMaxResults(10)
      ->getQuery();


  }

  public function findLike($key): Query
  {
    return $this->getEntityManager()
      ->createQuery("z
                SELECT a FROM App\Entity\Article a
                WHERE a.title LIKE :key ")
      ->setParameter('key', '%' . $key . '%');
  }

  public function search($mots): Query
  {
    return $this->createQueryBuilder('a')
      ->where('MATCH_AGAINST(a.title,a.introduction,a.content) AGAINST(:mots boolean)>0')
      ->setParameter('mots', $mots)
      ->orderBy('a.createdAt', 'DESC')
      ->getQuery();
  }

  public function findThreeLast($value)
  {
    return $this->createQueryBuilder('a')
      ->join('a.category', 'c')
      // ->select('a as article, c.libelle')
      ->andWhere('c.libelle = :val')
      ->setParameter('val', $value)
      ->orderBy('a.createdAt', 'DESC')
      ->setMaxResults(3)
      ->getQuery()
      ->getResult();
  }

  /**
   * @return Query
   */
  public function findAllByCategory($value): Query
  {
    return $this->createQueryBuilder('a')

      ->orderBy('a.createdAt', 'DESC')
      ->join('a.category', 'c')
      // ->select('a as article, c.libelle')
      ->andWhere('c.libelle = :val')
      ->setParameter('val', $value)
      ->orderBy('a.createdAt', 'DESC')
      ->getQuery();
  }



  /**
   * @return Query
   */
  public function findAllByTags($value): Query
  {
    return $this->createQueryBuilder('a')

      ->orderBy('a.createdAt', 'DESC')
      ->join('a.tags', 't')
      // ->select('a as article, c.libelle')
      ->andWhere('t.libelle = :val')
      ->setParameter('val', $value)
      ->orderBy('a.createdAt', 'DESC')
      ->getQuery();
  }
  /*
public function findOneBySomeField($value): ?Article
{
return $this->createQueryBuilder('a')
->andWhere('a.exampleField = :val')
->setParameter('val', $value)
->getQuery()
->getOneOrNullResult()
;
}
 */
}
