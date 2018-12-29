<?php
namespace App\Controller;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Adorador;
use Sonata\AdminBundle\Controller\CRUDController;

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
}

