<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends Aksara
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
				ta__model.nm_model
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
			ta__program_capaian.tahun_1_target,
			ta__program_capaian.tahun_1_satuan
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
		$indikator_query									= $this->model
		->query
		('
			SELECT
				ta__indikator.id,
				ta__indikator.jns_indikator,
				ta__indikator.kd_indikator,
				ta__indikator.tolak_ukur,
				ta__indikator.target,
				ta__indikator.satuan,
				ta__indikator.penjelasan
			FROM
				ta__indikator
			WHERE
				ta__indikator.id_keg = ' . $kegiatan . '
			ORDER BY
				ta__indikator.jns_indikator ASC
		')
		->result_array();
		$belanja_query									= $this->model
		->query
		('
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
			ta__belanja_sub.uraian AS nama_sub,
			ta__belanja_rinc.uraian AS nama_rinc,
			ta__belanja_rinc.vol_1,
			ta__belanja_rinc.vol_2,
			ta__belanja_rinc.vol_3,
			ta__belanja_rinc.vol_123,
			ta__belanja_rinc.satuan_1,
			ta__belanja_rinc.satuan_2,
			ta__belanja_rinc.satuan_3,
			ta__belanja_rinc.satuan_123,
			ta__belanja_rinc.nilai,
			ta__belanja_rinc.total,
			rek_1.subtotal_rek_1,
			rek_2.subtotal_rek_2,
			rek_3.subtotal_rek_3,
			rek_4.subtotal_rek_4,
			rek_5.subtotal_rek_5,
			belanja_sub.subtotal_rinc
			FROM
			ta__belanja_rinc
			INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
				ref__rek_2.id_ref_rek_1,
				Sum(ta__belanja_rinc.total) AS subtotal_rek_1
				FROM
				ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
				ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
				ref__rek_3.id_ref_rek_2,
				Sum(ta__belanja_rinc.total) AS subtotal_rek_2
				FROM
				ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
				ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			LEFT JOIN (
				SELECT
				ref__rek_4.id_ref_rek_3,
				Sum(ta__belanja_rinc.total) AS subtotal_rek_3
				FROM
				ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
				ref__rek_4.id_ref_rek_3
			) AS rek_3 ON rek_3.id_ref_rek_3 = ref__rek_3.id
			LEFT JOIN (
				SELECT
				ref__rek_5.id_ref_rek_4,
				Sum(ta__belanja_rinc.total) AS subtotal_rek_4
				FROM
				ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
				ref__rek_5.id_ref_rek_4
			) AS rek_4 ON rek_4.id_ref_rek_4 = ref__rek_4.id
			LEFT JOIN (
				SELECT
				ta__belanja_sub.id_belanja,
				Sum(ta__belanja_rinc.total) AS subtotal_rek_5
				FROM
				ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
				ta__belanja_sub.id_belanja
			) AS rek_5 ON rek_5.id_belanja = ta__belanja_sub.id_belanja
			LEFT JOIN (
				SELECT
				ta__belanja_rinc.id_belanja_sub,
				Sum(ta__belanja_rinc.total) AS subtotal_rinc
				FROM
				ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
				ta__belanja_rinc.id_belanja_sub
			) AS belanja_sub ON belanja_sub.id_belanja_sub = ta__belanja_sub.id
			WHERE
			ta__belanja.id_keg = ' . $kegiatan . '
			ORDER BY
			ref__rek_1.kd_rek_1 ASC,
			ref__rek_2.kd_rek_2 ASC,
			ref__rek_3.kd_rek_3 ASC,
			ref__rek_4.kd_rek_4 ASC,
			ref__rek_5.kd_rek_5 ASC,
			ta__belanja_sub.kd_belanja_sub ASC,
			ta__belanja_rinc.kd_belanja_rinc ASC		
		')
		->result_array();
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
				ref__tanggal.tanggal_rka
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . get_userdata('year') . '
		')
		->row();
		$verified									= $this->model->get_where('ta__asistensi_setuju', array('id_keg' => $kegiatan), 1)->row();
		$output										= array
		(
			'header'								=> $header_query,
			'sumber_dana'							=> $sumber_dana_query,
			'capaian_program'						=> $capaian_program_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'verified'								=> $verified,
			'tanggal'								=> $tanggal_query
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