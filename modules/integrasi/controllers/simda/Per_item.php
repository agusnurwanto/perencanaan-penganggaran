<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Per_item extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_id_sub								= $this->input->get('id_sub');
		$this->_primary								= $this->input->get('id_keg');
		$this->_year								= get_userdata('year');
		$this->_perubahan							= $this->input->get('kd_perubahan');
		if('kirim-ke-simda' == $this->input->get('method'))
		{
			if($this->_primary)
			{
				return $this->_execute();
			}
			else
			{
				return throw_exception(404, 'Data yang anda kirim tidak tersedia...', 'renja/integrasi');
			}
		}
		elseif(!$this->_id_sub)
		{
			return throw_exception(301, 'Silakan memilih sub unit terlebih dahulu...', 'renja/integrasi');
		}
		$this->set_permission();
		$this->set_theme('backend');
		$this->unset_action('create, read, update, delete');
	}
	
	public function index()
	{
		$this->add_filter($this->_filter());
		
		$this->set_title('Kirim ke simda')
		->set_icon('fa fa-file-o')
		/*->set_relation
		(
			'id_prog',
			'ta__program.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__unit.kd_unit}.{ref__sub.kd_sub}',
			null,
			array
			(
				array
				(
					'ref__program',
					'ref__program.id = ta__program.id_prog'
				),
				array
				(
					'ref__sub',
					'ref__sub.id = ta__program.id_sub'
				),
				array
				(
					'ref__unit',
					'ref__unit.id = ref__sub.id_unit'
				),
				array
				(
					'ref__bidang',
					'ref__bidang.id = ref__unit.id_bidang'
				),
				array
				(
					'ref__urusan',
					'ref__urusan.id = ref__bidang.id_urusan'
				)
			)
		)
		->set_field
		(
			'pengusul',
			'dropdown',
			array
			(
				0									=> '<label class="label bg-red">Musrenbang</label>',
				1									=> '<label class="label bg-green">SKPD</label>',
				2									=> '<label class="label bg-maroon">DPRD</label>',
				3									=> '<label class="label bg-navy">Fraksi</label>'
			)
		)*/
		->set_field
		(
			array
			(
				'pagu'								=> 'number_format'
			)
		)
		->unset_truncate('kegiatan')
		->add_action('option', null, 'Kirim ke SIMDA', 'btn-success ajax', 'fa fa-exchange', array('id_keg' => 'id_keg', 'method' => 'kirim-ke-simda'))
		->unset_column('id, id_urusan, id_bidang, id_unit, id_sub, id_prog, id_program, id_keg, id_sumber_dana, kode_id_prog, kode_perubahan, jenis_kegiatan_renja, input_kegiatan, map_coordinates, map_address, alamat_detail, kelurahan, kecamatan, images, kelompok_sasaran, waktu_pelaksanaan, capaian_program, survey, pagu_1, tahun, created, updated, jenis_usulan')
		->merge_content('{kode_urusan}.{kode_bidang}.{kode_unit}.{kode_sub}.{kode_prog}.{kode_keg}', 'Kode')
		->column_order('kode_urusan, kegiatan, pagu')
		//->set_alias('pagu_1', 'Pagu N+1')
		->where
		(
			array
			(
				'id_sub'							=> $this->_id_sub,
				'kode_perubahan'					=> $this->_perubahan
			)
		)
		->order_by
		(
			array
			(
				'kode_urusan'						=> 'ASC',
				'kode_bidang'						=> 'ASC',
				'kode_unit'							=> 'ASC',
				'kode_sub'							=> 'ASC',
				'kode_prog'							=> 'ASC',
				'kode_keg'							=> 'ASC'
			)
		)
		->render('ta__kegiatan_arsip');
	}
	
	private function _execute()
	{
		$this->permission->must_ajax();
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '20000M');
		
		$kode										= $this->model
		->select
		('
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ref__program.kd_program,
			ta__program.kd_id_prog,
			ta__kegiatan.kd_keg
		')
		->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog')
		->join('ref__program', 'ref__program.id = ta__program.id_prog')
		->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'					=> $this->input->get('id_keg')
			),
			1
		)
		->row();
		
		if($kode)
		{
			$Kd_Urusan								= $kode->kd_urusan;
			$Kd_Bidang								= $kode->kd_bidang;
			$Kd_Unit								= $kode->kd_unit;
			$Kd_Sub									= $kode->kd_sub;
			$Kd_Prog								= $kode->kd_program;
			$ID_Prog								= $kode->kd_id_prog;
			$Kd_Keg									= $kode->kd_keg;
		}
		else
		{
			$Kd_Urusan								= "'%'";
			$Kd_Bidang								= "'%'";
			$Kd_Unit								= "'%'";
			$Kd_Sub									= "'%'";
			$Kd_Prog								= "'%'";
			$ID_Prog								= "'%'";
			$Kd_Keg									= "'%'";
		}
		
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
				ta__indikator_arsip.id_keg = ' . $this->_primary . ' AND
				ta__indikator_arsip.kode_perubahan = ' . $this->_perubahan . '
			ORDER BY
				ta__indikator_arsip.kd_urusan ASC,
				ta__indikator_arsip.kd_bidang ASC,
				ta__indikator_arsip.kd_unit ASC,
				ta__indikator_arsip.kd_sub ASC,
				ta__indikator_arsip.kd_program ASC,
				ta__indikator_arsip.kd_id_prog ASC,
				ta__indikator_arsip.kd_keg ASC,
				ta__indikator_arsip.kd_indikator ASC
		')
		->result();
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
				ta__belanja_arsip.kd_rek_3 AS KD_Rek_3,
				ta__belanja_arsip.kd_rek_4 AS Kd_Rek_4,
				ta__belanja_arsip.kd_rek_5 AS Kd_Rek_5,
				null AS Kd_Ap_Pub,
				ref__sumber_dana.kode AS Kd_Sumber
			FROM
				ta__belanja_arsip
			INNER JOIN ref__sumber_dana ON ref__sumber_dana.id = ta__belanja_arsip.id_sumber_dana
			WHERE
				ta__belanja_arsip.id_keg = ' . $this->_primary . ' AND
				ta__belanja_arsip.kode_perubahan = ' . $this->_perubahan . '
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
		->result();
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
				ta__belanja_arsip.kd_rek_3 AS KD_Rek_3,
				ta__belanja_arsip.kd_rek_4 AS Kd_Rek_4,
				ta__belanja_arsip.kd_rek_5 AS Kd_Rek_5,
				ta__belanja_arsip.kd_belanja_sub AS No_Rinc,
				ta__belanja_arsip.uraian_belanja_sub AS Keterangan,
				null AS Kd_Sumber
			FROM
				ta__belanja_arsip
			WHERE
				ta__belanja_arsip.id_keg = ' . $this->_primary . ' AND
				ta__belanja_arsip.kode_perubahan = ' . $this->_perubahan . '
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
				ta__belanja_arsip.kd_rek_1 ASC,
				ta__belanja_arsip.kd_rek_2 ASC,
				ta__belanja_arsip.kd_rek_3 ASC,
				ta__belanja_arsip.kd_rek_4 ASC,
				ta__belanja_arsip.kd_rek_5 ASC,
				ta__belanja_arsip.kd_belanja_sub ASC
		')
		->result();
		//print_r($ta__belanja_rinc);exit;
		$ta__belanja_rinc_sub						= $this->model->query
		('
			SELECT
				ta__belanja_arsip.tahun AS Tahun,
				ta__belanja_arsip.kd_urusan AS Kd_Urusan,
				ta__belanja_arsip.kd_bidang AS Kd_Bidang,
				ta__belanja_arsip.kd_unit AS Kd_Unit,
				ta__belanja_arsip.kd_sub AS Kd_Sub,
				ta__belanja_arsip.kd_program AS Kd_Prog,
				ta__belanja_arsip.kd_id_prog AS ID_Prog,
				ta__belanja_arsip.kd_keg AS Kd_Keg,
				ta__belanja_arsip.kd_rek_1 AS Kd_Rek_1,
				ta__belanja_arsip.kd_rek_2 AS Kd_Rek_2,
				ta__belanja_arsip.kd_rek_3 AS KD_Rek_3,
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
				ta__belanja_arsip.id_keg = ' . $this->_primary . ' AND
				ta__belanja_arsip.kode_perubahan = ' . $this->_perubahan . '
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
		->result();
		print_r($ta__belanja_rinc_sub);exit;
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
				ta__rencana_arsip.kd_rek_3 AS KD_Rek_3,
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
				ta__rencana_arsip.id_keg = ' . $this->_primary . ' AND
				ta__rencana_arsip.kode_perubahan = ' . $this->_perubahan . '
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
		->result();
		//print_r($ta__belanja);exit;
		
		
		// if the query above have some result(s)
		if($ta__indikator || $ta__belanja || $ta__rencana)
		{
			// get active connection (by "year" session)
			$connection								= $this->model->get_where('ref__koneksi', array('tahun' => $this->_year), 1)->row();
			
			// check the result
			if(!$connection)
			{
				// no result! throw error...
				return throw_exception(500, phrase('connection_is_not_found'), current_page('../Per_item'));
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
			
			$insert									= 0;
			
			if($ta__indikator)
			{
				// delete if rencana is matched selected
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
				//echo $this->_db->last_query();exit;
				// prepare insert
				if($this->_db->insert_batch('Ta_Indikator', $ta__indikator, sizeof($ta__indikator)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__indikator);
				}
			}
			
			// query ta__belanja have result(s)
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
			
			// query ta__belanja_rinc have result(s)
			if($ta__belanja_rinc)
			{
				// prepare insert
				if($this->_db->insert_batch('Ta_Belanja_Rinc', $ta__belanja_rinc, sizeof($ta__belanja_rinc)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__belanja_rinc);
				}
			}
			
			// query ta__belanja_rinc_sub have result(s)
			if($ta__belanja_rinc_sub)
			{
				// prepare insert
				if($this->_db->insert_batch('Ta_Belanja_Rinc_Sub', $ta__belanja_rinc_sub, sizeof($ta__belanja_rinc_sub)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__belanja_rinc_sub);
				}
			}
			
			// query ta__rencana have result(s)
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
				
				// prepare insert
				if($this->_db->insert_batch('Ta_Rencana', $ta__rencana, sizeof($ta__rencana)))
				{
					// successfully insert new data...
					$insert							+= sizeof($ta__rencana);
				}
			}
			
			// returning the message
			if($insert )
			{
				// nothing wrong, both query is running successfully!
				return throw_exception(301, 'Sinkronisasi Rencana dan Belanja berhasil dijalankan. Sebanyak <b>' . number_format($insert) . '</b> data berhasil dikirim ke SIMDA', current_page('../Per_item'));
			}
			else
			{
				// nothing happened
				return throw_exception(202, 'Sinkronisasi Kegiatan gagal dijalankan...', current_page('../Per_item'));
			}
		}
		else
		{
			return throw_exception(404, 'Query kegiatan tidak mendapatkan hasil. Sinkronisasi dibatalkan...', current_page('../Per_item'));
		}
	}
	
	private function _filter()
	{
		$output										= null;
		$query										= $this->model
		->select
		('
			kode,
			kode_perubahan,
			nama_jenis_anggaran
		')
		->get_where
		(
			'ref__renja_jenis_anggaran'
		)
		->result();
		
		$output										= '<option value="0">Kode Perubahan</option>';
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->kode_perubahan . '"' . ($val->kode_perubahan == $this->_perubahan ? ' selected' : null) . '>' . $val->kode_perubahan . ' - ' . $val->nama_jenis_anggaran . '</option>';
			}
		}
		$output										= '
			<select name="kd_perubahan" class="form-control bordered input-sm" placeholder="Kode Perubahan">
				' . $output . '
			</select>
		';
		return $output;
	}
}