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
        $collection->add('print');
    }

    public function getClassName() { return "sacerdote"; }
    public function getMinTime() { return '07:00:00'; }
    public function getMaxTime() { return '23:00:00'; }
    public function getHeaderRight() { return 'prev,next'; }

    public function configureActionButtons($action, $object = null) {
        $list = parent::configureActionButtons($action, $object);
        $list['print'] = [
                // NEXT_MAJOR: Remove this line and use commented line below it instead
                'template' => 'crud/button_print.html.twig',
                // 'template' => $this->getTemplateRegistry()->getTemplate('button_list'),
        ];

        return $list;
    }

    public function getClassPrint() {
        return "sacerdote";
    }
}