<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rekening extends Aksara
{
	private $_table									= 'ta__model_belanja_sub';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('belanja');
		$this->_title								= phrase('belanja_sub') . ' ' . $this->model->select('ref__rek_6.uraian')->join('ref__rek_6', 'ref__rek_6.id = ta__model_belanja.id_rek_6')->get_where('ta__model_belanja', array('ta__model_belanja.id' => $this->_primary), 1)->row('uraian');
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'model'								=> 'Model',
				'belanja'							=> 'Rekening'
			)
		)
		->set_title($this->_title)
		->set_icon('mdi mdi-microwave')
		->set_primary('id')
		->unset_column('id, id_belanja')
		->unset_field('id, id_belanja')
		->unset_view('id')
		->unset_action('export, print, pdf')
		->merge_field('kd_belanja_sub, uraian')
		->field_size
		(
			array
			(
				'kd_belanja_sub'					=> 'col-3'
			)
		)
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus'
			)
		)
		->set_alias
		(
			array
			(
				'kd_belanja_sub'					=> 'Kode Belanja'
			)
		)
		->set_default
		(
			array
			(
				'id_belanja'						=> $this->_primary
			)
		)
		->where('id_belanja', $this->_primary)
		->set_field('kd_belanja_sub', 'last_insert')
		->set_field('uraian', 'textarea, hyperlink', 'model/belanja_sub', array('belanja_sub' => 'id'))
		->order_by('kd_belanja_sub')
		->render($this->_table);
	}
}