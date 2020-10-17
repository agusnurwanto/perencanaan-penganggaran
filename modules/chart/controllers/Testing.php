<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Testing extends Aksara
{
	
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend');
		
	}
	
	public function index()
	{
		$this->set_title('Testing-testing')
		->set_icon('fa fa-info')
		->set_field('uraian', 'textarea')
		->set_autocomplete
		(
			'id_rek_5',
			'ref__rek_5.id',
			array
			(
				'{ref__rek_5.id}',
				'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5} - {ref__rek_5.uraian}',
				'{ref__rek_5.uraian}'
			),
			array
			(
				'ref__rek_5.id !='				=> null
			),
			array
			(
				array
				(
					'ref__rek_4',
					'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
				),
				array
				(
					'ref__rek_3',
					'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
				),
				array
				(
					'ref__rek_2',
					'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
				),
				array
				(
					'ref__rek_1',
					'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
				)
			)
		)/*
		->set_field
		(
			'id_rek_5', // relation field
			'autocomplete', // field type
			'ref__rek_5.id', // relation key
			array
			(
				'value'							=> '{ref__rek_5.id}', // autocomplete value (required)
				'label'							=> '{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5} - {ref__rek_5.uraian}', // autocomplete label (required)
				'description'					=> '{ref__rek_5.uraian}', // autocomplete description (if any)
				//'image'							=> '{ref__rek_5.uraian}' // autocomplete image (if any)
			),
			array // where (if any)
			(
				'ref__rek_5.id !='				=> null
			),
			array // join (if any)
			(
				array
				(
					'ref__rek_4',
					'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
				),
				array
				(
					'ref__rek_3',
					'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
				),
				array
				(
					'ref__rek_2',
					'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
				),
				array
				(
					'ref__rek_1',
					'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
				)
			),
			'ref__rek_1.kd_rek_1, ref__rek_2.kd_rek_2, ref__rek_3.kd_rek_3, ref__rek_4.kd_rek_4, ref__rek_5.kd_rek_5' // order by (if any)
		)*/
		->render('ta__belanja');
	}
}