<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Symfony\Component\Form\Extension\Core\Type\ChoiceType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;
use App\Form\Type\CalendarType;
use Sonata\AdminBundle\Route\RouteCollection;
use App\Entity\Adorador;

class UsuarioAdmin extends AbstractAdmin
{   

    public function toString($entity) {
        return $entity->__toString();
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        
        if ($this->getSubject()->getId() > 0) {
                $formMapper->tab('Calendario')
                ->add('diasemanahoras', CalendarType::class, ['label' => false])
                ->end()->end()
            ;
        }

        $formMapper->tab('Datos básicos')
                    ->add('nombre', TextType::class)
                    ->add('apellidos', TextType::class, ['required'=>false])
                    ->add('email', EmailType::class, ['required'=>false])
                    ->add('telefono', TextType::class, ['required'=>false])
        ;    
        
        if ($this->getSubject() instanceOf \App\Entity\Adorador) {
            $formMapper->add('sustitucionfranja', ChoiceType::class, ['label'=> 'Franja horaria de sustituto', 'required'=>false, 'choices'=>array_flip(Adorador::getSustitucionfranjas()), 'placeholder'=> 'Elegir para sustitutos'])
                ->add('baja')

            ;

        }        

        $formMapper->end()->end()
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {   
        $datagridMapper->add('nombre')
                    ->add('apellidos')
                    ->add('email')
                    ->add('telefono')
        ;
        if ($this->getBaseRoutePattern() == 'adorador') {
            $horas = [];
            for ($i =0; $i< 24; $i++) {
                $h = sprintf('%s:00:00', str_pad($i,2,'0', STR_PAD_LEFT));
                $hm = sprintf('%s:00', str_pad($i,2,'0', STR_PAD_LEFT));
                $horas[$hm] = $h;
            }

            $datagridMapper->add('diasemanahoras.dayofweek',null,['label'=> 'Día de la semana'], ChoiceType::class, ['choices'=>array_flip(Adorador::getDayofweek())])
                ->add('diasemanahoras.hhmm',null,['label'=> 'Hora'], ChoiceType::class, ['choices'=>$horas])
                ->add('sustitucionfranja',null,['label'=> 'Franja horaria de sustituto'], ChoiceType::class, ['choices'=>array_flip(Adorador::getSustitucionfranjas())])
                ->add('baja')

            ;
        }
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('nombre')
                    ->add('apellidos', TextType::class)
                    ->add('email', EmailType::class)
                    ->add('telefono', TextType::class)
                    ->add('_action', null, [
                        'actions' => [
                            'show' => [],
                            'edit' => [],
                            'delete' => [],
                        ],
                    ])
        ;
    }

    public function getColor() {return $this->getSubject()->getColor(); }

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->remove('show');
    }
}