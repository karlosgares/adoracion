<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\CoreBundle\Form\Type\DatePickerType;

abstract class NotaAdmin extends AbstractAdmin
{

    protected $datagridValues = [

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'id',
    ];

    public function toString($entity)
    {
        return "Nota";
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('fecha', 'doctrine_orm_date_range', ['label' => 'Fecha'], [], DatePickerType::class)
            ->add('valida');
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('fecha', null, ['format' => 'd-m-Y'])
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

        if (!($this->getSubject()->getId() > 0)) {
            $this->getSubject()->setFecha(new \DateTime('NOW'));
            $this->getSubject()->setValida(true);
        }

        $formMapper
            ->add('fecha', DatePickerType::class, [
                'dp_side_by_side'       => true,
                'dp_use_current'        => false,
                'dp_collapse'           => true,
                'dp_calendar_weeks'     => false,
                'dp_view_mode'          => 'days',
                'dp_min_view_mode'      => 'days',
                'format' => 'dd-MM-yyyy',
            ])
            ->add('tipo', HiddenType::class, ['data' => $this->getTipo()])
            ->add('texto')
            ->add('valida');
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('fecha')
            ->add('tipo')
            ->add('texto');
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $query->andWhere('o.tipo=:tipo')->setParameter('tipo', $this->getTipo());
        return $query;
    }

    public function configureActionButtons($action, $object = null)
    {
        $list = parent::configureActionButtons($action, $object);
        if ($action == "list") {
            $list['print'] = [
                // NEXT_MAJOR: Remove this line and use commented line below it instead
                'template' => 'crud/button_print.html.twig',
                // 'template' => $this->getTemplateRegistry()->getTemplate('button_list'),
            ];
        }

        return $list;
    }

    public function getClassPrint()
    {
        return "nota";
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
        $collection->add('print', 'print');
    }
}
