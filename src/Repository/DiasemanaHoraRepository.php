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
        $start = new \DateTime('NOW');
        $rst =  $this->createQueryBuilder('d')
            ->where('d.hora >= :start')
            ->andWhere('d.tipo = :tipo')
            ->setParameter('start', $start)
            ->setParameter('tipo', $tipo)
            ->getQuery()
            ->getResult();
        
        if (count($rst) == 0) {
            $last = clone $start;
            $last->sub(new \DateInterval("P7D"));
            $em = $this->getEntityManager();

            $sql = "INSERT INTO diasemana_hora(dayofweek, hora, tipo, fin, idcopia)
            SELECT dayofweek, DATE_ADD(hora, INTERVAL 7 DAY), tipo,  DATE_ADD(fin, INTERVAL 7 DAY), id FROM diasemana_hora WHERE hora >=:hora and tipo=:tipo";

            $stmt = $em->getConnection()->prepare($sql);
            $stmt->bindValue(':tipo', $tipo);
            $stmt->bindValue(':hora', $last->format('Y-m-d 00:00:00'));
            $result = $stmt->execute();

            

            if ($tipo == 0) {
                 $sql = "INSERT INTO sacerdote_diasemana_hora(sacerdote_id, diasemana_hora_id)
                SELECT s.sacerdote_id, d.id  FROM sacerdote_diasemana_hora s INNER JOIN  diasemana_hora d on s.diasemana_hora_id = d.idcopia
                 WHERE hora >=:hora and tipo=:tipo";

                $stmt = $em->getConnection()->prepare($sql);
                $stmt->bindValue(':tipo', $tipo);
                $stmt->bindValue(':hora', $last->format('Y-m-d 00:00:00'));
                $result = $stmt->execute();
            }

        }

        return true;
    }
}
