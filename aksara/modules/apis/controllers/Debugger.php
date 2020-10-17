<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Rest API Debugger
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Debugger extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		//$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('api_debugger'))
		->set_icon('mdi mdi-android-debug-bridge')
		->render();
	}
}