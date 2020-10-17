<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Akun extends Aksara
{
	private $_table									= 'ref__rek_1';
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
				'master/rekening'					=> phrase('rekening')
			)
		)
		->set_title(phrase('rekening_akun'))
		->set_icon('fa fa-dollar')
		->set_relation
		(
			'id_jns_kas',
			'ref__jenis_kas.id',
			'{ref__jenis_kas.nama}'
		)
		->set_field('uraian', 'hyperlink', 'master/rekening/kelompok', array('id_ref_rek_1' => 'id'))
		->set_field('kd_rek_1', 'last_insert')
		->unset_column('id, id_ref_rek_1, tahun')
		->unset_field('id, id_ref_rek_1, tahun')
		->unset_view('id, id_ref_rek_1, tahun')
		->set_alias
		(
			array
			(
				'kd_rek_1'							=> 'Kode',
				'id_jns_kas'						=> 'Jenis Kas',
				'nama'								=> 'Jenis Kas'
			)
		)
		->set_validation
		(
			array
			(
				'kd_rek_1'							=> 'required',
				'uraian'							=> 'required',
				'id_jns_kas'						=> 'required'
			)
		)
		->where('tahun', get_userdata('year'))
		->set_default('tahun', get_userdata('year'))
		->order_by('kd_rek_1')
		->render($this->_table);
	}
}