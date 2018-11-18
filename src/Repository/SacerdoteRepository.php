<?php

namespace App\Repository;

use App\Entity\Sacerdote;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Sacerdote|null find($id, $lockMode = null, $lockVersion = null)
 * @method Sacerdote|null findOneBy(array $criteria, array $orderBy = null)
 * @method Sacerdote[]    findAll()
 * @method Sacerdote[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class SacerdoteRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Sacerdote::class);
    }

    // /**
    //  * @return Sacerdote[] Returns an array of Sacerdote objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('a')
            ->andWhere('a.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('a.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Sacerdote
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
