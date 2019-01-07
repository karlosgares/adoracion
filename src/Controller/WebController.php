<?php
namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\JsonResponse;
use App\Entity\DiasemanaHora;
use App\Entity\Nota;
use App\Entity\Adorador;
use App\Manager\CalendarioManager;


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
        $data['color0'] = Adorador::color0;
        $data['color1'] = Adorador::color1;
        return $this->render('Web/index.html.twig',$data);
    }

    /**
     * @Route("/web/calendar", name="web_load_calendar")
     */
    public function calendarAction(Request $request)
    {
        $em = $this->getDoctrine()->getEntityManager();
        $post = $request->request->all();
        $post['id'] = 0;
        switch($post['tipo']) {
            case 'adorador':
                $ret = CalendarioManager::getAdoradoresDias($em, $post);
            break;
            case 'sacerdote':
                $ret = CalendarioManager::getSacerdotesDias($em, $post);
            break;
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

    /**
     * @Route("/ajax/getnoticia", name="app_web_getnoticia")
     */
    public function getnoticiaAction(Request $request)
    {
        $post = $request->request->all();
        $className = "Noticia";
        $id = $post['id'];
        $em = $this->getDoctrine()->getEntityManager();
        
        if ($id > 0)
                $entity = $em->getRepository("App\\Entity\\" . $className)->findOneById($id);
        else  $entity = $em->getRepository("App\\Entity\\" . $className)->findOneBy(['portada' => 1, 'activo' => 1], ['id' => 'DESC']);

        if ($entity) {
            switch ($entity->getPosicion()) {
                case 0:
                    $ret['html'] = '<h5>'.$entity->getTitulo().'</h5>';
                    $ret['html'] .= '<p>' . $entity->getContenido();
                    $ret['html'] .= '<img src="/noticias/'.$entity->getFoto().'" class="img-fluid" alt="" align="right"  style="padding: 5px">';
                    $ret['html'] .=  '</p>';
                    break;
                
                case 1:
                    $ret['html'] = '<h5>'.$entity->getTitulo().'</h5>';
                    $ret['html'] .= '<p>' . $entity->getContenido();
                    $ret['html'] .= '<img src="/noticias/'.$entity->getFoto().'" class="img-fluid" alt="" align="left"  style="padding: 5px">';
                    $ret['html'] .=  '</p>';
                    break;


                case 2:                                                                                                                 $ret['html'] = '<h5>'.$entity->getTitulo().'</h5>';
                    $ret['html'] .= '<p>' . $entity->getContenido();
                    $ret['html'] .= '<center><img src="/noticias/'.$entity->getFoto().'" class="img-fluid" alt=""  style="padding: 5px"></center>';
                    $ret['html'] .=  '</p>';                             

                    break;

                case 3:
                    $ret['html'] = '<h5>'.$entity->getTitulo().'</h5>';
                    $ret['html'] .= '<center><img src="/noticias/'.$entity->getFoto().'" class="img-fluid" alt=""  style="padding: 5px"></center>';
                    $ret['html'] .= '<p>' . $entity->getContenido();
                    $ret['html'] .=  '</p>'; 

                    break;
            }
        }
        else {
            $ret['html'] = '';
        }
        return new JsonResponse($ret, 200);
    }
}