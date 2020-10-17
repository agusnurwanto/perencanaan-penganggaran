<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sumber_dana extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_title('Master Sumber Dana')
		->set_icon('fa fa-comment-o')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->column_order('kode, nama_sumber_dana')
		->field_order('kode, nama_sumber_dana')
		->set_field
		(
			array
			(
				'kode'								=> 'last_insert',
				'nama_sumber_dana'					=> 'textarea'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required|is_unique[ref__musrenbang_isu.kode.id.' . $this->input->get('id') . ']',
				'nama_sumber_dana'					=> 'required'
			)
		)
		->add_class
		(
			array
			(
				'nama_sumber_dana'						=> 'autofocus'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required',
				'nama_sumber_dana'					=> 'required'
			)
		)
		->order_by('kode', 'ASC')
		->render('ref__sumber_dana');
	}
}