<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Isu extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_title('Master Isu')
		->set_icon('fa fa-comment-o')
		->unset_column('id, kode_ref__bidang_bappeda')
		->unset_field('id')
		->unset_view('id')
		->column_order('kode, nama_isu, nama_bidang, koefisien')
		->field_order('kode, nama_isu, id_bidang_bappeda, koefisien')
		->set_field
		(
			array
			(
				'kode'								=> 'last_insert',
				'nama_isu'							=> 'textarea'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required|is_unique[ref__musrenbang_isu.kode.id.' . $this->input->get('id') . ']',
				'nama_isu'							=> 'required'
			)
		)
		->add_class
		(
			array
			(
				'nama_isu'							=> 'autofocus'
			)
		)
		->set_alias
		(
			array
			(
				'id_bidang_bappeda'					=> 'Bidang Bappeda'
			)
		)
		->set_relation
		(
			'id_bidang_bappeda',
			'ref__bidang_bappeda.id',
			'{ref__bidang_bappeda.kode}. {ref__bidang_bappeda.nama_bidang}'
		)
		->order_by('kode', 'ASC')
		->render('ref__renja_isu');
	}
}