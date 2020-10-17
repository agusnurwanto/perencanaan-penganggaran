<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Simda_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		error_reporting(E_ALL);
		ini_set('display_error', 1);
		ini_set('sqlsrv.ClientBufferMaxKBSize', '512M');
		$year										= get_userdata('year');
		$connection									= $this->db->get_where('ref__koneksi', array('tahun' => $year), 1)->row();
		$configs									= array();
		if($connection)
		{
			$configs								= array
			(
				'dsn'								=> '',
				'hostname' 							=> $this->encryption->decrypt($connection->hostname),
				'username'							=> $this->encryption->decrypt($connection->username),
				'password' 							=> $this->encryption->decrypt($connection->password),
				'database' 							=> $this->encryption->decrypt($connection->database_name),
				'dbdriver' 							=> $connection->database_driver,
				'dbprefix' 							=> '',
				'pconnect' 							=> FALSE,
				'db_debug' 							=> (ENVIRONMENT !== 'production'),
				'cache_on' 							=> FALSE,
				'cachedir' 							=> '',
				'char_set' 							=> 'utf8',
				'dbcollat' 							=> 'utf8_unicode_ci',
				'swap_pre' 							=> '',
				'encrypt' 							=> FALSE,
				'compress' 							=> FALSE,
				'stricton' 							=> FALSE,
				'failover' 							=> array(),
				'save_queries' 						=> TRUE
			);
		}
		$this->simda								= $this->load->database($configs, TRUE);
	}
	
	public function perbandingan_organisasi($tahun = null)
	{
		$planning_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit,
				ref__sub.nm_sub
			FROM
				ref__sub
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__sub.tahun = ' . $tahun . '	
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC
		')
		->result();
		$simda_query								= $this->simda->query
		('
			SELECT
				Ref_Sub_Unit.Kd_Urusan,
				Ref_Sub_Unit.Kd_Bidang,
				Ref_Sub_Unit.Kd_Unit,
				Ref_Sub_Unit.Kd_Sub,
				Ref_Urusan.Nm_Urusan,
				Ref_Bidang.Nm_Bidang,
				Ref_Unit.Nm_Unit,
				Ref_Sub_Unit.Nm_Sub_Unit
			FROM
				Ref_Sub_Unit
			INNER JOIN Ref_Unit ON Ref_Sub_Unit.Kd_Urusan = Ref_Unit.Kd_Urusan
			AND Ref_Sub_Unit.Kd_Bidang = Ref_Unit.Kd_Bidang
			AND Ref_Sub_Unit.Kd_Unit = Ref_Unit.Kd_Unit
			INNER JOIN Ref_Bidang ON Ref_Unit.Kd_Urusan = Ref_Bidang.Kd_Urusan
			AND Ref_Unit.Kd_Bidang = Ref_Bidang.Kd_Bidang
			INNER JOIN Ref_Urusan ON Ref_Bidang.Kd_Urusan = Ref_Urusan.Kd_Urusan
			ORDER BY
				Ref_Sub_Unit.Kd_Urusan ASC,
				Ref_Sub_Unit.Kd_Bidang ASC,
				Ref_Sub_Unit.Kd_Unit ASC,
				Ref_Sub_Unit.Kd_Sub ASC
		')
		->result();
		
		$output										= array();
		
		if($planning_query)
		{
			foreach($planning_query as $key => $val)
			{
				$kode								= $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '.' . $val->kd_sub;
				$output[$kode]						= array
				(
					'kd_urusan'						=> $val->kd_urusan,
					'kd_bidang'						=> $val->kd_bidang,
					'kd_unit'						=> $val->kd_unit,
					'kd_sub'						=> $val->kd_sub,
					'nm_urusan'						=> $val->nm_urusan,
					'nm_bidang'						=> $val->nm_bidang,
					'nm_unit'						=> $val->nm_unit,
					'nm_sub'						=> $val->nm_sub
				);
			}
		}
		if($simda_query)
		{
			foreach($simda_query as $key => $val)
			{
				$kode								= $val->Kd_Urusan . '.' . $val->Kd_Bidang . '.' . $val->Kd_Unit . '.' . $val->Kd_Sub;
				if(isset($output[$kode]))
				{
					$output[$kode]['kd_urusan_simda']	= $val->Kd_Urusan;
					$output[$kode]['kd_bidang_simda']	= $val->Kd_Bidang;
					$output[$kode]['kd_unit_simda']		= $val->Kd_Unit;
					$output[$kode]['kd_sub_simda']		= $val->Kd_Sub;
					$output[$kode]['nm_urusan_simda']	= $val->Nm_Urusan;
					$output[$kode]['nm_bidang_simda']	= $val->Nm_Bidang;
					$output[$kode]['nm_unit_simda']		= $val->Nm_Unit;
					$output[$kode]['nm_sub_simda']		= $val->Nm_Sub_Unit;
				}
				else
				{
					$output[$kode]					= array
					(
						'kd_urusan_simda'			=> $val->Kd_Urusan,
						'kd_bidang_simda'			=> $val->Kd_Bidang,
						'kd_unit_simda'				=> $val->Kd_Unit,
						'kd_sub_simda'				=> $val->Kd_Sub,
						'nm_urusan_simda'			=> $val->Nm_Urusan,
						'nm_bidang_simda'			=> $val->Nm_Bidang,
						'nm_unit_simda'				=> $val->Nm_Unit,
						'nm_sub_simda'				=> $val->Nm_Sub_Unit
					);
				}
			}
		}
		ksort($output);
		return $output;
	}
	
	public function perbandingan_program($tahun = null, $unit = null)
	{
		if(get_userdata('group_id') == 5)
		{
			$unit									= get_userdata('sub_unit');
		}
		elseif(!$unit)
		{
			$unit									= 0;
		}
		elseif($unit == 999)
		{
			$unit									= "'%'";
		}
		$planning_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__program.kd_id_prog,
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit,
				ref__sub.nm_sub,
				ref__program.nm_program
			FROM
				ta__program
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			WHERE
				ta__program.tahun = ' . $tahun . ' AND
				ref__unit.id LIKE ' . $unit . ' AND
				ref__program.kd_program > 0
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC,
				ref__program.kd_program ASC,
				ta__program.kd_id_prog ASC
		')
		->result();
		$simda_query								= $this->simda->query
		('
			SELECT
				Ta_Program.Kd_Urusan,
				Ta_Program.Kd_Bidang,
				Ta_Program.Kd_Unit,
				Ta_Program.Kd_Sub,
				Ta_Program.Kd_Prog,
				Ta_Program.ID_Prog,
				Ref_Urusan.Nm_Urusan,
				Ref_Bidang.Nm_Bidang,
				Ref_Unit.Nm_Unit,
				Ref_Sub_Unit.Nm_Sub_Unit,
				Ta_Program.Ket_Program
			FROM
				Ta_Program
			INNER JOIN Ref_Sub_Unit ON Ta_Program.Kd_Urusan = Ref_Sub_Unit.Kd_Urusan
			AND Ta_Program.Kd_Bidang = Ref_Sub_Unit.Kd_Bidang
			AND Ta_Program.Kd_Unit = Ref_Sub_Unit.Kd_Unit
			AND Ta_Program.Kd_Sub = Ref_Sub_Unit.Kd_Sub
			INNER JOIN Ref_Unit ON Ref_Sub_Unit.Kd_Urusan = Ref_Unit.Kd_Urusan
			AND Ref_Sub_Unit.Kd_Bidang = Ref_Unit.Kd_Bidang
			AND Ref_Sub_Unit.Kd_Unit = Ref_Unit.Kd_Unit
			INNER JOIN Ref_Bidang ON Ta_Program.Kd_Urusan = Ref_Bidang.Kd_Urusan
			AND Ta_Program.Kd_Bidang = Ref_Bidang.Kd_Bidang
			INNER JOIN Ref_Urusan ON Ref_Bidang.Kd_Urusan = Ref_Urusan.Kd_Urusan
			WHERE
				(Ta_Program.Tahun = ' . $tahun . ') AND
				(Ta_Program.Kd_Prog > 0)
			ORDER BY
				Ta_Program.Kd_Urusan ASC,
				Ta_Program.Kd_Bidang ASC,
				Ta_Program.Kd_Unit ASC,
				Ta_Program.Kd_Sub ASC,
				Ta_Program.Kd_Prog ASC,
				Ta_Program.ID_Prog ASC
		')
		->result();
		$output										= array();
		
		if($planning_query)
		{
			foreach($planning_query as $key => $val)
			{
				$kode								= $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '.' . $val->kd_sub . '.' . $val->kd_program . '.' . $val->kd_id_prog;
				$output[$kode]						= array
				(
					'kd_urusan'						=> $val->kd_urusan,
					'kd_bidang'						=> $val->kd_bidang,
					'kd_unit'						=> $val->kd_unit,
					'kd_sub'						=> $val->kd_sub,
					'kd_program'					=> $val->kd_program,
					'kd_id_prog'					=> $val->kd_id_prog,
					'nm_urusan'						=> $val->nm_urusan,
					'nm_bidang'						=> $val->nm_bidang,
					'nm_unit'						=> $val->nm_unit,
					'nm_sub'						=> $val->nm_sub,
					'nm_program'					=> $val->nm_program
				);
			}
		}
		if($simda_query)
		{
			foreach($simda_query as $key => $val)
			{
				$kode								= $val->Kd_Urusan . '.' . $val->Kd_Bidang . '.' . $val->Kd_Unit . '.' . $val->Kd_Sub . '.' . $val->Kd_Prog . '.' . $val->ID_Prog;
				if(isset($output[$kode]))
				{
					$output[$kode]['kd_urusan_simda']	= $val->Kd_Urusan;
					$output[$kode]['kd_bidang_simda']	= $val->Kd_Bidang;
					$output[$kode]['kd_unit_simda']		= $val->Kd_Unit;
					$output[$kode]['kd_sub_simda']		= $val->Kd_Sub;
					$output[$kode]['kd_program_simda']	= $val->Kd_Prog;
					$output[$kode]['kd_id_prog_simda']	= $val->ID_Prog;
					$output[$kode]['nm_urusan_simda']	= $val->Nm_Urusan;
					$output[$kode]['nm_bidang_simda']	= $val->Nm_Bidang;
					$output[$kode]['nm_unit_simda']		= $val->Nm_Unit;
					$output[$kode]['nm_sub_simda']		= $val->Nm_Sub_Unit;
					$output[$kode]['nm_program_simda']	= $val->Ket_Program;
				}
				else
				{
					$output[$kode]					= array
					(
						'kd_urusan_simda'			=> $val->Kd_Urusan,
						'kd_bidang_simda'			=> $val->Kd_Bidang,
						'kd_unit_simda'				=> $val->Kd_Unit,
						'kd_sub_simda'				=> $val->Kd_Sub,
						'kd_program_simda'			=> $val->Kd_Prog,
						'kd_id_prog_simda'			=> $val->ID_Prog,
						'nm_urusan_simda'			=> $val->Nm_Urusan,
						'nm_bidang_simda'			=> $val->Nm_Bidang,
						'nm_unit_simda'				=> $val->Nm_Unit,
						'nm_sub_simda'				=> $val->Nm_Sub_Unit,
						'nm_program_simda'			=> $val->Ket_Program
					);
				}
			}
		}
		ksort($output);
		//print_r($output);exit;
		return $output;
	}
	
	public function perbandingan_kegiatan($tahun = null, $unit = null)
	{
		if(get_userdata('group_id') == 5)
		{
			$unit										= get_userdata('sub_unit');
		}
		elseif(!$unit)
		{
			$unit										= 0;
		}
		elseif($unit == 999)
		{
			$unit										= "'%'";
			$kd_urusan									= "'%'";
			$kd_bidang									= "'%'";
			$kd_unit									= "'%'";
		}
		else
		{
			$unit_query									= $this->db->query
			('
				SELECT
					ref__urusan.kd_urusan,
					ref__bidang.kd_bidang,
					ref__unit.kd_unit
				FROM
					ref__unit
				inner join ref__bidang ON ref__bidang.id = ref__unit.id_bidang
				inner join ref__urusan ON ref__urusan.id = ref__bidang.id_urusan
				where
					ref__unit.id = ' . $unit . '
			')
			->row();
			$kd_urusan									= $unit_query->kd_urusan;
			$kd_bidang									= $unit_query->kd_bidang;
			$kd_unit									= $unit_query->kd_unit;
		}
		
		//print_r($kd_urusan);exit;
		$planning_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__program.kd_id_prog,
				ta__kegiatan_arsip.kode_keg AS kd_keg,
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit,
				ref__sub.nm_sub,
				ref__program.nm_program,
				ta__kegiatan_arsip.kegiatan,
				ta__kegiatan_arsip.pagu
			FROM
				ta__kegiatan_arsip
			INNER JOIN ta__program ON ta__program.id = ta__kegiatan_arsip.id_program
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ta__program.tahun = ' . $tahun . ' AND
				ref__unit.id LIKE ' . $unit . ' AND
				ta__kegiatan_arsip.kode_perubahan = (Select Max(kode_perubahan) From ta__kegiatan_arsip)
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC,
				ref__program.kd_program ASC,
				ta__program.kd_id_prog ASC,
				ta__kegiatan_arsip.kode_keg ASC
		')
		->result();
		
		$simda_query								= $this->simda->query
		('
			SELECT
				Ta_Kegiatan.Kd_Urusan,
				Ta_Kegiatan.Kd_Bidang,
				Ta_Kegiatan.Kd_Unit,
				Ta_Kegiatan.Kd_Sub,
				Ta_Kegiatan.Kd_Prog,
				Ta_Kegiatan.ID_Prog,
				Ta_Kegiatan.Kd_Keg,
				Ref_Urusan.Nm_Urusan,
				Ref_Bidang.Nm_Bidang,
				Ref_Unit.Nm_Unit,
				Ref_Sub_Unit.Nm_Sub_Unit,
				Ta_Program.Ket_Program,
				Ta_Kegiatan.Ket_Kegiatan,
				Ta_Kegiatan.Pagu_Anggaran
			FROM
				Ta_Kegiatan
			INNER JOIN Ta_Program ON Ta_Kegiatan.Kd_Urusan = Ta_Program.Kd_Urusan
				AND Ta_Kegiatan.Kd_Bidang = Ta_Program.Kd_Bidang
				AND Ta_Kegiatan.Kd_Unit = Ta_Program.Kd_Unit
				AND Ta_Kegiatan.Kd_Sub = Ta_Program.Kd_Sub
				AND Ta_Kegiatan.Kd_Prog = Ta_Program.Kd_Prog
				AND Ta_Kegiatan.ID_Prog = Ta_Program.ID_Prog
			INNER JOIN Ref_Sub_Unit ON Ta_Program.Kd_Urusan = Ref_Sub_Unit.Kd_Urusan
				AND Ta_Program.Kd_Bidang = Ref_Sub_Unit.Kd_Bidang
				AND Ta_Program.Kd_Unit = Ref_Sub_Unit.Kd_Unit
				AND Ta_Program.Kd_Sub = Ref_Sub_Unit.Kd_Sub
			INNER JOIN Ref_Unit ON Ref_Sub_Unit.Kd_Urusan = Ref_Unit.Kd_Urusan
				AND Ref_Sub_Unit.Kd_Bidang = Ref_Unit.Kd_Bidang
				AND Ref_Sub_Unit.Kd_Unit = Ref_Unit.Kd_Unit
			INNER JOIN Ref_Bidang ON Ta_Program.Kd_Urusan = Ref_Bidang.Kd_Urusan
				AND Ta_Program.Kd_Bidang = Ref_Bidang.Kd_Bidang
			INNER JOIN Ref_Urusan ON Ref_Bidang.Kd_Urusan = Ref_Urusan.Kd_Urusan
			WHERE
				Ta_Kegiatan.Tahun = ' . $tahun . ' AND
				Ta_Kegiatan.Kd_Urusan LIKE ' . $kd_urusan . ' AND
				Ta_Kegiatan.Kd_Bidang LIKE ' . $kd_bidang . ' AND
				Ta_Kegiatan.Kd_Unit LIKE ' . $kd_unit . ' AND
				Ta_Kegiatan.Kd_Prog > 0
			ORDER BY
				Ta_Kegiatan.Kd_Urusan ASC,
				Ta_Kegiatan.Kd_Bidang ASC,
				Ta_Kegiatan.Kd_Unit ASC,
				Ta_Kegiatan.Kd_Sub ASC,
				Ta_Kegiatan.Kd_Prog ASC,
				Ta_Kegiatan.ID_Prog ASC,
				Ta_Kegiatan.Kd_Keg ASC
		')
		->result();
		
		$output										= array();
		
		if($planning_query)
		{
			foreach($planning_query as $key => $val)
			{
				$kode								= $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '.' . $val->kd_sub . '.' . $val->kd_program . '.' . $val->kd_id_prog . '.' . $val->kd_keg;
				$output[$kode]						= array
				(
					'kd_urusan'						=> $val->kd_urusan,
					'kd_bidang'						=> $val->kd_bidang,
					'kd_unit'						=> $val->kd_unit,
					'kd_sub'						=> $val->kd_sub,
					'kd_program'					=> $val->kd_program,
					'kd_id_prog'					=> $val->kd_id_prog,
					'kd_keg'						=> $val->kd_keg,
					'nm_urusan'						=> $val->nm_urusan,
					'nm_bidang'						=> $val->nm_bidang,
					'nm_unit'						=> $val->nm_unit,
					'nm_sub'						=> $val->nm_sub,
					'nm_program'					=> $val->nm_program,
					'kegiatan'						=> $val->kegiatan,
					'pagu'							=> $val->pagu
				);
			}
		}
		
		if($simda_query)
		{
			foreach($simda_query as $key => $val)
			{
				$kode									= $val->Kd_Urusan . '.' . $val->Kd_Bidang . '.' . $val->Kd_Unit . '.' . $val->Kd_Sub . '.' . $val->Kd_Prog . '.' . $val->ID_Prog . '.' . $val->Kd_Keg;
				if(isset($output[$kode]))
				{
					$output[$kode]['kd_urusan_simda']	= $val->Kd_Urusan;
					$output[$kode]['kd_bidang_simda']	= $val->Kd_Bidang;
					$output[$kode]['kd_unit_simda']		= $val->Kd_Unit;
					$output[$kode]['kd_sub_simda']		= $val->Kd_Sub;
					$output[$kode]['kd_program_simda']	= $val->Kd_Prog;
					$output[$kode]['kd_id_prog_simda']	= $val->ID_Prog;
					$output[$kode]['kd_keg_simda']		= $val->Kd_Keg;
					$output[$kode]['nm_urusan_simda']	= $val->Nm_Urusan;
					$output[$kode]['nm_bidang_simda']	= $val->Nm_Bidang;
					$output[$kode]['nm_unit_simda']		= $val->Nm_Unit;
					$output[$kode]['nm_sub_simda']		= $val->Nm_Sub_Unit;
					$output[$kode]['nm_program_simda']	= $val->Ket_Program;
					$output[$kode]['kegiatan_simda']	= $val->Ket_Kegiatan;
					$output[$kode]['pagu_simda']		= $val->Pagu_Anggaran;
				}
				else
				{
					$output[$kode]						= array
					(
						'kd_urusan_simda'				=> $val->Kd_Urusan,
						'kd_bidang_simda'				=> $val->Kd_Bidang,
						'kd_unit_simda'					=> $val->Kd_Unit,
						'kd_sub_simda'					=> $val->Kd_Sub,
						'kd_program_simda'				=> $val->Kd_Prog,
						'kd_id_prog_simda'				=> $val->ID_Prog,
						'kd_keg_simda'					=> $val->Kd_Keg,
						'nm_urusan_simda'				=> $val->Nm_Urusan,
						'nm_bidang_simda'				=> $val->Nm_Bidang,
						'nm_unit_simda'					=> $val->Nm_Unit,
						'nm_sub_simda'					=> $val->Nm_Sub_Unit,
						'nm_program_simda'				=> $val->Ket_Program,
						'kegiatan_simda'				=> $val->Ket_Kegiatan,
						'pagu_simda'					=> $val->Pagu_Anggaran
					);
				}
			}
		}
		
		ksort($output);
		//print_r($output);exit;
		if(1 == $this->input->get('debug'))
		{
			print_r($output);exit;
		}
		return $output;
	}
	
	public function perbandingan_rekening($tahun = null)
	{
		$planning_query								= $this->db->query
		('
			SELECT
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				ref__rek_4.uraian AS nm_rek_4,
				ref__rek_5.uraian AS nm_rek_5
			FROM
				ref__rek_5
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ref__rek_5.tahun = ' . $tahun . '	
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC,
				ref__rek_4.kd_rek_4 ASC,
				ref__rek_5.kd_rek_5 ASC
		')
		->result();
		$simda_query								= $this->simda->query
		('
			SELECT
				Ref_Rek_5.Kd_Rek_1,
				Ref_Rek_5.Kd_Rek_2,
				Ref_Rek_5.Kd_Rek_3,
				Ref_Rek_5.Kd_Rek_4,
				Ref_Rek_5.Kd_Rek_5,
				Ref_Rek_1.Nm_Rek_1,
				Ref_Rek_2.Nm_Rek_2,
				Ref_Rek_3.Nm_Rek_3,
				Ref_Rek_4.Nm_Rek_4,
				Ref_Rek_5.Nm_Rek_5
			FROM
				Ref_Rek_5
			INNER JOIN Ref_Rek_4 ON Ref_Rek_5.Kd_Rek_1 = Ref_Rek_4.Kd_Rek_1
			AND Ref_Rek_5.Kd_Rek_2 = Ref_Rek_4.Kd_Rek_2
			AND Ref_Rek_5.Kd_Rek_3 = Ref_Rek_4.Kd_Rek_3
			AND Ref_Rek_5.Kd_Rek_4 = Ref_Rek_4.Kd_Rek_4
			INNER JOIN Ref_Rek_3 ON Ref_Rek_4.Kd_Rek_1 = Ref_Rek_3.Kd_Rek_1
			AND Ref_Rek_4.Kd_Rek_2 = Ref_Rek_3.Kd_Rek_2
			AND Ref_Rek_4.Kd_Rek_3 = Ref_Rek_3.Kd_Rek_3
			INNER JOIN Ref_Rek_2 ON Ref_Rek_3.Kd_Rek_1 = Ref_Rek_2.Kd_Rek_1
			AND Ref_Rek_3.Kd_Rek_2 = Ref_Rek_2.Kd_Rek_2
			INNER JOIN Ref_Rek_1 ON Ref_Rek_2.Kd_Rek_1 = Ref_Rek_1.Kd_Rek_1
			WHERE
				(Ref_Rek_5.Kd_Rek_1 = 5)
			AND (Ref_Rek_5.Kd_Rek_2 = 2)
			ORDER BY
				Ref_Rek_5.Kd_Rek_1,
				Ref_Rek_5.Kd_Rek_2,
				Ref_Rek_5.Kd_Rek_3,
				Ref_Rek_5.Kd_Rek_4,
				Ref_Rek_5.Kd_Rek_5
		')
		->result();
		
		$output										= array();
		
		if($planning_query)
		{
			foreach($planning_query as $key => $val)
			{
				$kode								= $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . $val->kd_rek_4 . '.' . $val->kd_rek_5;
				$output[$kode]						= array
				(
					'kd_rek_1'						=> $val->kd_rek_1,
					'kd_rek_2'						=> $val->kd_rek_2,
					'kd_rek_3'						=> $val->kd_rek_3,
					'kd_rek_4'						=> $val->kd_rek_4,
					'kd_rek_5'						=> $val->kd_rek_5,
					'nm_rek_1'						=> $val->nm_rek_1,
					'nm_rek_2'						=> $val->nm_rek_2,
					'nm_rek_3'						=> $val->nm_rek_3,
					'nm_rek_4'						=> $val->nm_rek_4,
					'nm_rek_5'						=> $val->nm_rek_5
				);
			}
		}
		if($simda_query)
		{
			foreach($simda_query as $key => $val)
			{
				$kode								= $val->Kd_Rek_1 . '.' . $val->Kd_Rek_2 . '.' . $val->Kd_Rek_3 . '.' . $val->Kd_Rek_4 . '.' . $val->Kd_Rek_5;
				if(isset($output[$kode]))
				{
					$output[$kode]['kd_rek_1_simda']	= $val->Kd_Rek_1;
					$output[$kode]['kd_rek_2_simda']	= $val->Kd_Rek_2;
					$output[$kode]['kd_rek_3_simda']	= $val->Kd_Rek_3;
					$output[$kode]['kd_rek_4_simda']	= $val->Kd_Rek_4;
					$output[$kode]['kd_rek_5_simda']	= $val->Kd_Rek_5;
					$output[$kode]['nm_rek_1_simda']	= $val->Nm_Rek_1;
					$output[$kode]['nm_rek_2_simda']	= $val->Nm_Rek_2;
					$output[$kode]['nm_rek_3_simda']	= $val->Nm_Rek_3;
					$output[$kode]['nm_rek_4_simda']	= $val->Nm_Rek_4;
					$output[$kode]['nm_rek_5_simda']	= $val->Nm_Rek_5;
				}
				else
				{
					$output[$kode]					= array
					(
					'kd_rek_1_simda'				=> $val->Kd_Rek_1,
					'kd_rek_2_simda'				=> $val->Kd_Rek_2,
					'kd_rek_3_simda'				=> $val->Kd_Rek_3,
					'kd_rek_4_simda'				=> $val->Kd_Rek_4,
					'kd_rek_5_simda'				=> $val->Kd_Rek_5,
					'nm_rek_1_simda'				=> $val->Nm_Rek_1,
					'nm_rek_2_simda'				=> $val->Nm_Rek_2,
					'nm_rek_3_simda'				=> $val->Nm_Rek_3,
					'nm_rek_4_simda'				=> $val->Nm_Rek_4,
					'nm_rek_5_simda'				=> $val->Nm_Rek_5
					);
				}
			}
		}
		ksort($output);
		return $output;
	}
}