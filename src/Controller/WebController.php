<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\DiasemanaHora;

class WebController extends AbstractController
{
    
	/**
     * @Route("/web", name="web_index")
     */
    public function indexAction(Request $request)
    {
        return $this->render('Web/index.html.twig',[]);
    }
}