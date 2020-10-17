<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sub extends Aksara
{
	private $_table									= 'ta__belanja_sub';
	
	function __construct()
	{
		parent::__construct();
		$this->_kegiatan							= $this->input->get('kegiatan');
		$this->_sub_kegiatan						= $this->input->get('sub_kegiatan');
		$this->_belanja								= $this->input->get('belanja');
		if(!$this->_belanja)
		{
			return throw_exception(301, 'Silakan memilih Rekening terlebih dahulu', go_to('../rekening'));
		}
		elseif(!$this->_sub_kegiatan)
		{
			return throw_exception(301, 'Silakan memilih Sub Kegiatan terlebih dahulu', go_to('../renja/sub_kegiatan'));
		}
		//$this->_primary								= $this->input->get('id_bel');
		$this->set_permission();
		$this->set_theme('backend');
		
	}
	
	public function index()
	{
		$header										= $this->_header();
		$anggaran									= $this->_anggaran();
		$total_rekening								= $this->_total_rekening();
		$this->set_breadcrumb
		(
			array
			(
				'renja/kegiatan/sub_unit'						=> 'Sub Unit',
				'..'						=> 'Kegiatan',
				'../sub_kegiatan'		=> 'Sub Kegiatan',
				'../../anggaran/rekening'				=> 'Rekening'
			)
		);
		$this->set_title(phrase('sub_rincian'))
		->set_description
		('
			<div class="row">
				<div class="col-4 col-sm-2 text-muted text-sm">
					SUB UNIT
				</div>
				<div class="col-8 col-sm-6 font-weight text-sm">
					' . (isset($header->nm_sub) ?  $header->kd_urusan . '.' . $header->kd_bidang . '.' . sprintf('%02d', $header->kd_unit) . '.' . sprintf('%02d', $header->kd_sub) . ' ' . $header->nm_sub : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="col-4 col-sm-2 text-muted text-sm">
					PROGRAM
				</div>
				<div class="col-8 col-sm-10 font-weight text-sm">
					' . (isset($header->nm_program) ?  $header->kd_program . ' ' . $header->nm_program : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="col-4 col-sm-2 text-muted text-sm">
					KEGIATAN
				</div>
				<div class="col-8 col-sm-10 font-weight text-sm">
					' . (isset($header->kegiatan) ?  $header->kd_program . '.' . sprintf('%02d', $header->kd_keg) . ' ' . $header->kegiatan : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="col-4 col-sm-2 text-muted text-sm">
					SUB KEGIATAN
				</div>
				<div class="col-8 col-sm-10 font-weight text-sm">
					' . (isset($header->kegiatan_sub) ?  $header->kd_program . '.' . sprintf('%02d', $header->kd_keg) . '.' . $header->kd_keg_sub . ' ' . $header->kegiatan_sub : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="col-4 col-sm-2 text-sm text-muted text-uppercase no-margin">
					PLAFON
				</div>
				<div class="col-8 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format($header->pagu, 2) . '
					</b>
				</div>			
				<div class="col-4 col-sm-2 text-sm text-muted text-uppercase no-margin">
					ANGGARAN
				</div>
				<div class="col-8 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format((isset($anggaran) ? $anggaran : 0), 2) . '
					</b>
				</div>
				<div class="col-4 col-sm-2 text-sm text-muted text-uppercase no-margin">
					SELISIH
				</div>
				<div class="col-8 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format(($header->pagu - $anggaran), 2) . '
					</b>
				</div>
			</div>
			<div class="row border-bottom">
				<div class="col-4 col-sm-2 text-muted text-sm">
					REKENING
				</div>
				<div class="col-4 col-sm-8 font-weight text-sm">
					' . (isset($header->kd_rek_1) ?  $header->kd_rek_1 . '.' . $header->kd_rek_2 . '.' . $header->kd_rek_3 . '.' . sprintf('%02d', $header->kd_rek_4) . '.' . sprintf('%02d', $header->kd_rek_5) . '.' . sprintf('%02d', $header->kd_rek_6) . ' ' . $header->nm_rek_6 : '-') . '
				</div>
				<div class="col-4 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format(($total_rekening), 2) . '
					</b>
				</div>
			</div>
		')
		->set_icon('mdi mdi-content-save-settings-outline')
		->unset_column('id, id_belanja, tahun')
		->unset_view('id, id_belanja, tahun')
		->unset_field('id, id_belanja, tahun')
		->column_order('kd_urusan')
		->set_field('uraian', 'textarea, hyperlink', 'anggaran/rincian', array('belanja_sub' => 'id'))
		->set_field('kd_belanja_sub', 'last_insert')
		->unset_truncate('uraian')
		->add_class('uraian', 'autofocus')
		->unset_action('pdf, export, print')
		->add_action('toolbar', '../../laporan/anggaran/rka/rka_sub_kegiatan', 'Preview RKA', 'btn-success ajax', 'mdi mdi-printer-alert', array('kegiatan' => $this->_kegiatan, 'sub_kegiatan' => $this->_sub_kegiatan, 'method' => 'preview'), true)
		->add_action('toolbar', '../../laporan/anggaran/rka/rka_sub_kegiatan', 'Cetak RKA', 'btn-info ajax', 'mdi mdi-printer', array('kegiatan' => $this->_kegiatan, 'sub_kegiatan' => $this->_sub_kegiatan, 'method' => 'embed'), true)
		->set_alias
		(
			array
			(
				'kd_belanja_sub'					=> 'Kode'
			)
		)
		->set_default
		(
			array
			(
				'id_belanja'						=> $this->_belanja
			)
		)
		->set_validation
		(
			array
			(
				'kd_belanja_sub'					=> 'required|is_unique[' . $this->_table . '.kd_belanja_sub.id.' . $this->input->get('id') . '.id_belanja.' . $this->_belanja . ']',
				'uraian'							=> 'required'

			)
		)
		->where
		(
			array
			(
				'id_belanja'						=> $this->_belanja
			)
		)
		->order_by('kd_belanja_sub')
		->render($this->_table);
	}
	
	/**
	 * Cek jika item akan dihapus
	 */
	public function before_delete()
	{
		$current								= $this->model
												->select('sum(total) AS total')
												->get_where('ta__belanja_rinci',array('id_belanja_sub' => $this->input->get('id')))
												->row('total');
													
		$current_rekening							= $this->model->query
													('
														SELECT
															SUM(ta__belanja_rinci.total) AS total
														FROM
															ta__belanja_rinci
														INNER JOIN ta__belanja_sub ON ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub
														WHERE
															ta__belanja_sub.id_belanja = ' . $this->_belanja . '
													')
													->row('total');
		
		$connection									= $this->model->get_where('ref__koneksi', array('tahun' => get_userdata('year')), 1)->row();
		if($connection)
		{			
			$kode									= $this->model
			->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__program.kd_id_prog,
				ta__kegiatan.kd_keg,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6
			')
			->join
			(
				'ta__belanja',
				'ta__belanja.id = ta__belanja_sub.id_belanja'
			)
			->join
			(
				'ref__rek_6',
				'ref__rek_6.id = ta__belanja.id_rek_6'
			)
			->join
			(
				'ref__rek_5',
				'ref__rek_5.id = ref__rek_6.id_ref_rek_5'
			)
			->join
			(
				'ref__rek_4',
				'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
			)
			->join
			(
				'ref__rek_3',
				'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
			)
			->join
			(
				'ref__rek_2',
				'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
			)
			->join
			(
				'ref__rek_1',
				'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
			)
			->join
			(
				'ta__kegiatan_sub',
				'ta__kegiatan_sub.id = ta__belanja.id_keg_sub'
			)
			->join
			(
				'ta__kegiatan',
				'ta__kegiatan.id = ta__kegiatan_sub.id_keg'
			)
			->join
			(
				'ta__program',
				'ta__program.id = ta__kegiatan.id_prog'
			)
			->join
			(
				'ref__program',
				'ref__program.id = ta__program.id_prog'
			)
			->join
			(
				'ref__sub',
				'ref__sub.id = ta__program.id_sub'
			)
			->join
			(
				'ref__unit',
				'ref__unit.id = ref__sub.id_unit'
			)
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
				'ta__belanja_sub',
				array
				(
					'ta__belanja_sub.id'			=> $this->input->get('id')
				),
				1
			)
			->row();
			
			if($kode)
			{
				$params								= array
				(
					'Tahun'							=> get_userdata('year'),
					'Kd_Urusan'						=> $kode->kd_urusan,
					'Kd_Bidang'						=> $kode->kd_bidang,
					'Kd_Unit'						=> $kode->kd_unit,
					'Kd_Sub'						=> $kode->kd_sub,
					'Kd_Prog'						=> $kode->kd_program,
					'ID_Prog'						=> $kode->kd_id_prog,
					'Kd_Keg'						=> $kode->kd_keg,
					'Kd_Rek_1'						=> $kode->kd_rek_1,
					'Kd_Rek_2'						=> $kode->kd_rek_2,
					'Kd_Rek_3'						=> $kode->kd_rek_3,
					'Kd_Rek_4'						=> $kode->kd_rek_4,
					'Kd_Rek_5'						=> $kode->kd_rek_5,
					'Kd_Rek_6'						=> $kode->kd_rek_6
				);
				
				$configs							= array
				(
					'dsn'							=> '',
					'hostname' 						=> $this->encryption->decrypt($connection->hostname),
					'port' 							=> $this->encryption->decrypt($connection->port),
					'username'						=> $this->encryption->decrypt($connection->username),
					'password' 						=> $this->encryption->decrypt($connection->password),
					'database' 						=> $this->encryption->decrypt($connection->database_name),
					'dbdriver' 						=> $connection->database_driver,
					'dbprefix' 						=> '',
					'pconnect' 						=> FALSE,
					'db_debug' 						=> false,
					'cache_on' 						=> FALSE,
					'cachedir' 						=> '',
					'char_set' 						=> 'utf8',
					'dbcollat' 						=> 'utf8_unicode_ci',
					'swap_pre' 						=> '',
					'encrypt' 						=> FALSE,
					'compress' 						=> FALSE,
					'stricton' 						=> FALSE,
					'failover' 						=> array(),
					'save_queries' 					=> TRUE
				);
				
				$db									= $this->load->database($configs, true);
				
				$cek_realisasi						= $db->query
				(
					'BEGIN SET NOCOUNT ON EXEC SP_Cek_Realisasi ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ? END',
					$params
				);
				
				if($cek_realisasi)
				{
					$cek_realisasi					= $cek_realisasi->row('Saldo');
				}
				else
				{
					$cek_realisasi					= 0;
				}
				
				if(($current_rekening - $current) < $cek_realisasi)
				{
					return generateMessages(403, 'Total rekening tidak boleh lebih kecil dari yang sudah direalisasikan sebesar <b>Rp.' . number_format($current) . '</b>');
				}
			}
			else
			{
				return generateMessages(404, 'Tidak dapat melakukan validasi untuk item yang akan dihapus');
			}
		}
		else
		{
			return generateMessages(404, 'Tidak dapat melakukan validasi, koneksi SIMDA tidak ditemukan');
		}
	}
	
	private function _header()
	{
		$query											= $this->model->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__kegiatan.kd_keg,
				ta__kegiatan_sub.kd_keg_sub,
				ref__unit.nm_unit,
				ref__sub.nm_sub,
				ref__program.nm_program,
				ta__kegiatan.kegiatan,
				ta__kegiatan_sub.kegiatan_sub,
				ta__kegiatan_sub.pagu,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ref__rek_6.uraian AS nm_rek_6
			FROM
				ta__belanja
			INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ta__belanja.id = ' . $this->_belanja . '
			LIMIT 1
		')
		->row();
		
		return $query;
	}
	
	private function _anggaran()
	{
		$query										= $this->model->query
		('
			SELECT
				Sum(ta__belanja_rinci.total) AS anggaran
			FROM
				ta__belanja_rinci
			INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			WHERE
				ta__belanja.id_keg_sub = ' . $this->_sub_kegiatan . '
			LIMIT 1
		')
		->row('anggaran');
		return $query;
	}
	
	private function _total_rekening()
	{
		$query										= $this->model->query
		('
			SELECT
				SUM(ta__belanja_rinci.total) AS rekening
			FROM
				ta__belanja_rinci
			INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
			WHERE
				ta__belanja_sub.id_belanja = ' . $this->_belanja . '
			LIMIT 1
		')
		->row('rekening');
		return $query;
	}
}