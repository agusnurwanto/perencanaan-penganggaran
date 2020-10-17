<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Desk extends Aksara
{
	private $_table									= 'ta__desk_renja';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Desk Renja')
		->set_icon('fa fa-upload')
		->unset_column('id')
		->unset_field('id')
		->column_order('kd_urusan, nomor_ba, tanggal_ba, keterangan, file')
		->merge_content('<b>{kd_urusan}.{kd_bidang}.{kd_unit} {nm_unit}</b>', phrase('unit'))
		->set_relation
		(
			'id_unit',
			'ref__unit.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__unit.kd_unit}. {ref__unit.nm_unit}',
			array
			(
				'ref__unit.tahun'					=> get_userdata('year')
			),
			array
			(
				array
				(
					'ref__bidang',
					'ref__bidang.id = ref__unit.id_bidang'
				),
				array
				(
					'ref__urusan',
					'ref__urusan.id = ref__bidang.id_urusan'
				)
			),
			array
			(
				'ref__urusan.kd_urusan'				=> 'ASC',
				'ref__bidang.kd_bidang'				=> 'ASC',
				'ref__unit.kd_unit'					=> 'ASC'
			)
		)
		->set_field
		(
			array
			(
				'tanggal_ba'						=> 'datepicker',
				'keterangan'						=> 'textarea',
				'file'								=> 'file'
			)
		)
		->set_alias
		(
			array
			(
				'id_unit'							=> 'Unit'
			)
		)
		->add_class
		(
			array
			(
				'nomor_ba'							=> 'autofocus'
			)
		)
		->set_validation
		(
			array
			(
				'nomor_ba'							=> 'required',
				'tanggal_ba'						=> 'required',
				'keterangan'						=> 'required'
			)
		)
		->field_position
		(
			array
			(
				'keterangan'						=> 2,
				'file'								=> 2
			)
		)
		->order_by
		(
			array
			(
				'ref__urusan.kd_urusan'				=> 'ASC',
				'ref__bidang.kd_bidang'				=> 'ASC',
				'ref__unit.kd_unit'					=> 'ASC'
			)
		)
		->render($this->_table);
	}
}