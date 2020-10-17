<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Rencana_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function perencanaan($tahun = null, $unit = null, $sub_unit = null, $sumber_dana = null, $jenis_anggaran = null)
	{
		if($unit == 'all' || $unit == NULL)
		{
			$unit									= "'%'";
			$header_query							= $this->db->query
			('
				SELECT
					ref__settings.jabatan_sekretaris_daerah AS nama_jabatan,
					ref__settings.nama_sekretaris_daerah AS nama_pejabat,
					ref__settings.nip_sekretaris_daerah AS nip_pejabat
				FROM
					ref__settings
				WHERE
					ref__settings.tahun = ' . $tahun . '
				LIMIT 1
			')
			->row();
		}
		else
		{
			$header_query							= $this->db->query
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
		}
		if($sub_unit == 'all' || $sub_unit == NULL)
		{
			$sub_unit								= "'%'";
		}
		if($sumber_dana == 'all')
		{
			$sumber_dana							= "'%'";
			$sumber_dana_query						= 'Semua Sumber Dana Terpilih';
		}
		else
		{
			$sumber_dana_query									= $this->db->query
			('
				SELECT
					CONCAT(ref__sumber_dana_rek_1.kd_sumber_dana_rek_1, ".",
					ref__sumber_dana_rek_2.kd_sumber_dana_rek_2, ".",
					ref__sumber_dana_rek_3.kd_sumber_dana_rek_3, ".",
					ref__sumber_dana_rek_4.kd_sumber_dana_rek_4, ".",
					ref__sumber_dana_rek_5.kd_sumber_dana_rek_5, " ",
					ref__sumber_dana_rek_6.nama_sumber_dana) AS sumber_dana
				FROM
					ref__sumber_dana_rek_6
				INNER JOIN ref__sumber_dana_rek_5 ON ref__sumber_dana_rek_6.id_sumber_dana_rek_5 = ref__sumber_dana_rek_5.id
				INNER JOIN ref__sumber_dana_rek_4 ON ref__sumber_dana_rek_5.id_sumber_dana_rek_4 = ref__sumber_dana_rek_4.id
				INNER JOIN ref__sumber_dana_rek_3 ON ref__sumber_dana_rek_4.id_sumber_dana_rek_3 = ref__sumber_dana_rek_3.id
				INNER JOIN ref__sumber_dana_rek_2 ON ref__sumber_dana_rek_3.id_sumber_dana_rek_2 = ref__sumber_dana_rek_2.id
				INNER JOIN ref__sumber_dana_rek_1 ON ref__sumber_dana_rek_2.id_sumber_dana_rek_1 = ref__sumber_dana_rek_1.id
				WHERE
					ref__sumber_dana_rek_6.id = ' . $sumber_dana . '
				AND ref__sumber_dana_rek_6.tahun = ' . $tahun . '
				LIMIT 1
			')
			->row('sumber_dana');
		}
		
		if($jenis_anggaran == 0 || $jenis_anggaran == NULL)
		{
			$capaian_program_query						= $this->db->query
			('
				SELECT
					ta__program_capaian.id_prog AS id_ta__program ,
					ta__program_capaian.id_prog,
					ta__program_capaian.kode,
					ta__program_capaian.tolak_ukur,
					ta__program_capaian.tahun_2_target AS target,
					ta__program_capaian.tahun_2_satuan AS satuan_target
				FROM
					ta__program_capaian
				INNER JOIN ta__program ON ta__program_capaian.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				WHERE
					ref__sub.id_unit LIKE ' . $unit . ' AND
					ref__sub.id LIKE ' . $sub_unit . '
				ORDER BY
					ref__urusan.kd_urusan ASC,
					ref__bidang.kd_bidang ASC,
					ref__unit.kd_unit ASC,
					ref__sub.kd_sub ASC,
					ref__program.kd_program ASC,
					ta__program.kd_id_prog ASC,
					ta__program_capaian.kode ASC
			')
			->result_array();
			$indikator_kegiatan_query					= $this->db->query
			('
				SELECT
					ta__indikator.id_keg,
					ta__indikator.jns_indikator,
					ta__indikator.kd_indikator,
					ta__indikator.tolak_ukur,
					ta__indikator.target,
					ta__indikator.satuan
				FROM
					ta__indikator
				INNER JOIN ta__kegiatan ON ta__indikator.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit LIKE ' . $unit . ' AND
					ref__sub.id LIKE ' . $sub_unit . '
				ORDER BY
					ta__indikator.id_keg ASC,
					ta__indikator.jns_indikator ASC,
					ta__indikator.kd_indikator ASC
			')
			->result_array();
			$indikator_sub_kegiatan_query				= $this->db->query
			('
				SELECT
					ta__indikator_sub.id_keg_sub,
					ta__indikator_sub.jns_indikator,
					ta__indikator_sub.kd_indikator,
					ta__indikator_sub.tolak_ukur,
					ta__indikator_sub.target,
					ta__indikator_sub.satuan
				FROM
					ta__indikator_sub
				INNER JOIN ta__kegiatan_sub ON ta__indikator_sub.id_keg_sub = ta__kegiatan_sub.id
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__sub.id_unit LIKE ' . $unit . ' AND
					ref__sub.id LIKE ' . $sub_unit . '
				ORDER BY
					ta__indikator_sub.id_keg_sub ASC,
					ta__indikator_sub.jns_indikator ASC,
					ta__indikator_sub.kd_indikator ASC
			')
			->result_array();
		
			$data_query									= $this->db->query
			('
				SELECT
					ref__urusan.id AS id_urusan,
					ref__bidang.id AS id_bidang,
					ref__unit.id AS id_unit,
					ref__sub.id AS id_sub,
					ref__program.id AS id_prog,
					ta__program.id AS id_ta__program,
					ta__kegiatan.id AS id_kegiatan,
					ta__kegiatan_sub.id AS id_kegiatan_sub,
					ref__urusan.kd_urusan,
					ref__bidang.kd_bidang,
					ref__unit.kd_unit,
					ref__sub.kd_sub,
					ref__program.kd_program,
					ta__program.kd_id_prog,
					ta__kegiatan.kd_keg,
					ta__kegiatan_sub.kd_keg_sub,
					ref__urusan.nm_urusan,
					ref__bidang.nm_bidang,
					ref__unit.nm_unit,
					ref__sub.nm_sub,
					ref__program.nm_program,
					ta__kegiatan.kegiatan,
					ta__kegiatan_sub.kegiatan_sub,
					ta__kegiatan_sub.kelurahan,
					ta__kegiatan_sub.kecamatan,
					ta__kegiatan_sub.pagu AS pagu_sub_kegiatan,
					sub_kegiatan_rka.sub_kegiatan_rka,
					
					kegiatan.pagu_kegiatan,
					kegiatan_rka.kegiatan_rka,
					
					program.pagu_program,
					program_rka.program_rka,
					
					bidang.pagu_bidang,
					bidang_rka.bidang_rka,
					
					urusan.pagu_urusan,
					urusan_rka.urusan_rka
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
				INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				
				LEFT JOIN (
					SELECT
						ref__bidang.id_urusan,
						Sum(ta__kegiatan_sub.pagu) AS pagu_urusan
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__bidang.id_urusan
				) AS urusan ON urusan.id_urusan = ref__urusan.id
				LEFT JOIN (
					SELECT
						ref__program.id_bidang,
						Sum(ta__kegiatan_sub.pagu) AS pagu_bidang
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__program.id_bidang
				) AS bidang ON bidang.id_bidang = ref__bidang.id
				LEFT JOIN (
					SELECT
						ta__kegiatan.id_prog,
						Sum(ta__kegiatan_sub.pagu) AS pagu_program
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan.id_prog
				) AS program ON program.id_prog = ta__program.id
				LEFT JOIN (
					SELECT
						ta__kegiatan_sub.id_keg,
						Sum(ta__kegiatan_sub.pagu) AS pagu_kegiatan
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan_sub.id_keg
				) AS kegiatan ON kegiatan.id_keg = ta__kegiatan.id
				
				LEFT JOIN (
					SELECT
						ref__bidang.id_urusan,
						Sum(ta__belanja_rinci.total) AS urusan_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__bidang.id_urusan
				) AS urusan_rka ON urusan_rka.id_urusan = ref__urusan.id
				LEFT JOIN (
					SELECT
						ref__program.id_bidang,
						Sum(ta__belanja_rinci.total) AS bidang_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__program.id_bidang
				) AS bidang_rka ON bidang_rka.id_bidang = ref__bidang.id
				LEFT JOIN (
					SELECT
						ta__kegiatan.id_prog,
						Sum(ta__belanja_rinci.total) AS program_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan.id_prog
				) AS program_rka ON program_rka.id_prog = ta__program.id
				LEFT JOIN (
					SELECT
						ta__kegiatan_sub.id_keg,
						Sum(ta__belanja_rinci.total) AS kegiatan_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan_sub.id_keg
				) AS kegiatan_rka ON kegiatan_rka.id_keg = ta__kegiatan.id
				LEFT JOIN (
					SELECT
						ta__belanja.id_keg_sub,
						Sum(ta__belanja_rinci.total) AS sub_kegiatan_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND	ref__sub.id LIKE ' . $sub_unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__belanja.id_keg_sub
				) AS sub_kegiatan_rka ON sub_kegiatan_rka.id_keg_sub = ta__kegiatan_sub.id
				WHERE
					ta__kegiatan_sub.flag = 1
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				AND ref__unit.id LIKE ' . $unit . '
				AND	ref__sub.id LIKE ' . $sub_unit . '
				AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
				ORDER BY
					ref__urusan.kd_urusan ASC,
					ref__bidang.kd_bidang ASC,
					ref__unit.kd_unit ASC,
					ref__sub.kd_sub ASC,
					ref__program.kd_program ASC,
					ta__program.kd_id_prog ASC,
					ta__kegiatan.kd_keg ASC,
					ta__kegiatan_sub.kd_keg_sub ASC
			')
			->result();
		}
		else
		{
			$capaian_program_query						= $this->db->query
			('
				SELECT
					ta__program_capaian_arsip.id_program AS id_ta_program,
					ta__program_capaian_arsip.id_program AS id_prog,
					ta__program_capaian_arsip.kode_capaian AS kode,
					ta__program_capaian_arsip.tolak_ukur,
					ta__program_capaian_arsip.tahun_2_target AS target,
					ta__program_capaian_arsip.tahun_2_satuan AS satuan_target
				FROM
					ta__program_capaian_arsip
				WHERE
					ta__program_capaian_arsip.tahun = ' . $tahun . '
				AND ta__program_capaian_arsip.kode_perubahan = ' . $jenis_anggaran . '
				AND ta__program_capaian_arsip.id_unit LIKE ' . $unit . '
				AND ta__program_capaian_arsip.id_sub LIKE ' . $sub_unit . '
				ORDER BY
					ta__program_capaian_arsip.kode_urusan ASC,
					ta__program_capaian_arsip.kode_bidang ASC,
					ta__program_capaian_arsip.kode_bidang_2 ASC,
					ta__program_capaian_arsip.kode_bidang_3 ASC,
					ta__program_capaian_arsip.kode_unit ASC,
					ta__program_capaian_arsip.kode_sub ASC,
					ta__program_capaian_arsip.kode_prog ASC,
					ta__program_capaian_arsip.kode_id_prog ASC,
					kode ASC
			')
			->result_array();
			$indikator_kegiatan_query					= $this->db->query
			('
				SELECT
					ta__indikator_arsip.id_keg,
					ta__indikator_arsip.jns_indikator,
					ta__indikator_arsip.kd_indikator,
					ta__indikator_arsip.tolak_ukur,
					ta__indikator_arsip.target,
					ta__indikator_arsip.satuan
				FROM
					ta__indikator_arsip
				WHERE
					ta__indikator_arsip.tahun = ' . $tahun . '
				AND ta__indikator_arsip.id_unit LIKE ' . $unit . '
				AND ta__indikator_arsip.id_sub LIKE ' . $sub_unit . '
				AND ta__indikator_arsip.kode_perubahan = ' . $jenis_anggaran . '
				ORDER BY
					ta__indikator_arsip.tahun ASC,
					ta__indikator_arsip.jns_indikator ASC,
					ta__indikator_arsip.kd_urusan ASC,
					ta__indikator_arsip.kd_bidang ASC,
					ta__indikator_arsip.kd_bidang_2 ASC,
					ta__indikator_arsip.kd_bidang_3 ASC,
					ta__indikator_arsip.kd_unit ASC,
					ta__indikator_arsip.kd_sub ASC,
					ta__indikator_arsip.kd_program ASC,
					ta__indikator_arsip.kd_id_prog ASC,
					ta__indikator_arsip.kd_keg ASC,
					ta__indikator_arsip.kd_indikator ASC
			')
			->result_array();
			$indikator_sub_kegiatan_query				= $this->db->query
			('
				SELECT
					ta__indikator_sub_arsip.id_keg_sub,
					ta__indikator_sub_arsip.jns_indikator,
					ta__indikator_sub_arsip.kd_indikator,
					ta__indikator_sub_arsip.tolak_ukur,
					ta__indikator_sub_arsip.target,
					ta__indikator_sub_arsip.satuan
				FROM
					ta__indikator_sub_arsip
				WHERE
					ta__indikator_sub_arsip.kode_perubahan = ' . $jenis_anggaran . '
				AND ta__indikator_sub_arsip.id_unit LIKE ' . $unit . '
				AND ta__indikator_sub_arsip.id_sub LIKE ' . $sub_unit . '
				ORDER BY
					ta__indikator_sub_arsip.kd_urusan ASC,
					ta__indikator_sub_arsip.kd_bidang ASC,
					ta__indikator_sub_arsip.kd_bidang_2 ASC,
					ta__indikator_sub_arsip.kd_bidang_3 ASC,
					ta__indikator_sub_arsip.kd_unit ASC,
					ta__indikator_sub_arsip.kd_sub ASC,
					ta__indikator_sub_arsip.kd_program ASC,
					ta__indikator_sub_arsip.kd_id_prog ASC,
					ta__indikator_sub_arsip.kd_keg ASC,
					ta__indikator_sub_arsip.kd_keg_sub ASC,
					ta__indikator_sub_arsip.jns_indikator ASC,
					ta__indikator_sub_arsip.kd_indikator ASC
			')
			->result_array();
		
			$data_query									= $this->db->query
			('
				SELECT
					ref__urusan.id AS id_urusan,
					ref__bidang.id AS id_bidang,
					ref__unit.id AS id_unit,
					ref__sub.id AS id_sub,
					ref__program.id AS id_prog,
					ta__program.id AS id_ta__program,
					ta__kegiatan.id AS id_kegiatan,
					ta__kegiatan_sub.id AS id_kegiatan_sub,
					ref__urusan.kd_urusan,
					ref__bidang.kd_bidang,
					ref__unit.kd_unit,
					ref__sub.kd_sub,
					ref__program.kd_program,
					ta__program.kd_id_prog,
					ta__kegiatan.kd_keg,
					ta__kegiatan_sub.kd_keg_sub,
					ref__urusan.nm_urusan,
					ref__bidang.nm_bidang,
					ref__unit.nm_unit,
					ref__sub.nm_sub,
					ref__program.nm_program,
					ta__kegiatan.kegiatan,
					ta__kegiatan_sub.kegiatan_sub,
					ta__kegiatan_sub.kelurahan,
					ta__kegiatan_sub.kecamatan,
					ta__kegiatan_sub.pagu AS pagu_sub_kegiatan,
					sub_kegiatan_rka.sub_kegiatan_rka,
					
					kegiatan.pagu_kegiatan,
					kegiatan_rka.kegiatan_rka,
					
					program.pagu_program,
					program_rka.program_rka,
					
					bidang.pagu_bidang,
					bidang_rka.bidang_rka,
					
					urusan.pagu_urusan,
					urusan_rka.urusan_rka
				FROM
					ta__kegiatan_sub
				INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
				INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				
				LEFT JOIN (
					SELECT
						ref__bidang.id_urusan,
						Sum(ta__kegiatan_sub.pagu) AS pagu_urusan
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__bidang.id_urusan
				) AS urusan ON urusan.id_urusan = ref__urusan.id
				LEFT JOIN (
					SELECT
						ref__program.id_bidang,
						Sum(ta__kegiatan_sub.pagu) AS pagu_bidang
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__program.id_bidang
				) AS bidang ON bidang.id_bidang = ref__bidang.id
				LEFT JOIN (
					SELECT
						ta__kegiatan.id_prog,
						Sum(ta__kegiatan_sub.pagu) AS pagu_program
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan.id_prog
				) AS program ON program.id_prog = ta__program.id
				LEFT JOIN (
					SELECT
						ta__kegiatan_sub.id_keg,
						Sum(ta__kegiatan_sub.pagu) AS pagu_kegiatan
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan_sub.id_keg
				) AS kegiatan ON kegiatan.id_keg = ta__kegiatan.id
				
				LEFT JOIN (
					SELECT
						ref__bidang.id_urusan,
						Sum(ta__belanja_rinci.total) AS urusan_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__bidang.id_urusan
				) AS urusan_rka ON urusan_rka.id_urusan = ref__urusan.id
				LEFT JOIN (
					SELECT
						ref__program.id_bidang,
						Sum(ta__belanja_rinci.total) AS bidang_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__program.id_bidang
				) AS bidang_rka ON bidang_rka.id_bidang = ref__bidang.id
				LEFT JOIN (
					SELECT
						ta__kegiatan.id_prog,
						Sum(ta__belanja_rinci.total) AS program_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan.id_prog
				) AS program_rka ON program_rka.id_prog = ta__program.id
				LEFT JOIN (
					SELECT
						ta__kegiatan_sub.id_keg,
						Sum(ta__belanja_rinci.total) AS kegiatan_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan_sub.id_keg
				) AS kegiatan_rka ON kegiatan_rka.id_keg = ta__kegiatan.id
				LEFT JOIN (
					SELECT
						ta__belanja.id_keg_sub,
						Sum(ta__belanja_rinci.total) AS sub_kegiatan_rka
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					AND ta__kegiatan_sub.tahun = ' . $tahun . '
					AND ref__sub.id_unit LIKE ' . $unit . '
					AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__belanja.id_keg_sub
				) AS sub_kegiatan_rka ON sub_kegiatan_rka.id_keg_sub = ta__kegiatan_sub.id
				WHERE
					ta__kegiatan_sub.flag = 1
				AND ta__kegiatan_sub.tahun = ' . $tahun . '
				AND ref__unit.id LIKE ' . $unit . '
				AND ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
				ORDER BY
					ref__urusan.kd_urusan ASC,
					ref__bidang.kd_bidang ASC,
					ref__unit.kd_unit ASC,
					ref__sub.kd_sub ASC,
					ref__program.kd_program ASC,
					ta__program.kd_id_prog ASC,
					ta__kegiatan.kd_keg ASC,
					ta__kegiatan_sub.kd_keg_sub ASC
			')
			->result();
		}
		$output										= array
		(
			'header'								=> $header_query,
			'sumber_dana'							=> $sumber_dana_query,
			'capaian_program'						=> $capaian_program_query,
			'indikator_kegiatan'					=> $indikator_kegiatan_query,
			'indikator_sub_kegiatan'				=> $indikator_sub_kegiatan_query,
			'data'									=> $data_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function ba_desk_renja($tahun = null, $unit = null, $sub_unit = null)
	{
		/*if(get_userdata('group_id') == 5)
		{
			$unit										= get_userdata('sub_unit');
		}
		if(!$unit)
		{
			$unit										= 0;
		}*/
		if($unit == 'all')
		{
			$header_query										= $this->db->query
			('
				SELECT
					ref__settings.jabatan_kepala_perencanaan AS jabatan_kepala,
					ref__settings.nama_kepala_perencanaan AS nama_kepala,
					ref__settings.nip_kepala_perencanaan AS nip_kepala
				FROM
					ref__settings
				WHERE
					ref__settings.tahun = ' . $tahun . '
				LIMIT 1
			')
			->row();
		}
		else
		{
			$header_query										= $this->db->query
			('
				SELECT
					ref__bidang_bappeda.nama_bidang,
					ref__bidang_bappeda.jabatan_kepala AS jabatan_kepala,
					ref__bidang_bappeda.nama_kepala AS nama_kepala,
					ref__bidang_bappeda.nip_kepala AS nip_kepala,
					ref__unit.nm_unit
				FROM
					ref__unit
				INNER JOIN ref__bidang_bappeda ON ref__unit.id_bidang_bappeda = ref__bidang_bappeda.id
				WHERE
					ref__unit.id = ' . $unit . '
				LIMIT 1
			')
			->row();
		}
		$output												= array
		(
			'header'										=> $header_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_perencanaan_per_skpd($tahun = null, $sumber_dana = null, $jenis_anggaran = null)
	{
		/*if(get_userdata('group_id') == 5)
		{
			$unit										= get_userdata('sub_unit');
		}*/
		if($sumber_dana == 'all')
		{
			$sumber_dana								= "'%'";
		}
		//$kode_perubahan									= 1;
		if ($jenis_anggaran == NULL) {
			$data										= $this->db->query
			('
				SELECT
					ref__urusan.kd_urusan,
					ref__bidang.kd_bidang,
					ref__unit.kd_unit,
					ref__unit.nm_unit,
					program.jumlah_program_blpu,
					program.jumlah_program_blu,
					skpd.jumlah_sub_kegiatan_skpd_blpu,
					skpd.jumlah_sub_kegiatan_skpd_blu,
					skpd.plafon_anggaran_skpd_blpu,
					skpd.plafon_anggaran_skpd_blu,
					pra_rka.pra_rka_blpu,
					pra_rka.pra_rka_blu
				FROM
					ref__unit
				INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				LEFT JOIN (
					SELECT
						ref__sub.id_unit,
						Count(DISTINCT CASE WHEN ref__program.kd_program = 1 THEN kd_program ELSE NULL END) AS jumlah_program_blpu,
						Count(DISTINCT CASE WHEN ref__program.kd_program > 1 THEN kd_program ELSE NULL END) AS jumlah_program_blu
					FROM
						ta__program
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					INNER JOIN ta__kegiatan ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					WHERE
						ta__kegiatan_sub.flag = 1 AND
						ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__sub.id_unit
				) AS program ON program.id_unit = ref__unit.id
				LEFT JOIN (
					SELECT
						ref__sub.id_unit,
						Count(CASE WHEN ref__program.kd_program = 1 THEN ta__kegiatan_sub.id ELSE NULL END) AS jumlah_sub_kegiatan_skpd_blpu,
						Count(CASE WHEN ref__program.kd_program > 11 THEN ta__kegiatan_sub.id ELSE NULL END) AS jumlah_sub_kegiatan_skpd_blu,
						Sum(CASE WHEN ref__program.kd_program = 1 THEN ta__kegiatan_sub.pagu ELSE 0 END) AS plafon_anggaran_skpd_blpu,
						Sum(CASE WHEN ref__program.kd_program > 1 THEN ta__kegiatan_sub.pagu ELSE 0 END) AS plafon_anggaran_skpd_blu
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__kegiatan_sub.id_keg
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
					WHERE
						ta__kegiatan_sub.flag = 1 AND
						ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__sub.id_unit
				) AS skpd ON skpd.id_unit = ref__unit.id
				LEFT JOIN (
					SELECT
						ref__sub.id_unit,
						Sum(CASE WHEN ref__program.kd_program < 15 THEN ta__belanja_rinci.total ELSE 0 END) AS pra_rka_blpu,
						Sum(CASE WHEN ref__program.kd_program >= 15 THEN ta__belanja_rinci.total ELSE 0 END) AS pra_rka_blu
					FROM
						ta__belanja_rinci
					INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
					INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
					INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
					WHERE
						ta__kegiatan_sub.flag = 1 AND
						ta__kegiatan_sub.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__sub.id_unit
				) AS pra_rka ON pra_rka.id_unit = ref__unit.id
				ORDER BY
					ref__urusan.kd_urusan ASC,
					ref__bidang.kd_bidang ASC,
					ref__unit.kd_unit ASC
			')
			->result();
		}
		else
		{
			$data										= $this->db->query
			('
				SELECT
					ref__urusan.kd_urusan,
					ref__bidang.kd_bidang,
					ref__unit.kd_unit,
					ref__unit.nm_unit,
					program.jumlah_program_blpu,
					program.jumlah_program_blu,
					skpd.jumlah_kegiatan_skpd_blpu,
					skpd.jumlah_kegiatan_skpd_blu,
					skpd.plafon_anggaran_skpd_blpu,
					skpd.plafon_anggaran_skpd_blu,
					pra_rka.pra_rka_blpu,
					pra_rka.pra_rka_blu
				FROM
					ref__unit
				INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				LEFT JOIN (
					SELECT
						ref__sub.id_unit,
						Count(CASE WHEN ref__program.kd_program < 15 THEN 1 ELSE NULL END) AS jumlah_program_blpu,
						Count(CASE WHEN ref__program.kd_program >= 15 THEN 1 ELSE NULL END) AS jumlah_program_blu
					FROM
						ta__program
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
					INNER JOIN ta__kegiatan_arsip ON ta__kegiatan_arsip.id_prog = ta__program.id
					WHERE
						ta__kegiatan_arsip.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ref__sub.id_unit
				) AS program ON program.id_unit = ref__unit.id
				LEFT JOIN (
					SELECT
						ta__kegiatan_arsip.id_unit,
						Count(CASE WHEN ta__kegiatan_arsip.kode_prog < 15 THEN 1 ELSE NULL END) AS jumlah_kegiatan_skpd_blpu,
						Count(CASE WHEN ta__kegiatan_arsip.kode_prog >= 15 THEN 1 ELSE NULL END) AS jumlah_kegiatan_skpd_blu,
						Sum(CASE WHEN ta__kegiatan_arsip.kode_prog < 15 THEN ta__kegiatan_arsip.pagu ELSE 0 END) AS plafon_anggaran_skpd_blpu,
						Sum(CASE WHEN ta__kegiatan_arsip.kode_prog >= 15 THEN ta__kegiatan_arsip.pagu ELSE 0 END) AS plafon_anggaran_skpd_blu
					FROM
						ta__kegiatan_arsip
					WHERE
						ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__kegiatan_arsip.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__kegiatan_arsip.id_unit
				) AS skpd ON skpd.id_unit = ref__unit.id
				LEFT JOIN (
					SELECT
						ta__belanja_arsip.id_unit,
						Sum(CASE WHEN ta__belanja_arsip.kd_program < 15 THEN ta__belanja_arsip.total ELSE 0 END) AS pra_rka_blpu,
						Sum(CASE WHEN ta__belanja_arsip.kd_program >= 15 THEN ta__belanja_arsip.total ELSE 0 END) AS pra_rka_blu
					FROM
						ta__belanja_arsip
					WHERE
						ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__belanja_arsip.id_sumber_dana LIKE ' . $sumber_dana . '
					GROUP BY
						ta__belanja_arsip.id_unit
				) AS pra_rka ON pra_rka.id_unit = ref__unit.id
				ORDER BY
					ref__urusan.kd_urusan ASC,
					ref__bidang.kd_bidang ASC,
					ref__unit.kd_unit ASC
			')
			->result();
		}
		$output										= array
		(
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_perencanaan_per_program($tahun = NULL, $sumber_dana = NULL, $jenis_anggaran = NULL)
	{
		//$kode_perubahan									= 4;
		if ($jenis_anggaran == NULL) {
			$data										= $this->db->query
			('
				SELECT
					ref__urusan.id AS id_urusan,
					ref__program.id AS id_program,
					ref__urusan.kd_urusan AS kode_urusan,
					ref__urusan.nm_urusan AS nama_urusan,
					urusan.jumlah_urusan,
					urusan.pagu_urusan,
					ref__bidang.id AS id_bidang,
					ref__bidang.kd_bidang AS kode_bidang,
					ref__bidang.nm_bidang AS nama_bidang,
					bidang.jumlah_bidang,
					bidang.pagu_bidang,
					ref__unit.id AS id_unit,
					ref__unit.kd_unit AS kode_unit,
					ref__unit.nm_unit AS nama_unit,
					unit.jumlah_unit,
					unit.pagu_unit,
					ref__sub.id AS id_sub,
					ref__sub.kd_sub AS kode_sub,
					ref__sub.nm_sub AS nama_sub,
					ta__program.id AS id_prog,
					ref__program.kd_program AS kode_program,
					ref__program.nm_program AS nama_program,
					program.jumlah_program,
					program.pagu_program
				FROM
					ta__program
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
				INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				LEFT JOIN (
					SELECT
						ta__kegiatan.id_prog,
						Count(ta__kegiatan_sub.id) AS jumlah_program,
						Sum(ta__kegiatan_sub.pagu) AS pagu_program
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					WHERE
						ta__kegiatan_sub.flag = 1
					GROUP BY
						ta__kegiatan.id_prog
				) AS program ON program.id_prog = ta__program.id
				LEFT JOIN (
					SELECT
						ref__sub.id_unit,
						ta__program.kd_id_prog,
						Count(ta__kegiatan_sub.id) AS jumlah_unit,
						Sum(ta__kegiatan_sub.pagu) AS pagu_unit
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__kegiatan_sub.flag = 1
					GROUP BY
						ref__sub.id_unit,
						ta__program.kd_id_prog
				) AS unit ON unit.id_unit = ref__unit.id AND unit.kd_id_prog = ta__program.kd_id_prog
				LEFT JOIN (
					SELECT
						ref__unit.id_bidang,
						Count(ta__kegiatan_sub.id) AS jumlah_bidang,
						Sum(ta__kegiatan_sub.pagu) AS pagu_bidang
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
					WHERE
						ta__kegiatan_sub.flag = 1
					GROUP BY
						ref__unit.id_bidang
				) AS bidang ON bidang.id_bidang = ref__bidang.id
				LEFT JOIN (
					SELECT
						ref__bidang.id_urusan,
						Count(ta__kegiatan_sub.id) AS jumlah_urusan,
						Sum(ta__kegiatan_sub.pagu) AS pagu_urusan
					FROM
						ta__kegiatan_sub
					INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
					INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
					WHERE
						ta__kegiatan_sub.flag = 1
					GROUP BY
						ref__bidang.id_urusan
				) AS urusan ON urusan.id_urusan = ref__urusan.id
				WHERE
					ta__program.tahun = ' . $tahun . '
				ORDER BY
					ref__urusan.kd_urusan ASC,
					ref__bidang.kd_bidang ASC,
					ref__unit.kd_unit ASC,
					ref__sub.kd_sub ASC,
					ref__program.kd_program ASC
			')
			->result();
		}
		else
		{
			$data										= $this->db->query
			('
				SELECT
					ref__urusan.id AS id_urusan,
					ref__program.id AS id_program,
					ref__urusan.kd_urusan AS kode_urusan,
					ref__urusan.nm_urusan AS nama_urusan,
					urusan.jumlah_urusan,
					urusan.pagu_urusan,
					ref__bidang.id AS id_bidang,
					ref__bidang.kd_bidang AS kode_bidang,
					ref__bidang.nm_bidang AS nama_bidang,
					bidang.jumlah_bidang,
					bidang.pagu_bidang,
					ref__unit.id AS id_unit,
					ref__unit.kd_unit AS kode_unit,
					ref__unit.nm_unit AS nama_unit,
					unit.jumlah_unit,
					unit.pagu_unit,
					ref__sub.id AS id_sub,
					ref__sub.kd_sub AS kode_sub,
					ref__sub.nm_sub AS nama_sub,
					ta__program.id AS id_prog,
					ref__program.kd_program AS kode_program,
					ref__program.nm_program AS nama_program,
					program.jumlah_program,
					program.pagu_program
				FROM
					ta__program
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
				LEFT JOIN (
					SELECT
						ta__kegiatan_arsip.id_program,
						Count(ta__kegiatan_arsip.id_keg) AS jumlah_program,
						Sum(ta__kegiatan_arsip.pagu) AS pagu_program
					FROM
						ta__kegiatan_arsip
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__kegiatan_arsip.id_keg
					WHERE
						ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' 
					GROUP BY
						ta__kegiatan_arsip.id_program
				) AS program ON program.id_program = ta__program.id
				LEFT JOIN (
					SELECT
						ta__kegiatan_arsip.id_unit,
						ta__kegiatan_arsip.kode_id_prog,
						Count(ta__kegiatan_arsip.id_keg) AS jumlah_unit,
						Sum(ta__kegiatan_arsip.pagu) AS pagu_unit
					FROM
						ta__kegiatan_arsip
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__kegiatan_arsip.id_keg
					WHERE
						ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' 
					GROUP BY
						ta__kegiatan_arsip.id_unit,
						ta__kegiatan_arsip.kode_id_prog
				) AS unit ON unit.id_unit = ref__unit.id AND unit.kode_id_prog = ta__program.kd_id_prog
				LEFT JOIN (
					SELECT
						ta__kegiatan_arsip.id_bidang,
						Count(ta__kegiatan_arsip.id_keg) AS jumlah_bidang,
						Sum(ta__kegiatan_arsip.pagu) AS pagu_bidang
					FROM
						ta__kegiatan_arsip
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__kegiatan_arsip.id_keg
					WHERE
						ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' 
					GROUP BY
						ta__kegiatan_arsip.id_bidang
				) AS bidang ON bidang.id_bidang = ref__bidang.id
				LEFT JOIN (
					SELECT
						ta__kegiatan_arsip.id_urusan,
						Count(ta__kegiatan_arsip.id_keg) AS jumlah_urusan,
						Sum(ta__kegiatan_arsip.pagu) AS pagu_urusan
					FROM
						ta__kegiatan_arsip
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__kegiatan_arsip.id_keg
					WHERE
						ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' 
					GROUP BY
						ta__kegiatan_arsip.id_urusan
				) AS urusan ON urusan.id_urusan = ref__urusan.id
				WHERE
					ta__program.tahun = ' . get_userdata('year') . '
				ORDER BY
					kode_urusan ASC,
					kode_bidang ASC,
					kode_unit ASC,
					kode_sub ASC,
					kode_program ASC
			')
			->result();
		}
		$output										= array
		(
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
}