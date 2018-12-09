<?php
namespace App\Admin;


class SacerdoteAdmin extends UsuarioAdmin
{
	protected $baseRouteName = 'sacerdote';
    protected $baseRoutePattern = 'sacerdote';
	
	public function getTipo() { return 0;}
}