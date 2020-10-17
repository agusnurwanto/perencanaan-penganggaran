<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Anggaran_model extends CI_Model
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
	
	public function rka_221($kegiatan = null)
	{
		$header_query									= $this->db->query
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
				ta__kegiatan_sub.kegiatan_sub AS nama_kegiatan_sub,
				ta__kegiatan.capaian_program,
				ta__kegiatan_sub.pilihan,
				ta__kegiatan_sub.map_address,
				ta__kegiatan_sub.alamat_detail,
				ta__kegiatan_sub.kelompok_sasaran,
				ta__kegiatan_sub.waktu_pelaksanaan,
				ta__kegiatan_sub.pagu,
				ta__kegiatan_sub.pagu_1,
				ta__model.nm_model
			FROM
				ta__kegiatan_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ta__model ON ta__model.id = ta__kegiatan.id_model
		
			WHERE
				ta__kegiatan_sub.id = ' . $kegiatan . '
							
		')
		->result_array();
		//print_r($header_query);exit;
		$sumber_dana_query									= $this->db->query
		('
			SELECT DISTINCT
				ref__sumber_dana.nama_sumber_dana
			FROM
				ta__belanja
			INNER JOIN ref__sumber_dana ON ta__belanja.id_sumber_dana = ref__sumber_dana.id
			WHERE
				ta__belanja.id_keg_sub = ' . $kegiatan . '
		')
		->row();
		$capaian_program_query									= $this->db->query
		('
			SELECT
				ta__program_capaian.id,
				jumlah_capaian_program.jumlah_capaian_program,
				ta__program_capaian.kode,
				ta__program_capaian.tolak_ukur,
				ta__program_capaian.tahun_2_target AS target,
				ta__program_capaian.tahun_2_satuan AS satuan_target
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
		$indikator_query									= $this->db->query
		('
			SELECT
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
		$belanja_query									= $this->db->query
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
				ref__tim_anggaran.jabatan_tim,
				ref__tim_anggaran.ttd
			FROM
				ref__tim_anggaran
			WHERE
				ref__tim_anggaran.tahun = ' . get_userdata('year') . '
			ORDER BY
				ref__tim_anggaran.kode
		')
		->result_array();
		$tanggal_query								= $this->db->query
		('
			SELECT
				ref__tanggal.tanggal_rka
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . get_userdata('year') . '
			LIMIT 1
		')
		->row();
		$approval									= $this->model
		->select
		('
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
		')
		->get_where('ta__asistensi_setuju', array('ta__asistensi_setuju.id_keg' => $kegiatan), 1)
		->row();
		//echo $this->db->last_query();exit;
		//print_r($indikator_query);exit;
		$output										= array
		(
			'header'								=> $header_query,
			'sumber_dana'							=> $sumber_dana_query,
			'capaian_program'						=> $capaian_program_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'tanggal'								=> $tanggal_query,
			'approval'								=> $approval
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rkap_221($kegiatan = null)
	{
		$kode_perubahan								= 9;
		$header_query								= $this->db->query
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
				ta__kegiatan.pagu_1,
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
			LIMIT 1		
		')
		->result_array();
		//print_r($header_query);exit;
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
		->result();
		$capaian_program_query									= $this->db->query
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
		if('simda' == get_userdata('username'))
		{
			//print_r($tmp_indikator_query);exit;
		}
		
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
				tmp__rkap_indikator.jns_indikator,
				tmp__rkap_indikator.kd_indikator,
				MIN(tmp__rkap_indikator.tolak_ukur_sebelum) AS tolak_ukur_sebelum,
				SUM(tmp__rkap_indikator.target_sebelum) AS target_sebelum,
				MIN(tmp__rkap_indikator.satuan_sebelum) AS satuan_sebelum,
				MIN(tmp__rkap_indikator.tolak_ukur_setelah) AS tolak_ukur_setelah,
				SUM(tmp__rkap_indikator.target_setelah) AS target_setelah,
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
		if('simda' == get_userdata('username'))
		{
			//print_r($indikator_query);exit;
		}
		
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
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_3
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_4
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_5
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_6
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
		');
		$this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rkap_belanja_7
			(id_rek_1 int(10), id_rek_2 int(10), id_rek_3 int(10), id_rek_4 int(10), id_rek_5 int(10), id_belanja_sub int(10), id_belanja_rinc int(10), kd_rek_1 int(10), kd_rek_2 int(10), kd_rek_3 int(10), kd_rek_4 int(10), kd_rek_5 int(10), kd_belanja_sub int(10), kd_belanja_rinc int(10), nm_rek_1 varchar(255), nm_rek_2 varchar(255), nm_rek_3 varchar(255), nm_rek_4 varchar(255), nm_rek_5 varchar(255), nm_belanja_sub varchar(255), nm_belanja_rinc varchar(255), vol_1_sebelum decimal(19,2), vol_2_sebelum decimal(19,2), vol_3_sebelum decimal(19,2), vol_123_sebelum decimal(19,2), satuan_1_sebelum varchar(100), satuan_2_sebelum varchar(100), satuan_3_sebelum varchar(100), satuan_123_sebelum varchar(100), nilai_sebelum decimal(19,2), total_sebelum decimal(19,2), vol_1_setelah decimal(19,2), vol_2_setelah decimal(19,2), vol_3_setelah decimal(19,2), vol_123_setelah decimal(19,2), satuan_1_setelah varchar(100), satuan_2_setelah varchar(100), satuan_3_setelah varchar(100), satuan_123_setelah varchar(100), nilai_setelah decimal(19,2), total_setelah decimal(19,2), urutan tinyint(1))
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
		if('simda' == get_userdata('username'))
		{
			//print_r($tmp_belanja_query);exit;
		}
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
		if('simda' == get_userdata('username'))
		{
			/*$tmp__rkap_belanja									= $this->db
			->query
			('
				SELECT*
				FROM
					tmp__rkap_belanja_7
			')
			->result();
			print_r($tmp__rkap_belanja);exit;*/
		}
		
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
		if('simda' == get_userdata('username'))
		{
			//print_r($belanja_query);exit;
		}
		$tim_anggaran_query								= $this->db->query
		('
			SELECT
				ref__tim_anggaran.id,
				ref__tim_anggaran.kode,
				ref__tim_anggaran.nama_tim,
				ref__tim_anggaran.nip_tim,
				ref__tim_anggaran.jabatan_tim,
				ref__tim_anggaran.ttd
			FROM
				ref__tim_anggaran
			WHERE
				ref__tim_anggaran.tahun = ' . get_userdata('year') . '
			ORDER BY
				ref__tim_anggaran.kode
		')
		->result_array();
		$tanggal_query								= $this->db->query
		('
			SELECT
				ref__tanggal.tanggal_rka_perubahan
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . get_userdata('year') . '
			LIMIT 1
		')
		->row();
		$approval									= $this->model
		->select
		('
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
		')
		->get_where('ta__asistensi_setuju', array('ta__asistensi_setuju.id_keg' => $kegiatan), 1)
		->row();
		//echo $this->db->last_query();exit;
		//print_r($indikator_query);exit;
		$output										= array
		(
			'header'								=> $header_query,
			'sumber_dana'							=> $sumber_dana_query,
			'capaian_program'						=> $capaian_program_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'tanggal'								=> $tanggal_query,
			'approval'								=> $approval
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rka_22($unit = null)
	{
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
				ta__kegiatan.map_address,
				ta__kegiatan.pagu,
				anggaran.pegawai,
				anggaran.barang_jasa,
				anggaran.modal,
				anggaran_program.pegawai_program,
				anggaran_program.barang_jasa_program,
				anggaran_program.modal_program
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ta__belanja.id_keg,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 AND ref__rek_3.kd_rek_3 = 1 THEN ta__belanja_rinc.total ELSE 0 END) AS pegawai,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 AND ref__rek_3.kd_rek_3 = 2 THEN ta__belanja_rinc.total ELSE 0 END) AS barang_jasa,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 AND ref__rek_3.kd_rek_3 = 3 THEN ta__belanja_rinc.total ELSE 0 END) AS modal
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ta__belanja.id_keg
			) AS anggaran ON anggaran.id_keg = ta__kegiatan.id
			LEFT JOIN (
				SELECT
					ta__kegiatan.id_prog,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 AND ref__rek_3.kd_rek_3 = 1 THEN ta__belanja_rinc.total ELSE 0 END) AS pegawai_program,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 AND ref__rek_3.kd_rek_3 = 2 THEN ta__belanja_rinc.total ELSE 0 END) AS barang_jasa_program,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 AND ref__rek_3.kd_rek_3 = 3 THEN ta__belanja_rinc.total ELSE 0 END) AS modal_program
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ta__kegiatan.id_prog
			) AS anggaran_program ON anggaran_program.id_prog = ta__kegiatan.id_prog
			WHERE
				ref__sub.id_unit = ' . $unit . ' AND
				ta__kegiatan.flag = 1
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
	
	public function rekapitulasi_model_rka()
	{
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__urusan.nm_urusan,
				ref__bidang.kd_bidang,
				ref__bidang.nm_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				penggunaan_model.rka,
				penggunaan_model.model
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					Count(CASE WHEN ta__kegiatan.pilihan = 0 THEN 1 ELSE NULL END) AS rka,
					Count(CASE WHEN ta__kegiatan.pilihan = 1 THEN 1 ELSE NULL END) AS model
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ta__kegiatan.flag = 1
				GROUP BY
					ref__sub.id_unit
			) AS penggunaan_model ON penggunaan_model.id_unit = ref__unit.id
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function ringkasan($jenis_data = NULL, $tahun = NULL)
	{
		if($jenis_data == 'renja_awal')
		{
			$kode_perubahan = 1;
		}
		elseif($jenis_data == 'renja')
		{
			$kode_perubahan = 2;
		}
		elseif($jenis_data == 'renja_akhir')
		{
			$kode_perubahan = 3;
		}
		elseif($jenis_data == 'rkpd_awal')
		{
			$kode_perubahan = 4;
		}
		elseif($jenis_data == 'rkpd_akhir')
		{
			$kode_perubahan = 5;
		}
		elseif($jenis_data == 'rancangan_kua_ppas')
		{
			$kode_perubahan = 6;
		}
		else
		{
			$kode_perubahan = 7;
		}
		$header										= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah				
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
		')
		->row();
		$data										= $this->db->query
		('
			SELECT
				ref__rek_1.kd_rek_1,
				ref__rek_1.uraian AS uraian_rek_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_2.uraian AS uraian_rek_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_3.uraian AS uraian_rek_rek_3,
				rek_1.total_rek_1,
				rek_2.total_rek_2,
				rek_3.total_rek_3
			FROM
				ref__rek_3
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ta__belanja_arsip.id_rek_3,
					SUM(ta__belanja_arsip.total) AS total_rek_3
				FROM
					ta__belanja_arsip
				WHERE
					ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . '
				GROUP BY
					ta__belanja_arsip.id_rek_3
			) AS rek_3 ON rek_3.id_rek_3 = ref__rek_3.id
			LEFT JOIN (
				SELECT
					ta__belanja_arsip.id_rek_2,
					SUM(ta__belanja_arsip.total) AS total_rek_2
				FROM
					ta__belanja_arsip
				WHERE
					ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . '
				GROUP BY
					ta__belanja_arsip.id_rek_2
			) AS rek_2 ON rek_2.id_rek_2 = ref__rek_2.id
			LEFT JOIN (
				SELECT
					ta__belanja_arsip.id_rek_1,
					SUM(ta__belanja_arsip.total) AS total_rek_1
				FROM
					ta__belanja_arsip
				WHERE
					ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . '
				GROUP BY
					ta__belanja_arsip.id_rek_1
			) AS rek_1 ON rek_1.id_rek_1 = ref__rek_1.id
			WHERE
				ref__rek_3.tahun = ' . $tahun . '
			ORDER BY
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3
		')
		->result();
		$output										= array
		(
			'header'								=> $header,
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function perbandingan_plafon_anggaran_kegiatan($unit = null)
	{
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
				ta__kegiatan.map_address,
				ta__kegiatan.pagu,
				anggaran_kegiatan.jumlah_anggaran,
				anggaran_program.jumlah_program,
				anggaran_bidang.jumlah_bidang,
				anggaran_urusan.jumlah_urusan,
				program.total_program,
				bidang.total_bidang,
				urusan.total_urusan
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
			LEFT JOIN (
				SELECT
					ta__program.id,
					Sum(ta__kegiatan.pagu) AS jumlah_program
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ta__program.id
			) AS anggaran_program ON anggaran_program.id = ta__program.id
			LEFT JOIN (
				SELECT
					ref__program.id_bidang,
					Sum(ta__kegiatan.pagu) AS jumlah_bidang
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ref__program.id_bidang
			) AS anggaran_bidang ON anggaran_bidang.id_bidang = ref__bidang.id
			LEFT JOIN (
				SELECT
					ref__bidang.id_urusan,
					Sum(ta__kegiatan.pagu) AS jumlah_urusan
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ref__bidang.id_urusan
			) AS anggaran_urusan ON anggaran_urusan.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ta__belanja.id_keg,
					Sum(ta__belanja_rinc.total) AS jumlah_anggaran
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ta__belanja.id_keg
			) AS anggaran_kegiatan ON anggaran_kegiatan.id_keg = ta__kegiatan.id
			LEFT JOIN (
				SELECT
					ta__kegiatan.id_prog,
					Sum(ta__belanja_rinc.total) as total_program
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ta__kegiatan.id_prog
			) AS program ON program.id_prog = ta__program.id
			LEFT JOIN (
				SELECT
					ref__program.id_bidang,
					Sum(ta__belanja_rinc.total) as total_bidang
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ref__program.id_bidang
			) AS bidang ON bidang.id_bidang = ref__bidang.id
			LEFT JOIN (
				SELECT
					ref__bidang.id_urusan,
					Sum(ta__belanja_rinc.total) as total_urusan
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ref__bidang.id_urusan
			) AS urusan ON urusan.id_urusan = ref__urusan.id
			WHERE
				ref__sub.id_unit = ' . $unit . ' AND
				ta__kegiatan.flag = 1
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
	
	public function perbandingan_plafon_anggaran_skpd($tahun = null)
	{
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				skpd.plafon_anggaran_skpd_blpu,
				skpd.plafon_anggaran_skpd_blu,
				skpd.jumlah_kegiatan_skpd_blpu,
				skpd.jumlah_kegiatan_skpd_blu,
				pra_rka.pra_rka_blpu,
				pra_rka.pra_rka_blu,
				selesai.selesai_blpu,
				selesai.selesai_blu
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					Count(CASE WHEN ref__program.kd_program < 15 THEN ta__kegiatan.id ELSE NULL END) AS jumlah_kegiatan_skpd_blpu,
					Count(CASE WHEN ref__program.kd_program >= 15 THEN ta__kegiatan.id ELSE NULL END) AS jumlah_kegiatan_skpd_blu,
					Sum(CASE WHEN ref__program.kd_program < 15 THEN ta__kegiatan.pagu ELSE 0 END) AS plafon_anggaran_skpd_blpu,
					Sum(CASE WHEN ref__program.kd_program >= 15 THEN ta__kegiatan.pagu ELSE 0 END) AS plafon_anggaran_skpd_blu
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
				WHERE
					ta__kegiatan.flag = 1 
				GROUP BY
					ref__sub.id_unit
			) AS skpd ON skpd.id_unit = ref__unit.id
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					Sum(CASE WHEN ref__program.kd_program < 15 THEN ta__belanja_rinc.total ELSE 0 END) AS pra_rka_blpu,
					Sum(CASE WHEN ref__program.kd_program >= 15 THEN ta__belanja_rinc.total ELSE 0 END) AS pra_rka_blu
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
				WHERE
					ta__kegiatan.flag = 1 
				GROUP BY
					ref__sub.id_unit
			) AS pra_rka ON pra_rka.id_unit = ref__unit.id
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					COUNT(CASE WHEN (ta__kegiatan.pagu - IFNULL(anggaran.anggaran, 0)) = 0 AND ref__program.kd_program < 15 THEN 1 ELSE NULL END) AS selesai_blpu,
					COUNT(CASE WHEN (ta__kegiatan.pagu - IFNULL(anggaran.anggaran, 0)) = 0 AND ref__program.kd_program >= 15 THEN 1 ELSE NULL END) AS selesai_blu
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__tahun ON ta__kegiatan.tahun = ref__tahun.tahun
				LEFT JOIN (
					SELECT
						ta__belanja.id_keg,
						Sum(ta__belanja_rinc.total) AS anggaran
					FROM
						ta__belanja_rinc
					INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					GROUP BY
						ta__belanja.id_keg
				) AS anggaran ON anggaran.id_keg = ta__kegiatan.id
				WHERE
					ta__kegiatan.flag = 1
					
				GROUP BY
					ref__sub.id_unit
			) AS selesai ON selesai.id_unit = ref__unit.id
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function anggaran_kas($kegiatan = null)
	{
		$kegiatan_query									= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__kegiatan.kd_keg,
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit,
				ref__sub.nm_sub,
				ref__sub.jabatan_ppk_skpd,
				ref__sub.nama_ppk_skpd,
				ref__sub.nip_ppk_skpd,
				ref__program.nm_program,
				ta__kegiatan.kegiatan,
				ta__kegiatan.pagu,
				ta__kegiatan.kecamatan
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			WHERE
				ta__kegiatan.id = ' . $kegiatan . '
			LIMIT 1
		')
		->row();
		$tanggal_query								= $this->db->query
		('
			SELECT
				ref__tanggal.tanggal_anggaran_kas
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . get_userdata('year') . '
			LIMIT 1
		')
		->row('tanggal_anggaran_kas');
		$data_query										= $this->db->query
		('
			SELECT
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_3.uraian AS uraian_rek_3,
				ref__rek_4.uraian AS uraian_rek_4,
				ref__rek_5.uraian AS uraian_rek_5,
				pagu_rek_3.pagu_rek_3,
				pagu_rek_4.pagu_rek_4,
				pagu.pagu_rek_5,
				(ta__rencana.jan + ta__rencana.feb + ta__rencana.mar) AS tw_1,
				(ta__rencana.apr + ta__rencana.mei + ta__rencana.jun) AS tw_2,
				(ta__rencana.jul + ta__rencana.agt + ta__rencana.sep) AS tw_3,
				(ta__rencana.okt + ta__rencana.nop + ta__rencana.des) AS tw_4,
				rencana_rek_4.rencana_rek_4_tw_1,
				rencana_rek_4.rencana_rek_4_tw_2,
				rencana_rek_4.rencana_rek_4_tw_3,
				rencana_rek_4.rencana_rek_4_tw_4,
				rencana_rek_3.rencana_rek_3_tw_1,
				rencana_rek_3.rencana_rek_3_tw_2,
				rencana_rek_3.rencana_rek_3_tw_3,
				rencana_rek_3.rencana_rek_3_tw_4
			FROM
				ta__belanja
			LEFT JOIN ta__rencana ON ta__rencana.id_belanja = ta__belanja.id
			INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ta__belanja.id_rek_5,
					Sum(ta__belanja_rinc.total) AS pagu_rek_5
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				WHERE
					ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
					ta__belanja.id_rek_5
			) AS pagu ON pagu.id_rek_5 = ref__rek_5.id
			LEFT JOIN (
				SELECT
					ref__rek_5.id_ref_rek_4,
					Sum(ta__belanja_rinc.total) AS pagu_rek_4
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				WHERE
					ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
					ref__rek_5.id_ref_rek_4
			) AS pagu_rek_4 ON pagu_rek_4.id_ref_rek_4 = ref__rek_4.id
			LEFT JOIN (
				SELECT
					ref__rek_4.id_ref_rek_3,
					Sum(ta__belanja_rinc.total) AS pagu_rek_3
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
			) AS pagu_rek_3 ON pagu_rek_3.id_ref_rek_3 = ref__rek_3.id
			LEFT JOIN (
				SELECT
					ref__rek_5.id_ref_rek_4,
					Sum(ta__rencana.jan + ta__rencana.feb + ta__rencana.mar) AS rencana_rek_4_tw_1,
					Sum(ta__rencana.apr + ta__rencana.mei + ta__rencana.jun) AS rencana_rek_4_tw_2,
					Sum(ta__rencana.jul + ta__rencana.agt + ta__rencana.sep) AS rencana_rek_4_tw_3,
					Sum(ta__rencana.okt + ta__rencana.nop + ta__rencana.des) AS rencana_rek_4_tw_4
				FROM
					ta__rencana
				INNER JOIN ta__belanja ON ta__rencana.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				WHERE
					ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
					ref__rek_5.id_ref_rek_4
			) AS rencana_rek_4 ON rencana_rek_4.id_ref_rek_4 = ref__rek_4.id
			LEFT JOIN (
				SELECT
					ref__rek_4.id_ref_rek_3,
					Sum(ta__rencana.jan + ta__rencana.feb + ta__rencana.mar) AS rencana_rek_3_tw_1,
					Sum(ta__rencana.apr + ta__rencana.mei + ta__rencana.jun) AS rencana_rek_3_tw_2,
					Sum(ta__rencana.jul + ta__rencana.agt + ta__rencana.sep) AS rencana_rek_3_tw_3,
					Sum(ta__rencana.okt + ta__rencana.nop + ta__rencana.des) AS rencana_rek_3_tw_4
				FROM
					ta__rencana
				INNER JOIN ta__belanja ON ta__rencana.id_belanja = ta__belanja.id
				INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				WHERE
					ta__belanja.id_keg = ' . $kegiatan . '
				GROUP BY
					ref__rek_4.id_ref_rek_3
			) AS rencana_rek_3 ON rencana_rek_3.id_ref_rek_3 = ref__rek_3.id
			WHERE
				ta__belanja.id_keg = ' . $kegiatan . '
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC,
				ref__rek_4.kd_rek_4 ASC,
				ref__rek_5.kd_rek_5 ASC
		')
		->result();
		$output										= array
		(
			'kegiatan'								=> $kegiatan_query,
			'tanggal_anggaran_kas'					=> $tanggal_query,
			'data'									=> $data_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_anggaran_kas_kegiatan($unit = null)
	{
		$unit_query									= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit,
				ref__sub.jabatan_ppk_skpd,
				ref__sub.nama_ppk_skpd,
				ref__sub.nip_ppk_skpd
			FROM
				ref__sub
			INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__unit.id = ' . $unit . '
			LIMIT 1
		')
		->row();
		$tanggal_query								= $this->db->query
		('
			SELECT
				ref__tanggal.tanggal_anggaran_kas
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . get_userdata('year') . '
			LIMIT 1
		')
		->row('tanggal_anggaran_kas');
		$data										= $this->db->query
		('
			
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				ref__program.id AS id_program,
				ta__kegiatan.id AS id_kegiatan,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__program.kd_program AS kode_program,
				ta__kegiatan.kd_keg AS kode_kegiatan,
				ref__urusan.nm_urusan AS nama_urusan,
				ref__bidang.nm_bidang AS nama_bidang,
				ref__program.nm_program AS nama_program,
				ta__kegiatan.kegiatan AS nama_kegiatan,
				ta__kegiatan.pagu,
				rencana.tw_1,
				rencana.tw_2,
				rencana.tw_3,
				rencana.tw_4
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__tahun on ta__kegiatan.tahun=ref__tahun.tahun
			LEFT JOIN (
				SELECT
					ta__belanja.id_keg,
					Sum((ta__rencana.jan + ta__rencana.feb + ta__rencana.mar)) AS tw_1,
					Sum((ta__rencana.apr + ta__rencana.mei + ta__rencana.jun)) AS tw_2,
					Sum((ta__rencana.jul + ta__rencana.agt + ta__rencana.sep)) AS tw_3,
					Sum((ta__rencana.okt + ta__rencana.nop + ta__rencana.des)) AS tw_4
				FROM
					ta__rencana
				INNER JOIN ta__belanja ON ta__rencana.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . ' AND
					ta__kegiatan.flag = 1
				GROUP BY
					ta__belanja.id_keg
			) AS rencana ON rencana.id_keg = ta__kegiatan.id
			WHERE
				ref__sub.id_unit = ' . $unit . ' AND
				ta__kegiatan.flag = 1
			ORDER BY
				ref__sub.kd_sub ASC,
				ref__program.kd_program ASC,
				ta__kegiatan.kd_keg ASC
		')
		->result();
		$output										= array
		(
			'unit'									=> $unit_query,
			'tanggal_anggaran_kas'					=> $tanggal_query,
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_anggaran_kas_per_bulan($tahun = null)
	{
		$header_query									= $this->db->query
		('
			SELECT
				ref__settings.jabatan_sekretaris_daerah,
				ref__settings.nama_sekretaris_daerah,
				ref__settings.nip_sekretaris_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
		')
		->row();
		$tanggal_query								= $this->db->query
		('
			SELECT
				ref__tanggal.tanggal_anggaran_kas
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row('tanggal_anggaran_kas');
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__sub.nm_sub,
				anggaran.plafon,
				rencana.jan,
				rencana.feb,
				rencana.mar,
				rencana.apr,
				rencana.mei,
				rencana.jun,
				rencana.jul,
				rencana.agt,
				rencana.sep,
				rencana.okt,
				rencana.nop,
				rencana.des
			FROM
				ref__sub
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ta__program.id_sub,
					Sum(ta__belanja_rinc.total) AS plafon
				FROM
					ta__belanja_rinc
				INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				WHERE
					ta__kegiatan.flag = 1
				GROUP BY
					ta__program.id_sub
			) AS anggaran ON anggaran.id_sub = ref__sub.id
			LEFT JOIN (
				SELECT
					ta__program.id_sub,
					Sum(ta__rencana.jan) AS jan,
					Sum(ta__rencana.feb) AS feb,
					Sum(ta__rencana.mar) AS mar,
					Sum(ta__rencana.apr) AS apr,
					Sum(ta__rencana.mei) AS mei,
					Sum(ta__rencana.jun) AS jun,
					Sum(ta__rencana.jul) AS jul,
					Sum(ta__rencana.agt) AS agt,
					Sum(ta__rencana.sep) AS sep,
					Sum(ta__rencana.okt) AS okt,
					Sum(ta__rencana.nop) AS nop,
					Sum(ta__rencana.des) AS des
				FROM
					ta__rencana
				INNER JOIN ta__belanja ON ta__rencana.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan ON ta__belanja.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				GROUP BY
					ta__program.id_sub
			) AS rencana ON rencana.id_sub = ref__sub.id
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC
		')
		->result();
		$output										= array
		(
			'header'								=> $header_query,
			'tanggal_anggaran_kas'					=> $tanggal_query,
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function standar_harga($standar_harga = null, $pilihan_standar_harga = null, $tahun = null)
	{
		if($this->input->get('jenis_standar_harga') == "sht") // SHT
		{
			$jenis_standar_harga			= '= 0';
		}
		elseif($this->input->get('jenis_standar_harga') == "sbm") // SBM
		{
			$jenis_standar_harga			= '= 1';
		}
		elseif($this->input->get('jenis_standar_harga') == "all") // SHT dan SBM
		{
			$jenis_standar_harga			= 'LIKE "%"';
		}
		else
		{
			generateMessages(403, 'Silakan pilih Standar Harga untuk melihat ' . phrase($this->_request) . ' Laporan Standar Harga!', go_to());
		}
		
		if($this->input->get('pilihan_standar_harga') == "usulan") // Usulan
		{
			$pilihan_standar_harga			= '= 0';
		}
		elseif($this->input->get('pilihan_standar_harga') == "disetujui") // Disetejui
		{
			$pilihan_standar_harga			= '= 1';
		}
		elseif($this->input->get('pilihan_standar_harga') == "ditolak") // Ditolak
		{
			$pilihan_standar_harga			= '= 2';
		}
		elseif($this->input->get('pilihan_standar_harga') == "all") // SHT dan SBM
		{
			$pilihan_standar_harga			= 'LIKE "%"';
		}
		else
		{
			generateMessages(403, 'Silakan pilih Jenis  untuk melihat ' . phrase($this->_request) . ' Laporan Standar Harga!', go_to());
		}
		//print_r($jenis_standar_harga);exit;
		$query										= $this->db->query
		('
			SELECT
				ref__standar_harga.flag,
				ref__standar_harga.uraian,
				ref__standar_harga.nilai,
				ref__standar_harga.satuan_1,
				ref__standar_harga.satuan_2,
				ref__standar_harga.satuan_3,
				ref__standar_harga.deskripsi,
				ref__standar_harga.approve,
				ref__standar_harga.alasan,
				ref__standar_harga.url
			FROM
				ref__standar_harga
			WHERE
				ref__standar_harga.flag ' . $jenis_standar_harga . '
			AND ref__standar_harga.approve ' . $pilihan_standar_harga . '
			AND ref__standar_harga.tahun = ' . $tahun . '
			ORDER BY
				ref__standar_harga.uraian ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_standar_harga($tahun = null)
	{
		$query										= $this->db->query
		('
			SELECT
				Count(
					CASE
					WHEN ref__standar_harga.flag = 0
					AND ref__standar_harga.approve = 0 THEN
						1
					ELSE
						NULL
					END
				) AS jumlah_sht_usulan,
				Count(
					CASE
					WHEN ref__standar_harga.flag = 0
					AND ref__standar_harga.approve = 1 THEN
						1
					ELSE
						NULL
					END
				) AS jumlah_sht_disetujui,
				Count(
					CASE
					WHEN ref__standar_harga.flag = 0
					AND ref__standar_harga.approve = 2 THEN
						1
					ELSE
						NULL
					END
				) AS jumlah_sht_ditolak,
				Count(
					CASE
					WHEN ref__standar_harga.flag = 1
					AND ref__standar_harga.approve = 0 THEN
						1
					ELSE
						NULL
					END
				) AS jumlah_sbm_usulan,
				Count(
					CASE
					WHEN ref__standar_harga.flag = 1
					AND ref__standar_harga.approve = 1 THEN
						1
					ELSE
						NULL
					END
				) AS jumlah_sbm_disetujui,
				Count(
					CASE
					WHEN ref__standar_harga.flag = 1
					AND ref__standar_harga.approve = 2 THEN
						1
					ELSE
						NULL
					END
				) AS jumlah_sbm_ditolak
			FROM
				ref__standar_harga
			WHERE
				ref__standar_harga.tahun = ' . $tahun . '
			
		')
		->result_array();
		$output										= array
		(
			'data'									=> $query
		);
		//print_r($output);exit;
		return $output;
	}
	public function rekapitulasi_rekening($unit = null)
	{
		$unit_query									= $this->db->query
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
			LIMIT 1
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_1.uraian AS uraian_rek_1,
				ref__rek_2.uraian AS uraian_rek_2,
				ref__rek_3.uraian AS uraian_rek_3,
				ref__rek_4.uraian AS uraian_rek_4,
				ref__rek_5.uraian AS uraian_rek_5,
				Sum(ta__belanja_rinc.total) AS total_rek_5
			FROM
				ta__belanja_rinc
			INNER JOIN ta__belanja_sub ON ta__belanja_rinc.id_belanja_sub = ta__belanja_sub.id
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja.id_keg
			INNER JOIN ta__program ON ta__program.id = ta__kegiatan.id_prog
			INNER JOIN ref__sub ON ref__sub.id = ta__program.id_sub
			INNER JOIN ref__rek_5 ON ta__belanja.id_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ref__sub.id_unit LIKE ' . $unit . '
			GROUP BY
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5
			ORDER BY
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5
		')
		->result();
		$output										= array
		(
			'unit'									=> $unit_query,
			'data'									=> $query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lembar_asistensi($kegiatan = null)
	{
		$tanggapan_pendukung						= $this->model
		->select
		('
			ta__kegiatan_pendukung.tanggapan_kak,
			ta__kegiatan_pendukung.tanggapan_rkbu,
			ta__kegiatan_pendukung.tanggapan_rab,
			ta__kegiatan_pendukung.tanggapan_gambar
		')
		->get_where
		(
			'ta__kegiatan_pendukung',
			array
			(
				'ta__kegiatan_pendukung.id_keg'		=> $kegiatan
			)
		)
		->row();
		
		$capaian_program							= $this->model
		->select
		('
			ta__asistensi.uraian,
			ta__asistensi.comments,
			ta__asistensi.tanggal,
			ta__asistensi.operator,
			ta__asistensi.tanggapan,
			ta__asistensi.tanggal_tanggapan,
			ta__program_capaian.tolak_ukur
		')
		->join('ta__program_capaian', 'ta__program_capaian.id = ta__asistensi.id_jenis', 'left')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.id_keg'				=> $kegiatan,
				'ta__asistensi.jenis'				=> 1
			)
		)
		->result();
		
		$indikator									= $this->model
		->select
		('
			ta__asistensi.jenis_indikator,
			ta__asistensi.comments,
			ta__asistensi.tanggal,
			ta__asistensi.operator,
			ta__asistensi.tanggapan,
			ta__asistensi.tanggal_tanggapan,
			ta__indikator.tolak_ukur
		')
		->join('ta__indikator', 'ta__indikator.id = ta__asistensi.id_jenis', 'left')
		->order_by('ta__indikator.jns_indikator ASC, ta__indikator.kd_indikator')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.id_keg'				=> $kegiatan,
				'ta__asistensi.jenis'				=> 2
			)
		)
		->result();
		
		$kelompok_sasaran							= $this->model
		->select
		('
			ta__asistensi.jenis_indikator,
			ta__asistensi.comments,
			ta__asistensi.tanggal,
			ta__asistensi.operator,
			ta__asistensi.tanggapan,
			ta__asistensi.tanggal_tanggapan,
			ta__kegiatan.kelompok_sasaran
		')
		->join('ta__kegiatan', 'ta__kegiatan.id = ta__asistensi.id_keg')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.id_keg'				=> $kegiatan,
				'ta__asistensi.jenis'				=> 7
			)
		)
		->result();
		
		$kesesuaian									= $this->model
		->select
		('
			ta__asistensi.jenis_indikator,
			ta__asistensi.comments,
			ta__asistensi.tanggal,
			ta__asistensi.operator,
			ta__asistensi.tanggapan,
			ta__asistensi.tanggal_tanggapan
		')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.id_keg'				=> $kegiatan,
				'ta__asistensi.jenis'				=> 6
			)
		)
		->result();
		
		$belanja									= $this->model
		->select
		('
			ta__asistensi.uraian,
			ta__asistensi.comments,
			ta__asistensi.tanggal,
			ta__asistensi.operator,
			ta__asistensi.tanggapan,
			ta__asistensi.tanggal_tanggapan
		')
		->order_by('ta__asistensi.uraian ASC')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.id_keg'				=> $kegiatan,
				'ta__asistensi.jenis'				=> 3
			)
		)
		->result();
		
		$belanja_sub								= $this->model
		->select
		('
			ta__asistensi.uraian,
			ta__asistensi.comments,
			ta__asistensi.tanggal,
			ta__asistensi.operator,
			ta__asistensi.tanggapan,
			ta__asistensi.tanggal_tanggapan,
		')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.id_keg'				=> $kegiatan,
				'ta__asistensi.jenis'				=> 4
			)
		)
		->result();
		
		$belanja_rinc								= $this->model
		->select
		('
			ta__asistensi.comments,
			ta__asistensi.tanggal,
			ta__asistensi.operator,
			ta__asistensi.tanggapan,
			ta__asistensi.tanggal_tanggapan,
			ta__belanja_rinc.*
		')
		->join('ta__belanja_rinc', 'ta__belanja_rinc.id = ta__asistensi.id_jenis', 'left')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.id_keg'				=> $kegiatan,
				'ta__asistensi.jenis'				=> 5
			)
		)
		->result();
		
		$kegiatan_query								= $this->model
		->select
		('
			ta__kegiatan.id,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__program.kd_program,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ref__sub.nm_sub,
			ta__kegiatan.kd_keg,
			ta__kegiatan.kegiatan,
			ta__kegiatan.pagu,
			ref__bidang_bappeda.nama_bidang
		')
		->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog', 'INNER')
		->join('ref__sub', 'ref__sub.id = ta__program.id_sub', 'INNER')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit', 'INNER')
		->join('ref__bidang_bappeda', 'ref__bidang_bappeda.id = ref__unit.id_bidang_bappeda', 'INNER')
		->join('ref__program', 'ref__program.id = ta__program.id_prog', 'INNER')
		->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang', 'INNER')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan', 'INNER')
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'					=> $kegiatan
			),
			1
		)
		->row();
		
		$approval									= $this->model->select
		('
			ta__asistensi_setuju.perencanaan,
			ta__asistensi_setuju.waktu_verifikasi_perencanaan,
			ta__asistensi_setuju.nama_operator_perencanaan,
			ta__asistensi_setuju.keuangan,
			ta__asistensi_setuju.waktu_verifikasi_keuangan,
			ta__asistensi_setuju.nama_operator_keuangan,
			ta__asistensi_setuju.setda,
			ta__asistensi_setuju.waktu_verifikasi_setda,
			ta__asistensi_setuju.nama_operator_setda
		')
		->get_where('ta__asistensi_setuju', array('ta__asistensi_setuju.id_keg' => $kegiatan), 1)
		->row();
		
		$output										= array
		(
			'tanggapan_pendukung'					=> $tanggapan_pendukung,
			'capaian_program'						=> $capaian_program,
			'indikator'								=> $indikator,
			'kelompok_sasaran'						=> $kelompok_sasaran,
			'kesesuaian'							=> $kesesuaian,
			'belanja'								=> $belanja,
			'belanja_sub'							=> $belanja_sub,
			'belanja_rinc'							=> $belanja_rinc,
			'kegiatan'								=> $kegiatan_query,
			'approval'								=> $approval
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lembar_kak($kegiatan = null)
	{
		$tanggapan_pendukung						= $this->model
		->select
		('
			ta__kegiatan_pendukung.tanggapan_kak,
			ta__kegiatan_pendukung.tanggapan_rkbu,
			ta__kegiatan_pendukung.tanggapan_rab,
			ta__kegiatan_pendukung.tanggapan_gambar
		')
		->get_where
		(
			'ta__kegiatan_pendukung',
			array
			(
				'ta__kegiatan_pendukung.id_keg'		=> $kegiatan
			)
		)
		->row();
		
		$capaian_program							= $this->model
		->select
		('
			ta__asistensi_kak.uraian,
			ta__asistensi_kak.comments,
			ta__asistensi_kak.tanggal,
			ta__asistensi_kak.operator,
			ta__asistensi_kak.tanggapan,
			ta__asistensi_kak.tanggal_tanggapan,
			ta__program_capaian.tolak_ukur
		')
		->join('ta__program_capaian', 'ta__program_capaian.id = ta__asistensi_kak.sub_jenis', 'left')
		->get_where
		(
			'ta__asistensi_kak',
			array
			(
				'ta__asistensi_kak.id_keg'			=> $kegiatan,
				'ta__asistensi_kak.jenis'			=> 1
			)
		)
		->result();
		
		$indikator									= $this->model
		->select
		('
			ta__asistensi_kak.sub_jenis_id,
			ta__asistensi_kak.comments,
			ta__asistensi_kak.tanggal,
			ta__asistensi_kak.operator,
			ta__asistensi_kak.tanggapan,
			ta__asistensi_kak.tanggal_tanggapan,
			ta__indikator.tolak_ukur
		')
		->join('ta__indikator', 'ta__indikator.id = ta__asistensi_kak.sub_jenis', 'left')
		->order_by('ta__indikator.jns_indikator ASC, ta__indikator.kd_indikator')
		->get_where
		(
			'ta__asistensi_kak',
			array
			(
				'ta__asistensi_kak.id_keg'			=> $kegiatan,
				'ta__asistensi_kak.jenis'			=> 2
			)
		)
		->result();
		
		$kelompok_sasaran							= $this->model
		->select
		('
			ta__asistensi_kak.sub_jenis_id,
			ta__asistensi_kak.comments,
			ta__asistensi_kak.tanggal,
			ta__asistensi_kak.operator,
			ta__asistensi_kak.tanggapan,
			ta__asistensi_kak.tanggal_tanggapan,
			ta__kegiatan.kelompok_sasaran
		')
		->join('ta__kegiatan', 'ta__kegiatan.id = ta__asistensi_kak.id_keg')
		->get_where
		(
			'ta__asistensi_kak',
			array
			(
				'ta__asistensi_kak.id_keg'			=> $kegiatan,
				'ta__asistensi_kak.jenis'			=> 7
			)
		)
		->result();
		
		$kesesuaian									= $this->model
		->select
		('
			ta__asistensi_kak.sub_jenis_id,
			ta__asistensi_kak.comments,
			ta__asistensi_kak.tanggal,
			ta__asistensi_kak.operator,
			ta__asistensi_kak.tanggapan,
			ta__asistensi_kak.tanggal_tanggapan
		')
		->get_where
		(
			'ta__asistensi_kak',
			array
			(
				'ta__asistensi_kak.id_keg'			=> $kegiatan,
				'ta__asistensi_kak.jenis'			=> 6
			)
		)
		->result();
		
		$belanja									= $this->model
		->select
		('
			ta__asistensi_kak.uraian,
			ta__asistensi_kak.comments,
			ta__asistensi_kak.tanggal,
			ta__asistensi_kak.operator,
			ta__asistensi_kak.tanggapan,
			ta__asistensi_kak.tanggal_tanggapan
		')
		->order_by('ta__asistensi_kak.uraian ASC')
		->get_where
		(
			'ta__asistensi_kak',
			array
			(
				'ta__asistensi_kak.id_keg'			=> $kegiatan,
				'ta__asistensi_kak.jenis'			=> 3
			)
		)
		->result();
		
		$belanja_sub								= $this->model
		->select
		('
			ta__asistensi_kak.uraian,
			ta__asistensi_kak.comments,
			ta__asistensi_kak.tanggal,
			ta__asistensi_kak.operator,
			ta__asistensi_kak.tanggapan,
			ta__asistensi_kak.tanggal_tanggapan,
		')
		->get_where
		(
			'ta__asistensi_kak',
			array
			(
				'ta__asistensi_kak.id_keg'			=> $kegiatan,
				'ta__asistensi_kak.jenis'			=> 4
			)
		)
		->result();
		
		$belanja_rinc								= $this->model
		->select
		('
			ta__asistensi_kak.comments,
			ta__asistensi_kak.tanggal,
			ta__asistensi_kak.operator,
			ta__asistensi_kak.tanggapan,
			ta__asistensi_kak.tanggal_tanggapan,
			ta__belanja_rinc.*
		')
		->join('ta__belanja_rinc', 'ta__belanja_rinc.id = ta__asistensi_kak.sub_jenis', 'left')
		->get_where
		(
			'ta__asistensi_kak',
			array
			(
				'ta__asistensi_kak.id_keg'			=> $kegiatan,
				'ta__asistensi_kak.jenis'			=> 5
			)
		)
		->result();
		
		$kegiatan_query								= $this->model
		->select
		('
			ta__kegiatan.id,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__program.kd_program,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ref__sub.nm_sub,
			ta__kegiatan.kd_keg,
			ta__kegiatan.kegiatan,
			ta__kegiatan.pagu,
			ref__bidang_bappeda.nama_bidang
		')
		->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog', 'INNER')
		->join('ref__sub', 'ref__sub.id = ta__program.id_sub', 'INNER')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit', 'INNER')
		->join('ref__bidang_bappeda', 'ref__bidang_bappeda.id = ref__unit.id_bidang_bappeda', 'INNER')
		->join('ref__program', 'ref__program.id = ta__program.id_prog', 'INNER')
		->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang', 'INNER')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan', 'INNER')
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'					=> $kegiatan
			),
			1
		)
		->row();
		
		$approval									= $this->model->select
		('
			ta__asistensi_kak_setuju.perencanaan,
			ta__asistensi_kak_setuju.waktu_verifikasi_perencanaan,
			ta__asistensi_kak_setuju.nama_operator_perencanaan,
			ta__asistensi_kak_setuju.keuangan,
			ta__asistensi_kak_setuju.waktu_verifikasi_keuangan,
			ta__asistensi_kak_setuju.nama_operator_keuangan,
			ta__asistensi_kak_setuju.setda,
			ta__asistensi_kak_setuju.waktu_verifikasi_setda,
			ta__asistensi_kak_setuju.nama_operator_setda
		')
		->get_where('ta__asistensi_kak_setuju', array('ta__asistensi_kak_setuju.id_keg' => $kegiatan), 1)
		->row();
		
		$output										= array
		(
			'tanggapan_pendukung'					=> $tanggapan_pendukung,
			'capaian_program'						=> $capaian_program,
			'indikator'								=> $indikator,
			'kelompok_sasaran'						=> $kelompok_sasaran,
			'kesesuaian'							=> $kesesuaian,
			'belanja'								=> $belanja,
			'belanja_sub'							=> $belanja_sub,
			'belanja_rinc'							=> $belanja_rinc,
			'kegiatan'								=> $kegiatan_query,
			'approval'								=> $approval
		);
		//print_r($output);exit;
		return $output;
	}
}