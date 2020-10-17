<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Perubahan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_id_keg								= $this->input->get('id_keg');
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
			->select('jenis_anggaran')
			->limit(1)
			->get_where('ta__kegiatan', array('id' => $this->_id_keg))
			->row('jenis_anggaran');
		//print($jenis_anggaran);exit;
		if($jenis_anggaran < 2)
		{
			return generateMessages(301, 'Kegiatan Murni', go_to('../data'));
		}
		if($this->input->post())
		{
			return $this->_process_data();
		}
		$this->set_title('Pengecekan data')
		->set_icon('fa fa-check-circle-o')
		->set_output
		(
			array
			(
				'results'								=> $this->_rka_221($this->_id_keg),
				'existing'								=> $this->_existing()
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
					'id_keg'							=> $this->_id_keg,
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
					'id_keg'							=> $this->_id_keg,
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
						'id_keg'						=> $this->_id_keg,
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
						'id_keg'						=> $this->_id_keg,
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
						'id_keg'						=> $this->_id_keg,
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
						'id_keg'						=> $this->_id_keg,
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
						'id_keg'						=> $this->_id_keg,
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
						'id_keg'						=> $this->_id_keg,
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
					'id_keg'							=> $this->_id_keg,
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
					'id_keg'							=> $this->_id_keg,
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
				$uraian									= $this->model->select('CONCAT(ref__rek_1.kd_rek_1, ".", ref__rek_2.kd_rek_2 ,".", ref__rek_3.kd_rek_3, ".", ref__rek_4.kd_rek_4, ".", ref__rek_5.kd_rek_5, ".[", ta__belanja_sub.kd_belanja_sub, "] ", ta__belanja_sub.uraian) AS uraian')->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja')->join('ref__rek_5', 'ref__rek_5.id = ta__belanja.id_rek_5')->join('ref__rek_4', 'ref__rek_4.id = ref__rek_5.id_ref_rek_4')->join('ref__rek_3', 'ref__rek_3.id = ref__rek_4.id_ref_rek_3')->join('ref__rek_2', 'ref__rek_2.id = ref__rek_3.id_ref_rek_2')->join('ref__rek_1', 'ref__rek_1.id = ref__rek_2.id_ref_rek_1')->get_where('ta__belanja_sub', array('ta__belanja_sub.id' => $key), 1)->row('uraian');
				$prepare								= array
				(
					'id_keg'							=> $this->_id_keg,
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
					'id_keg'							=> $this->_id_keg,
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
				$uraian									= $this->model->select('CONCAT(ref__rek_1.kd_rek_1, ".", ref__rek_2.kd_rek_2 ,".", ref__rek_3.kd_rek_3, ".", ref__rek_4.kd_rek_4, ".", ref__rek_5.kd_rek_5, ".[", ta__belanja_sub.kd_belanja_sub, ".", ta__belanja_rinc.kd_belanja_rinc, "] ", ta__belanja_rinc.uraian) AS uraian')->join('ta__belanja_sub', 'ta__belanja_sub.id = ta__belanja_rinc.id_belanja_sub')->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja')->join('ref__rek_5', 'ref__rek_5.id = ta__belanja.id_rek_5')->join('ref__rek_4', 'ref__rek_4.id = ref__rek_5.id_ref_rek_4')->join('ref__rek_3', 'ref__rek_3.id = ref__rek_4.id_ref_rek_3')->join('ref__rek_2', 'ref__rek_2.id = ref__rek_3.id_ref_rek_2')->join('ref__rek_1', 'ref__rek_1.id = ref__rek_2.id_ref_rek_1')->get_where('ta__belanja_rinc', array('ta__belanja_rinc.id' => $key), 1)->row('uraian');
				$prepare								= array
				(
					'id_keg'							=> $this->_id_keg,
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
					'id_keg'							=> $this->_id_keg,
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
					'id_keg'							=> $this->_id_keg,
					'jenis'								=> 6,
					'comments'							=> $comments,
					'id_operator'						=> get_userdata('user_id'),
					'operator'							=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
					'tanggal'							=> date('Y-m-d H:i:s')
				);
				if($this->model->get_where('ta__asistensi', array('id_keg' => $this->_id_keg, 'jenis' => 6, 'id' => $id_kesesuaian), 1)->num_rows() > 0)
				{
					$this->model->update('ta__asistensi', $prepare, array('id_keg' => $this->_id_keg, 'jenis' => 6, 'id' => $id_kesesuaian), 1);
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
					'id_keg'							=> $this->_id_keg,
					'jenis'								=> 7,
					'comments'							=> $comments,
					'id_operator'						=> get_userdata('user_id'),
					'operator'							=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
					'tanggal'							=> date('Y-m-d H:i:s')
				);
				if($this->model->get_where('ta__asistensi', array('id_keg' => $this->_id_keg, 'jenis' => 7, 'id' => $id_kesesuaian), 1)->num_rows() > 0)
				{
					$this->model->update('ta__asistensi', $prepare, array('id_keg' => $this->_id_keg, 'jenis' => 7, 'id' => $id_kesesuaian), 1);
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
							<div class="btn-group pull-right">
								' . ($val->id_operator == get_userdata('user_id') ? '
								<a class="btn btn-default btn-xs update-comment prevent-close" data-text="' . $val->comments . '" data-id="' . $val->id . '">
									<i class="fa fa-edit"></i>
								</a>
								' : '') . '
								<a class="btn btn-default btn-xs remove-comment prevent-close" data-id="' . $val->id . '">
									<i class="fa fa-trash"></i>
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
		$query											= $this->model->get_where('ta__asistensi', array('id_keg' => $this->_id_keg))->result_array();
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
	
	private function _rka_221($kegiatan = null)
	{
		$kode_perubahan								= 9;
		$daerah										= $this->db
			->select('nama_pemda, nama_daerah')
			->get_where('app__settings')
			->row();
		$header_query									= $this->model
		->query
		('
			SELECT
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				ref__program.kd_program AS kode_program,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ta__program.kd_id_prog AS kode_id_prog,
				ta__kegiatan.kd_keg AS kode_kegiatan,
				ref__urusan.nm_urusan AS nama_urusan,
				ref__bidang.nm_bidang AS nama_bidang,
				ref__program.nm_program AS nama_program,
				ref__unit.nm_unit AS nama_unit,
				ref__sub.nm_sub AS nama_sub,
				ref__unit.nama_jabatan,
				ref__unit.nama_pejabat,
				ref__unit.nip_pejabat,
				ta__kegiatan.kegiatan AS nama_kegiatan,
				ta__kegiatan.capaian_program,
				ta__kegiatan.pilihan,
				ta__kegiatan.map_address,
				ta__kegiatan.alamat_detail,
				ta__kegiatan.kelompok_sasaran,
				ta__kegiatan.waktu_pelaksanaan,
				ta__kegiatan.pagu,
				ta__model.nm_model,
				ta__kegiatan.latar_belakang_perubahan
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ta__model ON ta__model.id = ta__kegiatan.id_model
			WHERE
				ta__kegiatan.id = ' . $kegiatan . '
				AND ta__kegiatan.tahun = ' . get_userdata('year') . '			
		')
		->result_array();
		$sumber_dana_query									= $this->db->query
		('
			SELECT DISTINCT
				ref__sumber_dana.nama_sumber_dana
			FROM
				ta__belanja
			INNER JOIN ref__sumber_dana ON ta__belanja.id_sumber_dana = ref__sumber_dana.id
			WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
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
		$this->db->query('DROP TABLE IF EXISTS tmp__rkap_indikator');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_indikator
			(
				id int(5),
				jns_indikator int(5),
				kd_indikator int(5),
				tolak_ukur_sebelum varchar(255) DEFAULT NULL,
				target_sebelum decimal(19,2),
				satuan_sebelum varchar(255),
				tolak_ukur_setelah varchar(255) DEFAULT NULL,
				target_setelah decimal(19,2),
				satuan_setelah varchar(255),
				urutan tinyint(1)
			)
		');
		$tmp_indikator_query									= $this->db->query
		('
			SELECT
				ta__indikator_arsip.id_indikator AS id,
				ref__indikator.id AS jns_indikator,
				ta__indikator_arsip.kd_indikator,
				ta__indikator_arsip.tolak_ukur AS tolak_ukur_sebelum,
				ta__indikator_arsip.target AS target_sebelum,
				ta__indikator_arsip.satuan AS satuan_sebelum,
				NULL AS tolak_ukur_setelah,
				0 AS target_setelah,
				NULL AS satuan_setelah,
				1 AS urutan
			FROM
				ta__indikator_arsip
			INNER JOIN ref__indikator ON ref__indikator.kd_indikator = ta__indikator_arsip.jns_indikator
			WHERE
				ta__indikator_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
				ta__indikator_arsip.id_keg = ' . $kegiatan . '
			
			UNION
			
			SELECT
				ta__indikator.id,
				ta__indikator.jns_indikator,
				ta__indikator.kd_indikator,
				NULL AS tolak_ukur_sebelum,
				0 AS target_sebelum,
				NULL AS satuan_sebelum,
				ta__indikator.tolak_ukur AS tolak_ukur_setelah,
				ta__indikator.target AS target_setelah,
				ta__indikator.satuan AS satuan_setelah,
				2 AS urutan
			FROM
				ta__indikator
			WHERE
				ta__indikator.id_keg = ' . $kegiatan . '
			ORDER BY
				jns_indikator ASC,
				kd_indikator ASC,
				urutan ASC
		')
		->result_array();
		if($tmp_indikator_query)
		{
			$this->db->insert_batch('tmp__rkap_indikator', $tmp_indikator_query, sizeof($tmp_indikator_query));
		}
		//print_r($tmp_indikator_query);exit;
		/*$tmp__rkap_indikator									= $this->db
		->query
		('
			SELECT *
			FROM
				tmp__rkap_indikator
		')
		->result();
		print_r($tmp__rkap_indikator);exit;*/
		
		$indikator_query									= $this->db->query
		('
			SELECT
				tmp__rkap_indikator.id,
				tmp__rkap_indikator.jns_indikator,
				tmp__rkap_indikator.kd_indikator,
				SUM(tmp__rkap_indikator.target_sebelum) AS target_sebelum,
				tmp__rkap_indikator.satuan_sebelum,
				MIN(tmp__rkap_indikator.tolak_ukur_sebelum) AS tolak_ukur_sebelum,
				MIN(tmp__rkap_indikator.tolak_ukur_setelah) AS tolak_ukur_setelah,
				SUM(tmp__rkap_indikator.target_setelah) AS target_setelah,
				MIN(tmp__rkap_indikator.satuan_sebelum) AS satuan_sebelum,
				MIN(tmp__rkap_indikator.satuan_setelah) AS satuan_setelah,
				tmp__rkap_indikator.urutan
			FROM
				tmp__rkap_indikator
			GROUP BY
				tmp__rkap_indikator.jns_indikator,
				tmp__rkap_indikator.kd_indikator
			ORDER BY
				tmp__rkap_indikator.jns_indikator ASC,
				tmp__rkap_indikator.kd_indikator ASC,
				tmp__rkap_indikator.urutan ASC
		')
		->result_array();
		//print_r($indikator_query);exit;
		$this->db->query('DROP TABLE IF EXISTS tmp__rkap_belanja_1');
		$this->db->query('DROP TABLE IF EXISTS tmp__rkap_belanja_2');
		$this->db->query('DROP TABLE IF EXISTS tmp__rkap_belanja_3');
		$this->db->query('DROP TABLE IF EXISTS tmp__rkap_belanja_4');
		$this->db->query('DROP TABLE IF EXISTS tmp__rkap_belanja_5');
		$this->db->query('DROP TABLE IF EXISTS tmp__rkap_belanja_6');
		$this->db->query('DROP TABLE IF EXISTS tmp__rkap_belanja_7');
		
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_1
			(
				id_rek_1 int(10),
				id_rek_2 int(10),
				id_rek_3 int(10),
				id_rek_4 int(10),
				id_rek_5 int(10),
				id_belanja_sub int(10),
				id_belanja_rinc int(10),
				kd_rek_1 int(10),
				kd_rek_2 int(10),
				kd_rek_3 int(10),
				kd_rek_4 int(10),
				kd_rek_5 int(10),
				kd_belanja_sub int(10),
				kd_belanja_rinc int(10),
				nm_rek_1 varchar(255),
				nm_rek_2 varchar(255),
				nm_rek_3 varchar(255),
				nm_rek_4 varchar(255),
				nm_rek_5 varchar(255),
				keterangan varchar(255),
				nm_belanja_sub varchar(255),
				nm_belanja_rinc varchar(255),
				vol_1_sebelum decimal(19,2),
				vol_2_sebelum decimal(19,2),
				vol_3_sebelum decimal(19,2),
				vol_123_sebelum decimal(19,2),
				satuan_1_sebelum varchar(100),
				satuan_2_sebelum varchar(100),
				satuan_3_sebelum varchar(100),
				satuan_123_sebelum varchar(100),
				nilai_sebelum decimal(19,2),
				total_sebelum decimal(19,2),
				vol_1_setelah decimal(19,2),
				vol_2_setelah decimal(19,2),
				vol_3_setelah decimal(19,2),
				vol_123_setelah decimal(19,2),
				satuan_1_setelah varchar(100),
				satuan_2_setelah varchar(100),
				satuan_3_setelah varchar(100),
				satuan_123_setelah varchar(100),
				nilai_setelah decimal(19,2),
				total_setelah decimal(19,2),
				urutan tinyint(1)
			)
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_2
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), keterangan varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_3
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), keterangan varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_4
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), keterangan varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_5
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), keterangan varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_6
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), keterangan varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_7
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), keterangan varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		
		$tmp_belanja_query								= $this->db
		->query
		('
			SELECT
				ta__belanja_arsip.id_rek_1,
				ta__belanja_arsip.id_rek_2,
				ta__belanja_arsip.id_rek_3,
				ta__belanja_arsip.id_rek_4,
				ta__belanja_arsip.id_rek_5,
				ta__belanja_arsip.id_belanja_sub,
				ta__belanja_arsip.id_belanja_rinc,
				ta__belanja_arsip.kd_rek_1,
				ta__belanja_arsip.kd_rek_2,
				ta__belanja_arsip.kd_rek_3,
				ta__belanja_arsip.kd_rek_4,
				ta__belanja_arsip.kd_rek_5,
				ta__belanja_arsip.kd_belanja_sub,
				ta__belanja_arsip.kd_belanja_rinc,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				ref__rek_4.uraian AS nm_rek_4,
				ref__rek_5.uraian AS nm_rek_5,
				ref__rek_5.keterangan,
				ta__belanja_arsip.uraian_belanja_sub AS nm_belanja_sub,
				ta__belanja_arsip.uraian_belanja_rinc AS nm_belanja_rinc,
				ta__belanja_arsip.vol_1 AS vol_1_sebelum,
				ta__belanja_arsip.vol_2 AS vol_2_sebelum,
				ta__belanja_arsip.vol_3 AS vol_3_sebelum,
				ta__belanja_arsip.vol_123 AS vol_123_sebelum,
				ta__belanja_arsip.satuan_1 AS satuan_1_sebelum,
				ta__belanja_arsip.satuan_2 AS satuan_2_sebelum,
				ta__belanja_arsip.satuan_3 AS satuan_3_sebelum,
				ta__belanja_arsip.satuan_123 AS satuan_123_sebelum,
				ta__belanja_arsip.nilai AS nilai_sebelum,
				ta__belanja_arsip.total AS total_sebelum,
				0 AS vol_1_setelah,
				0 AS vol_2_setelah,
				0 AS vol_3_setelah,
				0 AS vol_123_setelah,
				0 AS satuan_1_setelah,
				0 AS satuan_2_setelah,
				0 AS satuan_3_setelah,
				0 AS satuan_123_setelah,
				0 AS nilai_setelah,
				0 AS total_setelah,
				1 AS urutan
			FROM
				ta__belanja_arsip
			INNER JOIN ref__rek_5 ON ref__rek_5.id = ta__belanja_arsip.id_rek_5
			INNER JOIN ref__rek_4 ON ref__rek_4.id = ta__belanja_arsip.id_rek_4
			INNER JOIN ref__rek_3 ON ref__rek_3.id = ta__belanja_arsip.id_rek_3
			INNER JOIN ref__rek_2 ON ref__rek_2.id = ta__belanja_arsip.id_rek_2
			INNER JOIN ref__rek_1 ON ref__rek_1.id = ta__belanja_arsip.id_rek_1
			WHERE
				ta__belanja_arsip.id_keg = ' . $kegiatan . ' AND
				ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . '

			UNION

			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ta__belanja_sub.id AS id_belanja_sub,
				ta__belanja_rinc.id AS id_belanja_rinc,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ta__belanja_sub.kd_belanja_sub,
				ta__belanja_rinc.kd_belanja_rinc,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				ref__rek_4.uraian AS nm_rek_4,
				ref__rek_5.uraian AS nm_rek_5,
				ref__rek_5.keterangan,
				ta__belanja_sub.uraian AS nm_belanja_sub,
				ta__belanja_rinc.uraian AS nm_belanja_rinc,
				0 AS vol_1_sebelum,
				0 AS vol_2_sebelum,
				0 AS vol_3_sebelum,
				0 AS vol_123_sebelum,
				0 AS satuan_1_sebelum,
				0 AS satuan_2_sebelum,
				0 AS satuan_3_sebelum,
				0 AS satuan_123_sebelum,
				0 AS nilai_sebelum,
				0 AS total_sebelum,
				ta__belanja_rinc.vol_1 AS vol_1_setelah,
				ta__belanja_rinc.vol_2 AS vol_2_setelah,
				ta__belanja_rinc.vol_3 AS vol_3_setelah,
				ta__belanja_rinc.vol_123 AS vol_123_setelah,
				ta__belanja_rinc.satuan_1 AS satuan_1_setelah,
				ta__belanja_rinc.satuan_2 AS satuan_2_setelah,
				ta__belanja_rinc.satuan_3 AS satuan_3_setelah,
				ta__belanja_rinc.satuan_123 AS satuan_123_setelah,
				ta__belanja_rinc.nilai AS nilai_setelah,
				ta__belanja_rinc.total AS total_setelah,
				2 AS urutan
			FROM
				ta__belanja_rinc
			INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
			ORDER BY
				kd_rek_1 ASC,
				kd_rek_2 ASC,
				kd_rek_3 ASC,
				kd_rek_4 ASC,
				kd_rek_5 ASC,
				kd_belanja_sub ASC,
				kd_belanja_rinc ASC,
				urutan ASC
		')
		->result_array();
		if($tmp_belanja_query)
		{
			$this->db->insert_batch('tmp__rkap_belanja_1', $tmp_belanja_query, sizeof($tmp_belanja_query));
			$this->db->insert_batch('tmp__rkap_belanja_2', $tmp_belanja_query, sizeof($tmp_belanja_query));
			$this->db->insert_batch('tmp__rkap_belanja_3', $tmp_belanja_query, sizeof($tmp_belanja_query));
			$this->db->insert_batch('tmp__rkap_belanja_4', $tmp_belanja_query, sizeof($tmp_belanja_query));
			$this->db->insert_batch('tmp__rkap_belanja_5', $tmp_belanja_query, sizeof($tmp_belanja_query));
			$this->db->insert_batch('tmp__rkap_belanja_6', $tmp_belanja_query, sizeof($tmp_belanja_query));
			$this->db->insert_batch('tmp__rkap_belanja_7', $tmp_belanja_query, sizeof($tmp_belanja_query));
		}
		//print_r($tmp_belanja_query);exit;
		/*$tmp__rkap_belanja									= $this->db
		->query
		('
			SELECT*
			FROM
				tmp__rkap_belanja
		')
		->result();
		print_r($tmp__rkap_belanja);exit;*/
		$belanja_query									= $this->db
		->query
		('
			SELECT
				tmp__rkap_belanja_1.id_rek_1,
				tmp__rkap_belanja_1.id_rek_2,
				tmp__rkap_belanja_1.id_rek_3,
				tmp__rkap_belanja_1.id_rek_4,
				tmp__rkap_belanja_1.id_rek_5,
				tmp__rkap_belanja_1.id_belanja_sub,
				tmp__rkap_belanja_1.id_belanja_rinc,
				tmp__rkap_belanja_1.kd_rek_1,
				tmp__rkap_belanja_1.kd_rek_2,
				tmp__rkap_belanja_1.kd_rek_3,
				tmp__rkap_belanja_1.kd_rek_4,
				tmp__rkap_belanja_1.kd_rek_5,
				tmp__rkap_belanja_1.kd_belanja_sub,
				tmp__rkap_belanja_1.kd_belanja_rinc,
				tmp__rkap_belanja_1.nm_rek_1,
				tmp__rkap_belanja_1.nm_rek_2,
				tmp__rkap_belanja_1.nm_rek_3,
				tmp__rkap_belanja_1.nm_rek_4,
				tmp__rkap_belanja_1.nm_rek_5,
				tmp__rkap_belanja_1.keterangan,
				tmp__rkap_belanja_1.nm_belanja_sub,
				tmp__rkap_belanja_1.nm_belanja_rinc,
				SUM(tmp__rkap_belanja_1.vol_1_sebelum) AS vol_1_sebelum,
				SUM(tmp__rkap_belanja_1.vol_2_sebelum) AS vol_2_sebelum,
				SUM(tmp__rkap_belanja_1.vol_3_sebelum) AS vol_3_sebelum,
				SUM(tmp__rkap_belanja_1.vol_123_sebelum) AS vol_123_sebelum,
				tmp__rkap_belanja_1.satuan_1_sebelum,
				tmp__rkap_belanja_1.satuan_2_sebelum,
				tmp__rkap_belanja_1.satuan_3_sebelum,
				if(tmp__rkap_belanja_1.satuan_123_sebelum = "0", "-", satuan_123_sebelum) AS satuan_123_sebelum,
				SUM(tmp__rkap_belanja_1.nilai_sebelum) as nilai_sebelum,
				SUM(tmp__rkap_belanja_1.total_sebelum) as total_sebelum,
				subtotal_rek_1.subtotal_rek_1_sebelum,
				subtotal_rek_2.subtotal_rek_2_sebelum,
				subtotal_rek_3.subtotal_rek_3_sebelum,
				subtotal_rek_4.subtotal_rek_4_sebelum,
				subtotal_rek_5.subtotal_rek_5_sebelum,
				subtotal_sub.subtotal_sub_sebelum,
				SUM(tmp__rkap_belanja_1.vol_1_setelah) AS vol_1_setelah,
				SUM(tmp__rkap_belanja_1.vol_2_setelah) AS vol_2_setelah,
				SUM(tmp__rkap_belanja_1.vol_3_setelah) AS vol_3_setelah,
				SUM(tmp__rkap_belanja_1.vol_123_setelah) AS vol_123_setelah,
				if(satuan_1_setelah = "0", satuan_1_sebelum, satuan_1_setelah) AS satuan_1_setelah,
				if(satuan_2_setelah = "0", satuan_2_sebelum, satuan_2_setelah) AS satuan_2_setelah,
				if(satuan_3_setelah = "0", satuan_3_sebelum, satuan_3_setelah) AS satuan_3_setelah,
				if(satuan_123_setelah = "0", satuan_123_sebelum, satuan_123_setelah) AS satuan_123_setelah,
				SUM(tmp__rkap_belanja_1.nilai_setelah) as nilai_setelah,
				SUM(tmp__rkap_belanja_1.total_setelah) as total_setelah,
				subtotal_rek_1.subtotal_rek_1_setelah,
				subtotal_rek_2.subtotal_rek_2_setelah,
				subtotal_rek_3.subtotal_rek_3_setelah,
				subtotal_rek_4.subtotal_rek_4_setelah,
				subtotal_rek_5.subtotal_rek_5_setelah,
				subtotal_sub.subtotal_sub_setelah
			FROM
				tmp__rkap_belanja_1
			LEFT JOIN (
				SELECT
					tmp__rkap_belanja_2.kd_rek_1,
					SUM(tmp__rkap_belanja_2.total_sebelum) AS subtotal_rek_1_sebelum,
					SUM(tmp__rkap_belanja_2.total_setelah) AS subtotal_rek_1_setelah
				FROM
					tmp__rkap_belanja_2
				GROUP BY
					tmp__rkap_belanja_2.kd_rek_1
			) AS subtotal_rek_1 ON 
				subtotal_rek_1.kd_rek_1 = tmp__rkap_belanja_1.kd_rek_1
			LEFT JOIN (
				SELECT
					tmp__rkap_belanja_3.kd_rek_1,
					tmp__rkap_belanja_3.kd_rek_2,
					SUM(tmp__rkap_belanja_3.total_sebelum) AS subtotal_rek_2_sebelum,
					SUM(tmp__rkap_belanja_3.total_setelah) AS subtotal_rek_2_setelah
				FROM
					tmp__rkap_belanja_3
				GROUP BY
					tmp__rkap_belanja_3.kd_rek_1,
					tmp__rkap_belanja_3.kd_rek_2
			) AS subtotal_rek_2 ON 
				subtotal_rek_2.kd_rek_1 = tmp__rkap_belanja_1.kd_rek_1 AND
				subtotal_rek_2.kd_rek_2 = tmp__rkap_belanja_1.kd_rek_2
			LEFT JOIN (
				SELECT
					tmp__rkap_belanja_4.kd_rek_1,
					tmp__rkap_belanja_4.kd_rek_2,
					tmp__rkap_belanja_4.kd_rek_3,
					SUM(tmp__rkap_belanja_4.total_sebelum) AS subtotal_rek_3_sebelum,
					SUM(tmp__rkap_belanja_4.total_setelah) AS subtotal_rek_3_setelah
				FROM
					tmp__rkap_belanja_4
				GROUP BY
					tmp__rkap_belanja_4.kd_rek_1,
					tmp__rkap_belanja_4.kd_rek_2,
					tmp__rkap_belanja_4.kd_rek_3
			) AS subtotal_rek_3 ON 
				subtotal_rek_3.kd_rek_1 = tmp__rkap_belanja_1.kd_rek_1 AND
				subtotal_rek_3.kd_rek_2 = tmp__rkap_belanja_1.kd_rek_2 AND
				subtotal_rek_3.kd_rek_3 = tmp__rkap_belanja_1.kd_rek_3
			LEFT JOIN (
				SELECT
					tmp__rkap_belanja_5.kd_rek_1,
					tmp__rkap_belanja_5.kd_rek_2,
					tmp__rkap_belanja_5.kd_rek_3,
					tmp__rkap_belanja_5.kd_rek_4,
					SUM(tmp__rkap_belanja_5.total_sebelum) AS subtotal_rek_4_sebelum,
					SUM(tmp__rkap_belanja_5.total_setelah) AS subtotal_rek_4_setelah
				FROM
					tmp__rkap_belanja_5
				GROUP BY
					tmp__rkap_belanja_5.kd_rek_1,
					tmp__rkap_belanja_5.kd_rek_2,
					tmp__rkap_belanja_5.kd_rek_3,
					tmp__rkap_belanja_5.kd_rek_4
			) AS subtotal_rek_4 ON 
				subtotal_rek_4.kd_rek_1 = tmp__rkap_belanja_1.kd_rek_1 AND
				subtotal_rek_4.kd_rek_2 = tmp__rkap_belanja_1.kd_rek_2 AND
				subtotal_rek_4.kd_rek_3 = tmp__rkap_belanja_1.kd_rek_3 AND
				subtotal_rek_4.kd_rek_4 = tmp__rkap_belanja_1.kd_rek_4
			LEFT JOIN (
				SELECT
					tmp__rkap_belanja_6.kd_rek_1,
					tmp__rkap_belanja_6.kd_rek_2,
					tmp__rkap_belanja_6.kd_rek_3,
					tmp__rkap_belanja_6.kd_rek_4,
					tmp__rkap_belanja_6.kd_rek_5,
					SUM(tmp__rkap_belanja_6.total_sebelum) AS subtotal_rek_5_sebelum,
					SUM(tmp__rkap_belanja_6.total_setelah) AS subtotal_rek_5_setelah
				FROM
					tmp__rkap_belanja_6
				GROUP BY
					tmp__rkap_belanja_6.kd_rek_1,
					tmp__rkap_belanja_6.kd_rek_2,
					tmp__rkap_belanja_6.kd_rek_3,
					tmp__rkap_belanja_6.kd_rek_4,
					tmp__rkap_belanja_6.kd_rek_5
			) AS subtotal_rek_5 ON 
				subtotal_rek_5.kd_rek_1 = tmp__rkap_belanja_1.kd_rek_1 AND
				subtotal_rek_5.kd_rek_2 = tmp__rkap_belanja_1.kd_rek_2 AND
				subtotal_rek_5.kd_rek_3 = tmp__rkap_belanja_1.kd_rek_3 AND
				subtotal_rek_5.kd_rek_4 = tmp__rkap_belanja_1.kd_rek_4 AND
				subtotal_rek_5.kd_rek_5 = tmp__rkap_belanja_1.kd_rek_5
			LEFT JOIN (
				SELECT
					tmp__rkap_belanja_7.kd_rek_1,
					tmp__rkap_belanja_7.kd_rek_2,
					tmp__rkap_belanja_7.kd_rek_3,
					tmp__rkap_belanja_7.kd_rek_4,
					tmp__rkap_belanja_7.kd_rek_5,
					tmp__rkap_belanja_7.kd_belanja_sub,
					SUM(tmp__rkap_belanja_7.total_sebelum) AS subtotal_sub_sebelum,
					SUM(tmp__rkap_belanja_7.total_setelah) AS subtotal_sub_setelah
				FROM
					tmp__rkap_belanja_7
				GROUP BY
					tmp__rkap_belanja_7.kd_rek_1,
					tmp__rkap_belanja_7.kd_rek_2,
					tmp__rkap_belanja_7.kd_rek_3,
					tmp__rkap_belanja_7.kd_rek_4,
					tmp__rkap_belanja_7.kd_rek_5,
					tmp__rkap_belanja_7.kd_belanja_sub
			) AS subtotal_sub ON 
				subtotal_sub.kd_rek_1 = tmp__rkap_belanja_1.kd_rek_1 AND
				subtotal_sub.kd_rek_2 = tmp__rkap_belanja_1.kd_rek_2 AND
				subtotal_sub.kd_rek_3 = tmp__rkap_belanja_1.kd_rek_3 AND
				subtotal_sub.kd_rek_4 = tmp__rkap_belanja_1.kd_rek_4 AND
				subtotal_sub.kd_rek_5 = tmp__rkap_belanja_1.kd_rek_5 AND
				subtotal_sub.kd_belanja_sub = tmp__rkap_belanja_1.kd_belanja_sub
			GROUP BY
				kd_rek_1,
				kd_rek_2,
				kd_rek_3,
				kd_rek_4,
				kd_rek_5,
				kd_belanja_sub,
				kd_belanja_rinc,
				nm_belanja_sub,
				nm_belanja_rinc
		')
		->result_array();
		//print_r($belanja_query);exit;
		$tim_anggaran_query								= $this->db->query
		('
			SELECT
				ref__tim_anggaran.id,
				ref__tim_anggaran.kode,
				ref__tim_anggaran.nama_tim,
				ref__tim_anggaran.nip_tim,
				ref__tim_anggaran.jabatan_tim
			FROM
				ref__tim_anggaran
			WHERE
				ref__tim_anggaran.tahun = ' . get_userdata('year') . '
			ORDER BY
				ref__tim_anggaran.kode
		')
		->result();
		$tanggal_query								= $this->db->query
		('
			SELECT
				ref__tanggal.tanggal_rka_perubahan
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . get_userdata('year') . '
		')
		->row();
		$verified									= $this->model->get_where('ta__asistensi_setuju', array('id_keg' => $kegiatan), 1)->row();
		$output										= array
		(
			'daerah'								=> $daerah,
			'header'								=> $header_query,
			'sumber_dana'							=> $sumber_dana_query,
			'capaian_program'						=> $capaian_program_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'verified'								=> $verified,
			'tanggal'								=> $tanggal_query
		);
		//print_r($output);exit;
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
		
		$query										= $this->model->select('id, comments, tanggapan, id_operator, operator, tanggal')->order_by('tanggal', 'asc')->get_where('ta__asistensi', array('jenis' => $type, 'id_keg' => $this->_id_keg))->result();
		
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
							<div class="btn-group pull-right">
								' . ($val->id_operator == get_userdata('user_id') ? '
								<a class="btn btn-default btn-xs update-comment prevent-close" data-text="' . $val->comments . '" data-id="' . $val->id . '">
									<i class="fa fa-edit"></i>
								</a>
								' : '') . '
								<a class="btn btn-default btn-xs remove-comment prevent-close" data-id="' . $val->id . '">
									<i class="fa fa-trash"></i>
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