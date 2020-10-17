<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sub_rincian_objek extends Aksara
{
	private $_table									= 'ref__rek_6';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_ref_rek_5');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/rekening/akun'				=> phrase('akun'),
				'../kelompok'						=> phrase('kelompok'),
				'../jenis_belanja'					=> phrase('jenis_belanja'),
				'../objek_belanja'					=> phrase('objek_belanja'),
				'../objek_belanja'					=> phrase('Rincian_objek')
			)
		);
		if($this->_primary)
		{
			$this->_title							= $this->model->select('uraian')->get_where('ref__rek_5', array('id' => $this->_primary), 1)->row('uraian');
			$this->where
			(
				array
				(
					'id_ref_rek_5'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_ref_rek_5'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_ref_rek_5')
			->unset_field('id_ref_rek_5')
			->unset_view('id_ref_rek_5');
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
				'id_ref_rek_5',
				'ref__rek_5.id',
				'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4} {ref__rek_5.kd_rek_5} - {ref__rek_5.uraian}',
				array
				(
					'ref__rek_5.tahun'				=> get_userdata('year')
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
				),
				array
				(
					'ref__rek_1.kd_rek_1'			=> 'ASC',
					'ref__rek_2.kd_rek_2'			=> 'ASC',
					'ref__rek_3.kd_rek_3'			=> 'ASC',
					'ref__rek_4.kd_rek_4'			=> 'ASC',
					'ref__rek_5.kd_rek_5'			=> 'ASC'
				)
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('rincian_objek') . ' ' . $this->_title)
			->set_icon('fa fa-user-circle-o')
			->unset_truncate('uraian')
			->unset_column('id, id_ref_rek_5, tahun')
			->unset_view('id, id_ref_rek_5, tahun')
			->unset_field('id, tahun')
			->set_field
			(
				array
				(
					'kd_rek_6'							=> 'last_insert',
					'keterangan'						=> 'textarea'
				)
			)
			->column_order('kd_rek_1, uraian')
			->field_order('id_ref_rek_5, kd_rek_6')
			->merge_content('<b>{kd_rek_1}.{kd_rek_2}.{kd_rek_3}.{kd_rek_4}.{kd_rek_5}.{kd_rek_6}</b>', phrase('kode'))
			->merge_field('kd_rek_6, id_jns_kas')
			->field_size
			(
				array
				(
					'kd_rek_6'							=> 'col-3'
				)
			)
			->add_class
			(
				array
				(
					'id_ref_rek_5'						=> 'trigger_kode',
					'kd_rek_6'							=> 'kode_input',
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
					'id_ref_rek_5'						=> 'Rincian Objek',
					'kd_rek_6'							=> 'Kode',
					'uraian'							=> 'Sub Rincian Objek',
					'id_jns_kas'						=> 'Jenis Kas',
					'nama'								=> 'Jenis Kas'
				)
			)
			->set_validation
			(
				array
				(
					'id_ref_rek_5'						=> 'required',
					'kd_rek_6'							=> 'required',
					'uraian'							=> 'required',
					'id_jns_kas'						=> 'required'
				)
			)
			->order_by('kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, kd_rek_5, kd_rek_6')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_rek_6')->get_where('ref__rek_6', array('id_ref_rek_5' => $this->input->post('isu')), 1)->row('kd_rek_6');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}