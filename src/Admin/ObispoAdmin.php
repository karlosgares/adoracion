<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

final class ObispoAdmin extends NotaAdmin
{
    protected $baseRouteName = 'obispo';
    protected $baseRoutePattern = 'obispo';

    public function toSring($entity) {
        return "Petición del obispo";
    }

    // in your ProductAdmin class
    public function configure()
    {
        parent::configure();
        $this->classnameLabel = "Peticiones del obispo";
    }

    public function getTipo() {
        return 1;
    }
}
