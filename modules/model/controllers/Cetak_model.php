<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Cetak_model extends Aksara
{
	private $_table									= 'ta__model';
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_model');
		$this->set_permission();
		$this->set_theme('backend');
		$this->load->model('Model', 'query');
	}
	
	public function index()
	{
		//$this->pageSize('8.5in', '13.5in');
		$this
		//->set_title($this->model->select('nm_model')->get_where($this->_table, array('id' => $this->_primary), 1)->row('nm_model'))
		->set_method('pdf')
		->set_output('results', $this->query->get_rka($this->_primary))
		->set_template('export', 'cetak_model/export')
		->render($this->_table);
	}
}