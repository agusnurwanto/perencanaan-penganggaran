<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Rpjmd_model extends CI_Model
{
	function __construct()
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		parent::__construct();
	}
	
	public function rpjmd_misi($misi = NULL)
	{
		if($misi == 'all')
		{
			$misi									= "'%'";
		}
		
		$query										= $this->db->query
		('
			SELECT
				ta__rpjmd_misi.kode AS kode_misi,
				ta__rpjmd_misi.misi,
				ta__rpjmd_tujuan.kode AS kode_tujuan,
				ta__rpjmd_tujuan.tujuan,
				ta__rpjmd_tujuan_indikator.kode AS kode_indikator_tujuan,
				ta__rpjmd_tujuan_indikator.uraian AS indikator_tujuan,
				ta__rpjmd_sasaran.kode AS kode_sasaran,
				ta__rpjmd_sasaran.sasaran,
				ta__rpjmd_strategi.kode AS kode_strategi,
				ta__rpjmd_strategi.strategi,
				ta__rpjmd_kebijakan.kode AS kode_kebijakan,
				ta__rpjmd_kebijakan.kebijakan,
				ta__rpjmd_sasaran_indikator.kode AS kode_indikator_sasaran,
				ta__rpjmd_sasaran_indikator.satuan,
				ref__program.kd_program AS kode_program,
				ref__program.nm_program AS program,
				ta__program_capaian.kode AS kode_capaian_program,
				ta__program_capaian.tolak_ukur,
				ta__program_capaian.tahun_1_target,
				ta__program_capaian.tahun_1_satuan,
				ta__program_capaian.tahun_2_target,
				ta__program_capaian.tahun_2_satuan,
				ta__program_capaian.tahun_3_target,
				ta__program_capaian.tahun_3_satuan,
				ta__program_capaian.tahun_4_target,
				ta__program_capaian.tahun_4_satuan,
				ta__program_capaian.tahun_5_target,
				ta__program_capaian.tahun_5_satuan,
				ta__program_capaian.`status`,
				ref__urusan.kd_urusan,
				ref__urusan.nm_urusan,
				ref__bidang.kd_bidang,
				ref__bidang.nm_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				ref__sub.kd_sub,
				ref__sub.nm_sub
			FROM
				ta__rpjmd_misi
			LEFT JOIN ta__rpjmd_tujuan ON ta__rpjmd_tujuan.id_misi = ta__rpjmd_misi.id
			LEFT JOIN ta__rpjmd_tujuan_indikator ON ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan = ta__rpjmd_tujuan.id
			LEFT JOIN ta__rpjmd_sasaran ON ta__rpjmd_sasaran.id_rpjmd_tujuan_indikator = ta__rpjmd_tujuan_indikator.id
			LEFT JOIN ta__rpjmd_sasaran_indikator ON ta__rpjmd_sasaran_indikator.id_rpjmd_sasaran = ta__rpjmd_sasaran.id
			LEFT JOIN ta__rpjmd_strategi ON ta__rpjmd_strategi.id_rpjmd_sasaran = ta__rpjmd_sasaran.id
			LEFT JOIN ta__rpjmd_kebijakan ON ta__rpjmd_kebijakan.id_rpjmd_sasaran = ta__rpjmd_sasaran.id
			LEFT JOIN ta__program ON ta__program.id_sasaran_indikator = ta__rpjmd_sasaran_indikator.id
			LEFT JOIN ta__program_capaian ON ta__program_capaian.id_prog = ta__program.id
			LEFT JOIN ref__program ON ta__program.id_prog = ref__program.id
			LEFT JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			LEFT JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			LEFT JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			LEFT JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ta__rpjmd_misi.id LIKE ' . $misi . '
			ORDER BY
				kode_misi ASC,
				kode_tujuan ASC,
				indikator_tujuan ASC,
				kode_indikator_sasaran ASC
		')
		->result();
		$output										= array
		(
			'data'									=> $query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function program_pembangunan_prioritas_pendanaan($tahun = NULL)
	{
		//echo $kelurahan;exit;
		$visi_query									= $this->db->query
		('
			SELECT
				ta__rpjmd_visi.tahun_awal,
				ta__rpjmd_visi.tahun_akhir
			FROM
				ta__rpjmd_visi
			LIMIT 1
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ta__rpjmd_misi.id AS id_misi,
				ta__rpjmd_misi.kode AS kode_misi,
				ta__rpjmd_misi.misi,
				ta__rpjmd_tujuan.id AS id_tujuan,
				ta__rpjmd_tujuan.kode AS kode_tujuan,
				ta__rpjmd_tujuan.tujuan,
				ta__rpjmd_sasaran.id AS id_sasaran,
				ta__rpjmd_sasaran.kode AS kode_sasaran,
				ta__rpjmd_sasaran.sasaran,
				ta__rpjmd_sasaran_indikator.satuan,
				ta__rpjmd_sasaran_indikator.tahun_1,
				ta__rpjmd_sasaran_indikator.tahun_2,
				ta__rpjmd_sasaran_indikator.tahun_3,
				ta__rpjmd_sasaran_indikator.tahun_4,
				ta__rpjmd_sasaran_indikator.tahun_5
			FROM
				ta__rpjmd_sasaran_indikator
			INNER JOIN ta__rpjmd_sasaran ON ta__rpjmd_sasaran_indikator.id_rpjmd_sasaran = ta__rpjmd_sasaran.id
			INNER JOIN ta__rpjmd_tujuan ON ta__rpjmd_sasaran.id_rpjmd_tujuan_indikator = ta__rpjmd_tujuan.id
			INNER JOIN ta__rpjmd_misi ON ta__rpjmd_tujuan.id_misi = ta__rpjmd_misi.id
			ORDER BY
				ta__rpjmd_misi.kode ASC,
				ta__rpjmd_tujuan.kode ASC,
				ta__rpjmd_sasaran.kode ASC			
		')
		->result();
		$output										= array
		(
			'visi'									=> $visi_query,
			'data'									=> $query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function capaian_program($tahun = NULL)
	{
		$visi_query									= $this->db->query
		('
			SELECT
				ta__rpjmd_visi.tahun_ke
			FROM
				ta__rpjmd_visi
			LIMIT 1
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_program,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__urusan.nm_urusan AS nama_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				ref__bidang.nm_bidang AS nama_bidang,
				ref__unit.kd_unit AS kode_unit,
				ref__unit.nm_unit AS nama_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__sub.nm_sub AS nama_sub,
				ref__program.kd_program AS kode_program,
				ref__program.nm_program AS nama_program,
				ta__program_capaian.kode AS kode_capaian,
				ta__program_capaian.tolak_ukur,
				ta__program_capaian.tahun_' . $visi_query->tahun_ke . '_target,
				ta__program_capaian.tahun_' . $visi_query->tahun_ke . '_satuan
			FROM
				ta__program
			INNER JOIN ta__program_capaian ON ta__program_capaian.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			WHERE
				ta__program.tahun = ' . $tahun . '
			ORDER BY
				kode_urusan ASC,
				kode_bidang ASC,
				kode_unit ASC,
				kode_sub ASC,
				kode_program ASC,
				kode_capaian ASC
		')
		->result();
		$output										= array
		(
			'visi'									=> $visi_query,
			'data'									=> $query
		);
		//print_r($query);exit;
		return $output;
	}
	
	public function rekapitulasi_program($tahun = NULL, $unit = NULL, $jenis_bl = NULL)
	{
		if(get_userdata('group_id') == 5)
		{
			$unit										= get_userdata('sub_unit');
		}
		elseif(!$unit)
		{
			$unit										= 0;
		}
		elseif($unit == 'all')
		{
			$unit										= "'%'";
		}
		
		if(1 == $this->input->get('jenis_bl'))
		{
			$jenis_bl									= '= 1';
		}
		elseif(2 == $this->input->get('jenis_bl'))
		{
			$jenis_bl									= '> 1';
		}
		else
		{
			$jenis_bl									= 'LIKE "%"';
		}
		$visi										= $this->db->query
		('
			SELECT
				ta__rpjmd_visi.tahun_awal,
				ta__rpjmd_visi.tahun_akhir,
				ta__rpjmd_visi.tahun_ke
			FROM
				ta__rpjmd_visi
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__program.id AS id_program,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__urusan.nm_urusan AS nama_urusan,
				ref__bidang.id AS id_bidang,
				ref__bidang.kd_bidang AS kode_bidang,
				ref__bidang.nm_bidang AS nama_bidang,
				ref__unit.id AS id_unit,
				ref__unit.kd_unit AS kode_unit,
				ref__unit.nm_unit AS nama_unit,
				ref__sub.id AS id_sub,
				ref__sub.kd_sub AS kode_sub,
				ref__sub.nm_sub AS nama_sub,
				ta__program.id AS id_prog,
				ref__program.kd_program AS kode_program,
				ref__program.nm_program AS nama_program
			FROM
				ta__program
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			WHERE
				ta__program.tahun = ' . $tahun . ' AND
				ref__sub.id_unit LIKE ' . $unit . ' AND
				ref__program.kd_program ' . $jenis_bl . '
			ORDER BY
				kode_urusan ASC,
				kode_bidang ASC,
				kode_unit ASC,
				kode_sub ASC,
				kode_program ASC
		')
		->result();
		$capaian_program_query						= $this->db->query
		('
			SELECT
				ta__program_capaian.id_prog,
				ta__program_capaian.kode,
				ta__program_capaian.tolak_ukur,
				ta__program_capaian.tahun_1_target,
				ta__program_capaian.tahun_1_satuan,
				ta__program_capaian.tahun_2_target,
				ta__program_capaian.tahun_2_satuan,
				ta__program_capaian.tahun_3_target,
				ta__program_capaian.tahun_3_satuan,
				ta__program_capaian.tahun_4_target,
				ta__program_capaian.tahun_4_satuan,
				ta__program_capaian.tahun_5_target,
				ta__program_capaian.tahun_5_satuan
			FROM
				ta__program_capaian
			INNER JOIN ta__program ON ta__program_capaian.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			WHERE
				ta__program.tahun = ' . $tahun . ' AND
				ref__sub.id_unit LIKE ' . $unit . ' AND
				ref__program.kd_program ' . $jenis_bl . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC,
				ref__program.kd_program ASC,
				ta__program.kd_id_prog ASC,
				ta__program_capaian.kode ASC
		')
		->result();
		$output										= array
		(
			'visi'									=> $visi,
			'data'									=> $query,
			'capaian_program'						=> $capaian_program_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rencana_program_kegiatan()
	{
		//echo $kelurahan;exit;
		$query										= $this->db->query
		('
			SELECT
				ta__rpjmd_misi.id AS id_misi,
				ta__rpjmd_misi.kode AS kode_misi,
				ta__rpjmd_misi.misi,
				ref__unit.id AS id_unit,
				ref__unit.kd_unit AS kode_unit,
				ref__unit.nm_unit AS nama_unit,
				ta__rpjmd_tujuan.id AS id_tujuan,
				ta__rpjmd_tujuan.kode AS kode_tujuan,
				ta__rpjmd_tujuan.tujuan,
				ta__rpjmd_sasaran.id AS id_sasaran,
				ta__rpjmd_sasaran.sasaran,
				ta__rpjmd_sasaran_indikator.satuan,
				ta__rpjmd_sasaran_indikator.kondisi_awal,
				ta__rpjmd_sasaran_indikator.tahun_1,
				ta__rpjmd_sasaran_indikator.tahun_2,
				ta__rpjmd_sasaran_indikator.tahun_3,
				ta__rpjmd_sasaran_indikator.tahun_4,
				ta__rpjmd_sasaran_indikator.tahun_5,
				ta__rpjmd_sasaran_indikator.kondisi_akhir
			FROM
				ta__rpjmd_sasaran_indikator
			INNER JOIN ta__rpjmd_sasaran ON ta__rpjmd_sasaran_indikator.id_rpjmd_sasaran = ta__rpjmd_sasaran.id
			INNER JOIN ta__rpjmd_tujuan ON ta__rpjmd_sasaran.id_rpjmd_tujuan_indikator = ta__rpjmd_tujuan.id
			INNER JOIN ta__rpjmd_misi ON ta__rpjmd_tujuan.id_misi = ta__rpjmd_misi.id
			INNER JOIN ref__unit ON ta__rpjmd_tujuan.id_unit = ref__unit.id
			ORDER BY
				id_misi ASC,
				ta__rpjmd_tujuan.id_misi ASC,
				id_sasaran ASC			
		')
		->result_array();
		//print_r($query);exit;
		$output										= array
		(
			'data'									=> $query
		);
		return $output;
	}
	
	public function program_pembangunan_pagu_indikatif($tahun = null)
	{
		return true;
	}
}