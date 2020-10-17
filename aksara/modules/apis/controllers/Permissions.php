<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * API's Permissions Management
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Permissions extends Aksara
{
	private $_table									= 'rest__permissions';
	
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
				'description'						=> 'textarea',
				'parameter'							=> 'attributes',
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
		->set_relation
		(
			'url_id',
			'rest__urls.id',
			'{rest__urls.title}',
			array
			(
				'rest__urls.status'					=> 1
			)
		)
		->set_relation
		(
			'client_id',
			'rest__clients.id',
			'{rest__clients.title}',
			array
			(
				'rest__clients.status'				=> 1
			)
		)
		->set_validation
		(
			array
			(
				'title'								=> 'required|xss_clean|is_unique[' . $this->_table . '.title.id.' . $this->_primary . ']',
				'description'						=> 'required|xss_clean',
				'status'							=> 'is_boolean'
			)
		)
		->render($this->_table);
	}
}