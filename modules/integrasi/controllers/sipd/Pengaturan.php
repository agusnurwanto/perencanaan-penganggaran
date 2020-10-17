<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Integrasi > SIPD > Pengaturan
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Pengaturan extends Aksara
{
	private $_table									= 'ref__pengaturan_sipd';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_method('update');
		$this->set_theme('backend');
		$this->insert_on_update_fail();
	}
	
	public function index()
	{
		$this->set_title('Pengaturan SIPD')
		->set_icon('mdi mdi-cog')
		->set_field
		(
			array
			(
				'username'							=> 'encryption',
				'password'							=> 'encryption'
			)
		)
		->set_validation
		(
			array
			(
				'hostname'							=> 'required|valid_url',
				'logout_url'						=> 'required|valid_url',
				'username'							=> 'required',
				'password'							=> 'required'
			)
		)
		->where
		(
			array
			(
				'id'								=> 1
			)
		)
		->set_default
		(
			array
			(
				'id'								=> 1
			)
		)
		->render($this->_table);
	}
}
