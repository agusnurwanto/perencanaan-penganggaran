<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Metode_pemilihan extends Aksara
{
	private $_table									= 'ref__rup_metode_pemilihan';
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
		->set_title('Metode Pemilihan')
		->set_icon('fa fa-audio-description')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->add_class
		(
			array
			(
				'metode_pemilihan'					=> 'autofocus'
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
				'metode_pemilihan'					=> 'required'
			)
		)
		->order_by('kode')
		->render($this->_table);
	}
}