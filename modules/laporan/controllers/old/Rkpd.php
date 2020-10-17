<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Laporan > Rkpd
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Rkpd extends Aksara
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
		
		$this->_rw									= null;
		$this->_kelurahan							= null;
		$this->_kecamatan							= null;
		$this->_skpd								= null;
		$this->_fraksi								= null;
		$this->_dprd								= null;
		
		if(in_array(get_userdata('group_id'), array(1, 8))) //admin atau Sekretariat Bappeda
		{
			$this->_rw								= ($this->input->get('id_rw') ? $this->input->get('id_rw') : 0);
			$this->_kelurahan						= ($this->input->get('id_kel') ? $this->input->get('id_kel') : 0);
			$this->_kecamatan						= ($this->input->get('id_kec') ? $this->input->get('id_kec') : 0);
			$this->_skpd							= ($this->input->get('id_skpd') ? $this->input->get('id_skpd') : 0);
			$this->_fraksi							= ($this->input->get('id_fraksi') ? $this->input->get('id_fraksi') : 0);
			$this->_dprd							= ($this->input->get('id_dprd') ? $this->input->get('id_dprd') : 0);
		}
		elseif(get_userdata('group_id') == 2) //kecamatan
		{
			$this->_kecamatan						= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_kec'));
			$this->_kelurahan						= ($this->input->get('id_kel') ? $this->input->get('id_kel') : null);
		}
		elseif(get_userdata('group_id') == 3) //kelurahan
		{
			$this->_kelurahan						= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_kel'));		
		}
		elseif(get_userdata('group_id') == 4) // rw
		{
			$this->_rw								= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_rw'));
		}
		elseif(get_userdata('group_id') == 5) // skpd
		{
			$this->_kecamatan						= $this->input->get('id_kec');
			$this->_skpd							= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_skpd'));
		}
		elseif(get_userdata('group_id') == 6) // Fraksi
		{
			$this->_fraksi							= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_fraksi'));
		}
		elseif(get_userdata('group_id') == 7) // DPRD
		{
			$this->_dprd							= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_dprd'));
		}
		
		$this->_tahapan								= ($this->input->get('tahapan') ? $this->input->get('tahapan') : 0);
		$this->_sub_unit							= ($this->input->get('sub_unit') ? $this->input->get('sub_unit') : 0);
		$this->_kegiatan							= ($this->input->get('kegiatan') ? $this->input->get('kegiatan') : 0);
		$this->_jenis_usulan						= ($this->input->get('jenis_usulan') ? $this->input->get('jenis_usulan') : 0);
		$this->_tahun								= get_userdata('year');
		$this->_tanggal_cetak						= ($this->input->get('tanggal_cetak') ? $this->input->get('tanggal_cetak') : date('Y-m-d'));
		
		$this->load->model('Rkpd_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan RKPD')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function rancangan_awal_rkpd()
	{
		if(!$this->_skpd_all)
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Jenis Belanja untuk melakukan ' . phrase($this->_request) . ' Rancangan Awal RKPD!', go_to());
		}
		$this->_title								= 'Rancangan Awal RKPD';
		$this->_output								= $this->report->rancangan_awal_rkpd($this->_skpd_all, $this->_sumber_dana, $this->_jenis_usulan, $this->_dprd);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function rancangan_akhir_rkpd()
	{
		if(!$this->_skpd_all)
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Jenis Belanja untuk melakukan ' . phrase($this->_request) . ' Rancangan Awal RKPD!', go_to());
		}
		$this->_title								= 'Rancangan Akhir RKPD';
		$this->_output								= $this->report->rancangan_akhir_rkpd($this->_skpd_all, $this->_sumber_dana, $this->_jenis_usulan, $this->_dprd);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function rekapitulasi_per_program()
	{
		$this->_title								= 'Laporan Rekapitulasi per Program (T-B.55)';
		$this->_output								= $this->report->rekapitulasi_per_program($this->_status, $this->_sumber_dana, $this->_rancangan);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function rekapitulasi_per_program_5_1()
	{
		$this->_title								= 'Laporan Rekapitulasi per Program (Tabel 5.1.)';
		$this->_output								= $this->report->rekapitulasi_per_program_5_1($this->_rancangan);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function rancangan_awal_rekapitulasi_rkpd()
	{
		$this->_title								= 'Laporan Rekapitulasi Rancangan Awal RKPD';
		$this->_output								= $this->report->rancangan_awal_rekapitulasi_rkpd($this->_sumber_dana);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function rancangan_akhir_rekapitulasi_rkpd()
	{
		$this->_title								= 'Laporan Rekapitulasi Rancangan Akhir RKPD';
		$this->_output								= $this->report->rancangan_akhir_rekapitulasi_rkpd($this->_sumber_dana);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function rekapitulasi_jumlah_program_kegiatan()
	{
		if(!$this->_status)
		{
			return throw_exception(403, 'Silakan pilih Status untuk melakukan ' . phrase($this->_request) . ' Rekapitulasi Jumlah Program Kegiatan!', go_to());
		}
		$this->_title								= 'Rekapitulasi Jumlah Program Kegiatan';
		$this->_output								= $this->report->rekapitulasi_jumlah_program_kegiatan($this->_tahun, $this->_status);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function rekapitulasi_jumlah_program_kegiatan_rancangan_akhir()
	{
		if(!$this->_status)
		{
			return throw_exception(403, 'Silakan pilih Status untuk melakukan ' . phrase($this->_request) . ' Rekapitulasi Jumlah Program Kegiatan!', go_to());
		}
		$this->_title								= 'Rekapitulasi Jumlah Program Kegiatan';
		$this->_output								= $this->report->rekapitulasi_jumlah_program_kegiatan_rancangan_akhir($this->_tahun, $this->_status);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function rekapitulasi_rencana_kerja_pemerintah_daerah()
	{
		if(!$this->_skpd)
		{
			return throw_exception(403, 'Silakan pilih SKPD untuk melakukan ' . phrase($this->_request) . ' Rekapitulasi Rencana Kerja Pemerintah Daerah [Form 2]!', go_to());
		}
		$this->_title								= 'Rekapitulasi Rencana Kerja Pemerintah Daerah [Form 2]';
		$this->_output								= $this->report->rekapitulasi_rencana_kerja_pemerintah_daerah($this->_tahun, $this->_skpd);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
		$this->_execute();
	}
	
	public function konsistensi_jumlah_dan_nomenklatur_program_rkpd()
	{
		if(!$this->_status)
		{
			return throw_exception(403, 'Silakan pilih Status untuk melakukan ' . phrase($this->_request) . ' Konsistensi Jumlah dan Nomenklatur Program RKPD [Form 3]!', go_to());
		}
		$this->_title								= 'Konsistensi Jumlah dan Nomenklatur Program RKPD [Form 3]';
		$this->_output								= $this->report->konsistensi_jumlah_dan_nomenklatur_program_rkpd($this->_tahun, $this->_status);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(10);
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
				'title'								=> 'Rancangan Awal RKPD',
				'description'						=> 'Rancangan Awal Rencana Kerja Pemerintah Daerah',
				'icon'								=> 'mdi-folder-key-network-outline',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'rancangan_awal_rkpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd_all(),
					'jenis_bl'						=> $this->_jenis_bl(),
					'sumber_dana'					=> $this->_sumber_dana(),
					'jenis_usulan'					=> $this->_jenis_usulan(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rancangan Akhir RKPD',
				'description'						=> 'Rancangan Akhir Rencana Kerja Pemerintah Daerah (T-B.56)',
				'icon'								=> 'mdi-move-resize-variant',
				'color'								=> 'bg-dark',
				'placement'							=> 'left',
				'controller'						=> 'rancangan_akhir_rkpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd_all(),
					'jenis_bl'						=> $this->_jenis_bl(),
					'sumber_dana'					=> $this->_sumber_dana(),
					'jenis_usulan'					=> $this->_jenis_usulan(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Per Program',
				'description'						=> 'Laporan Rekapitulasi per Program (T-B.55)',
				'icon'								=> 'mdi-nfc-variant',
				'color'								=> 'bg-fuchsia',
				'placement'							=> 'left',
				'controller'						=> 'rekapitulasi_per_program',
				'parameter'							=> array
				(
					'rancangan'						=> $this->_rancangan(),
					'sumber_dana'					=> $this->_sumber_dana(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Per Program',
				'description'						=> 'Laporan Rekapitulasi per Program (Tabel.5.1)',
				'icon'								=> 'mdi-chemical-weapon',
				'color'								=> 'bg-aqua',
				'placement'							=> 'left',
				'controller'						=> 'rekapitulasi_per_program_5_1',
				'parameter'							=> array
				(
					'rancangan'						=> $this->_rancangan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rancangan Awal Rekapitulasi RKPD',
				'description'						=> 'Laporan Rancangan Awal Rekapitulasi Rencana Kerja Pemerintah Daerah',
				'icon'								=> 'mdi-format-align-middle',
				'color'								=> 'bg-teal',
				'placement'							=> 'left',
				'controller'						=> 'rancangan_awal_rekapitulasi_rkpd',
				'parameter'							=> array
				(
					'sumber_dana'					=> $this->_sumber_dana(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rancangan Akhir Rekapitulasi RKPD',
				'description'						=> 'Laporan Rancangan Akhir Rekapitulasi Rencana Kerja Pemerintah Daerah',
				'icon'								=> 'mdi-star-half',
				'color'								=> 'bg-maroon',
				'placement'							=> 'left',
				'controller'						=> 'rancangan_akhir_rekapitulasi_rkpd',
				'parameter'							=> array
				(
					'sumber_dana'					=> $this->_sumber_dana(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Jumlah Program/Kegiatan dan Pagu Rancangan Awal RKPD [Form 1]',
				'description'						=> 'Laporan Rekapitulasi Jumlah Program/Kegiatan dan Pagu Rancangan Awal RKPD [Form 1]',
				'icon'								=> 'mdi-notebook',
				'color'								=> 'bg-success',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_jumlah_program_kegiatan',
				'parameter'							=> array
				(
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Jumlah Program/Kegiatan dan Pagu Rancangan Akhir RKPD [Form 1]',
				'description'						=> 'Laporan Rekapitulasi Jumlah Program/Kegiatan dan Pagu Rancangan Akhir RKPD [Form 1]',
				'icon'								=> 'mdi-circle-slice-6',
				'color'								=> 'bg-fuchsia',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_jumlah_program_kegiatan_rancangan_akhir',
				'parameter'							=> array
				(
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Rencana Kerja Pemerintah Daerah [Form 2]',
				'description'						=> 'Laporan Rekapitulasi Rencana Kerja Pemerintah Daerah [Form 2]',
				'icon'								=> 'mdi-city-variant',
				'color'								=> 'bg-maroon',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_rencana_kerja_pemerintah_daerah',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd_all(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Konsistensi Jumlah dan Nomenklatur Program RKPD [Form 3]',
				'description'						=> 'Laporan Konsistensi Jumlah dan Nomenklatur Program RKPD [Form 3]',
				'icon'								=> 'mdi-clipboard-account-outline',
				'color'								=> 'bg-info',
				'placement'							=> 'right',
				'controller'						=> 'konsistensi_jumlah_dan_nomenklatur_program_rkpd',
				'parameter'							=> array
				(
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			)
		);
	}
	
	private function _skpd_all()
	{
		if(get_userdata('group_id') > 1) return false;
		$output										= null;
		$query										= $this->model
		->select('ref__unit.id, ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__unit.nm_unit')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->order_by('ref__urusan.kd_urusan', 'ASC')
		->order_by('ref__bidang.kd_bidang', 'ASC')
		->order_by('ref__unit.kd_unit', 'ASC')
		->get('ref__unit')
		->result_array();
		if($query)
		{
			$output								.= '<option value="999">Pilih Semua SKPD</option>';
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . $val['kd_unit'] . '. ' . $val['nm_unit'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					SKPD
				</label>
				<select name="id_skpd" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _skpd()
	{
		if(get_userdata('group_id') > 1) return false;
		$output										= null;
		$query										= $this->model
		->select('ref__unit.id, ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__unit.nm_unit')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->order_by('ref__urusan.kd_urusan', 'ASC')
		->order_by('ref__bidang.kd_bidang', 'ASC')
		->order_by('ref__unit.kd_unit', 'ASC')
		->get('ref__unit')
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . $val['kd_unit'] . '. ' . $val['nm_unit'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					SKPD
				</label>
				<select name="id_skpd" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _sumber_dana()
	{
		$output										= null;
		$query										= $this->model
													->select('ref__sumber_dana.id, ref__sumber_dana.kode, ref__sumber_dana.nama_sumber_dana')
													->order_by('ref__sumber_dana.kode', 'ASC')
													->get('ref__sumber_dana')
													->result_array();
		if($query)
		{
			$output								.= '<option value="all">Pilih Semua Sumber Dana</option>';
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kode'] . '. ' . $val['nama_sumber_dana'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Sumber Dana
				</label>
				<select name="sumber_dana" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _jenis_bl()
	{
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Belanja Langsung
				</label>
				<select name="jenis_bl" class="form-control input-sm" placeholder="Jenis Belanja Langsung">
					<option value="1">1. Semua</option>
					<option value="2">2. Belanja Langsung Penunjang Urusan</option>
					<option value="3">3. Belanja Langsung Urusan</option>
				</select>
			</div>
		';
		return $output;
	}
	
	private function _rancangan()
	{
		return '
			<div class="row form-group">
				<div class="col-sm-12">
					<label style="margin-right:20px">
						<input type="radio" name="rancangan" value="1" checked>
						Rancangan Awal RKPD
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="rancangan" value="2">
						Rancangan Akhir RKPD
					</label>
				</div>
			</div>
		';
	}
	
	private function _status()
	{
		return '
			<div class="col-sm-6">
				<label style="margin-right:20px">
					<input type="radio" name="status" value="1" checked>
					Draft
				</label>
				<label style="margin-right:20px">
					<input type="radio" name="status" value="2">
					Final
				</label>
			</div>
		';
	}
	
	private function _jenis_usulan()
	{
		if(!in_array(get_userdata('group_id'), array(1, 8, 9))) return null;
		$query										= $this->model->get('ref__renja_jenis_usulan')->result();
		$options									= '<option value="all">Semua Jenis Usulan</option>';
		foreach($query as $key => $val)
		{
			$options								.= '<option value="' . $val->id . '">' . $val->kode . '. ' . $val->nama_jenis_usulan . '</option>';
		}
		return '
			<div class="form-group">
				<label class="control-label">
					Jenis Usulan
				</label>
				<select name="jenis_usulan" class="form-control input-sm ifPokir" placeholder="Silakan pilih Jenis Usulan">
					' . $options . '
				</select>
			</div>
		';
	}
	
	private function _is_pokir()
	{
		$primary									= $this->input->post('primary');
		$query										= $this->model->select('id, kode, nama_dewan')->get('ref__dprd')->result();
		$options									= null;
		if($query)
		{
			$options								.= '<option value="All">-- Pilih Semua DPRD --</option>';
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val->id . '">' . $val->kode . '. ' . $val->nama_dewan . '</option>';
			}
		}
		$output										= '
			<label class="control-label">
				DPRD
			</label>
			<select name="dprd" class="form-control input-sm" placeholder="Silakan pilih DPRD">
				' . $options . '
			</select>
		';
		make_json
		(
			array
			(
				'html'								=> $output
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
						<input type="text" name="tanggal_cetak" class="form-control input-sm bordered" placeholder="Pilih Tanggal" value="' . date('d M Y') . '" role="datepicker" readonly />
					</div>
				</div>
			</div>
		';
		
		return $options;
	}
}
