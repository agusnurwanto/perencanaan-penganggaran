<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sub_rincian_objek extends Aksara
{
	private $_table									= 'ref__sumber_dana_rek_6';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_sumber_dana_rek_5');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/sumber_dana/akun'			=> phrase('Akun'),
				'../kelompok'						=> phrase('Kelompok'),
				'../jenis'							=> phrase('Jenis'),
				'../objek'							=> phrase('Objek'),
				'../rincian_objek'					=> phrase('Rincian Objek')
			)
		);
		if($this->_primary)
		{
			$this->_title							= $this->model->select('uraian')->get_where('ref__sumber_dana_rek_5', array('id' => $this->_primary), 1)->row('uraian');
			$this->where
			(
				array
				(
					'id_sumber_dana_rek_5'			=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_sumber_dana_rek_5'			=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_sumber_dana_rek_5')
			->unset_field('id_sumber_dana_rek_5')
			->unset_view('id_sumber_dana_rek_5');
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
				'id_sumber_dana_rek_5',
				'ref__sumber_dana_rek_5.id',
				'{ref__sumber_dana_rek_1.kd_sumber_dana_rek_1}.{ref__sumber_dana_rek_2.kd_sumber_dana_rek_2}.{ref__sumber_dana_rek_3.kd_sumber_dana_rek_3}.{ref__sumber_dana_rek_4.kd_sumber_dana_rek_4}.{ref__sumber_dana_rek_5.kd_sumber_dana_rek_5} {ref__sumber_dana_rek_5.uraian}',
				array
				(
					'ref__sumber_dana_rek_5.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__sumber_dana_rek_4',
						'ref__sumber_dana_rek_4.id = ref__sumber_dana_rek_5.id_sumber_dana_rek_4'
					),
					array
					(
						'ref__sumber_dana_rek_3',
						'ref__sumber_dana_rek_3.id = ref__sumber_dana_rek_4.id_sumber_dana_rek_3'
					),
					array
					(
						'ref__sumber_dana_rek_2',
						'ref__sumber_dana_rek_2.id = ref__sumber_dana_rek_3.id_sumber_dana_rek_2'
					),
					array
					(
						'ref__sumber_dana_rek_1',
						'ref__sumber_dana_rek_1.id = ref__sumber_dana_rek_2.id_sumber_dana_rek_1'
					)
				)
			//	'ref__bidang.kd_bidang, ref__program.kd_program'
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_sumber_dana_sub_rincian_objek') . ' ' . $this->_title)
			->set_icon('fa fa-user-circle-o')
			->unset_column('id, id_sumber_dana_rek_5, tahun, uraian')
			//->unset_truncate('keterangan')
			->unset_view('id, id_sumber_dana_rek_5, tahun')
			->unset_field('id, tahun')
			//->set_field('uraian', 'textarea, hyperlink', 'master/sumber_dana/sub_rincian_objek', array('id_sumber_dana_rek_5' => 'id'))
			->set_field
			(
				array
				(
					'kode'				=> 'last_insert'
				)
			)
			->add_class
			(
				array
				(
					'id_sumber_dana_rek_5'				=> 'trigger_kode',
					'kode'								=> 'kode_input',
					'nama_sumber_dana'					=> 'autofocus'
				)
			)
			->column_order('kd_sumber_dana_rek_1, nama_sumber_dana')
			->field_order('id_sumber_dana_rek_5, kode')
			->merge_content('{kd_sumber_dana_rek_1}.{kd_sumber_dana_rek_2}.{kd_sumber_dana_rek_3}.{kd_sumber_dana_rek_4}.{kd_sumber_dana_rek_5}.{kode}', phrase('kode'))
			->set_alias
			(
				array
				(
					'id_sumber_dana_rek_5'				=> 'Rincian Objek',
					'kode'								=> 'Kode',
					'nama_sumber_dana'					=> 'Sumber Dana Sub Rincian Objek',
					'nama_sumber_dana'					=> 'Sumber Dana Sub Rincian Objek'
				)
			)
			->set_validation
			(
				array
				(
					'kode'								=> 'required',
					'nama_sumber_dana'					=> 'required',
					'singkat'							=> 'required'
				)
			)
			/*->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang
			')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')*/
			->order_by('kd_sumber_dana_rek_1, kd_sumber_dana_rek_2, kd_sumber_dana_rek_3, kd_sumber_dana_rek_4, kd_sumber_dana_rek_5, kode')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ref__sumber_dana_rek_6', array('id_sumber_dana_rek_5' => $this->input->post('isu')), 1)->row('kode');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}