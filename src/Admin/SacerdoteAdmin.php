<?php
namespace App\Admin;

use Sonata\AdminBundle\Datagrid\ListMapper;


class SacerdoteAdmin extends UsuarioAdmin
{
	protected $baseRouteName = 'sacerdote';
    protected $baseRoutePattern = 'sacerdote';
	
	public function getTipo() { return 0;}

	public function getMinTime() { return '07:00:00'; }
    public function getMaxTime() { return '23:00:00'; }
    public function getHeaderRight() { return 'prev,next'; }

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
                    ])
        ;
    }
}