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
		$this->set_title('Model Isu')
		->set_icon('fa fa-comment-o')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->column_order('kode, nama_isu')
		->field_order('kode, nama_isu')
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
				'kode'								=> 'required|numeric',
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
		->order_by('kode', 'ASC')
		->render('ta__model_isu');
	}
}