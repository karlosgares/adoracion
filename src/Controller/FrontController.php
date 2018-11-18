<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\DiasemanaHora;

class FrontController extends AbstractController
{
    /**
     * @Route("/api/install", name="app_api_install")
     */
    public function install()
    {
        $em = $this->getDoctrine()->getEntityManager();
        $number = 0;
        $dias = ['Lunes', 'Martes', 'Miércoles', 'Jueves', 'Viernes', 'Sábado', 'Domingo'];

        foreach ($dias as $i => $dia) {
        	for ($j=0; $j<24;$j++){
        		$date = new \DateTime('NOW');
        		$date->setTime($j, 0, 0);
        		$d = new DiasemanaHora();
        		$d->setHora($date);
        		$d->setDiasemana($dia);
        		$d->setDayofweek(($j==6)?0:$j+1);
        		$em->persist($d);
        		$em->flush();
        		$number++;
        	}

        }

        return new Response(
            '<html><body>Lucky number: '.$number.'</body></html>'
        );
    }
}

