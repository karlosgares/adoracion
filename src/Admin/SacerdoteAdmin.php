<?php
namespace App\Admin;


class SacerdoteAdmin extends UsuarioAdmin
{
	protected $baseRouteName = 'sacerdote';
    protected $baseRoutePattern = 'sacerdote';
	
	public function getTipo() { return 0;}

	public function getMinTime() { return '07:00:00'; }
    public function getMaxTime() { return '23:00:00'; }
    public function getHeaderRight() { return 'prev,next'; }
}