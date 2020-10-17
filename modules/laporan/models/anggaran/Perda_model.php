<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Perda_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function lampiran_1($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
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
				rek_1.jumlah_rek_1,
				rek_2.jumlah_rek_2,
				Sum(ta__anggaran_pendapatan_rinci.total) AS jumlah_rek_3
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
					ref__rek_2.id_ref_rek_1,
					Sum(ta__anggaran_pendapatan_rinci.total) AS jumlah_rek_1
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
				WHERE
					ref__rek_1.kd_rek_1 = 4
				AND	ta__anggaran_pendapatan.tahun = ' . $tahun . '
				AND ref__sub.id_unit LIKE ' . $unit . '
				AND ta__anggaran_pendapatan.id_sub LIKE ' . $sub_unit . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__anggaran_pendapatan_rinci.total) AS jumlah_rek_2
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
				WHERE
					ref__rek_1.kd_rek_1 = 4
				AND	ta__anggaran_pendapatan.tahun = ' . $tahun . '
				AND ref__sub.id_unit LIKE ' . $unit . '
				AND ta__anggaran_pendapatan.id_sub LIKE ' . $sub_unit . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			WHERE
				ref__rek_1.kd_rek_1 = 4
			AND	ta__anggaran_pendapatan.tahun = ' . $tahun . '
			AND ref__sub.id_unit LIKE ' . $unit . '
			AND ta__anggaran_pendapatan.id_sub LIKE ' . $sub_unit . '
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
				kd_rek_1 ASC,
				kd_rek_2 ASC,
				kd_rek_3 ASC
		')
		->result();
		$belanja_query								= $this->db->query
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
				rek_1.jumlah_rek_1,
				rek_2.jumlah_rek_2,
				Sum(ta__belanja_rinci.total) AS jumlah_rek_3
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
			INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			LEFT JOIN (
				SELECT
					ref__rek_2.id_ref_rek_1,
					Sum(ta__belanja_rinci.total) AS jumlah_rek_1
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
					ta__kegiatan_sub.tahun = ' . $tahun . '
				AND ref__sub.id_unit LIKE ' . $unit . '
				AND ta__program.id_sub LIKE ' . $sub_unit . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__belanja_rinci.total) AS jumlah_rek_2
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
					ta__kegiatan_sub.tahun = ' . $tahun . '
				AND ref__sub.id_unit LIKE ' . $unit . '
				AND ta__program.id_sub LIKE ' . $sub_unit . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			WHERE
				ref__rek_1.kd_rek_1 = 5
			AND	ref__rek_6.tahun = ' . $tahun . '
			AND ref__sub.id_unit LIKE ' . $unit . '
			AND ta__program.id_sub LIKE ' . $sub_unit . '
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
				kd_rek_1 ASC,
				kd_rek_2 ASC,
				kd_rek_3 ASC
		')
		->result();
		$pembiayaan_penerimaan_query						= $this->db->query
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
				rek_1.jumlah_rek_1,
				rek_2.jumlah_rek_2,
				Sum(ta__anggaran_pembiayaan_rinci.total) AS jumlah_rek_3
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
					ref__rek_2.id_ref_rek_1,
					Sum(ta__anggaran_pembiayaan_rinci.total) AS jumlah_rek_1
				FROM
					ta__anggaran_pembiayaan_rinci
				INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
				INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__rek_1.kd_rek_1 = 6
				AND	ref__rek_2.kd_rek_2 = 1
				AND	ta__anggaran_pembiayaan.tahun = ' . $tahun . '
				AND ref__sub.id_unit LIKE ' . $unit . '
				AND ta__anggaran_pembiayaan.id_sub LIKE ' . $sub_unit . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__anggaran_pembiayaan_rinci.total) AS jumlah_rek_2
				FROM
					ta__anggaran_pembiayaan_rinci
				INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
				INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__rek_1.kd_rek_1 = 6
				AND	ref__rek_2.kd_rek_2 = 1
				AND	ta__anggaran_pembiayaan.tahun = ' . $tahun . '
				AND ref__sub.id_unit LIKE ' . $unit . '
				AND ta__anggaran_pembiayaan.id_sub LIKE ' . $sub_unit . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			WHERE
				ref__rek_1.kd_rek_1 = 6
			AND	ref__rek_2.kd_rek_2 = 1
			AND	ta__anggaran_pembiayaan.tahun = ' . $tahun . '
			AND ref__sub.id_unit LIKE ' . $unit . '
			AND ta__anggaran_pembiayaan.id_sub LIKE ' . $sub_unit . '
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
				kd_rek_1 ASC,
				kd_rek_2 ASC,
				kd_rek_3 ASC
		')
		->result();
		$pembiayaan_pengeluaran_query						= $this->db->query
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
				rek_1.jumlah_rek_1,
				rek_2.jumlah_rek_2,
				Sum(ta__anggaran_pembiayaan_rinci.total) AS jumlah_rek_3
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
					ref__rek_2.id_ref_rek_1,
					Sum(ta__anggaran_pembiayaan_rinci.total) AS jumlah_rek_1
				FROM
					ta__anggaran_pembiayaan_rinci
				INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
				INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__rek_1.kd_rek_1 = 6
				AND	ref__rek_2.kd_rek_2 = 2
				AND	ta__anggaran_pembiayaan.tahun = ' . $tahun . '
				AND ref__sub.id_unit LIKE ' . $unit . '
				AND ta__anggaran_pembiayaan.id_sub LIKE ' . $sub_unit . '
				GROUP BY
					ref__rek_2.id_ref_rek_1
			) AS rek_1 ON rek_1.id_ref_rek_1 = ref__rek_1.id
			LEFT JOIN (
				SELECT
					ref__rek_3.id_ref_rek_2,
					Sum(ta__anggaran_pembiayaan_rinci.total) AS jumlah_rek_2
				FROM
					ta__anggaran_pembiayaan_rinci
				INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
				INNER JOIN ref__sub ON ta__anggaran_pembiayaan.id_sub = ref__sub.id
				INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__rek_1.kd_rek_1 = 6
				AND	ref__rek_2.kd_rek_2 = 2
				AND	ta__anggaran_pembiayaan.tahun = ' . $tahun . '
				AND ref__sub.id_unit LIKE ' . $unit . '
				AND ta__anggaran_pembiayaan.id_sub LIKE ' . $sub_unit . '
				GROUP BY
					ref__rek_3.id_ref_rek_2
			) AS rek_2 ON rek_2.id_ref_rek_2 = ref__rek_2.id
			WHERE
				ref__rek_1.kd_rek_1 = 6
			AND	ref__rek_2.kd_rek_2 = 2
			AND	ta__anggaran_pembiayaan.tahun = ' . $tahun . '
			AND ref__sub.id_unit LIKE ' . $unit . '
			AND ta__anggaran_pembiayaan.id_sub LIKE ' . $sub_unit . '
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
				kd_rek_1 ASC,
				kd_rek_2 ASC,
				kd_rek_3 ASC
		')
		->result();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query,
			'pendapatan'							=> $pendapatan_query,
			'belanja'								=> $belanja_query,
			'pembiayaan_penerimaan'					=> $pembiayaan_penerimaan_query,
			'pembiayaan_pengeluaran'				=> $pembiayaan_pengeluaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_2($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$data_query								= $this->db->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				ref__unit.id AS id_unit,
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit,
				Sum(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 1 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_operasi,
				Sum(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 2 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_modal,
				Sum(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 3 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_tidak_terduga,
				Sum(CASE WHEN ref__rek_1.kd_rek_1 = 5 AND ref__rek_2.kd_rek_2 = 4 THEN ta__belanja_rinci.total ELSE 0 END) AS belanja_transfer
			FROM
				ta__belanja_rinci
			INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_prog = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ta__kegiatan_sub.tahun = ' . $tahun . '
			AND ref__unit.id LIKE "%"
			AND ref__sub.id LIKE "%"
			GROUP BY
				ref__urusan.id,
				ref__bidang.id,
				ref__unit.id,
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__urusan.nm_urusan,
				ref__bidang.nm_bidang,
				ref__unit.nm_unit
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC
		')
		->result();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query,
			'data'									=> $data_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_3($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_4($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_5($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_6($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_7($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_8($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_9($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_10($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_11($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_12($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_13($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_14($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_15($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function lampiran_16($tahun = null, $unit = null, $sub_unit = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($jenis_anggaran == 'aktual')
		{
			$jenis_anggaran_query					= NULL;
		}
		else
		{
			$jenis_anggaran_query					= $this->db->query
			('
				SELECT
					ref__renja_jenis_anggaran.kode,
					ref__renja_jenis_anggaran.nama_jenis_anggaran,
					ref__renja_jenis_anggaran.nomor_perda,
					ref__renja_jenis_anggaran.tanggal_perda
				FROM
					ref__renja_jenis_anggaran
				WHERE
					ref__renja_jenis_anggaran.id = ' . $jenis_anggaran . '
				LIMIT 1
			')
			->row();
		}
		$header_query								= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_daerah,
				ref__settings.nama_kepala_daerah
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
			LIMIT 1
		')
		->row();
		$output										= array
		(
			'header'								=> $header_query,
			'jenis_anggaran'						=> $jenis_anggaran_query
		);
		//print_r($output);exit;
		return $output;
	}
}