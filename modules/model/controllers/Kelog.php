<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kelog extends Aksara
{
	private $_table									= 'ta__model_kelog';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_model');
		$this->_title								= phrase('kerangka_logis') . ' - ' .  phrase('model') . ' ' . $this->model->select('nm_model')->get_where('ta__model', array('id' => $this->_primary), 1)->row('nm_model');
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title($this->_title)
		->set_icon('fa fa-table')
		->unset_column('id, id_model, kd_indikator_ref__indikator')
		->unset_field('id, id_model')
		->unset_view('id, id_model')
		->column_order('kd_kelog, nm_indikator_ref__indikator, ringkasan, indikator, penjelasan')
		->field_order('jenis_indikator')
		->set_alias
		(
			array
			(
				'kd_kelog'					=> 'Kode Kelog',
				'kd_indikator'				=> 'Kode Indikator',
				'nm_indikator'				=> 'Nama Indikator'
			)
		)
		
		->set_field
		(
			array
			(
				'penjelasan'						=> 'wysiwyg'
			)
		)
		->set_relation
		(
			'jenis_indikator',
			'ref__indikator.id',
			'{ref__indikator.kd_indikator} - {ref__indikator.nm_indikator}'
		)
		/*
		->set_relation
		(
			'indikator',
			'ta__indikator.id',
			'{ta__indikator.kd_indikator} - {ta__indikator.tolak_ukur}'
		)
		*/
		->set_default('id_model', $this->_primary)
		->where('id_model', $this->_primary)
		->render($this->_table);
	}
}