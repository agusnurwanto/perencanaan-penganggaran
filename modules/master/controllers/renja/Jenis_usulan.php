<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Jenis_usulan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_title('Master Jenis Usulan')
		->set_icon('fa fa-comment-o')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->column_order('kode, nama_jenis_usulan, keterangan')
		->field_order('kode, nama_jenis_usulan, keterangan')
		->set_field
		(
			array
			(
				'kode'								=> 'last_insert',
				'jenis_usulan'						=> 'textarea',
				'keterangan'						=> 'textarea'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required|is_unique[ref__renja_jenis_usulan.kode.id.' . $this->input->get('id') . ']',
				'nama_jenis_usulan'					=> 'required'
			)
		)
		->add_class
		(
			array
			(
				'nama_jenis_usulan'					=> 'autofocus'
			)
		)
		->order_by('kode', 'ASC')
		->render('ref__renja_jenis_usulan');
	}
}