<?php
namespace App\Admin;


class SacerdoteAdmin extends UsuarioAdmin
{
	protected $baseRouteName = 'sacerdote';
    protected $baseRoutePattern = 'sacerdote';
	
	public function getTipo() { return 0;}

	public function getMinTime() { return '07:00:00'; }
    public function getMaxTime() { return '21:00:00'; }
}