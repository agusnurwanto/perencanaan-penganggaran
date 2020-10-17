<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Laporan
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Laporan extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission(array(1));
		$this->set_theme('backend');
		
		$this->unset_action('create, read, update, delete, export, print, pdf');
	}
	
	public function index()
	{
		$this->set_title('Laporan')
		->set_icon('mdi mdi-chart-areaspline')
		->render();
	}
}