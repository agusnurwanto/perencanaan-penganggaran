<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Musrenbang_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function header($kelurahan = null, $kecamatan = null, $rw = null, $skpd = null, $fraksi = null, $dprd = null)
	{
		if(in_array(get_userdata('group_id'), array(1, 5, 8,9)) && $skpd) // Admin atau sekretariat atau SKPD
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
				ref__unit_jabatan.nama_jabatan,
				ref__unit_jabatan.nama_pejabat,
				ref__unit_jabatan.nip_pejabat
				FROM
				ref__unit
				LEFT JOIN ref__unit_jabatan ON ref__unit_jabatan.id_unit = ref__unit.id
				INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				WHERE
				ref__unit.tahun = ' . get_userdata('year') . ' AND
				ref__unit.id = ' . $skpd . '
			')
			->row();
			
			return $query;
		}
		elseif(in_array(get_userdata('group_id'), array(1, 4, 8,9)) && $rw) //admin atau sekretariat atau rw
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
			
			return $query;
		}
		elseif(in_array(get_userdata('group_id'), array(1, 2, 3, 8,9)) && $kelurahan) // Admin atau sekretariat atau Kelurahan
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
			
			return $query;
		}
		elseif(in_array(get_userdata('group_id'), array(1, 2, 8,9)) && $kecamatan) // Admin atau sekretariat atau Kecamatan
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
			
			return $query;
		}
		return true;
	}
	
	public function hasil_musrenbang_rw($rw = null)
	{
		//echo $kelurahan;exit;
		$query										= $this->db->query
		('
			SELECT
			ta__musrenbang.kode,
			ref__rw.rw,
			ref__rt.rt,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ta__musrenbang.map_address,
			ta__musrenbang.jenis_pekerjaan,
			ta__musrenbang.variabel_usulan,
			ta__musrenbang.nilai_usulan,
			ta__musrenbang.urgensi,
			ta__musrenbang.variabel_kelurahan,
			ta__musrenbang.nilai_kelurahan,
			ta__musrenbang.nama_kegiatan

			FROM
			ta__musrenbang
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			WHERE
			ta__musrenbang.id_rw = ' . $rw . '
			ORDER BY
			ref__rw.rw ASC,
			ref__rt.rt ASC
			
		')
		->result_array();
		//print_r($query);exit;
		$output										= array
		(
			'data'									=> $query
		);
		return $output;
	}
	
	public function ba_hasil_musrenbang_kelurahan($kelurahan = null)
	{	
	
		$query										= $this->db->query
		('
			SELECT
				count(case when ta__musrenbang.pengusul = 1 then 1 else null end) AS jumlah_usulan,
				count(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 then 1 else null end) AS jumlah_verifikasi,
				count(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 then 1 else null end) AS jumlah_ditolak,
				count(case when ta__musrenbang.pengusul = 2 then 1 else null end) AS jumlah_kelurahan,
				
				Sum(case when ta__musrenbang.pengusul = 1 then ta__musrenbang.nilai_usulan else 0 end) AS nilai_usulan,
				Sum(case when (ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0) then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_verifikasi,
				Sum(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_ditolak,
				Sum(case when ta__musrenbang.pengusul = 2 then ta__musrenbang.nilai_kelurahan else 0 end) AS usulan_kelurahan
			FROM
				ta__musrenbang
			WHERE
				ta__musrenbang.id_kel = ' . $kelurahan . '
		')
		->result_array();
		//print_r($query);exit;
		$output										= array
		(
			'data'									=> $query,
			'header'								=> $this->header($kelurahan)
		);
		return $output;
	}
	
	public function hasil_musrenbang_kelurahan($kelurahan = null)
	{
		if($this->input->get('status') == 1) // Usulan RW
		{
			$pengusul			= '= 1';
			$flag				= '>= 0';
		}
		elseif($this->input->get('status') == 2) // Diterima Kelurahan
		{
			$pengusul			= '= 1';
			$flag				= 'IN(1, 4, 5, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 3) // Ditolak Kelurahan
		{
			$pengusul			= '= 1';
			$flag				= '= 2';
		}
		elseif($this->input->get('status') == 4) // Usulan Kelurahan
		{
			$pengusul			= '= 2';
			$flag				= '> 2';
		}
		elseif($this->input->get('status') == 5) // Pilih Semua
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= '>= 0';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kelurahan per Bidang Bappeda!', go_to());
		}
		//print_r($status);exit;
		$query										= $this->db->query
		('
			SELECT
			ta__musrenbang.kode,
			ref__rw.rw,
			ref__rt.rt,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ta__musrenbang.pengusul,
			ta__musrenbang.flag,
			ta__musrenbang.map_address,
			ta__musrenbang.nama_kegiatan,
			ta__musrenbang.jenis_pekerjaan,
			ta__musrenbang.variabel_usulan,
			ta__musrenbang.nilai_usulan,
			ta__musrenbang.urgensi,
			ta__musrenbang.variabel_kelurahan,
			ta__musrenbang.nilai_kelurahan
			FROM
			ta__musrenbang
			LEFT JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			WHERE
			ta__musrenbang.id_kel = ' . $kelurahan . ' AND
			ta__musrenbang.pengusul ' . $pengusul . ' AND
			ta__musrenbang.flag ' . $flag . '
			ORDER BY
			ref__rw.rw ASC,
			ref__rt.rt ASC
			
		')
		->result_array();
		//echo $this->db->last_query();exit;
		//print_r($query);exit;
		$output										= array
		(
			'data'									=> $query,
			'header'								=> $this->header($kelurahan)
		);
		return $output;
	}
	
	public function rekapitulasi_musrenbang_kelurahan()
	{
		$data										= $this->db->query
		('
			SELECT
			ref__kecamatan.kode AS kode_kecamatan,
			ref__kecamatan.kecamatan,
			ref__kelurahan.kode AS kode_kelurahan,
			ref__kelurahan.nama_kelurahan,
			jumlah_rw.jumlah_rw,
			jumlah_rw_kecamatan.jumlah_rw_kecamatan,
			jumlah_kelurahan_sekecamatan.jumlah_kelurahan_sekecamatan,
			total_kelurahan.nilai_usulan,
			(total_kelurahan.nilai_verifikasi - total_kelurahan.nilai_ditolak) AS nilai_diterima,
			total_kelurahan.nilai_ditolak,
			total_kelurahan.usulan_kelurahan,
			jumlah_kelurahan.jumlah_usulan,
			(jumlah_kelurahan.jumlah_verifikasi - jumlah_kelurahan.jumlah_ditolak) AS jumlah_diterima,
			jumlah_kelurahan.jumlah_ditolak,
			jumlah_kelurahan.jumlah_kelurahan,
			total_kecamatan.nilai_usulan_kecamatan,
			(total_kecamatan.nilai_verifikasi_kecamatan - total_kecamatan.nilai_ditolak_kecamatan) AS nilai_diterima_kecamatan,
			total_kecamatan.nilai_ditolak_kecamatan,
			total_kecamatan.nilai_usulan_kelurahan_kecamatan,
			jumlah_kecamatan.jumlah_usulan_kecamatan,
			(jumlah_kecamatan.jumlah_verifikasi_kecamatan - jumlah_kecamatan.jumlah_ditolak_kecamatan) AS jumlah_diterima_kecamatan,
			jumlah_kecamatan.jumlah_ditolak_kecamatan,
			jumlah_kecamatan.jumlah_usulan_kelurahan_kecamatan
			FROM
			ref__kelurahan
			INNER JOIN ref__kecamatan ON ref__kelurahan.id_kec = ref__kecamatan.id
			LEFT JOIN (
				SELECT
				ref__rw.id_kel,
				Count(ref__rw.id) AS jumlah_rw
				FROM
				ref__rw
				GROUP BY
				ref__rw.id_kel
			) AS jumlah_rw ON jumlah_rw.id_kel = ref__kelurahan.id
			LEFT JOIN (
				SELECT
					ref__kelurahan.id_kec,
					Count(ref__rw.id) AS jumlah_rw_kecamatan
				FROM
					ref__rw
				INNER JOIN
					ref__kelurahan ON ref__kelurahan.id = ref__rw.id_kel
				GROUP BY
					ref__kelurahan.id_kec
			) AS jumlah_rw_kecamatan ON jumlah_rw_kecamatan.id_kec = ref__kecamatan.id
			LEFT JOIN (
				SELECT
					ref__kelurahan.id_kec,
					Count(ref__kelurahan.id) AS jumlah_kelurahan_sekecamatan
				FROM
					ref__kelurahan
				GROUP BY
					ref__kelurahan.id_kec
			) AS jumlah_kelurahan_sekecamatan ON jumlah_kelurahan_sekecamatan.id_kec = ref__kecamatan.id
			LEFT JOIN (
				SELECT
				ta__musrenbang.id_kel,
				Sum(case when ta__musrenbang.pengusul = 1 then ta__musrenbang.nilai_usulan else 0 end) AS nilai_usulan,
				Sum(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_verifikasi,
				Sum(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_ditolak,
				Sum(case when ta__musrenbang.pengusul = 2 then ta__musrenbang.nilai_kelurahan else 0 end) AS usulan_kelurahan
				FROM
				ta__musrenbang
				GROUP BY
				ta__musrenbang.id_kel
			) AS total_kelurahan ON total_kelurahan.id_kel = ref__kelurahan.id
			LEFT JOIN (
				SELECT
				ta__musrenbang.id_kel,
				count(case when ta__musrenbang.pengusul = 1 then 1 else null end) AS jumlah_usulan,
				count(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 then 1 else null end) AS jumlah_verifikasi,
				count(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 then 1 else null end) AS jumlah_ditolak,
				count(case when ta__musrenbang.pengusul = 2 then 1 else null end) AS jumlah_kelurahan
				FROM
				ta__musrenbang
				GROUP BY
				ta__musrenbang.id_kel
			) AS jumlah_kelurahan ON jumlah_kelurahan.id_kel = ref__kelurahan.id
			LEFT JOIN (
				SELECT
				ta__musrenbang.id_kec,
				Sum(case when ta__musrenbang.pengusul = 1 then ta__musrenbang.nilai_usulan else 0 end) AS nilai_usulan_kecamatan,
				Sum(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_verifikasi_kecamatan,
				Sum(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_ditolak_kecamatan,
				Sum(case when ta__musrenbang.pengusul = 2 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_usulan_kelurahan_kecamatan
				FROM
				ta__musrenbang
				GROUP BY
				ta__musrenbang.id_kec
			) AS total_kecamatan ON total_kecamatan.id_kec = ref__kelurahan.id_kec
			LEFT JOIN (
				SELECT
				ta__musrenbang.id_kec,
				count(case when ta__musrenbang.pengusul = 1 then 1 else null end) AS jumlah_usulan_kecamatan,
				count(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 then 1 else null end) AS jumlah_verifikasi_kecamatan,
				count(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 then 1 else null end) AS jumlah_ditolak_kecamatan,
				count(case when ta__musrenbang.pengusul = 2 then 1 else null end) AS jumlah_usulan_kelurahan_kecamatan
				FROM
				ta__musrenbang
				GROUP BY
				ta__musrenbang.id_kec
			) AS jumlah_kecamatan ON jumlah_kecamatan.id_kec = ref__kelurahan.id_kec
			ORDER BY
			kode_kecamatan ASC,
			kode_kelurahan ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data
			
		);
		return $output;
	}
	
	public function hasil_musrenbang_kelurahan_per_program($kelurahan = null)
	{
		if($this->input->get('status') == 1) // Usulan RW
		{
			$pengusul			= '= 1';
			$flag				= '>= 0';
		}
		elseif($this->input->get('status') == 2) // Diterima Kelurahan
		{
			$pengusul			= '= 1';
			$flag				= 'IN(1, 3, 4, 5, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 3) // Ditolak Kelurahan
		{
			$pengusul			= '= 1';
			$flag				= '= 2';
		}
		elseif($this->input->get('status') == 4) // Usulan Kelurahan
		{
			$pengusul			= '= 2';
			$flag				= '>= 3';
		}
		elseif($this->input->get('status') == 5) // Pilih Semua
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= '>= 0';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kelurahan per Bidang Bappeda!', go_to());
		}
		//print_r($status);exit;
		$query										= $this->db->query
		('
			SELECT
			ta__program.id as id_prog,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__program.kd_program,
			ref__urusan.nm_urusan,
			ref__bidang.nm_bidang,
			ref__program.nm_program,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ta__musrenbang.flag,
			ta__musrenbang.map_address,
			ta__musrenbang.urgensi,
			ta__musrenbang.nilai_usulan,
			ta__musrenbang.nilai_kelurahan,
			ref__rw.rw,
			ref__rt.rt,
			ref__sub.nm_sub
			FROM
			ref__musrenbang_jenis_pekerjaan
			INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ta__musrenbang ON ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			WHERE
			ta__musrenbang.id_kel = ' . $kelurahan . ' AND
			ta__musrenbang.pengusul ' . $pengusul . ' AND
			ta__musrenbang.flag ' . $flag . '
			ORDER BY
			ref__urusan.kd_urusan ASC,
			ref__bidang.kd_bidang ASC,
			ref__program.kd_program ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $query,
			'header'								=> $this->header($kelurahan)
		);
		return $output;
	}
	
	public function hasil_musrenbang_kelurahan_per_bidang_bappeda($kelurahan = null)
	{
		if($this->input->get('status') == 1) // Usulan RW
		{
			$pengusul			= '= 1';
			$flag				= '>= 0';
		}
		elseif($this->input->get('status') == 2) // Diterima Kelurahan
		{
			$pengusul			= '= 1';
			$flag				= 'IN(1, 3, 4, 5, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 3) // Ditolak Kelurahan
		{
			$pengusul			= '= 1';
			$flag				= '= 2';
		}
		elseif($this->input->get('status') == 4) // Usulan Kelurahan
		{
			$pengusul			= '= 2';
			$flag				= '>= 3';
		}
		elseif($this->input->get('status') == 5) // Pilih Semua
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= '>= 0';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kelurahan per Bidang Bappeda!', go_to());
		}
		//print_r($status);exit;
		$query										= $this->db->query
		('
			SELECT
			ref__bidang_bappeda.kode,
			ref__bidang_bappeda.nama_bidang,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ta__musrenbang.flag,
			ta__musrenbang.map_address,
			ta__musrenbang.urgensi,
			ta__musrenbang.nilai_usulan,
			ta__musrenbang.nilai_kelurahan,
			ref__rw.rw,
			ref__rt.rt
			FROM
			ref__bidang_bappeda
			LEFT JOIN ref__musrenbang_isu ON ref__bidang_bappeda.id = ref__musrenbang_isu.id_bidang_bappeda
			LEFT JOIN ref__musrenbang_jenis_pekerjaan ON ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu
			LEFT JOIN ta__musrenbang ON ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan
			LEFT JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			LEFT JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			WHERE
			ta__musrenbang.id_kel = ' . $kelurahan . ' AND
			ta__musrenbang.pengusul ' . $pengusul . ' AND
			ta__musrenbang.flag ' . $flag . '
			ORDER BY			
			ref__bidang_bappeda.kode ASC
			
		')
		->result_array();
		$output										= array
		(
			'data'									=> $query,
			'header'								=> $this->header($kelurahan)
		);
		return $output;
	}
	
	public function rekapitulasi_musrenbang_kelurahan_per_isu()
	{
		$data										= $this->db->query
		('
			SELECT
			ref__musrenbang_isu.id AS id_isu,
			ref__musrenbang_isu.kode AS kode_isu,
			ref__musrenbang_isu.nama_isu,
			ref__kelurahan.id AS id_kelurahan,
			ref__kelurahan.kode AS kode_kelurahan,
			ref__kelurahan.nama_kelurahan,
			
			total_isu.jumlah_usulan_isu,
			total_isu.nilai_usulan_isu,
			
			total_isu.jumlah_diterima_kelurahan_isu,
			total_isu.nilai_diterima_kelurahan_isu,
			
			total_isu.jumlah_ditolak_kelurahan_isu,
			total_isu.nilai_ditolak_kelurahan_isu,
			
			total_isu.jumlah_usulan_kelurahan_isu,
			total_isu.nilai_usulan_kelurahan_isu,
			
			Count(CASE WHEN ta__musrenbang.pengusul = 1 THEN 1 else NULL END) AS jumlah_usulan,
			Sum(CASE WHEN ta__musrenbang.pengusul = 1 THEN ta__musrenbang.nilai_usulan else 0 END) AS nilai_usulan,
			
			(Count(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 THEN 1 ELSE NULL END) - Count(CASE WHEN 
			ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 THEN 1 ELSE NULL END)) AS jumlah_diterima_kelurahan,			
			(Sum(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 THEN ta__musrenbang.nilai_kelurahan ELSE 0 END) - Sum(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 THEN ta__musrenbang.nilai_kelurahan ELSE 0 END)) AS nilai_diterima_kelurahan,
			
			Count(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 THEN 1 ELSE NULL END) AS jumlah_ditolak_kelurahan,
			Sum(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 THEN ta__musrenbang.nilai_kelurahan ELSE 0 END) AS nilai_ditolak_kelurahan,
			
			Count(CASE WHEN ta__musrenbang.pengusul = 2 THEN 1 ELSE NULL END) AS jumlah_usulan_kelurahan,
			Sum(CASE WHEN ta__musrenbang.pengusul = 2 THEN ta__musrenbang.nilai_kelurahan ELSE 0 END) AS nilai_usulan_kelurahan
			
			FROM
			ref__musrenbang_isu
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ref__musrenbang_jenis_pekerjaan.id_isu = ref__musrenbang_isu.id
			INNER JOIN ta__musrenbang ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ref__kelurahan ON ta__musrenbang.id_kel = ref__kelurahan.id
			LEFT JOIN (
				SELECT
				ref__musrenbang_jenis_pekerjaan.id_isu,
				Count(CASE WHEN ta__musrenbang.pengusul = 1 THEN 1 else NULL END) AS jumlah_usulan_isu,
				Sum(CASE WHEN ta__musrenbang.pengusul = 1 THEN ta__musrenbang.nilai_usulan else 0 END) AS nilai_usulan_isu,
				
				(Count(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 THEN 1 ELSE NULL END) - Count(CASE WHEN 
				ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 THEN 1 ELSE NULL END)) AS jumlah_diterima_kelurahan_isu,			
				(Sum(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 THEN ta__musrenbang.nilai_kelurahan ELSE 0 END) - Sum(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 THEN ta__musrenbang.nilai_kelurahan ELSE 0 END)) AS nilai_diterima_kelurahan_isu,
				
				Count(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 THEN 1 ELSE NULL END) AS jumlah_ditolak_kelurahan_isu,
				Sum(CASE WHEN ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 THEN ta__musrenbang.nilai_kelurahan ELSE 0 END) AS nilai_ditolak_kelurahan_isu,
			
				Count(CASE WHEN ta__musrenbang.pengusul = 2 THEN 1 ELSE NULL END) AS jumlah_usulan_kelurahan_isu,
				Sum(CASE WHEN ta__musrenbang.pengusul = 2 THEN ta__musrenbang.nilai_kelurahan ELSE 0 END) AS nilai_usulan_kelurahan_isu
			
				FROM
				ta__musrenbang
				INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
				GROUP BY
				ref__musrenbang_jenis_pekerjaan.id_isu
			) AS total_isu ON total_isu.id_isu = ref__musrenbang_isu.id
			GROUP BY
			ref__musrenbang_isu.id,
			ref__kelurahan.id
			ORDER BY
			ref__musrenbang_isu.kode ASC,
			ref__kelurahan.nama_kelurahan ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data
		);
		return $output;
	}
	
	public function daftar_prioritas_kecamatan($kecamatan = null)
	{
		
		if($this->input->get('status') == 1) // Usulan Kelurahan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= 'IN(1, 3, 4, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 2) // Diterima Kecamatan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= 'IN(4, 7, 8)';
		}
		elseif($this->input->get('status') == 3) // Ditolak Kecamatan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= '= 5';
		}
		elseif($this->input->get('status') == 4) // Usulan Kecamatan
		{
			$pengusul			= '= 3';
			$flag				= '>= 6';
		}
		elseif($this->input->get('status') == 5) // Pilih Semua
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= 'IN(1, 3, 4, 5, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 6) // Diterima Kecamatan dan Usulan Kecamatan
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= 'IN(4, 6, 7, 8)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Kecamatan dan Status untuk ' . phrase($this->_request) . ' Daftar Urutan Kegiatan Prioritas Kecamatan Menurut Perangkat Daerah!', go_to());
		}
		//print_r($status);exit;
		$data										= $this->db->query
		('
			SELECT
				ref__prioritas_pembangunan.uraian AS prioritas,
				ref__sasaran_daerah.uraian AS sasaran,
				ref__program.nm_program,
				ta__musrenbang.nama_kegiatan,
				ta__musrenbang.map_address AS lokasi,
				ta__musrenbang.sasaran_kegiatan AS sasaran_kegiatan,
				ta__musrenbang.variabel_kecamatan,
				ta__musrenbang.nilai_kecamatan,
				ref__unit.nm_unit
			FROM
				ta__musrenbang
			LEFT JOIN ref__prioritas_pembangunan ON ta__musrenbang.id_prioritas_pembangunan = ref__prioritas_pembangunan.id
			LEFT JOIN ref__sasaran_daerah ON ref__sasaran_daerah.id_prioritas = ref__prioritas_pembangunan.id
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			WHERE
				ta__musrenbang.id_kec = ' . $kecamatan . ' AND
				ta__musrenbang.pengusul ' . $pengusul . ' AND
				ta__musrenbang.flag ' . $flag . '
			ORDER BY
				ref__prioritas_pembangunan.kode ASC,
				ref__sasaran_daerah.kode ASC,
				ref__rw.rw ASC,
				ref__rt.rt ASC,
				ta__musrenbang.nama_kegiatan ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data,
			'header'								=> $this->header($kecamatan)
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function daftar_prioritas_kelurahan($kelurahan = null)
	{
		if($this->input->get('status') == 1) // Usulan RW
		{
			$pengusul			= '= 1';
			$flag				= '>= 0';
		}
		elseif($this->input->get('status') == 2) // Diterima Kelurahan
		{
			$pengusul			= '= 1';
			$flag				= 'IN(1, 3, 4, 5, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 3) // Ditolak Kelurahan
		{
			$pengusul			= '= 1';
			$flag				= '= 2';
		}
		elseif($this->input->get('status') == 4) // Usulan Kelurahan
		{
			$pengusul			= '= 2';
			$flag				= '>= 3';
		}
		elseif($this->input->get('status') == 5) // Pilih Semua
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= '>= 0';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kelurahan per Bidang Bappeda!', go_to());
		}
		//print_r($status);exit;
		$data										= $this->db->query
		('
			SELECT
				ref__prioritas_pembangunan.uraian AS prioritas,
				ref__sasaran_daerah.uraian AS sasaran,
				ref__program.nm_program,
				ta__musrenbang.nama_kegiatan,
				ta__musrenbang.map_address AS lokasi,
				ta__musrenbang.variabel_kelurahan,
				ta__musrenbang.nilai_kelurahan,
				ref__unit.nm_unit
			FROM
				ta__musrenbang
			LEFT JOIN ref__prioritas_pembangunan ON ta__musrenbang.id_prioritas_pembangunan = ref__prioritas_pembangunan.id
			LEFT JOIN ref__sasaran_daerah ON ref__sasaran_daerah.id_prioritas = ref__prioritas_pembangunan.id
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			WHERE
				ta__musrenbang.id_kel = ' . $kelurahan . ' AND
				ta__musrenbang.pengusul ' . $pengusul . ' AND
				ta__musrenbang.flag ' . $flag . '
			ORDER BY
				ref__prioritas_pembangunan.kode ASC,
				ref__sasaran_daerah.kode ASC,
				ref__rw.rw ASC,
				ref__rt.rt ASC,
				ta__musrenbang.nama_kegiatan ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data,
			'header'								=> $this->header($kelurahan)
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function ba_hasil_musrenbang_kecamatan($kecamatan = null)
	{
		$query										= $this->db->query
		('
			SELECT
				count(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 then 1 else null end) AS jumlah_verifikasi_kelurahan,
				count(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 then 1 else null end) AS jumlah_ditolak_kelurahan,
				count(case when ta__musrenbang.pengusul = 2 then 1 else null end) AS jumlah_usulan_kelurahan,
				count(case when ta__musrenbang.flag > 3 then 1 else null end) AS jumlah_verifikasi_kecamatan,
				count(case when ta__musrenbang.flag = 5 then 1 else null end) AS jumlah_ditolak_kecamatan,
				count(case when ta__musrenbang.pengusul = 3 then 1 else null end) AS jumlah_usulan_kecamatan,
				
				Sum(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag > 0 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_verifikasi_kelurahan,
				Sum(case when ta__musrenbang.pengusul = 1 AND ta__musrenbang.flag = 2 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_ditolak_kelurahan,
				Sum(case when ta__musrenbang.pengusul = 2 then ta__musrenbang.nilai_usulan else 0 end) AS nilai_usulan_kelurahan,
				Sum(case when ta__musrenbang.flag > 3 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_diterima_kecamatan,
				Sum(case when ta__musrenbang.flag = 5 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_ditolak_kecamatan,
				Sum(case when ta__musrenbang.pengusul = 3 then ta__musrenbang.nilai_kelurahan else 0 end) AS nilai_usulan_kecamatan
			FROM
				ta__musrenbang
			WHERE
				ta__musrenbang.id_kec = ' . $kecamatan . '
		')
		->result_array();
		//print_r($query);exit;
		$output										= array
		(
			'data'									=> $query,
			'header'								=> $this->header($kecamatan)
		);
		return $output;
	}
	
	public function hasil_musrenbang_kecamatan($kecamatan = null)
	{
		if($this->input->get('status') == 1) // Usulan Kelurahan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= 'IN(1, 3, 4, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 2) // Diterima Kecamatan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= 'IN(4, 7, 8)';
		}
		elseif($this->input->get('status') == 3) // Ditolak Kecamatan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= '= 5';
		}
		elseif($this->input->get('status') == 4) // Usulan Kecamatan
		{
			$pengusul			= '= 3';
			$flag				= '>= 6';
		}
		elseif($this->input->get('status') == 5) // Pilih Semua
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= 'IN(1, 3, 4, 5, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 6) // Diterima Kecamatan dan Usulan Kecamatan
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= 'IN(4, 6, 7, 8)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Kecamatan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kecamatan!', go_to());
		}
		//print_r($status);exit;
		$header										= $this->db->query
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
		->result_array();
		$query										= $this->db->query
		('
			SELECT
			ta__musrenbang.kode,
			ref__rw.rw,
			ref__rt.rt,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ta__musrenbang.nama_kegiatan,
			ref__kelurahan.nama_kelurahan,
			ta__musrenbang.flag,
			ta__musrenbang.map_address,
			ta__musrenbang.jenis_pekerjaan,
			ta__musrenbang.variabel_usulan,
			ta__musrenbang.variabel_kelurahan,
			ta__musrenbang.variabel_kecamatan,
			ta__musrenbang.variabel_skpd,
			ta__musrenbang.nilai_usulan,
			ta__musrenbang.urgensi,
			ta__musrenbang.nilai_kelurahan,
			ta__musrenbang.nilai_kecamatan
			FROM
			ta__musrenbang
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			INNER JOIN ref__kelurahan ON ta__musrenbang.id_kel = ref__kelurahan.id
			WHERE
			ta__musrenbang.id_kec = ' . $kecamatan . ' AND
			ta__musrenbang.pengusul ' . $pengusul . ' AND
			ta__musrenbang.flag ' . $flag . '
			ORDER BY
			ref__kelurahan.nama_kelurahan ASC,
			ref__rw.rw ASC,
			ref__rt.rt ASC			
		')
		->result_array();
		$output										= array
		(
			'data'									=> $query,
			'header'								=> $this->header($kecamatan)
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_musrenbang_kecamatan()
	{
		$data										= $this->db->query
		('
			SELECT
			ref__kecamatan.kode AS kode_kecamatan,
			ref__kecamatan.kecamatan,
			ref__kelurahan.kode AS kode_kelurahan,
			ref__kelurahan.nama_kelurahan,
			jumlah_rw.jumlah_rw,
			jumlah_rw_kecamatan.jumlah_rw_kecamatan,
			jumlah_kelurahan_sekecamatan.jumlah_kelurahan_sekecamatan,
			
			total_kelurahan.kelurahan_nilai_usulan_kelurahan,
			total_kelurahan.kelurahan_nilai_diterima_kecamatan,
			total_kelurahan.kelurahan_nilai_ditolak_kecamatan,
			total_kelurahan.kelurahan_nilai_usulan_kecamatan,
			
			jumlah_kelurahan.kelurahan_jumlah_usulan_kelurahan,
			jumlah_kelurahan.kelurahan_jumlah_diterima_kecamatan,
			jumlah_kelurahan.kelurahan_jumlah_ditolak_kecamatan,
			jumlah_kelurahan.kelurahan_jumlah_usulan_kecamatan,
			
			total_kecamatan.kecamatan_nilai_usulan_kelurahan,
			total_kecamatan.kecamatan_nilai_diterima_kecamatan,
			total_kecamatan.kecamatan_nilai_ditolak_kecamatan,
			total_kecamatan.kecamatan_nilai_usulan_kecamatan,
			
			jumlah_kecamatan.kecamatan_jumlah_usulan_kelurahan,
			jumlah_kecamatan.kecamatan_jumlah_diterima_kecamatan,
			jumlah_kecamatan.kecamatan_jumlah_ditolak_kecamatan,
			jumlah_kecamatan.kecamatan_jumlah_usulan_kecamatan
			FROM
			ref__kelurahan
			INNER JOIN ref__kecamatan ON ref__kelurahan.id_kec = ref__kecamatan.id
			LEFT JOIN (
				SELECT
					ref__kelurahan.id_kec,
					Count(ref__kelurahan.id) AS jumlah_kelurahan_sekecamatan
				FROM
					ref__kelurahan
				GROUP BY
					ref__kelurahan.id_kec
			) AS jumlah_kelurahan_sekecamatan ON jumlah_kelurahan_sekecamatan.id_kec = ref__kecamatan.id
			LEFT JOIN (
				SELECT
				ref__rw.id_kel,
				Count(ref__rw.id) AS jumlah_rw
				FROM
				ref__rw
				GROUP BY
				ref__rw.id_kel
			) AS jumlah_rw ON jumlah_rw.id_kel = ref__kelurahan.id
			LEFT JOIN (
				SELECT
					ref__kelurahan.id_kec,
					Count(ref__rw.id) AS jumlah_rw_kecamatan
				FROM
					ref__rw
				INNER JOIN
					ref__kelurahan ON ref__kelurahan.id = ref__rw.id_kel
				GROUP BY
					ref__kelurahan.id_kec
			) AS jumlah_rw_kecamatan ON jumlah_rw_kecamatan.id_kec = ref__kecamatan.id
			LEFT JOIN (
				SELECT
				ta__musrenbang.id_kel,
				Sum(case when ta__musrenbang.pengusul IN(1,2) AND ta__musrenbang.flag IN(1,3,4,5,6,7,8) then ta__musrenbang.nilai_kelurahan else 0 end) AS kelurahan_nilai_usulan_kelurahan,
				Sum(case when ta__musrenbang.pengusul IN(1,2) AND ta__musrenbang.flag IN(4,7,8) then ta__musrenbang.nilai_kecamatan else 0 end) AS kelurahan_nilai_diterima_kecamatan,
				Sum(case when ta__musrenbang.flag = 5 then ta__musrenbang.nilai_kelurahan else 0 end) AS kelurahan_nilai_ditolak_kecamatan,
				Sum(case when ta__musrenbang.pengusul = 3 then ta__musrenbang.nilai_kecamatan else 0 end) AS kelurahan_nilai_usulan_kecamatan
				FROM
				ta__musrenbang
				GROUP BY
				ta__musrenbang.id_kel
			) AS total_kelurahan ON total_kelurahan.id_kel = ref__kelurahan.id
			LEFT JOIN (
				SELECT
				ta__musrenbang.id_kel,
				count(case when ta__musrenbang.pengusul IN(1,2) AND ta__musrenbang.flag IN(1,3,4,5,6,7,8) then 1 else null end) AS kelurahan_jumlah_usulan_kelurahan,
				count(case when ta__musrenbang.pengusul IN(1,2) AND ta__musrenbang.flag IN(4,7,8) then 1 else null end) AS kelurahan_jumlah_diterima_kecamatan,
				count(case when ta__musrenbang.flag = 5 then 1 else null end) AS kelurahan_jumlah_ditolak_kecamatan,
				count(case when ta__musrenbang.pengusul = 3 then 1 else null end) AS kelurahan_jumlah_usulan_kecamatan
				FROM
				ta__musrenbang
				GROUP BY
				ta__musrenbang.id_kel
			) AS jumlah_kelurahan ON jumlah_kelurahan.id_kel = ref__kelurahan.id
			LEFT JOIN (
				SELECT
				ta__musrenbang.id_kec,
				Sum(case when ta__musrenbang.pengusul IN(1,2) AND ta__musrenbang.flag IN(1,3,4,5,6,7,8) then ta__musrenbang.nilai_kelurahan else 0 end) AS kecamatan_nilai_usulan_kelurahan,
				Sum(case when ta__musrenbang.pengusul IN(1,2) AND ta__musrenbang.flag IN(4,7,8) then ta__musrenbang.nilai_kecamatan else 0 end) AS kecamatan_nilai_diterima_kecamatan,
				Sum(case when ta__musrenbang.flag = 5 then ta__musrenbang.nilai_kelurahan else 0 end) AS kecamatan_nilai_ditolak_kecamatan,
				Sum(case when ta__musrenbang.pengusul = 3 then ta__musrenbang.nilai_kecamatan else 0 end) AS kecamatan_nilai_usulan_kecamatan
				FROM
				ta__musrenbang
				GROUP BY
				ta__musrenbang.id_kec
			) AS total_kecamatan ON total_kecamatan.id_kec = ref__kelurahan.id_kec
			LEFT JOIN (
				SELECT
				ta__musrenbang.id_kec,
				count(case when ta__musrenbang.pengusul IN(1,2) AND ta__musrenbang.flag IN(1,3,4,5,6,7,8) then 1 else null end) AS kecamatan_jumlah_usulan_kelurahan,
				count(case when ta__musrenbang.pengusul IN(1,2) AND ta__musrenbang.flag IN(4,7,8) then 1 else null end) AS kecamatan_jumlah_diterima_kecamatan,
				count(case when ta__musrenbang.flag = 5 then 1 else null end) AS kecamatan_jumlah_ditolak_kecamatan,
				count(case when ta__musrenbang.pengusul = 3 then 1 else null end) AS kecamatan_jumlah_usulan_kecamatan
				FROM
				ta__musrenbang
				GROUP BY
				ta__musrenbang.id_kec
			) AS jumlah_kecamatan ON jumlah_kecamatan.id_kec = ref__kelurahan.id_kec
			ORDER BY
			kode_kecamatan ASC,
			kode_kelurahan ASC
		')
		->result_array();
		//print_r($data);exit;
		$output										= array
		(
			'data'									=> $data
		);
		return $output;
	}
	
	public function hasil_musrenbang_kecamatan_per_program($kecamatan = null)
	{
		if($this->input->get('status') == 1) // Usulan Kelurahan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= 'IN(1, 3, 4, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 2) // Diterima Kecamatan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= 'IN(4, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 3) // Ditolak Kecamatan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= '= 5';
		}
		elseif($this->input->get('status') == 4) // Usulan Kecamatan
		{
			$pengusul			= '= 2';
			$flag				= '>= 6';
		}
		elseif($this->input->get('status') == 5) // Pilih Semua
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= 'IN(1, 3, 4, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 6) // Diterima Kecamatan dan Usulan Kecamatan
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= 'IN(4, 6, 7, 8)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Kecamatan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kecamatan Per Program!', go_to());
		}
		//print_r($status);exit;
		$header										= $this->db->query
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
		->result_array();
		$query										= $this->db->query
		('
			SELECT
			ta__program.id as id_prog,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__program.kd_program,
			ref__urusan.nm_urusan,
			ref__bidang.nm_bidang,
			ref__program.nm_program,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ta__musrenbang.flag,
			ta__musrenbang.map_address,
			ta__musrenbang.urgensi,
			ta__musrenbang.nilai_usulan,
			ta__musrenbang.nilai_kelurahan,
			ref__rw.rw,
			ref__rt.rt,
			ref__kelurahan.nama_kelurahan,
			ref__sub.nm_sub
			FROM
			ref__musrenbang_jenis_pekerjaan
			INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ta__musrenbang ON ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			INNER JOIN ref__kelurahan ON ta__musrenbang.id_kel = ref__kelurahan.id
			WHERE
			ta__musrenbang.id_kec = ' . $kecamatan . ' AND
			ta__musrenbang.pengusul ' . $pengusul . ' AND
			ta__musrenbang.flag ' . $flag . '
			ORDER BY
			ref__urusan.kd_urusan ASC,
			ref__bidang.kd_bidang ASC,
			ref__program.kd_program ASC			
		')
		->result_array();
		$output										= array
		(
			//'header'								=> $header,
			'data'									=> $query,
			'header'								=> $this->header($kecamatan)
		);
		return $output;
	}
	
	public function hasil_musrenbang_kecamatan_per_bidang_bappeda($kecamatan = null)
	{
		if($this->input->get('status') == 1) // Usulan Kelurahan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= 'IN(1, 3, 4, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 2) // Diterima Kecamatan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= 'IN(4, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 3) // Ditolak Kecamatan
		{
			$pengusul			= 'IN(1, 2)';
			$flag				= '= 5';
		}
		elseif($this->input->get('status') == 4) // Usulan Kecamatan
		{
			$pengusul			= '= 2';
			$flag				= '>= 6';
		}
		elseif($this->input->get('status') == 5) // Pilih Semua
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= 'IN(1, 3, 4, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 6) // Diterima Kecamatan dan Usulan Kecamatan
		{
			$pengusul			= 'IN(1, 2, 3)';
			$flag				= 'IN(4, 6, 7, 8)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Kecamatan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kecamatan Per Bidang Bappeda!', go_to());
		}
		//print_r($status);exit;
		$header										= $this->db->query
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
		->result_array();
		$query										= $this->db->query
		('
			SELECT
			ref__bidang_bappeda.kode,
			ref__bidang_bappeda.nama_bidang,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ta__musrenbang.pengusul,
			ta__musrenbang.flag,
			ta__musrenbang.map_address,
			ta__musrenbang.urgensi,
			ta__musrenbang.nilai_usulan,
			ta__musrenbang.nilai_kelurahan,
			ref__rw.rw,
			ref__rt.rt,
			ref__kelurahan.nama_kelurahan
			FROM
			ref__bidang_bappeda
			LEFT JOIN ref__musrenbang_isu ON ref__bidang_bappeda.id = ref__musrenbang_isu.id_bidang_bappeda
			LEFT JOIN ref__musrenbang_jenis_pekerjaan ON ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu
			LEFT JOIN ta__musrenbang ON ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan
			LEFT JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			LEFT JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			INNER JOIN ref__kelurahan ON ta__musrenbang.id_kel = ref__kelurahan.id
			WHERE
			ta__musrenbang.id_kec = ' . $kecamatan . ' AND
			ta__musrenbang.pengusul ' . $pengusul . ' AND
			ta__musrenbang.flag ' . $flag . '
			ORDER BY
			ref__bidang_bappeda.kode
		')
		->result_array();
		$output										= array
		(
		//	'header'								=> $header,
			'data'									=> $query,
			'header'								=> $this->header($kecamatan)
		);
		return $output;
	}
	
	public function hasil_musrenbang_skpd($unit = null, $status = null)
	{
		if($status == 1) // Usulan Kecamatan
		{
			$flag				= 'IN(4, 6, 7, 8)';
		}
		elseif($status == 2) // Diterima SKPD
		{
			$flag				= '= 7';
		}
		elseif($status == 3) // Ditolak SKPD
		{
			$flag				= '= 8';
		}
		elseif($status == 4) // Pilih Semua
		{
			$flag				= 'IN(4, 5, 6, 7, 8)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD!', go_to());
		}
		//print_r($status);exit;
		$query_header								= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
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
				ref__kecamatan.kode AS kode_kecamatan,
				ref__kecamatan.kecamatan AS nama_kecamatan,
				ref__kelurahan.kode AS kode_kelurahan,
				ref__kelurahan.nama_kelurahan,
				ref__rw.rw,
				ref__rt.rt,
				ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
				ta__musrenbang.map_address,
				ta__musrenbang.nilai_kecamatan,
				ta__musrenbang.nilai_skpd,
				ta__musrenbang.flag,
				ta__musrenbang.urgensi,
				ta__musrenbang.nama_kegiatan,
				ref__sub.id_unit
			FROM
				ta__musrenbang
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__kecamatan ON ta__musrenbang.id_kec = ref__kecamatan.id
			INNER JOIN ref__kelurahan ON ta__musrenbang.id_kel = ref__kelurahan.id
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			WHERE
				ta__musrenbang.flag ' . $flag . ' AND
				ref__sub.id_unit = ' . $unit . '
			ORDER BY
				kode_kecamatan ASC,
				kode_kelurahan ASC,
				ref__rw.rw ASC,
				ref__rt.rt ASC	
		')
		->result_array();
		$output										= array
		(
			'header'								=> $query_header,
			'data'									=> $query
		);
		return $output;
	}
	
	public function rekapitulasi_musrenbang_skpd()
	{
		$data										= $this->db->query
		('
			SELECT
			ref__urusan.id AS id_urusan,
			ref__bidang.id AS id_bidang,
			ref__unit.id AS id_unit,
			ref__urusan.kd_urusan AS kode_urusan,
			ref__bidang.kd_bidang AS kode_bidang,
			ref__unit.kd_unit AS kode_unit,
			ref__urusan.nm_urusan AS nama_urusan,
			ref__bidang.nm_bidang AS nama_bidang,
			ref__unit.nm_unit AS nama_unit,
			
			(jumlah_urusan.jumlah_verifikasi_kecamatan_urusan - jumlah_urusan.jumlah_ditolak_kecamatan_urusan) AS jumlah_usulan_kecamatan_urusan,
			(jumlah_urusan.jumlah_verifikasi_skpd_urusan - jumlah_urusan.jumlah_ditolak_skpd_urusan) AS jumlah_diterima_skpd_urusan,
			jumlah_urusan.jumlah_ditolak_skpd_urusan,
			
			(nilai_urusan.nilai_verifikasi_kecamatan_urusan - nilai_urusan.nilai_ditolak_kecamatan_urusan) AS nilai_usulan_kecamatan_urusan,
			(nilai_urusan.nilai_verifikasi_skpd_urusan - nilai_urusan.nilai_ditolak_skpd_urusan) AS nilai_diterima_skpd_urusan,
			nilai_urusan.nilai_ditolak_skpd_urusan,
			
			(jumlah_bidang.jumlah_verifikasi_kecamatan_bidang - jumlah_bidang.jumlah_ditolak_kecamatan_bidang) AS jumlah_usulan_kecamatan_bidang,
			(jumlah_bidang.jumlah_verifikasi_skpd_bidang - jumlah_bidang.jumlah_ditolak_skpd_bidang) AS jumlah_diterima_skpd_bidang,
			jumlah_bidang.jumlah_ditolak_skpd_bidang,
			
			(nilai_bidang.nilai_verifikasi_kecamatan_bidang - nilai_bidang.nilai_ditolak_kecamatan_bidang) AS nilai_usulan_kecamatan_bidang,
			(nilai_bidang.nilai_verifikasi_skpd_bidang - nilai_bidang.nilai_ditolak_skpd_bidang) AS nilai_diterima_skpd_bidang,
			nilai_bidang.nilai_ditolak_skpd_bidang,
			
			(jumlah_skpd.jumlah_verifikasi_kecamatan - jumlah_skpd.jumlah_ditolak_kecamatan) AS jumlah_usulan_kecamatan,
			(jumlah_skpd.jumlah_verifikasi_skpd - jumlah_skpd.jumlah_ditolak_skpd) AS jumlah_diterima_skpd,
			jumlah_skpd.jumlah_ditolak_skpd,
			
			(nilai_skpd.nilai_verifikasi_kecamatan - nilai_skpd.nilai_ditolak_kecamatan) AS nilai_usulan_kecamatan,
			(nilai_skpd.nilai_verifikasi_skpd - nilai_skpd.nilai_ditolak_skpd) AS nilai_diterima_skpd,
			nilai_skpd.nilai_ditolak_skpd
			FROM
			ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
				ref__sub.id_unit,
				count(case when ta__musrenbang.flag > 3 then 1 else null end) AS jumlah_verifikasi_kecamatan,
				count(case when ta__musrenbang.flag = 5 then 1 else null end) AS jumlah_ditolak_kecamatan,
				count(case when ta__musrenbang.flag > 6 then 1 else null end) AS jumlah_verifikasi_skpd,
				count(case when ta__musrenbang.flag = 8 then 1 else null end) AS jumlah_ditolak_skpd
				FROM
				ta__musrenbang
				INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
				INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				GROUP BY
				ref__sub.id_unit
			) AS jumlah_skpd ON jumlah_skpd.id_unit = ref__unit.id
			LEFT JOIN (
				SELECT
				ref__sub.id_unit,
				Sum(case when ta__musrenbang.flag > 3 then ta__musrenbang.nilai_kecamatan else 0 end) AS nilai_verifikasi_kecamatan,
				Sum(case when ta__musrenbang.flag = 5 then ta__musrenbang.nilai_kecamatan else 0 end) AS nilai_ditolak_kecamatan,
				Sum(case when ta__musrenbang.flag > 6 then ta__musrenbang.nilai_skpd else 0 end) AS nilai_verifikasi_skpd,
				Sum(case when ta__musrenbang.flag = 8 then ta__musrenbang.nilai_skpd else 0 end) AS nilai_ditolak_skpd
				FROM
				ta__musrenbang
				INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
				INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				GROUP BY
				ref__sub.id_unit
			) AS nilai_skpd ON nilai_skpd.id_unit = ref__unit.id
			LEFT JOIN (
				SELECT
				ref__unit.id_bidang,
				count(case when ta__musrenbang.flag > 3 then 1 else null end) AS jumlah_verifikasi_kecamatan_bidang,
				count(case when ta__musrenbang.flag = 5 then 1 else null end) AS jumlah_ditolak_kecamatan_bidang,
				count(case when ta__musrenbang.flag > 6 then 1 else null end) AS jumlah_verifikasi_skpd_bidang,
				count(case when ta__musrenbang.flag = 8 then 1 else null end) AS jumlah_ditolak_skpd_bidang
				FROM
				ta__musrenbang
				INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
				INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
				GROUP BY
				ref__unit.id_bidang
			) AS jumlah_bidang ON jumlah_bidang.id_bidang = ref__bidang.id
			LEFT JOIN (
				SELECT
				ref__unit.id_bidang,
				Sum(case when ta__musrenbang.flag > 3 then ta__musrenbang.nilai_kecamatan else 0 end) AS nilai_verifikasi_kecamatan_bidang,
				Sum(case when ta__musrenbang.flag = 5 then ta__musrenbang.nilai_kecamatan else 0 end) AS nilai_ditolak_kecamatan_bidang,
				Sum(case when ta__musrenbang.flag > 6 then ta__musrenbang.nilai_skpd else 0 end) AS nilai_verifikasi_skpd_bidang,
				Sum(case when ta__musrenbang.flag = 8 then ta__musrenbang.nilai_skpd else 0 end) AS nilai_ditolak_skpd_bidang
				FROM
				ta__musrenbang
				INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
				INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
				GROUP BY
				ref__unit.id_bidang
			) AS nilai_bidang ON nilai_bidang.id_bidang = ref__bidang.id
			LEFT JOIN (
				SELECT
				ref__bidang.id_urusan,
				count(case when ta__musrenbang.flag > 3 then 1 else null end) AS jumlah_verifikasi_kecamatan_urusan,
				count(case when ta__musrenbang.flag = 5 then 1 else null end) AS jumlah_ditolak_kecamatan_urusan,
				count(case when ta__musrenbang.flag > 6 then 1 else null end) AS jumlah_verifikasi_skpd_urusan,
				count(case when ta__musrenbang.flag = 8 then 1 else null end) AS jumlah_ditolak_skpd_urusan
				FROM
				ta__musrenbang
				INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
				INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
				INNER JOIN ref__bidang ON ref__bidang.id = ref__unit.id_bidang
				GROUP BY
				ref__bidang.id_urusan
			) AS jumlah_urusan ON jumlah_urusan.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
				ref__bidang.id_urusan,
				Sum(case when ta__musrenbang.flag > 3 then ta__musrenbang.nilai_kecamatan else 0 end) AS nilai_verifikasi_kecamatan_urusan,
				Sum(case when ta__musrenbang.flag = 5 then ta__musrenbang.nilai_kecamatan else 0 end) AS nilai_ditolak_kecamatan_urusan,
				Sum(case when ta__musrenbang.flag > 6 then ta__musrenbang.nilai_skpd else 0 end) AS nilai_verifikasi_skpd_urusan,
				Sum(case when ta__musrenbang.flag = 8 then ta__musrenbang.nilai_skpd else 0 end) AS nilai_ditolak_skpd_urusan
				FROM
				ta__musrenbang
				INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
				INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
				INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
				INNER JOIN ref__unit ON ref__unit.id = ref__sub.id_unit
				INNER JOIN ref__bidang ON ref__bidang.id = ref__unit.id_bidang
				GROUP BY
				ref__bidang.id_urusan
			) AS nilai_urusan ON nilai_urusan.id_urusan = ref__urusan.id
			WHERE
			ref__unit.tahun = ' . get_userdata('year') . '
			ORDER BY
			ref__urusan.kd_urusan ASC,
			ref__bidang.kd_bidang ASC,
			ref__unit.kd_unit ASC
		')
		->result_array();
		//print_r($data);exit;
		$output										= array
		(
			'data'									=> $data
		);
		return $output;
	}
	
	public function hasil_musrenbang_skpd_per_program($unit = null, $status = null)
	{
		if($status == 1) // Usulan Kecamatan
		{
			$flag				= 'IN(4, 5, 6, 7, 8)';
		}
		elseif($status == 2) // Diterima SKPD
		{
			$flag				= '= 7';
		}
		elseif($status == 3) // Ditolak SKPD
		{
			$flag				= '= 8';
		}
		elseif($status == 4) // Pilih Semua
		{
			$flag				= 'IN(4, 5, 6, 7, 8)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD Per Program!', go_to());
		}
		//print_r($status);exit;
		$query_header								= $this->db->query
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
			LIMIT 1
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				ref__program.id AS id_program,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				ref__program.kd_program AS kode_program,
				ref__urusan.nm_urusan AS nama_urusan,
				ref__bidang.nm_bidang AS nama_bidang,
				ref__sub.nm_sub AS nama_sub,
				ref__program.nm_program AS nama_program,
				ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
				ta__musrenbang.flag,
				ta__musrenbang.map_address,
				ta__musrenbang.urgensi,
				ta__musrenbang.nilai_kecamatan,
				ta__musrenbang.nilai_skpd,
				ref__kecamatan.kode AS kode_kecamatan,
				ref__kecamatan.kecamatan AS nama_kecamatan,
				ref__kelurahan.kode AS kode_kelurahan,
				ref__kelurahan.nama_kelurahan,
				ref__rw.rw,
				ref__rt.rt
			FROM
				ta__musrenbang
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__musrenbang.jenis_pekerjaan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__kecamatan ON ta__musrenbang.id_kec = ref__kecamatan.id
			INNER JOIN ref__kelurahan ON ta__musrenbang.id_kel = ref__kelurahan.id
			INNER JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			INNER JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			WHERE
				ref__sub.id_unit = ' . $unit . ' AND
				ta__musrenbang.flag ' . $flag . '
			ORDER BY
				kode_urusan ASC,
				kode_bidang ASC,
				kode_program ASC		
		')
		->result_array();
		$output										= array
		(
			'header'								=> $query_header,
			'data'									=> $query
		);
		return $output;
	}
	
	public function hasil_musrenbang_skpd_per_bidang_bappeda($unit = null, $status = null)
	{
		if($status == 1) // Usulan Kecamatan
		{
			$flag				= 'IN(4, 5, 6, 7, 8)';
		}
		elseif($status == 2) // Diterima SKPD
		{
			$flag				= '= 7';
		}
		elseif($status == 3) // Ditolak SKPD
		{
			$flag				= '= 8';
		}
		elseif($status == 4) // Pilih Semua
		{
			$flag				= 'IN(4, 5, 6, 7, 8)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD Per Bidang Bappeda!', go_to());
		}
		//print_r($status);exit;
		$query_header								= $this->db->query
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
			LIMIT 1
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ref__bidang_bappeda.kode,
				ref__bidang_bappeda.nama_bidang,
				ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
				ta__musrenbang.flag,
				ta__musrenbang.map_address,
				ta__musrenbang.urgensi,
				ta__musrenbang.nilai_kecamatan,
				ta__musrenbang.nilai_skpd,
				ref__rw.rw,
				ref__rt.rt,
				ref__kelurahan.nama_kelurahan
			FROM
				ref__bidang_bappeda
			LEFT JOIN ref__musrenbang_isu ON ref__bidang_bappeda.id = ref__musrenbang_isu.id_bidang_bappeda
			LEFT JOIN ref__musrenbang_jenis_pekerjaan ON ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu
			LEFT JOIN ta__musrenbang ON ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan
			LEFT JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			LEFT JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			INNER JOIN ref__kelurahan ON ta__musrenbang.id_kel = ref__kelurahan.id
			INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			WHERE
				ref__sub.id_unit = ' . $unit . ' AND
				ta__musrenbang.flag ' . $flag . '
			ORDER BY
				ref__bidang_bappeda.kode
		')
		->result_array();
		$output										= array
		(
			'header'								=> $query_header,
			'data'									=> $query
		);
		return $output;
	}
	
	public function hasil_musrenbang_skpd_bidang_bappeda($bidang_bappeda = null)
	{
		if($this->input->get('status') == 1) // Usulan Kecamatan
		{
			$flag				= 'IN(4, 5, 6, 7, 8)';
		}
		elseif($this->input->get('status') == 2) // Diterima SKPD
		{
			$flag				= '= 7';
		}
		elseif($this->input->get('status') == 3) // Ditolak SKPD
		{
			$flag				= '= 8';
		}
		elseif($this->input->get('status') == 4) // Pilih Semua
		{
			$flag				= 'IN(4, 5, 6, 7, 8)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Bidang Bappeda dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD Bidang Bappeda!', go_to());
		}
		if($bidang_bappeda == 1) // Bidang IPW
		{
			$bidang						= 1;
			$bidang_bappeda				= 'IN(1)';
		}
		elseif($bidang_bappeda == 2) // Bidang PMM
		{
			$bidang						= 2;
			$bidang_bappeda				= 'IN(2)';
		}
		elseif($bidang_bappeda == 3) // Bidang ESDA
		{
			$bidang						= 3;
			$bidang_bappeda				= 'IN(3)';
		}
		elseif($bidang_bappeda == 4) // Pilih Semua Bidang
		{
			$bidang						= 4;
			$bidang_bappeda				= 'IN(1,2,3)';
		}
		else
		{
			return throw_exception(403, 'Silakan pilih Bidang Bappeda dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD Bidang Bappeda!', go_to());
		}
		$query_header								= $this->db->query
		('
			SELECT
				ref__bidang_bappeda.nama_bidang,
				ref__bidang_bappeda.jabatan_kepala,
				ref__bidang_bappeda.nama_kepala,
				ref__bidang_bappeda.nip_kepala
			FROM
				ref__bidang_bappeda
			WHERE
				ref__bidang_bappeda.id = ' . $bidang. '
			LIMIT 1
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ref__bidang_bappeda.kode,
				ref__bidang_bappeda.nama_bidang,
				ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
				ta__musrenbang.flag,
				ta__musrenbang.map_address,
				ta__musrenbang.urgensi,
				ta__musrenbang.nilai_kecamatan,
				ta__musrenbang.nilai_skpd,
				ref__rw.rw,
				ref__rt.rt,
				ref__kelurahan.nama_kelurahan
			FROM
				ref__bidang_bappeda
			LEFT JOIN ref__musrenbang_isu ON ref__bidang_bappeda.id = ref__musrenbang_isu.id_bidang_bappeda
			LEFT JOIN ref__musrenbang_jenis_pekerjaan ON ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu
			LEFT JOIN ta__musrenbang ON ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan
			LEFT JOIN ref__rw ON ta__musrenbang.id_rw = ref__rw.id
			LEFT JOIN ref__rt ON ta__musrenbang.id_rt = ref__rt.id
			INNER JOIN ref__kelurahan ON ta__musrenbang.id_kel = ref__kelurahan.id
			WHERE
				ref__bidang_bappeda.kode ' . $bidang_bappeda . ' AND
				ta__musrenbang.flag ' . $flag . '
			ORDER BY
				ref__bidang_bappeda.kode
		')
		->result_array();
		$output										= array
		(
			'header'								=> $query_header,
			'data'									=> $query
		);
		//print_r($output);exit;
		return $output;
	}
}