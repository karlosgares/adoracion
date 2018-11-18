<?php
namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Symfony\Component\Form\Extension\Core\Type\TextType;
use Symfony\Component\Form\Extension\Core\Type\EmailType;
use Sonata\AdminBundle\Form\Type\ModelAutocompleteType;

class UsuarioAdmin extends AbstractAdmin
{   

    public function toString($entity) {
        return $entity->__toString();
    }
    
    protected function configureFormFields(FormMapper $formMapper)
    {
        $formMapper->add('nombre', TextType::class)
                    ->add('apellidos', TextType::class, ['required'=>false])
                    ->add('email', EmailType::class)
                    ->add('telefono', TextType::class)
                    ->add('diasemanahoras', ModelAutocompleteType::class, ['label'=> 'Hora','property' => 'diasemana', 'required' => false, 'multiple' => true, 'minimum_input_length' => 2,
                         'callback' => function ($admin, $property, $value) {
                            $datagrid = $admin->getDatagrid();
                            $queryBuilder = $datagrid->getQuery();
                            $queryBuilder
                                ->andWhere($queryBuilder->getRootAlias() . '.diasemana like :barValue')
                                ->setParameter('barValue', '%' . $value . '%')
                            ;
                            if (intval($value) > 0 || $value == "00" || $value == "0"){
                                $hora = new \DateTime("NOW");
                                $hora->setTime($value, 0, 0);
                                $queryBuilder
                                    ->orWhere($queryBuilder->getRootAlias() . '.hora=:hora')
                                    ->setParameter('hora', $hora->format('H:00:00'))
                            ;
                            }
                            $datagrid->setValue($property, null, $value);
                        },
                ])
        ;
    }

    protected function configureDatagridFilters(DatagridMapper $datagridMapper)
    {
        $datagridMapper->add('nombre')
                    ->add('apellidos')
                    ->add('email')
                    ->add('telefono')
        ;
    }

    protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->addIdentifier('nombre')
                    ->add('apellidos', TextType::class)
                    ->add('email', EmailType::class)
                    ->add('telefono', TextType::class)
        ;
    }
}