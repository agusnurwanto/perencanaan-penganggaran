<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rup_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function header($kegiatan = null, $unit = null)
	{
		if((get_userdata('group_id') == 1 AND $unit) || (get_userdata('group_id') == 13 AND $unit) || get_userdata('group_id') == 5) // Admin atau SKPD
		{
			$query										= $this->db->query
			('
				SELECT
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				ref__unit.kd_unit AS kode_unit,
				ref__urusan.nm_urusan AS nama_urusan,
				ref__bidang.nm_bidang AS nama_bidang,
				ref__unit.nm_unit AS nama_unit,
				ref__unit.nama_jabatan,
				ref__unit.nama_pejabat,
				ref__unit.nip_pejabat
				FROM
				ref__unit
				INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				WHERE
				ref__unit.tahun = ' . get_userdata('year') . ' AND
				ref__unit.id = ' . $unit . '
			')
			->row();
			//print_r($query);exit;
			$query										= json_decode(json_encode($query), true);
			return $query;
		}
		return true;
	}
	
	public function daftar_rup($unit = null, $umumkan = null)
	{
		if($umumkan == "all") // Umumkan
		{
			$umumkan			= '"%"';
		}
		elseif($umumkan == NULL) // Umumkan
		{
			$umumkan			= 0;
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__unit.id = ' . $unit . '
		')
		->row();
		$rup_query									= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program AS kd_prog,
				ta__program.kd_id_prog AS id_prog,
				ta__kegiatan.kd_keg,
				ta__kegiatan.kegiatan,
				ta__kegiatan.pagu,
				pekerjaan_kegiatan.nilai_pekerjaan_kegiatan,
				pekerjaan.no,
				pekerjaan.pekerjaan,
				pekerjaan.nilai_pekerjaan
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			LEFT JOIN(
				SELECT
					ta__rup_pekerjaan.id_keg,
					ta__rup_pekerjaan.pekerjaan,
					ta__rup_pekerjaan.no,
					Sum(ta__rup_rekening.nilai) AS nilai_pekerjaan
				FROM
					ta__rup_pekerjaan
				LEFT JOIN ta__rup_rekening ON ta__rup_rekening.id_pekerjaan = ta__rup_pekerjaan.id
				INNER JOIN ta__kegiatan ON ta__rup_pekerjaan.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ta__kegiatan.flag = 1 AND
					ref__sub.id_unit = ' . $unit . ' AND
					ta__rup_pekerjaan.umumkan LIKE ' . $umumkan . '
				GROUP BY
					ta__rup_pekerjaan.id_keg,
					ta__rup_pekerjaan.pekerjaan
			) AS pekerjaan ON pekerjaan.id_keg = ta__kegiatan.id
			LEFT JOIN(
				SELECT
					ta__rup_pekerjaan.id_keg,
					Sum(ta__rup_rekening.nilai) AS nilai_pekerjaan_kegiatan
				FROM
					ta__rup_pekerjaan
				LEFT JOIN ta__rup_rekening ON ta__rup_rekening.id_pekerjaan = ta__rup_pekerjaan.id
				INNER JOIN ta__kegiatan ON ta__rup_pekerjaan.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ta__kegiatan.flag = 1 AND
					ref__sub.id_unit = ' . $unit . ' AND
					ta__rup_pekerjaan.umumkan LIKE ' . $umumkan . '
				GROUP BY
					ta__rup_pekerjaan.id_keg
			) AS pekerjaan_kegiatan ON pekerjaan_kegiatan.id_keg = ta__kegiatan.id
			WHERE
				ta__kegiatan.flag = 1
			AND ref__unit.id = ' . $unit . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC,
				ref__program.kd_program ASC,
				ta__program.kd_id_prog ASC,
				ta__kegiatan.kd_keg ASC,
				pekerjaan.`no` ASC
		')
		->result();
		//echo $this->db->last_query();exit;
		//print_r($indikator_query);exit;
		$output										= array
		(
			'header'								=> $header_query,
			'rup'									=> $rup_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_rup($tahun = null)
	{
		$data_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				pekerjaan.jumlah_pekerjaan,
				pekerjaan.belum_diumumkan,
				pekerjaan.sudah_diumumkan
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN(
				SELECT
					ref__sub.id_unit,
					COUNT(ta__rup_pekerjaan.id) AS jumlah_pekerjaan,
					COUNT(CASE WHEN ta__rup_pekerjaan.umumkan = 0 THEN 1 ELSE NULL END) AS belum_diumumkan,
					COUNT(CASE WHEN ta__rup_pekerjaan.umumkan = 1 THEN 1 ELSE NULL END) AS sudah_diumumkan
				FROM
					ta__rup_pekerjaan
				INNER JOIN ta__kegiatan ON ta__rup_pekerjaan.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.tahun = ' . $tahun . '
				GROUP BY
					ref__sub.id_unit
			) AS pekerjaan ON pekerjaan.id_unit = ref__unit.id
			WHERE
				ref__unit.tahun = ' . $tahun . '
			GROUP BY
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit 
		')
		->result();
		$output										= array
		(
			'data'									=> $data_query
		);
		//print_r($output);exit;
		return $output;
	}
}