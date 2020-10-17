<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Clients API's Management
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Clients extends Aksara
{
	private $_table									= 'rest__clients';
	
	public function __construct()
	{
		parent::__construct();
		
		//$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
		
		$this->_primary								= $this->input->get('id');
	}
	
	public function index()
	{
		$this->set_title(phrase('manage_clients'))
		->set_icon('mdi mdi-account-check-outline')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->set_field
		(
			array
			(
				'api_key'							=> '',
				'ip_range'							=> 'textarea',
				'valid_until'						=> 'datepicker',
				'status'							=> 'boolean'
			)
		)
		->set_field
		(
			'method',
			'checkbox',
			array
			(
				'GET'								=> 'GET',
				'POST'								=> 'POST',
				'PUT'								=> 'PUT',
				'DELETE'							=> 'DELETE'
			)
		)
		->set_validation
		(
			array
			(
				'title'								=> 'required|xss_clean|max_length[64]|is_unique[' . $this->_table . '.title.id.' . $this->_primary . ']',
				'description'						=> 'required|xss_clean',
				'api_key'							=> 'required|max_length[32]|is_unique[' . $this->_table . '.api_key.id.' . $this->_primary . ']',
				'status'							=> 'is_boolean'
			)
		)
		->merge_field('valid_until, status')
		->render($this->_table);
	}
}