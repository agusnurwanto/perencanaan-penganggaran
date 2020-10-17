<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Master_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function header($kelurahan = null, $kecamatan = null, $rw = null)
	{
		if((get_userdata('group_id') == 1 || get_userdata('group_id') == 4) && $rw) //admin atau rw
		{
			$query										= $this->db->query
			('
				SELECT
				ref__kecamatan.kode AS kode_kecamatan,
				ref__kecamatan.kecamatan AS nama_kecamatan,
				ref__kecamatan.camat,
				ref__kecamatan.nip AS nip_camat,
				ref__kecamatan.jabatan AS jabatan_camat,
				ref__kelurahan.kode AS kode_kelurahan,
				ref__kelurahan.nama_kelurahan,
				ref__kelurahan.singkat_kelurahan,
				ref__kelurahan.nama_lurah,
				ref__kelurahan.nip_lurah,
				ref__kelurahan.jabatan_lurah
				FROM
				ref__kelurahan
				INNER JOIN ref__kecamatan ON ref__kelurahan.id_kec = ref__kecamatan.id
				INNER JOIN ref__rw ON ref__rw.id_kel = ref__kelurahan.id
				WHERE
				ref__rw.id = ' . $rw . '
			')
			->row();			
			//print_r($query);exit;
			$query										= json_decode(json_encode($query), true);
			return $query;
		}
		elseif(get_userdata('group_id') == 2 && $kecamatan)
		{
			$query										= $this->db->query
			('
				SELECT
				ref__kecamatan.kode AS kode_kecamatan,
				ref__kecamatan.kecamatan AS nama_kecamatan,
				ref__kecamatan.camat,
				ref__kecamatan.nip AS nip_camat,
				ref__kecamatan.jabatan AS jabatan_camat
				FROM
				ref__kecamatan
				WHERE
				ref__kecamatan.id = ' . $kecamatan . '
			')
			->row();
			//print_r($query);exit;
			$query										= json_decode(json_encode($query), true);
			return $query;
		}
		elseif((get_userdata('group_id') == 1 || (get_userdata('group_id') == 3)) && $kelurahan)
		{
			$query										= $this->db->query
			('
				SELECT
				ref__kecamatan.kode AS kode_kecamatan,
				ref__kecamatan.kecamatan AS nama_kecamatan,
				ref__kecamatan.camat,
				ref__kecamatan.nip AS nip_camat,
				ref__kecamatan.jabatan AS jabatan_camat,
				ref__kelurahan.kode AS kode_kelurahan,
				ref__kelurahan.nama_kelurahan,
				ref__kelurahan.singkat_kelurahan,
				ref__kelurahan.nama_lurah,
				ref__kelurahan.nip_lurah,
				ref__kelurahan.jabatan_lurah
				FROM
				ref__kelurahan
				INNER JOIN ref__kecamatan ON ref__kelurahan.id_kec = ref__kecamatan.id
				WHERE
				ref__kelurahan.id = ' . $kelurahan . '
			')
			->row();
			//print_r($query);exit;
			$query										= json_decode(json_encode($query), true);
			return $query;
		}
		return true;
	}
	
	public function jenis_pekerjaan_variabel()
	{
		$query										= $this->db->query
		('
			SELECT
			ref__bidang_bappeda.kode AS kode_bidang_bappeda,
			ref__bidang_bappeda.nama_bidang,
			ref__musrenbang_isu.kode AS kode_isu,
			ref__musrenbang_isu.nama_isu,
			ref__musrenbang_jenis_pekerjaan.kode AS kode_jenis_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.deskripsi,
			ref__musrenbang_jenis_pekerjaan.nilai_satuan,
			ref__musrenbang_variabel.kode_variabel,
			ref__musrenbang_variabel.nama_variabel,
			ref__musrenbang_variabel.satuan
			FROM
			ref__musrenbang_isu
			LEFT JOIN ref__musrenbang_jenis_pekerjaan ON ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu
			LEFT JOIN ref__musrenbang_variabel ON ref__musrenbang_jenis_pekerjaan.id = ref__musrenbang_variabel.id_musrenbang_jenis_pekerjaan
			LEFT JOIN ref__bidang_bappeda ON ref__musrenbang_isu.id_bidang_bappeda = ref__bidang_bappeda.id
			ORDER BY
			ref__musrenbang_isu.kode ASC,
			kode_jenis_pekerjaan ASC,
			ref__musrenbang_variabel.kode_variabel ASC			
		')
		->result_array();
		//print_r($query);exit;
		$output										= array
		(
			'data'									=> $query
		);
		return $output;
	}
	
	public function jenis_pekerjaan_pertanyaan()
	{
		$query										= $this->db->query
		('
			SELECT
			ref__bidang_bappeda.nama_bidang,
			ref__musrenbang_isu.kode AS kode_isu,
			ref__musrenbang_isu.nama_isu,
			ref__musrenbang_jenis_pekerjaan.kode AS kode_jenis_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.deskripsi,
			ref__musrenbang_jenis_pekerjaan.nilai_satuan,
			ref__musrenbang_pertanyaan.kode AS kode_pertanyaan,
			ref__musrenbang_pertanyaan.pertanyaan
			FROM
			ref__musrenbang_isu
			LEFT JOIN ref__musrenbang_jenis_pekerjaan ON ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu
			LEFT JOIN ref__bidang_bappeda ON ref__musrenbang_isu.id_bidang_bappeda = ref__bidang_bappeda.id
			LEFT JOIN ref__musrenbang_pertanyaan ON ref__musrenbang_jenis_pekerjaan.id = ref__musrenbang_pertanyaan.id_musrenbang_jenis_pekerjaan
			ORDER BY
			ref__musrenbang_isu.kode ASC,
			kode_jenis_pekerjaan ASC,
			kode_pertanyaan ASC
						
		')
		->result_array();
		//print_r($query);exit;
		$output										= array
		(
			'data'									=> $query
		);
		return $output;
	}
}