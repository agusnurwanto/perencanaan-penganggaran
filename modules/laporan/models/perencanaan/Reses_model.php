<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Reses_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function hasil_reses_dprd($dprd = null)
	{
		if($this->input->get('status') == 1) // Usulan DPRD
		{
			$pengusul			= '= 2';
			$flag				= '>= 0';
		}
		elseif($this->input->get('status') == 2) // Diterima SKPD
		{
			$pengusul			= '= 2';
			$flag				= 'IN(1)';
		}
		elseif($this->input->get('status') == 3) // Ditolak SKPD
		{
			$pengusul			= '= 2';
			$flag				= '= 2';
		}
		elseif($this->input->get('status') == 4) // Pilih Semua
		{
			$pengusul			= '= 2';
			$flag				= '>= 0';
		}
		else
		{
			generateMessages(403, 'Silakan pilih DPRD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Reses DPRD!', go_to());
		}
		//print_r($status);exit;
		$header_query										= $this->db->query
		('
			SELECT
				ref__dprd_fraksi.kode AS kode_fraksi,
				ref__dprd_fraksi.nama_fraksi,
				ref__dprd.kode AS kode_dprd,
				ref__dprd.nama_dewan,
				ref__dprd.jabatan_dewan,
				ref__dprd.pagu
			FROM
				ref__dprd
			INNER JOIN ref__dprd_fraksi ON ref__dprd.id_fraksi = ref__dprd_fraksi.id
			WHERE
				ref__dprd.id = ' . $dprd . '
			LIMIT 1
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ref__musrenbang_jenis_pekerjaan.kode,
				ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
				ref__dprd.nama_dewan,
				ta__reses.id_reses,
				ta__reses.pengusul,
				ta__reses.map_address,
				ta__reses.alamat_detail,
				ta__reses.flag,
				ta__reses.pilihan,
				ta__reses.kd_keg,
				ta__reses.kegiatan,
				ta__reses.kegiatan_judul_baru,
				ta__reses.nilai_usulan
			FROM
				ta__reses
			LEFT JOIN ref__musrenbang_jenis_pekerjaan ON ta__reses.jenis_kegiatan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ref__dprd ON ref__dprd.id = ta__reses.id_reses
			WHERE
			' . (99 == $dprd ? '
			ta__reses.id_reses LIKE "%"
			' : '
			ta__reses.id_reses = ' . $dprd . '
			') . '
			AND
			ta__reses.pengusul ' . $pengusul . ' AND
			ta__reses.flag ' . $flag . '
			
		')
		->result();
		//echo $this->db->last_query();exit;
		//print_r($query);exit;
		$output										= array
		(
			'header'								=> $header_query,
			'data'									=> $query
		);
		return $output;
	}
	
	public function rekapitulasi_reses_dprd()
	{
		$data										= $this->db->query
		('
			SELECT
			ref__dprd_fraksi.id AS id_fraksi,
			ref__dprd_fraksi.kode AS kode_fraksi,
			ref__dprd_fraksi.nama_fraksi,
			ref__dprd_fraksi.pagu,
			ref__dprd.id AS id_dprd,
			ref__dprd.kode AS kode_dprd,
			ref__dprd.nama_dewan,
			ref__dprd.jabatan_dewan,
			ref__dprd.pagu,
			
			jumlah_fraksi.jumlah_usulan_fraksi,
			jumlah_fraksi.jumlah_diterima_fraksi,
			jumlah_fraksi.jumlah_ditolak_fraksi,
			
			nilai_fraksi.nilai_usulan_fraksi,
			nilai_fraksi.nilai_diterima_fraksi,
			nilai_fraksi.nilai_ditolak_fraksi,
			
			jumlah_reses.jumlah_usulan,
			jumlah_reses.jumlah_diterima,
			jumlah_reses.jumlah_ditolak,
			
			nilai_reses.nilai_usulan,
			nilai_reses.nilai_diterima,
			nilai_reses.nilai_ditolak
			FROM
			ref__dprd
			INNER JOIN ref__dprd_fraksi ON ref__dprd.id_fraksi = ref__dprd_fraksi.id
			LEFT JOIN (
				SELECT
				ta__reses.id_reses,
				Count(CASE WHEN ta__reses.flag >= 0 THEN 1 ELSE NULL END) AS jumlah_usulan,
				Count(CASE WHEN ta__reses.flag = 1 THEN 1 ELSE NULL END) AS jumlah_diterima,
				Count(CASE WHEN ta__reses.flag = 2 THEN 1 ELSE NULL END) AS jumlah_ditolak
				FROM
				ta__reses
				WHERE
				ta__reses.pengusul = 2
				GROUP BY
				ta__reses.id_reses
			) AS jumlah_reses ON jumlah_reses.id_reses = ref__dprd.id
			LEFT JOIN (
				SELECT
				ta__reses.id_reses,
				Sum(CASE WHEN ta__reses.flag >= 0 THEN ta__reses.nilai_usulan ELSE 0 END) AS nilai_usulan,
				Sum(CASE WHEN ta__reses.flag = 1 THEN ta__reses.nilai_usulan ELSE 0 END) AS nilai_diterima,
				Sum(CASE WHEN ta__reses.flag = 2 THEN ta__reses.nilai_usulan ELSE 0 END) AS nilai_ditolak
				FROM
				ta__reses
				WHERE
				ta__reses.pengusul = 2
				GROUP BY
				ta__reses.id_reses
			) AS nilai_reses ON nilai_reses.id_reses = ref__dprd.id
			LEFT JOIN (
				SELECT
				ref__dprd.id_fraksi,
				Count(CASE WHEN ta__reses.flag >= 0 THEN 1 ELSE NULL END) AS jumlah_usulan_fraksi,
				Count(CASE WHEN ta__reses.flag = 1 THEN 1 ELSE NULL END) AS jumlah_diterima_fraksi,
				Count(CASE WHEN ta__reses.flag = 2 THEN 1 ELSE NULL END) AS jumlah_ditolak_fraksi
				FROM
				ta__reses
				INNER JOIN ref__dprd ON ref__dprd.id = ta__reses.id_reses
				WHERE
				ta__reses.pengusul = 2
				GROUP BY
				ref__dprd.id_fraksi
			) AS jumlah_fraksi ON jumlah_fraksi.id_fraksi = ref__dprd.id_fraksi
			LEFT JOIN (
				SELECT
				ref__dprd.id_fraksi,
				Sum(CASE WHEN ta__reses.flag >= 0 THEN ta__reses.nilai_usulan ELSE 0 END) AS nilai_usulan_fraksi,
				Sum(CASE WHEN ta__reses.flag = 1 THEN ta__reses.nilai_usulan ELSE 0 END) AS nilai_diterima_fraksi,
				Sum(CASE WHEN ta__reses.flag = 2 THEN ta__reses.nilai_usulan ELSE 0 END) AS nilai_ditolak_fraksi
				FROM
				ta__reses
				INNER JOIN ref__dprd ON ref__dprd.id = ta__reses.id_reses
				WHERE
				ta__reses.pengusul = 2
				GROUP BY
				ref__dprd.id_fraksi
			) AS nilai_fraksi ON nilai_fraksi.id_fraksi = ref__dprd.id_fraksi
			ORDER BY
			kode_fraksi ASC,
			kode_dprd ASC
		')
		->result_array();
		$output										= array
		(
			'data'									=> $data
		);
		return $output;
	}
	
	public function hasil_reses_dprd_per_program($dprd = null, $status = null)
	{
		if($status == 1) // Usulan DPRD
		{
			$status				= '>= 0';
		}
		elseif($status == 2) // Diterima SKPD
		{
			$status				= 'IN(1)';
		}
		elseif($status == 3) // Ditolak SKPD
		{
			$status				= '= 2';
		}
		elseif($status == 4) // Pilih Semua
		{
			$status				= '>= 0';
		}
		else
		{
			generateMessages(403, 'Silakan pilih DPRD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Reses DPRD!', go_to());
		}
		if($dprd == 99)
		{
			$dprd = " '%' ";
		}
		//print_r($status);exit;
		$header_query										= $this->db->query
		('
			SELECT
				ref__dprd_fraksi.kode AS kode_fraksi,
				ref__dprd_fraksi.nama_fraksi,
				ref__dprd.kode AS kode_dprd,
				ref__dprd.nama_dewan,
				ref__dprd.jabatan_dewan,
				ref__dprd.pagu
			FROM
				ref__dprd
			INNER JOIN ref__dprd_fraksi ON ref__dprd.id_fraksi = ref__dprd_fraksi.id
			WHERE
				ref__dprd.id = ' . $dprd . '
			LIMIT 1
		')
		->row();
		$query										= $this->db->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__urusan.nm_urusan AS nama_urusan,
				ref__bidang.id AS id_bidang,
				ref__bidang.kd_bidang AS kode_bidang,
				ref__bidang.nm_bidang AS nama_bidang,
				ref__program.id AS id_program,
				ref__program.kd_program AS kode_program,
				ref__program.nm_program AS nama_program,
				ta__program.kd_id_prog,
				ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
				ref__dprd.nama_dewan,
				ta__reses.pengusul,
				ta__reses.flag,
				ta__reses.pilihan,
				ta__reses.map_address,
				ta__reses.alamat_detail,
				ref__unit.nm_unit,
				ta__reses.kd_keg AS kode_kegiatan,
				ta__reses.kegiatan,
				ta__reses.nilai_usulan AS nilai
			FROM
				ta__reses
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__reses.jenis_kegiatan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ref__dprd ON ref__dprd.id = ta__reses.id_reses
			INNER JOIN ta__program ON ref__musrenbang_jenis_pekerjaan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ta__reses.id_reses LIKE ' . $dprd . ' AND
				ta__reses.pengusul = 2 AND
				ta__reses.flag ' . $status . '
			ORDER BY
				kode_urusan ASC,
				kode_bidang ASC,
				kode_program ASC,
				ta__program.kd_id_prog ASC
		')
		->result_array();
		$output										= array
		(
			'header'								=> $header_query,
			'data'									=> $query
		);
		return $output;
	}
	
	public function hasil_reses_dprd_per_bidang_bappeda($dprd = null)
	{
		if($this->input->get('status') == 1) // Usulan DPRD
		{
			$pengusul			= '= 2';
			$flag				= '>= 0';
		}
		elseif($this->input->get('status') == 2) // Diterima SKPD
		{
			$pengusul			= '= 2';
			$flag				= 'IN(1)';
		}
		elseif($this->input->get('status') == 3) // Ditolak SKPD
		{
			$pengusul			= '= 2';
			$flag				= '= 2';
		}
		elseif($this->input->get('status') == 4) // Pilih Semua
		{
			$pengusul			= '= 2';
			$flag				= '>= 0';
		}
		else
		{
			generateMessages(403, 'Silakan pilih DPRD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Reses DPRD!', go_to());
		}
		if($dprd == 99)
		{
			$dprd = " '%' ";
		}
		//print_r($status);exit;
		$query										= $this->db->query
		('
			SELECT
			ref__bidang_bappeda.kode AS kode_bidang_bappeda,
			ref__bidang_bappeda.nama_bidang,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ref__dprd.nama_dewan,
			ta__reses.map_address,
			ta__reses.alamat_detail,
			ta__reses.kegiatan,
			ta__reses.nilai_usulan AS nilai
			FROM
			ta__reses
			INNER JOIN ta__program ON ta__reses.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang_bappeda ON ref__unit.id_bidang_bappeda = ref__bidang_bappeda.id
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__reses.jenis_kegiatan = ref__musrenbang_jenis_pekerjaan.id
			INNER JOIN ref__dprd ON ref__dprd.id = ta__reses.id_reses
			WHERE
			ta__reses.id_reses LIKE ' . $dprd . '
			ORDER BY
			ref__bidang_bappeda.kode ASC,
			ta__reses.kd_keg ASC
			
		')
		->result_array();
		$output										= array
		(
			'data'									=> $query
		);
		return $output;
	}
	
	public function hasil_reses_dprd_per_skpd($skpd = null)
	{
		if($this->input->get('status') == 1) // Usulan DPRD
		{
			$pengusul			= '= 2';
			$flag				= '>= 0';
		}
		elseif($this->input->get('status') == 2) // Diterima SKPD
		{
			$pengusul			= '= 2';
			$flag				= 'IN(1)';
		}
		elseif($this->input->get('status') == 3) // Ditolak SKPD
		{
			$pengusul			= '= 2';
			$flag				= '= 2';
		}
		elseif($this->input->get('status') == 4) // Pilih Semua
		{
			$pengusul			= '= 2';
			$flag				= '>= 0';
		}
		else
		{
			generateMessages(403, 'Silakan pilih DPRD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Reses DPRD!', go_to());
		}		
		if($skpd == 999)
		{
			$skpd = " '%' ";
		}
		//print_r($status);exit;
		$header_query										= $this->db->query
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
				ref__unit.id = ' . $skpd . '
			LIMIT 1
		')
		->result_array();
		$query										= $this->db->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				ref__unit.id AS id_unit,
				ref__program.id AS id_program,
				ta__reses.id AS id_kegiatan,
				ref__urusan.kd_urusan AS kode_urusan,
				ref__bidang.kd_bidang AS kode_bidang,
				ref__unit.kd_unit AS kode_unit,
				ref__program.kd_program AS kode_program,
				ta__reses.kd_keg AS kode_kegiatan,
				ref__urusan.nm_urusan AS nama_urusan,
				ref__bidang.nm_bidang AS nama_bidang,
				ref__unit.nm_unit AS nama_unit,
				ref__program.nm_program AS nama_program,
				ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
				ta__reses.flag,
				ta__reses.kegiatan,
				ta__reses.map_address,
				ta__reses.alamat_detail,
				ta__reses.kelurahan,
				ta__reses.kecamatan,
				ta__reses.nilai_usulan AS nilai,
				ref__dprd.kode AS kode_dprd,
				ref__dprd.nama_dewan AS nama_dprd
			FROM
				ta__reses
			INNER JOIN ta__program ON ta__reses.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__dprd ON ta__reses.id_reses = ref__dprd.id
			INNER JOIN ref__musrenbang_jenis_pekerjaan ON ta__reses.jenis_kegiatan = ref__musrenbang_jenis_pekerjaan.id
			WHERE
				ta__reses.pengusul ' . $pengusul . ' AND
				ta__reses.flag ' . $flag . '
				AND ref__unit.id LIKE ' . $skpd . '
			ORDER BY
				kode_urusan ASC,
				kode_bidang ASC,
				kode_unit ASC,
				kode_program ASC,
				kode_kegiatan ASC
		')
		->result_array();
		//print_r($query);exit;
		$output										= array
		(
			'header'								=> $header_query,
			'data'									=> $query
		);
		return $output;
	}
}