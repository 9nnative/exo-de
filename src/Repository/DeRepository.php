<?php

namespace App\Repository;

use App\Entity\De;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Doctrine\Persistence\ManagerRegistry;

/**
 * @method De|null find($id, $lockMode = null, $lockVersion = null)
 * @method De|null findOneBy(array $criteria, array $orderBy = null)
 * @method De[]    findAll()
 * @method De[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DeRepository extends ServiceEntityRepository
{
    public function __construct(ManagerRegistry $registry)
    {
        parent::__construct($registry, De::class);
    }

    // /**
    //  * @return De[] Returns an array of De objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('d.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?De
    {
        return $this->createQueryBuilder('d')
            ->andWhere('d.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
