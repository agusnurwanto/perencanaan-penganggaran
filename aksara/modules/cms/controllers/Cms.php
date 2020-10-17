<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * CMS
 * Landing page for Content Management Module.
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Cms extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
		
		$this->unset_action('create, read, update, delete, export, print, pdf');
	}
	
	public function index()
	{
		$this->set_title(phrase('content_management_system'))
		->set_icon('mdi mdi-briefcase-outline')
		->render();
	}
}