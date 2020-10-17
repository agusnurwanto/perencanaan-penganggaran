<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pajak extends Aksara
{
	private $_table									= 'ref_pajak_pot';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master'							=> phrase('master')
			)
		)
		->set_title(phrase('master_pajak'))
		->set_icon('fa fa-file-powerpoint-o')
		->set_relation
		(
			'id_rek_5',
			'ref_rek_5.id_ref_rek_5',
			'{ref_rek_1.kd_rek_1}.{ref_rek_2.kd_rek_2}.{ref_rek_3.kd_rek_3}.{ref_rek_4.kd_rek_4}.{ref_rek_5.kd_rek_5} {ref_rek_5.uraian}',
			array
			(
				'ref_rek_1.kd_rek_1'				=> 7,
				'ref_rek_2.kd_rek_2'				=> 1
			),
			array
			(
				array
				(
					'ref_rek_4',
					'ref_rek_4.id_ref_rek_4 = ref_rek_5.id_ref_rek_4'
				),
				array
				(
					'ref_rek_3',
					'ref_rek_3.id_ref_rek_3 = ref_rek_4.id_ref_rek_3'
				),
				array
				(
					'ref_rek_2',
					'ref_rek_2.id_ref_rek_2 = ref_rek_3.id_ref_rek_2'
				),
				array
				(
					'ref_rek_1',
					'ref_rek_1.id_ref_rek_1 = ref_rek_2.id_ref_rek_1'
				)
			),
			null,
			'kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, kd_rek_5'
		)
		->set_validation
		(
			array
			(
				'id_rek_5'							=> 'required|is_unique[' . $this->_table . '.id_rek_5.id.' . $this->input->get('id') . ']',
				'nilai'								=> 'required'
			)
		)
		->set_alias('nilai', 'Nilai (%)')
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->merge_content('<b>{kd_rek_1}.{kd_rek_2}.{kd_rek_3}.{kd_rek_4}.{kd_rek_5}</b>', 'Kode')
		->column_order('kd_rek_1')
		->render($this->_table);
	}
}