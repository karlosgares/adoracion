<?php
namespace App\Manager;

use App\Entity\Adorador;
use App\Entity\Sacerdote;

class CalendarioManager
{

    public static function getAdoradoresDiasNumero($entityManager)
    {
        $response = [];
        $className = "AdoradoresDiaHora";
        $entities = $entityManager->getRepository("App\\Entity\\" . $className)->findAll();
        $numHoras = self::getNumeroAdoradores($entities);
        ksort($numHoras);

        $dia = self::primerDiaSemana();
        for ($cont = 0; $cont < 7; $cont++) {
            $response = self::addHoraNum($dia, $numHoras, $response);
            $dia->add(new \DateInterval("P1D"));
        }
        
  
        return $response;
    }

    private static function addHoraNum($dia, $numHoras, $response)
    {
        foreach (range(0, 23) as $hora) {
            $idSemana = $dia->format('w') . "_" . $hora;

            $horaEnd = $hora + 1;

            if ($hora < 10) {
                $hora = "0{$hora}";
            }

            if ($horaEnd < 10) {
                $horaEnd = "0{$horaEnd}";
            }
            $idx = $dia->format('Y-m-d') . $hora;

            $cont = (!array_key_exists($idSemana, $numHoras)) ? 0 : $numHoras[$idSemana];
            $background = ($cont > 1) ? "green" : "red";
            
            $response[$idx] = [
                "id" => $dia->format('Ymd') . $hora,
                "start" => $dia->format("Y-m-d") . "T" . "$hora:00:00",
                "end" => $dia->format("Y-m-d") . "T" . "{$horaEnd}:00:00",
                "title" => "",
                "allDay" => null,
                "backgroundColor" => $background,
                "count" => $cont,
                "users" => ""];

        }

        return $response;
    }

    private static function primerDiaSemana()
    {
        $now = new \DateTime(); //
        $diff = ($now->format("w") > 0) ? $now->format("w") : 7;
        $diff--;
        $now->sub(new \DateInterval(sprintf("P%dD", $diff)));

        return $now;
    }

    private static function getNumeroAdoradores(array $entities): array
    {
        $diaHoraNum = [];
        foreach ($entities as $entity) {
            $key = $entity->getDia() . "_" . $entity->getHora();
            $diaHoraNum[$key] = $entity->getNumero();
        }

        return $diaHoraNum;
    }

    public static function getAdoradoresDias($em, $post, $bWeb = false)
    {
        $ret = [];
        $id = $post['id'];
        $className = ucfirst($post['tipo']);
        $start = new \DateTime($post['start']);
        $end = new \DateTime($post['end']);
        if ($id > 0) {
            $entities = $em->getRepository("App\\Entity\\" . $className)->findById($id);
        } else {
            $entities = $em->getRepository("App\\Entity\\" . $className)->findAll();
        }

        $bSeguir = true;
        $arrW = []; /// fecha del calendario por día de la semana
        while ($bSeguir) {
            $arrW[$start->format("w")] = clone $start;
            $start->add(new \DateInterval('P1D'));
            $bSeguir = (!array_key_exists($start->format("w"), $arrW));
        }
        $arrDay = [];
        $arrUser = [];
        foreach ($entities as $entity) {
            if ($entity->getBaja()) {
                continue;
            }
            foreach ($entity->getDiasemanahoras() as $dia) {
                $start = clone $arrW[$dia->getHora()->format("w")];
                if ($id == 0) {
                    $idx = $start->format('Y-m-d') . $dia->getHora()->format('H');

                    if (!array_key_exists($idx, $arrDay)) {
                        $arrDay[$idx] = 1;
                        $arrUser[$idx] = [$entity->__toString()];
                    } else {
                        $arrDay[$idx]++;
                        $arrUser[$idx][] = $entity->__toString();
                    }

                    $title = ($bWeb) ? '' : $arrDay[$idx];
                    $color = ($arrDay[$idx] > 1) ? Adorador::color1 : Adorador::color0;
                } else {
                    $title = '';
                    $idx = count($ret);
                    $color = $entity->getColor();
                }
                $data = ['id' => $dia->getId(), 'start' => sprintf('%sT%s', $start->format('Y-m-d'), $dia->getHora()->format('H:i:s')), 'title' => $title, 'allDay' => false, 'backgroundColor' => $color];

                if (!is_null($dia->getFin())) {
                    if (intval($dia->getFin()->format('H')) > 0) {
                        $data['end'] = sprintf('%sT%s', $start->format('Y-m-d'), $dia->getFin()->format('H:i:s'));
                    } else {
                        $f = clone $start;
                        $f->add(new \DateInterval('P1D'));
                        $data['end'] = sprintf('%sT%s', $f->format('Y-m-d'), $dia->getFin()->format('H:i:s'));
                    }
                }
                if (array_key_exists($idx, $arrDay)) {
                    $data["count"] = $arrDay[$idx];
                    if ($bWeb) {
                        $data["users"] = implode(";", $arrUser[$idx]);
                    }
                } else {
                    $data["count"] = 0;
                    if ($bWeb) {
                        $data["users"] = "";
                    }
                }
                $ret[$idx] = $data;
            }
        }
        ksort($ret);
        return $ret;
    }

    public static function getSacerdotesDias($em, $post, $bPrint = false)
    {
        $ret = [];
        $id = $post['id'];
        $className = ucfirst($post['tipo']);
        $start = new \DateTime($post['start']);
        $end = new \DateTime($post['end']);

        $qb = $em->createQueryBuilder('d')
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
        } else {
            $qb->andWhere('s.activo = 1');
        }

        $qb->orderBy('d.hora');
        $rst = $qb->getQuery()->getResult();
        foreach ($rst as $dia) {
            $hora = $dia['hora'];
            $fin = $dia['fin'];
            if ($bPrint) {
                $idx = $hora->format('w') * 100 + $hora->format('H');
            } else {
                $idx = $hora->format('Y-m-dH:i');
            }

            $color = Sacerdote::color;
            $title = $dia['nombre'];
            $iddia = $dia['id'];
            $data = ['id' => $iddia, 'start' => sprintf('%sT%s', $hora->format('Y-m-d'), $hora->format('H:i:s')), 'title' => $title, 'allDay' => false, 'backgroundColor' => $color];

            if (!is_null($fin)) {
                $data['end'] = sprintf('%sT%s', $hora->format('Y-m-d'), $fin->format('H:i:s'));
            }
            $ret[$idx] = $data;
        }
        return $ret;
    }

}
