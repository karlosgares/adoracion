<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;


final class PeticionAdmin extends NotaAdmin
{
	protected $baseRouteName = 'peticion';
    protected $baseRoutePattern = 'peticion';

    public function toSring($entity) {
        return "Petición particular";
    }


    // in your ProductAdmin class
    public function configure()
    {
        parent::configure();
        $this->classnameLabel = "Peticion particular";
    }

    public function getTipo() {
        return 2;
    }
}
