<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tahun extends Aksara
{
	private $_table									= 'ref__tahun';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master'							=> phrase('master')
			)
		)
		->set_title(phrase('master_tahun'))
		->set_icon('fa fa-calendar')
		->set_field
		(
			array
			(
				'aktif'								=> 'boolean',
				'default'							=> 'boolean'
			)
		)
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->render($this->_table);
	}
}