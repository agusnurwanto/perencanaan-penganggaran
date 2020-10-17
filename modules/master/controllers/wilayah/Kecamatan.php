<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kecamatan extends Aksara
{
	private $_table									= 'ref__kecamatan';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Data Kecamatan')
		->set_field('kecamatan', 'hyperlink', 'master/wilayah/kelurahan', array('id_kec' => 'id'))
		->set_field('kode', 'last_insert')
		->unset_column('id')
		->unset_field('id')
		->add_class
		(
			array
			(
				'kecamatan'							=> 'autofocus'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required',
				'kecamatan'							=> 'required'
			)
		)
		->field_position
		(
			array
			(
				'camat'								=> 2,
				'nip'								=> 2,
				'jabatan'							=> 2
			)
		)
		->order_by('kode')
		->render($this->_table);
	}
}