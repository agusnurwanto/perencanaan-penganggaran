<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Bidang_bappeda extends Aksara
{
	private $_table									= 'ref__bidang_bappeda';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Bidang Bappeda')
		->set_field('kode', 'last_insert')
		->unset_column('id, nip_kepala')
		->unset_field('id')
		->order_by('kode')
		->set_field
		(
			array
			(
				'keterangan'					=> 'textarea'
			)
		)
		->add_class
		(
			array
			(
				'nama_bidang'					=> 'autofocus'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required',
				'nama_bidang'						=> 'required',
				'jabatan_kepala'					=> 'required',
				'nama_kepala'						=> 'required',
				'nip_kepala'						=> 'required'
			)
		)
		->field_position
		(
			array
			(
				'jabatan_kepala'					=> 2,
				'nama_kepala'						=> 2,
				'nip_kepala'						=> 2
			)
		)
		->render($this->_table);
	}
}