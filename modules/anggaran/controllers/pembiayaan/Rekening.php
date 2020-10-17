<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Rekening extends Aksara
{
	private $_table									= 'ta__anggaran_pembiayaan';
	
	function __construct()
	{
		parent::__construct();
		$this->_id									= $this->input->get('id');
		$this->_sub_unit							= $this->input->get('sub_unit');
		$this->_unit								= $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_sub_unit), 1)->row('id_unit');
		if(!$this->_sub_unit)
		{
			return throw_exception(301, 'silakan memilih Sub Unit terlebih dahulu', go_to('../../anggaran/pembiayaan/sub_unit'));
		}
		$this->set_permission();
		$this->set_theme('backend');
		/*$locked										= $this->model->select('lock_kegiatan')->get_where('ta__kegiatan_sub', array('id' => $this->_primary), 1)->row('lock_kegiatan');
		if($locked)
		{
			generateMessages(403, 'Anda tidak dapat menghapus atau memodifikasi kegiatan yang telah dikunci!', current_page('../../renja/kegiatan/data'));
		}*/
		
		if('program' == $this->input->post('method'))
		{
			return $this->_keterangan_rekening($this->input->post('id'));
		}
		elseif('sumber_dana' == $this->input->post('method'))
		{
			return $this->_sumber_dana($this->input->post('id'));
		}
	}
	
	public function index()
	{
		$header										= $this->_header();
		$anggaran									= $this->_anggaran();
		
		$this->set_breadcrumb
		(
			array
			(
				'anggaran/pembiayaan/sub_unit'		=> 'Sub Unit'
			)
		);
		$this->set_title('Rekening Pembiayaan')
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
			<div class="row border-bottom">
				<div class="col-4 col-sm-2 text-sm text-muted text-uppercase no-margin">
					ANGGARAN
				</div>
				<div class="col-8 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format((isset($anggaran) ? $anggaran : 0), 2) . '
					</b>
				</div>
			</div>
		');
		
		$this
		->set_icon('mdi mdi-deviantart')
		//->add_action('option', '../rencana_keuangan', phrase('rencana_keuangan'), 'btn-success ajax', 'fa fa-money', array('id_bel' => 'id'))
		//->add_action('toolbar', '../../laporan/anggaran/rka/rka_sub_kegiatan', 'Preview RKA', 'btn-success ajax', 'mdi mdi-printer-alert', array('kegiatan' => $this->_kegiatan, 'sub_kegiatan' => $this->_primary, 'method' => 'preview'), true)
		//->add_action('toolbar', '../../laporan/anggaran/rka/rka_sub_kegiatan', 'Cetak RKA', 'btn-info ajax', 'mdi mdi-printer', array('kegiatan' => $this->_kegiatan, 'sub_kegiatan' => $this->_primary, 'method' => 'embed'), true)
		//->add_action('toolbar', '../../laporan/anggaran/rka/anggaran_kas', 'Cetak Anggaran Kas', 'btn-danger ajax', 'mdi mdi-printer', array('sub_kegiatan' => $this->_primary, 'method' => 'embed'), true)
		->add_action('option', '../rencana_keuangan', 'Rencana', 'btn-success --modal', 'mdi mdi-cash-usd', array('pembiayaan' => 'id'))
		->add_class
		(
			array
			(
				'id_rek_6'							=> 'program',
				'id_sumber_dana'					=> 'sumber_dana'
			)
		)
		
		->unset_column('id, kode, nama_sumber_dana, id_sub, tahun, kd_sumber_dana_rek_1, kd_sumber_dana_rek_2, kd_sumber_dana_rek_3, kd_sumber_dana_rek_4, kd_sumber_dana_rek_5')
		->unset_view('id, id_sub, tahun')
		->unset_field('id, id_sub, tahun')
		->unset_truncate('uraian')
		->unset_action('pdf, export, print')
		
		->column_order('kd_rek_1, uraian, nama_sumber_dana')
		->field_order('id_rek_6, id_sumber_dana')
		->merge_content('<b>{kd_rek_1}.{kd_rek_2}.{kd_rek_3}.{kd_rek_4}.{kd_rek_5}.{kd_rek_6}</b>', 'Kode')
		->set_field('uraian', 'hyperlink', 'anggaran/pembiayaan/rincian', array('pembiayaan' => 'id'))
		->set_alias
		(
			array
			(
				'id_rek_6'							=> 'Rekening',
				'id_sumber_dana'					=> 'Sumber Dana',
				'tw_1'								=> 'TW I',
				'tw_2'								=> 'TW II',
				'tw_3'								=> 'TW III',
				'tw_4'								=> 'TW IV'
			)
		)
		->set_relation
		(
			'id_sumber_dana',
			'ref__sumber_dana_rek_6.id',
			'{ref__sumber_dana_rek_1.kd_sumber_dana_rek_1}.{ref__sumber_dana_rek_2.kd_sumber_dana_rek_2}.{ref__sumber_dana_rek_3.kd_sumber_dana_rek_3}.{ref__sumber_dana_rek_4.kd_sumber_dana_rek_4}.{ref__sumber_dana_rek_5.kd_sumber_dana_rek_5}.{ref__sumber_dana_rek_6.kode}. {ref__sumber_dana_rek_6.nama_sumber_dana}',
			array
			(
				'ref__sumber_dana_rek_6.tahun'			=> get_userdata('year')
			),
			array
			(
				array
				(
					'ref__sumber_dana_rek_5',
					'ref__sumber_dana_rek_5.id = ref__sumber_dana_rek_6.id_sumber_dana_rek_5'
				),
				array
				(
					'ref__sumber_dana_rek_4',
					'ref__sumber_dana_rek_4.id = ref__sumber_dana_rek_5.id_sumber_dana_rek_4'
				),
				array
				(
					'ref__sumber_dana_rek_3',
					'ref__sumber_dana_rek_3.id = ref__sumber_dana_rek_4.id_sumber_dana_rek_3'
				),
				array
				(
					'ref__sumber_dana_rek_2',
					'ref__sumber_dana_rek_2.id = ref__sumber_dana_rek_3.id_sumber_dana_rek_2'
				),
				array
				(
					'ref__sumber_dana_rek_1',
					'ref__sumber_dana_rek_1.id = ref__sumber_dana_rek_2.id_sumber_dana_rek_1'
				)
			),
			array
			(
				'ref__sumber_dana_rek_1.kd_sumber_dana_rek_1'	=> 'ASC',
				'ref__sumber_dana_rek_2.kd_sumber_dana_rek_2'	=> 'ASC',
				'ref__sumber_dana_rek_3.kd_sumber_dana_rek_3'	=> 'ASC',
				'ref__sumber_dana_rek_4.kd_sumber_dana_rek_4'	=> 'ASC',
				'ref__sumber_dana_rek_5.kd_sumber_dana_rek_5'	=> 'ASC',
				'ref__sumber_dana_rek_6.kode'					=> 'ASC'
			)
		)
		->set_relation
		(
			'id_rek_6',
			'ref__rek_6.id',
			'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5}.{ref__rek_6.kd_rek_6}. {ref__rek_6.uraian}',
			array
			(
				'ref__rek_1.kd_rek_1'				=> 6,
				'ref__rek_6.tahun'					=> get_userdata('year')
			),
			array
			(
				array
				(
					'ref__rek_5',
					'ref__rek_5.id = ref__rek_6.id_ref_rek_5'
				),
				array
				(
					'ref__rek_4',
					'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
				),
				array
				(
					'ref__rek_3',
					'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
				),
				array
				(
					'ref__rek_2',
					'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
				),
				array
				(
					'ref__rek_1',
					'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
				)
			),
			array
			(
				'ref__rek_1.kd_rek_1'				=> 'ASC',
				'ref__rek_2.kd_rek_2'				=> 'ASC',
				'ref__rek_3.kd_rek_3'				=> 'ASC',
				'ref__rek_4.kd_rek_4'				=> 'ASC',
				'ref__rek_5.kd_rek_5'				=> 'ASC',
				'ref__rek_6.kd_rek_6'				=> 'ASC'
			)
		)
		->set_default
		(
			array
			(
				'id_sub'							=> $this->_sub_unit,
				'tahun'								=> get_userdata('year')
			)
		)
		->set_validation
		(
			array
			(
				'id_rek_6'							=> 'required|is_unique[' . $this->_table . '.id_rek_6.id.' . $this->input->get('id') . '.id_sub.' . $this->_sub_unit . ']|callback_cek_realisasi',
				'id_sumber_dana'					=> 'required'
			)
		)
		->where
		(
			array
			(
				'id_sub'							=> $this->_sub_unit,
				'tahun'								=> get_userdata('year')
			)
		)
		->order_by('kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, kd_rek_5, kd_rek_6')
		->select
		('
			(ta__rencana_pembiayaan.jan + ta__rencana_pembiayaan.feb + ta__rencana_pembiayaan.mar) AS tw_1,
			(ta__rencana_pembiayaan.apr + ta__rencana_pembiayaan.mei + ta__rencana_pembiayaan.jun) AS tw_2,
			(ta__rencana_pembiayaan.jul + ta__rencana_pembiayaan.agt + ta__rencana_pembiayaan.sep) AS tw_3,
			(ta__rencana_pembiayaan.okt + ta__rencana_pembiayaan.nop + ta__rencana_pembiayaan.des) AS tw_4
		')
		->join('ta__rencana_pembiayaan', 'ta__rencana_pembiayaan.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id', 'left')
		->set_field
		(
			array
			(
				'tw_1'								=> 'number_format',
				'tw_2'								=> 'number_format',
				'tw_3'								=> 'number_format',
				'tw_4'								=> 'number_format'
			)
		)
		->field_position
		(
			array
			(
				'id_sumber_dana'					=> 2
			)
		)
		->render($this->_table);
	}
	
	/**
	 * Cek jika rekening diubah
	 */
	public function cek_realisasi($value = 0)
	{
		$old_rekening								= $this->model->select('id_rek_6')->get_where('ta__belanja', array('id' => $this->input->get('id')), 1)->row('id_rek_6');
		if($old_rekening != $value)
		{
			$checker								= $this->model->select('id')->get_where('ta__belanja_sub', array('id_belanja' => $this->input->get('id')), 1)->row('id');
			//print_r($value);exit;
			if($checker)
			{
				//$this->form_validation->set_message('cek_realisasi', 'Tidak dapat mengubah rekening karena sudah terdapat realisasi');
				//return false;
			}
		}
		return true;
	}
	
	/**
	 * Cek jika item akan dihapus
	 */
	/*public function before_delete()
	{
		$current									= $this->model->select
		('
			sum(total) AS total
		')
		->join
		(
			'ta__belanja_sub',
			'ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub'
		)
		->get_where
		(
			'ta__belanja_rinci',
			array
			(
				'ta__belanja_sub.id_belanja'		=> $this->input->get('id')
			)
		)
		->row('total');
													
		$current_rekening							= $this->model->query
													('
														SELECT
															SUM(ta__belanja_rinci.total) AS total
														FROM
															ta__belanja_rinci
														INNER JOIN ta__belanja_sub ON ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub
														WHERE
															ta__belanja_sub.id_belanja = ' . $this->input->get('id') . '
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
				ta__kegiatan_sub.kd_keg_sub,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6
			')
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
				'ta__belanja',
				array
				(
					'ta__belanja.id'				=> $this->input->get('id')
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
					'Kd_Keg'						=> $kode->kd_keg_sub,
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
					return throw_exception(403, 'Total rekening tidak boleh lebih kecil dari yang sudah direalisasikan sebesar <b>Rp.' . number_format($current) . '</b>');
				}
			}
			else
			{
				return throw_exception(404, 'Tidak dapat melakukan validasi untuk item yang akan dihapus');
			}
		}
		else
		{
			return throw_exception(404, 'Tidak dapat melakukan validasi, koneksi SIMDA tidak ditemukan');
		}
	}*/
	
	private function _header()
	{
		$query										= $this->model->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__unit.nm_unit,
				ref__sub.nm_sub
			FROM
				ref__sub
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ref__sub.id = ' . $this->_sub_unit . '
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
				Sum(ta__anggaran_pembiayaan_rinci.total) AS anggaran
			FROM
				ta__anggaran_pembiayaan_rinci
			INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
			WHERE
				ta__anggaran_pembiayaan.id_sub = ' . $this->_sub_unit . '
			LIMIT 1
		')
		->row('anggaran');
		return $query;
	}
	
	private function _keterangan_rekening($id = 0)
	{
		if($this->input->post('id'))
		{
			$urusan									= $this->model->query
			('
				SELECT
					ref__rek_1.kd_rek_1,
					ref__rek_2.kd_rek_2,
					ref__rek_3.kd_rek_3,
					ref__rek_4.kd_rek_4,
					ref__rek_5.kd_rek_5,
					ref__rek_1.uraian AS nm_rek_1,
					ref__rek_2.uraian AS nm_rek_2,
					ref__rek_3.uraian AS nm_rek_3,
					ref__rek_4.uraian AS nm_rek_4,
					ref__rek_5.uraian AS nm_rek_5
				FROM
					ref__rek_6
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__rek_6.id = ' . $this->input->post('id') . '
				LIMIT 1
			')
			->row();
			
			$detail_rekening							= '
				<table class="table table-bordered table-sm">
					<tbody>
						<tr>
							<td class="text-sm" width="25%">
								Akun
							</td>
							<td class="text-sm" width="18%">
								' . (isset($urusan->kd_rek_1) ? $urusan->kd_rek_1 : 0) . '
							</td>
							<td class="text-sm" width="57%">
								<a href="' . base_url('laporan/anggaran/rka/rekening', array('rekening' => 10, 'method' => 'embed', 'tanggal_cetak' => date('Y-m-d'))) . '" class="btn btn-info btn-sm float-right" target="_blank">
									<i class="mdi mdi-printer"></i>
								</a>
								' . (isset($urusan->nm_rek_1) ? $urusan->nm_rek_1 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Kelompok
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_rek_2) ? $urusan->kd_rek_1 . '.' . $urusan->kd_rek_2 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->nm_rek_2) ? $urusan->nm_rek_2 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Jenis
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_rek_3) ? $urusan->kd_rek_1 . '.' . $urusan->kd_rek_2 . '.' . $urusan->kd_rek_3 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->nm_rek_3) ? $urusan->nm_rek_3 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_rek_4) ? $urusan->kd_rek_1 . '.' . $urusan->kd_rek_2 . '.' . $urusan->kd_rek_3 . '.' . $urusan->kd_rek_4 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->nm_rek_4) ? $urusan->nm_rek_4 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Rincian Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_rek_5) ? $urusan->kd_rek_1 . '.' . $urusan->kd_rek_2 . '.' . $urusan->kd_rek_3 . '.' . $urusan->kd_rek_4 . '.' . $urusan->kd_rek_5 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->nm_rek_5) ? $urusan->nm_rek_5 : NULL) . '
							</td>
						</tr>
					</tbody>
				</table>
			';
		}
		else
		{
			$detail_rekening							= '';
		}
		
		$query										= $this->model->select('keterangan')->get_where
		(
			'ref__rek_6',
			array
			(
				'id'								=> $this->input->post('id')
			)
		)
		->row('keterangan');
		
		return make_json
		(
			array
			(
				'detail_program'					=> $detail_rekening,
				'html'								=> '<div class="alert alert-info checkbox-wrapper" style="margin-top:10px">' . ($query ? $query : 'Belum ada keterangan untuk rekening yang dipilih') . '</div>'
			)
		);
	}
	
	private function _sumber_dana()
	{
		if($this->input->post('id'))
		{
			$urusan									= $this->model->query
			('
				SELECT
					ref__sumber_dana_rek_1.kd_sumber_dana_rek_1,
					ref__sumber_dana_rek_2.kd_sumber_dana_rek_2,
					ref__sumber_dana_rek_3.kd_sumber_dana_rek_3,
					ref__sumber_dana_rek_4.kd_sumber_dana_rek_4,
					ref__sumber_dana_rek_5.kd_sumber_dana_rek_5,
					ref__sumber_dana_rek_1.uraian AS uraian_rek_1,
					ref__sumber_dana_rek_2.uraian AS uraian_rek_2,
					ref__sumber_dana_rek_3.uraian AS uraian_rek_3,
					ref__sumber_dana_rek_4.uraian AS uraian_rek_4,
					ref__sumber_dana_rek_5.uraian AS uraian_rek_5
				FROM
					ref__sumber_dana_rek_6
				INNER JOIN ref__sumber_dana_rek_5 ON ref__sumber_dana_rek_6.id_sumber_dana_rek_5 = ref__sumber_dana_rek_5.id
				INNER JOIN ref__sumber_dana_rek_4 ON ref__sumber_dana_rek_5.id_sumber_dana_rek_4 = ref__sumber_dana_rek_4.id
				INNER JOIN ref__sumber_dana_rek_3 ON ref__sumber_dana_rek_4.id_sumber_dana_rek_3 = ref__sumber_dana_rek_3.id
				INNER JOIN ref__sumber_dana_rek_2 ON ref__sumber_dana_rek_3.id_sumber_dana_rek_2 = ref__sumber_dana_rek_2.id
				INNER JOIN ref__sumber_dana_rek_1 ON ref__sumber_dana_rek_2.id_sumber_dana_rek_1 = ref__sumber_dana_rek_1.id
				WHERE
					ref__sumber_dana_rek_6.id = ' . $this->input->post('id') . '
				LIMIT 1
			')
			->row();
			
			$detail_sumber_dana						= '
				<table class="table table-bordered table-sm">
					<tbody>
						<tr>
							<td class="text-sm" width="25%">
								Akun
							</td>
							<td class="text-sm" width="18%">
								' . (isset($urusan->kd_sumber_dana_rek_1) ? $urusan->kd_sumber_dana_rek_1 : 0) . '
							</td>
							<td class="text-sm" width="57%">
								<a href="' . base_url('laporan/anggaran/rka/sumber_dana', array('method' => 'embed', 'tanggal_cetak' => date('Y-m-d'))) . '" class="btn btn-success btn-sm float-right" target="_blank">
									<i class="mdi mdi-printer"></i>
								</a>
								' . (isset($urusan->uraian_rek_1) ? $urusan->uraian_rek_1 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Kelompok
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_sumber_dana_rek_2) ? $urusan->kd_sumber_dana_rek_1 . '.' . $urusan->kd_sumber_dana_rek_2 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_rek_2) ? $urusan->uraian_rek_2 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Jenis
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_sumber_dana_rek_3) ? $urusan->kd_sumber_dana_rek_1 . '.' . $urusan->kd_sumber_dana_rek_2 . '.' . $urusan->kd_sumber_dana_rek_3 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_rek_3) ? $urusan->uraian_rek_3 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_sumber_dana_rek_4) ? $urusan->kd_sumber_dana_rek_1 . '.' . $urusan->kd_sumber_dana_rek_2 . '.' . $urusan->kd_sumber_dana_rek_3 . '.' . $urusan->kd_sumber_dana_rek_4 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_rek_4) ? $urusan->uraian_rek_4 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Rincian Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_sumber_dana_rek_5) ? $urusan->kd_sumber_dana_rek_1 . '.' . $urusan->kd_sumber_dana_rek_2 . '.' . $urusan->kd_sumber_dana_rek_3 . '.' . $urusan->kd_sumber_dana_rek_4 . '.' . $urusan->kd_sumber_dana_rek_5 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_rek_5) ? $urusan->uraian_rek_5 : NULL) . '
							</td>
						</tr>
					</tbody>
				</table>
			';
		}
		else
		{
			$detail_sumber_dana						= '';
		}
		
		$query										= $this->model->select('keterangan')->get_where
		(
			'ref__sumber_dana_rek_6',
			array
			(
				'id'								=> $this->input->post('id')
			)
		)
		->row('keterangan');
		
		return make_json
		(
			array
			(
				'detail_sumber_dana'				=> $detail_sumber_dana,
				'html'								=> '<div class="alert alert-info checkbox-wrapper" style="margin-top:10px">' . ($query ? $query : 'Belum ada keterangan untuk sumber dana yang dipilih') . '</div>'
			)
		);
	}
}