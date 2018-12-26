<?php
namespace App\Admin;
use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Show\ShowMapper;
use App\Type\Form\CalendarType;
use Sonata\AdminBundle\Route\RouteCollection;

class CalendarioSacerdoteAdmin extends UsuarioAdmin
{
	protected $baseRoutePattern = 'calendario_confesion';
	protected $baseRouteName = 'calendario_confesion';

	/*protected function configureShowFields(ShowMapper $showMapper)
    {
        $showMapper->add('calendario', CalendarType::class,  ['label' => false])
        ;
    }
    */

    public function toString($entity){
        return "Calendario de confesiones";
    } 

    protected function configureRoutes(RouteCollection $collection)
    {
         $collection->clearExcept('list');
    }

    public function getClassName() { return "sacerdote"; }
    public function getMinTime() { return '07:00:00'; }
    public function getMaxTime() { return '21:00:00'; }
}