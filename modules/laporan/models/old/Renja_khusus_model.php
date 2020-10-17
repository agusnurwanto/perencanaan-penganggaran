<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Renja_khusus_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function header($kegiatan = null, $unit = null)
	{
		if((get_userdata('group_id') == 1 AND $unit)) // Admin atau SKPD
		{
			if(get_userdata('group_id') == 5)
			{
				$unit									= get_userdata('sub_unit');
			}
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
	
	public function renja_awal($unit = null, $sumber_dana = null, $jenis_usulan = null)
	{
		if(get_userdata('group_id') == 5)
		{
			$unit										= get_userdata('sub_unit');
		}
		if(!$unit)
		{
			$unit										= 0;
		}
		if($unit == 999)
		{
			$unit										= "'%'";
		}
		if($sumber_dana == 'all')
		{
			$sumber_dana								= "'%'";
		}
		if($jenis_usulan == 'all')
		{
			$jenis_usulan								= "'%'";
		}
		if(2 == $this->input->get('jenis_bl'))
		{
			$jenis_bl									= '< 15';
		}
		elseif(3 == $this->input->get('jenis_bl'))
		{
			$jenis_bl									= '>= 15';
		}
		else
		{
			$jenis_bl									= 'LIKE "%"';
		}
		$kode_perubahan									= 1;
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
				ref__program.kd_program ' . $jenis_bl . '
				AND
				ref__sub.id_unit LIKE ' . $unit . '
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
		if ($this->input->get('status') == 1) {
			$data_query									= $this->db->query
			('
				SELECT
					ref__urusan.id AS id_urusan,
					ref__urusan.kd_urusan,
					ref__urusan.nm_urusan,
					urusan.pagu_urusan,
					ref__bidang.id AS id_bidang,
					ref__bidang.kd_bidang,
					ref__bidang.nm_bidang,
					bidang.pagu_bidang,
					ref__unit.id AS id_unit,
					ref__unit.kd_unit,
					ref__unit.nm_unit,
					unit.pagu_unit,
					ref__program.id AS id_program,
					ref__program.kd_program,
					ref__program.nm_program,
					ta__program.id AS id_ta__program,
					ta__program.id_prog,
					program.pagu_program,
					ta__kegiatan.id AS id_kegiatan,
					ta__kegiatan.kd_keg,
					ta__kegiatan.kegiatan,
					ta__kegiatan.kelurahan,
					ta__kegiatan.kecamatan,
					ta__kegiatan.pagu AS pagu_kegiatan,
					kegiatan_rka.kegiatan_rka,
					program_rka.program_rka,
					bidang_rka.bidang_rka,
					urusan_rka.urusan_rka,
					ref__sumber_dana.nama_sumber_dana
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				LEFT JOIN ref__sumber_dana ON ref__sumber_dana.id = ta__kegiatan.id_sumber_dana
				LEFT JOIN (
					SELECT
						ta__program.id,
						Sum(ta__kegiatan.pagu) AS pagu_program
					FROM
						ta__kegiatan
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ref__sub.id = ta__program.id_sub
					INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
					WHERE
						ref__program.kd_program ' . $jenis_bl . ' AND
						ta__program.tahun = ' . get_userdata('year') . ' AND
						ref__sub.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__program.id
				) AS program ON program.id = ta__program.id
				LEFT JOIN (
					SELECT
						ref__sub.id_unit,
						Sum(ta__kegiatan.pagu) AS pagu_unit
					FROM
						ta__kegiatan
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					WHERE
						ta__program.tahun = ' . get_userdata('year') . '
					GROUP BY
						ref__sub.id_unit
				) AS unit ON unit.id_unit = ref__unit.id
				LEFT JOIN (
					SELECT
						ref__program.id_bidang,
						Sum(ta__kegiatan.pagu) AS pagu_bidang
					FROM
						ta__kegiatan
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
					INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
					WHERE
						ref__program.kd_program ' . $jenis_bl . ' AND
						ta__program.tahun = ' . get_userdata('year') . ' AND
						ref__sub.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ref__program.id_bidang
				) AS bidang ON bidang.id_bidang = ref__bidang.id
				LEFT JOIN (
					SELECT
						ref__bidang.id_urusan,
						Sum(ta__kegiatan.pagu) AS pagu_urusan
					FROM
						ta__kegiatan
					INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
					INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
					INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
					INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
					INNER JOIN ref__bidang ON ref__bidang.id = ref__program.id_bidang
					WHERE
						ref__program.kd_program ' . $jenis_bl . ' AND
						ta__program.tahun = ' . get_userdata('year') . ' AND
						ref__sub.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ref__bidang.id_urusan
				) AS urusan ON urusan.id_urusan = ref__urusan.id
				LEFT JOIN (
					SELECT
						ta__belanja.id_keg,
						SUM(ta__belanja_rinc.total) AS kegiatan_rka
					FROM 
						ta__belanja_rinc
					JOIN ta__belanja_sub ON ta__belanja_sub.id = ta__belanja_rinc.id_belanja_sub
					JOIN ta__belanja ON ta__belanja.id = ta__belanja_sub.id_belanja
					JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja.id_keg
					JOIN ta__program ON ta__program.id = ta__kegiatan.id_prog
					JOIN ref__sub ON ref__sub.id = ta__program.id_sub
					JOIN ref__program ON ref__program.id = ta__program.id_prog
					WHERE 
						ref__program.kd_program ' . $jenis_bl . ' AND
						ta__program.tahun = ' . get_userdata('year') . ' AND
						ref__sub.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__belanja.id_keg
				) AS kegiatan_rka ON kegiatan_rka.id_keg = ta__kegiatan.id
				LEFT JOIN (
					SELECT
						ta__program.id,
						SUM(ta__belanja_rinc.total) AS program_rka
					FROM ta__belanja_rinc
					JOIN ta__belanja_sub ON ta__belanja_sub.id = ta__belanja_rinc.id_belanja_sub
					JOIN ta__belanja ON ta__belanja.id = ta__belanja_sub.id_belanja
					JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja.id_keg
					JOIN ta__program ON ta__program.id = ta__kegiatan.id_prog
					JOIN ref__sub ON ref__sub.id = ta__program.id_sub
					JOIN ref__program ON ref__program.id = ta__program.id_prog
					WHERE 
						ref__program.kd_program ' . $jenis_bl . ' AND
						ta__program.tahun = ' . get_userdata('year') . ' AND
						ref__sub.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__program.id
				) AS program_rka ON program_rka.id = ta__program.id
				LEFT JOIN (
					SELECT
						ref__program.id_bidang,
						SUM(ta__belanja_rinc.total) AS bidang_rka
					FROM ta__belanja_rinc
					JOIN ta__belanja_sub ON ta__belanja_sub.id = ta__belanja_rinc.id_belanja_sub
					JOIN ta__belanja ON ta__belanja.id = ta__belanja_sub.id_belanja
					JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja.id_keg
					JOIN ta__program ON ta__program.id = ta__kegiatan.id_prog
					JOIN ref__sub ON ref__sub.id = ta__program.id_sub
					JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
					JOIN ref__program ON ref__program.id = ta__program.id_prog
					WHERE 
						ref__program.kd_program ' . $jenis_bl . ' AND
						ta__program.tahun = ' . get_userdata('year') . ' AND
						ref__sub.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ref__program.id_bidang
				) AS bidang_rka ON bidang_rka.id_bidang = ref__bidang.id
				LEFT JOIN (
					SELECT
						ref__bidang.id_urusan,
						SUM(ta__belanja_rinc.total) AS urusan_rka
					FROM ta__belanja_rinc
					JOIN ta__belanja_sub ON ta__belanja_sub.id = ta__belanja_rinc.id_belanja_sub
					JOIN ta__belanja ON ta__belanja.id = ta__belanja_sub.id_belanja
					JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja.id_keg
					JOIN ta__program ON ta__program.id = ta__kegiatan.id_prog
					JOIN ref__sub ON ref__sub.id = ta__program.id_sub
					JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
					JOIN ref__program ON ref__program.id = ta__program.id_prog
					JOIN ref__bidang ON ref__bidang.id = ref__program.id_bidang
					WHERE 
						ref__program.kd_program ' . $jenis_bl . ' AND
						ta__program.tahun = ' . get_userdata('year') . ' AND
						ref__sub.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ref__bidang.id_urusan
				) AS urusan_rka ON urusan_rka.id_urusan = ref__urusan.id
				WHERE
					ta__kegiatan.flag = 1 AND
					ref__program.kd_program ' . $jenis_bl . ' AND
					ref__sub.id_unit LIKE ' . $unit . ' AND
					ref__urusan.tahun = ' . get_userdata('year') . ' AND
					ta__kegiatan.id_sumber_dana LIKE ' . $sumber_dana . ' AND
					ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
				ORDER BY
					ref__urusan.kd_urusan ASC,
					ref__bidang.kd_bidang ASC,
					ref__program.kd_program ASC,
					ta__kegiatan.kd_keg ASC
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
					ta__indikator.satuan,
					ta__indikator.penjelasan
				FROM
					ta__indikator
				INNER JOIN ta__kegiatan ON ta__indikator.id_keg = ta__kegiatan.id
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ref__program.kd_program ' . $jenis_bl . ' AND
					ref__sub.id_unit LIKE ' . $unit . '
				ORDER BY
					ta__indikator.id_keg ASC,
					ta__indikator.jns_indikator ASC,
					ta__indikator.kd_indikator ASC
			')
			->result_array();
		}
		elseif($this->input->get('status') == 2) {
			$data_query									= $this->db->query
			('
				SELECT
					ta__kegiatan_arsip.id_urusan,
					ta__kegiatan_arsip.kode_urusan AS kd_urusan,
					ref__urusan.nm_urusan,
					urusan.pagu_urusan,
					ta__kegiatan_arsip.id_bidang,
					ta__kegiatan_arsip.kode_bidang AS kd_bidang,
					ref__bidang.nm_bidang,
					bidang.pagu_bidang,
					ref__unit.id AS id_unit,
					ref__unit.kd_unit,
					ref__unit.nm_unit,
					ta__kegiatan_arsip.id_program,
					ta__kegiatan_arsip.kode_prog AS kd_program,
					ref__program.nm_program,
					program.pagu_program,
					ta__kegiatan_arsip.id_program AS id_ta__program,
					ta__kegiatan_arsip.kode_id_prog AS id_prog,
					ta__kegiatan_arsip.id_keg AS id_kegiatan,
					ta__kegiatan_arsip.kode_keg AS kd_keg,
					ta__kegiatan_arsip.kegiatan,
					ta__kegiatan_arsip.kelurahan,
					ta__kegiatan_arsip.kecamatan,
					ta__kegiatan_arsip.pagu AS pagu_kegiatan,
					kegiatan_rka.kegiatan_rka,
					program_rka.program_rka,
					bidang_rka.bidang_rka,
					urusan_rka.urusan_rka,
					ref__sumber_dana.nama_sumber_dana
				FROM
					ta__kegiatan_arsip
				INNER JOIN ref__sub ON ta__kegiatan_arsip.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ta__kegiatan_arsip.id_unit = ref__unit.id
				INNER JOIN ta__program ON ta__program.id = ta__kegiatan_arsip.id_program
				INNER JOIN ref__bidang ON ref__bidang.id = ta__kegiatan_arsip.id_bidang
				INNER JOIN ref__urusan ON ref__urusan.id = ta__kegiatan_arsip.id_urusan
				INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
				LEFT JOIN ref__sumber_dana ON ref__sumber_dana.id = ta__kegiatan_arsip.id_sumber_dana
				LEFT JOIN (
					SELECT
						ta__kegiatan_arsip.id_urusan,
						Sum(ta__kegiatan_arsip.pagu) AS pagu_urusan
					FROM
						ta__kegiatan_arsip
					WHERE
						ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__kegiatan_arsip.kode_prog ' . $jenis_bl . ' AND
						ta__kegiatan_arsip.tahun = ' . get_userdata('year') . ' AND
						ta__kegiatan_arsip.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan_arsip.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan_arsip.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__kegiatan_arsip.id_urusan
				) AS urusan ON urusan.id_urusan = ta__kegiatan_arsip.id_urusan
				LEFT JOIN (
					SELECT
						ta__kegiatan_arsip.id_bidang,
						Sum(ta__kegiatan_arsip.pagu) AS pagu_bidang
					FROM
						ta__kegiatan_arsip
					WHERE
						ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__kegiatan_arsip.kode_prog ' . $jenis_bl . ' AND
						ta__kegiatan_arsip.tahun = ' . get_userdata('year') . ' AND
						ta__kegiatan_arsip.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan_arsip.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan_arsip.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__kegiatan_arsip.id_bidang
				) AS bidang ON bidang.id_bidang = ta__kegiatan_arsip.id_bidang
				LEFT JOIN (
					SELECT
						ta__kegiatan_arsip.id_program,
						Sum(ta__kegiatan_arsip.pagu) AS pagu_program
					FROM
						ta__kegiatan_arsip
					WHERE
						ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__kegiatan_arsip.kode_prog ' . $jenis_bl . ' AND
						ta__kegiatan_arsip.tahun = ' . get_userdata('year') . ' AND
						ta__kegiatan_arsip.id_unit LIKE ' . $unit . ' AND
						ta__kegiatan_arsip.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan_arsip.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__kegiatan_arsip.id_program
				) AS program ON program.id_program = ta__kegiatan_arsip.id_program
				LEFT JOIN (
					SELECT
						ta__belanja_arsip.id_keg,
						Sum(ta__belanja_arsip.total) AS kegiatan_rka
					FROM
						ta__belanja_arsip
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja_arsip.id_keg
					WHERE
						ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__belanja_arsip.kd_program ' . $jenis_bl . ' AND
						ta__belanja_arsip.tahun = ' . get_userdata('year') . ' AND
						ta__belanja_arsip.id_unit LIKE ' . $unit . '  AND
						ta__belanja_arsip.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__belanja_arsip.id_keg	
				) AS kegiatan_rka ON kegiatan_rka.id_keg = ta__kegiatan_arsip.id_keg
				LEFT JOIN (
					SELECT
						ta__belanja_arsip.id_program,
						Sum(ta__belanja_arsip.total) AS program_rka
					FROM
						ta__belanja_arsip
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja_arsip.id_keg
					WHERE
						ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__belanja_arsip.kd_program ' . $jenis_bl . ' AND
						ta__belanja_arsip.tahun = ' . get_userdata('year') . ' AND
						ta__belanja_arsip.id_unit LIKE ' . $unit . '  AND
						ta__belanja_arsip.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__belanja_arsip.id_program	
				) AS program_rka ON program_rka.id_program = ta__kegiatan_arsip.id_program
				LEFT JOIN (
					SELECT
						ta__belanja_arsip.id_bidang,
						Sum(ta__belanja_arsip.total) AS bidang_rka
					FROM
						ta__belanja_arsip
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja_arsip.id_keg
					WHERE
						ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__belanja_arsip.kd_program ' . $jenis_bl . ' AND
						ta__belanja_arsip.tahun = ' . get_userdata('year') . ' AND
						ta__belanja_arsip.id_unit LIKE ' . $unit . '  AND
						ta__belanja_arsip.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__belanja_arsip.id_bidang	
				) AS bidang_rka ON bidang_rka.id_bidang = ta__kegiatan_arsip.id_bidang
				LEFT JOIN (
					SELECT
						ta__belanja_arsip.id_urusan,
						Sum(ta__belanja_arsip.total) AS urusan_rka
					FROM
						ta__belanja_arsip
					INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__belanja_arsip.id_keg
					WHERE
						ta__belanja_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
						ta__belanja_arsip.kd_program ' . $jenis_bl . ' AND
						ta__belanja_arsip.tahun = ' . get_userdata('year') . ' AND
						ta__belanja_arsip.id_unit LIKE ' . $unit . '  AND
						ta__belanja_arsip.id_sumber_dana LIKE ' . $sumber_dana . ' AND
						ta__kegiatan.jenis_usulan LIKE ' . $jenis_usulan . ' 
					GROUP BY
						ta__belanja_arsip.id_urusan	
				) AS urusan_rka ON urusan_rka.id_urusan = ta__kegiatan_arsip.id_urusan
				WHERE
					ref__program.kd_program ' . $jenis_bl . ' AND
					ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . ' AND
					ta__kegiatan_arsip.id_unit LIKE ' . $unit . ' AND
					ta__kegiatan_arsip.tahun = ' . get_userdata('year') . ' AND
					ta__kegiatan_arsip.id_sumber_dana LIKE ' . $sumber_dana . ' AND
					ta__kegiatan_arsip.jenis_usulan LIKE ' . $jenis_usulan . '
				ORDER BY
					kd_urusan ASC,
					kd_bidang ASC,
					kd_program ASC,
					kd_keg ASC
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
					ta__indikator_arsip.satuan,
					ta__indikator_arsip.penjelasan
				FROM
					ta__indikator_arsip
				WHERE
					ta__indikator_arsip.kd_program ' . $jenis_bl . ' AND
					ta__indikator_arsip.id_unit LIKE ' . $unit . ' AND
					ta__indikator_arsip.kode_perubahan = ' . $kode_perubahan . '
				ORDER BY
					ta__indikator_arsip.id_keg ASC,
					ta__indikator_arsip.jns_indikator ASC,
					ta__indikator_arsip.kd_indikator ASC
			')
			->result_array();
		}
		else {
			generateMessages(403, 'Silakan  di pilih status laporan nya!', go_to());
		}
		$output										= array
		(
			'data'									=> $data_query,
			'capaian_program'						=> $capaian_program_query,
			'indikator_kegiatan'					=> $indikator_kegiatan_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function renja_akhir($unit = null)
	{
		if(get_userdata('group_id') == 5)
		{
			$unit										= get_userdata('sub_unit');
		}
		if(!$unit)
		{
			$unit										= 0;
		}
		if($unit == 999)
		{
			$unit		= "'%'";
		}
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.id AS id_unit,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				unit.pagu_unit,
				ref__program.id AS id_program,
				ref__program.kd_program,
				ref__program.nm_program,
				program.pagu_program,
				ta__kegiatan.id AS id_kegiatan,
				ta__kegiatan.kd_keg,
				ta__kegiatan.kegiatan,
				ta__kegiatan.pagu AS pagu_kegiatan,
				ta__indikator.kd_indikator,
				ta__indikator.tolak_ukur,
				ta__indikator.target,
				ta__indikator.satuan
			FROM
				ta__kegiatan
			LEFT JOIN ta__indikator ON ta__kegiatan.id = ta__indikator.id_keg
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id AND ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ta__program.id,
					Sum(ta__kegiatan.pagu) AS pagu_program
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				WHERE
					ta__program.tahun = ' . get_userdata('year') . '
				GROUP BY
					ta__program.id
			) AS program ON program.id = ta__program.id
			LEFT JOIN (
				SELECT
					ref__sub.id_unit,
					Sum(ta__kegiatan.pagu) AS pagu_unit
				FROM
					ta__kegiatan
				INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				WHERE
					ta__program.tahun = ' . get_userdata('year') . '
				GROUP BY
					ref__sub.id_unit
			) AS unit ON unit.id_unit = ref__unit.id
			WHERE
				ta__kegiatan.flag = 1 AND
				ref__unit.id LIKE ' . $unit . ' AND
				ref__urusan.tahun = ' . get_userdata('year') . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC,
				ref__program.kd_program ASC,
				ta__program.kd_id_prog ASC,
				ta__kegiatan.kd_keg ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_renja_awal_per_skpd($unit = null)
	{
		if(get_userdata('group_id') == 5)
		{
			$unit										= get_userdata('sub_unit');
		}
		if(!$unit)
		{
			$unit										= 0;
		}
		if($unit == 999)
		{
			$unit		= "'%'";
		}
		$data										= $this->db->query
		('
		SELECT
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__unit.nm_unit,
			skpd.jumlah_kegiatan_skpd,
			skpd.plafon_anggaran_skpd
		FROM
			ref__unit
		INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
		INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
		LEFT JOIN
		(
			SELECT
				ref__sub.id_unit,
				Count(ta__kegiatan.id) AS jumlah_kegiatan_skpd,
				Sum(ta__kegiatan.pagu) AS plafon_anggaran_skpd
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			WHERE
				ta__kegiatan.flag = 1
			GROUP BY
				ref__sub.id_unit
		) AS skpd ON skpd.id_unit = ref__unit.id
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
	
	public function ba_desk_renja($unit = null)
	{
		if(get_userdata('group_id') == 5)
		{
			$unit										= get_userdata('sub_unit');
		}
		if(!$unit)
		{
			$unit										= 0;
		}
		if($unit == 999)
		{
			$unit		= "'%'";
		}
		$header_query										= $this->db->query
		('
			SELECT
				ref__bidang_bappeda.nama_bidang,
				ref__bidang_bappeda.jabatan_kepala,
				ref__bidang_bappeda.nama_kepala,
				ref__bidang_bappeda.nip_kepala,
				ref__unit.nm_unit
			FROM
				ref__unit
			INNER JOIN ref__bidang_bappeda ON ref__unit.id_bidang_bappeda = ref__bidang_bappeda.id
			WHERE
				ref__unit.id = ' . $unit . '
			LIMIT 1
		')
		->result_array();
		$output												= array
		(
			'header'										=> $header_query
		);
		//print_r($output);exit;
		return $output;
	}
}