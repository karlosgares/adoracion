<?php

namespace App\Admin;

class SacerdoteAdmin extends UsuarioAdmin
{
    protected $baseRouteName = 'sacerdote';
    protected $baseRoutePattern = 'sacerdote';

    public function getTipo()
    {
        return 0;
    }

    public function createQuery($context = 'list')
    {
        $query = parent::createQuery($context);
        $parameters = $this->getFilterParameters();
        list($tableAlias) = $query->getQueryBuilder()->getRootAliases();
        if (!array_key_exists("activo", $parameters)) {
            $query->where($tableAlias . '.activo = :activo')->setParameter('activo', 1);
        }

        return $query;
    }

    public function getMinTime()
    {
        return '07:00:00';
    }
    public function getMaxTime()
    {
        return '23:00:00';
    }
    public function getHeaderRight()
    {
        return 'prev,next';
    }
}
