<?php
namespace App\Manager;

use App\Entity\Sacerdote;
use App\Entity\Adorador;


abstract class CalendarioManager {

	public static function getAdoradoresDias($em, $post, $bWeb = false) {
        $ret = [];
        $id = $post['id'];
        $className = ucfirst($post['tipo']);
        $start = new \DateTime($post['start']);
        $end = new \DateTime($post['end']);
        if ($id > 0)
             $entities = $em->getRepository("App\\Entity\\" . $className)->findById($id);
        else $entities = $em->getRepository("App\\Entity\\" . $className)->findAll();
        
        $bSeguir = true;
        $arrW = []; /// fecha del calendario por día de la semana
        while($bSeguir) {
            $arrW[$start->format("w")] = clone $start;
            $start->add( new \DateInterval('P1D'));
            $bSeguir = (!array_key_exists($start->format("w"), $arrW));
        }


        $arrDay = [];
        foreach ($entities as $entity) {
            foreach ($entity->getDiasemanahoras() as $dia) {
                if ($id == 0) {
                    
                    if ($entity->getBaja()) continue;
                        
                    $idx = $start->format('Y-m-d') . $dia->getHora()->format('H:i');
                    if (!array_key_exists($idx, $arrDay))
                            $arrDay[$idx] = 1;
                    else    $arrDay[$idx]++;
                    
                    $title = ($bWeb)?'':$arrDay[$idx];
                   
                    $color = ($arrDay[$idx] > 1)?Adorador::color1:Adorador::color0;
                }
                else {
                    $title = '';
                    $idx = count($ret);
                    $color = $entity->getColor();
                }
                $start = clone $arrW[$dia->getHora()->format("w")];
                $data = ['id'=> $dia->getId(), 'start'=> sprintf('%sT%s', $start->format('Y-m-d'),$dia->getHora()->format('H:i:s')), 'title' => $title, 'allDay' =>false, 'backgroundColor' => $color];
                    
                if (!is_null($dia->getFin())) {
                    $data['end'] = sprintf('%sT%s', $start->format('Y-m-d'),$dia->getFin()->format('H:i:s'));
                }
                $ret[$idx] = $data;
            }
        }
        return $ret;
    }

    public static function getSacerdotesDias($em, $post) {
        $ret = [];
        $id = $post['id'];
        $className = ucfirst($post['tipo']);
        $start = new \DateTime($post['start']);
        $end = new \DateTime($post['end']);

        $qb =  $em->createQueryBuilder('d')
            ->select('d.id, d.hora, d.fin')
            ->addSelect('s.nombre')
            ->from('App:DiasemanaHora', 'd')
            ->innerJoin('d.sacerdotes', 's')
            ->where('d.hora >= :start')
            ->andWhere('d.tipo = :tipo')
            ->andWhere('d.fin < :end')
            ->setParameter('start', $start->format('Y-m-d 00:00:00'))
            ->setParameter('end', $end->format('Y-m-d 00:00:00'))
            ->setParameter('tipo', 0)
        ;

        if ($id > 0) {
            $qb->andWhere('s.id = :idsacerdote')->setParameter('idsacerdote', $id);
        }
        
        $qb->orderBy('d.hora');
        $rst = $qb->getQuery()->getResult();
        foreach ($rst as $dia) {
            $hora = $dia['hora'];
            $fin= $dia['fin'];
            $idx = $hora->format('Y-m-dH:i');
            $color = Sacerdote::color;
            $title = $dia['nombre'];
            $iddia = $dia['id'];
            $data = ['id'=> $iddia, 'start'=>sprintf('%sT%s', $hora->format('Y-m-d'),$hora->format('H:i:s')), 'title' => $title, 'allDay' =>false, 'backgroundColor' => $color];
                    
            if (!is_null($fin)) {
                $data['end'] = sprintf('%sT%s', $hora->format('Y-m-d'),$fin->format('H:i:s'));
            }
            $ret[$idx] = $data;
        }
        return $ret;
    }

}

?>