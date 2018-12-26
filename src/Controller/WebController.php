<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\DiasemanaHora;
use App\Entity\Nota;

class WebController extends AbstractController
{
    
    /**
     * @Route("/web/savenota", name="web_save_nota")
     */
    public function saveNotaAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $post = $request->request->all();
        $now = new \DateTime('NOW');
        $now->add(new \DateInterval('P1M'));
        $nota = new Nota();
        $nota->setFecha(new \DateTime($now->format('Y-m-1')));
        $nota->setTipo($post['tiponota']);
        $nota->setTexto($post['textonota']);
        $nota->setValida(false);
        $em->persist($nota);
        $em->flush();
        $return['ok'] = true;
        return new JsonResponse($return, 200);
    }




	/**
     * @Route("/web", name="web_index")
     */
    public function indexAction(Request $request)
    {
        setlocale(LC_ALL, "es_ES", 'Spanish_Spain', 'Spanish');
        $now = new \DateTime('NOW');
        $data['mes'] = strtoupper($now->format('F'));
        $data['obispo'] =  $this->getQueryNotas([1])->getQuery()->getResult();
        /// noticias
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n')
                   ->from('App:Noticia', 'n')
                   ->where('n.activo=1')
                   ->andwhere("n.fechaalta <=:fecha")
                   ->andwhere("n.fechabaja >= :fecha or n.fechabaja is null")->setParameter('fecha',$now->format('Y-m-d'))
                   ->orderBy('n.fechaalta','desc')
        ;
        $data['noticias'] = $qb->getQuery()->getResult();

        return $this->render('Web/index.html.twig',$data);
    }

    /**
     * @Route("/web/calendar", name="web_load_calendar")
     */
    public function calendarAction(Request $request)
    {
        $ret = [];
        $em = $this->getDoctrine()->getEntityManager();
        $post = $request->request->all();
        $className = ucfirst($post['tipo']);
        $entities = $em->getRepository("App\\Entity\\" . $className)->findAll();

        $start = new \DateTime($post['start']);
        $end = new \DateTime($post['end']);
        $bSeguir = true;
        $arrW = [];
        $horas = [];
        while($bSeguir) {
            $arrW[$start->format("w")] = clone $start;
            $start->add( new \DateInterval('P1D'));
            $bSeguir = (!array_key_exists($start->format("w"), $arrW));
        }
        
        foreach ($entities as $entity) {
            foreach ($entity->getDiasemanahoras() as $dia) {
                $title = '';
                $horainicio = intval($dia->getHora()->format('H'));
                $horafin = intval($dia->getFin()->format('H'));
                
                for ($h=$horainicio; $h<$horafin;$h++) {
                    $index = sprintf('%sT%s', $dia->getHora()->format('Y-m-d'), "{$h}:00:00");

                    if (!array_key_exists($index, $arrW))
                            $horas[$index] = 1;
                    else    $horas[$index] += 1;
                    if ($post['tipo'] == 'adorador') {
                        $title = '';
                        $color = ($horas[$index] > 1)?"#88AA88":"gold";
                    }
                    else {
                        $title = $entity->getNombre();
                        $color = "blue";
                    }
                    
                    $start = clone $arrW[$dia->getHora()->format("w")]; 
                    $data = ['start'=> sprintf('%sT%s', $start->format('Y-m-d'),$dia->getHora()->format('H:i:s')), 'title' => $title, 'allDay' =>false, 'backgroundColor' => $color];

                    $data['end'] = sprintf('%sT%s', $start->format('Y-m-d'),str_pad($dia->getHora()->format("H") + 1, 2,'0',  STR_PAD_LEFT) . ":00:00");
                    $ret[$index] = $data;
                }
            } 
        }
        return new JsonResponse(array_values($ret), 200);
    }

    /**
     * @Route("/web/notas", name="web_load_notas")
     */
    public function notasAction(Request $request)
    {
        $ret = [];
        $qb = $this->getQueryNotas([2,3]);

        $ret[2] = $ret[3] = [];
        foreach ($qb->getQuery()->getResult() as $nota) {
            $ret[$nota->getTipo()][] = $nota->getTexto();
        }

        foreach ($ret as $tipo => $lista) {
            $return[$tipo] = "<li>" .implode("</li>\n<li>", $lista) . "</li>";
        }
        return new JsonResponse($return, 200);
    }

    /**
     * @Route("/web/noticias", name="web_load_noticias")
     */
    public function noticiasAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $now = new \DateTime('NOW');
        $qb = $em->createQueryBuilder();
        $qb->select('n')
                   ->from('App:Noticia', 'n')
                   ->where('n.activo=1')
                   ->andwhere("n.fechaalta <=:fecha")
                   ->andwhere("n.fechabaja >= :fecha or n.fechabaja is null")->setParameter('fecha',$now->format('Y-m-d'))
                   ->orderBy('n.fechaalta','desc')
        ;
        $noticias = "";
        foreach ($qb->getQuery()->getResult() as $nota) {
            $noticias .= '<li class="list-group-item"><a href="web/noticia/'. $nota->getId() .'" class="list-noticia">'.$nota->getTitulo().'</a></li>';
        }
        $ret['noticias'] = $noticias;
        return new JsonResponse($ret, 200);
    }

     /**
     * @Route("/web/noticia/{id}", name="web_load_noticia")
     */
    public function noticiaAction($id)
    {
        $className = "Noticia";
        $em = $this->getDoctrine()->getEntityManager();
        $entity = $em->getRepository("App\\Entity\\" . $className)->findOneById($id);
        
        if ($entity) {
            $data['object'] = $entity;
        }
        else {
            $data['msgError'] = "No existe la noticia";
        }
        return $this->render('Web/noticia.html.twig',$data);
    }

    public function getQueryNotas($arr) {
        $now = new \DateTime('NOW');
        $fecha = \DateTime::createFromFormat("Y-m-d H", $now->format('Y') ."-". $now->format('m')."-1 00");
        $fin = clone $fecha;
        $fin->add( new \DateInterval('P1M'));
        $fin->sub( new \DateInterval('P1D'));
        $em = $this->getDoctrine()->getEntityManager();
        $qb = $em->createQueryBuilder();
        $qb->select('n')
                   ->from('App:Nota', 'n')
                   ->where('n.valida=1 and n.tipo in ('.implode(",", $arr).')')
                   ->andwhere("n.fecha BETWEEN :fecha AND :fin")->setParameter('fecha',$fecha)->setParameter('fin',$fin)
        ;

        return $qb;
    }
}