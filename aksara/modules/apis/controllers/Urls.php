<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * URL Management
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Urls extends Aksara
{
	private $_table									= 'rest__urls';
	
	public function __construct()
	{
		parent::__construct();
		
		//$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
		
		$this->_primary								= $this->input->get('id');
	}
	
	public function index()
	{
		$this->set_title(phrase('manage_urls'))
		->set_icon('mdi mdi-link-variant')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->set_field
		(
			array
			(
				'description'						=> 'textarea',
				'status'							=> 'boolean'
			)
		)
		->set_validation
		(
			array
			(
				'url'								=> 'required|valid_url|is_unique[' . $this->_table . '.url.id.' . $this->_primary . ']',
				'title'								=> 'required|xss_clean|max_length[64]|is_unique[' . $this->_table . '.title.id.' . $this->_primary . ']',
				'description'						=> 'required|xss_clean',
				'status'							=> 'is_boolean'
			)
		)
		->render($this->_table);
	}
}