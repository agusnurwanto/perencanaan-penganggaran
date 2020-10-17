<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Capaian_program extends Aksara
{
	private $_table									= 'ta__capaian_program';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_prog');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master'							=> phrase('master'),
				'data'								=> phrase('data'),
				'urusan'							=> phrase('urusan'),
				'../bidang'							=> phrase('bidang'),
				'../program'						=> phrase('program')
			)
		);
		if($this->_primary)
		{
			$this->_title							= $this->model->select('nm_program')->get_where('ref__program', array('id' => $this->_primary), 1)->row('nm_program');
			$this->where
			(
				array
				(
					'id_prog'						=> $this->_primary,
					'ref__program.tahun'				=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_prog'						=> $this->_primary
				)
			)
			->unset_field('id_prog');
		}
		else
		{
			$this->where
			(
				array
				(
					'ref__program.tahun'				=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'tahun'							=> get_userdata('year')
				)
			)
			->set_relation
			(
				'id_prog',
				'ref__program.id',
				'{ref__program.kd_program} - {ref__program.nm_program}',
				array
				(
					'ta__program.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__program',
						'ref__program.id = ta__program.id_prog'
					)
				)
			)
			->set_validation('id_prog', 'required|numeric');
		}
		
		$this->set_title(phrase('master_capaian_program') . ' ' . $this->_title)
		->set_icon('fa fa-check-circle-o')
		->unset_column('id, id_prog, tahun')
		->unset_view('id, id_prog, tahun')
		->unset_field('id, tahun')
		->column_order('kd_urusan, nm_program')
		->field_order('id_prog')
		->merge_content('{kd_urusan}.{kd_bidang}.{kd_program}.{kd_capaian}', phrase('kode'))
		->merge_content('{target_angka} {target_uraian}', phrase('target'))
		->set_field
		(
			array
			(
				'kd_capaian'						=> 'last_insert',
				'tolak_ukur'						=> 'textarea',
				'target_angka'						=> 'number_format'
			)
		)
		->set_alias
		(
			array
			(
				'id_prog'							=> 'Program',
				'kd_capaian'						=> 'Kode'
			)
		)
		->set_validation
		(
			array
			(
				'kd_capaian'						=> 'required|numeric',
				'tolak_ukur'						=> 'required',
				'target_angka'						=> 'required',
				'target_uraian'						=> 'required'
			)
		)
		->select
		('
			ref_urusan.kd_urusan,
			ref_bidang.kd_bidang,
			ref_program.kd_program
		')
		->join('ref_program', 'ref_program.id = ta_capaian_program.id_prog')
		->join('ref_bidang', 'ref_bidang.id = ref_program.id_bidang')
		->join('ref_urusan', 'ref_urusan.id = ref_bidang.id_urusan')
		->order_by('kd_urusan, kd_bidang, kd_program')
		->set_template('form', 'capaian_program/form')
		->render($this->_table);
	}
}