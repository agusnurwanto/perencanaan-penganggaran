<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Rencana extends Aksara
{
	private $_title;
	private $_pageSize;
	private $_output;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission();
		$this->set_theme('backend');
		
		$this->unset_action('create, read, update, delete, export, print, pdf');
		
		$this->_tahun								= get_userdata('year');
		$this->_unit								= (in_array(get_userdata('group_id'), array(13, 14)) ? get_userdata('sub_level_1') : $this->input->get('unit'));
		$this->_sub_unit							= (in_array(get_userdata('group_id'), array(11, 12)) ? get_userdata('sub_level_1') : $this->input->get('sub_unit'));
		$this->_program								= ($this->input->get('program') ? $this->input->get('program') : null);
		$this->_kegiatan							= ($this->input->get('kegiatan') ? $this->input->get('kegiatan') : null);
		$this->_sub_kegiatan						= ($this->input->get('sub_kegiatan') ? $this->input->get('sub_kegiatan') : null);
		//$this->_rekening							= ($this->input->get('rekening') ? $this->input->get('rekening') : null);
		$this->_sumber_dana							= ($this->input->get('sumber_dana') ? $this->input->get('sumber_dana') : 'all');
		$this->_jenis_anggaran						= ($this->input->get('jenis_anggaran') ? $this->input->get('jenis_anggaran') : 0);
		$this->_tanggal_cetak						= ($this->input->get('tanggal_cetak') ? $this->input->get('tanggal_cetak') : date('Y-m-d'));
		
				
		//$this->_jenis_usulan						= ($this->input->get('jenis_usulan') ? $this->input->get('jenis_usulan') : 0);
		
		$this->load->model('perencanaan/Rencana_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan Rencana')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function perencanaan()
	{
		if(!$this->_unit || !$this->_sumber_dana)
		{
			return throw_exception(404, 'Silakan pilih Unit, Sumber Dana untuk melakukan ' . phrase($this->_request) . ' Perencanaan', current_page('../'));
		}
		
		$this->_title								= 'Laporan Perencanaan';
		$this->_output								= $this->report->perencanaan($this->_tahun, $this->_unit, $this->_sub_unit, $this->_sumber_dana, $this->_jenis_anggaran);
		
		$this->_execute();
	}
	
	public function ba_desk_renja()
	{
		if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' Berita Acara Desk Renja', current_page('../'));
		}
		
		$this->_title								= 'Berita Acara Desk Renja';
		$this->_output								= $this->report->ba_desk_renja($this->_tahun, $this->_unit, $this->_sub_unit);
		
		$this->_execute();
	}
	
	public function rekapitulasi_perencanaan_per_skpd()
	{
		if(!$this->_sumber_dana)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' Rekapitulasi Perencanaan per SKPD', current_page('../'));
		}
		
		$this->_title								= 'Rekapitulasi Perencanaan per SKPD';
		$this->_output								= $this->report->rekapitulasi_perencanaan_per_skpd($this->_tahun, $this->_sumber_dana, $this->_jenis_anggaran);
		
		$this->_execute();
	}
	
	public function rekapitulasi_perencanaan_per_program()
	{
		if(!$this->_sumber_dana)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' Rekapitulasi Perencanaan per Program', current_page('../'));
		}
		
		$this->_title								= 'Rekapitulasi Perencanaan per Program';
		$this->_output								= $this->report->rekapitulasi_perencanaan_per_program($this->_tahun, $this->_sumber_dana, $this->_jenis_anggaran);
		
		$this->_execute();
	}
	
	private function _execute()
	{
		if(!$this->_output)
		{
			return throw_exception(404, 'Tidak atau belum dapat menampilkan laporan yang Anda pilih. Pastikan Anda melengkapi formulir yang diminta apabila tersedia...', current_page('../'));
		}
		
		/* prepare object data */
		$this->_tanggal_cetak						= ($this->input->get('tanggal_cetak') ? $this->input->get('tanggal_cetak') : date('Y-m-d'));
		$daerah										= $this->model->select
		('
			nama_pemda,
			nama_daerah,
			office_address
		')
		->get_where
		(
			'app__settings',
			array
			(
			)
		)
		->row();
		
		$data										= array
		(
			'title'									=> $this->_title,
			'nama_pemda'							=> (isset($daerah->nama_pemda) ? $daerah->nama_pemda : null),
			'nama_daerah'							=> (isset($daerah->nama_daerah) ? $daerah->nama_daerah : null),
			'alamat_daerah'							=> (isset($daerah->office_address) ? $daerah->office_address : null),
			'tanggal_cetak'							=> date_indo($this->_tanggal_cetak),
			'results'								=> $this->_output
		);
		
		//print_r($data);exit;
		
		if(in_array($this->input->get('method'), array('embed', 'download', 'export', 'doc')))
		{
			$data									= $this->load->view($this->_template, $data, true);
			
			/* load the document library */
			$this->load->library('Document');
			
			/* set page size */
			$this->document->pageSize($this->_pageSize);
			
			return $this->document->generate($data, $this->_title, $this->input->get('method'));
		}
		
		$this->load->view($this->_template, $data);
	}
	
	/**
	 * List of reports
	 */
	private function _reports()
	{
		return array
		(
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Perencanaan',
				'description'						=> 'Laporan Perencanaan',
				'icon'								=> 'mdi-chart-arc',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'perencanaan',
				'parameter'							=> array
				(
					'unit'							=> $this->_unit(),
					'sumber_dana'					=> $this->_sumber_dana(),
					'jenis_anggaran'				=> $this->_jenis_anggaran(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Berita Acara Desk Renja',
				'description'						=> 'Berita Acara Desk Renja',
				'icon'								=> 'mdi-chart-bell-curve',
				'color'								=> 'bg-teal',
				'placement'							=> 'left',
				'controller'						=> 'ba_desk_renja',
				'parameter'							=> array
				(
					'skpd'							=> $this->_unit(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			/*
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Laporan RKA - Belanja SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Belanja SKPD',
				'icon'								=> 'mdi-chart-areaspline',
				'color'								=> 'bg-danger',
				'placement'							=> 'left',
				'controller'						=> 'rka_belanja_skpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_unit(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Laporan RKA - Rincian Belanja SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Rincian Belanja',
				'icon'								=> 'mdi-chart-bar',
				'color'								=> 'bg-maroon',
				'placement'							=> 'left',
				'controller'						=> 'rka_rincian_belanja',
				'parameter'							=> array
				(
					'sub_kegiatan'					=> $this->_kegiatan(),
					//'jenis_data'					=> $this->_jenis_data(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'laporan RKA - Sub Kegiatan SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Sub Kegiatan SKPD',
				'icon'								=> 'mdi-comment-account-outline',
				'color'								=> 'bg-success',
				'placement'							=> 'left',
				'controller'						=> 'rka_sub_kegiatan',
				'parameter'							=> array
				(
					'sub_kegiatan'					=> $this->_sub_kegiatan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Laporan RKA - Pembiayaan SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Pembiayaan SKPD',
				'icon'								=> 'mdi-chart-bar-stacked',
				'color'								=> 'bg-info',
				'placement'							=> 'left',
				'controller'						=> 'rka_pembiayaan_skpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_unit(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),*/
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Perencanaan per SKPD',
				'description'						=> 'Rekapitulasi Perencanaan per SKPD',
				'icon'								=> 'mdi-chart-bar-stacked',
				'color'								=> 'bg-primary',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_perencanaan_per_skpd',
				'parameter'							=> array
				(
					'sumber_dana'					=> $this->_sumber_dana(),
					'jenis_anggaran'				=> $this->_jenis_anggaran(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Perencanaan per Program',
				'description'						=> 'Rekapitulasi Perencanaan per Program',
				'icon'								=> 'mdi-chart-timeline',
				'color'								=> 'bg-teal',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_perencanaan_per_program',
				'parameter'							=> array
				(
					'sumber_dana'					=> $this->_sumber_dana(),
					'jenis_anggaran'				=> $this->_jenis_anggaran(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			)/*,
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Anggaran Kas per Kegiatan',
				'description'						=> 'Rekapitulasi Anggaran Kas per Kegiatan',
				'icon'								=> 'mdi-credit-card',
				'color'								=> 'bg-fuchsia',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_anggaran_kas_kegiatan',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Anggaran Kas per Bulan',
				'description'						=> 'Rekapitulasi Per SKPD',
				'icon'								=> 'mdi-hexagon-slice-4',
				'color'								=> 'bg-warning',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_anggaran_kas_per_bulan',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Standar Harga',
				'description'						=> 'Laporan Standar Harga',
				'icon'								=> 'mdi-database-plus',
				'color'								=> 'bg-purple',
				'placement'							=> 'right',
				'controller'						=> 'standar_harga',
				'parameter'							=> array
				(
					'jenis_standar_harga'			=> $this->_jenis_standar_harga(),
					'standar_harga_pilihan'			=> $this->_standar_harga_pilihan()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Standar Harga',
				'description'						=> 'Laporan Rekapitulasi Standar Harga',
				'icon'								=> 'mdi-database-search',
				'color'								=> 'bg-fuchsia',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_standar_harga',
				'parameter'							=> array
				(
				
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lembar Verifikasi KAK',
				'description'						=> 'Lembar Verifikasi KAK',
				'icon'								=> 'mdi-printer-wireless',
				'color'								=> 'bg-olive',
				'placement'							=> 'right',
				'controller'						=> 'lembar_kak',
				'parameter'							=> array
				(
					'kegiatan'						=> $this->_kegiatan()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lembar Asistensi',
				'description'						=> 'Laporan Lembar Asistensi',
				'icon'								=> 'mdi-deskphone',
				'color'								=> 'bg-dark',
				'placement'							=> 'right',
				'controller'						=> 'lembar_asistensi',
				'parameter'							=> array
				(
					'kegiatan'						=> $this->_kegiatan()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Rekening',
				'description'						=> 'Laporan Rekapitulasi Rekening',
				'icon'								=> 'mdi-image-search',
				'color'								=> 'bg-green',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_rekening',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			)*/
		);
	}
	
	private function _unit()
	{
		// Super Admin, Admin Perencanaan, Admin Keuangan, Admin Monev, Admin RUP, Tim Asistensi, TAPD TTD, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		if(!in_array(get_userdata('group_id'), array(1, 2, 3, 4, 5, 16, 17, 18, 19, 20, 21))) return false;
		
		$options									= null;
		
		$query										= $this->model->select
		('
			ref__unit.id,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__unit.nm_unit
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
		->order_by
		('
			ref__urusan.kd_urusan ASC,
			ref__bidang.kd_bidang ASC,
			ref__unit.kd_unit ASC
		')
		->get_where
		(
			'ref__unit',
			array
			(
			)
		)
		->result();
		
		if($query)
		{
			$options								= '<option value="all">Pilih Semua Unit</option>';
			
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '.' . sprintf('%02d', $val->kd_unit) . '. ' . $val->nm_unit . '</option>';
			}
			
			$output									= '
				<div class="form-group">
					<label class="control-label">
						Unit
					</label>
					<select name="unit" class="form-control bordered form-control-sm report-dropdown" placeholder="Silakan Pilih Unit" to-change=".sub_unit">
						' . $options . '
					</select>
				</div>
				<div class="form-group">
					<label class="control-label">
						Sub Unit
					</label>
					<select name="sub_unit" class="form-control bordered form-control-sm sub_unit" placeholder="Pilih Unit terlebih dahulu" disabled>
						
					</select>
				</div>
			';
		}
		
		return $output;
	}
	
	private function _sumber_dana()
	{
		$output										= null;
		$query										= $this->model->query
		('
			SELECT
				ref__sumber_dana_rek_6.id,
				ref__sumber_dana_rek_1.kd_sumber_dana_rek_1,
				ref__sumber_dana_rek_2.kd_sumber_dana_rek_2,
				ref__sumber_dana_rek_3.kd_sumber_dana_rek_3,
				ref__sumber_dana_rek_4.kd_sumber_dana_rek_4,
				ref__sumber_dana_rek_5.kd_sumber_dana_rek_5,
				ref__sumber_dana_rek_6.kode,
				ref__sumber_dana_rek_6.nama_sumber_dana
			FROM
				ref__sumber_dana_rek_6
			INNER JOIN ref__sumber_dana_rek_5 ON ref__sumber_dana_rek_6.id_sumber_dana_rek_5 = ref__sumber_dana_rek_5.id
			INNER JOIN ref__sumber_dana_rek_4 ON ref__sumber_dana_rek_5.id_sumber_dana_rek_4 = ref__sumber_dana_rek_4.id
			INNER JOIN ref__sumber_dana_rek_3 ON ref__sumber_dana_rek_4.id_sumber_dana_rek_3 = ref__sumber_dana_rek_3.id
			INNER JOIN ref__sumber_dana_rek_2 ON ref__sumber_dana_rek_3.id_sumber_dana_rek_2 = ref__sumber_dana_rek_2.id
			INNER JOIN ref__sumber_dana_rek_1 ON ref__sumber_dana_rek_2.id_sumber_dana_rek_1 = ref__sumber_dana_rek_1.id
			WHERE
				ref__sumber_dana_rek_6.tahun = 2021
			ORDER BY
				ref__sumber_dana_rek_1.kd_sumber_dana_rek_1 ASC,
				ref__sumber_dana_rek_2.kd_sumber_dana_rek_2 ASC,
				ref__sumber_dana_rek_3.kd_sumber_dana_rek_3 ASC,
				ref__sumber_dana_rek_4.kd_sumber_dana_rek_4 ASC,
				ref__sumber_dana_rek_5.kd_sumber_dana_rek_5 ASC,
				ref__sumber_dana_rek_6.kode ASC
		')
		->result();
		
		if($query)
		{
			$output									.= '<option value="all">Pilih Semua Sumber Dana</option>';
			
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->id . '">' . $val->kd_sumber_dana_rek_1 . '.' . $val->kd_sumber_dana_rek_2 . '.' . $val->kd_sumber_dana_rek_3 . '.' . sprintf('%02d', $val->kd_sumber_dana_rek_4) . '.' . sprintf('%02d', $val->kd_sumber_dana_rek_5) . '.' . sprintf('%02d', $val->kode) . '. ' . $val->nama_sumber_dana . '</option>';
			}
		}
		
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Sumber Dana
				</label>
				<select name="sumber_dana" class="form-control form-control-sm">
					' . $output . '
				</select>
			</div>
		';
		
		return $output;
	}
	
	private function _jenis_anggaran()
	{
		$output										= null;
		$query										= $this->model->query
		('
			SELECT
				ref__renja_jenis_anggaran.id,
				ref__renja_jenis_anggaran.kode,
				ref__renja_jenis_anggaran.nama_jenis_anggaran
			FROM
				ref__renja_jenis_anggaran
			ORDER BY
				ref__renja_jenis_anggaran.kode ASC
		')
		->result();
		
		if($query)
		{
			$output									.= '<option value="0">Aktual</option>';
			
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->id . '">' . $val->kode . '. ' . $val->nama_jenis_anggaran . '</option>';
			}
		}
		
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Jenis Anggaran
				</label>
				<select name="jenis_anggaran" class="form-control form-control-sm">
					' . $output . '
				</select>
			</div>
		';
		
		return $output;
	}
	
	private function _kegiatan()
	{
		$output										= null;
		// Super Admin, Admin Perencanaan, Admin Keuangan, Admin Monev, Admin RUP, Tim Asistensi, TAPD TTD, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		
		if(in_array(get_userdata('group_id'), array(1, 2, 3, 4, 5, 16, 17, 18, 19, 20, 21)))
		{
			$query									= $this->model->select
			('
				ref__sub.id,
				ref__sub.nm_sub,
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub
			')
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
				'ref__sub',
				array
				(
					'ref__sub.id !='				=> null
				)
			)
			->result();
			
			if($query)
			{
				$options							= null;
				
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '.' . $val->kd_sub . ' ' . $val->nm_sub . '</option>';
				}
				
				$output								.= '
					<div class="form-group">
						<label class="control-label">
							Unit
						</label>
						<select name="sub_unit" class="form-control form-control-sm report-dropdown" to-change=".sub_unit">
							<option value="">Silakan pilih Sub Unit</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Sub Unit
						</label>
						<select name="sub_unit" class="form-control form-control-sm report-dropdown sub_unit" to-change=".program">
							<option value="">Silakan pilih Unit terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Program
						</label>
						<select name="program" class="form-control form-control-sm report-dropdown program" to-change=".kegiatan" disabled>
							<option value="">Silakan pilih Sub Unit terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Kegiatan
						</label>
						<select name="kegiatan" class="form-control form-control-sm kegiatan" disabled>
							<option value="">Silakan pilih Program terlebih dahulu</option>
						</select>
					</div>
				';
			}
		}
		
		// Grup Sub Unit
		elseif(in_array(get_userdata('group_id'), array(11, 12)))
		{
			$query									= $this->model->select
			('
				ta__program.id,
				ref__program.kd_program,
				ref__program.nm_program
			')
			->join
			(
				'ta__program',
				'ref__program.id = ta__program.id_prog'
			)
			->join
			(
				'ref__sub',
				'ref__sub.id = ta__program.id_sub'
			)
			->order_by('ref__program.kd_program', 'ASC')
			->get_where
			(
				'ref__program',
				array
				(
					'ref__program.id !='			=> null,
					'ref__sub.id_unit'				=> $this->_unit
				)
			)
			->result();
			
			if($query)
			{
				$options							= null;
				
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_program . '. ' . $val->nm_program . '</option>';
				}
				
				$output								.= '
					<div class="form-group">
						<label class="control-label">
							Program
						</label>
						<select name="program" class="form-control form-control-sm report-dropdown" to-change=".kegiatan">
							<option value="">Silakan pilih Program</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Kegiatan
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
	
	private function _sub_kegiatan()
	{
		$output										= null;
		// Super Admin, Admin Perencanaan, Admin Keuangan, Admin Monev, Admin RUP, Tim Asistensi, TAPD TTD, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		
		if(in_array(get_userdata('group_id'), array(1, 2, 3, 4, 5, 16, 17, 18, 19, 20, 21)))
		{
			$query									= $this->model->select
			('
				ref__sub.id,
				ref__sub.nm_sub,
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub
			')
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
				'ref__sub',
				array
				(
					'ref__sub.id !='				=> null
				)
			)
			->result();
			
			if($query)
			{
				$options							= null;
				
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '.' . $val->kd_sub . ' ' . $val->nm_sub . '</option>';
				}
				
				$output								.= '
					<div class="form-group">
						<label class="control-label">
							Unit
						</label>
						<select name="sub_unit" class="form-control form-control-sm report-dropdown" to-change=".sub_unit">
							<option value="">Silakan pilih Sub Unit</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Sub Unit
						</label>
						<select name="sub_unit" class="form-control form-control-sm report-dropdown sub_unit" to-change=".program">
							<option value="">Silakan pilih Unit terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Program
						</label>
						<select name="program" class="form-control form-control-sm report-dropdown program" to-change=".kegiatan" disabled>
							<option value="">Silakan pilih Sub Unit terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Kegiatan
						</label>
						<select name="kegiatan" class="form-control form-control-sm report-dropdown kegiatan" to-change=".sub_kegiatan" disabled>
							<option value="">Silakan pilih Program terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Sub Kegiatan
						</label>
						<select name="sub_kegiatan" class="form-control form-control-sm sub_kegiatan" disabled>
							<option value="">Silakan pilih Kegiatan terlebih dahulu</option>
						</select>
					</div>
				';
			}
		}
		
		// Grup Sub Unit
		elseif(in_array(get_userdata('group_id'), array(11, 12)))
		{
			$query									= $this->model->select
			('
				ta__program.id,
				ref__program.kd_program,
				ref__program.nm_program
			')
			->join
			(
				'ta__program',
				'ref__program.id = ta__program.id_prog'
			)
			->join
			(
				'ref__sub',
				'ref__sub.id = ta__program.id_sub'
			)
			->order_by('ref__program.kd_program', 'ASC')
			->get_where
			(
				'ref__program',
				array
				(
					'ref__program.id !='			=> null,
					'ref__sub.id_unit'				=> $this->_unit
				)
			)
			->result();
			
			if($query)
			{
				$options							= null;
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_program . '. ' . $val->nm_program . '</option>';
				}
				
				$output								.= '
					<div class="form-group">
						<label class="control-label">
							Program
						</label>
						<select name="program" class="form-control form-control-sm report-dropdown" to-change=".kegiatan">
							<option value="">Silakan pilih Program</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Kegiatan
						</label>
						<select name="kegiatan" class="form-control form-control-sm kegiatan" to-change=".sub_kegiatan" disabled>
							<option value="">Silakan pilih Program terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Sub Kegiatan
						</label>
						<select name="sub_kegiatan" class="form-control form-control-sm sub_kegiatan" disabled>
							<option value="">Silakan pilih Kegiatan terlebih dahulu</option>
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
		$primary									= $this->input->post('primary');
		$element									= $this->input->post('element');
		$options									= null;
		
		if('.sub_unit' == $element)
		{
			$query									= $this->model->select
			('
				id,
				kd_sub,
				nm_sub
			')
			->order_by('kd_sub')
			->get_where
			(
				'ref__sub',
				array
				(
					'id_unit'						=> $primary
				)
			)
			->result();
			
			if($query)
			{
				$options							= ('unit' == $this->input->post('referer') ? '<option value="all">Semua Sub Unit</option>' : '<option value="">Silakan pilih Sub Unit</option>');
				
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_sub . '. ' . $val->nm_sub . '</option>';
				}
			}
		}
		elseif('.program' == $element)
		{
			$query									= $this->model->select
			('
				ta__program.id,
				ref__program.kd_program,
				ref__program.nm_program
			')
			->join
			(
				'ta__program',
				'ta__program.id_prog = ref__program.id'
			)
			->order_by('ref__program.kd_program')
			->get_where
			(
				'ref__program',
				array
				(
					'ta__program.id_sub'			=> $primary
				)
			)
			->result();
			
			if($query)
			{
				$options							= '<option value="">Silakan pilih Program</option>';
				
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_program . '. ' . $val->nm_program . '</option>';
				}
			}
		}
		elseif('.kegiatan' == $element)
		{
			$query									= $this->model->select
			('
				id,
				kd_keg,
				kegiatan
			')
			->order_by('kd_keg')
			->get_where
			(
				'ta__kegiatan',
				array
				(
					'id_prog'						=> $primary
				)
			)
			->result();
			
			if($query)
			{
				$options							= '<option value="">Silakan pilih Kegiatan</option>';
				
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_keg . '. ' . $val->kegiatan . '</option>';
				}
			}
		}
		elseif('.sub_kegiatan' == $element)
		{
			$query									= $this->model->select
			('
				id,
				kd_keg_sub,
				kegiatan_sub
			')
			->order_by('kd_keg_sub')
			->get_where
			(
				'ta__kegiatan_sub',
				array
				(
					'id_keg'						=> $primary
				)
			)
			->result();
			
			if($query)
			{
				$options							= '<option value="">Silakan pilih Sub Kegiatan</option>';
				
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_keg_sub . '. ' . $val->kegiatan_sub . '</option>';
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
	
	private function _tanggal_cetak()
	{
		$options									= '
			<div class="row">
				<div class="col-sm-6">
					<div class="form-group">
						<label class="text-muted d-block">
							Tanggal Cetak
						</label>
						<input type="text" name="tanggal_cetak" class="form-control form-control-sm bordered" placeholder="Pilih Tanggal" value="' . date('d M Y') . '" role="datepicker" readonly />
					</div>
				</div>
			</div>
		';
		
		return $options;
	}
}