<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sinkronisasi_kegiatan extends Aksara
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
		
		// prepare ta_kegiatan
		$ta_kegiatan								= $this->model
		->query
		('			
			SELECT
				ta__kegiatan_arsip.tahun AS Tahun,
				ta__kegiatan_arsip.kode_urusan AS Kd_Urusan,
				ta__kegiatan_arsip.kode_bidang AS Kd_Bidang,
				ta__kegiatan_arsip.kode_unit AS Kd_Unit,
				ta__kegiatan_arsip.kode_sub AS Kd_Sub,
				ta__kegiatan_arsip.kode_prog AS Kd_Prog,
				ta__kegiatan_arsip.kode_id_prog AS ID_Prog,
				ta__kegiatan_arsip.kode_keg AS Kd_Keg,
				ta__kegiatan_arsip.kegiatan AS Ket_Kegiatan,
				CONCAT(ta__kegiatan_arsip.map_address, " ", ta__kegiatan_arsip.alamat_detail) AS Lokasi,
				ta__kegiatan_arsip.kelompok_sasaran AS Kelompok_Sasaran,
				1 AS Status_Kegiatan,
				ta__kegiatan_arsip.pagu AS Pagu_Anggaran,
				ta__kegiatan_arsip.waktu_pelaksanaan AS Waktu_Pelaksanaan
			FROM
				ta__kegiatan_arsip
			WHERE
				ta__kegiatan_arsip.tahun = ' . $this->_year . ' AND
				ta__kegiatan_arsip.kode_perubahan = ' . $this->input->post('kd_perubahan') . ' AND
				ta__kegiatan_arsip.kode_urusan LIKE ' . $kd_urusan . ' AND
				ta__kegiatan_arsip.kode_bidang LIKE ' . $kd_bidang . ' AND
				ta__kegiatan_arsip.kode_unit LIKE ' . $kd_unit . '
		')
		->result_array();
		
		$ta_kegiatan_perubahan						= $this->model
		->query
		('
			SELECT
				ta__kegiatan.tahun AS Tahun,
				ref__urusan.kd_urusan AS Kd_Urusan,
				ref__bidang.kd_bidang AS Kd_Bidang,
				ref__unit.kd_unit AS Kd_Unit,
				ref__sub.kd_sub AS Kd_Sub,
				ref__program.kd_program AS Kd_Prog,
				ta__program.kd_id_prog AS ID_Prog,
				ta__kegiatan.kd_keg AS Kd_Keg,
				ta__kegiatan.latar_belakang_perubahan AS Keterangan,
				NULL AS Keterangan_1,
				NULL AS Keterangan_31,
				NULL AS Keterangan_32
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			WHERE
				ta__kegiatan.tahun = ' . $this->_year . ' AND
				ref__urusan.kd_urusan LIKE ' . $kd_urusan . ' AND
				ref__bidang.kd_bidang LIKE ' . $kd_bidang . ' AND
				ref__unit.kd_unit LIKE ' . $kd_unit . ' AND
				ta__kegiatan.latar_belakang_perubahan != ""
		')
		->result_array();
		//print_r($ta_kegiatan_perubahan);exit;
		
		// prepare Indikator Kegiatan
		/*
		$ta_indikator								= $this->model
		->query
		('
			SELECT
				' . $this->_year . ' AS Tahun,
				ta__indikator_arsip.kd_urusan AS Kd_Urusan,
				ta__indikator_arsip.kd_bidang AS Kd_Bidang,
				ta__indikator_arsip.kd_unit AS Kd_Unit,
				ta__indikator_arsip.kd_sub AS Kd_Sub,
				ta__indikator_arsip.kd_program AS Kd_Prog,
				ta__indikator_arsip.kd_id_prog AS ID_Prog,
				ta__indikator_arsip.kd_keg AS Kd_Keg,
				ta__indikator_arsip.jns_indikator AS Kd_Indikator,
				ta__indikator_arsip.kd_indikator AS No_ID,
				ta__indikator_arsip.tolak_ukur AS Tolak_Ukur,
				ta__indikator_arsip.target AS Target_Angka,
				ta__indikator_arsip.satuan AS Target_Uraian
			FROM
				ta__indikator_arsip
			WHERE
				ta__indikator_arsip.kode_perubahan = 8 AND
				ta__indikator_arsip.kd_urusan LIKE ' . $kd_urusan . ' AND
				ta__indikator_arsip.kd_bidang LIKE ' . $kd_bidang . ' AND
				ta__indikator_arsip.kd_unit LIKE ' . $kd_unit . '
		')
		->result_array();
		*/
		// if the query above have some result(s)
		if($ta_kegiatan || $ta_kegiatan_perubahan)
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
			
			if($ta_kegiatan)
			{
				foreach($ta_kegiatan as $key => $val)
				{
					$checker						= $this->_db
					->query
					('
						SELECT
							Kd_Keg
						FROM
							Ta_Kegiatan
						WHERE
							Tahun = ' . $val['Tahun'] . ' AND
							Kd_Urusan = ' . $val['Kd_Urusan'] . ' AND
							Kd_Bidang = ' . $val['Kd_Bidang'] . ' AND
							Kd_Unit = ' . $val['Kd_Unit'] . ' AND
							Kd_Sub = ' . $val['Kd_Sub'] . ' AND
							Kd_Prog = ' . $val['Kd_Prog'] . ' AND
							ID_Prog = ' . $val['ID_Prog'] . ' AND
							Kd_Keg = ' . $val['Kd_Keg'] . '
					')
					->row();
					
					if($checker)
					{
						$do_update					= $this->_db
						->update
						(
							// table
							'Ta_Kegiatan',
							// set
							array
							(
								'Ket_Kegiatan'		=> $val['Ket_Kegiatan'],
								'Lokasi'			=> $val['Lokasi'],
								'Kelompok_Sasaran'	=> $val['Kelompok_Sasaran'],
								'Status_Kegiatan'	=> $val['Status_Kegiatan'],
								'Pagu_Anggaran'		=> $val['Pagu_Anggaran'],
								'Waktu_Pelaksanaan'	=> $val['Waktu_Pelaksanaan']
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
								'Kd_Keg'			=> $val['Kd_Keg']
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
							'Ta_Kegiatan',
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
								'Kd_Keg'			=> $val['Kd_Keg'],
								'Ket_Kegiatan'		=> $val['Ket_Kegiatan'],
								'Lokasi'			=> $val['Lokasi'],
								'Kelompok_Sasaran'	=> $val['Kelompok_Sasaran'],
								'Status_Kegiatan'	=> $val['Status_Kegiatan'],
								'Pagu_Anggaran'		=> $val['Pagu_Anggaran'],
								'Waktu_Pelaksanaan'	=> $val['Waktu_Pelaksanaan']
							)
						);
						if($do_insert)
						{
							$insert++;
						}
					}
				}
			}
			
			if($ta_kegiatan_perubahan)
			{
				foreach($ta_kegiatan_perubahan as $key => $val)
				{
					$checker						= $this->_db
					->query
					('
						SELECT
							Kd_Keg
						FROM
							Ta_Kegiatan_Perubahan
						WHERE
							Tahun = ' . $val['Tahun'] . ' AND
							Kd_Urusan = ' . $val['Kd_Urusan'] . ' AND
							Kd_Bidang = ' . $val['Kd_Bidang'] . ' AND
							Kd_Unit = ' . $val['Kd_Unit'] . ' AND
							Kd_Sub = ' . $val['Kd_Sub'] . ' AND
							Kd_Prog = ' . $val['Kd_Prog'] . ' AND
							ID_Prog = ' . $val['ID_Prog'] . ' AND
							Kd_Keg = ' . $val['Kd_Keg'] . '
					')
					->row();
					
					if($checker)
					{
						$do_update					= $this->_db
						->update
						(
							// table
							'Ta_Kegiatan_Perubahan',
							// set
							array
							(
								'Keterangan'		=> $val['Keterangan']
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
								'Kd_Keg'			=> $val['Kd_Keg']
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
							'Ta_Kegiatan_Perubahan',
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
								'Kd_Keg'			=> $val['Kd_Keg'],
								'Keterangan'		=> $val['Keterangan'],
								'Keterangan_1'		=> $val['Keterangan_1'],
								'Keterangan_31'		=> $val['Keterangan_31'],
								'Keterangan_32'		=> $val['Keterangan_32']
							)
						);
						if($do_insert)
						{
							$insert++;
						}
					}
				}
			}
			/*
			if($ta_indikator)
			{
				// delete Ta_Kegiatan if year is matched
				$this->_db
				->query
				('
					DELETE FROM Ta_Indikator
					WHERE
						Tahun = ' . $this->_year . ' AND
						Kd_Prog > 0 AND
						Kd_Urusan LIKE ' . $kd_urusan . ' AND
						Kd_Bidang LIKE ' . $kd_bidang . ' AND
						Kd_Unit LIKE ' . $kd_unit . '
				');
				
				// prepare insert Capaian Program
				if($this->_db->insert_batch('Ta_Indikator', $ta_indikator, sizeof($ta_indikator)))
				{
					// successfully insert new data...
					$update							= sizeof($ta_indikator);
				}
			}
			*/
			// returning the message
			if($update || $insert)
			{
				// nothing wrong, both query is running successfully!
				generateMessages(200, 'Sinkronisasi Kegiatan berhasil dijalankan. Sebanyak <b>' . number_format(($update + $insert)) . '</b> data berhasil dikirim ke SIMDA', current_page('../massal'));
			}
			else
			{
				// nothing happened
				generateMessages(202, 'Sinkronisasi Kegiatan gagal dijalankan...', current_page('../massal'));
			}
		}
		else
		{
			generateMessages(404, 'Query tidak mendapatkan hasil. Sinkronisasi dibatalkan...', current_page('../massal'));
		}
	}
}