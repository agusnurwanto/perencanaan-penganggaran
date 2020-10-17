<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Dpa_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function dpa_skpd($tahun = null, $unit = null, $sub_unit = null)
	{
		if($sub_unit == 'all')
		{
			$sub_unit								= "'%'";
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__urusan.nm_urusan,
				ref__bidang.kd_bidang,
				ref__bidang.nm_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				ref__unit.nama_jabatan,
				ref__unit.nama_pejabat,
				ref__unit.nip_pejabat
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__unit.id = ' . $unit . '
			AND ref__unit.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$pendapatan_query								= $this->db->query
		('
			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_3,
				rek_2.subtotal_rek_2,
				rek_1.subtotal_rek_1
			FROM
				ta__anggaran_pendapatan_rinci
			INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
			INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_2
				FROM
				ta__anggaran_pendapatan_rinci
				INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			LEFT JOIN (
				SELECT
					ref__rek_2.id_ref_rek_1,
					Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_1
				FROM
					ta__anggaran_pendapatan_rinci
				INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ref__sub.id_unit = ' . $unit . '
			GROUP BY
				ref__rek_1.id,
				ref__rek_2.id,
				ref__rek_3.id,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_1.uraian,
				ref__rek_2.uraian,
				ref__rek_3.uraian
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC
		')
		->result();
		$belanja_query									= $this->db->query
		('
			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				Sum(ta__belanja_rinci.total) AS subtotal_rek_3,
				rek_2.subtotal_rek_2,
				rek_1.subtotal_rek_1
			FROM
				ta__belanja_rinci
			INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_2
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				AND ta__kegiatan_sub.flag = 1
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			LEFT JOIN (
				SELECT
					ref__rek_2.id_ref_rek_1,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_1
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				AND ta__kegiatan_sub.flag = 1
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ref__sub.id_unit = ' . $unit . '
			AND ta__kegiatan_sub.tahun = ' . $tahun . '
			AND ta__kegiatan_sub.flag = 1
			GROUP BY
				ref__rek_1.id,
				ref__rek_2.id,
				ref__rek_3.id,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_1.uraian,
				ref__rek_2.uraian,
				ref__rek_3.uraian
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC
		')
		->result();
		$pembiayaan_query									= $this->db->query
		('
			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				Sum(ta__anggaran_pembiayaan_rinci.total) AS subtotal_rek_3,
				rek_2.subtotal_rek_2,
				rek_1.subtotal_rek_1
			FROM
				ta__anggaran_pembiayaan_rinci
			INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
			INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__anggaran_pembiayaan_rinci.total) AS subtotal_rek_2
				FROM
				ta__anggaran_pembiayaan_rinci
				INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			LEFT JOIN (
				SELECT
					ref__rek_2.id_ref_rek_1,
					Sum(ta__anggaran_pembiayaan_rinci.total) AS subtotal_rek_1
				FROM
					ta__anggaran_pembiayaan_rinci
				INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ref__sub.id_unit = ' . $unit . '
			GROUP BY
				ref__rek_1.id,
				ref__rek_2.id,
				ref__rek_3.id,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_1.uraian,
				ref__rek_2.uraian,
				ref__rek_3.uraian
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC
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
				ref__tanggal.tanggal_rka
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'pendapatan'							=> $pendapatan_query,
			'belanja'								=> $belanja_query,
			'pembiayaan'							=> $pembiayaan_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'tanggal'								=> $tanggal_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function dpa_pendapatan_skpd($tahun = null, $unit = null, $sub_unit = null)
	{
		if($sub_unit == 'all')
		{
			$sub_unit								= "'%'";
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__urusan.nm_urusan,
				ref__bidang.kd_bidang,
				ref__bidang.nm_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				ref__unit.nama_jabatan,
				ref__unit.nama_pejabat,
				ref__unit.nip_pejabat
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__unit.id = ' . $unit . '
			AND ref__unit.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$data_query									= $this->db->query
		('
			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ref__rek_6.id AS id_rek_6,
				ta__anggaran_pendapatan_rinci.id AS id_pendapatan_rinci,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				ref__rek_4.uraian AS nm_rek_4,
				ref__rek_5.uraian AS nm_rek_5,
				ref__rek_6.uraian AS nm_rek_6,
				ta__anggaran_pendapatan_rinci.kd_anggaran_pendapatan_rinci,
				ta__anggaran_pendapatan_rinci.uraian AS nama_rincian,
				ta__anggaran_pendapatan_rinci.vol_1,
				ta__anggaran_pendapatan_rinci.vol_2,
				ta__anggaran_pendapatan_rinci.vol_3,
				ta__anggaran_pendapatan_rinci.satuan_1,
				ta__anggaran_pendapatan_rinci.satuan_2,
				ta__anggaran_pendapatan_rinci.satuan_3,
				ta__anggaran_pendapatan_rinci.nilai,
				ta__anggaran_pendapatan_rinci.vol_123,
				ta__anggaran_pendapatan_rinci.satuan_123,
				ta__anggaran_pendapatan_rinci.total,
				rek_1.subtotal_rek_1,
				rek_2.subtotal_rek_2,
				rek_3.subtotal_rek_3,
				rek_4.subtotal_rek_4,
				rek_5.subtotal_rek_5,
				rek_6.subtotal_rek_6
			FROM
				ta__anggaran_pendapatan_rinci
			INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
			INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
			INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ta__anggaran_pendapatan.id_rek_6,
					Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_6
				FROM
					ta__anggaran_pendapatan_rinci
				INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
				INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ta__anggaran_pendapatan.id_rek_6
			) AS rek_6 ON rek_6.id_rek_6 = ref__rek_6.id
			LEFT JOIN (
				SELECT
					ref__rek_6.id_ref_rek_5,
					Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_5
				FROM
					ta__anggaran_pendapatan_rinci
				INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
				INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_6.id_ref_rek_5
			) AS rek_5 ON rek_5.id_ref_rek_5 = ref__rek_5.id
			LEFT JOIN (
				SELECT
					ref__rek_5.id_ref_rek_4,
					Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_4
				FROM
					ta__anggaran_pendapatan_rinci
				INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
				INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_5.id_ref_rek_4
			) AS rek_4 ON rek_4.id_ref_rek_4 = ref__rek_4.id
			LEFT JOIN (
				SELECT
					ref__rek_4.id_ref_rek_3,
					Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_3
				FROM
					ta__anggaran_pendapatan_rinci
				INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
				INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_4.id_ref_rek_3
			) AS rek_3 ON rek_3.id_ref_rek_3 = ref__rek_3.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_2
				FROM
					ta__anggaran_pendapatan_rinci
				INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
				INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			LEFT JOIN (
				SELECT
					ref__rek_2.id_ref_rek_1,
					Sum(ta__anggaran_pendapatan_rinci.total) AS subtotal_rek_1
				FROM
					ta__anggaran_pendapatan_rinci
				INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
				INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ta__anggaran_pendapatan.tahun = ' . $tahun . '
			AND ref__sub.id_unit = ' . $unit . '
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC,
				ref__rek_4.kd_rek_4 ASC,
				ref__rek_5.kd_rek_5 ASC,
				ref__rek_6.kd_rek_6 ASC,
				ta__anggaran_pendapatan_rinci.kd_anggaran_pendapatan_rinci ASC,
				ta__anggaran_pendapatan_rinci.uraian ASC
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
				ref__tanggal.tanggal_rka
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		/*$approval									= $this->model->query
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
		->row();*/
		$output										= array
		(
			'header'								=> $header_query,
			'data'									=> $data_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'tanggal'								=> $tanggal_query,
			//'approval'								=> $approval
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function dpa_belanja_skpd($tahun = null, $unit = null)
	{
		$header_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				ref__unit.nama_jabatan,
				ref__unit.nama_pejabat,
				ref__unit.nip_pejabat
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__unit.id = ' . $unit . '
			AND ref__unit.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		/*
		$data_query									= $this->db->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				ref__program.id AS id_program,
				ta__kegiatan.id AS id_kegiatan,
				ta__kegiatan_sub.id AS id_sub_kegiatan,
				
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__program.kd_program,
				ta__kegiatan.kd_keg,
				ta__kegiatan_sub.kd_keg_sub,
				
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__program.nm_program,
				ta__kegiatan.kegiatan,
				ta__kegiatan_sub.kegiatan_sub,
				ta__kegiatan_sub.pagu,
				ta__kegiatan_sub.pagu_1,
				
				ref__sumber_dana_rek_1.kd_sumber_dana_rek_1,
				ref__sumber_dana_rek_2.kd_sumber_dana_rek_2,
				ref__sumber_dana_rek_3.kd_sumber_dana_rek_3,
				ref__sumber_dana_rek_4.kd_sumber_dana_rek_4,
				ref__sumber_dana_rek_5.kd_sumber_dana_rek_5,
				ref__sumber_dana_rek_6.kode,
				ref__sumber_dana_rek_6.nama_sumber_dana,
				
				ta__kegiatan_sub.kecamatan,
				ta__kegiatan_sub.kelurahan,
				ta__kegiatan_sub.map_address,
				ta__kegiatan_sub.alamat_detail,
				
				anggaran_urusan.belanja_operasi_urusan,
				anggaran_urusan.belanja_modal_urusan,
				anggaran_urusan.belanja_tidak_terduga_urusan,
				anggaran_urusan.belanja_transfer_urusan,
				anggaran_urusan.pagu_1_urusan,
				
				anggaran_bidang.belanja_operasi_bidang,
				anggaran_bidang.belanja_modal_bidang,
				anggaran_bidang.belanja_tidak_terduga_bidang,
				anggaran_bidang.belanja_transfer_bidang,
				anggaran_bidang.pagu_1_bidang,
				
				anggaran_program.belanja_operasi_program,
				anggaran_program.belanja_modal_program,
				anggaran_program.belanja_tidak_terduga_program,
				anggaran_program.belanja_transfer_program,
				anggaran_program.pagu_1_program,
				
				anggaran_kegiatan.belanja_operasi_kegiatan,
				anggaran_kegiatan.belanja_modal_kegiatan,
				anggaran_kegiatan.belanja_tidak_terduga_kegiatan,
				anggaran_kegiatan.belanja_transfer_kegiatan,
				anggaran_kegiatan.pagu_1_kegiatan,
				
				anggaran_sub_kegiatan.belanja_operasi_sub_kegiatan,
				anggaran_sub_kegiatan.belanja_modal_sub_kegiatan,
				anggaran_sub_kegiatan.belanja_tidak_terduga_sub_kegiatan,
				anggaran_sub_kegiatan.belanja_transfer_sub_kegiatan
			FROM
				ta__kegiatan_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__sumber_dana_rek_6 ON ta__kegiatan_sub.id_sumber_dana = ref__sumber_dana_rek_6.id
			INNER JOIN ref__sumber_dana_rek_5 ON ref__sumber_dana_rek_6.id_sumber_dana_rek_5 = ref__sumber_dana_rek_5.id
			INNER JOIN ref__sumber_dana_rek_4 ON ref__sumber_dana_rek_5.id_sumber_dana_rek_4 = ref__sumber_dana_rek_4.id
			INNER JOIN ref__sumber_dana_rek_3 ON ref__sumber_dana_rek_4.id_sumber_dana_rek_3 = ref__sumber_dana_rek_3.id
			INNER JOIN ref__sumber_dana_rek_2 ON ref__sumber_dana_rek_3.id_sumber_dana_rek_2 = ref__sumber_dana_rek_2.id
			INNER JOIN ref__sumber_dana_rek_1 ON ref__sumber_dana_rek_2.id_sumber_dana_rek_1 = ref__sumber_dana_rek_1.id
			LEFT JOIN (
				SELECT
					ref__bidang.id_urusan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_urusan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_urusan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_urusan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_urusan,
					SUM(ta__kegiatan_sub.pagu_1) AS pagu_1_urusan
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				
				LEFT JOIN ta__belanja ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				INNER JOIN ta__belanja_sub ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__belanja_rinci ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ref__bidang.id_urusan
			) AS anggaran_urusan ON anggaran_urusan.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ref__program.id_bidang,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_bidang,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_bidang,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_bidang,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_bidang,
					SUM(ta__kegiatan_sub.pagu_1) AS pagu_1_bidang
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				LEFT JOIN ta__belanja ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				INNER JOIN ta__belanja_sub ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__belanja_rinci ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ref__program.id_bidang
			) AS anggaran_bidang ON anggaran_bidang.id_bidang = ref__bidang.id
			LEFT JOIN (
				SELECT
					ta__kegiatan.id_prog,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_program,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_program,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_program,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_program,
					SUM(ta__kegiatan_sub.pagu_1) AS pagu_1_program
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				LEFT JOIN ta__belanja ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				INNER JOIN ta__belanja_sub ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__belanja_rinci ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__kegiatan.id_prog
			) AS anggaran_program ON anggaran_program.id_prog = ta__program.id
			LEFT JOIN (
				SELECT
					ta__kegiatan_sub.id_keg,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_kegiatan,
					SUM(ta__kegiatan_sub.pagu_1) AS pagu_1_kegiatan
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				LEFT JOIN ta__belanja ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				LEFT JOIN ta__belanja_sub ON ta__belanja_sub.id_belanja = ta__belanja.id
				LEFT JOIN ta__belanja_rinci ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				LEFT JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				LEFT JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				LEFT JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				LEFT JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				LEFT JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__kegiatan_sub.id_keg
			) AS anggaran_kegiatan ON anggaran_kegiatan.id_keg = ta__kegiatan.id
			LEFT JOIN (
				SELECT
					ta__belanja.id_keg_sub,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_sub_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_sub_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_sub_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_sub_kegiatan
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__belanja.id_keg_sub
			) AS anggaran_sub_kegiatan ON anggaran_sub_kegiatan.id_keg_sub = ta__kegiatan_sub.id
			WHERE
				ref__sub.id_unit = ' . $unit . '
			AND ta__kegiatan_sub.tahun = ' . $tahun . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__program.kd_program ASC,
				ta__kegiatan.kd_keg ASC,
				ta__kegiatan_sub.kd_keg_sub ASC
		')
		->result();
		*/
		$data_query									= $this->db->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				ref__program.id AS id_program,
				ta__kegiatan.id AS id_kegiatan,
				ta__kegiatan_sub.id AS id_sub_kegiatan,
				
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__program.kd_program,
				ta__kegiatan.kd_keg,
				ta__kegiatan_sub.kd_keg_sub,
				
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__program.nm_program,
				ta__kegiatan.kegiatan,
				ta__kegiatan_sub.kegiatan_sub,
				ta__kegiatan_sub.pagu,
				ta__kegiatan_sub.pagu_1,
				
				ref__sumber_dana_rek_1.kd_sumber_dana_rek_1,
				ref__sumber_dana_rek_2.kd_sumber_dana_rek_2,
				ref__sumber_dana_rek_3.kd_sumber_dana_rek_3,
				ref__sumber_dana_rek_4.kd_sumber_dana_rek_4,
				ref__sumber_dana_rek_5.kd_sumber_dana_rek_5,
				ref__sumber_dana_rek_6.kode,
				ref__sumber_dana_rek_6.nama_sumber_dana,
				
				ta__kegiatan_sub.kecamatan,
				ta__kegiatan_sub.kelurahan,
				ta__kegiatan_sub.map_address,
				ta__kegiatan_sub.alamat_detail,
				
				anggaran_urusan.belanja_operasi_urusan,
				anggaran_urusan.belanja_modal_urusan,
				anggaran_urusan.belanja_tidak_terduga_urusan,
				anggaran_urusan.belanja_transfer_urusan,
				pagu_1_urusan.pagu_1_urusan,
				
				anggaran_bidang.belanja_operasi_bidang,
				anggaran_bidang.belanja_modal_bidang,
				anggaran_bidang.belanja_tidak_terduga_bidang,
				anggaran_bidang.belanja_transfer_bidang,
				pagu_1_bidang.pagu_1_bidang,
				
				anggaran_program.belanja_operasi_program,
				anggaran_program.belanja_modal_program,
				anggaran_program.belanja_tidak_terduga_program,
				anggaran_program.belanja_transfer_program,
				pagu_1_program.pagu_1_program,
				
				anggaran_kegiatan.belanja_operasi_kegiatan,
				anggaran_kegiatan.belanja_modal_kegiatan,
				anggaran_kegiatan.belanja_tidak_terduga_kegiatan,
				anggaran_kegiatan.belanja_transfer_kegiatan,
				pagu_1_kegiatan.pagu_1_kegiatan,
				
				anggaran_sub_kegiatan.belanja_operasi_sub_kegiatan,
				anggaran_sub_kegiatan.belanja_modal_sub_kegiatan,
				anggaran_sub_kegiatan.belanja_tidak_terduga_sub_kegiatan,
				anggaran_sub_kegiatan.belanja_transfer_sub_kegiatan
			FROM
				ta__kegiatan_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__sumber_dana_rek_6 ON ta__kegiatan_sub.id_sumber_dana = ref__sumber_dana_rek_6.id
			INNER JOIN ref__sumber_dana_rek_5 ON ref__sumber_dana_rek_6.id_sumber_dana_rek_5 = ref__sumber_dana_rek_5.id
			INNER JOIN ref__sumber_dana_rek_4 ON ref__sumber_dana_rek_5.id_sumber_dana_rek_4 = ref__sumber_dana_rek_4.id
			INNER JOIN ref__sumber_dana_rek_3 ON ref__sumber_dana_rek_4.id_sumber_dana_rek_3 = ref__sumber_dana_rek_3.id
			INNER JOIN ref__sumber_dana_rek_2 ON ref__sumber_dana_rek_3.id_sumber_dana_rek_2 = ref__sumber_dana_rek_2.id
			INNER JOIN ref__sumber_dana_rek_1 ON ref__sumber_dana_rek_2.id_sumber_dana_rek_1 = ref__sumber_dana_rek_1.id
			LEFT JOIN (
				SELECT
					ref__bidang.id_urusan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_urusan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_urusan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_urusan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_urusan
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ref__bidang.id_urusan
			) AS anggaran_urusan ON anggaran_urusan.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ref__program.id_bidang,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_bidang,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_bidang,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_bidang,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_bidang
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ref__program.id_bidang
			) AS anggaran_bidang ON anggaran_bidang.id_bidang = ref__bidang.id
			LEFT JOIN (
				SELECT
					ta__kegiatan.id_prog,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_program,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_program,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_program,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_program
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__kegiatan.id_prog
			) AS anggaran_program ON anggaran_program.id_prog = ta__program.id
			LEFT JOIN (
				SELECT
					ta__kegiatan_sub.id_keg,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_kegiatan
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__kegiatan_sub.id_keg
			) AS anggaran_kegiatan ON anggaran_kegiatan.id_keg = ta__kegiatan.id
			LEFT JOIN (
				SELECT
					ta__belanja.id_keg_sub,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi_sub_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal_sub_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga_sub_kegiatan,
					SUM(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer_sub_kegiatan
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__belanja.id_keg_sub
			) AS anggaran_sub_kegiatan ON anggaran_sub_kegiatan.id_keg_sub = ta__kegiatan_sub.id
			LEFT JOIN (
				SELECT
					ta__kegiatan_sub.id_keg,
					Sum(ta__kegiatan_sub.pagu_1) AS pagu_1_kegiatan
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__kegiatan_sub.id_keg
			) AS pagu_1_kegiatan ON pagu_1_kegiatan.id_keg = ta__kegiatan.id
			LEFT JOIN (
				SELECT
					ta__kegiatan.id_prog,
					Sum(ta__kegiatan_sub.pagu_1) AS pagu_1_program
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__kegiatan.id_prog
			) AS pagu_1_program ON pagu_1_program.id_prog = ta__program.id
			LEFT JOIN (
				SELECT
					ref__program.id_bidang,
					Sum(ta__kegiatan_sub.pagu_1) AS pagu_1_bidang
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ref__program.id_bidang
			) AS pagu_1_bidang ON pagu_1_bidang.id_bidang = ref__bidang.id
			LEFT JOIN (
				SELECT
					ref__bidang.id_urusan,
					Sum(ta__kegiatan_sub.pagu_1) AS pagu_1_urusan
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ref__bidang.id_urusan
			) AS pagu_1_urusan ON pagu_1_urusan.id_urusan = ref__urusan.id
			WHERE
				ref__sub.id_unit = ' . $unit . '
			AND ta__kegiatan_sub.tahun = ' . $tahun . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__program.kd_program ASC,
				ta__kegiatan.kd_keg ASC,
				ta__kegiatan_sub.kd_keg_sub ASC
		')
		->result();
		$output										= array
		(
			'header'								=> $header_query,
			'data'									=> $data_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function dpa_rincian_belanja($tahun = null, $sub_unit = null, $program = null, $kegiatan = null)
	{
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
				kegiatan_pagu.total_pagu_kegiatan,
				kegiatan_pagu_1.total_pagu_kegiatan_1
			FROM
				ta__kegiatan
			LEFT JOIN ta__program_capaian ON ta__program_capaian.id = ta__kegiatan.capaian_program
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ta__kegiatan_sub.id_keg,
					Sum(ta__belanja_rinci.total) AS total_pagu_kegiatan
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__kegiatan_sub.id_keg
				LIMIT 1
			) AS kegiatan_pagu ON kegiatan_pagu.id_keg = ta__kegiatan.id
			LEFT JOIN (
				SELECT
					ta__kegiatan_sub.id_keg,
					Sum(ta__kegiatan_sub.pagu_1) AS total_pagu_kegiatan_1
				FROM
					ta__kegiatan_sub
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				GROUP BY
					ta__kegiatan_sub.id_keg
				LIMIT 1
			) AS kegiatan_pagu_1 ON kegiatan_pagu_1.id_keg = ta__kegiatan.id
			WHERE
				ta__kegiatan.id = ' . $kegiatan . '
			AND ta__kegiatan.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		//print_r($header_query);exit;
		/*$sumber_dana_query									= $this->db->query
		('
			SELECT DISTINCT
				ref__sumber_dana_rek_6.nama_sumber_dana
			FROM
				ta__belanja
			INNER JOIN ref__sumber_dana_rek_6 ON ta__belanja.id_sumber_dana = ref__sumber_dana_rek_6.id
			WHERE
				ta__belanja.id_keg_sub = ' . $sub_kegiatan . '
			LIMIT 1
		')
		->row();*/
		
		/*$capaian_program_query									= $this->db->query
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
			LEFT JOIN (
				SELECT
					ta__program_capaian.id_prog,
					count(ta__program_capaian.id) AS jumlah_capaian_program
				FROM
					ta__program_capaian
				WHERE
					ta__program_capaian.id_prog = ' . $program . '
				GROUP BY
					ta__program_capaian.id_prog
			) AS jumlah_capaian_program ON jumlah_capaian_program.id_prog = ta__program_capaian.id_prog
			WHERE
				ta__program_capaian.id_prog = ' . $program . '
		')
		->result();*/
		$indikator_kegiatan_query							= $this->db->query
		('
			SELECT
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
		
		/*$indikator_sub_kegiatan_query						= $this->db->query
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
		->result();*/
		
		$belanja_query									= $this->db->query
		('
			SELECT
				ta__belanja.id_keg_sub,
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ref__rek_6.id AS id_rek_6,
				ta__belanja_sub.id AS id_belanja_sub,
				ta__belanja_rinci.id AS id_belanja_rinci,
				
				ta__kegiatan_sub.kd_keg_sub,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ta__belanja_sub.kd_belanja_sub,
				ta__belanja_rinci.kd_belanja_rinci,
				
				ta__kegiatan_sub.kegiatan_sub,
				ta__kegiatan_sub.kecamatan,
				ta__kegiatan_sub.kelurahan,
				ta__kegiatan_sub.alamat_detail,
				ta__kegiatan_sub.waktu_pelaksanaan_mulai,
				ta__kegiatan_sub.waktu_pelaksanaan_sampai,
				ref__sumber_dana_rek_6.nama_sumber_dana,
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
			INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
			LEFT JOIN ref__sumber_dana_rek_6 ON ref__sumber_dana_rek_6.id = ta__kegiatan_sub.id_sumber_dana
			INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ref__rek_2.id_ref_rek_1,
					ta__belanja.id_keg_sub,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_1
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				GROUP BY
					ref__rek_2.id_ref_rek_1,
					ta__belanja.id_keg_sub
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id AND rek_1.id_keg_sub = ta__kegiatan_sub.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					ta__belanja.id_keg_sub,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_2
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				GROUP BY
					ref__rek_3.id_ref_rek_2,
					ta__belanja.id_keg_sub
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id AND rek_2.id_keg_sub = ta__kegiatan_sub.id
			LEFT JOIN (
				SELECT
					ref__rek_4.id_ref_rek_3,
					ta__belanja.id_keg_sub,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_3
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				GROUP BY
					ref__rek_4.id_ref_rek_3,
					ta__belanja.id_keg_sub
			) AS rek_3 ON rek_3.id_ref_rek_3 = ref__rek_3.id AND rek_3.id_keg_sub = ta__kegiatan_sub.id
			LEFT JOIN (
				SELECT
					ref__rek_5.id_ref_rek_4,
					ta__belanja.id_keg_sub,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_4
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				GROUP BY
					ref__rek_5.id_ref_rek_4,
					ta__belanja.id_keg_sub
			) AS rek_4 ON rek_4.id_ref_rek_4 = ref__rek_4.id AND rek_4.id_keg_sub = ta__kegiatan_sub.id
			LEFT JOIN (
				SELECT
					ref__rek_6.id_ref_rek_5,
					ta__belanja.id_keg_sub,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_5
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				GROUP BY
					ref__rek_6.id_ref_rek_5,
					ta__belanja.id_keg_sub
			) AS rek_5 ON rek_5.id_ref_rek_5 = ref__rek_5.id AND rek_5.id_keg_sub = ta__kegiatan_sub.id
			LEFT JOIN (
				SELECT
					ta__belanja_sub.id_belanja,
					ta__belanja.id_keg_sub,
					Sum(ta__belanja_rinci.total) AS subtotal_rek_6
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				GROUP BY
					ta__belanja_sub.id_belanja,
					ta__belanja.id_keg_sub
			) AS rek_6 ON rek_6.id_belanja = ta__belanja_sub.id_belanja AND rek_6.id_keg_sub = ta__kegiatan_sub.id
			LEFT JOIN (
				SELECT
					ta__belanja_rinci.id_belanja_sub,
					ta__belanja.id_keg_sub,
					Sum(ta__belanja_rinci.total) AS subtotal_rinci
				FROM
					ta__belanja_rinci
				INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
				INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
				INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
				WHERE
					ta__kegiatan_sub.id_keg = ' . $kegiatan . '
				GROUP BY
					ta__belanja_rinci.id_belanja_sub,
					ta__belanja.id_keg_sub
			) AS belanja_sub ON belanja_sub.id_belanja_sub = ta__belanja_sub.id AND belanja_sub.id_keg_sub = ta__kegiatan_sub.id
			WHERE
				ta__kegiatan_sub.id_keg = ' . $kegiatan . '
			ORDER BY
				ta__kegiatan_sub.kd_keg_sub ASC,
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
				ref__tim_anggaran.tahun = ' . $tahun . '
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
				ref__tanggal.tahun = ' . $tahun . '
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
			INNER JOIN ta__kegiatan_sub ON ta__asistensi_setuju.id_keg_sub = ta__kegiatan_sub.id
			WHERE
				ta__kegiatan_sub.id_keg = ' . $kegiatan . '
		')
		->result();
		//echo $this->db->last_query();exit;
		//print_r($indikator_query);exit;
		$output										= array
		(
			'header'								=> $header_query,
			//'sumber_dana'							=> $sumber_dana_query,
			//'capaian_program'						=> $capaian_program_query,
			'indikator'								=> $indikator_kegiatan_query,
			//'indikator_sub'							=> $indikator_sub_kegiatan_query,
			'belanja'								=> $belanja_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'tanggal'								=> $tanggal_query,
			'approval'								=> $approval
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function dpa_sub_kegiatan($tahun = null, $sub_unit = null, $program = null, $kegiatan = null, $sub_kegiatan = null)
	{
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
				ta__kegiatan_sub.waktu_pelaksanaan_mulai,
				ta__kegiatan_sub.waktu_pelaksanaan_sampai,
				ta__kegiatan_sub.kelompok_sasaran,
				ta__kegiatan_sub.map_address,
				ta__kegiatan_sub.alamat_detail,
				ta__kegiatan_sub.kelurahan,
				ta__kegiatan_sub.kecamatan,
				ta__kegiatan_sub.id_jenis_anggaran,
				ta__kegiatan_sub.waktu_pelaksanaan_mulai,
				ta__kegiatan_sub.waktu_pelaksanaan_sampai,
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
			LEFT JOIN ta__model ON ta__model.id = ta__kegiatan_sub.id_model
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
		//print_r($header_query);exit;
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
		->result();
		//print_r($sumber_dana_query);exit;
		
		/*$capaian_program_query									= $this->db->query
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
			LEFT JOIN (
				SELECT
					ta__program_capaian.id_prog,
					count(ta__program_capaian.id) AS jumlah_capaian_program
				FROM
					ta__program_capaian
				WHERE
					ta__program_capaian.id_prog = ' . $program . '
				GROUP BY
					ta__program_capaian.id_prog
			) AS jumlah_capaian_program ON jumlah_capaian_program.id_prog = ta__program_capaian.id_prog
			WHERE
				ta__program_capaian.id_prog = ' . $program . '
		')
		->result();*/
		$indikator_kegiatan_query							= $this->db->query
		('
			SELECT
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
		//print_r($indikator_kegiatan_query);exit;
		
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
		//print_r($indikator_sub_kegiatan_query);exit;
		
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
		//print_r($belanja_query);exit;
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
		//echo $this->db->last_query();exit;
		//print_r($indikator_query);exit;
		$output										= array
		(
			'header'								=> $header_query,
			'sumber_dana'							=> $sumber_dana_query,
			//'capaian_program'						=> $capaian_program_query,
			'indikator'								=> $indikator_kegiatan_query,
			'indikator_sub'							=> $indikator_sub_kegiatan_query,
			'belanja'								=> $belanja_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'tanggal'								=> $tanggal_query,
			'approval'								=> $approval
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function dpa_pembiayaan_skpd($tahun = null, $unit = null, $sub_unit = null)
	{
		if($sub_unit == 'all')
		{
			$sub_unit								= "'%'";
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__urusan.nm_urusan,
				ref__bidang.kd_bidang,
				ref__bidang.nm_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				ref__unit.nama_jabatan,
				ref__unit.nama_pejabat,
				ref__unit.nip_pejabat
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__unit.id = ' . $unit . '
			AND ref__unit.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$pembiayaan_query									= $this->db->query
		('
			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				Sum(ta__anggaran_pembiayaan_rinci.total) AS subtotal_rek_3,
				rek_2.subtotal_rek_2,
				rek_1.subtotal_rek_1
			FROM
				ta__anggaran_pembiayaan_rinci
			INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
			INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__anggaran_pembiayaan_rinci.total) AS subtotal_rek_2
				FROM
				ta__anggaran_pembiayaan_rinci
				INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			LEFT JOIN (
				SELECT
					ref__rek_2.id_ref_rek_1,
					Sum(ta__anggaran_pembiayaan_rinci.total) AS subtotal_rek_1
				FROM
					ta__anggaran_pembiayaan_rinci
				INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				WHERE
					ref__sub.id_unit = ' . $unit . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ref__sub.id_unit = ' . $unit . '
			GROUP BY
				ref__rek_1.id,
				ref__rek_2.id,
				ref__rek_3.id,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_1.uraian,
				ref__rek_2.uraian,
				ref__rek_3.uraian
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC
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
				ref__tanggal.tanggal_rka
			FROM
				ref__tanggal
			WHERE
				ref__tanggal.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'pembiayaan'							=> $pembiayaan_query,
			'tim_anggaran'							=> $tim_anggaran_query,
			'tanggal'								=> $tanggal_query
		);
		//print_r($output);exit;
		return $output;
	}
}