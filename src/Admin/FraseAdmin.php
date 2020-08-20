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
protected $datagridValues = [

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'DESC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'id',
    ];

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
            ->add('activa')
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

    /**
     * Activo false a la anterior frase
     *
     * @param Frase $object
     * @return void
     */
    public function prePersist($object)
    {   
        if ($object->getActiva() == false) {
            return;
        }
        $container = $this->getConfigurationPool()->getContainer();
        $em = $container->get('doctrine')->getManager();
        $repo = $em->getRepository(get_class($object));
        $fraseActiva = $repo->findOneBy(['activa'=> 1]);
        if ($fraseActiva && $fraseActiva->getId() > 0){
            $fraseActiva->setActiva(false);
            $em->persist($fraseActiva);
            // $em->flush();
        }
    }
}
