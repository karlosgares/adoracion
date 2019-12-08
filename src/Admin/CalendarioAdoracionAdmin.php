<?php
namespace App\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use App\Type\Form\CalendarType;
use Sonata\AdminBundle\Route\RouteCollection;

class CalendarioAdoracionAdmin extends UsuarioAdmin
{
	protected $baseRoutePattern = 'calendario_adoracion';
	protected $baseRouteName = 'calendario_adoracion';

	/*protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('calendario', CalendarType::class,  ['label' => false])
        ;
    }
    */

    public function toString($entity){
        return "Calendario de adoradores";
    } 

    protected function configureRoutes(RouteCollection $collection)
    {
        $collection->clearExcept('list');
        $collection->add('print','print');
    }


    public function getTipo() { return 1;}
    public function getColor() { 
        return "blue";
    }

    public function getClassName() { return 'adorador';}
    public function getMinTime() { return '00:00:00'; }
    public function getMaxTime() { return '24:00:00'; }
    public function getHeaderRight() { return ''; }
}