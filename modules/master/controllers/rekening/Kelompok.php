<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kelompok extends Aksara
{
	private $_table									= 'ref__rek_2';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_ref_rek_1');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/rekening/akun'				=> phrase('akun')
			)
		);
		if($this->_primary)
		{
			$this->_title							= $this->model->select('uraian')->get_where('ref__rek_1', array('id' => $this->_primary), 1)->row('uraian');
			$this->where
			(
				array
				(
					'id_ref_rek_1'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_ref_rek_1'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_ref_rek_1')
			->unset_field('id_ref_rek_1')
			->unset_view('id_ref_rek_1');
		}
		else
		{
			$this->where
			(
				array
				(
					'tahun'							=> get_userdata('year')
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
				'id_ref_rek_1',
				'ref__rek_1.id',
				'{ref__rek_1.kd_rek_1}. {ref__rek_1.uraian}',
				array
				(
					'ref__rek_1.tahun'				=> get_userdata('year')
				),
				NULL,
				array
				(
					'ref__rek_1.kd_rek_1'			=> 'ASC'
				)
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('rekening_kelompok') . ' ' . $this->_title)
			->set_icon('fa fa-user-circle-o')
			->unset_column('id, id_ref_rek_2, tahun')
			->unset_field('id, tahun')
			->unset_view('id, id_ref_rek_2, tahun')
			->set_field('uraian', 'textarea, hyperlink', 'master/rekening/jenis_belanja', array('id_ref_rek_2' => 'id'))
			->column_order('kd_rek_1, uraian')
			->field_order('id_ref_rek_1, kd_rek_2')
			->merge_content('<b>{kd_rek_1}.{kd_rek_2}</b>', phrase('kode'))
			->add_class
			(
				array
				(
					'id_ref_rek_1'						=> 'trigger_kode',
					'kd_rek_2'							=> 'kode_input',
					'uraian'							=> 'autofocus'
				)
			)
			->set_relation
			(
				'id_jns_kas',
				'ref__jenis_kas.id',
				'{ref__jenis_kas.nama}'
			)
			->set_alias
			(
				array
				(
					'id_ref_rek_1'						=> 'Akun',
					'kd_rek_2'							=> 'Kode',
					'uraian'							=> 'Kelompok',
					'id_jns_kas'						=> 'Jenis Kas',
					'nama'								=> 'Jenis Kas'
				)
			)
			->set_validation
			(
				array
				(
					'id_ref_rek_1'						=> 'required',
					'kd_rek_2'							=> 'required',
					'uraian'							=> 'required',
					'id_jns_kas'						=> 'required'
				)
			)
			->select
			(
				'ref__rek_1.kd_rek_1'
			)
			->join('ref__rek_1', 'ref__rek_1.id = ref__rek_2.id_ref_rek_1')
			->order_by('kd_rek_1, kd_rek_2')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_rek_2')->get_where('ref__rek_2', array('id_ref_rek_1' => $this->input->post('isu')), 1)->row('kd_rek_2');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}