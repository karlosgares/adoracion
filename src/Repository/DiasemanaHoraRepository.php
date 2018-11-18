<?php

namespace App\Repository;

use App\Entity\DiasemanaHora;
use Doctrine\Bundle\DoctrineBundle\Repository\ServiceEntityRepository;
use Symfony\Bridge\Doctrine\RegistryInterface;

/**
 * @method DiasemanaHora|null find($id, $lockMode = null, $lockVersion = null)
 * @method DiasemanaHora|null findOneBy(array $criteria, array $orderBy = null)
 * @method DiasemanaHora[]    findAll()
 * @method DiasemanaHora[]    findBy(array $criteria, array $orderBy = null, $limit = null, $offset = null)
 */
class DiasemanaHoraRepository extends ServiceEntityRepository
{
    public function __construct(RegistryInterface $registry)
    {
        parent::__construct($registry, DiasemanaHora::class);
    }

    // /**
    //  * @return DiasemanaHora[] Returns an array of DiasemanaHora objects
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
    public function findOneBySomeField($value): ?DiasemanaHora
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
