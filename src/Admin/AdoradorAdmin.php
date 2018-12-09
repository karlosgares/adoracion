<?php
namespace App\Admin;


class AdoradorAdmin extends UsuarioAdmin
{
	
	protected $baseRouteName = 'adorador';
    protected $baseRoutePattern = 'adorador';

	public function getTipo() { return 1;}
}