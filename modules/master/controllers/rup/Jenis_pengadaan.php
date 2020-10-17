<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Jenis_pengadaan extends Aksara
{
	private $_table									= 'ref__rup_jenis_pengadaan';
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
				'master'							=> 'master'
			)
		)
		->set_title('Jenis Pengadaan')
		->set_icon('mdi mdi-file-chart')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->add_class
		(
			array
			(
				'jenis_pengadaan'					=> 'autofocus'
			)
		)
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
				'jenis_pengadaan'					=> 'required'
			)
		)
		->order_by('kode')
		->render($this->_table);
	}
}