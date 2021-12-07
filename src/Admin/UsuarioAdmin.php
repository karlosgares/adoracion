<?php

namespace App\Admin;

use App\Entity\Adorador;
use App\Form\Type\CalendarType;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Route\RouteCollection;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class UsuarioAdmin extends AbstractAdmin
{

    protected $datagridValues = [

        // display the first page (default = 1)
        '_page' => 1,

        // reverse order (default = 'ASC')
        '_sort_order' => 'ASC',

        // name of the ordered field (default = the model's id field, if any)
        '_sort_by' => 'apellidos',
    ];

    public function toString($entity)
    {
        return $entity->__toString();
    }

    protected function configureFormFields(FormMapper $formMapper)
    {

        if ($this->getSubject()->getId() > 0) {
            $formMapper->tab('Calendario')
                ->add('diasemanahoras', CalendarType::class, ['label' => false])
                ->end()->end();
        }

        $formMapper->tab('Datos básicos')
            ->add('nombre', TextType::class)
            ->add('apellidos', TextType::class, ['required' => false])
            ->add('email', EmailType::class, ['required' => false])
            ->add('telefono', TextType::class, ['required' => false])
            ->add('activo')
        ;

        if ($this->getSubject() instanceof \App\Entity\Adorador) {

            $formMapper
                ->add('movil', TextType::class, ['required' => false, 'label' => 'Móvil'])
                ->add('responsable', TextType::class, ['required' => false])
                ->add('direccion', TextType::class, ['required' => false, 'label' => 'Dirección'])
                ->add('cp', TextType::class, ['required' => false, 'label' => 'CP'])
                ->add('poblacion', TextType::class, ['required' => false, 'label' => 'Población'])
                ->add('sustitucionfranja', ChoiceType::class, ['label' => 'Franja horaria de sustituto', 'required' => false, 'choices' => array_flip(Adorador::getSustitucionfranjas()), 'placeholder' => 'Elegir para sustitutos'])
                ->add('tipo', ChoiceType::class, ['label' => 'Tipo', 'required' => true, 'choices' => array_flip(Adorador::getTipos())])
                ->add('observaciones')

                //->add('baja')

            ;
        }

        $formMapper->end()->end();
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre')
            ->add('apellidos')
            ->add('email')
            ->add('telefono')
            ->add('activo');

        if ($this->getBaseRoutePattern() == 'adorador') {
            $horas = [];
            for ($i = 0; $i < 24; $i++) {
                $h = sprintf('%s:00:00', str_pad($i, 2, '0', STR_PAD_LEFT));
                $hm = sprintf('%s:00', str_pad($i, 2, '0', STR_PAD_LEFT));
                $horas[$hm] = $h;
            }

            $datagridMapper->add('diasemanahoras.dayofweek', null, ['label' => 'Día de la semana'], ChoiceType::class, ['choices' => array_flip(Adorador::getDayofweek())])
                ->add('diasemanahoras.hhmm', null, ['label' => 'Hora'], ChoiceType::class, ['choices' => $horas])
                ->add('sustitucionfranja', null, ['label' => 'Franja horaria de sustituto'], ChoiceType::class, ['choices' => array_flip(Adorador::getSustitucionfranjas())])
                ->add('tipo', null, ['label' => 'Tipo'], ChoiceType::class, ['choices' => array_flip(Adorador::getTipos())])
                ->add('baja');
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper
            ->add('apellidos', TextType::class)
            ->add('nombre')
            ->add('email', EmailType::class)
            ->add('telefono', TextType::class)
            ->add('activo')
            ->add('_action', null, [
                'actions' => [
                    'show' => [],
                    'edit' => [],
                    'delete' => [],
                ],
            ]);
    }

    public function getColor()
    {
        return $this->getSubject()->getColor();
    }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }
}
