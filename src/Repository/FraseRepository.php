<?php

namespace App\Repository;

use App\Entity\Frase;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Frase|null find($id, $lockMode = null, $lockVersion = null)
 * @method Frase|null findOneBy(array $criteria, array $orderBy = null)
 * @method Frase[]    findAll()
 * @method Frase[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class FraseRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Frase::class);
    }

    // /**
    //  * @return Frase[] Returns an array of Frase objects
    //  */
    /*
    public function findByExampleField($value)
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->orderBy('f.id', 'ASC')
            ->setMaxResults(10)
            ->getQuery()
            ->getResult()
        ;
    }
    */

    /*
    public function findOneBySomeField($value): ?Frase
    {
        return $this->createQueryBuilder('f')
            ->andWhere('f.exampleField = :val')
            ->setParameter('val', $value)
            ->getQuery()
            ->getOneOrNullResult()
        ;
    }
    */
}
