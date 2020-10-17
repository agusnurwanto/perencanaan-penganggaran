<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sinkronisasi_program extends Aksara
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
		if(in_array(get_userdata('group_id'), array(1)))
		{
			$this->form_validation->set_rules('unit', 'Sub Unit', 'required');
			if($this->form_validation->run() === false)
			{
				return generateMessages(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
			}
		}
		
		if($this->input->post('unit') == 'all')
		{
			$kd_urusan									= "'%'";
			$kd_bidang									= "'%'";
			$kd_unit									= "'%'";
		}
		else
		{
			$kode										= explode('.', $this->input->post('unit'));
			$kd_urusan									= $kode[0];
			$kd_bidang									= $kode[1];
			$kd_unit									= $kode[2];
		}
		
		// prepare ta_program
		$ta_program									= $this->model
		->query
		('
			SELECT
				ref__program.tahun AS Tahun,
				ref__urusan.kd_urusan AS Kd_Urusan,
				ref__bidang.kd_bidang AS Kd_Bidang,
				ref__unit.kd_unit AS Kd_Unit,
				ref__sub.kd_sub AS Kd_Sub,
				ref__program.kd_program AS Kd_Prog,
				ta__program.kd_id_prog AS ID_Prog,
				ref__program.nm_program AS Ket_Program,
				LEFT(ta__program.kd_id_prog, 1) AS Kd_Urusan1,
				RIGHT(ta__program.kd_id_prog, 2) AS Kd_Bidang1
			FROM
				ta__program
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			WHERE
				ta__program.tahun = ' . $this->_year . ' AND
				ref__urusan.kd_urusan LIKE ' . $kd_urusan . ' AND
				ref__bidang.kd_bidang LIKE ' . $kd_bidang . ' AND
				ref__unit.kd_unit LIKE ' . $kd_unit . '
		')
		->result_array();
		
		// prepare capaian_program
		$capaian_program									= $this->model
		->query
		('
			SELECT
				ref__urusan.tahun AS Tahun,
				ref__urusan.kd_urusan AS Kd_Urusan,
				ref__bidang.kd_bidang AS Kd_Bidang,
				ref__unit.kd_unit AS Kd_Unit,
				ref__sub.kd_sub AS Kd_Sub,
				ref__program.kd_program AS Kd_Prog,
				ta__program.kd_id_prog AS ID_Prog,
				ta__program_capaian.kode AS No_ID,
				ta__program_capaian.tolak_ukur AS Tolak_Ukur,
				ta__program_capaian.tahun_1_target AS Target_Angka,
				ta__program_capaian.tahun_1_satuan AS Target_Uraian
			FROM
				ta__program_capaian
			INNER JOIN ta__program ON ta__program_capaian.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ta__program.tahun = ' . $this->_year . ' AND
				ref__urusan.kd_urusan LIKE ' . $kd_urusan . ' AND
				ref__bidang.kd_bidang LIKE ' . $kd_bidang . ' AND
				ref__unit.kd_unit LIKE ' . $kd_unit . '
		')
		->result_array();
		//print_r($capaian_program);exit;
		// if the query above have some result(s)
		if($ta_program || $capaian_program)
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
			
			if($ta_program)
			{
				foreach($ta_program as $key => $val)
				{
					$checker						= $this->_db
					->query
					('
						SELECT
							ID_Prog
						FROM
							Ta_Program
						WHERE
							Tahun = ' . $val['Tahun'] . ' AND
							Kd_Urusan = ' . $val['Kd_Urusan'] . ' AND
							Kd_Bidang = ' . $val['Kd_Bidang'] . ' AND
							Kd_Unit = ' . $val['Kd_Unit'] . ' AND
							Kd_Sub = ' . $val['Kd_Sub'] . ' AND
							Kd_Prog = ' . $val['Kd_Prog'] . ' AND
							ID_Prog = ' . $val['ID_Prog'] . '
					')
					->row();
					
					if($checker)
					{
						$do_update					= $this->_db
						->update
						(
							// table
							'Ta_Program',
							// set
							array
							(
								'Ket_Program'		=> $val['Ket_Program']
							),
							// where
							array
							(
								'Tahun'				=> $val['Tahun'],
								'Kd_Urusan'			=> $val['Kd_Urusan'],
								'Kd_Bidang'			=> $val['Kd_Bidang'],
								'Kd_Unit'			=> $val['Kd_Unit'],
								'Kd_Sub'			=> $val['Kd_Sub'],
								'Kd_Prog'			=> $val['Kd_Prog'],
								'ID_Prog'			=> $val['ID_Prog'],
								'Kd_Urusan1'		=> $val['Kd_Urusan1'],
								'Kd_Bidang1'		=> $val['Kd_Bidang1']
							)
						);
						if($do_update)
						{
							$update++;
						}
					}
					else
					{
						$do_insert					= $this->_db
						->insert
						(
							// table
							'Ta_Program',
							// set
							array
							(
								'Tahun'				=> $val['Tahun'],
								'Kd_Urusan'			=> $val['Kd_Urusan'],
								'Kd_Bidang'			=> $val['Kd_Bidang'],
								'Kd_Unit'			=> $val['Kd_Unit'],
								'Kd_Sub'			=> $val['Kd_Sub'],
								'Kd_Prog'			=> $val['Kd_Prog'],
								'ID_Prog'			=> $val['ID_Prog'],
								'Ket_Program'		=> $val['Ket_Program'],
								'Kd_Urusan1'		=> $val['Kd_Urusan1'],
								'Kd_Bidang1'		=> $val['Kd_Bidang1']
							)
						);
						if($do_insert)
						{
							$insert++;
						}
					}
				}
			}
			
			if($capaian_program)
			{
				// delete Ta_Kegiatan if year is matched
				$this->_db
					->query
					('
						DELETE FROM Ta_Capaian_Program
						WHERE
							Tahun = ' . $this->_year . ' AND
							Kd_Prog > 0 AND
							Kd_Urusan LIKE ' . $kd_urusan . ' AND
							Kd_Bidang LIKE ' . $kd_bidang . ' AND
							Kd_Unit LIKE ' . $kd_unit . '
					');
				// prepare insert Capaian Program
				if($this->_db->insert_batch('Ta_Capaian_Program', $capaian_program, sizeof($capaian_program)))
				{
					// successfully insert new data...
					$update							= sizeof($capaian_program);
				}
				/*foreach($capaian_program as $key => $val)
				{
					$checker						= $this->_db
					->query
					('
						SELECT
							No_ID
						FROM
							Ta_Capaian_Program
						WHERE
							Tahun = ' . $val['Tahun'] . ' AND
							Kd_Urusan = ' . $val['Kd_Urusan'] . ' AND
							Kd_Bidang = ' . $val['Kd_Bidang'] . ' AND
							Kd_Unit = ' . $val['Kd_Unit'] . ' AND
							Kd_Sub = ' . $val['Kd_Sub'] . ' AND
							Kd_Prog = ' . $val['Kd_Prog'] . ' AND
							ID_Prog = ' . $val['ID_Prog'] . ' AND
							No_ID = ' . $val['No_ID'] . '
					')
					->row();
					
					if($checker)
					{
						$do_update					= $this->_db
						->update
						(
							// table
							'Ta_Capaian_Program',
							// set
							array
							(
								'Tolak_Ukur'		=> $val['Tolak_Ukur'],
								'Target_Angka'		=> $val['Target_Angka'],
								'Target_Uraian'		=> $val['Target_Uraian']
							),
							// where
							array
							(
								'Tahun'				=> $val['Tahun'],
								'Kd_Urusan'			=> $val['Kd_Urusan'],
								'Kd_Bidang'			=> $val['Kd_Bidang'],
								'Kd_Unit'			=> $val['Kd_Unit'],
								'Kd_Sub'			=> $val['Kd_Sub'],
								'Kd_Prog'			=> $val['Kd_Prog'],
								'ID_Prog'			=> $val['ID_Prog'],
								'No_ID'				=> $val['No_ID']
							)
						);
						if($do_update)
						{
							$update++;
						}
					}
					else
					{
						$do_insert					= $this->_db
						->insert
						(
							// table
							'Ta_Capaian_Program',
							// set
							array
							(
								'Tahun'				=> $val['Tahun'],
								'Kd_Urusan'			=> $val['Kd_Urusan'],
								'Kd_Bidang'			=> $val['Kd_Bidang'],
								'Kd_Unit'			=> $val['Kd_Unit'],
								'Kd_Sub'			=> $val['Kd_Sub'],
								'Kd_Prog'			=> $val['Kd_Prog'],
								'ID_Prog'			=> $val['ID_Prog'],
								'No_ID'				=> $val['No_ID'],
								'Tolak_Ukur'		=> $val['Tolak_Ukur'],
								'Target_Angka'		=> $val['Target_Angka'],
								'Target_Uraian'		=> $val['Target_Uraian']
							)
						);
						if($do_insert)
						{
							$insert++;
						}
					}
				}*/
			}
			
			// returning the message
			if($update || $insert)
			{
				// nothing wrong, both query is running successfully!
				generateMessages(200, 'Sinkronisasi Transaksi Program berhasil dijalankan. Sebanyak <b>' . number_format(($update + $insert)) . '</b> data berhasil dikirim ke SIMDA', current_page('../massal'));
			}
			else
			{
				// nothing happened
				generateMessages(202, 'Sinkronisasi Program gagal dijalankan...', current_page('../massal'));
			}
		}
		else
		{
			generateMessages(404, 'Query tidak mendapatkan hasil. Sinkronisasi dibatalkan...', current_page('../massal'));
		}
	}
}