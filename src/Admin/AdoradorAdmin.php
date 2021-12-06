<?php
namespace App\Admin;
use Sonata\AdminBundle\Datagrid\ListMapper;

class AdoradorAdmin extends UsuarioAdmin
{
	
	protected $baseRouteName = 'adorador';
    protected $baseRoutePattern = 'adorador';

	
	protected function configureListFields(ListMapper $listMapper)
    {
        $listMapper->add('apellidos', TextType::class)
                    ->add('nombre')
                    ->add('email', EmailType::class)
                    ->add('telefono', TextType::class, ['label'=> 'Teléfono'])
                    ->add('movil', TextType::class, ['label'=> 'Móvil'])
                    ->add('baja')
                    ->add('_action', null, [
                        'actions' => [
                            'show' => [],
                            'edit' => [],
                            'delete' => [],
                        ],
                    ])
        ;
    }



	public function getTipo() { return 1;}
	public function getMinTime() { return '00:00:00'; }
    public function getMaxTime() { return '24:00:00'; }
    public function getHeaderRight() { return ''; }
}