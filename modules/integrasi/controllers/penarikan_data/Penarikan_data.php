<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Integrasi > SIPD > Penarikan Data
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Penarikan_data extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Penarikan Data')
		->set_icon('mdi mdi-database-import')
		->render();
	}
	
	public function referensi()
	{
		$query_program								= $this->model->get_where
		(
			'sipd__ref__program',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_kegiatan								= $this->model->get_where
		(
			'sipd__ref__kegiatan',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_sub_kegiatan							= $this->model->get_where
		(
			'sipd__ref__kegiatan_sub',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		if(!$query_program || !$query_kegiatan || !$query_sub_kegiatan)
		{
			return throw_exception(404, 'Salah satu dari referensi kegiatan tidak mendapatkan hasil. Penarikan data dibatalkan');
		}
		
		$this->model->trans_begin();
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ref__kegiatan_sub');
		$this->model->truncate('ref__kegiatan');
		$this->model->truncate('ref__program');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		$this->model->insert_batch('ref__program', $query_program, sizeof($query_program));
		$this->model->insert_batch('ref__kegiatan', $query_kegiatan, sizeof($query_kegiatan));
		$this->model->insert_batch('ref__kegiatan_sub', $query_sub_kegiatan, sizeof($query_sub_kegiatan));
		
		if($this->model->trans_status() === FALSE)
		{
			$this->model->trans_rollback();
		}
		else
		{
			$this->model->trans_commit();
			
			return throw_exception(200, 'Penarikan data Referensi Kegiatan dari SIPD berhasil dilakukan blah blah blah');
		}
		
		return throw_exception(500, 'Terjadi kesalahan pada saat melakukan penarikan data SIPD. Silakan coba kembali beberapa saat lagi...');
	}
	
	public function unit()
	{
		$query_urusan								= $this->model->get_where
		(
			'sipd__ref__urusan',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_bidang								= $this->model->get_where
		(
			'sipd__ref__bidang',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_unit									= $this->model->get_where
		(
			'sipd__ref__unit',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_sub_unit								= $this->model->get_where
		(
			'sipd__ref__sub',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		if(!$query_urusan || !$query_bidang || !$query_unit || !$query_sub_unit)
		{
			return throw_exception(404, 'Salah satu dari unit organisasi tidak mendapatkan hasil. Penarikan data dibatalkan');
		}
		
		$this->model->trans_begin();
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ref__sub');
		$this->model->truncate('ref__unit');
		$this->model->truncate('ref__bidang');
		$this->model->truncate('ref__urusan');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		$this->model->insert_batch('ref__urusan', $query_urusan, sizeof($query_urusan));
		$this->model->insert_batch('ref__bidang', $query_bidang, sizeof($query_bidang));
		$this->model->insert_batch('ref__unit', $query_unit, sizeof($query_unit));
		$this->model->insert_batch('ref__sub', $query_sub_unit, sizeof($query_sub_unit));
		
		if($this->model->trans_status() === FALSE)
		{
			$this->model->trans_rollback();
		}
		else
		{
			$this->model->trans_commit();
			
			return throw_exception(200, 'Penarikan data unit organisasi dari SIPD berhasil dilakukan blah blah blah');
		}
		
		return throw_exception(500, 'Terjadi kesalahan pada saat melakukan penarikan data SIPD. Silakan coba kembali beberapa saat lagi...');
	}
	
	public function rekening()
	{
		$query_rekening_1							= $this->model->get_where
		(
			'sipd__ref__rek_1',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_rekening_2							= $this->model->get_where
		(
			'sipd__ref__rek_2',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_rekening_3							= $this->model->get_where
		(
			'sipd__ref__rek_3',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_rekening_4							= $this->model->get_where
		(
			'sipd__ref__rek_4',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_rekening_5							= $this->model->get_where
		(
			'sipd__ref__rek_5',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_rekening_6							= $this->model->get_where
		(
			'sipd__ref__rek_6',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->result_array();
		
		if(!$query_rekening_1 || !$query_rekening_2 || !$query_rekening_3 || !$query_rekening_4 || !$query_rekening_5 || !$query_rekening_6)
		{
			return throw_exception(404, 'Salah satu dari rekening tidak mendapatkan hasil. Penarikan data dibatalkan');
		}
		
		$this->model->trans_begin();
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ref__rek_6');
		$this->model->truncate('ref__rek_5');
		$this->model->truncate('ref__rek_4');
		$this->model->truncate('ref__rek_3');
		$this->model->truncate('ref__rek_2');
		$this->model->truncate('ref__rek_1');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		$this->model->insert_batch('ref__rek_1', $query_rekening_1, sizeof($query_rekening_1));
		$this->model->insert_batch('ref__rek_2', $query_rekening_2, sizeof($query_rekening_2));
		$this->model->insert_batch('ref__rek_3', $query_rekening_3, sizeof($query_rekening_3));
		$this->model->insert_batch('ref__rek_4', $query_rekening_4, sizeof($query_rekening_4));
		$this->model->insert_batch('ref__rek_5', $query_rekening_5, sizeof($query_rekening_5));
		$this->model->insert_batch('ref__rek_6', $query_rekening_6, sizeof($query_rekening_6));
		
		if($this->model->trans_status() === FALSE)
		{
			$this->model->trans_rollback();
		}
		else
		{
			$this->model->trans_commit();
			
			return throw_exception(200, 'Penarikan data rekening dari SIPD berhasil dilakukan blah blah blah');
		}
		
		return throw_exception(500, 'Terjadi kesalahan pada saat melakukan penarikan data SIPD. Silakan coba kembali beberapa saat lagi...');
	}
	
	public function kegiatan()
	{
		$query_program								= $this->model->get_where
		(
			'sipd__ta__program',
			array
			(
			)
		)
		->result_array();
		
		$query_kegiatan								= $this->model->get_where
		(
			'sipd__ta__kegiatan',
			array
			(
			)
		)
		->result_array();
		
		$query_kegiatan_sub							= $this->model->get_where
		(
			'sipd__ta__kegiatan_sub',
			array
			(
			)
		)
		->result_array();
		
		if(!$query_program || !$query_kegiatan || !$query_kegiatan_sub)
		{
			return throw_exception(404, 'Salah satu dari query tidak mendapatkan hasil. Penarikan data dibatalkan');
		}
		
		$this->model->trans_begin();
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
		
		$this->model->truncate('ta__kegiatan_sub');
		$this->model->truncate('ta__kegiatan');
		$this->model->truncate('ta__program');
		
		$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
		
		$this->model->insert_batch('ta__program', $query_program, sizeof($query_program));
		$this->model->insert_batch('ta__kegiatan', $query_kegiatan, sizeof($query_kegiatan));
		$this->model->insert_batch('ta__kegiatan_sub', $query_kegiatan_sub, sizeof($query_kegiatan_sub));
		
		if($this->model->trans_status() === FALSE)
		{
			$this->model->trans_rollback();
		}
		else
		{
			$this->model->trans_commit();
			
			return throw_exception(200, 'Penarikan data kegiatan dari SIPD berhasil dilakukan blah blah blah');
		}
		
		return throw_exception(500, 'Terjadi kesalahan pada saat melakukan penarikan data SIPD. Silakan coba kembali beberapa saat lagi...');
	}
}
