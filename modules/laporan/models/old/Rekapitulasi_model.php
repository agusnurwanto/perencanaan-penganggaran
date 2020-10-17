<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rekapitulasi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function header($sub_unit = null, $kegiatan = null, $tahun = null)
	{
		/*
		$query										= $this->db
		->select
		('
			ref_sub.kd_sub,
			ref_sub.nm_sub,
			ref_unit.*,
			ref_bidang.kd_bidang,
			ref_bidang.nm_bidang,
			ref_urusan.kd_urusan,
			ref_urusan.nm_urusan
		')
		->join('ref_unit', 'ref_unit.id = ref_sub.id_unit')
		->join('ref_bidang', 'ref_bidang.id = ref_unit.id_bidang')
		->join('ref_urusan', 'ref_urusan.id = ref_bidang.id_urusan')
		->get_where('ref_sub', array('ref_sub.id' => $sub_unit, 'ref_sub.tahun' => $tahun), 1)
		->row();
		$detail										= $this->db
		->select
		('
			kpa,
			nip_kpa,
			jabatan_kpa,
			bendahara,
			nip_bendahara,
			jabatan_bendahara
		')
		->get_where('ta_detail', array('id_keg' => $kegiatan), 1)
		->row();
		$query										= json_decode(json_encode($query), true);
		$detail										= json_decode(json_encode($detail), true);
		return array_merge
		(
			$query,
			$detail
		);
		*/
	}
	
	public function rekapitulasi_pajak($periode_awal = null, $periode_akhir = null, $sub_unit = null, $kegiatan = null, $tahun = null)
	{
		return true;
	}
	
	public function rekapitulasi_dan_verifikasi($periode_awal = null, $periode_akhir = null, $sub_unit = null, $kegiatan = null, $tahun = null)
	{
		return true;
	}
	
	public function perbandingan_ppas_dan_rka($periode_awal = null, $periode_akhir = null, $sub_unit = null, $kegiatan = null, $tahun = null)
	{
		$query										= $this->db->query
		('
			SELECT
			ref_urusan.kd_urusan,
			ref_bidang.kd_bidang,
			ref_unit.kd_unit,
			ref_sub.kd_sub,
			ref_program.kd_program,
			ta_kegiatan.kode,
			ta_kegiatan.nama,
			ta_kegiatan.pagu,
			anggaran.total_anggaran,
			(ta_kegiatan.pagu - anggaran.total_anggaran) AS selisih
			FROM
			ta_kegiatan
			INNER JOIN ta_program ON ta_kegiatan.id_prog = ta_program.id
			INNER JOIN ref_sub ON ta_program.id_sub = ref_sub.id
			INNER JOIN ref_unit ON ref_sub.id_unit = ref_unit.id
			INNER JOIN ref_bidang ON ref_unit.id_bidang = ref_bidang.id
			INNER JOIN ref_urusan ON ref_bidang.id_urusan = ref_urusan.id
			INNER JOIN ref_program ON ta_program.id_prog = ref_program.id
			LEFT JOIN (
				SELECT
				ta_belanja.id_keg,
				Sum(ta_belanja_rinc.total) AS total_anggaran
				FROM
				ta_belanja_rinc
				INNER JOIN ta_belanja_sub ON ta_belanja_rinc.id_belanja_sub = ta_belanja_sub.id
				INNER JOIN ta_belanja ON ta_belanja_sub.id_belanja = ta_belanja.id
				WHERE
				ta_belanja_rinc.tahun
				GROUP BY
				ta_belanja.id_keg
			) AS anggaran ON anggaran.id_keg = ta_kegiatan.id
			WHERE
			ta_kegiatan.tahun = ' . $tahun . '
		')
		->result_array();
		return $query;
	}
	
	public function rekapitulasi_anggaran_per_skpd($periode_awal = null, $periode_akhir = null, $sub_unit = null, $kegiatan = null, $tahun = null)
	{
		$query										= $this->db->query
		('
			SELECT
			rka__belanja.kd_urusan,
			rka__belanja.kd_bidang,
			rka__belanja.kd_unit,
			rka__belanja.kd_sub,
			sub_unit.nm_sub,
			Sum(rka__belanja.total) AS anggaran
			FROM
			rka__belanja
			LEFT JOIN (
				SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__sub.nm_sub
				FROM
				ref__sub
				INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
				INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				WHERE
				ref__sub.tahun = ' . $tahun . '
			) as sub_unit ON sub_unit.kd_urusan = rka__belanja.kd_urusan AND sub_unit.kd_bidang = rka__belanja.kd_bidang AND sub_unit.kd_unit = rka__belanja.kd_unit AND sub_unit.kd_sub = rka__belanja.kd_sub
			WHERE
			rka__belanja.tahun = ' . $tahun . '
			GROUP BY
			rka__belanja.tahun,
			rka__belanja.kd_urusan,
			rka__belanja.kd_bidang,
			rka__belanja.kd_unit,
			rka__belanja.kd_sub
			ORDER BY
			rka__belanja.kd_urusan ASC,
			rka__belanja.kd_bidang ASC,
			rka__belanja.kd_unit ASC,
			rka__belanja.kd_sub ASC
		')
		->result_array();
		return $query;
	}
	
	public function perbandingan_rka_blud_dan_simda($periode_awal = null, $periode_akhir = null, $sub_unit = null, $kegiatan = null, $tahun = null)
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '2000M');
		$connection									= $this->db->get_where('ref_koneksi', array('id' => 1), 1)->row();
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
		$db											= $this->load->database($configs, TRUE);
		$query_simda								= $db->query
		('
			SELECT
			Kd_Urusan AS Expr2,
			Kd_Bidang,
			Kd_Unit,
			Kd_Sub,
			Kd_Prog,
			ID_Prog,
			Kd_Keg,
			SUM(CASE WHEN kd_rek_3 = 1 THEN total ELSE 0 END) AS pegawai, 
			SUM(CASE WHEN kd_rek_3 = 2 THEN total ELSE 0 END) AS barang_jasa,
			SUM(CASE WHEN kd_rek_3 = 3 THEN total ELSE 0 END) AS modal,
			SUM(Total) AS Expr1
			FROM
			Ta_Belanja_Rinc_Sub
			GROUP BY
			Kd_Urusan,
			Kd_Bidang,
			Kd_Unit,
			Kd_Sub,
			Kd_Prog,
			ID_Prog,
			Kd_Keg
			HAVING
			(Kd_Urusan = 1) AND (Kd_Bidang = 2) AND (Kd_Unit = 1) AND (Kd_Sub = 2)
		')
		->result_array();
		$query_blud									= $this->db->query
		('
			SELECT
			ref_urusan.kd_urusan,
			ref_bidang.kd_bidang,
			ref_unit.kd_unit,
			ref_sub.kd_sub,
			ref_program.kd_program AS kd_prog,
			ta_program.kd_id_prog AS id_prog,
			ta_kegiatan.kode AS kd_keg,
			ta_kegiatan.nama,
			ref_rek_3.id_ref_rek_3,
			SUM(case when ref_rek_3.kd_rek_3 = 1 THEN ta_belanja_rinc.total else 0 end) AS pegawai,
			SUM(case when ref_rek_3.kd_rek_3 = 2 THEN ta_belanja_rinc.total else 0 end) AS barang_jasa,
			SUM(case when ref_rek_3.kd_rek_3 = 3 THEN ta_belanja_rinc.total else 0 end) AS modal
			FROM
			ta_belanja_rinc
			INNER JOIN ta_belanja_sub ON ta_belanja_rinc.id_belanja_sub = ta_belanja_sub.id
			INNER JOIN ta_belanja ON ta_belanja_sub.id_belanja = ta_belanja.id
			INNER JOIN ref_rek_6 ON ta_belanja.id_rek_6 = ref_rek_6.id_ref_rek_6
			INNER JOIN ref_rek_5 ON ref_rek_6.id_ref_rek_5 = ref_rek_5.id_ref_rek_5
			INNER JOIN ref_rek_4 ON ref_rek_5.id_ref_rek_4 = ref_rek_4.id_ref_rek_4
			INNER JOIN ref_rek_3 ON ref_rek_4.id_ref_rek_3 = ref_rek_3.id_ref_rek_3
			INNER JOIN ta_kegiatan ON ta_belanja.id_keg = ta_kegiatan.id
			INNER JOIN ta_program ON ta_kegiatan.id_prog = ta_program.id
			INNER JOIN ref_program ON ta_program.id_prog = ref_program.id
			INNER JOIN ref_sub ON ta_program.id_sub = ref_sub.id
			INNER JOIN ref_unit ON ref_sub.id_unit = ref_unit.id
			INNER JOIN ref_bidang ON ref_unit.id_bidang = ref_bidang.id
			INNER JOIN ref_urusan ON ref_bidang.id_urusan = ref_urusan.id
			WHERE
				ta_belanja_rinc.tahun = ' . $tahun . '
			GROUP BY
			ta_belanja.id_keg
			ORDER BY
			ref_urusan.kd_urusan ASC,
			ref_bidang.kd_bidang ASC,
			ref_unit.kd_unit ASC,
			ref_sub.kd_sub ASC,
			kd_prog ASC,
			id_prog ASC,
			kd_keg ASC
		')
		->result_array();
		$output										= array();
		foreach($query_simda as $key => $val)
		{
			foreach($query_blud as $k => $v)
			{
				if($val['Expr2'] == $v['kd_urusan'] && $val['Kd_Bidang'] == $v['kd_bidang'] && $val['Kd_Unit'] == $v['kd_unit'] && $val['Kd_Prog'] == $v['kd_prog'] && $val['ID_Prog'] == $v['id_prog'] && $val['Kd_Keg'] == $v['kd_keg'])
				{
					$output[]						= array
					(
						'kd_urusan'					=> $v['kd_urusan'],
						'kd_bidang'					=> $v['kd_bidang'],
						'kd_unit'					=> $v['kd_unit'],
						'kd_sub'					=> $v['kd_sub'],
						'kd_prog'					=> $v['kd_prog'],
						'id_prog'					=> $v['id_prog'],
						'kd_keg'					=> $v['kd_keg'],
						'kegiatan'					=> $v['nama'],
						'pegawai_blud'				=> $v['pegawai'],
						'barang_jasa_blud'			=> $v['barang_jasa'],
						'modal_blud'				=> $v['modal'],
						'pegawai_simda'				=> $val['pegawai'],
						'barang_jasa_simda'			=> $val['barang_jasa'],
						'modal_simda'				=> $val['modal']
					);
				}
			}
		}
		return $output;
	}
}