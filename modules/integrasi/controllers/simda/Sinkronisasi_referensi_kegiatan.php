<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sinkronisasi_referensi_Kegiatan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->permission->must_ajax();
		$this->_year								= get_userdata('year');
	}
	
	public function index()
	{
		/*if(in_array(get_userdata('group_id'), array(1)))
		{
			$this->form_validation->set_rules('unit', 'Sub Unit', 'required');
			if($this->form_validation->run() === false)
			{
				return generateMessages(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
			}
		}*/
		
		// prepare ref_kegiatan
		//print_r($this->input->post('kd_perubahan'));exit;
		$ref_kegiatan								= $this->model
		->query
		('
			SELECT
				LEFT(ta__kegiatan_arsip.kode_id_prog, 1) AS Kd_Urusan,
				RIGHT(ta__kegiatan_arsip.kode_id_prog, 2) AS Kd_Bidang,
				ta__kegiatan_arsip.kode_prog AS Kd_Prog,
				ta__kegiatan_arsip.kode_keg AS Kd_Keg,
				ta__kegiatan_arsip.kegiatan AS Ket_Kegiatan
			FROM
				ta__kegiatan_arsip
			WHERE
				ta__kegiatan_arsip.tahun = ' . $this->_year . '
			AND ta__kegiatan_arsip.kode_perubahan = ' . $this->input->post('kd_perubahan') . '
			GROUP BY
				LEFT(ta__kegiatan_arsip.kode_id_prog, 1),
				RIGHT(ta__kegiatan_arsip.kode_id_prog, 2),
				ta__kegiatan_arsip.kode_prog,
				ta__kegiatan_arsip.kode_keg
			ORDER BY
				ta__kegiatan_arsip.kode_urusan ASC,
				ta__kegiatan_arsip.kode_bidang ASC,
				ta__kegiatan_arsip.kode_prog ASC,
				ta__kegiatan_arsip.kode_keg ASC
		')
		->result_array();
				
		// if the query above have some result(s)
		if($ref_kegiatan)
		{
			// get active connection (by "year" session)
			$connection								= $this->model->get_where('ref__koneksi', array('tahun' => $this->_year), 1)->row();
			
			// check the result
			if(!$connection)
			{
				// no result! throw error...
				generateMessages(500, phrase('connection_is_not_found'), current_page('../massal'));
			}
			
			// prepare the connection parameter
			$database_driver						= $connection->database_driver;
			$hostname								= $this->encryption->decrypt($connection->hostname);
			$port									= $this->encryption->decrypt($connection->port);
			$username								= $this->encryption->decrypt($connection->username);
			$password								= $this->encryption->decrypt($connection->password);
			$database_name							= $this->encryption->decrypt($connection->database_name);
			
			// getting a new connection
			$this->_db								= $this->model->new_connection($database_driver, $hostname, $port, $username, $password, $database_name);
			
			// variable to monitor the result
			$update									= 0;
			$insert									= 0;
			
			foreach($ref_kegiatan as $key => $val)
			{
				/* check data di simda */
				if($this->_db->get_where('Ref_Kegiatan', array('Kd_Urusan' => $val['Kd_Urusan'], 'Kd_Bidang' => $val['Kd_Bidang'], 'Kd_Prog' => $val['Kd_Prog'], 'Kd_Keg' => $val['Kd_Keg']), 1)->num_rows() > 0)
				{
					/* data ditemukan, update */
					if($this->_db->update('Ref_Kegiatan', array('Ket_Kegiatan' => $val['Ket_Kegiatan']), array('Kd_Urusan' => $val['Kd_Urusan'], 'Kd_Bidang' => $val['Kd_Bidang'], 'Kd_Prog' => $val['Kd_Prog'], 'Kd_Keg' => $val['Kd_Keg'])))
					{
						$update++;
					}
				}
				else
				{
					/* data tidak ditemukan, insert */
					if($this->_db->insert('Ref_Kegiatan', array('Ket_Kegiatan' => $val['Ket_Kegiatan'], 'Kd_Urusan' => $val['Kd_Urusan'], 'Kd_Bidang' => $val['Kd_Bidang'], 'Kd_Prog' => $val['Kd_Prog'], 'Kd_Keg' => $val['Kd_Keg'])))
					{
						$insert++;
					}
				}
			}
			
			// returning the message
			if($update || $insert)
			{
				// nothing wrong, both query is running successfully!
				generateMessages(200, 'Sinkronisasi Referensi Kegiatan berhasil dijalankan. Sebanyak <b>' . number_format(($update + $insert)) . '</b> data berhasil dikirim ke SIMDA', current_page('../massal'));
			}
			else
			{
				// nothing happened
				generateMessages(202, 'Sinkronisasi Referensi Kegiatan gagal dijalankan...', current_page('../massal'));
			}
		}
		else
		{
			generateMessages(404, 'Query tidak mendapatkan hasil. Sinkronisasi dibatalkan...', current_page('../massal'));
		}
	}
}