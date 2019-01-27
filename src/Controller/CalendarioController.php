<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Adorador;
use Sonata\AdminBundle\Controller\CRUDController;
use App\Manager\CalendarioManager;

class CalendarioController extends CRUDController
{
    public function listAction() {
	    
	    $vars["title"] = $this->admin->toString(null);
	    $vars["className"] = $this->admin->getClassName();
    	$vars["minTime"] = $this->admin->getMinTime();
    	$vars["maxTime"] = $this->admin->getMaxTime();
        $vars["headerRight"] = $this->admin->getHeaderRight();
        return $this->render('crud/list_calendar.html.twig',$vars);
    }


    public function getadmin() {
        return $this->admin;
    }

    public function printAction(Request $request)
    {   
        $data['className'] = $this->admin->getClassPrint();
        $query = $this->admin->createQuery('list');
        $post['tipo'] = "sacerdote";
        $post['start'] = $request->query->get('start');
        $post['end'] = $request->query->get('end');
        $post['id'] = 0;
        $em = $this->getDoctrine()->getEntityManager();
        $data['result'] = CalendarioManager::getSacerdotesDias($em, $post, true);

        $start = new \DateTime($request->query->get('start'));
        $end = new \DateTime($request->query->get('end'));

        $data["rangofechas"] = sprintf('Del %s al %s del %s', $start->format('d-m'), $end->format('d-m') , $end->format('Y'));

        return $this->render('crud/print.html.twig',$data);
    }
}

