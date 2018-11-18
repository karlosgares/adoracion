<?php
namespace App\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use App\Type\Form\CalendarType;
use Sonata\AdminBundle\Route\RouteCollection;

class CalendarioAdoracionAdmin extends UsuarioAdmin
{
	protected $baseRoutePattern = 'foo';
	protected $baseRouteName = 'calendario_adoracion';

	protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('calendario', CalendarType::class,  ['label' => false])
        ;
    }

    protected function configureRoutes(RouteCollection $collection)
    {
         $collection->clearExcept('show');
    }
}