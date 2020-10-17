<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Indikator extends Aksara
{
	private $_table									= 'ta__model_indikator';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_model');
		$this->_title								= phrase('indikator') . ' - ' .  phrase('model') . ' ' . $this->model->select('nm_model')->get_where('ta__model', array('id' => $this->_primary), 1)->row('nm_model');
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title($this->_title)
		->set_default('id_model', $this->_primary)
		->unset_column('id, id_model, penjelasan')
		->unset_field('id, id_model')
		->unset_view('id, id_model')
		->set_output('variabel', $this->_variabel())
		->set_field
		(
			array
			(
				'kd_indikator'						=> 'last_insert',
				'tolak_ukur'						=> 'textarea',
				'penjelasan'						=> 'wysiwyg'
			)
		)
		->add_class('penjelasan', 'minimal')
		->column_order('kd_indikator_ref__indikator')
		->merge_content('{kd_indikator_ref__indikator} - {nm_indikator}', 'Indikator')
		->set_relation
		(
			'jns_indikator',
			'ref__indikator.id',
			'{ref__indikator.kd_indikator} - {ref__indikator.nm_indikator}'
		)
		->set_validation
		(
			array
			(
				'jns_indikator'						=> 'required',
				'kd_indikator'						=> 'required',
				'tolak_ukur'						=> 'required',
				'target'							=> 'required',
				'satuan'							=> 'required'
			)
		)
		->set_default('id_model', $this->_primary)
		->where('id_model', $this->_primary)
		->order_by('jns_indikator')
		->set_template('form', 'form')
		->render($this->_table);
	}
	
	private function _variabel()
	{
		$query										= $this->model
		->query
		('
			SELECT
			ta__model_variabel.kd_variabel,
			ta__model_variabel.nm_variabel,
			ta__model_variabel.id
			FROM
			ta__model_variabel
			INNER JOIN ta__model ON ta__model_variabel.id_model = ta__model.id
			WHERE
			ta__model.id = ' . $this->input->get('id_model') . '
			ORDER BY
			ta__model_variabel.kd_variabel ASC										
		')
		->result_array();
		
		return $query;
	}
}