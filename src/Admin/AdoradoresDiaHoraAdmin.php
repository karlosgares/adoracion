<?php

namespace App\Admin;

use App\Entity\AdoradoresDiaHora;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;

final class AdoradoresDiaHoraAdmin extends AbstractAdmin
{

    public function toString($entity) {
        if ($entity->getId() > 0) {
            return sprintf("%s %s:00", $entity->getDiaText(), $entity->getHoraText());
        }


        return "Nueva";
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper
            ->add('dia', null, [], ChoiceType::class, ['choices' => array_flip(AdoradoresDiaHora::$diasSemana)])
            ->add('hora', null, [], ChoiceType::class, ['choices' => range(0, 23)])
            ->add('numero')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('diaText', null, ["label" => "Día"])
            ->add('horaText', null, ["label" => "Hora"])
            ->add('numero')
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
        $formMapper
            ->add('dia', ChoiceType::class, ['choices' => array_flip(AdoradoresDiaHora::$diasSemana)])
            ->add('hora', ChoiceType::class, ['choices' => range(0, 23)])
            ->add('numero')
        ;
    }

    protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper
            ->add('id')
            ->add('dia')
            ->add('hora')
            ->add('numero')
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }
}
