<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sinkronisasi_referensi_program extends Aksara
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
		
		// prepare ref_program
		$ref_program								= $this->model
		->query
		('
			SELECT
				ref__urusan.kd_urusan AS Kd_Urusan,
				ref__bidang.kd_bidang AS Kd_Bidang,
				ref__program.kd_program AS Kd_Prog,
				ref__program.nm_program AS Ket_Program
			FROM
				ref__program
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__program.tahun = ' . $this->_year . '
		')
		->result_array();
				
		// if the query above have some result(s)
		if($ref_program)
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
			
			foreach($ref_program as $key => $val)
			{
				/* check data di simda */
				if($this->_db->get_where('Ref_Program', array('Kd_Urusan' => $val['Kd_Urusan'], 'Kd_Bidang' => $val['Kd_Bidang'], 'Kd_Prog' => $val['Kd_Prog']), 1)->num_rows() > 0)
				{
					/* data ditemukan, update */
					if($this->_db->update('Ref_Program', array('Ket_Program' => $val['Ket_Program']), array('Kd_Urusan' => $val['Kd_Urusan'], 'Kd_Bidang' => $val['Kd_Bidang'], 'Kd_Prog' => $val['Kd_Prog'])))
					{
						$update++;
					}
				}
				else
				{
					/* data tidak ditemukan, insert */
					if($this->_db->insert('Ref_Program', array('Ket_Program' => $val['Ket_Program'], 'Kd_Urusan' => $val['Kd_Urusan'], 'Kd_Bidang' => $val['Kd_Bidang'], 'Kd_Prog' => $val['Kd_Prog'])))
					{
						$insert++;
					}
				}
			}
			
			// returning the message
			if($update || $insert)
			{
				// nothing wrong, both query is running successfully!
				generateMessages(200, 'Sinkronisasi Referensi Program berhasil dijalankan. Sebanyak <b>' . number_format(($update + $insert)) . '</b> data berhasil dikirim ke SIMDA', current_page('../massal'));
			}
			else
			{
				// nothing happened
				generateMessages(202, 'Sinkronisasi Referensi Program gagal dijalankan...', current_page('../massal'));
			}
		}
		else
		{
			generateMessages(404, 'Query tidak mendapatkan hasil. Sinkronisasi dibatalkan...', current_page('../massal'));
		}
	}
}