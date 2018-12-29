<?php
namespace App\Admin;


class AdoradorAdmin extends UsuarioAdmin
{
	
	protected $baseRouteName = 'adorador';
    protected $baseRoutePattern = 'adorador';

	public function getTipo() { return 1;}
	public function getMinTime() { return '00:00:00'; }
    public function getMaxTime() { return '23:00:00'; }
    public function getHeaderRight() { return ''; }



    public function createQuery($context = 'list')
	{

	    ///$fieldData = $this->getForm()->get('name_of_field')->getData();
	    $query = parent::createQuery($context);
	    
	    print $query->getQuery()->getSql();

	    /*

	    $query->andWhere(
	        $query->expr()->eq($query->getRootAliases()[0] . '.my_field', ':my_param')
	    );
	    $query->setParameter('my_param', 'my_value');
	    */
	    return $query;
	}

}