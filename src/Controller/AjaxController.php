<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\DiasemanaHora;

class AjaxController extends AbstractController
{
    
    /**
     * @Route("/ajax/deletediasemanahora/{id}", name="app_ajax_deletediasemanahoras")
     */
    public function deletediasemanahoraAction($id) {
        if ($id > 0) {
            $em = $this->getDoctrine()->getEntityManager();
            $className = "DiasemanaHora";
            $obj =  $em->getRepository("App\\Entity\\" . $className)->findOneById($id);
            $em->remove($obj);
            $em->flush();
            return new JsonResponse(true, 200);
        }
       return new JsonResponse(false, 400);

    }

	/**
     * @Route("/ajax/getdiasemanahoras", name="app_ajax_getdiasemanahoras")
     */
    public function getdiasemanahorasAction(Request $request)
    {
    	$ret = [];
    	$em = $this->getDoctrine()->getEntityManager();
    	$post = $request->request->all();
    	$id = $post['id'];
        $className = ucfirst($post['tipo']);
    	$start = new \DateTime($post['start']);
		$end = new \DateTime($post['end']);
		$bSeguir = true;
		$arrW = [];
		while($bSeguir) {
			$arrW[$start->format("w")] = clone $start;
			$start->add( new \DateInterval('P1D'));
			$bSeguir = (!array_key_exists($start->format("w"), $arrW));
		}
    	
        if ($id > 0)
            $entities = $em->getRepository("App\\Entity\\" . $className)->findById($id);
        else $entities = $em->getRepository("App\\Entity\\" . $className)->findAll();
        
        $arrDay = [];
        foreach ($entities as $entity)
        foreach ($entity->getDiasemanahoras() as $dia) {
            if ($id == 0) {

                if ($post['tipo'] == "adorador") {
                    $idx = $start->format('Y-m-d') . $dia->getHora()->format('H:i');
                    if (!array_key_exists($idx, $arrDay))
                            $arrDay[$idx] = 1;
                    else    $arrDay[$idx]++;
                    $title = $arrDay[$idx];
                    $color = ($arrDay[$idx] > 1)?'green':'gold';
                }
                else {   
                    $color = "blue";
                    $title = $entity->getNombre();
                    $idx = count($ret);
                }
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
 
         return new JsonResponse(array_values($ret), 200);

    }

    /**
     * @Route("/ajax/savediasemanahora", name="app_ajax_savediasemanahora")
     */
    public function savediasemanahoraAction(Request $request)
    {
        $status = 200;
        $ret = [];
        $post = $request->request->all();
        $ret['start'] = $post['start'];
        $ret['end'] = $post['end'];
        $em = $this->getDoctrine()->getEntityManager();
        $id = $post['id'];
        $className = ucfirst($post['className']);
        
       	$entity = $em->getRepository("App\\Entity\\" . $className)->findOneById($id);
       	if ($entity){
	        $tipohora = $post['tipohora'];
	        $date = new \DateTime($post['start'][0] . "-" . ($post['start'][1] +1). "-" . $post['start'][2]  . " " .  $post['start'][3] . ":" . $post['start'][4] . ":" .  $post['start'][5]);
	        $end = new \DateTime($post['end'][0] . "-" . ($post['end'][1] +1). "-" . $post['end'][2]  . " " .  $post['end'][3] . ":" . $post['end'][4] . ":" .  $post['end'][5]);
			$d = new DiasemanaHora();
			$d->setHora($date);
			$d->setFin($end);
			$d->setTipo($tipohora);
			$d->setDayofweek($date->format('w'));
			$em->persist($d);
			$entity->addDiasemanahora($d);
			$em->flush();
			$ret['id'] = $d->getId();
			$ret['title'] = '';
		}
		else {
			$status = 400;
		}
        return new JsonResponse($ret, $status);
    }
}

