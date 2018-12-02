<?php

namespace App\Admin;

use Sonata\AdminBundle\Admin\AbstractAdmin;
use Sonata\AdminBundle\Datagrid\DatagridMapper;
use Sonata\AdminBundle\Datagrid\ListMapper;
use Sonata\AdminBundle\Form\FormMapper;
use Sonata\AdminBundle\Show\ShowMapper;
use Symfony\Component\Form\Extension\Core\Type\HiddenType;

final class GraciasAdmin extends NotaAdmin
{
    protected $baseRouteName = 'gracias';
    protected $baseRoutePattern = 'gracias';

    public function toSring($entity) {
        return "Peticiones particulares";
    }

    public function getTipo() {
        return 3;
    }
}
