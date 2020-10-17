<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Massal extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->set_method('update');
		$this->parent_module('integrasi');
		$this->_primary								= $this->input->post('kegiatan');
		$this->_year								= get_userdata('year');
		$this->_perubahan							= $this->input->post('kd_perubahan');
	}
	
	public function index()
	{
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
		$this->set_title('Integrasi Massal')
		->set_icon('fa fa-files-o')
		->set_output
		(
			array
			(
				'kode_perubahan'					=> $this->_kode_perubahan(),
				'sub_unit'							=> $this->_sub_unit(),
				'kegiatan'							=> $this->_kegiatan()
			)
		)
		/*->set_validation
		(
			array
			(
				'kode_perubahan'					=> 'required|in_list[1,2,3,4]'
			)
		)*/
		->form_callback('_validate_form')
		//->form_callback('_execute')
		->render();
	}
	
	public function _validate_form()
	{/*
		$this->form_validation->set_rules('sub_unit', 'Sub Unit', 'required|numeric');
		$this->form_validation->set_rules('program', 'Program', 'required|numeric');
		$this->form_validation->set_rules('kegiatan', 'Kegiatan', 'required|numeric');
		
		if($this->form_validation->run() === false)
		{
			return return throw_exception(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
		}
		*/
		return $this->_execute();
	}
	
	private function _execute()
	{
		//print_r($this->input->post());exit;
		if($this->input->post('sub_unit') == 'all')
		{
			$Kd_Urusan									= "'%'";
			$Kd_Bidang									= "'%'";
			$Kd_Unit									= "'%'";
			$Kd_Sub										= "'%'";
			$Kd_Prog									= "'%'";
			$ID_Prog									= "'%'";
			$Kd_Keg										= "'%'";
		}
		elseif($this->input->post('program') == 'all' )
		{
			$kode										= explode('.', $this->input->post('sub_unit'));
			$Kd_Urusan									= $kode[0];
			$Kd_Bidang									= $kode[1];
			$Kd_Unit									= $kode[2];
			$Kd_Sub										= $kode[3];
			$Kd_Prog									= "'%'";
			$ID_Prog									= "'%'";
			$Kd_Keg										= "'%'";
		}
		elseif($this->input->post('kegiatan') == 'all')
		{
			$kode										= explode('.', $this->input->post('program'));
			$Kd_Urusan									= $kode[0];
			$Kd_Bidang									= $kode[1];
			$Kd_Unit									= $kode[2];
			$Kd_Sub										= $kode[3];
			$Kd_Prog									= $kode[4];
			$ID_Prog									= $kode[5];
			$Kd_Keg										= "'%'";
		}
		elseif($this->input->post('kegiatan') != 'all')
		{
			$kode										= explode('.', $this->input->post('kegiatan'));
			$Kd_Urusan									= $kode[0];
			$Kd_Bidang									= $kode[1];
			$Kd_Unit									= $kode[2];
			$Kd_Sub										= $kode[3];
			$Kd_Prog									= $kode[4];
			$ID_Prog									= $kode[5];
			$Kd_Keg										= $kode[6];
		}
		else
		{
			return throw_exception(301, 'Sepertinya Salah Pilih.', current_page('massal'));
		}
		
		$this->permission->must_ajax();
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$error										= false;
		$run										= null;
		$prepare									= array();
		$where										= array();
		
		// get active connection (by "year" session)
		$connection									= $this->model->get_where('ref__koneksi', array('tahun' => $this->_year), 1)->row();
		
		// check the result
		if(!$connection)
		{
			// no result! throw error...
			return throw_exception(500, phrase('connection_is_not_found'), current_page('../massal'));
		}
		
		// prepare the connection parameter
		$database_driver							= $connection->database_driver;
		$hostname									= $this->encryption->decrypt($connection->hostname);
		$port										= $this->encryption->decrypt($connection->port);
		$username									= $this->encryption->decrypt($connection->username);
		$password									= $this->encryption->decrypt($connection->password);
		$database_name								= $this->encryption->decrypt($connection->database_name);
		
		// getting a new connection
		$this->_db									= $this->model->new_connection($database_driver, $hostname, $port, $username, $password, $database_name);
		
		$ta__indikator								= $this->model->query
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
				ta__indikator_arsip.kode_perubahan = ' . $this->_perubahan . ' AND
				ta__indikator_arsip.kd_urusan LIKE ' . $Kd_Urusan . ' AND
				ta__indikator_arsip.kd_bidang LIKE ' . $Kd_Bidang . ' AND
				ta__indikator_arsip.kd_unit LIKE ' . $Kd_Unit . ' AND
				ta__indikator_arsip.kd_sub LIKE ' . $Kd_Sub . ' AND
				ta__indikator_arsip.kd_program LIKE ' . $Kd_Prog . ' AND
				ta__indikator_arsip.kd_id_prog LIKE ' . $ID_Prog . ' AND
				ta__indikator_arsip.kd_keg LIKE ' . $Kd_Keg . '
			ORDER BY
				ta__indikator_arsip.kd_urusan ASC,
				ta__indikator_arsip.kd_bidang ASC,
				ta__indikator_arsip.kd_unit ASC,
				ta__indikator_arsip.kd_sub ASC,
				ta__indikator_arsip.kd_program ASC,
				ta__indikator_arsip.kd_id_prog ASC,
				ta__indikator_arsip.kd_keg ASC,
				ta__indikator_arsip.jns_indikator ASC,
				ta__indikator_arsip.kd_indikator ASC
		')
		->result_array();
		//echo $this->model->last_query();exit;
		//print_r($ta__indikator);exit;
		$ta__belanja									= $this->model->query
		('
			SELECT
				' . $this->_year . ' AS Tahun,
				ta__belanja_arsip.kd_urusan AS Kd_Urusan,
				ta__belanja_arsip.kd_bidang AS Kd_Bidang,
				ta__belanja_arsip.kd_unit AS Kd_Unit,
				ta__belanja_arsip.kd_sub AS Kd_Sub,
				ta__belanja_arsip.kd_program AS Kd_Prog,
				ta__belanja_arsip.kd_id_prog AS ID_Prog,
				ta__belanja_arsip.kd_keg AS Kd_Keg,
				ta__belanja_arsip.kd_rek_1 AS Kd_Rek_1,
				ta__belanja_arsip.kd_rek_2 AS Kd_Rek_2,
				ta__belanja_arsip.kd_rek_3 AS Kd_Rek_3,
				ta__belanja_arsip.kd_rek_4 AS Kd_Rek_4,
				ta__belanja_arsip.kd_rek_5 AS Kd_Rek_5,
				null AS Kd_Ap_Pub,
				ref__sumber_dana.kode AS Kd_Sumber
			FROM
				ta__belanja_arsip
			LEFT JOIN ref__sumber_dana ON ref__sumber_dana.id = ta__belanja_arsip.id_sumber_dana
			WHERE
				ta__belanja_arsip.kode_perubahan = ' . $this->_perubahan . ' AND
				ta__belanja_arsip.kd_urusan LIKE ' . $Kd_Urusan . ' AND
				ta__belanja_arsip.kd_bidang LIKE ' . $Kd_Bidang . ' AND
				ta__belanja_arsip.kd_unit LIKE ' . $Kd_Unit . ' AND
				ta__belanja_arsip.kd_sub LIKE ' . $Kd_Sub . ' AND
				ta__belanja_arsip.kd_program LIKE ' . $Kd_Prog . ' AND
				ta__belanja_arsip.kd_id_prog LIKE ' . $ID_Prog . ' AND
				ta__belanja_arsip.kd_keg LIKE ' . $Kd_Keg . '
			GROUP BY
				ta__belanja_arsip.kd_urusan,
				ta__belanja_arsip.kd_bidang,
				ta__belanja_arsip.kd_unit,
				ta__belanja_arsip.kd_sub,
				ta__belanja_arsip.kd_program,
				ta__belanja_arsip.kd_id_prog,
				ta__belanja_arsip.kd_keg,
				ta__belanja_arsip.kd_rek_1,
				ta__belanja_arsip.kd_rek_2,
				ta__belanja_arsip.kd_rek_3,
				ta__belanja_arsip.kd_rek_4,
				ta__belanja_arsip.kd_rek_5
			ORDER BY
				ta__belanja_arsip.kd_rek_1 ASC,
				ta__belanja_arsip.kd_rek_2 ASC,
				ta__belanja_arsip.kd_rek_3 ASC,
				ta__belanja_arsip.kd_rek_4 ASC,
				ta__belanja_arsip.kd_rek_5 ASC
		')
		->result_array();
		//echo $this->model->last_query();exit;
		//print_r($ta__belanja);exit;
		$ta__belanja_rinc							= $this->model->query
		('
			SELECT
				' . $this->_year . ' AS Tahun,
				ta__belanja_arsip.kd_urusan AS Kd_Urusan,
				ta__belanja_arsip.kd_bidang AS Kd_Bidang,
				ta__belanja_arsip.kd_unit AS Kd_Unit,
				ta__belanja_arsip.kd_sub AS Kd_Sub,
				ta__belanja_arsip.kd_program AS Kd_Prog,
				ta__belanja_arsip.kd_id_prog AS ID_Prog,
				ta__belanja_arsip.kd_keg AS Kd_Keg,
				ta__belanja_arsip.kd_rek_1 AS Kd_Rek_1,
				ta__belanja_arsip.kd_rek_2 AS Kd_Rek_2,
				ta__belanja_arsip.kd_rek_3 AS Kd_Rek_3,
				ta__belanja_arsip.kd_rek_4 AS Kd_Rek_4,
				ta__belanja_arsip.kd_rek_5 AS Kd_Rek_5,
				ta__belanja_arsip.kd_belanja_sub AS No_Rinc,
				ta__belanja_arsip.uraian_belanja_sub AS Keterangan,
				null AS Kd_Sumber
			FROM
				ta__belanja_arsip
			WHERE
				ta__belanja_arsip.kode_perubahan = ' . $this->_perubahan . ' AND
				ta__belanja_arsip.kd_urusan LIKE ' . $Kd_Urusan . ' AND
				ta__belanja_arsip.kd_bidang LIKE ' . $Kd_Bidang . ' AND
				ta__belanja_arsip.kd_unit LIKE ' . $Kd_Unit . ' AND
				ta__belanja_arsip.kd_sub LIKE ' . $Kd_Sub . ' AND
				ta__belanja_arsip.kd_program LIKE ' . $Kd_Prog . ' AND
				ta__belanja_arsip.kd_id_prog LIKE ' . $ID_Prog . ' AND
				ta__belanja_arsip.kd_keg LIKE ' . $Kd_Keg . '
			GROUP BY
				ta__belanja_arsip.kd_urusan,
				ta__belanja_arsip.kd_bidang,
				ta__belanja_arsip.kd_unit,
				ta__belanja_arsip.kd_sub,
				ta__belanja_arsip.kd_program,
				ta__belanja_arsip.kd_id_prog,
				ta__belanja_arsip.kd_keg,
				ta__belanja_arsip.kd_rek_1,
				ta__belanja_arsip.kd_rek_2,
				ta__belanja_arsip.kd_rek_3,
				ta__belanja_arsip.kd_rek_4,
				ta__belanja_arsip.kd_rek_5,
				ta__belanja_arsip.kd_belanja_sub
			ORDER BY
				ta__belanja_arsip.kd_urusan ASC,
				ta__belanja_arsip.kd_bidang ASC,
				ta__belanja_arsip.kd_unit ASC,
				ta__belanja_arsip.kd_sub ASC,
				ta__belanja_arsip.kd_program ASC,
				ta__belanja_arsip.kd_id_prog ASC,
				ta__belanja_arsip.kd_keg ASC,
				ta__belanja_arsip.kd_rek_1 ASC,
				ta__belanja_arsip.kd_rek_2 ASC,
				ta__belanja_arsip.kd_rek_3 ASC,
				ta__belanja_arsip.kd_rek_4 ASC,
				ta__belanja_arsip.kd_rek_5 ASC,
				ta__belanja_arsip.kd_belanja_sub ASC
		')
		->result_array();
		//print_r($ta__belanja_rinc);exit;
		$ta__belanja_rinc_sub						= $this->model->query
		('
			SELECT
				' . $this->_year . ' AS Tahun,
				ta__belanja_arsip.kd_urusan AS Kd_Urusan,
				ta__belanja_arsip.kd_bidang AS Kd_Bidang,
				ta__belanja_arsip.kd_unit AS Kd_Unit,
				ta__belanja_arsip.kd_sub AS Kd_Sub,
				ta__belanja_arsip.kd_program AS Kd_Prog,
				ta__belanja_arsip.kd_id_prog AS ID_Prog,
				ta__belanja_arsip.kd_keg AS Kd_Keg,
				ta__belanja_arsip.kd_rek_1 AS Kd_Rek_1,
				ta__belanja_arsip.kd_rek_2 AS Kd_Rek_2,
				ta__belanja_arsip.kd_rek_3 AS Kd_Rek_3,
				ta__belanja_arsip.kd_rek_4 AS Kd_Rek_4,
				ta__belanja_arsip.kd_rek_5 AS Kd_Rek_5,
				ta__belanja_arsip.kd_belanja_sub AS No_Rinc,
				ta__belanja_arsip.kd_belanja_rinc AS No_ID,
				ta__belanja_arsip.satuan_1 AS Sat_1,
				ta__belanja_arsip.vol_1 AS Nilai_1,
				ta__belanja_arsip.satuan_2 AS Sat_2,
				ta__belanja_arsip.vol_2 AS Nilai_2,
				ta__belanja_arsip.satuan_3 AS Sat_3,
				ta__belanja_arsip.vol_3 AS Nilai_3,
				ta__belanja_arsip.satuan_123 AS Satuan123,
				ta__belanja_arsip.vol_123 AS Jml_Satuan,
				ta__belanja_arsip.nilai AS Nilai_Rp,
				ta__belanja_arsip.total AS Total,
				CONCAT(
					ta__belanja_arsip.uraian_belanja_rinc, " (",
					IF(ta__belanja_arsip.vol_1 > 0, CONCAT(ta__belanja_arsip.vol_1, " ",
					ta__belanja_arsip.satuan_1), ""),
					IF(ta__belanja_arsip.vol_2 > 0, CONCAT(" x ", ta__belanja_arsip.vol_2, " ",
					ta__belanja_arsip.satuan_2), ""),
					IF(ta__belanja_arsip.vol_3 > 0, CONCAT(" x ", ta__belanja_arsip.vol_3, " ",
					ta__belanja_arsip.satuan_3), "")
					, ")"
				) AS Keterangan
			FROM
				ta__belanja_arsip
			WHERE
				ta__belanja_arsip.kode_perubahan = ' . $this->_perubahan . ' AND
				ta__belanja_arsip.kd_urusan LIKE ' . $Kd_Urusan . ' AND
				ta__belanja_arsip.kd_bidang LIKE ' . $Kd_Bidang . ' AND
				ta__belanja_arsip.kd_unit LIKE ' . $Kd_Unit . ' AND
				ta__belanja_arsip.kd_sub LIKE ' . $Kd_Sub . ' AND
				ta__belanja_arsip.kd_program LIKE ' . $Kd_Prog . ' AND
				ta__belanja_arsip.kd_id_prog LIKE ' . $ID_Prog . ' AND
				ta__belanja_arsip.kd_keg LIKE ' . $Kd_Keg . '
			GROUP BY
				ta__belanja_arsip.kd_urusan,
				ta__belanja_arsip.kd_bidang,
				ta__belanja_arsip.kd_unit,
				ta__belanja_arsip.kd_sub,
				ta__belanja_arsip.kd_program,
				ta__belanja_arsip.kd_id_prog,
				ta__belanja_arsip.kd_keg,
				ta__belanja_arsip.kd_rek_1,
				ta__belanja_arsip.kd_rek_2,
				ta__belanja_arsip.kd_rek_3,
				ta__belanja_arsip.kd_rek_4,
				ta__belanja_arsip.kd_rek_5,
				ta__belanja_arsip.kd_belanja_sub,
				ta__belanja_arsip.kd_belanja_rinc
			ORDER BY
				ta__belanja_arsip.kd_rek_1 ASC,
				ta__belanja_arsip.kd_rek_2 ASC,
				ta__belanja_arsip.kd_rek_3 ASC,
				ta__belanja_arsip.kd_rek_4 ASC,
				ta__belanja_arsip.kd_rek_5 ASC,
				ta__belanja_arsip.kd_belanja_sub ASC,
				ta__belanja_arsip.kd_belanja_rinc ASC
		')
		->result_array();
		//echo $this->model->last_query();exit;
		//print_r($ta__belanja_rinc_sub);exit;
		$ta__rencana								= $this->model->query
		('
			SELECT
				' . $this->_year . ' AS Tahun,
				ta__rencana_arsip.kode_urusan AS Kd_Urusan,
				ta__rencana_arsip.kode_bidang AS Kd_Bidang,
				ta__rencana_arsip.kode_unit AS Kd_Unit,
				ta__rencana_arsip.kode_sub AS Kd_Sub,
				ta__rencana_arsip.kode_prog AS Kd_Prog,
				ta__rencana_arsip.kode_id_prog AS ID_Prog,
				ta__rencana_arsip.kode_keg AS Kd_Keg,
				ta__rencana_arsip.kd_rek_1 AS Kd_Rek_1,
				ta__rencana_arsip.kd_rek_2 AS Kd_Rek_2,
				ta__rencana_arsip.kd_rek_3 AS Kd_Rek_3,
				ta__rencana_arsip.kd_rek_4 AS Kd_Rek_4,
				ta__rencana_arsip.kd_rek_5 AS Kd_Rek_5,
				ta__rencana_arsip.jan AS Jan,
				ta__rencana_arsip.feb AS Feb,
				ta__rencana_arsip.mar AS Mar,
				ta__rencana_arsip.apr AS Apr,
				ta__rencana_arsip.mei AS Mei,
				ta__rencana_arsip.jun AS Jun,
				ta__rencana_arsip.jul AS Jul,
				ta__rencana_arsip.agt AS Agt,
				ta__rencana_arsip.sep AS Sep,
				ta__rencana_arsip.okt AS Okt,
				ta__rencana_arsip.nop AS Nop,
				ta__rencana_arsip.des AS Des
			FROM
				ta__rencana_arsip
			WHERE
				ta__rencana_arsip.kode_perubahan = ' . $this->_perubahan . ' AND
				ta__rencana_arsip.kode_urusan LIKE ' . $Kd_Urusan . ' AND
				ta__rencana_arsip.kode_bidang LIKE ' . $Kd_Bidang . ' AND
				ta__rencana_arsip.kode_unit LIKE ' . $Kd_Unit . ' AND
				ta__rencana_arsip.kode_sub LIKE ' . $Kd_Sub . ' AND
				ta__rencana_arsip.kode_prog LIKE ' . $Kd_Prog . ' AND
				ta__rencana_arsip.kode_id_prog LIKE ' . $ID_Prog . ' AND
				ta__rencana_arsip.kode_keg LIKE ' . $Kd_Keg . '
			GROUP BY
				ta__rencana_arsip.kode_urusan,
				ta__rencana_arsip.kode_bidang,
				ta__rencana_arsip.kode_unit,
				ta__rencana_arsip.kode_sub,
				ta__rencana_arsip.kode_prog,
				ta__rencana_arsip.kode_id_prog,
				ta__rencana_arsip.kode_keg,
				ta__rencana_arsip.kd_rek_1,
				ta__rencana_arsip.kd_rek_2,
				ta__rencana_arsip.kd_rek_3,
				ta__rencana_arsip.kd_rek_4,
				ta__rencana_arsip.kd_rek_5
			ORDER BY
				ta__rencana_arsip.kd_rek_1 ASC,
				ta__rencana_arsip.kd_rek_2 ASC,
				ta__rencana_arsip.kd_rek_3 ASC,
				ta__rencana_arsip.kd_rek_4 ASC,
				ta__rencana_arsip.kd_rek_5 ASC
		')
		->result_array();
		//print_r($ta__rencana);exit;
		// if the query above have some result(s)
		if($ta__indikator || $ta__belanja || $ta__belanja_rinc || $ta__belanja_rinc_sub|| $ta__rencana )
		{
			// get active connection (by "year" session)
			$connection								= $this->model->get_where('ref__koneksi', array('tahun' => $this->_year), 1)->row();
			
			// check the result
			if(!$connection)
			{
				// no result! throw error...
				return throw_exception(500, phrase('connection_is_not_found'), current_page('../massal'));
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
			
			//$update									= 0;
			$insert									= 0;
			
			if($ta__indikator)
			{
				$this->_db
				->query
				('
					DELETE FROM Ta_Indikator
					WHERE
						Tahun = ' . $this->_year . ' AND
						Kd_Prog > 0 AND
						Kd_Urusan LIKE ' . $Kd_Urusan . ' AND
						Kd_Bidang LIKE ' . $Kd_Bidang . ' AND
						Kd_Unit LIKE ' . $Kd_Unit . ' AND
						Kd_Sub LIKE ' . $Kd_Sub . ' AND
						Kd_Prog LIKE ' . $Kd_Prog . ' AND
						ID_Prog LIKE ' . $ID_Prog . ' AND
						Kd_Keg LIKE ' . $Kd_Keg . '
				');
				
				if($this->_db->insert_batch('Ta_Indikator', $ta__indikator, sizeof($ta__indikator)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__indikator);
				}
			}
			
			if($ta__belanja)
			{
				// delete if belanja is matched selected
				$this->_db
				->query
				('
					DELETE FROM Ta_Belanja
					WHERE
						Tahun = ' . $this->_year . ' AND
						Kd_Prog > 0 AND
						Kd_Urusan LIKE ' . $Kd_Urusan . ' AND
						Kd_Bidang LIKE ' . $Kd_Bidang . ' AND
						Kd_Unit LIKE ' . $Kd_Unit . ' AND
						Kd_Sub LIKE ' . $Kd_Sub . ' AND
						Kd_Prog LIKE ' . $Kd_Prog . ' AND
						ID_Prog LIKE ' . $ID_Prog . ' AND
						Kd_Keg LIKE ' . $Kd_Keg . '
				');
				// prepare insert
				if($this->_db->insert_batch('Ta_Belanja', $ta__belanja, sizeof($ta__belanja)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__belanja);
				}
			}
			
			if($ta__belanja_rinc)
			{
				// prepare insert
				if($this->_db->insert_batch('Ta_Belanja_Rinc', $ta__belanja_rinc, sizeof($ta__belanja_rinc)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__belanja_rinc);
				}
			}
			
			if($ta__belanja_rinc_sub)
			{
					//print_r($ta__belanja_rinc_sub);exit;
				if($this->_db->insert_batch('Ta_Belanja_Rinc_Sub', $ta__belanja_rinc_sub, sizeof($ta__belanja_rinc_sub)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__belanja_rinc_sub);
				}
			}
			
			if($ta__rencana)
			{
				// delete if rencana is matched selected
				$this->_db
				->query
				('
					DELETE FROM Ta_Rencana
					WHERE
						Tahun = ' . $this->_year . ' AND
						Kd_Prog > 0 AND
						Kd_Urusan LIKE ' . $Kd_Urusan . ' AND
						Kd_Bidang LIKE ' . $Kd_Bidang . ' AND
						Kd_Unit LIKE ' . $Kd_Unit . ' AND
						Kd_Sub LIKE ' . $Kd_Sub . ' AND
						Kd_Prog LIKE ' . $Kd_Prog . ' AND
						ID_Prog LIKE ' . $ID_Prog . ' AND
						Kd_Keg LIKE ' . $Kd_Keg . '
				');
				
				if($this->_db->insert_batch('Ta_Rencana', $ta__rencana, sizeof($ta__rencana)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__rencana);
				}
			}
			
			// returning the message
			if($insert)
			{
				// nothing wrong, both query is running successfully!
				return throw_exception(200, 'Sinkronisasi Rencana dan Belanja berhasil dijalankan. Sebanyak <b>' . number_format($insert) . '</b> data berhasil dikirim ke SIMDA', current_page('../massal'));
			}
			else
			{
				// nothing happened
				return throw_exception(202, 'Sinkronisasi Kegiatan gagal dijalankan...', current_page('../massal'));
			}
		}
		else
		{
			return throw_exception(404, 'Query tidak mendapatkan hasil. Sinkronisasi dibatalkan...', current_page('../massal'));
		}
	}
	
	private function _kode_perubahan()
	{
		$query										= $this->model
		->select
		('
			kode AS kode_perubahan,
			nama_jenis_anggaran
		')
		->order_by('kode ASC')
		->get_where
		(
			'ref__renja_jenis_anggaran'
		)
		->result();
		
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->kode_perubahan . '">' . $val->kode_perubahan . ' - ' . $val->nama_jenis_anggaran . '</option>';
			}
		}
		
		return '
			<div class="form-group">
				<label class="d-block text-muted text-uppercase">
					Perubahan
				</label>
				<select name="kd_perubahan" class="form-control form-control-sm" placeholder="Silakan pilih kode perubahan">
					' . $output . '
				</select>
			</div>
		';
	}
	
	private function _sub_unit()
	{
		if(!in_array(get_userdata('group_id'), array(1))) return false;
		
		$query										= $this->model
		->select
		('
			ref__unit.kd_unit,
			ref__unit.nm_unit,
			ref__bidang.kd_bidang,
			ref__urusan.kd_urusan
		')
		->join
		(
			'ref__bidang',
			'ref__bidang.id = ref__unit.id_bidang'
		)
		->join
		(
			'ref__urusan',
			'ref__urusan.id = ref__bidang.id_urusan'
		)
		->get_where
		(
			'ref__unit'
		)
		->result();
		
		$output										= '<option value="all">Semua Unit</option>';
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '">' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . ' - ' . $val->nm_unit . '</option>';
			}
		}
		
		return '
			<div class="form-group">
				<label class="d-block text-muted text-uppercase">
					Unit
				</label>
				<select name="unit" class="form-control form-control-sm" placeholder="Silakan pilih unit">
					' . $output . '
				</select>
			</div>
		';
	}
	
	private function _kegiatan()
	{
		$output										= null;
		if(1 == get_userdata('group_id'))
		{
			$query									= $this->model
			->select
			('
				ref__sub.id,
				ref__sub.kd_sub,
				ref__sub.nm_sub,
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit
			')
			->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
			->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			->get_where('ref__sub', array('ref__sub.id !=' => NULL))
			->result_array();
			if($query)
			{
				$options							= null;
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . $val['kd_unit'] . '.' . $val['kd_sub'] . '">' . $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . $val['kd_unit'] . '.' . $val['kd_sub'] . ' ' . $val['nm_sub'] . '</option>';
				}
				$output								.= '
					<div class="form-group">
						<label class="d-block">
							SUB UNIT
						</label>
						<select name="sub_unit" class="form-control form-control-sm report-dropdown" to-change=".program">
							<option value="all">Silakan pilih Sub Unit</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="d-block">
							PROGRAM
						</label>
						<select name="program" class="form-control form-control-sm report-dropdown program" to-change=".kegiatan" data-kode-perubahan="1" disabled>
							<option value="">Silakan pilih Sub Unit terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="d-block">
							KEGIATAN
						</label>
						<select name="kegiatan" class="form-control form-control-sm kegiatan" disabled>
							<option value="">Silakan pilih Program terlebih dahulu</option>
						</select>
					</div>
				';
			}
		}
		else
		{
			return false;
		}
		return $output;
	}
	
	private function _dropdown()
	{
		$kode_sub									= explode('.', $this->input->post('primary'));
		$kd_urusan									= $kode_sub[0];
		$kd_bidang									= $kode_sub[1];
		$kd_unit									= $kode_sub[2];
		$kd_sub										= $kode_sub[3];
		$element									= $this->input->post('element');
		$options									= null;
		if('.program' == $element)
		{
			$query									= $this->model
			->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__program.kd_id_prog,
				ref__program.nm_program
			')
			->join('ref__program', 'ta__program.id_prog = ref__program.id')
			->join('ref__sub', 'ta__program.id_sub = ref__sub.id')
			->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
			->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			->order_by('ref__program.kd_program')
			->get_where('ta__program', array
									(
										'ref__urusan.kd_urusan' 	=> $kd_urusan,
										'ref__bidang.kd_bidang' 	=> $kd_bidang,
										'ref__unit.kd_unit' 		=> $kd_unit,
										'ref__sub.kd_sub' 			=> $kd_sub
									)
						)
			->result_array();
			if($query)
			{
				$options							= '<option value="all">Silakan pilih Program</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . $val['kd_unit'] . '.' . $val['kd_sub'] . '.' . $val['kd_program'] . '.' . $val['kd_id_prog'] . '">' . $val['kd_program'] . '. ' . $val['nm_program'] . ' (' . $val['kd_id_prog'] . ')</option>';
				}
			}
		}
		elseif('.kegiatan' == $element)
		{
			$kode_prog									= explode('.', $this->input->post('primary'));
			$kd_urusan									= $kode_prog[0];
			$kd_bidang									= $kode_prog[1];
			$kd_unit									= $kode_prog[2];
			$kd_sub										= $kode_prog[3];
			$kd_prog									= $kode_prog[4];
			$ID_prog									= $kode_prog[5];
			$kd_perubahan								= $kode_prog[6];
			//print_r($kode_prog);exit;
			$query									= $this->model
			->select
			('
				ta__kegiatan_arsip.id_keg,
				ta__kegiatan_arsip.kode_urusan,
				ta__kegiatan_arsip.kode_bidang,
				ta__kegiatan_arsip.kode_unit,
				ta__kegiatan_arsip.kode_sub,
				ta__kegiatan_arsip.kode_prog,
				ta__kegiatan_arsip.kode_id_prog,
				ta__kegiatan_arsip.kode_keg,
				ta__kegiatan_arsip.kegiatan
			')
			->order_by('kode_keg')
			->get_where('ta__kegiatan_arsip', array
											(
												'ta__kegiatan_arsip.kode_urusan' 		=> $kd_urusan,
												'ta__kegiatan_arsip.kode_bidang' 		=> $kd_bidang,
												'ta__kegiatan_arsip.kode_unit' 			=> $kd_unit,
												'ta__kegiatan_arsip.kode_sub' 			=> $kd_sub,
												'ta__kegiatan_arsip.kode_prog' 			=> $kd_prog,
												'ta__kegiatan_arsip.kode_id_prog'		=> $ID_prog,
												'kode_perubahan' 						=> $kd_perubahan
											)
						)
			->result_array();
			//print_r($query);exit;
			if($query)
			{
				$options							= '<option value="all">Silakan pilih Kegiatan</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['kode_urusan'] . '.' . $val['kode_bidang'] . '.' . $val['kode_unit'] . '.' . $val['kode_sub'] . '.' . $val['kode_prog'] . '.' . $val['kode_id_prog'] . '.' . $val['kode_keg'] . '">' . $val['kode_keg'] . '. ' . $val['kegiatan'] . '</option>';
				}
			}
		}
		make_json
		(
			array
			(
				'results'							=> $options,
				'element'							=> $element,
				'html'								=> ($options ? $options : '<option value="">Data yang dipilih tidak mendapatkan hasil</options>')
			)
		);
	}
}