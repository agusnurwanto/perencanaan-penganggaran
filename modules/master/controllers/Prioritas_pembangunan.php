<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Prioritas_pembangunan extends Aksara
{
	private $_table									= 'ref__prioritas_pembangunan';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Prioritas Pembangunan')
		->unset_column('id')
		->unset_field('id')
		->unset_truncate('uraian')
		->set_field('uraian', 'hyperlink', 'master/sasaran_daerah', array('id_prioritas' => 'id'))
		->set_field
		(
			array
			(
				'kode'								=> 'last_insert',
				'keterangan'						=> 'textarea'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required',
				'uraian'							=> 'required'
			)
		)
		->add_class('uraian', 'autofocus')
		->field_position
		(
			array
			(
				'keterangan'						=> 2
			)
		)
		->render($this->_table);
	}
}