<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_tahun								= get_userdata('year');
		$this->_sub_unit							= $this->input->get('sub_unit');
		$this->_unit								= $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_sub_unit), 1)->row('id_unit');
		$this->_kegiatan							= $this->input->get('kegiatan');
		$this->_sub_kegiatan						= $this->input->get('sub_kegiatan');
		if(!$this->_sub_kegiatan)
		{
			return throw_exception(301, 'silakan memilih sub kegiatan terlebih dahulu', go_to('../sub_kegiatan'));
		}
		$this->set_theme('backend');
		$this->set_permission();
		if('remove-comment' == $this->input->post('method'))
		{
			return $this->_remove_comment();
		}
	}
	
	public function index()
	{
		$jenis_anggaran								= $this->model
			->select('id_jenis_anggaran')
			->limit(1)
			->get_where('ta__kegiatan_sub', array('id' => $this->_sub_kegiatan))
			->row('id_jenis_anggaran');
		//print($jenis_anggaran);exit;
		if($jenis_anggaran > 9)
		{
			return throw_exception(301, 'silakan memilih sub kegiatan terlebih dahulu', go_to('../perubahan'));
		}
		
		if($this->input->post('token'))
		{
			return $this->_process_data();
		}
		$this->set_title('Pengecekan data')
		->set_icon('mdi mdi-camcorder-box')
		->set_breadcrumb
		(
			array
			(
				'renja'								=> 'Renja',
				'asistensi'							=> 'Asistensi',
				'sub_unit'							=> 'Sub Unit',
				'../kegiatan'						=> 'Kegiatan',
				'../sub_kegiatan'					=> 'Sub Kegiatan'
			)
		)
		->set_output
		(
			array
			(
				'results'								=> $this->_rka_sub_kegiatan($this->_tahun, $this->_kegiatan, $this->_sub_kegiatan),
				//'existing'								=> $this->_existing()
			)
		)
		->render();
	}
	
	private function _process_data()
	{
		$data											= $this->input->post();
		//print_r($data);exit;
		$item_id										= 0;
		if(isset($data['capaian_program']) && is_array($data['capaian_program']) && sizeof($data['capaian_program']) > 0)
		{
			foreach($data['capaian_program'] as $key => $val)
			{
				if(!$val['comments']) continue;
				$uraian									= $this->model->select('tolak_ukur')->get_where('ta__program_capaian', array('id' => $key), 1)->row('tolak_ukur');
				$prepare								= array
				(
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 1,
					'id_jenis'							=> $key,
					'jenis_indikator'					=> 0,
					'uraian'							=> ($uraian ? $uraian : ''),
					'comments'							=> (isset($val['comments']) ? $val['comments'] : null),
					'id_operator'						=> get_userdata('user_id'),
					'operator'							=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
					'tanggal'							=> date('Y-m-d H:i:s')
				);
				$where									= array
				(
					'id'								=> (isset($val['id']) ? $val['id'] : 0),
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 1,
					'id_jenis'							=> $key,
					'jenis_indikator'					=> 0,
					'id_operator'						=> get_userdata('user_id')
				);
				if($this->model->get_where('ta__asistensi', $where)->num_rows() > 0)
				{
					$this->model->update('ta__asistensi', $prepare, $where, 1);
					$item_id							= (isset($val['id']) ? $val['id'] : 0);
				}
				else
				{
					$this->model->insert('ta__asistensi', $prepare, 1);
					$item_id							= $this->model->insert_id();
				}
			}
		}
		if(isset($data['indikator']) && is_array($data['indikator']) && sizeof($data['indikator']) > 0)
		{
			if(isset($data['indikator']['masukan']) && is_array($data['indikator']['masukan']))
			{
				foreach($data['indikator']['masukan'] as $key => $val)
				{
					if(!$val['comments']) continue;
					$uraian								= $this->model->select('tolak_ukur')->get_where('ta__indikator', array('id' => $key), 1)->row('tolak_ukur');
					$prepare							= array
					(
						'id_keg_sub'					=> $this->_sub_kegiatan,
						'jenis'							=> 2,
						'id_jenis'						=> $key,
						'jenis_indikator'				=> 1,
						'uraian'						=> ($uraian ? $uraian : ''),
						'comments'						=> (isset($val['comments']) ? $val['comments'] : null),
						'id_operator'					=> get_userdata('user_id'),
						'operator'						=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
						'tanggal'						=> date('Y-m-d H:i:s')
					);
					$where								= array
					(
						'id'							=> (isset($val['id']) ? $val['id'] : 0),
						'id_keg_sub'					=> $this->_sub_kegiatan,
						'jenis'							=> 2,
						'id_jenis'						=> $key,
						'jenis_indikator'				=> 1,
						'id_operator'					=> get_userdata('user_id')
					);
					if($this->model->get_where('ta__asistensi', $where)->num_rows() > 0)
					{
						$this->model->update('ta__asistensi', $prepare, $where, 1);
						$item_id						= (isset($val['id']) ? $val['id'] : 0);
					}
					else
					{
						$this->model->insert('ta__asistensi', $prepare, 1);
						$item_id						= $this->model->insert_id();
					}
				}
			}
			if(isset($data['indikator']['keluaran']) && is_array($data['indikator']['keluaran']))
			{
				foreach($data['indikator']['keluaran'] as $key => $val)
				{
					if(!$val['comments']) continue;
					$uraian								= $this->model->select('tolak_ukur')->get_where('ta__indikator', array('id' => $key), 1)->row('tolak_ukur');
					$prepare							= array
					(
						'id_keg_sub'					=> $this->_sub_kegiatan,
						'jenis'							=> 2,
						'id_jenis'						=> $key,
						'jenis_indikator'				=> 2,
						'uraian'						=> ($uraian ? $uraian : ''),
						'comments'						=> (isset($val['comments']) ? $val['comments'] : null),
						'id_operator'					=> get_userdata('user_id'),
						'operator'						=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
						'tanggal'						=> date('Y-m-d H:i:s')
					);
					$where								= array
					(
						'id'							=> (isset($val['id']) ? $val['id'] : 0),
						'id_keg_sub'					=> $this->_sub_kegiatan,
						'jenis'							=> 2,
						'id_jenis'						=> $key,
						'jenis_indikator'				=> 2,
						'id_operator'					=> get_userdata('user_id')
					);
					if($this->model->get_where('ta__asistensi', $where)->num_rows() > 0)
					{
						$this->model->update('ta__asistensi', $prepare, $where, 1);
						$item_id						= (isset($val['id']) ? $val['id'] : 0);
					}
					else
					{
						$this->model->insert('ta__asistensi', $prepare, 1);
						$item_id						= $this->model->insert_id();
					}
				}
			}
			if(isset($data['indikator']['hasil']) && is_array($data['indikator']['hasil']))
			{
				foreach($data['indikator']['hasil'] as $key => $val)
				{
					if(!$val['comments']) continue;
					$uraian								= $this->model->select('tolak_ukur')->get_where('ta__indikator', array('id' => $key), 1)->row('tolak_ukur');
					$prepare							= array
					(
						'id_keg_sub'					=> $this->_sub_kegiatan,
						'jenis'							=> 2,
						'id_jenis'						=> $key,
						'jenis_indikator'				=> 3,
						'uraian'						=> ($uraian ? $uraian : ''),
						'comments'						=> (isset($val['comments']) ? $val['comments'] : null),
						'id_operator'					=> get_userdata('user_id'),
						'operator'						=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
						'tanggal'						=> date('Y-m-d H:i:s')
					);
					$where								= array
					(
						'id'							=> (isset($val['id']) ? $val['id'] : 0),
						'id_keg_sub'					=> $this->_sub_kegiatan,
						'jenis'							=> 2,
						'id_jenis'						=> $key,
						'jenis_indikator'				=> 3,
						'id_operator'					=> get_userdata('user_id')
					);
					if($this->model->get_where('ta__asistensi', $where)->num_rows() > 0)
					{
						$this->model->update('ta__asistensi', $prepare, $where, 1);
						$item_id						= (isset($val['id']) ? $val['id'] : 0);
					}
					else
					{
						$this->model->insert('ta__asistensi', $prepare, 1);
						$item_id						= $this->model->insert_id();
					}
				}
			}
		}
		if(isset($data['belanja']) && is_array($data['belanja']) && sizeof($data['belanja']) > 0)
		{
			foreach($data['belanja'] as $key => $val)
			{
				if(!$val['comments']) continue;
				$uraian									= $this->model->select('CONCAT(ref__rek_1.kd_rek_1, ".", ref__rek_2.kd_rek_2 ,".", ref__rek_3.kd_rek_3, ".", ref__rek_4.kd_rek_4, ".", ref__rek_5.kd_rek_5, " ", ref__rek_5.uraian) AS uraian')->join('ref__rek_4', 'ref__rek_4.id = ref__rek_5.id_ref_rek_4')->join('ref__rek_3', 'ref__rek_3.id = ref__rek_4.id_ref_rek_3')->join('ref__rek_2', 'ref__rek_2.id = ref__rek_3.id_ref_rek_2')->join('ref__rek_1', 'ref__rek_1.id = ref__rek_2.id_ref_rek_1')->get_where('ref__rek_5', array('ref__rek_5.id' => $key), 1)->row('uraian');
				$prepare								= array
				(
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 3,
					'id_jenis'							=> $key,
					'jenis_indikator'					=> 0,
					'uraian'							=> ($uraian ? $uraian : ''),
					'comments'							=> (isset($val['comments']) ? $val['comments'] : null),
					'id_operator'						=> get_userdata('user_id'),
					'operator'							=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
					'tanggal'							=> date('Y-m-d H:i:s')
				);
				$where									= array
				(
					'id'								=> (isset($val['id']) ? $val['id'] : 0),
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 3,
					'id_jenis'							=> $key,
					'jenis_indikator'					=> 0,
					'id_operator'						=> get_userdata('user_id')
				);
				if($this->model->get_where('ta__asistensi', $where)->num_rows() > 0)
				{
					$this->model->update('ta__asistensi', $prepare, $where, 1);
					$item_id							= (isset($val['id']) ? $val['id'] : 0);
				}
				else
				{
					$this->model->insert('ta__asistensi', $prepare, 1);
					$item_id							= $this->model->insert_id();
				}
			}
		}
		if(isset($data['belanja_sub']) && is_array($data['belanja_sub']) && sizeof($data['belanja_sub']) > 0)
		{
			foreach($data['belanja_sub'] as $key => $val)
			{
				if(!$val['comments']) continue;
				$uraian									= $this->model->select('CONCAT(ref__rek_1.kd_rek_1, ".", ref__rek_2.kd_rek_2 ,".", ref__rek_3.kd_rek_3, ".", ref__rek_4.kd_rek_4, ".", ref__rek_5.kd_rek_5, ".[", ta__belanja_sub.kd_belanja_sub, "] ", ta__belanja_sub.uraian) AS uraian')->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja')->join('ref__rek_6', 'ref__rek_6.id = ta__belanja.id_rek_6')->join('ref__rek_5', 'ref__rek_5.id = ref__rek_6.id_ref_rek_5')->join('ref__rek_4', 'ref__rek_4.id = ref__rek_5.id_ref_rek_4')->join('ref__rek_3', 'ref__rek_3.id = ref__rek_4.id_ref_rek_3')->join('ref__rek_2', 'ref__rek_2.id = ref__rek_3.id_ref_rek_2')->join('ref__rek_1', 'ref__rek_1.id = ref__rek_2.id_ref_rek_1')->get_where('ta__belanja_sub', array('ta__belanja_sub.id' => $key), 1)->row('uraian');
				$prepare								= array
				(
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 4,
					'id_jenis'							=> $key,
					'jenis_indikator'					=> 0,
					'uraian'							=> ($uraian ? $uraian : ''),
					'comments'							=> (isset($val['comments']) ? $val['comments'] : null),
					'id_operator'						=> get_userdata('user_id'),
					'operator'							=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
					'tanggal'							=> date('Y-m-d H:i:s')
				);
				$where									= array
				(
					'id'								=> (isset($val['id']) ? $val['id'] : 0),
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 4,
					'id_jenis'							=> $key,
					'jenis_indikator'					=> 0,
					'id_operator'						=> get_userdata('user_id')
				);
				if($this->model->get_where('ta__asistensi', $where)->num_rows() > 0)
				{
					$this->model->update('ta__asistensi', $prepare, $where, 1);
					$item_id							= (isset($val['id']) ? $val['id'] : 0);
				}
				else
				{
					$this->model->insert('ta__asistensi', $prepare, 1);
					$item_id							= $this->model->insert_id();
				}
			}
		}
		if(isset($data['belanja_rinc']) && is_array($data['belanja_rinc']) && sizeof($data['belanja_rinc']) > 0)
		{
			foreach($data['belanja_rinc'] as $key => $val)
			{
				if(!$val['comments']) continue;
				$uraian									= $this->model->select('CONCAT(ref__rek_1.kd_rek_1, ".", ref__rek_2.kd_rek_2 ,".", ref__rek_3.kd_rek_3, ".", ref__rek_4.kd_rek_4, ".", ref__rek_5.kd_rek_5, ".[", ta__belanja_sub.kd_belanja_sub, ".", ta__belanja_rinci.kd_belanja_rinci, "] ", ta__belanja_rinci.uraian) AS uraian')->join('ta__belanja_sub', 'ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub')->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja')->join('ref__rek_6', 'ref__rek_6.id = ta__belanja.id_rek_6')->join('ref__rek_5', 'ref__rek_5.id = ref__rek_6.id_ref_rek_5')->join('ref__rek_4', 'ref__rek_4.id = ref__rek_5.id_ref_rek_4')->join('ref__rek_3', 'ref__rek_3.id = ref__rek_4.id_ref_rek_3')->join('ref__rek_2', 'ref__rek_2.id = ref__rek_3.id_ref_rek_2')->join('ref__rek_1', 'ref__rek_1.id = ref__rek_2.id_ref_rek_1')->get_where('ta__belanja_rinci', array('ta__belanja_rinci.id' => $key), 1)->row('uraian');
				$prepare								= array
				(
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 5,
					'id_jenis'							=> $key,
					'jenis_indikator'					=> 0,
					'uraian'							=> ($uraian ? $uraian : ''),
					'comments'							=> (isset($val['comments']) ? $val['comments'] : null),
					'id_operator'						=> get_userdata('user_id'),
					'operator'							=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
					'tanggal'							=> date('Y-m-d H:i:s')
				);
				$where									= array
				(
					'id'								=> (isset($val['id']) ? $val['id'] : 0),
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 5,
					'id_jenis'							=> $key,
					'jenis_indikator'					=> 0,
					'id_operator'						=> get_userdata('user_id')
				);
				if($this->model->get_where('ta__asistensi', $where)->num_rows() > 0)
				{
					$this->model->update('ta__asistensi', $prepare, $where, 1);
					$item_id							= (isset($val['id']) ? $val['id'] : 0);
				}
				else
				{
					$this->model->insert('ta__asistensi', $prepare, 1);
					$item_id							= $this->model->insert_id();
				}
			}
		}
		if(isset($data['kesesuaian']) && is_array($data['kesesuaian']) && sizeof($data['kesesuaian']) > 0)
		{
			$comments									= (isset($data['kesesuaian'][0]['comments']) ? truncate($data['kesesuaian'][0]['comments']) : '');
			$id_kesesuaian								= (isset($data['kesesuaian'][0]['id']) ? $data['kesesuaian'][0]['id'] : 0);
			if($comments)
			{
				$prepare								= array
				(
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 6,
					'comments'							=> $comments,
					'id_operator'						=> get_userdata('user_id'),
					'operator'							=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
					'tanggal'							=> date('Y-m-d H:i:s')
				);
				if($this->model->get_where('ta__asistensi', array('id_keg_sub' => $this->_sub_kegiatan, 'jenis' => 6, 'id' => $id_kesesuaian), 1)->num_rows() > 0)
				{
					$this->model->update('ta__asistensi', $prepare, array('id_keg_sub' => $this->_sub_kegiatan, 'jenis' => 6, 'id' => $id_kesesuaian), 1);
					$item_id							= (isset($data['kesesuaian'][0]['id']) ? $data['kesesuaian'][0]['id'] : 0);
				}
				else
				{
					$this->model->insert('ta__asistensi', $prepare, 1);
					$item_id							= $this->model->insert_id();
				}
			}
		}
		if(isset($data['kelompok_sasaran']) && is_array($data['kelompok_sasaran']) && sizeof($data['kelompok_sasaran']) > 0)
		{
			$comments									= (isset($data['kelompok_sasaran'][0]['comments']) ? truncate($data['kelompok_sasaran'][0]['comments']) : '');
			$id_kesesuaian								= (isset($data['kelompok_sasaran'][0]['id']) ? $data['kelompok_sasaran'][0]['id'] : 0);
			if($comments)
			{
				$prepare								= array
				(
					'id_keg_sub'						=> $this->_sub_kegiatan,
					'jenis'								=> 7,
					'comments'							=> $comments,
					'id_operator'						=> get_userdata('user_id'),
					'operator'							=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
					'tanggal'							=> date('Y-m-d H:i:s')
				);
				if($this->model->get_where('ta__asistensi', array('id_keg_sub' => $this->_sub_kegiatan, 'jenis' => 7, 'id' => $id_kesesuaian), 1)->num_rows() > 0)
				{
					$this->model->update('ta__asistensi', $prepare, array('id_keg_sub' => $this->_sub_kegiatan, 'jenis' => 7, 'id' => $id_kesesuaian), 1);
					$item_id							= (isset($data['kelompok_sasaran'][0]['id']) ? $data['kelompok_sasaran'][0]['id'] : 0);
				}
				else
				{
					$this->model->insert('ta__asistensi', $prepare, 1);
					$item_id							= $this->model->insert_id();
				}
			}
		}
		
		$val											= $this->model->get_where('ta__asistensi', array('id' => $item_id), 1)->row();
		if($val)
		{
			make_json
			(
				array
				(
					'id'								=> $item_id,
					'tooltip'							=> $val->comments,
					'html'								=> '
						<li class="item-' . $val->id . '-wrapper">
							<div class="btn-group float-right">
								' . ($val->id_operator == get_userdata('user_id') ? '
								<a class="btn btn-default btn-xs update-comment prevent-close" data-text="' . $val->comments . '" data-id="' . $val->id . '">
									<i class="mdi mdi-square-edit-outline"></i>
								</a>
								' : '') . '
								<a class="btn btn-default btn-xs remove-comment prevent-close" data-id="' . $val->id . '">
									<i class="mdi mdi-trash-can"></i>
								</a>
							</div>
							<b>
								' . $val->operator . '
							</b>
							<small style="font-size:10px">(' . $val->tanggal . ')</small>
							<p class="text-sm item-' . $val->id . '">
								' . $val->comments . '
							</p>
						</li>
					'
				)
			);
		}
	}
	
	private function _remove_comment()
	{
		if($this->model->delete('ta__asistensi', array('id' => $this->input->post('id'), 'id_operator' => get_userdata('user_id')), 1))
		{
			return make_json(array('status' => 200));
		}
		else
		{
			return make_json(array('status' => 500));
		}
	}
	
	private function _existing()
	{
		$query											= $this->model->get_where('ta__asistensi', array('id_keg_sub' => $this->_sub_kegiatan))->result_array();
		$output											= array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$jns									= $val['id_jenis'];
				$ind									= (1 == $val['jenis_indikator'] ? 'masukan' : (2 == $val['jenis_indikator'] ? 'keluaran' : 'hasil'));
				if(1 == $val['jenis'])
				{
					$output['capaian_program'][$jns]	= $val;
				}
				elseif(2 == $val['jenis'])
				{
					$output['indikator'][$ind][$jns]	= $val;
				}
				elseif(3 == $val['jenis'])
				{
					$output['belanja'][$jns]			= $val;
				}
				elseif(4 == $val['jenis'])
				{
					$output['belanja_sub'][$jns]		= $val;
				}
				else
				{
					$output['belanja_rinc'][$jns]		= $val;
				}
			}
		}
		return $output;
	}
	
	private function _rka_sub_kegiatan($tahun = null, $kegiatan = null, $sub_kegiatan = null)
	{
		$daerah_query									= $this->model->select('nama_pemda, nama_daerah')->get('app__settings')->row();
		$header_query									= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__kegiatan.kd_keg,
				
				ref__unit.nama_jabatan,
				ref__unit.nama_pejabat,
				ref__unit.nip_pejabat,
				
				ta__program_capaian.tolak_ukur,
				ta__program_capaian.tahun_3_target AS target,
				ta__program_capaian.tahun_3_satuan AS satuan,
				
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit,
				ref__sub.nm_sub,
				ref__program.nm_program,
				ta__kegiatan.kegiatan,
				ta__kegiatan_sub.kd_keg_sub,
				ta__kegiatan_sub.kegiatan_sub,
				ta__kegiatan_sub.pagu,
				ta__kegiatan_sub.pagu_1,
				ta__kegiatan_sub.kelompok_sasaran,
				ta__kegiatan_sub.waktu_pelaksanaan_mulai,
				ta__kegiatan_sub.waktu_pelaksanaan_sampai,
				ta__kegiatan_sub.map_address,
				ta__kegiatan_sub.alamat_detail,
				ta__kegiatan_sub.kelurahan,
				ta__kegiatan_sub.kecamatan,
				ta__kegiatan_sub.id_jenis_anggaran,
				ta__kegiatan_sub.pilihan,
				sub_kegiatan_pagu.total_anggaran_sub_kegiatan,
				ta__model.nm_model
			FROM
				ta__kegiatan_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__kegiatan_sub.id_keg
			LEFT JOIN ta__program_capaian ON ta__program_capaian.id = ta__kegiatan.capaian_program
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ta__model ON ta__model.id = ta__kegiatan_sub.pilihan
			LEFT JOIN (
				SELECT
					ta__belanja.id_keg_sub,
					Sum(ta__belanja_rinci.total) AS total_anggaran_sub_kegiatan
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				WHERE
					ta__kegiatan_sub.id = ' . $sub_kegiatan . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__belanja.id_keg_sub
				LIMIT 1
			) AS sub_kegiatan_pagu ON sub_kegiatan_pagu.id_keg_sub = ta__kegiatan_sub.id
			WHERE
				ta__kegiatan_sub.id = ' . $sub_kegiatan . '
			AND ta__kegiatan_sub.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$sumber_dana_query									= $this->db->query
		('
			SELECT DISTINCT
				ref__sumber_dana_rek_6.nama_sumber_dana
			FROM
				ta__belanja_rinci
			INNER JOIN ta__belanja_sub ON ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub
			INNER JOIN ta__belanja ON ta__belanja.id = ta__belanja_sub.id_belanja
			INNER JOIN ref__sumber_dana_rek_6 ON ta__belanja_rinci.id_sumber_dana = ref__sumber_dana_rek_6.id
			WHERE
				ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
		')
		->row();
		$capaian_program_query									= $this->model
		->query
		('
			SELECT
			ta__program_capaian.id,
			jumlah_capaian_program.jumlah_capaian_program,
			ta__program_capaian.kode,
			ta__program_capaian.tolak_ukur,
			ta__program_capaian.tahun_2_target AS target,
			ta__program_capaian.tahun_2_satuan AS satuan
			FROM
			ta__program_capaian
			INNER JOIN ta__program ON ta__program_capaian.id_prog = ta__program.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_prog = ta__program.id
			LEFT JOIN
			(
				SELECT
				ta__kegiatan.id,
				count(ta__program_capaian.id) AS jumlah_capaian_program
				FROM
				ta__program_capaian
				INNER JOIN ta__program ON ta__program_capaian.id_prog = ta__program.id
				INNER JOIN ta__kegiatan ON ta__kegiatan.id_prog = ta__program.id
				WHERE
				ta__kegiatan.id = ' . $kegiatan . '
			) AS jumlah_capaian_program ON jumlah_capaian_program.id = ta__kegiatan.id 
			WHERE
			ta__kegiatan.id = ' . $kegiatan . '
		')
		->result_array();
		$indikator_kegiatan_query							= $this->db->query
		('
			SELECT
				ta__indikator.id,
				ta__indikator.jns_indikator,
				ta__indikator.kd_indikator,
				ta__indikator.tolak_ukur,
				ta__indikator.target,
				ta__indikator.satuan
			FROM
				ta__indikator
			WHERE
				ta__indikator.id_keg = ' . $kegiatan . '
			ORDER BY
				ta__indikator.jns_indikator ASC,
				ta__indikator.kd_indikator ASC
		')
		->result();
		$indikator_sub_kegiatan_query						= $this->db->query
		('
			SELECT
				ta__indikator_sub.jns_indikator,
				ta__indikator_sub.kd_indikator,
				ta__indikator_sub.tolak_ukur,
				ta__indikator_sub.target,
				ta__indikator_sub.satuan
			FROM
				ta__indikator_sub
			WHERE
				ta__indikator_sub.id_keg_sub = ' . $sub_kegiatan . '
			ORDER BY
				ta__indikator_sub.jns_indikator ASC,
				ta__indikator_sub.kd_indikator ASC
		')
		->result();
		$belanja_query									= $this->db->query
		('
			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ref__rek_6.id AS id_rek_6,
				ta__belanja_sub.id AS id_belanja_sub,
				ta__belanja_rinci.id AS id_belanja_rinci,
				
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ref__rek_6.keterangan,
				ta__belanja_sub.kd_belanja_sub,
				ta__belanja_rinci.kd_belanja_rinci,
				
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				ref__rek_4.uraian AS nm_rek_4,
				ref__rek_5.uraian AS nm_rek_5,
				ref__rek_6.uraian AS nm_rek_6,
				ta__belanja_sub.uraian AS nama_sub_rincian,
				ta__belanja_rinci.uraian AS nama_rincian,
				ta__belanja_rinci.vol_1,
				ta__belanja_rinci.vol_2,
				ta__belanja_rinci.vol_3,
				ta__belanja_rinci.vol_123,
				ta__belanja_rinci.satuan_1,
				ta__belanja_rinci.satuan_2,
				ta__belanja_rinci.satuan_3,
				ta__belanja_rinci.satuan_123,
				ta__belanja_rinci.nilai,
				ta__belanja_rinci.total,
				
				rek_1.subtotal_rek_1,
				rek_2.subtotal_rek_2,
				rek_3.subtotal_rek_3,
				rek_4.subtotal_rek_4,
				rek_5.subtotal_rek_5,
				rek_6.subtotal_rek_6,
				belanja_sub.subtotal_rinci
			FROM
				ta__belanja_rinci
			INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ref__rek_2.id_ref_rek_1,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_1
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				WHERE
					ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_2
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				WHERE
					ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			LEFT JOIN (
				SELECT
					ref__rek_4.id_ref_rek_3,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_3
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				WHERE
					ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
				GROUP BY
					ref__rek_4.id_ref_rek_3
			) AS rek_3 ON rek_3.id_ref_rek_3 = ref__rek_3.id
			LEFT JOIN (
				SELECT
					ref__rek_5.id_ref_rek_4,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_4
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				WHERE
					ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
				GROUP BY
					ref__rek_5.id_ref_rek_4
			) AS rek_4 ON rek_4.id_ref_rek_4 = ref__rek_4.id
			LEFT JOIN (
				SELECT
					ref__rek_6.id_ref_rek_5,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_5
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				WHERE
					ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
				GROUP BY
					ref__rek_6.id_ref_rek_5
			) AS rek_5 ON rek_5.id_ref_rek_5 = ref__rek_5.id
			LEFT JOIN (
				SELECT
					ta__belanja_sub.id_belanja,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_6
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				WHERE
					ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
				GROUP BY
					ta__belanja_sub.id_belanja
			) AS rek_6 ON rek_6.id_belanja = ta__belanja_sub.id_belanja
			LEFT JOIN (
				SELECT
					ta__belanja_rinci.id_belanja_sub,
					Sum(ta__belanja_rinci.total) AS subtotal_rinci
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				WHERE
					ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
				GROUP BY
					ta__belanja_rinci.id_belanja_sub
			) AS belanja_sub ON belanja_sub.id_belanja_sub = ta__belanja_sub.id
			WHERE
				ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC,
				ref__rek_4.kd_rek_4 ASC,
				ref__rek_5.kd_rek_5 ASC,
				ref__rek_6.kd_rek_6 ASC,
				ta__belanja_sub.kd_belanja_sub ASC,
				ta__belanja_rinci.kd_belanja_rinci ASC		
		')
		->result();
		$tim_anggaran_query								= $this->db->query
		('
			SELECT
				ref__tim_anggaran.id,
				ref__tim_anggaran.kode,
				ref__tim_anggaran.nama_tim,
				ref__tim_anggaran.nip_tim,
				ref__tim_anggaran.jabatan_tim,
				ref__tim_anggaran.opd,
				ref__tim_anggaran.ttd
			FROM
				ref__tim_anggaran
			WHERE
				ref__tim_anggaran.status = 1 AND
				ref__tim_anggaran.tahun = ' . $tahun . '
			ORDER BY
				ref__tim_anggaran.kode
		')
		->result();
		$tanggal_query								= $this->db->query
		('
			SELECT
				ref__renja_jenis_anggaran.tanggal_rka
			FROM
				ref__renja_jenis_anggaran
			WHERE
				ref__renja_jenis_anggaran.kode = ' . $header_query->id_jenis_anggaran . '
			LIMIT 1
		')
		->row();
		$approval									= $this->model->query
		('
			SELECT
				ta__asistensi_setuju.perencanaan,
				ta__asistensi_setuju.waktu_verifikasi_perencanaan,
				ta__asistensi_setuju.nama_operator_perencanaan,
				ta__asistensi_setuju.keuangan,
				ta__asistensi_setuju.waktu_verifikasi_keuangan,
				ta__asistensi_setuju.nama_operator_keuangan,
				ta__asistensi_setuju.setda,
				ta__asistensi_setuju.waktu_verifikasi_setda,
				ta__asistensi_setuju.nama_operator_setda,
				ta__asistensi_setuju.ttd_1,
				ta__asistensi_setuju.ttd_2,
				ta__asistensi_setuju.ttd_3
			FROM
				ta__asistensi_setuju
			WHERE
				ta__asistensi_setuju.id_keg_sub = ' . $sub_kegiatan . ' AND
				ta__asistensi_setuju.kode_perubahan = ' . $header_query->id_jenis_anggaran . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'daerah'								=> $daerah_query,
			'header'								=> $header_query,
			'sumber_dana'							=> $sumber_dana_query,
			'capaian_program'						=> $capaian_program_query,
			'indikator'								=> $indikator_kegiatan_query,
			'indikator_sub'							=> $indikator_sub_kegiatan_query,
			'belanja'								=> $belanja_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'tanggal'								=> $tanggal_query,
			'verified'								=> $approval
		);
		return $output;
	}
	
	public function get_ttd($id = 0)
	{
		$ttd										= $this->model->select('ttd')->get_where('ref__tim_anggaran', array('id' => $id), 1)->row('ttd');
		if($ttd)
		{
			$ttd									= '<img src="' . get_image('anggaran', $ttd) . '" width="80" class="img-responsive" />';
		}
		return $ttd;
	}
	
	public function get_thread($type = 0, $id = 0, $indikator = 0, $tooltip = null)
	{
		if($indikator)
		{
			$this->model->where('jenis_indikator', $indikator);
		}
		if($id)
		{
			$this->model->where('id_jenis', $id);
		}
		
		$query										= $this->model->select('id, comments, tanggapan, id_operator, operator, tanggal')->order_by('tanggal', 'asc')->get_where('ta__asistensi', array('jenis' => $type, 'id_keg_sub' => $this->_sub_kegiatan))->result();
		
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				if(!$val->comments) continue;
				if('tooltip' == $tooltip)
				{
					$output							.= htmlspecialchars
					('
						<div class="form-group tooltip-' . $val->id . '-wrapper">
							<p class="text-sm comment-text" style="margin-bottom:0">
								Komentar: <span class="tooltip-' . $val->id . '-wrapper">' . $val->comments . '</span>
							</p>
							<p class="text-sm text-aqua">
								Tanggapan: ' . $val->tanggapan . '
							</p>
						</div>
					');
				}
				else
				{
					$output							.= '
						<li class="item-' . $val->id . '-wrapper">
							<div class="btn-group float-right">
								' . ($val->id_operator == get_userdata('user_id') ? '
								<a class="btn btn-default btn-xs update-comment prevent-close" data-text="' . $val->comments . '" data-id="' . $val->id . '">
									<i class="mdi mdi-square-edit-outline"></i>
								</a>
								' : '') . '
								<a class="btn btn-default btn-xs remove-comment prevent-close" data-id="' . $val->id . '">
									<i class="mdi mdi-trash-can"></i>
								</a>
							</div>
							<b>
								' . $val->operator . '
							</b>
							<small style="font-size:10px">(' . $val->tanggal . ')</small>
							<p class="text-sm item-' . $val->id . '">
								' . $val->comments . '
							</p>
						</li>
					';
				}
			}
		}
		return $output;
	}
}