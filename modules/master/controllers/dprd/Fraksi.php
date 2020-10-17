<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Fraksi extends Aksara
{
	private $_table									= 'ref__dprd_fraksi';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Data Fraksi')
		->set_field('nama_fraksi', 'hyperlink', 'master/dprd/dprd', array('id_fraksi' => 'id'))
		->set_field('kode', 'last_insert')
		->add_class('nama_fraksi', 'autofocus')
		->unset_column('id')
		->unset_field('id')
		->set_validation
		(
			array
			(
				'kode'					=> 'required',
				'nama_fraksi'			=> 'required'
			)
		)
		->order_by('kode')
		->render($this->_table);
	}
}