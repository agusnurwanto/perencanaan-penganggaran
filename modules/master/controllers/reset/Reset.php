<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Master > Pengosongan Data
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Reset extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->set_theme('backend');
		
		$this->set_method('index');
	}
	
	public function index()
	{
		if($this->input->post('token'))
		{
			return $this->_validate_form();
		}
		
		$this->set_title('Pengosongan Data')
		->set_icon('mdi mdi-trash-can-outline')
		->render();
	}
	
	private function _validate_form()
	{
		$this->load->library('form_validation');
		
		$this->form_validation->set_rules('jenis', 'Jenis Data', 'required|in_list[penatausahaan,anggaran,renja,program-opd,musrenbang,rekening-anggaran,unit-subunit,referensi-umum]');
		$this->form_validation->set_rules('kode_akses', 'Kode Akses', 'required|callback_validate_kode_akses');
		
		if($this->form_validation->run() === false)
		{
			return throw_exception(400, $this->form_validation->error_array());
		}
		
		$action										= false;
		$type										= null;
		
		if('penatausahaan' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_penatausahaan();
			$type									= 'Penatausahaan';
		}
		elseif('anggaran' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_anggaran();
			$type									= 'Anggaran';
		}
		elseif('standar-harga' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_standar_harga();
			$type									= 'Standar Harga';
		}
		elseif('renja' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_renja();
			$type									= 'Renja';
		}
		elseif('program-opd' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_program_opd();
			$type									= 'Program OPD';
		}
		elseif('musrenbang' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_musrenbang();
			$type									= 'Musrenbang';
		}
		elseif('rekening-anggaran' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_rekening_anggaran();
			$type									= 'Rekening Anggaran';
		}
		elseif('unit-subunit' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_unit_subunit();
			$type									= 'Unit dan Sub Unit';
		}
		elseif('referensi-umum' == $this->input->post('jenis'))
		{
			$action									= $this->_reset_referensi_umum();
			$type									= 'Referensi Umum';
		}
		
		if($action)
		{
			return throw_exception(200, 'Data ' . $type . ' berhasil dikosongkan');
		}
		
		return throw_exception(404, 'Tidak dapat mengosongkan data ' . $type . ', silakan coba lagi');
	}
	
	public function validate_kode_akses($value = null)
	{
		if(!$value)
		{
			$this->form_validation->set_message('validate_kode_akses', 'Silakan masukkan Kode Akses Anda!');
			
			return false;
		}
		
		$query										= $this->model->select('password')->get_where
		(
			'app__users',
			array
			(
				'user_id'							=> get_userdata('user_id')
			),
			1
		)
		->row('password');
		
		if(!password_verify($this->input->post('kode_akses') . SALT, $query))
		{
			$this->form_validation->set_message('validate_kode_akses', 'Kode akses yang Anda masukkan salah!');
			
			return false;
		}
		
		return true;
	}
	
	private function _reset_penatausahaan()
	{
		return true;
	}
	
	private function _reset_anggaran()
	{
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ta__rencana_pendapatan_arsip');
		$this->model->truncate('ta__rencana_pendapatan');
		$this->model->truncate('ta__rencana_pembiayaan_arsip');
		$this->model->truncate('ta__rencana_pembiayaan');
		$this->model->truncate('ta__rencana_arsip');
		$this->model->truncate('ta__rencana');
		
		$this->model->truncate('ta__asistensi_arsip');
		$this->model->truncate('ta__asistensi_setuju_arsip');
		$this->model->truncate('ta__asistensi_setuju');
		$this->model->truncate('ta__asistensi');
		$this->model->truncate('ta__asistensi_kak_arsip');
		$this->model->truncate('ta__asistensi_kak');
		$this->model->truncate('ta__asistensi_kak_setuju_arsip');
		$this->model->truncate('ta__asistensi_kak_setuju');
		$this->model->truncate('ta__belanja_arsip');
		$this->model->truncate('ta__belanja_rinci');
		$this->model->truncate('ta__belanja_sub');
		$this->model->truncate('ta__belanja');
		$this->model->truncate('ta__anggaran_pendapatan_arsip');
		$this->model->truncate('ta__anggaran_pendapatan_rinci');
		$this->model->truncate('ta__anggaran_pendapatan');
		$this->model->truncate('ta__anggaran_pembiayaan_arsip');
		$this->model->truncate('ta__anggaran_pembiayaan_rinci');
		$this->model->truncate('ta__anggaran_pembiayaan');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		return true;
	}
	
	private function _reset_standar_harga()
	{
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ref__standar_rekening');
		$this->model->truncate('ref__standar_harga');
		$this->model->truncate('ref__standar_harga_7');
		$this->model->truncate('ref__standar_harga_6');
		$this->model->truncate('ref__standar_harga_5');
		$this->model->truncate('ref__standar_harga_4');
		$this->model->truncate('ref__standar_harga_3');
		$this->model->truncate('ref__standar_harga_2');
		$this->model->truncate('ref__standar_harga_1');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		return true;
	}
	
	private function _reset_renja()
	{
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ta__rencana_pendapatan_arsip');
		$this->model->truncate('ta__rencana_pendapatan');
		$this->model->truncate('ta__rencana_pembiayaan_arsip');
		$this->model->truncate('ta__rencana_pembiayaan');
		$this->model->truncate('ta__rencana_arsip');
		$this->model->truncate('ta__rencana');
		
		$this->model->truncate('ta__asistensi_arsip');
		$this->model->truncate('ta__asistensi_setuju_arsip');
		$this->model->truncate('ta__asistensi_setuju');
		$this->model->truncate('ta__asistensi');
		$this->model->truncate('ta__asistensi_kak_arsip');
		$this->model->truncate('ta__asistensi_kak');
		$this->model->truncate('ta__asistensi_kak_setuju_arsip');
		$this->model->truncate('ta__asistensi_kak_setuju');
		$this->model->truncate('ta__belanja_arsip');
		$this->model->truncate('ta__belanja_rinci');
		$this->model->truncate('ta__belanja_sub');
		$this->model->truncate('ta__belanja');
		$this->model->truncate('ta__anggaran_pendapatan_arsip');
		$this->model->truncate('ta__anggaran_pendapatan_rinci');
		$this->model->truncate('ta__anggaran_pendapatan');
		$this->model->truncate('ta__anggaran_pembiayaan_arsip');
		$this->model->truncate('ta__anggaran_pembiayaan_rinci');
		$this->model->truncate('ta__anggaran_pembiayaan');
		
		$this->model->truncate('ta__kak');
		$this->model->truncate('ta__desk_renja');
		$this->model->truncate('ta__indikator_sub_arsip');
		$this->model->truncate('ta__indikator_sub');
		$this->model->truncate('ta__indikator_arsip');
		$this->model->truncate('ta__indikator');
		$this->model->truncate('ta__kegiatan_sub_arsip');
		$this->model->truncate('ta__kegiatan_sub_pendukung');
		$this->model->truncate('ta__kegiatan_sub');
		$this->model->truncate('ta__kegiatan_arsip');
		$this->model->truncate('ta__kegiatan_pendukung');
		$this->model->truncate('ta__kegiatan');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		return true;
	}
	
	private function _reset_program_opd()
	{
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ta__program_capaian_arsip');
		$this->model->truncate('ta__program_capaian');
		$this->model->truncate('ta__program_arsip');
		$this->model->truncate('ta__program');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		return true;
	}
	
	private function _reset_musrenbang()
	{
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ta__musrenbang_skpd_berkas');
		$this->model->truncate('ta__musrenbang_kecamatan_berkas');
		$this->model->truncate('ta__musrenbang_kelurahan_berkas');
		$this->model->truncate('ta__musrenbang');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		return true;
	}
	
	private function _reset_rekening_anggaran()
	{
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ref__rek_6');
		$this->model->truncate('ref__rek_5');
		$this->model->truncate('ref__rek_4');
		$this->model->truncate('ref__rek_3');
		$this->model->truncate('ref__rek_2');
		$this->model->truncate('ref__rek_1');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		return true;
	}
	
	private function _reset_unit_subunit()
	{
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ref__sub_jabatan');
		$this->model->truncate('ref__sub');
		$this->model->truncate('ref__unit_jabatan');
		$this->model->truncate('ref__unit');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		return true;
	}
	
	private function _reset_referensi_umum()
	{
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ref__kegiatan_sub');
		$this->model->truncate('ref__kegiatan');
		$this->model->truncate('ref__program');
		$this->model->truncate('ref__bidang');
		$this->model->truncate('ref__urusan');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		return true;
	}
}
