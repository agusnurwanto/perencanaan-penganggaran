<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rincian extends Aksara
{
	private $_table									= 'ta__belanja_rinci';
	private $_sub_kegiatan							= 0;
	private $_belanja								= 0;
	private $_belanja_sub							= 0;
	
	function __construct()
	{
		parent::__construct();
		$this->_kegiatan							= $this->input->get('kegiatan');
		$this->_sub_kegiatan						= $this->input->get('sub_kegiatan');
		$this->_belanja								= $this->input->get('belanja');
		$this->_belanja_sub							= $this->input->get('belanja_sub');
		if(!$this->_belanja_sub)
		{
			return throw_exception(301, phrase('silakan memilih Sub Rincian terlebih dahulu'), go_to('../sub'));
		}
		elseif(!$this->_belanja)
		{
			return throw_exception(301, phrase('silakan memilih Rekening terlebih dahulu'), go_to('../rekening'));
		}
		elseif(!$this->_sub_kegiatan)
		{
			return throw_exception(301, 'Silakan memilih Sub Kegiatan terlebih dahulu', go_to('../renja/sub_kegiatan'));
		}
		
		if('sumber_dana' == $this->input->post('method'))
		{
			return $this->_sumber_dana($this->input->post('id'));
		}
		
		$this->_id_standar_harga					= $this->input->post('id_standar_harga');
		$this->_validasi_lock						= $this->_validasi_lock();
		
		$this->set_permission();
		$this->set_theme('backend');
		
	}
	
	public function index()
	{
		if('ref__standar_harga' == $this->input->get('autocomplete_table'))
		{
			return $this->_standar_harga();
		}
		elseif('trigger_standar_harga' == $this->input->post('method'))
		{
			return $this->_trigger_standar_harga();
		}
		elseif(1 == $this->input->post('force'))
		{
			$this->_simpan_paksa();
		}
		
		if($this->input->post('pindah_rekening'))
		{
			return $this->_pindah_rekening($this->input->post('pindah_rekening'));
		}
		
		$header										= $this->_header();
		$anggaran									= $this->_anggaran();
		$total_rekening								= $this->_total_rekening();
		$total_belanja_sub							= $this->_total_belanja_sub();
		
		
		$this->set_breadcrumb
		(
			array
			(
				'renja/kegiatan/sub_unit'			=> 'Sub Unit',
				'../../kegiatan'					=> 'Kegiatan',
				'../sub_kegiatan'					=> 'Sub kegiatan',
				'../../anggaran/rekening'			=> 'Rekening',
				'../sub'							=> 'Sub Rincian'
			)
		);
		/*$realisasi_rinci							= $this->model->select('tgl_bukti, no_bukti, nilai')->order_by('tgl_bukti')->get_where('ta_transaksi', array('id_belanja_rinc' => $this->input->get('id')))->result_array();
		$alert_rinci								= null;
		$return										= false;
		if($realisasi_rinci)
		{
			$return									= true;
			foreach($realisasi_rinci as $key => $val)
			{
				$alert_rinci						.= '<li>Tanggal: ' . date_indo($val['tgl_bukti']) . ', No. Bukti: ' . $val['no_bukti'] . ', Nilai: ' . number_format($val['nilai']) . '</li>';
			}
		}
		$alert_rinci								= '<ol>' . $alert_rinci . '</ol>';*/
		
		if(isset($this->_validasi_lock->anggaran_kunci_satuan) && $this->_validasi_lock->anggaran_kunci_satuan == 1)
		{
			$this->set_field
			(
				array
				(
					'satuan_1'						=> 'readonly',
					'satuan_2'						=> 'readonly',
					'satuan_3'						=> 'readonly'
				)
			);
		}
		
		if('create' == $this->_method)
		{
			$this->default_value('id_sumber_dana', $header->id_sumber_dana);
		}
		
		$this->set_title('Rincian Anggaran')
		->set_icon('mdi mdi-desk-lamp')
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
			<div class="row">
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
		
			<div class="row border-bottom">
				<div class="col-4 col-sm-2 text-muted text-sm">
					SUB RINCIAN
				</div>
				<div class="col-4 col-sm-8 font-weight text-sm">
					' . (isset($header->uraian_sub) ?  $header->kd_belanja_sub . '. ' . $header->uraian_sub : '-') . '
				</div>
				<div class="col-4 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						RP. ' . number_format((isset($total_belanja_sub) ? $total_belanja_sub : 0), 2) . '
					</b>
				</div>
			</div>
		')
		->unset_view('id, id_belanja_sub, tahun, vol_123, satuan_123')
		->unset_field('id, id_belanja_sub, vol_123, tahun, id_standar_harga')
		->merge_content('{vol_123} {satuan_123}', phrase('volume'))
		->unset_column('id, id_belanja_sub, tahun, vol_1, vol_2, vol_3, satuan_1, satuan_2, satuan_3, id_standar_harga, uraian_ref__standar_harga, vol_123 , kd_sumber_dana_rek_1, kd_sumber_dana_rek_2, kd_sumber_dana_rek_3, kd_sumber_dana_rek_4, kd_sumber_dana_rek_5, kode, nama_sumber_dana')
		->unset_truncate('uraian')
		->column_order('kd_belanja_rinci, uraian, vol_123, volume, nilai, total')
		->field_order('kd_belanja_rinci, uraian, nilai, id_standar_harga, vol_1, satuan_1, vol_2, satuan_2, vol_3, satuan_3, volume, id_sumber_dana, total, satuan_123')
		->view_order('volume')
		->unset_action('pdf, export, print')
		->add_action('toolbar', '../../laporan/anggaran/rka/rka_sub_kegiatan', 'Preview RKA', 'btn-success ajax', 'mdi mdi-printer-alert', array('kegiatan' => $this->_kegiatan, 'sub_kegiatan' => $this->_sub_kegiatan, 'method' => 'preview'), true)
		->add_action('toolbar', '../../laporan/anggaran/rka/rka_sub_kegiatan', 'Cetak RKA', 'btn-info ajax', 'mdi mdi-printer', array('kegiatan' => $this->_kegiatan, 'sub_kegiatan' => $this->_sub_kegiatan, 'method' => 'embed'), true)
		//->add_action('option', 'rencana_keuangan', phrase('rencana'), 'btn-primary ajax', 'fa fa-id-card', array('id_belanja_rinc' => 'id'))
		->set_alias
		(
			array
			(
				'kd_belanja_rinci'					=> 'Kode',
				'id_sumber_dana'					=> 'Sumber Dana',
			)
		)
		->set_field
		(
			array
			(
				'kd_belanja_rinci'					=> 'last_insert',
				'uraian'							=> 'textarea',
				'nilai'								=> 'price_format',
				'vol_1'								=> 'price_format',
				'vol_2'								=> 'price_format',
				'vol_3'								=> 'price_format',
				'satuan_123'						=> 'readonly',
				'total'								=> 'price_format, readonly'
			)
		)
		->set_field('uraian', 'autocomplete', 'ref__standar_harga', 'uraian')
		/*
		->set_autocomplete
		(
			'uraian',
			'ref__standar_harga.id',
			array
			(
				'{ref__standar_harga.id}',
				'{ref__standar_harga.uraian AS uraian_standar_harga}',
				'{ref__standar_harga.deskripsi}'
			)
		)*/
		->set_relation
		(
			'id_standar_harga',
			'ref__standar_harga.id',
			'{ref__standar_harga.uraian}'
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
		
		
		->merge_field('kd_belanja_rinci, uraian')
		->merge_field('vol_1, satuan_1')
		->merge_field('vol_2, satuan_2')
		->merge_field('vol_3, satuan_3')
		->merge_field('total, satuan_123')
		
		->field_prepend
		(
			array
			(
				'nilai'    							=> 'Rp',
				'total'    							=> 'Rp'
			)
		)
		->field_size
		(
			array
			(
				'kd_belanja_rinci'					=> 'col-sm-3',
				'nilai'								=> 'col-sm-4',
				'vol_1'								=> 'col-sm-3',
				'vol_2'								=> 'col-sm-3',
				'vol_3'								=> 'col-sm-3'
			)
		)
		->field_position
		(
			array
			(
				'id_sumber_dana'					=> 2
			)
		)
		
		->set_validation
		(
			array
			(
				'kd_belanja_rinci'					=> 'required|is_unique[' . $this->_table . '.kd_belanja_rinci.id.' . $this->input->get('id') . '.id_belanja_sub.' . $this->_belanja_sub . ']',
				'uraian'							=> 'required|callback_validate_uraian',
				'vol_1'								=> 'required|numeric',
				'vol_2'								=> 'required|numeric',
				'vol_3'								=> 'required|numeric',
				'nilai'								=> 'required|numeric',
				'total'								=> 'required|callback_validate_total',
				'satuan_123'						=> 'required|callback_validate_satuan_total',
				'id_sumber_dana'					=> 'required'
			)
		)
		
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus uraian',
				'nilai'								=> 'sum_field',
				'vol_1'								=> 'sum_field vol_1',
				'vol_2'								=> 'sum_field vol_2',
				'vol_3'								=> 'sum_field vol_3',
				'satuan_1'							=> 'merge-text satuan_1',
				'satuan_2'							=> 'merge-text satuan_2',
				'satuan_3'							=> 'merge-text satuan_3',
				'satuan_123'						=> 'merge-text-result',
				'total'								=> 'sum_total',
				'id_sumber_dana'					=> 'sumber_dana'
			)
		)
		->set_default
		(
			array
			(
				'id_belanja_sub'					=> $this->_belanja_sub,
				'id_standar_harga'					=> $this->_id_standar_harga,
				'vol_123'							=> ($this->input->post('vol_1') > 0 ? $this->input->post('vol_1') : 1) * ($this->input->post('vol_2') > 0 ? $this->input->post('vol_2') : 1) * ($this->input->post('vol_3') > 0 ? $this->input->post('vol_3') : 1),
				'tahun'								=> get_userdata('year')
			)
		)
		->where
		(
			array
			(
				'id_belanja_sub'					=> $this->_belanja_sub
			)
		)
		//->autoload_form(false)
		//->set_template('form', 'form')
		->order_by('kd_belanja_rinci')
		//->set_messages('delete', 403, 'Anggaran yang anda hapus sudah memiliki daftar belanja seperti berikut:<br />' . $alert_rinci, $return)
		->render($this->_table);
	}
	
	private function _simpan_paksa()
	{
		// insert belanja
		$id_rek_6									= $this->model->select('id_rek_6')->get_where('ref__standar_harga', array('id' => $this->input->post('id_standar_harga')), 1)->row('id_rek_6');
		$prepare									= array
		(
			'id_keg_sub'							=> $this->input->get('id_keg_sub'),
			'id_rek_6'								=> $id_rek_6,
			'id_standar_harga'						=> $this->input->post('id_standar_harga')
		);
		$last_insert								= $this->model->select('id')->get_where('ta__belanja', $prepare)->row('id');
		if(!$last_insert)
		{
			$this->model->insert('ta__belanja', $prepare);
			$last_insert							= $this->model->insert_id();
		}
		$this->_id_bel								= $last_insert;
		
		// insert belanja sub
		$uraian										= $this->model->select('uraian')->get_where('ref__rek_6', array('id' => $id_rek_6), 1)->row('uraian');
		$prepare									= array
		(
			'id_belanja'							=> $last_insert,
			'kd_belanja_sub'						=> 1,
			'uraian'								=> $uraian
		);
		
		$this->_belanja_sub								= $this->model->select('id')->get_where('ta__belanja_sub', $prepare, 1)->row('id');
		if(!$this->_belanja_sub)
		{
			$this->model->insert('ta__belanja_sub', $prepare);
			$this->_belanja_sub							= $this->model->insert_id();
		}
	}
	
	private function _pindah_rekening($id_rekening = 0)
	{
		// insert belanja
		$id_rek_6									= $this->model->select('id_rek_6')->get_where('ref__standar_rekening', array('id_rek_6' => $id_rekening, 'id_standar_harga' => $this->input->post('id_standar_harga')), 1)->row('id_rek_6');
		
		if(!$id_rek_6)
		{
			return throw_exception(404, 'Rekening yang dipilih tidak tersedia.', current_page());
		}
		
		$belanja									= $this->model->select
		('
			ta__belanja_sub.uraian
		')
		->join
		(
			'ta__belanja',
			'ta__belanja.id = ta__belanja_sub.id_belanja'
		)
		->get_where
		(
			'ta__belanja_sub',
			array
			(
				'ta__belanja_sub.id'				=> $this->_belanja_sub
			),
			1
		)
		->row();
		
		$prepare									= array
		(
			'id_keg_sub'							=> $this->_sub_kegiatan,
			'id_rek_6'								=> $id_rek_6
		);
		$last_insert_belanja						= $this->model->select('id')->get_where('ta__belanja', $prepare)->row('id');
		if(!$last_insert_belanja)
		{
			$this->model->insert('ta__belanja', $prepare);
			$last_insert_belanja					= $this->model->insert_id();
		}
		$this->_belanja								= $last_insert_belanja;
		
		// insert belanja sub
		$prepare									= array
		(
			'id_belanja'							=> $last_insert_belanja,
			'kd_belanja_sub'						=> 1,
			'uraian'								=> $belanja->uraian
		);
		
		$this->_belanja_sub							= $this->model->select('id')->get_where('ta__belanja_sub', $prepare, 1)->row('id');
		if(!$this->_belanja_sub)
		{
			$this->model->insert('ta__belanja_sub', $prepare);
			$this->_belanja_sub						= $this->model->insert_id();
		}
		
		$kode										= $this->model->query
		('
			SELECT
				IFNULL(MAX(kd_belanja_rinci) + 1, 1) AS kd_belanja_rinci
			FROM
				ta__belanja_rinci
			WHERE
				id_belanja_sub = ' . $this->_belanja_sub . '
		')
		->row('kd_belanja_rinci');
		
		$prepare									= array
		(
			'id_belanja_sub'						=> $this->_belanja_sub,
			'id_sumber_dana'						=> $this->input->post('id_sumber_dana'),
			'id_standar_harga'						=> $this->input->post('id_standar_harga'),
			'kd_belanja_rinci'						=> $kode,
			'uraian'								=> $this->input->post('uraian'),
			'vol_1'									=> $this->input->post('vol_1'),
			'vol_2'									=> $this->input->post('vol_2'),
			'vol_3'									=> $this->input->post('vol_3'),
			'vol_123'								=> ($this->input->post('vol_1') > 0 ? $this->input->post('vol_1') : 1) * ($this->input->post('vol_2') > 0 ? $this->input->post('vol_2') : 1) * ($this->input->post('vol_3') > 0 ? $this->input->post('vol_3') : 1),
			'satuan_1'								=> $this->input->post('satuan_1'),
			'satuan_2'								=> $this->input->post('satuan_2'),
			'satuan_3'								=> $this->input->post('satuan_3'),
			'satuan_123'							=> $this->input->post('satuan_1') . ($this->input->post('satuan_2') ? '/' . $this->input->post('satuan_2') : null) . ($this->input->post('satuan_3') ? '/' . $this->input->post('satuan_3') : null),
			'nilai'									=> $this->input->post('nilai'),
			'total'									=> $this->input->post('total') * $this->input->post('vol_1') * ($this->input->post('vol_2') > 1 ? $this->input->post('vol_2') : 1) * ($this->input->post('vol_3') > 1 ? $this->input->post('vol_3') : 1)
		);
		
		// insert belanja rinci
		$this->model->insert('ta__belanja_rinci', $prepare);
		
		return throw_exception(301, 'Data berhasil disimpan. Anda telah dialihkan ke dalam rekening yang dipilih.', current_page('../', array('belanja' => $this->_belanja, 'belanja_sub' => $this->_belanja_sub)), true);
	}
	
	public function after_insert()
	{
		if(1 == $this->input->post('force') || 1 == $this->input->post('pindah_rekening'))
		{
			return throw_exception(301, 'Data berhasil disimpan. Anda telah dialihkan ke dalam rekening yang dipilih.', current_page('../', array('belanja' => $this->_belanja, 'belanja_sub' => $this->_belanja_sub)), true);
		}
	}
	
	/**
	 * Cek jika item akan dihapus
	 */
	public function before_delete()
	{
		$current									= $this->model
													->select('total')
													->get_where('ta__belanja_rinci', array('id' => $this->input->get('id')), 1)
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
													//->select('total')
													//->get_where('ta__belanja_rinci', array('id' => $this->input->get('id')), 1)
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
					'ta__belanja_sub.id'			=> $this->_belanja_sub
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
	}
	
	private function _standar_harga()
	{
		$this->permission->must_ajax();
		$rekening									= $this->model->select('uraian')->get_where('ta__belanja_sub', array('id' => $this->_belanja_sub), 1)->row('uraian');
		$table										= $this->input->get('autocomplete_table');
		$field										= $this->input->get('autocomplete_field');
		$keyword									= $this->input->post('q');
		$query										= $this->model->like($field, $keyword)->order_by($field)->get_where($table, array('tahun' => get_userdata('year'), 'approve !=' => 2), 50)->result();
		$output										= array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				list($whole, $decimal)				= explode('.', $val->nilai);
				$output['suggestions'][]			= array
				(
					//'id_belanja_sub'				=> $val['id_rek_5'],
					'belanja_sub'					=> $rekening,
					'current_id'					=> $this->_belanja_sub,
					'id_standar_harga'				=> $val->id,
					'value'							=> $val->$field,
					'label'							=> $val->$field,
					'nilai'							=> ($decimal > 0 ? $val->nilai : $whole),
					'satuan'						=> json_encode(array($val->satuan_1, $val->satuan_2, $val->satuan_3)),
					'tooltip'						=> $val->deskripsi . ($val->deskripsi ? ' => ' : null) . 'Rp. ' . number_format($val->nilai),
					'disabled'						=> (1 != $val->approve ? 1 : 0)
				);
			}
		}
		make_json($output);
	}
	
	private function _trigger_standar_harga()
	{
		$id											= $this->input->post('id');
		$query										= $this->model->get_where('ref__standar_harga', array('id' => $id, 'approve' => 1), 1)->row();
		list($whole, $decimal)						= explode('.', (isset($query->nilai) ? $query->nilai : '0.0000'));
		make_json
		(
			array
			(
				'title'								=> (isset($query->uraian) ? $query->uraian : ''),
				'deskripsi'							=> (isset($query->deskripsi) ? '<div class="alert alert-info">' . $query->deskripsi . '</div><input type="hidden" name="id_standar_harga" class="id_standar_harga_input" value="' . $query->id . '" />' : ''),
				'nilai'								=> (isset($query->nilai) && $decimal > 0 ? $query->nilai : $whole),
				'satuan_1'							=> (isset($query->satuan_1) ? $query->satuan_1 : ''),
				'satuan_2'							=> (isset($query->satuan_2) ? $query->satuan_2 : ''),
				'satuan_3'							=> (isset($query->satuan_3) ? $query->satuan_3 : '')
			)
		);
	}
	
	public function validate_uraian($value = null)
	{
		$id_keg_sub									= $this->input->get('id_keg_sub');
		$id_standar_harga							= $this->input->post('id_standar_harga');
		$uraian										= $this->input->post('uraian');
		$nilai										= $this->input->post('nilai');
		$tahun										= get_userdata('year');
		/*
		$pagu										= $this->model
		->select('ta__belanja_rinci.total AS total')
		->join('ta__belanja_sub', 'ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub', 'inner')
		->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja', 'inner')
		->get_where('ta__belanja_rinci', array('ta__belanja.id_keg' => $id_keg))
		->row('total');
		*/
		$pagu										= $this->model
			->select('ta__kegiatan_sub.pagu')
			->join('ta__kegiatan', 'ta__kegiatan.id = ta__kegiatan_sub.id_keg', 'INNER')
			->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog', 'INNER')
			->join('ref__sub', 'ref__sub.id = ta__program.id_sub', 'INNER')
			->join('ref__program', 'ref__program.id = ta__program.id_prog', 'INNER')
			->get_where
			(
				'ta__kegiatan_sub',
				array
				(
					'ta__kegiatan_sub.id'					=> $this->_sub_kegiatan
				)
			)
			->row('pagu');
		
		$nilai_terinput								= $this->model
		->select('total')
		->get_where
		(
			'ta__belanja_rinci',
			array
			(
				'id'								=> $this->input->get('id')
			),
			1
		)
		->row('total');
		
		if($nilai_terinput <= 0)
		{
			$nilai_terinput							= 0;
		}
		
		$cek_uraian									= $this->model
		->get_where
		(
			'ref__standar_harga',
			array
			(
				'id'								=> $id_standar_harga,
				'uraian'							=> $uraian
			),
			1
		)
		->row();
		
		$standar_harga								= $this->model
		->select
		('
			ref__standar_rekening.id_rek_6,
			ref__standar_harga.uraian,
			ref__rek_6.uraian AS rekening
		')
		->join
		(
			'ref__standar_rekening',
			'ref__standar_rekening.id_standar_harga = ref__standar_harga.id'
		)
		->join
		(
			'ref__rek_6',
			'ref__rek_6.id = ref__standar_rekening.id_rek_6'
		)
		->get_where
		(
			'ref__standar_harga',
			array
			(
				'ref__standar_harga.id'				=> $id_standar_harga
			),
			1
		)
		->row();
		
		$belanja									= $this->model
		->select
		('
			ref__rek_6.uraian
		')
		->join
		(
			'ref__rek_6',
			'ref__rek_6.id = ta__belanja.id_rek_6'
		)
		->get_where
		(
			'ta__belanja',
			array
			(
				'ta__belanja.id'					=> $this->_belanja
			),
			1
		)
		->row();
		
		if(!$cek_uraian || !$standar_harga || !$belanja && (isset($this->_validasi_lock->anggaran_standar_harga) && $this->_validasi_lock->anggaran_standar_harga == 1))
		{
			$href									= current_page('../pengajuan_standar_harga', array('u' => $this->input->post('uraian'), 'n' => $this->input->post('nilai'), 's1' => $this->input->post('satuan_1'), 's2' => $this->input->post('satuan_2'), 's3' => $this->input->post('satuan_3')));
			
			$this->form_validation->set_message('validate_uraian', 'Uraian yang Anda input belum tersedia dalam database. <a href="' . $href . '" class="btn btn-success btn-xs --modal"><i class="fa fa-refresh"></i>&nbsp;Ajukan standar harga</a>');
			return false;
		}
		/*elseif($standar_harga->id_rek_5 != $this->_belanja && 1 != $this->input->post('force'))
		{
			$this->form_validation->set_message('validate_uraian', '<b>' . $standar_harga->uraian . '</b> tidak termasuk dalam rekening <b>' . $belanja->uraian . '</b> melainkan <b>' . $standar_harga->rekening . '</b>.<br />Apakah Anda ingin menyimpan data ini ke dalam rekening <b>' . $standar_harga->rekening . '</b>?<br /><label class="control-label"><input type="checkbox" name="force" value="1" /> Ya</label>');
			return false;
		}*/
		else
		{
			if($nilai > $cek_uraian->nilai)
			{
				$this->form_validation->set_message('validate_uraian', 'Nilai tidak boleh melebihi standar harga sebesar <b>Rp. ' . number_format($cek_uraian->nilai) . '</b>');
				return false;
			}
			elseif($pagu > 0 && $this->_validasi_lock->anggaran_kunci_plafon == 1)
			{
				if($nilai > ($pagu - $nilai_terinput))
				{
					$this->form_validation->set_message('validate_uraian', 'Nilai tidak boleh melebihi pagu indikatif sebesar <b>Rp. ' . number_format($pagu) . '</b>');
					return false;
				}
			}
		}
		
		
		
		/**
		 * Validate Standar Harga dengan rekening
		 */
		$cek_rekening								= $this->model->select
		('
			ref__rek_1.kd_rek_1,
			ref__rek_2.kd_rek_2,
			ref__rek_3.kd_rek_3,
			ref__rek_4.kd_rek_4,
			ref__rek_5.kd_rek_5,
			ref__rek_6.kd_rek_6,
			ref__rek_6.id,
			ref__rek_6.uraian
		')
		->join
		(
			'ref__rek_6',
			'ref__rek_6.id = ref__standar_rekening.id_rek_6'
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
		->get_where
		(
			'ref__standar_rekening',
			array
			(
				'ref__standar_rekening.id_standar_harga'				=> $id_standar_harga
			)
		)
		->result();
		
		$pilihan_rekening							= null;
		$valid_rekening								= array();
		if($cek_rekening)
		{
			foreach($cek_rekening as $key => $val)
			{
				$pilihan_rekening					.= '<label class="d-block"><input type="radio" name="pindah_rekening" value="' . $val->id . '" /> <b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . $val->kd_rek_4 . '.' . $val->kd_rek_5 . '.' . $val->kd_rek_6 . '</b> - ' . $val->uraian . '</label>';
				$valid_rekening[$val->id]			= $val->uraian;
			}
		}
		
		if(!isset($valid_rekening[$this->input->post('pindah_rekening')]) && !in_array($this->_header()->nm_rek_6, $valid_rekening) && (isset($this->_validasi_lock->anggaran_kunci_standar_ke_rekening) && $this->_validasi_lock->anggaran_kunci_standar_ke_rekening == 1))
		{
			$this->form_validation->set_message('validate_uraian', 'Uraian tidak cocok pada Rekening <b>' . $this->_header()->kd_rek_1 . '.' . $this->_header()->kd_rek_2 . '.' . $this->_header()->kd_rek_3 . '.' . $this->_header()->kd_rek_4 . '.' . $this->_header()->kd_rek_5 . '.' . $this->_header()->kd_rek_6 . ' - ' . $this->_header()->nm_rek_6 . '</b>. Anda dapat memindahkan ke dalam salah satu rekening di bawah ini:' . $pilihan_rekening);
			return false;
		}
		
		return true;
	}
	
	public function validate_rekening($value = 0)
	{
		$query										= $this->model->select
		('
			ref__rek_6.uraian
		')
		->join
		(
			'ref__rek_6',
			'ref__rek_6.id = ref__standar_rekening.id_rek_6'
		)
		->get_where
		(
			'ref__standar_rekening',
			array
			(
				'ref__standar_rekening.id_standar_harga'				=> $value
			)
		)
		->row();
		
		return true;
	}
	
	public function validate_total($value = null)
	{
		$value										= str_replace(',', '', $value);
		$current									= $this->model
													->select('total')
													->get_where('ta__belanja_rinci', array('id' => $this->input->get('id')), 1)
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
													//->select('total')
													//->get_where('ta__belanja_rinci', array('id' => $this->input->get('id')), 1)
													->row('total');
		
		$connection									= $this->model->get_where('ref__koneksi', array('tahun' => get_userdata('year')), 1)->row();
		/*
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
					'ta__belanja_sub.id'			=> $this->_belanja_sub
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
					'Kd_Keg_Sub'					=> $kode->kd_keg_sub,
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
				//print_r($current_rekening);exit;
				
				if($cek_realisasi && ($current_rekening - $current + $value < $cek_realisasi))
				{
					$this->form_validation->set_message('validate_total', 'Total rekening tidak boleh lebih kecil dari yang sudah direalisasikan sebesar <b>Rp.' . number_format($cek_realisasi) . '</b>');
					return false;
				}
			}
		}
		else
		{
			$this->form_validation->set_message('validate_total', 'Tidak dapat melakukan validasi %s, koneksi SIMDA tidak ditemukan');
			return false;
		}
		*/
		//$realisasi									= $this->model->select_sum('nilai')->get_where('ta_transaksi', array('id_belanja_rinc' => $this->input->get('id')), 1)->row('nilai');
		$checker									= $this->_header();
		$plafon										= (isset($checker->pagu) ? $checker->pagu : 0);
		$nilai										= ($this->input->post('nilai') ? $this->input->post('nilai') : 0);
		$vol_1										= $this->input->post('vol_1');
		$vol_2										= ($this->input->post('vol_2') > 1 ? $this->input->post('vol_2') : 1);
		$vol_3										= ($this->input->post('vol_3') > 1 ? $this->input->post('vol_3') : 1);
		$total										= $nilai * $vol_1 * $vol_2 * $vol_3;
		
		$nilai_total_rka							= $this->model
		->select
		('
			sum(total) as total_belanja
		')
		->join('ta__belanja_sub', 'ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub')
		->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja')
		->get_where
		(
			'ta__belanja_rinci',
			array
			(
				'ta__belanja.id_keg_sub'				=> $this->input->get('id_keg_sub')
			)
		)
		->row('total_belanja');
		
		if($total > 0 && $value != $total)
		{
			$this->form_validation->set_message('validate_total', '%s wajib diisi dan harus cocok dengan penjumlahan nilai dan volume');
			return false;
		}
		if($plafon > 0 && (isset($this->_validasi_lock->anggaran_kunci_plafon) && $this->_validasi_lock->anggaran_kunci_plafon == 1))
		{
			if(($nilai_total_rka + $value - $current) > $plafon)
			{
				$this->form_validation->set_message('validate_total', 'Penganggaran melebihi plafon sebesar <b>Rp. ' . number_format(($nilai_total_rka + $value - $current) - $plafon) . '</b>');
				return false;
			}
		}
		/*
		elseif($value < $realisasi)
		{
			$realisasi_rinci						= $this->model->select('tgl_bukti, no_bukti, nilai')->order_by('tgl_bukti')->get_where('ta_transaksi', array('id_belanja_rinc' => $this->input->get('id')))->result_array();
			$alert_rinci							= null;
			foreach($realisasi_rinci as $key => $val)
			{
				$alert_rinci						.= '<li>Tanggal: ' . date_indo($val['tgl_bukti']) . ', No. Bukti: ' . $val['no_bukti'] . ', Nilai: ' . number_format($val['nilai']) . '</li>';
			}
			$alert_rinci							= '<ol>' . $alert_rinci . '</ol>';
			$this->form_validation->set_message('validate_total', 'Penganggaran tidak boleh lebih kecil dari total nilai yang telah direalisasikan.<br />Total nilai telah direalisasikan <b>Rp. ' . str_replace('-', '', number_format($realisasi, 2) . '</b><br /><br />' . $alert_rinci));
			return false;
		}*/
		return true;
	}
	
	public function validate_satuan_total($value = null)
	{
		$satuan										= explode('/', $value);
		if(isset($satuan[0]) && trim($satuan[0]) != trim($this->input->post('satuan_1')) || isset($satuan[1]) && trim($satuan[1]) != trim($this->input->post('satuan_2')) || isset($satuan[2]) && trim($satuan[2]) != trim($this->input->post('satuan_3')))
		{
			$this->form_validation->set_message('validate_satuan_total', '%s harus cocok dengan masing-masing satuan');
			return false;
		}
		return true;
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
				ta__kegiatan_sub.id_sumber_dana,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ref__rek_6.uraian AS nm_rek_6,
				ta__belanja_sub.uraian AS uraian_sub,
				ta__belanja_sub.kd_belanja_sub
			FROM
				ta__belanja_sub
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__rek_6 ON ta__belanja.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ta__belanja_sub.id = ' . $this->_belanja_sub . '
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
	
	private function _total_belanja_sub()
	{
		$query										= $this->model->query
		('
			SELECT
				Sum(ta__belanja_rinci.total) AS total_belanja_sub
			FROM
				ta__belanja_rinci
			WHERE
				ta__belanja_rinci.id_belanja_sub = ' . $this->_belanja_sub . '
			LIMIT 1
		')
		->row('total_belanja_sub');
		return $query;
	}
	
	public function volume($data = array())
	{
		return (is_numeric($data['vol_1']) ? number_format($data['vol_1']) : $data['vol_1']) . ' ' . $data['satuan_1'] . ($data['vol_2'] > 0 ? ' x ' . (is_numeric($data['vol_2']) ? number_format($data['vol_2']) : $data['vol_2']) . ' ' . $data['satuan_2'] : null) . ($data['vol_3'] > 0 ? ' x ' . (is_numeric($data['vol_3']) ? number_format($data['vol_3']) : $data['vol_3']) . ' ' . $data['satuan_3'] : null);
	}
	
	private function _validasi_lock()
	{
		$query										= $this->model->get_where
		(
			'ref__settings',
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->row();
		
		return $query;
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