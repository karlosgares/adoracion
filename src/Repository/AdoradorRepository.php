<?php

namespace App\Repository;

use App\Entity\Adorador;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method Adorador|null find($id, $lockMode = null, $lockVersion = null)
 * @method Adorador|null findOneBy(array $criteria, array $orderBy = null)
 * @method Adorador[]    findAll()
 * @method Adorador[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class AdoradorRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, Adorador::class);
    }

    // /**
    //  * @return Adorador[] Returns an array of Adorador objects
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
    public function findOneBySomeField($value): ?Adorador
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
