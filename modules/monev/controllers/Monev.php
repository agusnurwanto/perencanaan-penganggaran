<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Monev extends Aksara
{
	function __construct()
	{
		parent::__construct();
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', -1);
		
		if(!in_array(get_userdata('group_id'), array(1, 5, 8, 9, 11, 12, 13)))
		{
			return throw_exception(403, 'Anda tidak mempunyai hak akses yang cukup untuk melihat monev', base_url('dashboard'));
		}
		
		$this->_year								= get_userdata('year');
		$this->_id_keg								= $this->input->get('sub_kegiatan');
		$this->_id_unit								= (in_array(get_userdata('group_id'), array(5)) ? get_userdata('sub_unit') : 0);
		
		if(!$this->_id_keg)
		{
			if(in_array(get_userdata('group_id'), array(5)))
			{
				if($this->input->get('id_sub'))
				{
					return throw_exception(301, 'Silakan memilih kegiatan terlebih dahulu.', go_to('kegiatan', array('id_sub' => $this->input->get('id_sub'))));
				}
				elseif($this->_id_unit)
				{
					return throw_exception(301, 'Silakan memilih sub unit terlebih dahulu.', go_to('sub'));
				}
			}
			return throw_exception(301, 'Silakan memilih Kegiatan terlebih dahulu.', go_to('kegiatan'));
		}
		
		$this->set_theme('backend');
		$this->set_permission();
		
		// get active connection (by "year" session)
		$connection									= $this->model->get_where('ref__koneksi', array('tahun' => $this->_year), 1)->row();
		
		// check the result
		if(!$connection)
		{
			// no result! throw error...
			return throw_exception(500, phrase('connection_is_not_found'), current_page('kegiatan'));
		}
		
		// prepare the connection parameter
		$database_driver							= $connection->database_driver;
		$hostname									= $this->encryption->decrypt($connection->hostname);
		$port										= $this->encryption->decrypt($connection->port);
		$username									= $this->encryption->decrypt($connection->username);
		$password									= $this->encryption->decrypt($connection->password);
		$database_name								= $this->encryption->decrypt($connection->database_name);
		
		// getting a new connection
		$this->_db									= $this->model->new_connection($database_driver, $hostname, $port, $username, $password, $database_name);
		
		if(isset($this->_db->error()['code']) && $this->_db->error()['code'] > 0)
		{
			// no result! throw error...
			return throw_exception(500, phrase('connection_is_not_found'), current_page('kegiatan'));
		}
	}
	
	public function index()
	{
		if($this->input->post('token'))
		{
			return $this->_validate_form();
		}
		
		$params										= $this->model
		->select
		('
			ta__kegiatan.tahun AS Tahun,
			ref__urusan.kd_urusan As Kd_Urusan,
			ref__bidang.kd_bidang AS Kd_Bidang,
			ref__unit.kd_unit AS Kd_Unit,
			ref__sub.kd_sub AS Kd_Sub,
			ref__program.kd_program AS Kd_Prog,
			ta__program.kd_id_prog AS ID_Prog,
			ta__kegiatan.kd_keg AS Kd_Keg,
			DATE(NOW()) as Date_Now
		')
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->join
		(
			'ref__program',
			'ref__program.id = ta__program.id_prog'
		)
		->join
		(
			'ref__sub',
			'ref__sub.id = ta__program.id_sub'
		)
		->join
		(
			'ref__unit',
			'ref__unit.id = ref__sub.id_unit'
		)
		->join
		(
			'ref__bidang',
			'ref__bidang.id = ref__unit.id_bidang'
		)
		->join
		(
			'ref__urusan',
			'ref__urusan.id = ref__bidang.id_urusan'
		)
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'				=> $this->_id_keg
			),
			1
		)
		->row();
		
		$this->set_title('Monev')
		->set_icon('fa fa-drupal')
		->set_breadcrumb
		(
			array
			(
				'monev'								=> 'Monev',
				'kegiatan'							=> 'Kegiatan'
			)
		)
		->set_output
		(
			array
			(
				'header'							=> $this->_header(),
				'rencana'							=> $this->_rencana($params),
				'realisasi'							=> $this->_realisasi($params),
				'spd'								=> $this->_spd($params),
				'indikator'							=> $this->_indikator($params),
				'monev_realisasi'					=> $this->_monev_realisasi($params),
				'realisasi_fisik'					=> $this->_simpelbang_realisasi_fisik($params),
				'keterangan'						=> $this->model->select('keterangan')->get_where('ta__monev_keterangan', array('id_keg' => $this->_id_keg), 1)->row('keterangan'),
				'lock'								=> $this->model->get_where('ta__monev_locked', array('id_keg' => $this->_id_keg), 1)->row()
			)
		)
		->render();
	}
	
	private function _validate_form()
	{
		if($this->input->post('keluaran'))
		{
			foreach($this->input->post('keluaran') as $key => $val)
			{
				$this->form_validation->set_rules('keluaran[' . $key . '][]', 'Keluaran', 'numeric|less_than_equal_to[100]');
				$message							= 'Kolom keluaran dan hasil harus dalam jangkauan persen (0.01 - 100)...';
			}
		}
		if($this->input->post('hasil'))
		{
			foreach($this->input->post('hasil') as $key => $val)
			{
				$this->form_validation->set_rules('hasil[' . $key . '][]', 'Hasil', 'numeric|less_than_equal_to[100]');
				$message							= 'Kolom keluaran dan hasil harus dalam jangkauan persen (0.01 - 100)...';
			}
		}
		if($this->input->post('keterangan'))
		{
			$this->form_validation->set_rules('keterangan', 'Keterangan', 'xss_clean');
			$message								= 'Keterangan hanya berisi latin';
		}
		else
		{
			$message								= 'Silakan klik tombol "Simpan" untuk menyimpan perubahan';
		}
		if($this->form_validation->run() === false)
		{
			return throw_exception(403, $message);
		}
		else
		{
			if($this->input->post('keluaran'))
			{
				foreach($this->input->post('keluaran') as $key => $val)
				{
					$checker						= $this->model
					->select('id')
					->get_where
					(
						'ta__monev_realisasi_indikator',
						array
						(
							'id_keg'				=> $this->_id_keg,
							'id_indikator'			=> $key
						)
					)
					->row('id');
					$prepare						= array
					(
						'id_keg'					=> $this->_id_keg,
						'id_indikator'				=> $key
					);
					
					if(isset($val[1]))
					{
						$prepare['triwulan_1']		= $val[1];
					}
					
					if(isset($val[2]))
					{
						$prepare['triwulan_2']		= $val[2];
					}
					
					if(isset($val[3]))
					{
						$prepare['triwulan_3']		= $val[3];
					}
					
					if(isset($val[4]))
					{
						$prepare['triwulan_4']		= $val[4];
					}
					
					if($checker)
					{
						$this->model->update('ta__monev_realisasi_indikator', $prepare, array('id_indikator' => $key));
					}
					else
					{
						$this->model->insert('ta__monev_realisasi_indikator', $prepare);
					}
				}
			}
			if($this->input->post('hasil'))
			{
				foreach($this->input->post('hasil') as $key => $val)
				{
					$checker						= $this->model
					->select('id')
					->get_where
					(
						'ta__monev_realisasi_indikator',
						array
						(
							'id_keg'				=> $this->_id_keg,
							'id_indikator'			=> $key
						)
					)
					->row('id');
					$prepare						= array
					(
						'id_keg'					=> $this->_id_keg,
						'id_indikator'				=> $key
					);
					
					if(isset($val[1]))
					{
						$prepare['triwulan_1']		= $val[1];
					}
					
					if(isset($val[2]))
					{
						$prepare['triwulan_2']		= $val[2];
					}
					
					if(isset($val[3]))
					{
						$prepare['triwulan_3']		= $val[3];
					}
					
					if(isset($val[4]))
					{
						$prepare['triwulan_4']		= $val[4];
					}
					
					if($checker)
					{
						$this->model->update('ta__monev_realisasi_indikator', $prepare, array('id_indikator' => $key));
					}
					else
					{
						$this->model->insert('ta__monev_realisasi_indikator', $prepare);
					}
				}
			}
			if($this->input->post('keterangan'))
			{
				$checker							= $this->model->get_where('ta__monev_keterangan', array('id_keg' => $this->_id_keg), 1)->row();
				$prepare							= array
				(
					'id_keg'						=> $this->_id_keg,
					'keterangan'					=> $this->input->post('keterangan')
				);
				if($checker)
				{
					$this->model->update('ta__monev_keterangan', array('keterangan' => $this->input->post('keterangan')), array('id_keg' => $this->_id_keg));
				}
				else
				{
					$this->model->insert('ta__monev_keterangan', $prepare);
				}
			}
			return throw_exception(200, 'Input keluaran dan hasil berhasil disimpan...');
		}
	}
	
	private function _header()
	{
		return $this->model
		->select
		('
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__program.kd_program,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ref__sub.nm_sub,
			ref__program.nm_program,
			ta__kegiatan.kd_keg,
			ta__kegiatan.kegiatan,
			ta__kegiatan.pagu
		')
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->join
		(
			'ref__program',
			'ref__program.id = ta__program.id_prog'
		)
		->join
		(
			'ref__sub',
			'ref__sub.id = ta__program.id_sub'
		)
		->join
		(
			'ref__unit',
			'ref__unit.id = ref__sub.id_unit'
		)
		->join
		(
			'ref__bidang',
			'ref__bidang.id = ref__unit.id_bidang'
		)
		->join
		(
			'ref__urusan',
			'ref__urusan.id = ref__bidang.id_urusan'
		)
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'					=> $this->input->get('id_keg')
			)
		)
		->row();
	}
	
	private function _rencana($params = array())
	{
		return $this->_db->query
		('
			SELECT
				Ta_Rencana_Arsip.Kd_Rek_1,
				Ta_Rencana_Arsip.Kd_Rek_2,
				Ta_Rencana_Arsip.Kd_Rek_3,
				Ta_Rencana_Arsip.Kd_Rek_4,
				Ta_Rencana_Arsip.Kd_Rek_5,
				Ref_Rek_5.Nm_Rek_5,
				(Ta_Rencana_Arsip.Jan + Ta_Rencana_Arsip.Feb + Ta_Rencana_Arsip.Mar) AS tw_1,
				(Ta_Rencana_Arsip.Apr + Ta_Rencana_Arsip.Mei + Ta_Rencana_Arsip.Jun) AS tw_2,
				(Ta_Rencana_Arsip.Jul + Ta_Rencana_Arsip.Agt + Ta_Rencana_Arsip.Sep) AS tw_3,
				(Ta_Rencana_Arsip.Okt + Ta_Rencana_Arsip.Nop + Ta_Rencana_Arsip.Des) AS tw_4
			FROM
				Ta_Rencana_Arsip
			INNER JOIN Ref_Rek_5 ON Ta_Rencana_Arsip.Kd_Rek_1 = Ref_Rek_5.Kd_Rek_1
			AND Ta_Rencana_Arsip.Kd_Rek_2 = Ref_Rek_5.Kd_Rek_2
			AND Ta_Rencana_Arsip.Kd_Rek_3 = Ref_Rek_5.Kd_Rek_3
			AND Ta_Rencana_Arsip.Kd_Rek_4 = Ref_Rek_5.Kd_Rek_4
			AND Ta_Rencana_Arsip.Kd_Rek_5 = Ref_Rek_5.Kd_Rek_5
			WHERE
				Ta_Rencana_Arsip.Tahun = ' . (isset($params->Tahun) ? $params->Tahun : 0) . '
			AND Ta_Rencana_Arsip.Kd_Perubahan = 4
			AND Ta_Rencana_Arsip.Kd_Urusan = ' . (isset($params->Kd_Urusan) ? $params->Kd_Urusan : 0) . '
			AND Ta_Rencana_Arsip.Kd_Bidang = ' . (isset($params->Kd_Bidang) ? $params->Kd_Bidang : 0) . '
			AND Ta_Rencana_Arsip.Kd_Unit = ' . (isset($params->Kd_Unit) ? $params->Kd_Unit : 0) . '
			AND Ta_Rencana_Arsip.Kd_Sub = ' . (isset($params->Kd_Sub) ? $params->Kd_Sub : 0) . '
			AND Ta_Rencana_Arsip.Kd_Prog = ' . (isset($params->Kd_Prog) ? $params->Kd_Prog : 0) . '
			AND Ta_Rencana_Arsip.ID_Prog = ' . (isset($params->ID_Prog) ? $params->ID_Prog : 0) . '
			AND Ta_Rencana_Arsip.Kd_Keg = ' . (isset($params->Kd_Keg) ? $params->Kd_Keg : 0) . '
			ORDER BY
				Ta_Rencana_Arsip.Kd_Rek_1,
				Ta_Rencana_Arsip.Kd_Rek_2,
				Ta_Rencana_Arsip.Kd_Rek_3,
				Ta_Rencana_Arsip.Kd_Rek_4,
				Ta_Rencana_Arsip.Kd_Rek_5
		')
		->result();
	}
	
	private function _realisasi($params = array())
	{
		if($params)
		{
			$params									= (array) $params;
			
			// trigger the stored procedure
			$query									= $this->_db
			->query
			(
				'BEGIN SET NOCOUNT ON EXEC rptkartukendalikegiatan ?, ?, ?, ?, ?, ?, ?, ?, ? END',
				$params
			)
			->result();
			
			return $query;
		}
		
		return false;
	}
	
	private function _spd($params = array())
	{
		if($params)
		{
			$params									= (array) $params;
			
			// trigger the stored procedure
			$query									= $this->_db
			->query
			(
				'BEGIN SET NOCOUNT ON EXEC rptkartukendalispd ?, ?, ?, ?, ?, ?, ?, ?, ? END',
				$params
			)
			->result_array();
			$query									= json_decode(json_encode(array_sort($query, array('Kd_Rek_5_Gab'))));
			//print_r($query);exit;
			return $query;
		}
		
		return false;
	}
	
	private function _indikator()
	{
		$query										= $this->db->query
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
				ta__indikator.id_keg = ' . $this->_id_keg . '
			ORDER BY
				ta__indikator.kd_indikator ASC
		')
		->result();
		//print_r($query);exit;
		return $query;
	}
	
	private function _monev_realisasi()
	{
		$query										= $this->model->get_where('ta__monev_realisasi_indikator', array('id_keg' => $this->_id_keg))->result();
		$output										= array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output[$val->id_indikator]			= array
				(
					'tw_1'							=> number_format($val->triwulan_1, 2),
					'tw_2'							=> number_format($val->triwulan_2, 2),
					'tw_3'							=> number_format($val->triwulan_3, 2),
					'tw_4'							=> number_format($val->triwulan_4, 2)
				);
			}
		}
		return $output;
	}
	
	private function _simpelbang_realisasi_fisik($params = array())
	{
		if(isset($params->Date_Now))
		{
			unset($params->Date_Now);
		}
		
		if($params)
		{
			$parameter							= array
			(
				'X-API-KEY'						=> 'DEBD-2D65-280B-D1B0-5742',
				'parameter'						=> (array) $params
			);
			$this->load->library('rest');
			
			$data								= $this->rest->get('https://simpelbang.bekasikota.go.id/' . get_userdata('year') . '/apis/realisasi_fisik', $parameter);
			$data								= json_decode($data);
			
			//if('simda' == get_userdata('username')) print_r($data);exit;
			
			if(isset($data->RESULTS))
			{
				return $data->RESULTS;
			}
		}
		return false;
	}
}