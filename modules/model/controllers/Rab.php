<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rab extends Aksara
{
	private $_table									= 'ta__model_rab';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_model');
		$this->_title								= 'RAB - Model ' . $this->model->select('nm_model')->get_where('ta__model', array('id' => $this->_primary), 1)->row('nm_model');
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title($this->_title)
		->set_icon('fa fa-table')
		->unset_column('id, id_model')
		->unset_field('id, id_model')
		->unset_view('id, id_model')
		->set_field('keterangan', 'textarea')
		->set_alias
		(
			array
			(
				'kd_rab'					=> 'Kode RAB'
			)
		)
		->set_default('id_model', $this->_primary)
		->where('id_model', $this->_primary)
		->order_by('kd_rab')
		->render($this->_table);
	}
}