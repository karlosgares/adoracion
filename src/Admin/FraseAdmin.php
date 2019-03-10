<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Sonata\AdminBundle\Route\RouteCollection;

final class FraseAdmin extends AbstractAdmin
{
    public function toString($entity) {
        return "Frase";
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
			->add('texto')
			->add('autor')
			;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
			->add('textoshort', null, ['label' => 'Texto'])
			->add('autor')
			->add('_action', null, [
                'actions' => [
                    'show' => ['template' => 'button/print.html.twig'],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper
			->add('texto')
			->add('autor')
            ->add('activa')
			;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
			->add('id')
			->add('texto')
			->add('autor')
			;
    }
/*
    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    } */
}
