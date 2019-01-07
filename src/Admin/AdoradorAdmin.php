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
}