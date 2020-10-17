<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Units extends Aksara
{
	private $_table									= 'ref__unit';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_bidang');
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
				'../bidang'							=> phrase('bidang')
			)
		);
		if($this->_primary)
		{
			$this->_title							= $this->model->select('nm_bidang')->get_where('ref__bidang', array('id' => $this->_primary), 1)->row('nm_bidang');
			$this->where
			(
				array
				(
					'id_bidang'						=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_bidang'						=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_bidang')
			->unset_field('id_bidang')
			->unset_view('id_bidang');
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
				'id_bidang',
				'ref__bidang.id',
				'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}. {ref__bidang.nm_bidang}',
				array
				(
					'ref__bidang.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__urusan',
						'ref__urusan.id = ref__bidang.id_urusan'
					)
				),
				'ref__urusan.kd_urusan, ref__bidang.kd_bidang'
			)
			
			->set_relation
			(
				'id_bidang_2',
				'ref__bidang.id',
				'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}. {ref__bidang.nm_bidang}',
				array
				(
					'ref__bidang.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__urusan',
						'ref__urusan.id = ref__bidang.id_urusan'
					)
				),
				'ref__urusan.kd_urusan, ref__bidang.kd_bidang'
			)
			
			->set_relation
			(
				'id_bidang_3',
				'ref__bidang.id',
				'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}. {ref__bidang.nm_bidang}',
				array
				(
					'ref__bidang.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__urusan',
						'ref__urusan.id = ref__bidang.id_urusan'
					)
				),
				'ref__urusan.kd_urusan, ref__bidang.kd_bidang'
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_unit') . ' ' . $this->_title)
			->set_icon('fa fa-user-circle-o')
			->unset_column('id, kode, singkat, pagu_musrenbang, pagu_reses, pagu, nama_bidang, nm_bidang, nip_pejabat, nama_jabatan, tahun')
			->unset_view('id, tahun')
			->unset_field('id, tahun')
			->unset_truncate('nm_unit')
			->set_field('nm_unit', 'textarea, hyperlink', 'master/data/sub_units', array('id_unit' => 'id'))
			->field_order('id_bidang, id_bidang_2, id_bidang_3, kd_unit, nm_unit, singkat, id_bidang_bappeda, nama_jabatan, nama_pejabat, nip_pejabat, pagu_musrenbang, pagu_reses, pagu')
			->column_order('kd_urusan, nm_unit, singkat, nama_jabatan, nama_pejabat, nip_pejabat, pagu_musrenbang, pagu_reses, pagu, nama_bidang')
			->merge_content('{kd_urusan}.{kd_bidang}.{kd_unit}.{id}', phrase('kode'), 'kode_unit')
			->set_field
			(
				array
				(
					'kd_bidang'							=> 'sprintf',
					'kd_bidang2'						=> 'sprintf',
					'kd_bidang3'						=> 'sprintf',
					'pagu'								=> 'price_format',
					'pagu_musrenbang'					=> 'price_format',
					'pagu_reses'						=> 'price_format'
					
				)
			)
			->add_class
			(
				array
				(
					'id_bidang'							=> 'trigger_kode',
					'kd_unit'							=> 'kode_input',
					'nm_unit'							=> 'autofocus'
				)
			)
			->set_alias
			(
				array
				(
					'id_bidang'							=> 'Bidang',
					'kd_unit'							=> 'Kode',
					'nm_unit'							=> 'Nama',
					'id_bidang_bappeda'					=> 'Bidang Bappeda',
					'nama_bidang'						=> 'Bidang Bappeda',
					'nama_jabatan'						=> 'Jabatan Kepala',
					'nama_pejabat'						=> 'Nama Kepala',
					'nip_pejabat'						=> 'NIP Kepala',
					'id_bidang'							=> 'Bidang 1',
					'id_bidang_2'						=> 'Bidang 2',
					'id_bidang_3'						=> 'Bidang 3'
				)
			)
			->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang
			')
			->set_relation
			(
				'id_bidang_bappeda',
				'ref__bidang_bappeda.id',
				'{ref__bidang_bappeda.kode}. {ref__bidang_bappeda.nama_bidang}',
				NULL,
				NULL,
				'ref__bidang_bappeda.kode'
			)
			->field_position
			(
				array
				(
					'nama_jabatan'						=> 2,
					'nama_pejabat'						=> 2,
					'nip_pejabat'						=> 2,
					'pagu_musrenbang'					=> 3,
					'pagu_reses'						=> 3,
					'pagu'								=> 3,
					'id_bidang_bappeda'					=> 3
				)
			)
			->field_prepend
			(
				array
				(
					'pagu_musrenbang'					=> 'Rp',
					'pagu_reses'						=> 'Rp',
					'pagu'								=> 'Rp'
				)
			)
			->set_validation
			(
				array
				(
					'id_bidang'							=> 'required',
					'kd_unit'							=> 'required',
					'nm_unit'							=> 'required',
					'singkat'							=> 'required',
					'id_bidang_bappeda'					=> 'required',
					'pagu_musrenbang'					=> 'required|numeric',
					'pagu_reses'						=> 'required|numeric',
					'pagu'								=> 'required|numeric'
				)
			)
			->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			->order_by('kd_urusan, kd_bidang, kd_unit')
			->render($this->_table);
		}
	}
	
	public function kode_unit($params = array())
	{
		$query											= $this->model->select
		('
			a.kd_urusan,
			b.kd_bidang,
			a_2.kd_urusan AS kd_urusan_2,
			b_2.kd_bidang AS kd_bidang_2,
			a_3.kd_urusan AS kd_urusan_3,
			b_3.kd_bidang AS kd_bidang_3,
			ref__unit.kd_unit
		')
		->join
		(
			'ref__bidang b',
			'b.id = ref__unit.id_bidang',
			'left'
		)
		->join
		(
			'ref__urusan a',
			'a.id = b.id_urusan',
			'left'
		)
		->join
		(
			'ref__bidang b_2',
			'b_2.id = ref__unit.id_bidang_2',
			'left'
		)
		->join
		(
			'ref__urusan a_2',
			'a_2.id = b_2.id_urusan',
			'left'
		)
		->join
		(
			'ref__bidang b_3',
			'b_3.id = ref__unit.id_bidang_3',
			'left'
		)
		->join
		(
			'ref__urusan a_3',
			'a_3.id = b_3.id_urusan',
			'left'
		)
		->get_where
		(
			'ref__unit',
			array
			(
				'ref__unit.id'							=> $params['id']
			)
		)
		->row();
		
		return $query->kd_urusan . '.' . sprintf('%02d', $query->kd_bidang) . ' . ' . ($query->kd_urusan_2 ? $query->kd_urusan_2 : '0') . '.' . sprintf('%02d', $query->kd_bidang_2) . ' . ' . ($query->kd_urusan_3 ? $query->kd_urusan_3 : '0') . '.' . sprintf('%02d', $query->kd_bidang_3) . ' . ' . sprintf('%02d', $query->kd_unit);
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_unit')->get_where('ref__unit', array('id_bidang' => $this->input->post('isu')), 1)->row('kd_unit');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}