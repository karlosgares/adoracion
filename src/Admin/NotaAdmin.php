<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Sonata\AdminBundle\Route\RouteCollection;

abstract class NotaAdmin extends AbstractAdmin
{
    public function toString($entity) {
        return "Acción de gracias";
    }

    // in your ProductAdmin class
    public function configure()
    {
        parent::configure();
        $this->classnameLabel = "Accion de gracias";
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
			->add('fecha')
            ->add('valida')
			;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('fecha', null, ['format'=> 'd-m-Y'])
            ->add('texto')
            ->add('valida')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        if ($this->getSubject()->getId() > 0){
            
        }
        else {
            $now = new \DateTime('NOW');
            $now->add(new \DateInterval('P1M'));
            $this->getSubject()->setFecha(new \DateTime($now->format('Y-m-1')));
            $this->getSubject()->setValida(true);
        }

        $formMapper
            ->add('fecha')
            ->add('tipo', HiddenType::class, ['data'=> $this->getTipo()])
            ->add('texto')
            ->add('valida')
            ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('fecha')
            ->add('tipo')
            ->add('texto')
            ;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere('o.tipo=:tipo')->setParameter('tipo',$this->getTipo());
        return $query;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }
}
