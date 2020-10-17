<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Partials
 * Landing page for partials module
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Partials extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		//$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('partials'))
		->set_icon('mdi mdi-briefcase-outline')
		->render();
	}
}