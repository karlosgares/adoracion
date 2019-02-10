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

    public function activarSemana($tipo = 0) {
        

            $em = $this->getEntityManager();

            for ($i=1; $i < 52; $i++) {
                $sql = "INSERT INTO diasemana_hora(dayofweek, hora, tipo, fin, idcopia)
                SELECT dayofweek, DATE_ADD(hora, INTERVAL " . 7 * $i . " DAY), tipo,  DATE_ADD(fin, INTERVAL  " . 7 * $i . " DAY), id  FROM diasemana_hora WHERE tipo = 0 and hora BETWEEN  '2019-01-28' AND '2019-02-04'";

                    $stmt = $em->getConnection()->prepare($sql);
                    $result = $stmt->execute();
            }
            
             $sql = "INSERT INTO sacerdote_diasemana_hora(sacerdote_id, diasemana_hora_id) SELECT DISTINCT s.sacerdote_id, d.id  FROM sacerdote_diasemana_hora s INNER JOIN  diasemana_hora d on s.diasemana_hora_id = d.idcopia WHERE tipo = 0 and hora  >= '2019-02-04'";
                    $stmt = $em->getConnection()->prepare($sql);
                    $result = $stmt->execute();

            /**
BEGIN
 set @v1 = 1;

 WHILE @v1 < 50 DO
    
    INSERT INTO diasemana_hora(dayofweek, hora, tipo, fin, idcopia)
            SELECT dayofweek, DATE_ADD(hora, INTERVAL 7 * v1 DAY), tipo,  DATE_ADD(fin, INTERVAL 7 * v1 DAY), id WHERE tipo = 0 and hora BETWEEN  '2019-01-28' AND '2019-02-04';
            
            
     INSERT INTO sacerdote_diasemana_hora(sacerdote_id, diasemana_hora_id)
                SELECT s.sacerdote_id, d.id  FROM sacerdote_diasemana_hora s INNER JOIN  diasemana_hora d on s.diasemana_hora_id = d.idcopia
                 WHERE tipo = 0 and hora BETWEEN  '2019-01-28' AND '2019-02-04';
 
    SET v1 = v1 + 1;
    
 END WHILE;

            */

        return true;
    }
}
