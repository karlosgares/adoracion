<?php
namespace App\Admin;


class SacerdoteAdmin extends UsuarioAdmin
{
	protected $baseRouteName = 'sacerdote';
    protected $baseRoutePattern = 'sacerdorte';
	
	public function getTipo() { return 0;}
}