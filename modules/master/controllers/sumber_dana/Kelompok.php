<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kelompok extends Aksara
{
	private $_table									= 'ref__sumber_dana_rek_2';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_sumber_dana_rek_1');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/sumber_dana/akun'				=> phrase('Akun')
			)
		);
		if($this->_primary)
		{
			$this->_title							= $this->model->select('uraian')->get_where('ref__sumber_dana_rek_1', array('id' => $this->_primary), 1)->row('uraian');
			$this->where
			(
				array
				(
					'id_sumber_dana_rek_1'			=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_sumber_dana_rek_1'			=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_sumber_dana_rek_1')
			->unset_field('id_sumber_dana_rek_1')
			->unset_view('id_sumber_dana_rek_1');
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
				'id_sumber_dana_rek_1',
				'ref__sumber_dana_rek_1.id',
				'{ref__sumber_dana_rek_1.kd_sumber_dana_rek_1} {ref__sumber_dana_rek_1.uraian}',
				array
				(
					'ref__sumber_dana_rek_1.tahun'				=> get_userdata('year')
				)
				
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_sumber_dana_kelompok') . ' ' . $this->_title)
			->set_icon('mdi mdi-access-point')
			->unset_column('id, tahun')
			->unset_view('id, tahun')
			->unset_field('id, tahun')
			->set_field('uraian', 'textarea, hyperlink', 'master/sumber_dana/jenis', array('id_sumber_dana_rek_2' => 'id'))
			->set_field
			(
				array
				(
					'kd_sumber_dana_rek_2'			=> 'last_insert'
				)
			)
			->add_class
			(
				array
				(
					'id_sumber_dana_rek_1'			=> 'trigger_kode',
					'kd_sumber_dana_rek_2'			=> 'kode_input',
					'uraian'						=> 'autofocus'
				)
			)
			->column_order('kd_sumber_dana_rek_1, uraian')
			->field_order('id_sumber_dana_rek_1')
			->merge_content('{kd_sumber_dana_rek_1}.{kd_sumber_dana_rek_2}', phrase('kode'))
			->set_alias
			(
				array
				(
					'id_sumber_dana_rek_1'				=> 'Akun',
					'kd_sumber_dana_rek_2'				=> 'Kode',
					'uraian'							=> 'Kelompok',
					'uraian'							=> 'Sumber Dana Kelompok'
				)
			)
			->set_validation
			(
				array
				(
					'kd_sumber_dana_rek_2'				=> 'required',
					'uraian'							=> 'required'
				)
			)
			/*->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang
			')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')*/
			->order_by('kd_sumber_dana_rek_1, kd_sumber_dana_rek_2')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_sumber_dana_rek_2')->get_where('ref__sumber_dana_rek_2', array('id_sumber_dana_rek_1' => $this->input->post('isu')), 1)->row('kd_sumber_dana_rek_2');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}