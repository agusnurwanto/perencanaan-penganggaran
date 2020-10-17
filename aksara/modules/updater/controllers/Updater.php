<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Updater
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Updater extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		//$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Updater')
		->set_icon('mdi mdi-sync')
		->render();
	}
}
