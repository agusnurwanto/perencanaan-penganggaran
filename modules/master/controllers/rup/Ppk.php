<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Ppk extends Aksara
{
	private $_table									= 'ref__rup_ppk';
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
		->set_title('Pejabat Pembuat Komitmen')
		->set_icon('fa fa-beer')
		->unset_column('id, alamat, nip, nik, telp, pangkat, golongan')
		->unset_field('id')
		->unset_view('id')
		->set_alias
		(
			array
			(
				'id_golongan'						=> 'golongan'
			)
		)
		->set_field
		(
			array
			(
				'jabatan'							=> 'textarea',
				'alamat'							=> 'textarea'
			)
		)
		->set_relation
		(
			'id_golongan',
			'ref__pegawai_golongan.id',
			'{ref__pegawai_golongan.pangkat} - {ref__pegawai_golongan.golongan}',
			null,
			NULL,
			'ref__pegawai_golongan.golongan'
		)
		->set_validation
		(
			array
			(
				'nama'								=> 'required',
				'jabatan'							=> 'required',
				'alamat'							=> 'required',
				'nip'								=> 'required',
				'nik'								=> 'required',
				'id_golongan'						=> 'required',
				'telp'								=> 'required',
				'email'								=> 'required',
				'no_sk'								=> 'required'
			)
		)
		->field_position
		(
			array
			(
				'id_golongan'							=> 2,
				'telp'								=> 2,
				'email'								=> 2,
				'no_sk'								=> 2
			)
		)
		->order_by('nama')
		->render($this->_table);
	}
}