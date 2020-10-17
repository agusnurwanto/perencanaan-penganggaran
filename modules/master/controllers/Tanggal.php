<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tanggal extends Aksara
{
	private $_table									= 'ref__tanggal';
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
		->set_title(phrase('settings'))
		->set_icon('fa fa-toggle-on')
		->unset_column('tahun')
		->unset_field('tahun')
		->unset_view('tahun')
		->set_alias
		(
				array
				(
					'tanggal_rka'						=> 'Tanggal RKA',
					'tanggal_rka_perubahan'				=> 'Tanggal RKA Perubahan'
				)
		)		
		->set_field
		(
				
			array
			(
				'tanggal_rka'						=> 'datepicker',
				'tanggal_rka_perubahan'				=> 'datepicker',
				'tanggal_anggaran_kas'				=> 'datepicker'
			)
		)
		->field_position
		(
			array
			(
				'tanggal_rka_perubahan'				=> 2,
				'tanggal_anggaran_kas'				=> 3
			)
		)
		->set_default('tahun', get_userdata('year'))
		->where('tahun', get_userdata('year'))
		->render($this->_table);
	}
}