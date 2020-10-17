<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Variabel extends Aksara
{
	private $_table									= 'ta__model_variabel';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_model');
		$this->_title								= $this->model->select('nm_model')->get_where('ta__model', array('id' => $this->_primary), 1)->row('nm_model');
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		if($this->_primary)
		{
			$this->set_default('id_model', $this->_primary)
			->where('id_model', $this->_primary);
		}
		else
		{
			$this->set_relation
			(
				'id_model',
				'ta__model.id',
				'{ta__model.kd_model} - {ta__model.nm_model}'
			);
		}
		$this->set_title('Variabel - ' . $this->_title)
		->unset_column('id, id_model')
		->unset_field('id, id_model')
		->unset_view('id, id_model')
		->set_field('kd_variabel', 'last_insert')
		->set_alias
			(
				array
				(
					'kd_variabel'						=> 'Kode Variabel',
					'nm_variabel'						=> 'Nama Variabel'
				)
			)
		
		->order_by('kd_variabel')
		->render($this->_table);
		
	}
}