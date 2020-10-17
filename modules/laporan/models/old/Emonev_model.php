<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Emonev_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function kemajuan_kegiatan($unit = null, $tahun = null, $jenis_bl = 0, $sumber_dana = 0)
	{
		if(2 == $jenis_bl)
		{
			$jenis_bl								= '< 15';
		}
		elseif(3 == $jenis_bl)
		{
			$jenis_bl								= '>= 15';
		}
		else
		{
			$jenis_bl								= 'LIKE "%"';
		}
		if($sumber_dana == 'all')
		{
			$sumber_dana							= "'%'";
		}
		$tw											= $this->input->get('triwulan');
		$header										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.tahun,
				ref__unit.kd_unit,
				ref__unit.nm_unit
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__bidang.id = ref__unit.id_bidang
			INNER JOIN ref__urusan ON ref__urusan.id = ref__bidang.id_urusan
			WHERE
				ref__unit.id = ' . $unit . '
		')
		->row();
		
		$kegiatan_per_unit							= array();
		if($header)
		{
			$parameter								= array
			(
				'X-API-KEY'							=> 'DEBD-2D65-280B-D1B0-5742',
				'parameter'							=> array
				(
					'Tahun'							=> $header->tahun,
					'Kd_Urusan'						=> $header->kd_urusan,
					'Kd_Bidang'						=> $header->kd_bidang,
					'Kd_Unit'						=> $header->kd_unit
				)
			);
			
			$this->load->library('rest');
			$data									= $this->rest->get('https://simpelbang.bekasikota.go.id/' . $tahun . '/apis/kegiatan_per_unit', $parameter);
			$data									= json_decode($data);
			if(isset($data->RESULTS))
			{
				$kegiatan_per_unit					= $data->RESULTS;
			}
			//print_r($kegiatan_per_unit->rencana);exit;
			if(isset($kegiatan_per_unit->rencana))
			{
				// delete temporary table
				$this->db->query('DROP TABLE IF EXISTS tmp__rencana_fisik');
				$this->db->query('DROP TABLE IF EXISTS tmp__realisasi_fisik');
				$this->db->query('DROP TABLE IF EXISTS tmp__rencana_keuangan');
				$this->db->query('DROP TABLE IF EXISTS tmp__realisasi_keuangan');
				
				// create temporary table
				$create_table						= $this->db->query
				('
					CREATE TEMPORARY TABLE tmp__rencana_fisik
					(
						id INT,
						Tahun YEAR,
						Kd_Urusan INT(5),
						Kd_Bidang INT(5),
						Kd_Unit INT(5),
						Kd_Sub INT(5),
						ID_Prog INT(5),
						Kd_Prog INT(5),
						Kd_Keg INT(5),
						Jan DECIMAL(5,2),
						Feb DECIMAL(5,2),
						Mar DECIMAL(5,2),
						Apr DECIMAL(5,2),
						Mei DECIMAL(5,2),
						Jun DECIMAL(5,2),
						Jul DECIMAL(5,2),
						Agt DECIMAL(5,2),
						Sep DECIMAL(5,2),
						Okt DECIMAL(5,2),
						Nop DECIMAL(5,2),
						Des DECIMAL(5,2)
					)
				');
				
				if($kegiatan_per_unit->rencana)
				{
					//$prepare						= (array) $kegiatan_per_unit->rencana;
					/* insert rencana ke temporary table */
					$this->db->insert_batch('tmp__rencana_fisik', $kegiatan_per_unit->rencana, sizeof($kegiatan_per_unit->rencana));
					//$this->db->insert('tmp__rencana_fisik', $prepare);
				}
				
				// create temporary table
				$create_table						= $this->db->query
				('
					CREATE TEMPORARY TABLE tmp__realisasi_fisik
					(
						id INT,
						Tahun YEAR,
						Kd_Urusan INT(5),
						Kd_Bidang INT(5),
						Kd_Unit INT(5),
						Kd_Sub INT(5),
						ID_Prog INT(5),
						Kd_Prog INT(5),
						Kd_Keg INT(5),
						Jan DECIMAL(5,2),
						Feb DECIMAL(5,2),
						Mar DECIMAL(5,2),
						Apr DECIMAL(5,2),
						Mei DECIMAL(5,2),
						Jun DECIMAL(5,2),
						Jul DECIMAL(5,2),
						Agt DECIMAL(5,2),
						Sep DECIMAL(5,2),
						Okt DECIMAL(5,2),
						Nop DECIMAL(5,2),
						Des DECIMAL(5,2),
						Hambatan_Jan TINYTEXT,
						Hambatan_Feb TINYTEXT,
						Hambatan_Mar TINYTEXT,
						Hambatan_Apr TINYTEXT,
						Hambatan_Mei TINYTEXT,
						Hambatan_Jun TINYTEXT,
						Hambatan_Jul TINYTEXT,
						Hambatan_Agt TINYTEXT,
						Hambatan_Sep TINYTEXT,
						Hambatan_Okt TINYTEXT,
						Hambatan_Nop TINYTEXT,
						Hambatan_Des TINYTEXT,
						Photo TEXT
					)
				');
				
				if($kegiatan_per_unit->realisasi)
				{
					//$prepare						= (array) $kegiatan_per_unit->realisasi;
					/* insert realisasi ke temporary table */
					$this->db->insert_batch('tmp__realisasi_fisik', $kegiatan_per_unit->realisasi, sizeof($kegiatan_per_unit->realisasi));
					//$this->db->insert('tmp__realisasi_fisik', $prepare);
				}
				//print_r($prepare);exit;
			}
			
			$tmp_rencana_keuangan						= $this->_connector()->query
			('
				SELECT
					Tahun,
					Kd_Perubahan,
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					Kd_Sub,
					Kd_Prog,
					ID_Prog,
					Kd_Keg,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(Jan + Feb + Mar)
						WHEN ' . $tw . ' = 2 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun)
						WHEN ' . $tw . ' = 3 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep)
						WHEN ' . $tw . ' = 4 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep + Okt + Nop + Des)
					END nilai_rencana_uang
				FROM
					Ta_Rencana_Arsip
				GROUP BY
					Tahun,
					Kd_Perubahan,
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					Kd_Sub,
					Kd_Prog,
					ID_Prog,
					Kd_Keg
				HAVING
					Tahun = ' . $header->tahun . ' AND 
					Kd_Perubahan = (SELECT	MAX(Kd_Perubahan) FROM Ta_Rencana_Arsip) AND 
					Kd_Urusan = ' . $header->kd_urusan . ' AND 
					Kd_Bidang = ' . $header->kd_bidang . ' AND 
					Kd_Unit = ' . $header->kd_unit . '
			')
			->result_array();
			//print_r($tmp_rencana_keuangan);exit;
				
			// create temporary table
			$create_table						= $this->db->query
			('
				CREATE TEMPORARY TABLE tmp__rencana_keuangan
				(
					Tahun YEAR,
					Kd_Perubahan INT(5),
					Kd_Urusan INT(5),
					Kd_Bidang INT(5),
					Kd_Unit INT(5),
					Kd_Sub INT(5),
					ID_Prog INT(5),
					Kd_Prog INT(5),
					Kd_Keg INT(5),
					nilai_rencana_uang DECIMAL(19,2)
				)
			');
			
			if($tmp_rencana_keuangan)
			{
				/* insert realisasi ke temporary table */
				$this->db->insert_batch('tmp__rencana_keuangan', $tmp_rencana_keuangan, sizeof($tmp_rencana_keuangan));
			}
			
			$tmp_realisasi_keuangan						= $this->_connector()->query
			('
				SELECT
					a.Tahun,
					a.Kd_Urusan,
					a.Kd_Bidang,
					a.Kd_Unit,
					a.Kd_Sub,
					a.Kd_Prog,
					a.ID_Prog,
					a.Kd_Keg,
					(COALESCE(sp2d.nilai_sp2d, 0) - COALESCE(penyesuaian.nilai_penyesuaian, 0) + COALESCE(koreksi.nilai_debet, 0) - COALESCE(koreksi.nilai_kredit, 0) + COALESCE(jurnal.nilai_jurnal, 0)
					) AS nilai_realisasi_uang
				FROM
					ta_kegiatan AS a
				LEFT JOIN
				(
					/* SPM dan SPD */
					SELECT
						Ta_SPM_Rinc.Kd_Urusan,
						Ta_SPM_Rinc.Kd_Bidang,
						Ta_SPM_Rinc.Kd_Unit,
						Ta_SPM_Rinc.Kd_Sub,
						Ta_SPM_Rinc.Kd_Prog,
						Ta_SPM_Rinc.ID_Prog,
						Ta_SPM_Rinc.Kd_Keg, 
						CASE
							WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-03-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-06-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-09-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-12-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						END nilai_sp2d
					FROM
						Ta_SPM_Rinc 
					INNER JOIN Ta_SPM ON Ta_SPM_Rinc.Tahun = Ta_SPM.Tahun AND Ta_SPM_Rinc.No_SPM = Ta_SPM.No_SPM 
					INNER JOIN Ta_SP2D ON Ta_SPM.Tahun = Ta_SP2D.Tahun AND Ta_SPM.No_SPM = Ta_SP2D.No_SPM
					WHERE
						(NOT (Ta_SP2D.No_SP2D IS NULL)) AND								
						(Ta_SPM.Jn_SPM <> 1) AND
						(Ta_SPM.Jn_SPM <> 4) AND
						(Ta_SPM_Rinc.Kd_Rek_1 <> 6)
					GROUP BY
						Ta_SPM_Rinc.Kd_Urusan,
						Ta_SPM_Rinc.Kd_Bidang,
						Ta_SPM_Rinc.Kd_Unit,
						Ta_SPM_Rinc.Kd_Sub,
						Ta_SPM_Rinc.Kd_Prog,
						Ta_SPM_Rinc.ID_Prog,
						Ta_SPM_Rinc.Kd_Keg
				) AS sp2d ON
					sp2d.Kd_Urusan = a.Kd_Urusan AND sp2d.Kd_Bidang = a.Kd_Bidang AND sp2d.Kd_Unit = a.Kd_Unit AND sp2d.Kd_Sub = a.Kd_Sub AND sp2d.Kd_Prog = a.Kd_Prog AND sp2d.ID_Prog = a.ID_Prog AND sp2d.Kd_Keg = a.Kd_Keg
				LEFT JOIN
				(
					/* Penyesuaian */
					SELECT
						Ta_Penyesuaian_Rinc.Kd_Urusan,
						Ta_Penyesuaian_Rinc.Kd_Bidang,
						Ta_Penyesuaian_Rinc.Kd_Unit,
						Ta_Penyesuaian_Rinc.Kd_Sub,
						Ta_Penyesuaian_Rinc.Kd_Prog,
						Ta_Penyesuaian_Rinc.ID_Prog,
						Ta_Penyesuaian_Rinc.Kd_Keg,
						CASE
							WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-03-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-06-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-09-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-12-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						END nilai_penyesuaian
					FROM
						Ta_Penyesuaian 
					INNER JOIN
						Ta_Penyesuaian_Rinc ON Ta_Penyesuaian.Tahun = Ta_Penyesuaian_Rinc.Tahun AND Ta_Penyesuaian.No_Bukti = Ta_Penyesuaian_Rinc.No_Bukti
					WHERE
						(Ta_Penyesuaian_Rinc.D_K = \'K\') AND
						(Ta_Penyesuaian.Jns_P1 = 1) AND
						(Ta_Penyesuaian.Jns_P2 = 3)AND
						(Ta_Penyesuaian.Tahun = ' . $header->tahun . ')
					GROUP BY
						Ta_Penyesuaian_Rinc.Kd_Urusan,
						Ta_Penyesuaian_Rinc.Kd_Bidang,
						Ta_Penyesuaian_Rinc.Kd_Unit,
						Ta_Penyesuaian_Rinc.Kd_Sub,
						Ta_Penyesuaian_Rinc.Kd_Prog,
						Ta_Penyesuaian_Rinc.ID_Prog,
						Ta_Penyesuaian_Rinc.Kd_Keg
				) AS penyesuaian ON 
					penyesuaian.Kd_Urusan = a.Kd_Urusan AND penyesuaian.Kd_Bidang = a.Kd_Bidang AND penyesuaian.Kd_Unit = a.Kd_Unit AND penyesuaian.Kd_Sub = a.Kd_Sub AND penyesuaian.Kd_Prog = a.Kd_Prog AND penyesuaian.ID_Prog = a.ID_Prog AND penyesuaian.Kd_keg = a.Kd_Keg
				LEFT JOIN
				(
					/* Jurnal Koreksi */
					SELECT
						Ta_JurnalSemua.Kd_Urusan,
						Ta_JurnalSemua.Kd_Bidang,
						Ta_JurnalSemua.Kd_Unit,
						Ta_JurnalSemua.Kd_Sub,
						Ta_JurnalSemua_Rinc.Kd_Prog,
						Ta_JurnalSemua_Rinc.ID_Prog,
						Ta_JurnalSemua_Rinc.Kd_Keg,
						SUM(Ta_JurnalSemua_Rinc.Debet) AS debet, 
						SUM(Ta_JurnalSemua_Rinc.Kredit) AS kredit,
						CASE
							WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
							WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
							WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
							WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						END nilai_debet,
						CASE
							WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
							WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
							WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
							WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						END nilai_kredit
					FROM
						Ta_JurnalSemua
					INNER JOIN Ta_JurnalSemua_Rinc ON
						Ta_JurnalSemua.Tahun = Ta_JurnalSemua_Rinc.Tahun AND Ta_JurnalSemua.Kd_Source = Ta_JurnalSemua_Rinc.Kd_Source AND Ta_JurnalSemua.No_Bukti = Ta_JurnalSemua_Rinc.No_Bukti
					WHERE
						(Ta_JurnalSemua_Rinc.Kd_Jurnal = 5) AND (Ta_JurnalSemua_Rinc.Kd_Rek_1 = 5) AND (Ta_JurnalSemua_Rinc.Tahun = ' . $header->tahun . ')
					GROUP BY
						Ta_JurnalSemua.Kd_Urusan,
						Ta_JurnalSemua.Kd_Bidang,
						Ta_JurnalSemua.Kd_Unit,
						Ta_JurnalSemua.Kd_Sub,
						Ta_JurnalSemua_Rinc.Kd_Prog,
						Ta_JurnalSemua_Rinc.ID_Prog,
						Ta_JurnalSemua_Rinc.Kd_Keg
				) AS koreksi ON
					koreksi.Kd_Urusan = a.Kd_Urusan AND koreksi.Kd_Bidang = a.Kd_Bidang AND koreksi.Kd_Unit = a.Kd_Unit AND koreksi.Kd_Sub = a.Kd_Sub AND koreksi.Kd_Prog = a.Kd_Prog AND koreksi.ID_Prog = a.ID_Prog AND koreksi.Kd_Keg = a.Kd_Keg
				LEFT JOIN
				(
					/* Jurnal BLUD / FKTP */
					SELECT
						Ta_SP3B_Rinc.Kd_Urusan,
						Ta_SP3B_Rinc.Kd_Bidang,
						Ta_SP3B_Rinc.Kd_Unit,
						Ta_SP3B_Rinc.Kd_Sub,
						Ta_SP3B_Rinc.Kd_Prog,
						Ta_SP3B_Rinc.ID_Prog,
						Ta_SP3B_Rinc.Kd_Keg,
						CASE
							WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-03-31\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-06-30\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-09-30\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 end)
							WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $header->tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $header->tahun . '-12-31\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 end)
						END nilai_jurnal
					FROM
						Ta_SP2B
					INNER JOIN Ta_SP3B_Rinc ON Ta_SP2B.Tahun = Ta_SP3B_Rinc.Tahun
					AND Ta_SP2B.No_SP3B = Ta_SP3B_Rinc.No_SP3B
					WHERE
						Ta_SP3B_Rinc.Kd_Rek_1 = 5
					GROUP BY
						Ta_SP3B_Rinc.Kd_Urusan,
						Ta_SP3B_Rinc.Kd_Bidang,
						Ta_SP3B_Rinc.Kd_Unit,
						Ta_SP3B_Rinc.Kd_Sub,
						Ta_SP3B_Rinc.Kd_Prog,
						Ta_SP3B_Rinc.ID_Prog,
						Ta_SP3B_Rinc.Kd_Keg
				) AS jurnal ON
					jurnal.Kd_Urusan = a.Kd_Urusan AND jurnal.Kd_Bidang = a.Kd_Bidang AND jurnal.Kd_Unit = a.Kd_Unit AND jurnal.Kd_Sub = a.Kd_Sub AND jurnal.Kd_Prog = a.Kd_Prog AND jurnal.ID_Prog = a.ID_Prog AND jurnal.Kd_Keg = a.Kd_Keg
				WHERE
					a.tahun = ' . $header->tahun . ' AND 
					a.Kd_Urusan = ' . $header->kd_urusan . ' AND 
					a.Kd_Bidang = ' . $header->kd_bidang . ' AND 
					a.Kd_Unit = ' . $header->kd_unit . '
			')
			->result_array();
			//print_r($tmp_realisasi_keuangan);exit;
			
			// create temporary table
			$create_table						= $this->db->query
			('
				CREATE TEMPORARY TABLE tmp__realisasi_keuangan
				(
					Tahun YEAR,
					Kd_Urusan INT(5),
					Kd_Bidang INT(5),
					Kd_Unit INT(5),
					Kd_Sub INT(5),
					ID_Prog INT(5),
					Kd_Prog INT(5),
					Kd_Keg INT(5),
					nilai_realisasi_uang DECIMAL(19,2)
				)
			');
			
			if($tmp_realisasi_keuangan)
			{
				/* insert realisasi ke temporary table */
				$this->db->insert_batch('tmp__realisasi_keuangan', $tmp_realisasi_keuangan, sizeof($tmp_realisasi_keuangan));
			}
		}
		
		$kode_murni									= 8;
		$kode_perubahan								= 20;
		$data										= $this->db->query
		('
			SELECT
				ref__program.nm_program,
				ta__kegiatan_arsip.id_prog,
				ta__kegiatan_arsip.kode_urusan,
				ta__kegiatan_arsip.kode_bidang,
				ta__kegiatan_arsip.kode_unit,
				ta__kegiatan_arsip.kode_sub,
				ta__kegiatan_arsip.kode_prog,
				ta__kegiatan_arsip.kode_id_prog,
				ta__kegiatan_arsip.kode_keg,
				ta__kegiatan_arsip.kegiatan,
				ta__kegiatan_arsip.pagu,
				program.pagu_program,
				rencana_fisik.nilai_rencana_fisik,
				realisasi_fisik.nilai_realisasi_fisik,
				tmp__rencana_keuangan.nilai_rencana_uang,
				tmp__realisasi_keuangan.nilai_realisasi_uang
			FROM
				ta__kegiatan_arsip
			INNER JOIN ref__program ON ref__program.id = ta__kegiatan_arsip.id_prog
			LEFT JOIN (
				SELECT
					ta__kegiatan_arsip.id_prog,
					SUM(ta__kegiatan_arsip.pagu) AS pagu_program
				FROM
					ta__kegiatan_arsip
				WHERE
					ta__kegiatan_arsip.id_unit = ' . $unit . '
					AND ta__kegiatan_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_arsip)
					AND ta__kegiatan_arsip.tahun = ' . $tahun . '
					AND ta__kegiatan_arsip.id_sumber_dana LIKE ' . $sumber_dana . '
				GROUP BY
					ta__kegiatan_arsip.id_prog
			) AS program ON program.id_prog 	= ta__kegiatan_arsip.id_prog
			LEFT JOIN (
				SELECT
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					Kd_Sub,
					ID_Prog,
					Kd_Prog,
					Kd_Keg,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(Jan + Feb + Mar)
						WHEN ' . $tw . ' = 2 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun)
						WHEN ' . $tw . ' = 3 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep)
						WHEN ' . $tw . ' = 4 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep + Okt + Nop + Des)
					END nilai_rencana_fisik
				FROM
					tmp__rencana_fisik
				WHERE
					Tahun = ' . $tahun . '
				GROUP BY
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					Kd_Sub,
					ID_Prog,
					Kd_Prog,
					Kd_Keg
			) AS rencana_fisik ON 
				rencana_fisik.Kd_Urusan 		= ta__kegiatan_arsip.kode_urusan AND
				rencana_fisik.Kd_Bidang 		= ta__kegiatan_arsip.kode_bidang AND
				rencana_fisik.Kd_Unit 			= ta__kegiatan_arsip.kode_unit AND
				rencana_fisik.Kd_Sub 			= ta__kegiatan_arsip.kode_sub AND
				rencana_fisik.Kd_Prog 			= ta__kegiatan_arsip.kode_prog AND
				rencana_fisik.ID_Prog 			= ta__kegiatan_arsip.kode_id_prog AND
				rencana_fisik.Kd_Keg 			= ta__kegiatan_arsip.kode_keg
			LEFT JOIN (
				SELECT
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					Kd_Sub,
					ID_Prog,
					Kd_Prog,
					Kd_Keg,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(Jan + Feb + Mar)
						WHEN ' . $tw . ' = 2 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun)
						WHEN ' . $tw . ' = 3 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep)
						WHEN ' . $tw . ' = 4 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep + Okt + Nop + Des)
					END nilai_realisasi_fisik
				FROM
					tmp__realisasi_fisik
				WHERE
					Tahun = ' . $tahun . '
				GROUP BY
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					Kd_Sub,
					ID_Prog,
					Kd_Prog,
					Kd_Keg
			) AS realisasi_fisik ON 
				realisasi_fisik.Kd_Urusan 	= ta__kegiatan_arsip.kode_urusan AND
				realisasi_fisik.Kd_Bidang 	= ta__kegiatan_arsip.kode_bidang AND
				realisasi_fisik.Kd_Unit 	= ta__kegiatan_arsip.kode_unit AND
				realisasi_fisik.Kd_Sub 		= ta__kegiatan_arsip.kode_sub AND
				realisasi_fisik.Kd_Prog 	= ta__kegiatan_arsip.kode_prog AND
				realisasi_fisik.ID_Prog 	= ta__kegiatan_arsip.kode_id_prog AND
				realisasi_fisik.Kd_Keg 		= ta__kegiatan_arsip.kode_keg
			LEFT JOIN tmp__rencana_keuangan ON 
				tmp__rencana_keuangan.Kd_Urusan 		= ta__kegiatan_arsip.kode_urusan AND
				tmp__rencana_keuangan.Kd_Bidang 		= ta__kegiatan_arsip.kode_bidang AND
				tmp__rencana_keuangan.Kd_Unit 			= ta__kegiatan_arsip.kode_unit AND
				tmp__rencana_keuangan.Kd_Sub 			= ta__kegiatan_arsip.kode_sub AND
				tmp__rencana_keuangan.Kd_Prog 			= ta__kegiatan_arsip.kode_prog AND
				tmp__rencana_keuangan.ID_Prog 			= ta__kegiatan_arsip.kode_id_prog AND
				tmp__rencana_keuangan.Kd_Keg 			= ta__kegiatan_arsip.kode_keg
			LEFT JOIN tmp__realisasi_keuangan ON 
				tmp__realisasi_keuangan.Kd_Urusan 		= ta__kegiatan_arsip.kode_urusan AND
				tmp__realisasi_keuangan.Kd_Bidang 		= ta__kegiatan_arsip.kode_bidang AND
				tmp__realisasi_keuangan.Kd_Unit 		= ta__kegiatan_arsip.kode_unit AND
				tmp__realisasi_keuangan.Kd_Sub 			= ta__kegiatan_arsip.kode_sub AND
				tmp__realisasi_keuangan.Kd_Prog 		= ta__kegiatan_arsip.kode_prog AND
				tmp__realisasi_keuangan.ID_Prog 		= ta__kegiatan_arsip.kode_id_prog AND
				tmp__realisasi_keuangan.Kd_Keg 			= ta__kegiatan_arsip.kode_keg
			WHERE
				ref__program.kd_program ' . $jenis_bl . '
				AND ta__kegiatan_arsip.id_unit = ' . $unit . '
				AND ta__kegiatan_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_arsip)
				AND ta__kegiatan_arsip.tahun = ' . $tahun . '
				AND ta__kegiatan_arsip.id_sumber_dana LIKE ' . $sumber_dana . '
			ORDER BY
				ta__kegiatan_arsip.kode_prog ASC,
				ta__kegiatan_arsip.kode_id_prog ASC,
				ta__kegiatan_arsip.kode_keg ASC
		')
		->result();
		
		$output										= array
		(
			'header'								=> $header,
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function konsolidasi_triwulanan($tahun = null)
	{
		$tw											= $this->input->get('triwulan');
		$parameter									= array
		(
			'X-API-KEY'								=> 'DEBD-2D65-280B-D1B0-5742',
			'parameter'								=> array
			(
				'Tahun'								=> get_userdata('year'),
				'triwulan'							=> $this->input->get('triwulan')
				//'Kd_Bidang'						=> $header->kd_bidang,
				//'Kd_Unit'							=> $header->kd_unit
			)
		);
		
		$this->load->library('rest');
		$data										= $this->rest->get('https://simpelbang.bekasikota.go.id/' . $tahun . '/apis/rekap_per_unit', $parameter);
		$data										= json_decode($data);
		//print_r($data);exit;
		if(isset($data->RESULTS))
		{
			$rekap_per_unit							= $data->RESULTS;
		}
		//print_r($rekap_per_unit->rencana);exit;
		// delete temporary table
		$this->db->query('DROP TABLE IF EXISTS tmp__rencana_fisik');
		
		// create temporary table
		$create_table								= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rencana_fisik
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Unit INT(5),
				nilai_rencana_fisik DECIMAL(9,2)
			)
		');
		if(isset($rekap_per_unit->rencana))
		{
			$prepare								= (array) $rekap_per_unit->rencana;
			/* insert rencana ke temporary table */
			$this->db->insert_batch('tmp__rencana_fisik', $prepare, sizeof($prepare));
			//$this->db->insert('tmp__rencana_fisik', $prepare);
		}
		/*$abc								= $this->db->query
		('
			SELECT *
			FROM
				tmp__rencana_fisik
		')
		->result();*/
		//print_r($abc);exit;
		
			// tmp__realisasi_fisik
		// delete temporary table
		$this->db->query('DROP TABLE IF EXISTS tmp__realisasi_fisik');
		
		// create temporary table
		$create_table								= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__realisasi_fisik
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Unit INT(5),
				nilai_realisasi_fisik DECIMAL(9,2)
			)
		');
		if(isset($rekap_per_unit->realisasi))
		{
			$prepare								= (array) $rekap_per_unit->realisasi;
			/* insert realisasi ke temporary table */
			//$this->db->insert('tmp__realisasi_fisik', $prepare);
			$this->db->insert_batch('tmp__realisasi_fisik', $prepare, sizeof($prepare));
		}
		$kode_murni									= 8;
		$kode_perubahan								= 20;
		
		$tmp_realisasi_keuangan						= $this->_connector()->query
		('
			SELECT
				a.Kd_Urusan,
				a.Kd_Bidang,
				a.Kd_Unit,
				(COALESCE(sp2d.nilai_sp2d, 0) - COALESCE(penyesuaian.nilai_penyesuaian, 0) + COALESCE(koreksi.nilai_debet, 0) - COALESCE(koreksi.nilai_kredit, 0) + COALESCE(jurnal.nilai_jurnal, 0)
				) AS nilai_realisasi_uang
			FROM
				Ref_Unit AS a
			LEFT JOIN
			(
				/* SPM dan SPD */
				SELECT
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					Ta_SPM_Rinc.Kd_Unit,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
					END nilai_sp2d
				FROM
					Ta_SPM_Rinc 
				INNER JOIN Ta_SPM ON Ta_SPM_Rinc.Tahun = Ta_SPM.Tahun AND Ta_SPM_Rinc.No_SPM = Ta_SPM.No_SPM 
				INNER JOIN Ta_SP2D ON Ta_SPM.Tahun = Ta_SP2D.Tahun AND Ta_SPM.No_SPM = Ta_SP2D.No_SPM
				WHERE
					(NOT (Ta_SP2D.No_SP2D IS NULL)) AND								
					(Ta_SPM.Jn_SPM <> 1) AND
					(Ta_SPM.Jn_SPM <> 4) AND
					(Ta_SPM_Rinc.Kd_Rek_1 <> 6) AND
					(Ta_SPM_Rinc.Kd_Rek_2 <> 1)
				GROUP BY
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					Ta_SPM_Rinc.Kd_Unit
			) AS sp2d ON
				sp2d.Kd_Urusan = a.Kd_Urusan AND sp2d.Kd_Bidang = a.Kd_Bidang AND sp2d.Kd_Unit = a.Kd_Unit
			LEFT JOIN
			(
				/* Penyesuaian */
				SELECT
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					Ta_Penyesuaian_Rinc.Kd_Unit,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
					END nilai_penyesuaian
				FROM
					Ta_Penyesuaian 
				INNER JOIN
					Ta_Penyesuaian_Rinc ON Ta_Penyesuaian.Tahun = Ta_Penyesuaian_Rinc.Tahun AND Ta_Penyesuaian.No_Bukti = Ta_Penyesuaian_Rinc.No_Bukti
				WHERE
					(Ta_Penyesuaian_Rinc.D_K = \'K\') AND
					(Ta_Penyesuaian.Jns_P1 = 1) AND
					(Ta_Penyesuaian.Jns_P2 = 3) AND
					(Ta_Penyesuaian_Rinc.Kd_Rek_2 <> 1) AND
					(Ta_Penyesuaian.Tahun = ' . $tahun . ')
				GROUP BY
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					Ta_Penyesuaian_Rinc.Kd_Unit
			) AS penyesuaian ON 
				penyesuaian.Kd_Urusan = a.Kd_Urusan AND penyesuaian.Kd_Bidang = a.Kd_Bidang AND penyesuaian.Kd_Unit = a.Kd_Unit
			LEFT JOIN
			(
				/* Jurnal Koreksi */
				SELECT
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					Ta_JurnalSemua.Kd_Unit,
					SUM(Ta_JurnalSemua_Rinc.Debet) AS debet, 
					SUM(Ta_JurnalSemua_Rinc.Kredit) AS kredit,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
					END nilai_debet,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
					END nilai_kredit
				FROM
					Ta_JurnalSemua
				INNER JOIN
					Ta_JurnalSemua_Rinc ON
					Ta_JurnalSemua.Tahun = Ta_JurnalSemua_Rinc.Tahun AND Ta_JurnalSemua.Kd_Source = Ta_JurnalSemua_Rinc.Kd_Source AND Ta_JurnalSemua.No_Bukti = Ta_JurnalSemua_Rinc.No_Bukti
				WHERE
					(Ta_JurnalSemua_Rinc.Kd_Jurnal = 5) AND (Ta_JurnalSemua_Rinc.Kd_Rek_1 = 5) AND (Ta_JurnalSemua_Rinc.Tahun = ' . $tahun . ')
				GROUP BY
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					Ta_JurnalSemua.Kd_Unit
			) AS koreksi ON
				koreksi.Kd_Urusan = a.Kd_Urusan AND koreksi.Kd_Bidang = a.Kd_Bidang AND koreksi.Kd_Unit = a.Kd_Unit
			LEFT JOIN
			(
				/* Jurnal BLUD / FKTP */
				SELECT
					Ta_SP3B_Rinc.Kd_Urusan,
					Ta_SP3B_Rinc.Kd_Bidang,
					Ta_SP3B_Rinc.Kd_Unit,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 end)
					END nilai_jurnal
				FROM
					dbo.Ta_SP2B
				INNER JOIN dbo.Ta_SP3B_Rinc ON dbo.Ta_SP2B.Tahun = dbo.Ta_SP3B_Rinc.Tahun
					AND dbo.Ta_SP2B.No_SP3B = dbo.Ta_SP3B_Rinc.No_SP3B
				WHERE
					dbo.Ta_SP3B_Rinc.Kd_Rek_1 = 5
				GROUP BY
					dbo.Ta_SP3B_Rinc.Kd_Urusan,
					dbo.Ta_SP3B_Rinc.Kd_Bidang,
					dbo.Ta_SP3B_Rinc.Kd_Unit
			) AS jurnal ON
				jurnal.Kd_Urusan = a.Kd_Urusan AND jurnal.Kd_Bidang = a.Kd_Bidang AND jurnal.Kd_Unit = a.Kd_Unit 
		')
		->result_array();
		//print_r($tmp_realisasi_keuangan);exit;
		
		// create temporary table
		$create_table						= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__realisasi_keuangan
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Unit INT(5),
				nilai_realisasi_uang DECIMAL(19,2)
			)
		');
		
		if($tmp_realisasi_keuangan)
		{
			/* insert realisasi ke temporary table */
			$this->db->insert_batch('tmp__realisasi_keuangan', $tmp_realisasi_keuangan, sizeof($tmp_realisasi_keuangan));
		}
			
		$total_pagu									= $this->db->query
		('
			SELECT
				SUM(pagu) as total_pagu
			FROM
				ta__kegiatan_arsip
			WHERE
				ta__kegiatan_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_arsip)
		')
		->row('total_pagu');
		//print_r($total_pagu);exit;
		
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				kegiatan_arsip.pagu,
				kegiatan_arsip.jumlah_kegiatan,
				rencana_fisik.nilai_rencana_fisik,
				realisasi_fisik.nilai_realisasi_fisik,
				tmp__realisasi_keuangan.nilai_realisasi_uang
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__bidang.id = ref__unit.id_bidang
			INNER JOIN ref__urusan ON ref__urusan.id = ref__bidang.id_urusan
			LEFT JOIN (
				SELECT
					ta__kegiatan_arsip.id_unit,
					SUM(ta__kegiatan_arsip.pagu) AS pagu,
					Count(ta__kegiatan_arsip.kode_keg) AS jumlah_kegiatan
				FROM
					ta__kegiatan_arsip
				WHERE
					ta__kegiatan_arsip.kode_perubahan = (SELECT MAX(ta__kegiatan_arsip.kode_perubahan) FROM ta__kegiatan_arsip)
				GROUP BY
					ta__kegiatan_arsip.id_unit
			) AS kegiatan_arsip ON kegiatan_arsip.id_unit = ref__unit.id
			LEFT JOIN (
				SELECT
					Tahun, Kd_Urusan, Kd_Bidang, Kd_Unit, nilai_rencana_fisik
				FROM
					tmp__rencana_fisik
			) AS rencana_fisik ON 
				rencana_fisik.Tahun 					= ref__unit.tahun AND
				rencana_fisik.Kd_Urusan					= ref__urusan.kd_urusan AND
				rencana_fisik.Kd_Bidang					= ref__bidang.kd_bidang AND
				rencana_fisik.Kd_Unit					= ref__unit.kd_unit
			LEFT JOIN (
				SELECT
					Tahun, Kd_Urusan, Kd_Bidang, Kd_Unit, nilai_realisasi_fisik
				FROM
					tmp__realisasi_fisik
			) AS realisasi_fisik ON 
				realisasi_fisik.Tahun 					= ref__unit.tahun AND
				realisasi_fisik.Kd_Urusan				= ref__urusan.kd_urusan AND
				realisasi_fisik.Kd_Bidang				= ref__bidang.kd_bidang AND
				realisasi_fisik.Kd_Unit					= ref__unit.kd_unit
			LEFT JOIN tmp__realisasi_keuangan ON 
				tmp__realisasi_keuangan.Kd_Urusan				= ref__urusan.kd_urusan AND
				tmp__realisasi_keuangan.Kd_Bidang				= ref__bidang.kd_bidang AND
				tmp__realisasi_keuangan.Kd_Unit					= ref__unit.kd_unit
			WHERE
				ref__unit.tahun = ' . $tahun . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC
		')
		->result();
		$output										= array
		(
			'total_pagu'							=> $total_pagu,
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function rekapitulasi_kemajuan_kegiatan($periode_awal = null, $periode_akhir = null, $tahun = null)
	{
		$month										= date("n", strtotime($periode_akhir));
		
		$parameter									= array
		(
			'X-API-KEY'								=> 'DEBD-2D65-280B-D1B0-5742',
			'parameter'								=> array
			(
				'Tahun'								=> get_userdata('year'),
				'periode_awal'						=> $periode_awal,
				'periode_akhir'						=> $periode_akhir
				//'Kd_Unit'							=> $header->kd_unit
			)
		);
		
		$this->load->library('rest');
		$data										= $this->rest->get('https://simpelbang.bekasikota.go.id/' . $tahun . '/apis/rekap_per_unit_periode', $parameter);
		$data										= json_decode($data);
		//print_r($data);exit;
		if(isset($data->RESULTS))
		{
			$rekap_per_unit							= $data->RESULTS;
		}
		//print_r($rekap_per_unit->rencana);exit;
		//print_r($rekap_per_unit->realisasi);exit;
		// delete temporary table
		$this->db->query('DROP TABLE IF EXISTS tmp__rencana_fisik');
		
		// create temporary table
		$create_table								= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rencana_fisik
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Unit INT(5),
				nilai_rencana_fisik DECIMAL(9,2)
			)
		');
		if(isset($rekap_per_unit->rencana))
		{
			$prepare								= (array) $rekap_per_unit->rencana;
			/* insert rencana ke temporary table */
			$this->db->insert_batch('tmp__rencana_fisik', $prepare, sizeof($prepare));
			//$this->db->insert('tmp__rencana_fisik', $prepare);
		}
		/*$abc								= $this->db->query
		('
			SELECT *
			FROM
				tmp__rencana_fisik
		')
		->result();*/
		//print_r($abc);exit;
		
			// tmp__realisasi_fisik
		// delete temporary table
		$this->db->query('DROP TABLE IF EXISTS tmp__realisasi_fisik');
		
		// create temporary table
		$create_table								= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__realisasi_fisik
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Unit INT(5),
				nilai_realisasi_fisik DECIMAL(9,2)
			)
		');
		if(isset($rekap_per_unit->realisasi))
		{
			$prepare								= (array) $rekap_per_unit->realisasi;
			/* insert realisasi ke temporary table */
			//$this->db->insert('tmp__realisasi_fisik', $prepare);
			$this->db->insert_batch('tmp__realisasi_fisik', $prepare, sizeof($prepare));
		}
		
		$tmp_realisasi_keuangan						= $this->_connector()->query
		('
			SELECT
				a.Kd_Urusan,
				a.Kd_Bidang,
				a.Kd_Unit,
				(COALESCE(sp2d.nilai_sp2d, 0) - COALESCE(penyesuaian.nilai_penyesuaian, 0) + COALESCE(koreksi.nilai_debet, 0) - COALESCE(koreksi.nilai_kredit, 0) + COALESCE(jurnal.nilai_jurnal, 0)
				) AS nilai_realisasi_uang
			FROM
				Ref_Unit AS a
			LEFT JOIN
			(
				/* SPM dan SPD */
				SELECT
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					Ta_SPM_Rinc.Kd_Unit,
					SUM(CASE WHEN Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $periode_awal . '\', 102) AND CONVERT(DATETIME, \'' . $periode_akhir . '\', 102) THEN Ta_SPM_Rinc.Nilai else 0 END) AS nilai_sp2d
				FROM
					Ta_SPM_Rinc 
				INNER JOIN Ta_SPM ON Ta_SPM_Rinc.Tahun = Ta_SPM.Tahun AND Ta_SPM_Rinc.No_SPM = Ta_SPM.No_SPM 
				INNER JOIN Ta_SP2D ON Ta_SPM.Tahun = Ta_SP2D.Tahun AND Ta_SPM.No_SPM = Ta_SP2D.No_SPM
				WHERE
					(NOT (Ta_SP2D.No_SP2D IS NULL)) AND								
					(Ta_SPM.Jn_SPM <> 1) AND
					(Ta_SPM.Jn_SPM <> 4) AND
					(Ta_SPM_Rinc.Kd_Rek_1 <> 6) AND
					(Ta_SPM_Rinc.Kd_Rek_2 <> 1)
				GROUP BY
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					Ta_SPM_Rinc.Kd_Unit
			) AS sp2d ON
				sp2d.Kd_Urusan = a.Kd_Urusan AND sp2d.Kd_Bidang = a.Kd_Bidang AND sp2d.Kd_Unit = a.Kd_Unit
			LEFT JOIN
			(
				/* Penyesuaian */
				SELECT
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					Ta_Penyesuaian_Rinc.Kd_Unit,
					SUM(CASE WHEN Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $periode_awal . '\', 102) AND CONVERT(DATETIME, \'' . $periode_akhir . '\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 END) AS nilai_penyesuaian
				FROM
					Ta_Penyesuaian 
				INNER JOIN
					Ta_Penyesuaian_Rinc ON Ta_Penyesuaian.Tahun = Ta_Penyesuaian_Rinc.Tahun AND Ta_Penyesuaian.No_Bukti = Ta_Penyesuaian_Rinc.No_Bukti
				WHERE
					(Ta_Penyesuaian_Rinc.D_K = \'K\') AND
					(Ta_Penyesuaian.Jns_P1 = 1) AND
					(Ta_Penyesuaian.Jns_P2 = 3) AND
					(Ta_Penyesuaian_Rinc.Kd_Rek_2 <> 1) AND
					(Ta_Penyesuaian.Tahun = ' . $tahun . ')
				GROUP BY
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					Ta_Penyesuaian_Rinc.Kd_Unit
			) AS penyesuaian ON 
				penyesuaian.Kd_Urusan = a.Kd_Urusan AND penyesuaian.Kd_Bidang = a.Kd_Bidang AND penyesuaian.Kd_Unit = a.Kd_Unit
			LEFT JOIN
			(
				/* Jurnal Koreksi */
				SELECT
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					Ta_JurnalSemua.Kd_Unit,
					SUM(Ta_JurnalSemua_Rinc.Debet) AS debet, 
					SUM(Ta_JurnalSemua_Rinc.Kredit) AS kredit,
					SUM(CASE WHEN Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $periode_awal . '\', 102) AND CONVERT(DATETIME, \'' . $periode_akhir . '\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 END) AS nilai_debet,
					SUM(CASE WHEN Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $periode_awal . '\', 102) AND CONVERT(DATETIME, \'' . $periode_akhir . '\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 END) AS	nilai_kredit
				FROM
					Ta_JurnalSemua
				INNER JOIN
					Ta_JurnalSemua_Rinc ON
					Ta_JurnalSemua.Tahun = Ta_JurnalSemua_Rinc.Tahun AND Ta_JurnalSemua.Kd_Source = Ta_JurnalSemua_Rinc.Kd_Source AND Ta_JurnalSemua.No_Bukti = Ta_JurnalSemua_Rinc.No_Bukti
				WHERE
					(Ta_JurnalSemua_Rinc.Kd_Jurnal = 5) AND (Ta_JurnalSemua_Rinc.Kd_Rek_1 = 5) AND (Ta_JurnalSemua_Rinc.Tahun = ' . $tahun . ')
				GROUP BY
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					Ta_JurnalSemua.Kd_Unit
			) AS koreksi ON
				koreksi.Kd_Urusan = a.Kd_Urusan AND koreksi.Kd_Bidang = a.Kd_Bidang AND koreksi.Kd_Unit = a.Kd_Unit
			LEFT JOIN
			(
				/* Jurnal BLUD / FKTP */
				SELECT
					Ta_SP3B_Rinc.Kd_Urusan,
					Ta_SP3B_Rinc.Kd_Bidang,
					Ta_SP3B_Rinc.Kd_Unit,
					SUM(CASE WHEN Ta_SP2B.Tgl_SP2B BETWEEN CONVERT(DATETIME, \'' . $periode_awal . '\', 102) AND CONVERT(DATETIME, \'' . $periode_akhir . '\', 102) THEN Ta_SP3B_Rinc.Nilai else 0 END) AS nilai_jurnal
				FROM
					dbo.Ta_SP2B
				INNER JOIN dbo.Ta_SP3B_Rinc ON dbo.Ta_SP2B.Tahun = dbo.Ta_SP3B_Rinc.Tahun
					AND dbo.Ta_SP2B.No_SP3B = dbo.Ta_SP3B_Rinc.No_SP3B
				WHERE
					dbo.Ta_SP3B_Rinc.Kd_Rek_1 = 5
				GROUP BY
					dbo.Ta_SP3B_Rinc.Kd_Urusan,
					dbo.Ta_SP3B_Rinc.Kd_Bidang,
					dbo.Ta_SP3B_Rinc.Kd_Unit
			) AS jurnal ON
				jurnal.Kd_Urusan = a.Kd_Urusan AND jurnal.Kd_Bidang = a.Kd_Bidang AND jurnal.Kd_Unit = a.Kd_Unit 
		')
		->result_array();
		//print_r($tmp_realisasi_keuangan);exit;
		
		// create temporary table
		$create_table						= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__realisasi_keuangan
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Unit INT(5),
				nilai_realisasi_uang DECIMAL(19,2)
			)
		');
		
		if($tmp_realisasi_keuangan)
		{
			/* insert realisasi ke temporary table */
			$this->db->insert_batch('tmp__realisasi_keuangan', $tmp_realisasi_keuangan, sizeof($tmp_realisasi_keuangan));
		}
		
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__unit.nm_unit,
				anggaran.anggaran,
				target_keuangan.target_keuangan,
				rencana_fisik.nilai_rencana_fisik,
				realisasi_fisik.nilai_realisasi_fisik,
				realisasi_keuangan.nilai_realisasi_uang
			FROM
				ref__unit
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN (
				SELECT
					ta__belanja_arsip.kd_urusan,
					ta__belanja_arsip.kd_bidang,
					ta__belanja_arsip.kd_unit,
					Sum(ta__belanja_arsip.total) AS anggaran
				FROM
					ta__belanja_arsip
				WHERE
					ta__belanja_arsip.tahun = ' . $tahun . ' AND
					ta__belanja_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__belanja_arsip)
				GROUP BY
					ta__belanja_arsip.kd_urusan,
					ta__belanja_arsip.kd_bidang,
					ta__belanja_arsip.kd_unit
			) AS anggaran ON 
				anggaran.kd_urusan = ref__urusan.kd_urusan AND 
				anggaran.kd_bidang = ref__bidang.kd_bidang AND 
				anggaran.kd_unit = ref__unit.kd_unit
			LEFT JOIN(
				SELECT
					ta__rencana_arsip.kode_urusan,
					ta__rencana_arsip.kode_bidang,
					ta__rencana_arsip.kode_unit,
					(CASE
						WHEN ' . $month . ' = 1 THEN SUM(ta__rencana_arsip.jan)
						WHEN ' . $month . ' = 2 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb)
						WHEN ' . $month . ' = 3 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar)
						WHEN ' . $month . ' = 4 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr)
						WHEN ' . $month . ' = 5 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr + ta__rencana_arsip.mei)
						WHEN ' . $month . ' = 6 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr + ta__rencana_arsip.mei + ta__rencana_arsip.jun)
						WHEN ' . $month . ' = 7 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr + ta__rencana_arsip.mei + ta__rencana_arsip.jun + ta__rencana_arsip.jul)
						WHEN ' . $month . ' = 8 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr + ta__rencana_arsip.mei + ta__rencana_arsip.jun + ta__rencana_arsip.jul + ta__rencana_arsip.agt)
						WHEN ' . $month . ' = 9 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr + ta__rencana_arsip.mei + ta__rencana_arsip.jun + ta__rencana_arsip.jul + ta__rencana_arsip.agt + ta__rencana_arsip.sep)
						WHEN ' . $month . ' = 10 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr + ta__rencana_arsip.mei + ta__rencana_arsip.jun + ta__rencana_arsip.jul + ta__rencana_arsip.agt + ta__rencana_arsip.sep + ta__rencana_arsip.okt)
						WHEN ' . $month . ' = 11 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr + ta__rencana_arsip.mei + ta__rencana_arsip.jun + ta__rencana_arsip.jul + ta__rencana_arsip.agt + ta__rencana_arsip.sep + ta__rencana_arsip.okt + ta__rencana_arsip.nop)
						WHEN ' . $month . ' = 12 THEN SUM(ta__rencana_arsip.jan + ta__rencana_arsip.feb + ta__rencana_arsip.mar + ta__rencana_arsip.apr + ta__rencana_arsip.mei + ta__rencana_arsip.jun + ta__rencana_arsip.jul + ta__rencana_arsip.agt + ta__rencana_arsip.sep + ta__rencana_arsip.okt + ta__rencana_arsip.nop + ta__rencana_arsip.des)
						END) AS target_keuangan
				FROM
					ta__rencana_arsip
				WHERE
					ta__rencana_arsip.tahun = ' . $tahun . ' AND
					ta__rencana_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__rencana_arsip)
				GROUP BY
					ta__rencana_arsip.kode_urusan,
					ta__rencana_arsip.kode_bidang,
					ta__rencana_arsip.kode_unit
			) AS target_keuangan ON 
				target_keuangan.kode_urusan = ref__urusan.kd_urusan AND
				target_keuangan.kode_bidang = ref__bidang.kd_bidang AND
				target_keuangan.kode_unit = ref__unit.kd_unit
			LEFT JOIN(
				SELECT
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					nilai_realisasi_uang
				FROM
					tmp__realisasi_keuangan
			) AS realisasi_keuangan ON
				realisasi_keuangan.Kd_Urusan = ref__urusan.kd_urusan AND
				realisasi_keuangan.Kd_Bidang = ref__bidang.kd_bidang AND
				realisasi_keuangan.Kd_Unit = ref__unit.kd_unit
			LEFT JOIN(
				SELECT
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					nilai_rencana_fisik
				FROM
					tmp__rencana_fisik
			) AS rencana_fisik ON
				rencana_fisik.Kd_Urusan = ref__urusan.kd_urusan AND
				rencana_fisik.Kd_Bidang = ref__bidang.kd_bidang AND
				rencana_fisik.Kd_Unit = ref__unit.kd_unit
			LEFT JOIN(
				SELECT
					Kd_Urusan,
					Kd_Bidang,
					Kd_Unit,
					nilai_realisasi_fisik
				FROM
					tmp__realisasi_fisik
			) AS realisasi_fisik ON
				realisasi_fisik.Kd_Urusan = ref__urusan.kd_urusan AND
				realisasi_fisik.Kd_Bidang = ref__bidang.kd_bidang AND
				realisasi_fisik.Kd_Unit = ref__unit.kd_unit
			WHERE
				ref__unit.tahun = ' . $tahun . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__unit.kd_unit ASC
		')
		->result();
		$output										= array
		(
			'data'									=> $data
		);
	//	print_r($output);exit;
		return $output;
	}
	
	public function evaluasi_hasil_rkpd($unit = null, $tahun = null)
	{
		$tw											= $this->input->get('triwulan');
		$bappeda									= $this->db->query
		('
			SELECT
				ref__settings.jabatan_kepala_perencanaan,
				ref__settings.nama_kepala_perencanaan,
				ref__settings.nip_kepala_perencanaan
			FROM
				ref__settings
			WHERE
				ref__settings.tahun = ' . $tahun . '
		')
		->row();
		if($unit == 'all')
		{
			$header									= null;
			$kode_urusan							= "'%'";
			$kode_bidang							= "'%'";
			$kode_unit								= "'%'";
		}
		else
		{
			$header									= $this->db->query
			('
				SELECT
					ref__urusan.kd_urusan,
					ref__bidang.kd_bidang,
					ref__unit.tahun,
					ref__unit.kd_unit,
					ref__unit.nm_unit,
					ref__unit.nama_jabatan,
					ref__unit.nama_pejabat,
					ref__unit.nip_pejabat
				FROM
					ref__unit
				INNER JOIN ref__bidang ON ref__bidang.id = ref__unit.id_bidang
				INNER JOIN ref__urusan ON ref__urusan.id = ref__bidang.id_urusan
				WHERE
					ref__unit.id = ' . $unit . '
			')
			->row();
			$kode_urusan							= $header->kd_urusan;
			$kode_bidang							= $header->kd_bidang;
			$kode_unit								= $header->kd_unit;
		}
		$kode_murni									= 8;
		$kode_perubahan								= 20;
		
		$tmp_realisasi_keuangan						= $this->_connector()->query
		('
			SELECT
				a.Tahun,
				a.Kd_Urusan,
				a.Kd_Bidang,
				a.Kd_Unit,
				a.Kd_Sub,
				a.Kd_Prog,
				a.ID_Prog,
				a.Kd_Keg,
				(COALESCE(sp2d.nilai_sp2d_tw_1, 0) - COALESCE(penyesuaian.nilai_penyesuaian_tw_1, 0) + COALESCE(koreksi.nilai_debet_tw_1, 0) - COALESCE(koreksi.nilai_kredit_tw_1, 0) + COALESCE(jurnal.nilai_jurnal_tw_1, 0)
				) AS nilai_realisasi_uang_tw_1,
				(COALESCE(sp2d.nilai_sp2d_tw_2, 0) - COALESCE(penyesuaian.nilai_penyesuaian_tw_2, 0) + COALESCE(koreksi.nilai_debet_tw_2, 0) - COALESCE(koreksi.nilai_kredit_tw_2, 0) + COALESCE(jurnal.nilai_jurnal_tw_2, 0)
				) AS nilai_realisasi_uang_tw_2,
				(COALESCE(sp2d.nilai_sp2d_tw_3, 0) - COALESCE(penyesuaian.nilai_penyesuaian_tw_3, 0) + COALESCE(koreksi.nilai_debet_tw_3, 0) - COALESCE(koreksi.nilai_kredit_tw_3, 0) + COALESCE(jurnal.nilai_jurnal_tw_3, 0)
				) AS nilai_realisasi_uang_tw_3,
				(COALESCE(sp2d.nilai_sp2d_tw_4, 0) - COALESCE(penyesuaian.nilai_penyesuaian_tw_4, 0) + COALESCE(koreksi.nilai_debet_tw_4, 0) - COALESCE(koreksi.nilai_kredit_tw_4, 0) + COALESCE(jurnal.nilai_jurnal_tw_4, 0)
				) AS nilai_realisasi_uang_tw_4
			FROM
				ta_kegiatan AS a
			LEFT JOIN
			(
				/* SPM dan SPD */
				SELECT
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					Ta_SPM_Rinc.Kd_Unit,
					Ta_SPM_Rinc.Kd_Sub,
					Ta_SPM_Rinc.Kd_Prog,
					Ta_SPM_Rinc.ID_Prog,
					Ta_SPM_Rinc.Kd_Keg, 
					SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end) AS nilai_sp2d_tw_1,
					SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-04-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end) AS nilai_sp2d_tw_2,
					SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-07-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end) AS nilai_sp2d_tw_3,
					SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-10-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end) AS nilai_sp2d_tw_4
				FROM
					Ta_SPM_Rinc 
				INNER JOIN Ta_SPM ON Ta_SPM_Rinc.Tahun = Ta_SPM.Tahun AND Ta_SPM_Rinc.No_SPM = Ta_SPM.No_SPM 
				INNER JOIN Ta_SP2D ON Ta_SPM.Tahun = Ta_SP2D.Tahun AND Ta_SPM.No_SPM = Ta_SP2D.No_SPM
				WHERE
					(NOT (Ta_SP2D.No_SP2D IS NULL)) AND								
					(Ta_SPM.Jn_SPM <> 1) AND
					(Ta_SPM.Jn_SPM <> 4) AND
					(Ta_SPM_Rinc.Kd_Rek_1 <> 6) AND
					(Ta_SPM_Rinc.Kd_Prog > 0)
				GROUP BY
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					Ta_SPM_Rinc.Kd_Unit,
					Ta_SPM_Rinc.Kd_Sub,
					Ta_SPM_Rinc.Kd_Prog,
					Ta_SPM_Rinc.ID_Prog,
					Ta_SPM_Rinc.Kd_Keg
			) AS sp2d ON
				sp2d.Kd_Urusan = a.Kd_Urusan AND sp2d.Kd_Bidang = a.Kd_Bidang AND sp2d.Kd_Unit = a.Kd_Unit AND sp2d.Kd_Sub = a.Kd_Sub AND sp2d.Kd_Prog = a.Kd_Prog AND sp2d.ID_Prog = a.ID_Prog AND sp2d.Kd_Keg = a.Kd_Keg
			LEFT JOIN
			(
				/* Penyesuaian */
				SELECT
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					Ta_Penyesuaian_Rinc.Kd_Unit,
					Ta_Penyesuaian_Rinc.Kd_Sub,
					Ta_Penyesuaian_Rinc.Kd_Prog,
					Ta_Penyesuaian_Rinc.ID_Prog,
					Ta_Penyesuaian_Rinc.Kd_Keg,
					SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end) AS nilai_penyesuaian_tw_1,
					SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-04-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end) AS nilai_penyesuaian_tw_2,
					SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-07-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end) AS nilai_penyesuaian_tw_3,
					SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-10-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end) AS nilai_penyesuaian_tw_4
				FROM
					Ta_Penyesuaian 
				INNER JOIN
					Ta_Penyesuaian_Rinc ON Ta_Penyesuaian.Tahun = Ta_Penyesuaian_Rinc.Tahun AND Ta_Penyesuaian.No_Bukti = Ta_Penyesuaian_Rinc.No_Bukti
				WHERE
					(Ta_Penyesuaian_Rinc.D_K = \'K\') AND
					(Ta_Penyesuaian.Jns_P1 = 1) AND
					(Ta_Penyesuaian.Jns_P2 = 3)AND
					(Ta_Penyesuaian.Tahun = ' . $tahun . ') AND
					(Ta_Penyesuaian_Rinc.Kd_Prog > 0)
				GROUP BY
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					Ta_Penyesuaian_Rinc.Kd_Unit,
					Ta_Penyesuaian_Rinc.Kd_Sub,
					Ta_Penyesuaian_Rinc.Kd_Prog,
					Ta_Penyesuaian_Rinc.ID_Prog,
					Ta_Penyesuaian_Rinc.Kd_Keg
			) AS penyesuaian ON 
				penyesuaian.Kd_Urusan = a.Kd_Urusan AND penyesuaian.Kd_Bidang = a.Kd_Bidang AND penyesuaian.Kd_Unit = a.Kd_Unit AND penyesuaian.Kd_Sub = a.Kd_Sub AND penyesuaian.Kd_Prog = a.Kd_Prog AND penyesuaian.ID_Prog = a.ID_Prog AND penyesuaian.Kd_keg = a.Kd_Keg
			LEFT JOIN
			(
				/* Jurnal Koreksi */
				SELECT
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					Ta_JurnalSemua.Kd_Unit,
					Ta_JurnalSemua.Kd_Sub,
					Ta_JurnalSemua_Rinc.Kd_Prog,
					Ta_JurnalSemua_Rinc.ID_Prog,
					Ta_JurnalSemua_Rinc.Kd_Keg,
					SUM(Ta_JurnalSemua_Rinc.Debet) AS debet, 
					SUM(Ta_JurnalSemua_Rinc.Kredit) AS kredit,
					SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end) AS nilai_debet_tw_1,
					SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-04-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end) AS nilai_debet_tw_2,
					SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-07-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end) AS nilai_debet_tw_3,
					SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-10-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end) AS nilai_debet_tw_4,
					
					SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end) AS nilai_kredit_tw_1,
					SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-04-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end) AS nilai_kredit_tw_2,
					SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-07-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end) AS nilai_kredit_tw_3,
					SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-10-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end) AS nilai_kredit_tw_4
				FROM
					Ta_JurnalSemua
				INNER JOIN Ta_JurnalSemua_Rinc ON
					Ta_JurnalSemua.Tahun = Ta_JurnalSemua_Rinc.Tahun AND Ta_JurnalSemua.Kd_Source = Ta_JurnalSemua_Rinc.Kd_Source AND Ta_JurnalSemua.No_Bukti = Ta_JurnalSemua_Rinc.No_Bukti
				WHERE
					(Ta_JurnalSemua_Rinc.Kd_Jurnal = 5) AND (Ta_JurnalSemua_Rinc.Kd_Rek_1 = 5) AND (Ta_JurnalSemua_Rinc.Tahun = ' . $tahun . ') AND (Ta_JurnalSemua_Rinc.Kd_Prog > 0)
				GROUP BY
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					Ta_JurnalSemua.Kd_Unit,
					Ta_JurnalSemua.Kd_Sub,
					Ta_JurnalSemua_Rinc.Kd_Prog,
					Ta_JurnalSemua_Rinc.ID_Prog,
					Ta_JurnalSemua_Rinc.Kd_Keg
			) AS koreksi ON
				koreksi.Kd_Urusan = a.Kd_Urusan AND koreksi.Kd_Bidang = a.Kd_Bidang AND koreksi.Kd_Unit = a.Kd_Unit AND koreksi.Kd_Sub = a.Kd_Sub AND koreksi.Kd_Prog = a.Kd_Prog AND koreksi.ID_Prog = a.ID_Prog AND koreksi.Kd_Keg = a.Kd_Keg
			LEFT JOIN
			(
				/* Jurnal BLUD / FKTP */
				SELECT
					Ta_Jurnal_Rinc.Kd_Urusan,
					Ta_Jurnal_Rinc.Kd_Bidang,
					Ta_Jurnal_Rinc.Kd_Unit,
					Ta_Jurnal_Rinc.Kd_Sub,
					Ta_Jurnal_Rinc.Kd_Prog,
					Ta_Jurnal_Rinc.ID_Prog,
					Ta_Jurnal_Rinc.Kd_Keg,
					SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end) AS nilai_jurnal_tw_1,
					SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-04-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end) AS nilai_jurnal_tw_2,
					SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-07-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end) AS nilai_jurnal_tw_3,
					SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-10-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end) AS nilai_jurnal_tw_4
				FROM
					Ta_Jurnal_Rinc
				INNER JOIN Ta_Jurnal ON Ta_Jurnal_Rinc.Tahun = Ta_Jurnal.Tahun AND Ta_Jurnal_Rinc.No_Bukti = Ta_Jurnal.No_Bukti
				WHERE
					(Ta_Jurnal_Rinc.Kd_Urusan = 1) AND (Ta_Jurnal_Rinc.Kd_Bidang = 2) AND (Ta_Jurnal_Rinc.Kd_Rek_1 = 5) AND (Ta_Jurnal_Rinc.Kd_Prog > 0)
				GROUP BY
					Ta_Jurnal_Rinc.Kd_Urusan, Ta_Jurnal_Rinc.Kd_Bidang, Ta_Jurnal_Rinc.Kd_Unit, Ta_Jurnal_Rinc.Kd_Sub, Ta_Jurnal_Rinc.Kd_Prog, Ta_Jurnal_Rinc.ID_Prog, Ta_Jurnal_Rinc.Kd_Keg
			) AS jurnal ON
				jurnal.Kd_Urusan = a.Kd_Urusan AND jurnal.Kd_Bidang = a.Kd_Bidang AND jurnal.Kd_Unit = a.Kd_Unit AND jurnal.Kd_Sub = a.Kd_Sub AND jurnal.Kd_Prog = a.Kd_Prog AND jurnal.ID_Prog = a.ID_Prog AND jurnal.Kd_Keg = a.Kd_Keg
			WHERE
				a.tahun = ' . $tahun . ' AND 
				a.Kd_Urusan LIKE ' . $kode_urusan . ' AND 
				a.Kd_Bidang LIKE ' . $kode_bidang . ' AND 
				a.Kd_Unit LIKE ' . $kode_unit . ' AND
				a.Kd_Prog > 0
		')
		->result_array();
		//print_r($tmp_realisasi_keuangan);exit;
		
		// create temporary table
		$create_table						= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__realisasi_keuangan
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Unit INT(5),
				Kd_Sub INT(5),
				ID_Prog INT(5),
				Kd_Prog INT(5),
				Kd_Keg INT(5),
				nilai_realisasi_uang_tw_1 DECIMAL(19,2),
				nilai_realisasi_uang_tw_2 DECIMAL(19,2),
				nilai_realisasi_uang_tw_3 DECIMAL(19,2),
				nilai_realisasi_uang_tw_4 DECIMAL(19,2)
			)
		');
		
		if($tmp_realisasi_keuangan)
		{
			/* insert realisasi ke temporary table */
			$this->db->insert_batch('tmp__realisasi_keuangan', $tmp_realisasi_keuangan, sizeof($tmp_realisasi_keuangan));
		}
		$data										= $this->db->query
		('
			SELECT
				ref__bidang.id AS id_bidang,
				ref__bidang.nm_bidang,
				ta__program.id AS id_prog,
				ref__program.nm_program,
				/* ta__program_capaian.tolak_ukur AS tolak_ukur_program,
				ta__program_capaian.tahun_1_target,
				ta__program_capaian.tahun_1_satuan, */
				ta__kegiatan_arsip.kode_urusan,
				ta__kegiatan_arsip.kode_bidang,
				ta__kegiatan_arsip.kode_unit,
				ta__kegiatan_arsip.kode_sub,
				ta__kegiatan_arsip.kode_prog,
				ta__kegiatan_arsip.kode_id_prog,
				ta__kegiatan_arsip.kode_keg,
				ta__kegiatan_arsip.kegiatan,
				ta__kegiatan_arsip.pagu,
				ta__indikator.jns_indikator,
				ta__indikator.tolak_ukur,
				ta__indikator.target,
				ta__indikator.satuan,
				ta__monev_realisasi_indikator.triwulan_1 AS realisasi_indikator_triwulan_1,
				ta__monev_realisasi_indikator.triwulan_2 AS realisasi_indikator_triwulan_2,
				ta__monev_realisasi_indikator.triwulan_3 AS realisasi_indikator_triwulan_3,
				ta__monev_realisasi_indikator.triwulan_4 AS realisasi_indikator_triwulan_4,
				ref__unit.nm_unit,
				tmp__realisasi_keuangan.nilai_realisasi_uang_tw_1,
				tmp__realisasi_keuangan.nilai_realisasi_uang_tw_2,
				tmp__realisasi_keuangan.nilai_realisasi_uang_tw_3,
				tmp__realisasi_keuangan.nilai_realisasi_uang_tw_4
			FROM
				ta__kegiatan_arsip
			INNER JOIN ref__unit ON ta__kegiatan_arsip.id_unit = ref__unit.id
			INNER JOIN ta__indikator ON ta__kegiatan_arsip.id_keg = ta__indikator.id_keg
			LEFT JOIN ta__monev_realisasi_indikator ON ta__monev_realisasi_indikator.id_indikator = ta__indikator.id
			INNER JOIN ta__program ON ta__program.id = ta__kegiatan_arsip.id_program
			/* INNER JOIN ta__program_capaian ON ta__program_capaian.id_prog = ta__program.id */
			INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
			INNER JOIN ref__bidang ON ref__bidang.id = ref__program.id_bidang
			LEFT JOIN tmp__realisasi_keuangan ON
				tmp__realisasi_keuangan.Kd_Urusan		= ta__kegiatan_arsip.kode_urusan AND
				tmp__realisasi_keuangan.Kd_Bidang		= ta__kegiatan_arsip.kode_bidang AND
				tmp__realisasi_keuangan.Kd_Unit			= ta__kegiatan_arsip.kode_unit AND
				tmp__realisasi_keuangan.Kd_Sub			= ta__kegiatan_arsip.kode_sub AND
				tmp__realisasi_keuangan.Kd_Prog			= ta__kegiatan_arsip.kode_prog AND
				tmp__realisasi_keuangan.ID_Prog			= ta__kegiatan_arsip.kode_id_prog AND
				tmp__realisasi_keuangan.Kd_Keg			= ta__kegiatan_arsip.kode_keg
			WHERE
				ta__kegiatan_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_arsip) AND
				ta__kegiatan_arsip.kode_urusan LIKE ' . $kode_urusan . ' AND
				ta__kegiatan_arsip.kode_bidang LIKE ' . $kode_bidang . ' AND
				ta__kegiatan_arsip.kode_unit LIKE ' . $kode_unit . '
			ORDER BY
				ta__kegiatan_arsip.kode_urusan ASC,
				ta__kegiatan_arsip.kode_bidang ASC,
				ta__kegiatan_arsip.kode_unit ASC,
				ta__kegiatan_arsip.kode_sub ASC,
				ta__kegiatan_arsip.kode_prog ASC,
				ta__kegiatan_arsip.kode_id_prog ASC,
				ta__kegiatan_arsip.kode_keg ASC,
				ta__indikator.jns_indikator ASC,
				ta__indikator.kd_indikator ASC
		')
		->result();
		
		$output										= array
		(
			'bappeda'								=> $bappeda,
			'header'								=> $header,
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function target_realisasi_urusan($tahun = null)
	{
		$tw											= $this->input->get('triwulan');
		$kode_murni									= 8;
		$kode_perubahan								= 20;
			
		$tmp_rencana_keuangan						= $this->_connector()->query
		('
			SELECT
				Tahun,
				Kd_Perubahan,
				Kd_Urusan,
				Kd_Bidang,
				CASE
					WHEN ' . $tw . ' = 1 THEN SUM(Jan + Feb + Mar)
					WHEN ' . $tw . ' = 2 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun)
					WHEN ' . $tw . ' = 3 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep)
					WHEN ' . $tw . ' = 4 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep + Okt + Nop + Des)
				END nilai_rencana_uang
			FROM
				Ta_Rencana_Arsip
			GROUP BY
				Tahun,
				Kd_Perubahan,
				Kd_Urusan,
				Kd_Bidang
			HAVING
				Tahun = ' . $tahun . ' AND 
				Kd_Perubahan = (SELECT	MAX(Kd_Perubahan) FROM Ta_Rencana_Arsip)
		')
		->result_array();
		//print_r($tmp_rencana_keuangan);exit;
			
		// create temporary table
		$create_table						= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rencana_keuangan
			(
				Tahun YEAR,
				Kd_Perubahan INT(5),
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				nilai_rencana_uang DECIMAL(19,2)
			)
		');
		
		if($tmp_rencana_keuangan)
		{
			/* insert realisasi ke temporary table */
			$this->db->insert_batch('tmp__rencana_keuangan', $tmp_rencana_keuangan, sizeof($tmp_rencana_keuangan));
		}
		
		$tmp_realisasi_keuangan						= $this->_connector()->query
		('
			SELECT
				a.Kd_Urusan,
				a.Kd_Bidang,
				(
					COALESCE(sp2d.nilai_sp2d, 0) - COALESCE(penyesuaian.nilai_penyesuaian, 0) + COALESCE(koreksi.nilai_debet, 0) - COALESCE(koreksi.nilai_kredit, 0) + COALESCE(jurnal.nilai_jurnal, 0)
				) AS nilai_realisasi_uang
			FROM
				Ref_Bidang AS a
			LEFT JOIN
			(
				/* SPM dan SPD */
				SELECT
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
					END nilai_sp2d
				FROM
					Ta_SPM_Rinc 
				INNER JOIN Ta_SPM ON Ta_SPM_Rinc.Tahun = Ta_SPM.Tahun AND Ta_SPM_Rinc.No_SPM = Ta_SPM.No_SPM 
				INNER JOIN Ta_SP2D ON Ta_SPM.Tahun = Ta_SP2D.Tahun AND Ta_SPM.No_SPM = Ta_SP2D.No_SPM
				WHERE
					(NOT (Ta_SP2D.No_SP2D IS NULL)) AND								
					(Ta_SPM.Jn_SPM <> 1) AND
					(Ta_SPM.Jn_SPM <> 4) AND
					(Ta_SPM_Rinc.Kd_Rek_1 <> 6)
				GROUP BY
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang
			) AS sp2d ON sp2d.Kd_Urusan = a.Kd_Urusan AND sp2d.Kd_Bidang = a.Kd_Bidang 
			LEFT JOIN
			(
				/* Penyesuaian */
				SELECT
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
					END nilai_penyesuaian
				FROM
					Ta_Penyesuaian 
				INNER JOIN
					Ta_Penyesuaian_Rinc ON Ta_Penyesuaian.Tahun = Ta_Penyesuaian_Rinc.Tahun AND Ta_Penyesuaian.No_Bukti = Ta_Penyesuaian_Rinc.No_Bukti
				WHERE
					(Ta_Penyesuaian_Rinc.D_K = \'K\') AND
					(Ta_Penyesuaian.Jns_P1 = 1) AND
					(Ta_Penyesuaian.Jns_P2 = 3)AND
					(Ta_Penyesuaian.Tahun = ' . $tahun . ')
				GROUP BY
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang
			) AS penyesuaian ON penyesuaian.Kd_Urusan = a.Kd_Urusan AND penyesuaian.Kd_Bidang = a.Kd_Bidang
			LEFT JOIN
			(
				/* Jurnal Koreksi */
				SELECT
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					SUM(Ta_JurnalSemua_Rinc.Debet) AS debet, 
					SUM(Ta_JurnalSemua_Rinc.Kredit) AS kredit,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
					END nilai_debet,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
					END nilai_kredit
				FROM
					Ta_JurnalSemua
				INNER JOIN Ta_JurnalSemua_Rinc ON
					Ta_JurnalSemua.Tahun = Ta_JurnalSemua_Rinc.Tahun AND Ta_JurnalSemua.Kd_Source = Ta_JurnalSemua_Rinc.Kd_Source AND Ta_JurnalSemua.No_Bukti = Ta_JurnalSemua_Rinc.No_Bukti
				WHERE
					(Ta_JurnalSemua_Rinc.Kd_Jurnal = 5) AND (Ta_JurnalSemua_Rinc.Kd_Rek_1 = 5) AND (Ta_JurnalSemua_Rinc.Tahun = ' . $tahun . ')
				GROUP BY
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang
			) AS koreksi ON koreksi.Kd_Urusan = a.Kd_Urusan AND koreksi.Kd_Bidang = a.Kd_Bidang 
			LEFT JOIN
			(
				/* Jurnal BLUD / FKTP */
				SELECT
					Ta_Jurnal_Rinc.Kd_Urusan,
					Ta_Jurnal_Rinc.Kd_Bidang,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end)
					END nilai_jurnal
				FROM
					Ta_Jurnal_Rinc
				INNER JOIN
					Ta_Jurnal ON Ta_Jurnal_Rinc.Tahun = Ta_Jurnal.Tahun AND Ta_Jurnal_Rinc.No_Bukti = Ta_Jurnal.No_Bukti
				WHERE
					(Ta_Jurnal_Rinc.Kd_Urusan = 1) AND (Ta_Jurnal_Rinc.Kd_Bidang = 2) AND (Ta_Jurnal_Rinc.Kd_Rek_1 = 5) AND (Ta_Jurnal_Rinc.Kd_Prog > 0)
				GROUP BY
					Ta_Jurnal_Rinc.Kd_Urusan, Ta_Jurnal_Rinc.Kd_Bidang
			) AS jurnal ON jurnal.Kd_Urusan = a.Kd_Urusan AND jurnal.Kd_Bidang = a.Kd_Bidang 
		')
		->result_array();
		//print_r($tmp_realisasi_keuangan);exit;
		
		// create temporary table
		$create_table						= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__realisasi_keuangan
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Unit INT(5),
				Kd_Sub INT(5),
				ID_Prog INT(5),
				Kd_Prog INT(5),
				Kd_Keg INT(5),
				nilai_realisasi_uang DECIMAL(19,2)
			)
		');
		
		if($tmp_realisasi_keuangan)
		{
			/* insert realisasi ke temporary table */
			$this->db->insert_batch('tmp__realisasi_keuangan', $tmp_realisasi_keuangan, sizeof($tmp_realisasi_keuangan));
		}
			
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__bidang.nm_bidang,
				kegiatan_arsip.pagu,
				tmp__rencana_keuangan.nilai_rencana_uang,
				tmp__realisasi_keuangan.nilai_realisasi_uang
			FROM
				ref__bidang
			INNER JOIN ref__urusan ON ref__urusan.id = ref__bidang.id_urusan
			LEFT JOIN (
				SELECT
					ta__kegiatan_arsip.id_bidang,
					SUM(ta__kegiatan_arsip.pagu) AS pagu
				FROM
					ta__kegiatan_arsip
				WHERE
					ta__kegiatan_arsip.kode_perubahan = (SELECT MAX(kode_perubahan) FROM ta__kegiatan_arsip)
				GROUP BY
					ta__kegiatan_arsip.kode_id_prog
			) AS kegiatan_arsip ON kegiatan_arsip.id_bidang = ref__bidang.id
			LEFT JOIN tmp__rencana_keuangan ON 
				tmp__rencana_keuangan.Kd_Urusan = ref__urusan.kd_urusan AND
				tmp__rencana_keuangan.Kd_Bidang = ref__bidang.kd_bidang
			LEFT JOIN tmp__realisasi_keuangan ON 
				tmp__realisasi_keuangan.Kd_Urusan = ref__urusan.kd_urusan AND
				tmp__realisasi_keuangan.Kd_Bidang = ref__bidang.kd_bidang
			WHERE
				ref__urusan.tahun = ' . $tahun . '
			GROUP BY
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC
		')
		->result();
		$output										= array
		(
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function target_realisasi_program($tahun = null)
	{
		$tw											= $this->input->get('triwulan');
		$kode_murni									= 8;
		$kode_perubahan								= 20;
		
		$tmp_rencana_keuangan						= $this->_connector()->query
		('
			SELECT
				Tahun,
				Kd_Perubahan,
				Kd_Urusan,
				Kd_Bidang,
				Kd_Prog,
				CASE
					WHEN ' . $tw . ' = 1 THEN SUM(Jan + Feb + Mar)
					WHEN ' . $tw . ' = 2 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun)
					WHEN ' . $tw . ' = 3 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep)
					WHEN ' . $tw . ' = 4 THEN SUM(Jan + Feb + Mar + Apr + Mei + Jun + Jul + Agt + Sep + Okt + Nop + Des)
				END nilai_rencana_uang
			FROM
				Ta_Rencana_Arsip
			GROUP BY
				Tahun,
				Kd_Perubahan,
				Kd_Urusan,
				Kd_Bidang,
				Kd_Prog
			HAVING
				Tahun = ' . $tahun . ' AND 
				Kd_Perubahan = (SELECT	MAX(Kd_Perubahan) FROM Ta_Rencana_Arsip) 
		')
		->result_array();
		//print_r($tmp_rencana_keuangan);exit;
			
		// create temporary table
		$create_table						= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__rencana_keuangan
			(
				Tahun YEAR,
				Kd_Perubahan INT(5),
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Prog INT(5),
				nilai_rencana_uang DECIMAL(19,2)
			)
		');
		
		if($tmp_rencana_keuangan)
		{
			/* insert realisasi ke temporary table */
			$this->db->insert_batch('tmp__rencana_keuangan', $tmp_rencana_keuangan, sizeof($tmp_rencana_keuangan));
		}
		
		$tmp_realisasi_keuangan						= $this->_connector()->query
		('
			SELECT
				a.Kd_Urusan,
				a.Kd_Bidang,
				a.Kd_Prog,
				(COALESCE(sp2d.nilai_sp2d, 0) - COALESCE(penyesuaian.nilai_penyesuaian, 0) + COALESCE(koreksi.nilai_debet, 0) - COALESCE(koreksi.nilai_kredit, 0) + COALESCE(jurnal.nilai_jurnal, 0)
				) AS nilai_realisasi_uang
			FROM
				Ref_Program AS a
			LEFT JOIN
			(
				/* SPM dan SPD */
				SELECT
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					Ta_SPM_Rinc.Kd_Prog,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_SP2D.Tgl_SP2D BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_SPM_Rinc.Nilai else 0 end)
					END nilai_sp2d
				FROM
					Ta_SPM_Rinc 
				INNER JOIN Ta_SPM ON Ta_SPM_Rinc.Tahun = Ta_SPM.Tahun AND Ta_SPM_Rinc.No_SPM = Ta_SPM.No_SPM 
				INNER JOIN Ta_SP2D ON Ta_SPM.Tahun = Ta_SP2D.Tahun AND Ta_SPM.No_SPM = Ta_SP2D.No_SPM
				WHERE
					(NOT (Ta_SP2D.No_SP2D IS NULL)) AND								
					(Ta_SPM.Jn_SPM <> 1) AND
					(Ta_SPM.Jn_SPM <> 4) AND
					(Ta_SPM_Rinc.Kd_Rek_1 <> 6)
				GROUP BY
					Ta_SPM_Rinc.Kd_Urusan,
					Ta_SPM_Rinc.Kd_Bidang,
					Ta_SPM_Rinc.Kd_Prog
			) AS sp2d ON
				sp2d.Kd_Urusan = a.Kd_Urusan AND sp2d.Kd_Bidang = a.Kd_Bidang AND sp2d.Kd_Prog = a.Kd_Prog
			LEFT JOIN
			(
				/* Penyesuaian */
				SELECT
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					Ta_Penyesuaian_Rinc.Kd_Prog,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_Penyesuaian.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_Penyesuaian_Rinc.Nilai else 0 end)
					END nilai_penyesuaian
				FROM
					Ta_Penyesuaian 
				INNER JOIN
					Ta_Penyesuaian_Rinc ON Ta_Penyesuaian.Tahun = Ta_Penyesuaian_Rinc.Tahun AND Ta_Penyesuaian.No_Bukti = Ta_Penyesuaian_Rinc.No_Bukti
				WHERE
					(Ta_Penyesuaian_Rinc.D_K = \'K\') AND
					(Ta_Penyesuaian.Jns_P1 = 1) AND
					(Ta_Penyesuaian.Jns_P2 = 3)AND
					(Ta_Penyesuaian.Tahun = ' . $tahun . ')
				GROUP BY
					Ta_Penyesuaian_Rinc.Kd_Urusan,
					Ta_Penyesuaian_Rinc.Kd_Bidang,
					Ta_Penyesuaian_Rinc.Kd_Prog
			) AS penyesuaian ON 
				penyesuaian.Kd_Urusan = a.Kd_Urusan AND penyesuaian.Kd_Bidang = a.Kd_Bidang AND penyesuaian.Kd_Prog = a.Kd_Prog
			LEFT JOIN
			(
				/* Jurnal Koreksi */
				SELECT
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					Ta_JurnalSemua_Rinc.Kd_Prog,
					SUM(Ta_JurnalSemua_Rinc.Debet) AS debet, 
					SUM(Ta_JurnalSemua_Rinc.Kredit) AS kredit,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Debet else 0 end)
					END nilai_debet,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_JurnalSemua.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_JurnalSemua_Rinc.Kredit else 0 end)
					END nilai_kredit
				FROM
					Ta_JurnalSemua
				INNER JOIN Ta_JurnalSemua_Rinc ON
					Ta_JurnalSemua.Tahun = Ta_JurnalSemua_Rinc.Tahun AND Ta_JurnalSemua.Kd_Source = Ta_JurnalSemua_Rinc.Kd_Source AND Ta_JurnalSemua.No_Bukti = Ta_JurnalSemua_Rinc.No_Bukti
				WHERE
					(Ta_JurnalSemua_Rinc.Kd_Jurnal = 5) AND (Ta_JurnalSemua_Rinc.Kd_Rek_1 = 5) AND (Ta_JurnalSemua_Rinc.Tahun = ' . $tahun . ')
				GROUP BY
					Ta_JurnalSemua.Kd_Urusan,
					Ta_JurnalSemua.Kd_Bidang,
					Ta_JurnalSemua_Rinc.Kd_Prog
			) AS koreksi ON
				koreksi.Kd_Urusan = a.Kd_Urusan AND koreksi.Kd_Bidang = a.Kd_Bidang AND koreksi.Kd_Prog = a.Kd_Prog
			LEFT JOIN
			(
				/* Jurnal BLUD / FKTP */
				SELECT
					Ta_Jurnal_Rinc.Kd_Urusan,
					Ta_Jurnal_Rinc.Kd_Bidang,
					Ta_Jurnal_Rinc.Kd_Prog,
					CASE
						WHEN ' . $tw . ' = 1 THEN SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-03-31\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 2 THEN SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-06-30\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 3 THEN SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-09-30\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end)
						WHEN ' . $tw . ' = 4 THEN SUM(case when Ta_Jurnal.Tgl_Bukti BETWEEN CONVERT(DATETIME, \'' . $tahun . '-01-01\', 102) AND CONVERT(DATETIME, \'' . $tahun . '-12-31\', 102) THEN Ta_Jurnal_Rinc.Nilai else 0 end)
					END nilai_jurnal
				FROM
					Ta_Jurnal_Rinc
				INNER JOIN Ta_Jurnal ON Ta_Jurnal_Rinc.Tahun = Ta_Jurnal.Tahun AND Ta_Jurnal_Rinc.No_Bukti = Ta_Jurnal.No_Bukti
				WHERE
					(Ta_Jurnal_Rinc.Kd_Urusan = 1) AND (Ta_Jurnal_Rinc.Kd_Bidang = 2) AND (Ta_Jurnal_Rinc.Kd_Rek_1 = 5) AND (Ta_Jurnal_Rinc.Kd_Prog > 0)
				GROUP BY
					Ta_Jurnal_Rinc.Kd_Urusan, Ta_Jurnal_Rinc.Kd_Bidang, Ta_Jurnal_Rinc.Kd_Prog
			) AS jurnal ON
				jurnal.Kd_Urusan = a.Kd_Urusan AND jurnal.Kd_Bidang = a.Kd_Bidang AND jurnal.Kd_Prog = a.Kd_Prog 
		')
		->result_array();
		//print_r($tmp_realisasi_keuangan);exit;
		
		// create temporary table
		$create_table						= $this->db->query
		('
			CREATE TEMPORARY TABLE tmp__realisasi_keuangan
			(
				Tahun YEAR,
				Kd_Urusan INT(5),
				Kd_Bidang INT(5),
				Kd_Prog INT(5),
				nilai_realisasi_uang DECIMAL(19,2)
			)
		');
		
		if($tmp_realisasi_keuangan)
		{
			/* insert realisasi ke temporary table */
			$this->db->insert_batch('tmp__realisasi_keuangan', $tmp_realisasi_keuangan, sizeof($tmp_realisasi_keuangan));
		}
		$data										= $this->db->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__program.kd_program,
				ref__program.nm_program,
				kegiatan_arsip.pagu,
				tmp__rencana_keuangan.nilai_rencana_uang,
				tmp__realisasi_keuangan.nilai_realisasi_uang
			FROM
				ref__program
			INNER JOIN ref__bidang ON ref__bidang.id = ref__program.id_bidang
			INNER JOIN ref__urusan ON ref__urusan.id = ref__bidang.id_urusan
			LEFT JOIN (
				SELECT
					ta__kegiatan_arsip.id_prog,
					SUM(ta__kegiatan_arsip.pagu) AS pagu
				FROM
					ta__kegiatan_arsip
				WHERE
					ta__kegiatan_arsip.kode_perubahan = ' . $kode_perubahan . '
				GROUP BY
					ta__kegiatan_arsip.id_prog
			) AS kegiatan_arsip ON kegiatan_arsip.id_prog = ref__program.id
			LEFT JOIN tmp__rencana_keuangan ON 
				tmp__rencana_keuangan.Kd_Urusan 	= ref__urusan.kd_urusan AND
				tmp__rencana_keuangan.Kd_Bidang 	= ref__bidang.kd_bidang AND
				tmp__rencana_keuangan.Kd_Prog 		= ref__program.kd_program
			LEFT JOIN tmp__realisasi_keuangan ON 
				tmp__realisasi_keuangan.Kd_Urusan 	= ref__urusan.kd_urusan AND
				tmp__realisasi_keuangan.Kd_Bidang 	= ref__bidang.kd_bidang AND
				tmp__realisasi_keuangan.Kd_Prog 	= ref__program.kd_program
			WHERE
				ref__urusan.tahun = ' . $tahun . '
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__program.kd_program ASC
		')
		->result();
		$output										= array
		(
			'data'									=> $data
		);
		//print_r($output);exit;
		return $output;
	}
	
	private function _connector()
	{
		/* get sql server connection */
		$connection									= $this->db->get_where('ref__koneksi', array('tahun' => get_userdata('year')), 1)->row();
		
		/* check if connection is found */
		if(!$connection)
		{
			/* otherwise, throw the exception */
			return false;
		}
		
		/* define config */
		$config										= array
		(
			'hostname' 								=> $this->encryption->decrypt($connection->hostname),
			'port' 									=> $this->encryption->decrypt($connection->port),
			'username'								=> $this->encryption->decrypt($connection->username),
			'password' 								=> $this->encryption->decrypt($connection->password),
			'database' 								=> $this->encryption->decrypt($connection->database_name),
			'dbdriver' 								=> $connection->database_driver,
			'db_debug'								=> true
		);
		
		/* load the new database connection with the defined config */
		return $this->load->database($config, TRUE);
	}
}