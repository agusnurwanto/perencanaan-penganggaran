<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sub_units extends Aksara
{
	private $_table									= 'ref__sub';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_unit');
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
				'../units'							=> phrase('units')
			)
		);
		if($this->_primary)
		{
			$this->_title							= $this->model->select('nm_unit')->get_where('ref__unit', array('id' => $this->_primary), 1)->row('nm_unit');
			$this->where
			(
				array
				(
					'id_unit'						=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_unit'						=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_unit')
			->unset_field('id_unit')
			->unset_view('id_unit');
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
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_sub_unit') . ' ' . $this->_title)
			->set_icon('fa fa-user-circle-o')
			->unset_column('id, tahun, kd_urusan_2, kd_bidang_2, kd_urusan_3, kd_bidang_3, singkat, jabatan_ppk_skpd, nip_ppk_skpd')
			->unset_view('id, tahun')
			->unset_field('id, tahun')
			->set_field
			(
				array
				(
					'kd_sub'							=> 'last_insert',
					'nm_sub'							=> 'textarea'
				)
			)
			->field_order('id_unit, kd_sub, nm_sub, singkat')
			->field_position
			(
				array
				(
					'jabatan_ppk_skpd'					=> 2,
					'nama_ppk_skpd'						=> 2,
					'nip_ppk_skpd'						=> 2
				)
			)
			->column_order('kd_urusan, nm_sub, singkat, nm_unit')
			->merge_content('{kd_urusan}.{kd_bidang}.{kd_unit}.{kd_sub}.{id}', phrase('kode'), 'kode_sub')
			
			//->merge_content('{kd_urusan}.{kd_bidang}.{kd_urusan2}.{kd_bidang2}.{kd_unit}.{kd_sub}', phrase('kode'))
			->set_field
			(
				array
				(
					'kd_bidang'							=> 'sprintf',
					'kd_unit'							=> 'sprintf',
					'kd_bidang_2'						=> 'sprintf',
					'kd_bidang_3'						=> 'sprintf'
					
				)
			)
			->set_relation
			(
				'id_unit',
				'ref__unit.id',
				'{IFNULL(ref__urusan.kd_urusan, 0) AS kd_urusan}.{IFNULL(ref__bidang.kd_bidang, 0) AS kd_bidang}.{IFNULL(ref__urusan_2.kd_urusan, 0) AS kd_urusan_2}.{IFNULL(ref__bidang_2.kd_bidang, 0) AS kd_bidang_2}.{IFNULL(ref__urusan_3.kd_urusan, 0) AS kd_urusan_3}.{IFNULL(ref__bidang_3.kd_bidang, 0) AS kd_bidang_3}.{ref__unit.kd_unit} {ref__unit.nm_unit}',
				array
				(
					'ref__unit.tahun'				=> get_userdata('year')
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
					),
					array
					(
						'ref__bidang ref__bidang_2',
						'ref__bidang_2.id = ref__unit.id_bidang_2',
						'LEFT'
					),
					array
					(
						'ref__urusan ref__urusan_2',
						'ref__urusan_2.id = ref__bidang_2.id_urusan',
						'LEFT'
					),
					array
					(
						'ref__bidang ref__bidang_3',
						'ref__bidang_3.id = ref__unit.id_bidang_3',
						'LEFT'
					),
					array
					(
						'ref__urusan ref__urusan_3',
						'ref__urusan_3.id = ref__bidang_3.id_urusan',
						'LEFT'
					)
				),
				'ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit',
				null,
				null,
				null,
				'0'
			)
			->set_alias
			(
				array
				(
					'id_unit'							=> 'Unit',
					'kd_sub'							=> 'Kode',
					'nm_sub'							=> 'Nama',
					'jabatan_ppk_skpd'					=> 'Jabatan PPK',
					'nama_ppk_skpd'						=> 'PPK',
					'nip_ppk_skpd'						=> 'NIP PPK',
					'nm_unit'							=> 'Nama Unit'
				)
			)
			->add_class
			(
				array
				(
					'id_unit'							=> 'trigger_kode',
					'kd_sub'							=> 'kode_input',
					'nm_sub'							=> 'autofocus'
				)
			)
			->set_validation
			(
				array
				(
					'kd_sub'							=> 'required', //|is_unique[ref__sub.kd_sub.id.' . $this->input->get('id') . '.id_unit.' . $this->input->post('id_unit') . ']',
					'nm_sub'							=> 'required',
					'id_unit'							=> 'required',
					'singkat'							=> 'required'
				)
			)
			/*->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit
			')
			->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
			->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			->join('ref__bidang AS ref__bidang_2', 'ref__bidang_2.id = ref__unit.id_bidang_2', 'LEFT')
			->join('ref__urusan AS ref__urusan_2', 'ref__urusan_2.id = ref__bidang_2.id_urusan', 'LEFT')*/
			->order_by
			(
				array
				(
					'ref__urusan.kd_urusan'				=> 'ASC',
					'ref__bidang.kd_bidang'				=> 'ASC',
					'kd_urusan_2'						=> 'ASC',
					'kd_bidang_2'						=> 'ASC',
					'kd_urusan_3'						=> 'ASC',
					'kd_bidang_3'						=> 'ASC',
					'ref__unit.kd_unit'					=> 'ASC',
					'ref__sub.kd_sub'					=> 'ASC'
				)
			)
			->render($this->_table);
		}
	}
	
	public function kode_sub($params = array())
	{
		$query											= $this->model->select
		('
			a.kd_urusan,
			b.kd_bidang,
			a_2.kd_urusan AS kd_urusan_2,
			b_2.kd_bidang AS kd_bidang_2,
			a_3.kd_urusan AS kd_urusan_3,
			b_3.kd_bidang AS kd_bidang_3,
			ref__unit.kd_unit,
			ref__sub.kd_sub
		')
		->join
		(
			'ref__unit',
			'ref__unit.id = ref__sub.id_unit'
		)
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
			'ref__sub',
			array
			(
				'ref__sub.id'							=> $params['id']
			)
		)
		->row();
		
		return $query->kd_urusan . '.' . sprintf('%02d', $query->kd_bidang) . ' . ' . ($query->kd_urusan_2 ? $query->kd_urusan_2 : '0') . '.' . sprintf('%02d', $query->kd_bidang_2) . ' . ' . ($query->kd_urusan_3 ? $query->kd_urusan_3 : '0') . '.' . sprintf('%02d', $query->kd_bidang_3) . ' . ' . sprintf('%02d', $query->kd_unit) . '.' . sprintf('%02d', $query->kd_sub);
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_sub')->get_where('ref__sub', array('id_unit' => $this->input->post('isu')), 1)->row('kd_sub');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}