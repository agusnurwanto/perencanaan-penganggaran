<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Jenis extends Aksara
{
	private $_table									= 'ref__sumber_dana_rek_3';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_sumber_dana_rek_2');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/sumber_dana/akun'				=> phrase('Akun'),
				'../kelompok'							=> phrase('Kelompok')
			)
		);
		if($this->_primary)
		{
			$this->_title							= $this->model->select('uraian')->get_where('ref__sumber_dana_rek_2', array('id' => $this->_primary), 1)->row('uraian');
			$this->where
			(
				array
				(
					'id_sumber_dana_rek_2'			=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_sumber_dana_rek_2'			=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_sumber_dana_rek_2')
			->unset_field('id_sumber_dana_rek_2')
			->unset_view('id_sumber_dana_rek_2');
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
				'id_sumber_dana_rek_2',
				'ref__sumber_dana_rek_2.id',
				'{ref__sumber_dana_rek_1.kd_sumber_dana_rek_1}.{ref__sumber_dana_rek_2.kd_sumber_dana_rek_2} {ref__sumber_dana_rek_2.uraian}',
				array
				(
					'ref__sumber_dana_rek_2.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__sumber_dana_rek_1',
						'ref__sumber_dana_rek_1.id = ref__sumber_dana_rek_2.id_sumber_dana_rek_1'
					)
				),
				'ref__sumber_dana_rek_1.kd_sumber_dana_rek_1, ref__sumber_dana_rek_2.kd_sumber_dana_rek_2'
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_sumber_dana_jenis') . ' ' . $this->_title)
			->set_icon('fa fa-user-circle-o')
			->unset_column('id, tahun')
			->unset_view('id, tahun')
			->unset_field('id, tahun')
			->set_field('uraian', 'textarea, hyperlink', 'master/sumber_dana/objek', array('id_sumber_dana_rek_3' => 'id'))
			->set_field
			(
				array
				(
					'kd_sumber_dana_rek_3'				=> 'last_insert'
				)
			)
			->add_class
			(
				array
				(
					'id_sumber_dana_rek_2'				=> 'trigger_kode',
					'kd_sumber_dana_rek_3'				=> 'kode_input',
					'uraian'							=> 'autofocus'
				)
			)
			->column_order('kd_sumber_dana_rek_1, uraian')
			->field_order('id_sumber_dana_rek_2')
			->merge_content('{kd_sumber_dana_rek_1}.{kd_sumber_dana_rek_2}.{kd_sumber_dana_rek_3}', phrase('kode'))
			->set_alias
			(
				array
				(
					'id_sumber_dana_rek_2'				=> 'Kelompok',
					'kd_sumber_dana_rek_3'				=> 'Kode',
					'uraian'							=> 'Nama Sumber Dana',
					'uraian'							=> 'Sumber Dana Jenis'
				)
			)
			->set_validation
			(
				array
				(
					'kd_sumber_dana_rek_3'				=> 'required',
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
			->order_by('kd_sumber_dana_rek_1, kd_sumber_dana_rek_2, kd_sumber_dana_rek_3')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_sumber_dana_rek_3')->get_where('ref__sumber_dana_rek_3', array('id_sumber_dana_rek_2' => $this->input->post('isu')), 1)->row('kd_sumber_dana_rek_3');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}