<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Asistensi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
		/*ini_set('max_execution_time', 0);
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
		$this->simda									= $this->load->database($configs, TRUE);*/
	}
	
	public function asistensi_kegiatan($unit = null, $jenis_anggaran = null)
	{
		if($jenis_anggaran == 'all')
		{
			$jenis_anggaran					= "'%'";
		}
		
		$data										= $this->db->query
		('
			SELECT
			ref__urusan.id AS id_urusan,
			ref__bidang.id AS id_bidang,
			ref__program.id AS id_program,
			ta__kegiatan.id AS id_kegiatan,
			ref__urusan.kd_urusan AS kode_urusan,
			ref__bidang.kd_bidang AS kode_bidang,
			ref__program.kd_program AS kode_program,
			ta__kegiatan.kd_keg AS kode_kegiatan,
			ref__urusan.nm_urusan AS nama_urusan,
			ref__bidang.nm_bidang AS nama_bidang,
			ref__program.nm_program AS nama_program,
			ta__kegiatan.kegiatan AS nama_kegiatan,
			ta__kegiatan.pagu,
			ta__asistensi_setuju.perencanaan,
			ta__asistensi_setuju.keuangan,
			ta__asistensi_setuju.setda,
			ta__asistensi_setuju.ttd_1,
			ta__asistensi_setuju.ttd_2,
			ta__asistensi_setuju.ttd_3
		FROM
			ta__kegiatan
		INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
		INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
		INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
		INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
		INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
		INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
		LEFT JOIN ta__asistensi_setuju ON ta__asistensi_setuju.id_keg = ta__kegiatan.id
		WHERE
			ref__sub.id_unit = ' . $unit . ' AND
			ta__kegiatan.flag = 1 AND
			ta__kegiatan.jenis_anggaran LIKE ' . $jenis_anggaran . '
			
		ORDER BY
			kode_urusan ASC,
			kode_bidang ASC,
			kode_program ASC,
			kode_kegiatan ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function asistensi_skpd($tahun = null, $bidang_bappeda = null , $jenis_anggaran = null)
	{
		if($bidang_bappeda == 'all')
		{
			$bidang_bappeda_data					= "'%'";
		}
		else
		{
			
			$bidang_bappeda_data					= $bidang_bappeda;
			$bidang_bappeda_query					= $this->db->query
			('
				SELECT
					ref__bidang_bappeda.nama_bidang
				FROM
					ref__bidang_bappeda
				WHERE
					ref__bidang_bappeda.id = ' . $bidang_bappeda . '
			')
			->row();
		}
		
		if($jenis_anggaran == 'all')
		{
			$jenis_anggaran_data					= "'%'";
		}
		else
		{
			
			$jenis_anggaran_data					= $jenis_anggaran;
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.nama_jenis_anggaran
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
			')
			->row();
		}
		
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				skpd.jumlah_kegiatan_skpd_blpu,
				skpd.jumlah_kegiatan_skpd_blu,
				asistensi.perencanaan_blpu,
				asistensi.perencanaan_blu,
				asistensi.keuangan_blpu,
				asistensi.keuangan_blu,
				asistensi.setda_blpu,
				asistensi.setda_blu,
				asistensi.selesai_blpu,
				asistensi.selesai_blu
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang_bappeda ON ref__bidang_bappeda.id = ref__unit.id_bidang_bappeda
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					Count(CASE WHEN ref__program.kd_program < 15 THEN ta__kegiatan.id ELSE NULL END) AS jumlah_kegiatan_skpd_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 THEN ta__kegiatan.id ELSE NULL END) AS jumlah_kegiatan_skpd_blu
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
				WHERE
					ta__kegiatan.flag = 1 
					AND ta__kegiatan.jenis_anggaran LIKE ' . $jenis_anggaran_data . '
				GROUP BY
					ref__sub.id_unit
			) AS skpd ON skpd.id_unit = ref__unit.id
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					Count(CASE WHEN ref__program.kd_program < 15 AND ta__asistensi_setuju.perencanaan = 1 THEN 1 ELSE NULL END) AS perencanaan_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 AND ta__asistensi_setuju.perencanaan = 1 THEN 1 ELSE NULL END) AS perencanaan_blu,
					Count(CASE WHEN ref__program.kd_program < 15 AND ta__asistensi_setuju.keuangan = 1 THEN 1 ELSE NULL END) AS keuangan_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 AND ta__asistensi_setuju.keuangan = 1 THEN 1 ELSE NULL END) AS keuangan_blu,
					Count(CASE WHEN ref__program.kd_program < 15 AND ta__asistensi_setuju.setda = 1 THEN 1 ELSE NULL END) AS setda_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 AND ta__asistensi_setuju.setda = 1 THEN 1 ELSE NULL END) AS setda_blu,
					Count(CASE WHEN ref__program.kd_program < 15 AND ta__asistensi_setuju.perencanaan = 1 AND ta__asistensi_setuju.keuangan = 1 AND ta__asistensi_setuju.setda = 1 THEN 1 ELSE NULL END) AS selesai_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 AND ta__asistensi_setuju.perencanaan = 1 AND ta__asistensi_setuju.keuangan = 1 AND ta__asistensi_setuju.setda = 1 THEN 1 ELSE NULL END) AS selesai_blu
				FROM
					ta__asistensi_setuju
				INNER JOIN ta__kegiatan ON ta__asistensi_setuju.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
				WHERE
					ta__kegiatan.flag = 1
					AND ta__kegiatan.jenis_anggaran LIKE ' . $jenis_anggaran_data . '
					
				GROUP BY
					ref__sub.id_unit
			) AS asistensi ON asistensi.id_unit = ref__unit.id
			WHERE
				ref__bidang_bappeda.id LIKE ' . $bidang_bappeda_data . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC
		')
		->result_array();
		$output										= array
		(
			'bidang_bappeda'						=> ($bidang_bappeda == 'all' ? NULL : $bidang_bappeda_query),
			'jenis_anggaran'						=> ($jenis_anggaran == 'all' ? NULL : $jenis_anggaran_query),
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	
	public function ttd_tapd_asistensi_skpd($tahun = null, $bidang_bappeda = null, $jenis_anggaran = null)
	{
		if($bidang_bappeda == 'all')
		{
			$bidang_bappeda_data					= "'%'";
		}
		else
		{
			
			$bidang_bappeda_data					= $bidang_bappeda;
			$bidang_bappeda_query					= $this->db->query
			('
				SELECT
					ref__bidang_bappeda.nama_bidang
				FROM
					ref__bidang_bappeda
				WHERE
					ref__bidang_bappeda.id = ' . $bidang_bappeda . '
			')
			->row();
		}
		
		if($jenis_anggaran == 'all')
		{
			$jenis_anggaran_data					= "'%'";
		}
		else
		{
			
			$jenis_anggaran_data					= $jenis_anggaran;
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.nama_jenis_anggaran
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
			')
			->row();
		}
		
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				skpd.jumlah_kegiatan_skpd_blpu,
				skpd.jumlah_kegiatan_skpd_blu,
				asistensi.perencanaan_blpu,
				asistensi.perencanaan_blu,
				asistensi.keuangan_blpu,
				asistensi.keuangan_blu,
				asistensi.setda_blpu,
				asistensi.setda_blu,
				asistensi.selesai_blpu,
				asistensi.selesai_blu
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang_bappeda ON ref__bidang_bappeda.id = ref__unit.id_bidang_bappeda
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					Count(CASE WHEN ref__program.kd_program < 15 THEN ta__kegiatan.id ELSE NULL END) AS jumlah_kegiatan_skpd_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 THEN ta__kegiatan.id ELSE NULL END) AS jumlah_kegiatan_skpd_blu
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
				WHERE
					ta__kegiatan.flag = 1 
					AND ta__kegiatan.jenis_anggaran LIKE ' . $jenis_anggaran_data . '
					
				GROUP BY
					ref__sub.id_unit
			) AS skpd ON skpd.id_unit = ref__unit.id
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					Count(CASE WHEN ref__program.kd_program < 15 AND ta__asistensi_setuju.ttd_1 = 1 THEN 1 ELSE NULL END) AS perencanaan_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 AND ta__asistensi_setuju.ttd_1 = 1 THEN 1 ELSE NULL END) AS perencanaan_blu,
					Count(CASE WHEN ref__program.kd_program < 15 AND ta__asistensi_setuju.ttd_2 = 1 THEN 1 ELSE NULL END) AS keuangan_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 AND ta__asistensi_setuju.ttd_2 = 1 THEN 1 ELSE NULL END) AS keuangan_blu,
					Count(CASE WHEN ref__program.kd_program < 15 AND ta__asistensi_setuju.ttd_3 = 1 THEN 1 ELSE NULL END) AS setda_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 AND ta__asistensi_setuju.ttd_3 = 1 THEN 1 ELSE NULL END) AS setda_blu,
					Count(CASE WHEN ref__program.kd_program < 15 AND ta__asistensi_setuju.ttd_1 = 1 AND ta__asistensi_setuju.ttd_2 = 1 AND ta__asistensi_setuju.ttd_3 = 1 THEN 1 ELSE NULL END) AS selesai_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 AND ta__asistensi_setuju.ttd_1 = 1 AND ta__asistensi_setuju.ttd_2 = 1 AND ta__asistensi_setuju.ttd_3 = 1 THEN 1 ELSE NULL END) AS selesai_blu
				FROM
					ta__asistensi_setuju
				INNER JOIN ta__kegiatan ON ta__asistensi_setuju.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
				WHERE
					ta__kegiatan.flag = 1
					AND ta__kegiatan.jenis_anggaran LIKE ' . $jenis_anggaran_data . '
				GROUP BY
					ref__sub.id_unit
			) AS asistensi ON asistensi.id_unit = ref__unit.id
			WHERE
				ref__bidang_bappeda.id LIKE ' . $bidang_bappeda_data . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC
		')
		->result_array();
		$output										= array
		(
			'bidang_bappeda'						=> ($bidang_bappeda == 'all' ? NULL : $bidang_bappeda_query),
			'jenis_anggaran'						=> ($jenis_anggaran == 'all' ? NULL : $jenis_anggaran_query),
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
}