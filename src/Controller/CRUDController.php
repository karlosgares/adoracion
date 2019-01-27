<?php
namespace App\Controller;

use Sonata\AdminBundle\Controller\CRUDController as Controller;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\DiasemanaHora;
use App\Entity\Nota;
use App\Entity\Adorador;
use App\Manager\CalendarioManager;


class CRUDController extends Controller
{
    
    /**
     * @Route("/print", name="print")
     */
    public function printAction(Request $request)
    {   
        $data['className'] = $this->admin->getClassPrint();
        
        $query = $this->admin->createQuery('list');
        $filters = $this->admin->getFilterParameters();
        //print_R($filters);
        foreach ($filters as $filter => $data) {
             if (!is_array($data)) //// order by, page 
                continue;

            if (is_array($data['value'])) {
                if (array_key_exists("start", $data['value']) && $data['value']['start']['day'] != "" && $data['value']['start']['month'] != "" && $data['value']['start']['year'] != "" ) {
                    $start = new \DateTime( $data['value']['start']['year'] . '-'.$data['value']['start']['month'] . '-' .$data['value']['start']['day']);
                }
                else {
                    $start = false;
                }

                if (array_key_exists("end", $data['value']) && $data['value']['end']['day'] != "" && $data['value']['end']['month'] != "" && $data['value']['end']['year'] != "" ) {
                    $end = new \DateTime( $data['value']['end']['year']. '-'.$data['value']['end']['month']. '-'.$data['value']['end']['day']);
                }
                else {
                    $end = false;
                }

                if ($start && $end) {
                    $query->andWhere('o.'. $filter . ' BETWEEN :start and :end')->setParameter('start', $start)->setParameter('end', $end);
                }
                else if ($start) {
                    $query->andWhere('o.'. $filter . '>=:start')->setParameter('start', $start);
                }
                else if($end) {
                    $query->andWhere('o.'. $filter . '<=:end')->setParameter('end', $end);
                }

            }
            else {
                if ($data["value"] != '')
                $query->andWhere('o.'. $filter . "=:{$filter}")->setParameter($filter, $data["value"]);
            }
        }

        //print $query->getQuery()->getSql();

        $data['results'] =  $query->getQuery()->getResult();
        $data['className'] = $this->admin->getClassPrint();

        return $this->render('crud/print.html.twig',$data);
    }
}