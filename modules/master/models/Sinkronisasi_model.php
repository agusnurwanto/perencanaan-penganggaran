<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sinkronisasi_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function sipd_kemendagri($tahun = 0, $unit = 0)
	{
		if($unit == "all" || $unit == null)
		{
			$unit									= "'%'";
		}
		$skpd_query								= $this->db->query
		('
			SELECT
				ref__unit.id AS id_unit,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				CONCAT(ref__urusan.kd_urusan, ".", LPAD(ref__bidang.kd_bidang, 2, "0")) AS kodebidang,
				ref__bidang.nm_bidang AS uraibidang,
				CONCAT(ref__urusan.kd_urusan, ".", LPAD(ref__bidang.kd_bidang, 2, "0"), ".", LPAD(ref__unit.kd_unit, 2, "0"), LPAD(ref__sub.kd_sub, 2, "0")) AS kodeskpd,
				ref__sub.nm_sub AS uraiskpd,
				CONCAT(ref__urusan.kd_urusan, ".", LPAD(ref__bidang.kd_bidang, 2, "0")) AS pilihanbidang,
				ref__urusan.nm_urusan AS uraiurusan
			FROM
				ref__sub
			INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__urusan.tahun = ' . $tahun . ' AND
				ref__unit.id LIKE ' . $unit . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				bidang_2.kd_bidang ASC,
				bidang_3.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC
		')
		->result();
		$pejabat_query								= $this->db->query
		('
			SELECT
				ref__unit.id AS id_unit,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__unit.nip_pejabat AS kepalanip,
				ref__unit.nama_pejabat AS kepalanama,
				ref__unit.nama_jabatan AS kepalajabatan,
				NULL AS kepalapangkat
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__urusan.tahun = ' . $tahun . ' AND
				ref__unit.id LIKE ' . $unit . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				bidang_2.kd_bidang ASC,
				bidang_3.kd_bidang ASC,
				ref__unit.kd_unit ASC
		')
		->result();
		$program_query								= $this->db->query
		('
			SELECT
				ta__program_arsip.id_unit,
				ta__program_arsip.kode_urusan,
				ta__program_arsip.kode_bidang,
				ta__program_arsip.kode_bidang_2,
				ta__program_arsip.kode_bidang_3,
				ta__program_arsip.kode_unit,
				ta__program_arsip.kode_sub,
				ta__program_arsip.kode_prog,
				ta__program_arsip.kode_id_prog AS id_prog,
				CONCAT(ta__program_arsip.kode_urusan, ".", LPAD(ta__program_arsip.kode_bidang, 2, "0"), ".", LPAD(ta__program_arsip.kode_bidang_2, 2, "0"), ".", LPAD(ta__program_arsip.kode_bidang_3, 2, "0")) AS kodebidang,
				ref__bidang.nm_bidang AS uraibidang,
				CONCAT(ta__program_arsip.kode_urusan, ".", LPAD(ta__program_arsip.kode_bidang, 2, "0"), ".", LPAD(ta__program_arsip.kode_bidang_2, 2, "0"), ".", LPAD(ta__program_arsip.kode_bidang_3, 2, "0"), "-", LPAD(ta__program_arsip.kode_prog, 4, "0")) AS kodeprogram,
				ref__program.nm_program AS uraiprogram
			FROM
				ta__program_arsip
			INNER JOIN ref__program ON ta__program_arsip.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ta__program_arsip.id_bidang = ref__bidang.id
			WHERE
				ta__program_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__program_arsip) AND 
				ta__program_arsip.tahun = ' . $tahun . ' AND 
				ta__program_arsip.id_unit LIKE ' . $unit . '
			ORDER BY
				ta__program_arsip.kode_urusan ASC,
				ta__program_arsip.kode_bidang ASC,
				ta__program_arsip.kode_bidang_2 ASC,
				ta__program_arsip.kode_bidang_3 ASC,
				ta__program_arsip.kode_unit ASC,
				ta__program_arsip.kode_sub ASC,
				ta__program_arsip.kode_prog ASC,
				ta__program_arsip.kode_id_prog ASC
		')
		->result();
		$capaian_program_query							= $this->db->query
		('
			SELECT
				ta__program_capaian_arsip.id_unit,
				ta__program_capaian_arsip.kode_urusan,
				ta__program_capaian_arsip.kode_bidang,
				ta__program_capaian_arsip.kode_bidang_2,
				ta__program_capaian_arsip.kode_bidang_3,
				ta__program_capaian_arsip.kode_unit,
				ta__program_capaian_arsip.kode_sub,
				ta__program_capaian_arsip.kode_prog,
				ta__program_capaian_arsip.kode_id_prog AS id_prog,
				ta__program_capaian_arsip.kode_capaian AS kodeindikator,
				ta__program_capaian_arsip.tolak_ukur AS tolakukur,
				ta__program_capaian_arsip.tahun_3_satuan AS satuan,
				"0.00" AS pagu,
				"0" AS pagu_p,
				"0" AS pagu_p1,
				ta__program_capaian_arsip.tahun_3_target real_p1,
				"0" AS pagu_p2,
				ta__program_capaian_arsip.tahun_3_target real_p2,
				"0" AS pagu_p3,
				ta__program_capaian_arsip.tahun_3_target AS real_p3,
				"0" AS pagu_n1,
				ta__program_capaian_arsip.tahun_3_target AS target,
				ta__program_capaian_arsip.tahun_3_target AS target_n1
			FROM
				ta__program_capaian_arsip
			WHERE
				ta__program_capaian_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__program_capaian_arsip) AND 
				ta__program_capaian_arsip.tahun = ' . $tahun . ' AND 
				ta__program_capaian_arsip.id_unit LIKE ' . $unit . '
			ORDER BY
				ta__program_capaian_arsip.kode_urusan ASC,
				ta__program_capaian_arsip.kode_bidang ASC,
				ta__program_capaian_arsip.kode_bidang_2 ASC,
				ta__program_capaian_arsip.kode_bidang_3 ASC,
				ta__program_capaian_arsip.kode_unit ASC,
				ta__program_capaian_arsip.kode_sub ASC,
				ta__program_capaian_arsip.kode_prog ASC,
				ta__program_capaian_arsip.kode_id_prog ASC,
				ta__program_capaian_arsip.kode_capaian ASC
		')
		->result();
		$kegiatan_query										= $this->db->query
		('
			SELECT
				ta__kegiatan_arsip.id_unit,
				ta__kegiatan_arsip.kode_urusan,
				ta__kegiatan_arsip.kode_bidang,
				ta__kegiatan_arsip.kode_bidang_2,
				ta__kegiatan_arsip.kode_bidang_3,
				ta__kegiatan_arsip.kode_unit,
				ta__kegiatan_arsip.kode_sub,
				ta__kegiatan_arsip.kode_prog,
				ta__kegiatan_arsip.kode_id_prog AS id_prog,
				ta__kegiatan_arsip.kode_keg,
				CONCAT(ta__kegiatan_arsip.kode_urusan, ".", LPAD(ta__kegiatan_arsip.kode_bidang, 2, "0"), "-", LPAD(ta__kegiatan_arsip.kode_bidang_2, 2, "0"), "-", LPAD(ta__kegiatan_arsip.kode_bidang_3, 2, "0"), "-", LPAD(ta__kegiatan_arsip.kode_prog, 4, "0"), ".", LPAD(ta__kegiatan_arsip.kode_keg, 4, "0")) AS kodekegiatan,
				ta__kegiatan_arsip.kegiatan AS uraikegiatan,
				kegiatan.pagu,
				kegiatan.pagu_1 AS pagu_p
			FROM
				ta__kegiatan_arsip
			LEFT JOIN (
				SELECT
					ta__kegiatan_sub_arsip.id_keg,
					SUM(ta__kegiatan_sub_arsip.pagu) AS pagu,
					SUM(ta__kegiatan_sub_arsip.pagu_1) AS pagu_1
				FROM
					ta__kegiatan_sub_arsip
				WHERE
					ta__kegiatan_sub_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_sub_arsip) AND 
					ta__kegiatan_sub_arsip.tahun = ' . $tahun . ' AND 
					ta__kegiatan_sub_arsip.id_unit LIKE ' . $unit . '
				GROUP BY
					ta__kegiatan_sub_arsip.id_keg
			) AS kegiatan ON kegiatan.id_keg = ta__kegiatan_arsip.id_keg
			WHERE
				ta__kegiatan_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_arsip) AND 
				ta__kegiatan_arsip.tahun = ' . $tahun . ' AND 
				ta__kegiatan_arsip.id_unit LIKE ' . $unit . '
			ORDER BY
				ta__kegiatan_arsip.kode_urusan ASC,
				ta__kegiatan_arsip.kode_bidang ASC,
				ta__kegiatan_arsip.kode_bidang_2 ASC,
				ta__kegiatan_arsip.kode_bidang_3 ASC,
				ta__kegiatan_arsip.kode_unit ASC,
				ta__kegiatan_arsip.kode_sub ASC,
				ta__kegiatan_arsip.kode_prog ASC,
				ta__kegiatan_arsip.kode_id_prog ASC,
				ta__kegiatan_arsip.kode_keg ASC
		')
		->result();
		$sumber_dana_query										= $this->db->query
		('
			SELECT
				ref__sumber_dana_rek_1.kd_sumber_dana_rek_1,
				ref__sumber_dana_rek_2.kd_sumber_dana_rek_2,
				ref__sumber_dana_rek_3.kd_sumber_dana_rek_3,
				ref__sumber_dana_rek_4.kd_sumber_dana_rek_4,
				ref__sumber_dana_rek_5.kd_sumber_dana_rek_5,
				ref__sumber_dana_rek_6.kode AS kd_sumber_dana_rek_6,
				CONCAT(ref__sumber_dana_rek_1.kd_sumber_dana_rek_1, ".", LPAD(ref__sumber_dana_rek_2.kd_sumber_dana_rek_2, 2, "0"), "-", LPAD(ref__sumber_dana_rek_3.kd_sumber_dana_rek_3, 2, "0"), "-", LPAD(ref__sumber_dana_rek_4.kd_sumber_dana_rek_4, 2, "0"), "-", LPAD(ref__sumber_dana_rek_5.kd_sumber_dana_rek_5, 4, "0"), ".", LPAD(ref__sumber_dana_rek_6.kode, 4, "0")) AS kodesumberdana,
				ref__sumber_dana_rek_6.nama_sumber_dana AS sumberdana,
				Sum(ta__kegiatan_sub_arsip.pagu) AS pagu
			FROM
				ta__kegiatan_sub_arsip
			INNER JOIN ref__sumber_dana_rek_6 ON ta__kegiatan_sub_arsip.id_sumber_dana = ref__sumber_dana_rek_6.id
			INNER JOIN ref__sumber_dana_rek_5 ON ref__sumber_dana_rek_6.id_sumber_dana_rek_5 = ref__sumber_dana_rek_5.id
			INNER JOIN ref__sumber_dana_rek_4 ON ref__sumber_dana_rek_5.id_sumber_dana_rek_4 = ref__sumber_dana_rek_4.id
			INNER JOIN ref__sumber_dana_rek_3 ON ref__sumber_dana_rek_4.id_sumber_dana_rek_3 = ref__sumber_dana_rek_3.id
			INNER JOIN ref__sumber_dana_rek_2 ON ref__sumber_dana_rek_3.id_sumber_dana_rek_2 = ref__sumber_dana_rek_2.id
			INNER JOIN ref__sumber_dana_rek_1 ON ref__sumber_dana_rek_2.id_sumber_dana_rek_1 = ref__sumber_dana_rek_1.id
			WHERE
				ta__kegiatan_sub_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_sub_arsip) AND 
				ta__kegiatan_sub_arsip.tahun = ' . $tahun . ' AND 
				ta__kegiatan_sub_arsip.id_unit LIKE ' . $unit . '
			GROUP BY
				ref__sumber_dana_rek_1.kd_sumber_dana_rek_1,
				ref__sumber_dana_rek_2.kd_sumber_dana_rek_2,
				ref__sumber_dana_rek_3.kd_sumber_dana_rek_3,
				ref__sumber_dana_rek_4.kd_sumber_dana_rek_4,
				ref__sumber_dana_rek_5.kd_sumber_dana_rek_5,
				ref__sumber_dana_rek_6.kode,
				ref__sumber_dana_rek_6.nama_sumber_dana
			ORDER BY
				ref__sumber_dana_rek_1.kd_sumber_dana_rek_1 ASC,
				ref__sumber_dana_rek_2.kd_sumber_dana_rek_2 ASC,
				ref__sumber_dana_rek_3.kd_sumber_dana_rek_3 ASC,
				ref__sumber_dana_rek_4.kd_sumber_dana_rek_4 ASC,
				ref__sumber_dana_rek_5.kd_sumber_dana_rek_5 ASC,
				ref__sumber_dana_rek_6.kode ASC
		')
		->result();
		$lokasi_query										= $this->db->query
		('
			SELECT
				ref__unit.id AS id_unit,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__program.kd_program AS kode_prog,
				ta__program.kd_id_prog AS id_prog,
				ta__kegiatan.kd_keg AS kode_keg,
				ta__kegiatan_sub.kd_keg_sub AS kode_keg_sub,
				ta__kegiatan_sub.map_coordinates AS kodelokasi,
				ta__kegiatan_sub.map_address AS lokasi,
				ta__kegiatan_sub.alamat_detail AS detaillokasi
			FROM
				ta__kegiatan_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__kegiatan_sub.id_keg
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__unit.id LIKE ' . $unit . '
			AND ta__kegiatan.tahun = ' . $tahun . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				bidang_2.kd_bidang ASC,
				bidang_3.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC,
				ref__program.kd_program ASC,
				ta__program.kd_id_prog ASC,
				ta__kegiatan.kd_keg ASC
		')
		->result();
		$indikator_query							= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__program.kd_program AS kode_prog,
				ta__program.kd_id_prog AS id_prog,
				ta__kegiatan.kd_keg AS kode_keg,
				ta__indikator_sub_arsip.kd_indikator AS kodeindikator,
				ref__indikator.nm_indikator AS jenis,
				ta__indikator_sub_arsip.tolak_ukur AS tolakukur,
				ta__indikator_sub_arsip.satuan,
				ta__indikator_sub_arsip.target AS real_p3,
				"0" AS pagu_p3,
				ta__indikator_sub_arsip.target AS real_p2,
				"0" AS pagu_p2,
				ta__indikator_sub_arsip.target AS real_p1,
				"0" AS pagu_p1,
				ta__indikator_sub_arsip.target,
				"0" AS pagu,
				"0" AS pagu_p,
				"0" AS target_n1,
				"0" AS pagu_n1
			FROM
				ta__indikator_sub_arsip
			INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__indikator_sub_arsip.id_keg_sub
			INNER JOIN ta__kegiatan ON ta__indikator_sub_arsip.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__indikator ON ta__indikator_sub_arsip.jns_indikator = ref__indikator.id
			WHERE
				ref__unit.id LIKE ' . $unit . ' AND
				ta__indikator_sub_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__indikator_sub_arsip) AND
				ta__kegiatan_sub.tahun = ' . $tahun . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				bidang_2.kd_bidang ASC,
				bidang_3.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC,
				ref__program.kd_program ASC,
				ta__program.kd_id_prog ASC,
				ta__kegiatan.kd_keg ASC,
				ta__indikator_sub_arsip.kd_indikator ASC
		')
		->result();
		$sub_kegiatan_query							= $this->db->query
		('
			SELECT
				ta__kegiatan_sub_arsip.kode_urusan,
				ta__kegiatan_sub_arsip.kode_bidang,
				ta__kegiatan_sub_arsip.kode_unit,
				ta__kegiatan_sub_arsip.kode_sub,
				ta__kegiatan_sub_arsip.kode_prog,
				ta__kegiatan_sub_arsip.id_prog,
				ta__kegiatan_sub_arsip.kode_keg,
				ta__kegiatan_sub_arsip.kode_kegiatan_sub,
				CONCAT(ta__kegiatan_sub_arsip.kode_urusan, ".", LPAD(ta__kegiatan_sub_arsip.kode_bidang, 2, "0"), "-", LPAD(ta__kegiatan_sub_arsip.kode_prog, 2, "0"), ".", LPAD(ta__kegiatan_sub_arsip.kode_keg, 2, "0"), ".", LPAD(ta__kegiatan_sub_arsip.kode_kegiatan_sub, 2, "0")) AS kodesubkegiatan,
				ta__kegiatan_sub_arsip.kegiatan_sub AS uraisubkegiatan,
				ta__kegiatan_sub_arsip.pagu,
				null AS pagu_p,
				null AS sumberdana,
				null AS prioritas,
				null AS lokasi
			FROM
				ta__kegiatan_sub_arsip
			WHERE
				ta__kegiatan_sub_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_sub_arsip) AND
				ta__kegiatan_sub_arsip.id_unit LIKE ' . $unit . ' AND
				ta__kegiatan_sub_arsip.tahun = ' . $tahun . '
			GROUP BY
				ta__kegiatan_sub_arsip.kode_urusan,
				ta__kegiatan_sub_arsip.kode_bidang,
				ta__kegiatan_sub_arsip.kode_unit,
				ta__kegiatan_sub_arsip.kode_sub,
				ta__kegiatan_sub_arsip.kode_prog,
				ta__kegiatan_sub_arsip.id_prog,
				ta__kegiatan_sub_arsip.kode_keg,
				ta__kegiatan_sub_arsip.kode_kegiatan_sub
			ORDER BY
				ta__kegiatan_sub_arsip.kode_urusan ASC,
				ta__kegiatan_sub_arsip.kode_bidang ASC,
				ta__kegiatan_sub_arsip.kode_unit ASC,
				ta__kegiatan_sub_arsip.kode_sub ASC,
				ta__kegiatan_sub_arsip.kode_prog ASC,
				ta__kegiatan_sub_arsip.id_prog ASC,
				ta__kegiatan_sub_arsip.kode_keg ASC,
				ta__kegiatan_sub_arsip.kode_kegiatan_sub ASC
		')
		->result();
		$indikator_sub_kegiatan_query						= $this->db->query
		('
			SELECT
				ta__indikator_sub_arsip.kd_urusan AS kode_urusan,
				ta__indikator_sub_arsip.kd_bidang AS kode_bidang,
				ta__indikator_sub_arsip.kd_bidang AS kode_bidang_2,
				ta__indikator_sub_arsip.kd_bidang AS kode_bidang_3,
				ta__indikator_sub_arsip.kd_unit AS kode_unit,
				ta__indikator_sub_arsip.kd_sub AS kode_sub,
				ta__indikator_sub_arsip.kd_program AS kode_prog,
				ta__indikator_sub_arsip.kd_id_prog AS id_prog,
				ta__indikator_sub_arsip.kd_keg AS kode_keg,
				ta__indikator_sub_arsip.kd_keg_sub AS kode_keg_sub,
				ta__indikator_sub_arsip.kd_indikator AS kodeindikator,
				ref__indikator.nm_indikator AS jenis,
				ta__indikator_sub_arsip.tolak_ukur AS tolakukur,
				ta__indikator_sub_arsip.satuan,
				ta__indikator_sub_arsip.target AS real_p3,
				"0" AS pagu_p3,
				ta__indikator_sub_arsip.target AS real_p2,
				"0" AS pagu_p2,
				ta__indikator_sub_arsip.target AS real_p1,
				"0" AS pagu_p1,
				ta__indikator_sub_arsip.target,
				"0" AS pagu,
				"0" AS pagu_p,
				"0" AS target_n1,
				"0" AS pagu_n1
			FROM
				ta__indikator_sub_arsip
			INNER JOIN ref__indikator ON ta__indikator_sub_arsip.jns_indikator = ref__indikator.id
			WHERE
				ta__indikator_sub_arsip.id_unit LIKE ' . $unit . ' AND
				ta__indikator_sub_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__indikator_sub_arsip)
			ORDER BY
				ta__indikator_sub_arsip.kd_urusan ASC,
				ta__indikator_sub_arsip.kd_bidang ASC,
				ta__indikator_sub_arsip.kd_unit ASC,
				ta__indikator_sub_arsip.kd_sub ASC,
				ta__indikator_sub_arsip.kd_program ASC,
				ta__indikator_sub_arsip.kd_id_prog ASC,
				ta__indikator_sub_arsip.kd_keg ASC,
				ta__indikator_sub_arsip.kd_indikator ASC
		')
		->result();
		
		$output										= array
		(
			'skpd'									=> $skpd_query,
			'pejabat'								=> $pejabat_query,
			'program'								=> $program_query,
			'capaian_program'						=> $capaian_program_query,
			'kegiatan'								=> $kegiatan_query,
			'sumber_dana'							=> $sumber_dana_query,
			'lokasi'								=> $lokasi_query,
			'indikator'								=> $indikator_query,
			'sub_kegiatan'							=> $sub_kegiatan_query,
			'indikator_sub_kegiatan'				=> $indikator_sub_kegiatan_query
		);
		//print_r($output);exit;
		return $output;
	}
}
