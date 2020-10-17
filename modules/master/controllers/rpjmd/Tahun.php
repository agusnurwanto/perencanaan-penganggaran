<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tahun extends Aksara
{
	private $_table									= 'ref__rpjmd_tahun';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->insert_on_update_fail();
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
		->set_method('update')
		->set_primary('id_visi')
		->set_title(phrase('rpjmd_tahun'))
		->set_icon('fa fa-toggle-on')
		->unset_column('id, id_visi')
		->unset_field('id, id_visi')
		->unset_view('id, id_visi')
		->field_position
		(
			array
			(
				'tahun_1'							=> 1,
				'tahun_2'							=> 1,
				'tahun_3'							=> 2,
				'tahun_4'							=> 2,
				'tahun_5'							=> 3
			)
		)
		->set_default('id_visi', 1)
		->where('id_visi', 1)
		->render($this->_table);
	}
}