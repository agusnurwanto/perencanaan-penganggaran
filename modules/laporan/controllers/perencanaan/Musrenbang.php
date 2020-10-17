<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Musrenbang extends Aksara
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
			$this->_unit							= ($this->input->get('unit') ? $this->input->get('unit') : 0);
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
		
		$this->_status								= ($this->input->get('status') ? $this->input->get('status') : 0);
		$this->_sub_unit							= ($this->input->get('sub_unit') ? $this->input->get('sub_unit') : 0);
		$this->_kegiatan							= ($this->input->get('kegiatan') ? $this->input->get('kegiatan') : 0);
		$this->_jenis_usulan						= ($this->input->get('jenis_usulan') ? $this->input->get('jenis_usulan') : 0);
		$this->_tahun								= get_userdata('year');
		$this->_tanggal_cetak						= ($this->input->get('tanggal_cetak') ? $this->input->get('tanggal_cetak') : date('Y-m-d'));
		
		$this->load->model('perencanaan/Musrenbang_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan Musrenbang')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function hasil_musrenbang_rw()
	{
		/*if(!$this->_kelurahan)
		{
			generateMessages(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kelurahan!', go_to());
		}*/
		
		$this->_title								= 'Hasil Musrenbang RW';
		$this->_output								= $this->report->hasil_musrenbang_rw($this->_rw);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function hasil_musrenbang_kelurahan()
	{
		if(!$this->_kelurahan)
		{
			return throw_exception(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kelurahan!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang Kelurahan';
		$this->_output								= $this->report->hasil_musrenbang_kelurahan($this->_kelurahan);

		$this->_execute();
	}
	public function ba_hasil_musrenbang_kelurahan()
	{
		if(!$this->_kelurahan)
		{
			return throw_exception(403, 'Silakan pilih Kelurahan ' . phrase($this->_request) . ' Berita Acara Hasil Musrenbang Kelurahan!', go_to());
		}
		$this->_title								= 'Berita Acara Hasil Musrenbang Kelurahan';
		$this->_output								= $this->report->ba_hasil_musrenbang_kelurahan($this->_kelurahan);
		//$this->wkhtmltopdf->pageSize('8.5in', '13.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function rekapitulasi_musrenbang_kelurahan()
	{
		$this->_title								= 'Rekapitulasi Musrenbang Kelurahan';
		$this->_output								= $this->report->rekapitulasi_musrenbang_kelurahan();
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function hasil_musrenbang_kelurahan_per_program()
	{
		if(!$this->_kelurahan)
		{
		return throw_exception(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kelurahan Per Program!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang Kelurahan per Program';
		$this->_output								= $this->report->hasil_musrenbang_kelurahan_per_program($this->_kelurahan);
		$this->_execute();
	}
	
	public function hasil_musrenbang_kelurahan_per_bidang_bappeda()
	{
		if(!$this->_kelurahan)
		{
		return throw_exception(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kelurahan per Bidang Bappeda!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang Kelurahan per Bidang Bappeda';
		$this->_output								= $this->report->hasil_musrenbang_kelurahan_per_bidang_bappeda($this->_kelurahan);
		$this->_execute();
	}
	
	public function rekapitulasi_musrenbang_kelurahan_per_isu()
	{
		$this->_title								= 'Rekapitulasi Musrenbang Kelurahan Berdasarkan Isu';
		$this->_output								= $this->report->rekapitulasi_musrenbang_kelurahan_per_isu();
		$this->_execute();
	}
	
	public function daftar_prioritas_kecamatan()
	{
		if(!$this->_kecamatan)
		{
			return throw_exception(403, 'Silakan pilih Kecamatan dan Status untuk ' . phrase($this->_request) . ' Daftar Urutan Kegiatan Prioritas Kecamatan Menurut Perangkat Daerah!', go_to());
		}
		$this->_title								= 'Daftar Urutan Kegiatan Prioritas Kecamatan Menurut Perangkat Daerah';
		$this->_output								= $this->report->daftar_prioritas_kecamatan($this->_kecamatan);
		$this->_execute();
	}
	
	public function daftar_prioritas_kelurahan()
	{
		if(!$this->_kelurahan)
		{
			return throw_exception(403, 'Silakan pilih Kelurahan dan Status untuk ' . phrase($this->_request) . ' Daftar Urutan Kegiatan Prioritas Kelurahan Menurut Perangkat Daerah!', go_to());
		}
		$this->_title								= 'Daftar Urutan Kegiatan Prioritas Kelurahan Menurut Perangkat Daerah';
		$this->_output								= $this->report->daftar_prioritas_kelurahan($this->_kelurahan);
		$this->_execute();
	}
	
	public function ba_hasil_musrenbang_kecamatan()
	{
		if(!$this->_kecamatan)
		{
			return throw_exception(403, 'Silakan pilih Kecamatan ' . phrase($this->_request) . ' Berita Acara Hasil Musrenbang Kecamatan!', go_to());
		}
		$this->_title								= 'Berita Acara Hasil Musrenbang Kecamatan';
		$this->_output								= $this->report->ba_hasil_musrenbang_kecamatan($this->_kecamatan);
		$this->_execute();
	}
	
	public function hasil_musrenbang_kecamatan()
	{
		if(!$this->_kecamatan)
		{
		return throw_exception(403, 'Silakan pilih Kecamatan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kecamatan!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang Kecamatan';
		$this->_output								= $this->report->hasil_musrenbang_kecamatan($this->_kecamatan);
		$this->_execute();
	}
	
	public function rekapitulasi_musrenbang_kecamatan()
	{
		$this->_title								= 'Rekapitulasi Musrenbang Kecamatan';
		$this->_output								= $this->report->rekapitulasi_musrenbang_kecamatan();
		$this->_execute();
	}
	
	public function hasil_musrenbang_kecamatan_per_program()
	{
		if(!$this->_kecamatan)
		{
			return throw_exception(403, 'Silakan pilih Kecamatan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kecamatan Per Program!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang Kecamatan per Program';
		$this->_output								= $this->report->hasil_musrenbang_kecamatan_per_program($this->_kecamatan);
		$this->_execute();
	}
	
	public function hasil_musrenbang_kecamatan_per_bidang_bappeda()
	{
		if(!$this->_kecamatan)
		{
			return throw_exception(403, 'Silakan pilih Kecamatan dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang Kecamatan per Bidang Bappeda!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang Kecamatan per Bidang Bappeda';
		$this->_output								= $this->report->hasil_musrenbang_kecamatan_per_bidang_bappeda($this->_kecamatan);
		$this->_execute();
	}
	
	public function hasil_musrenbang_skpd()
	{
		if(!$this->_unit)
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang SKPD';
		$this->_output								= $this->report->hasil_musrenbang_skpd($this->_unit, $this->_status);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function rekapitulasi_musrenbang_skpd()
	{
		$this->_title								= 'Rekapitulasi Musrenbang SKPD';
		$this->_output								= $this->report->rekapitulasi_musrenbang_skpd();
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function hasil_musrenbang_skpd_per_program()
	{
		if(!$this->_unit)
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD Per Program!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang SKPD per Program';
		$this->_output								= $this->report->hasil_musrenbang_skpd_per_program($this->_unit, $this->_status);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function hasil_musrenbang_skpd_per_bidang_bappeda()
	{
		if(!$this->_unit)
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD per Bidang Bappeda!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang SKPD per Bidang Bappeda';
		$this->_output								= $this->report->hasil_musrenbang_skpd_per_bidang_bappeda($this->_unit, $this->_status);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function hasil_musrenbang_skpd_bidang_bappeda()
	{
		if(!$this->input->get('bidang_bappeda'))
		{
			return throw_exception(403, 'Silakan pilih Bidang Bappeda dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Musrenbang SKPD Bidang Bappeda!', go_to());
		}
		$this->_title								= 'Hasil Musrenbang SKPD per Bidang Bappeda';
		$this->_output								= $this->report->hasil_musrenbang_skpd_bidang_bappeda($this->input->get('bidang_bappeda'));
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
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
				'title'								=> 'Berita Acara Hasil Musrenbang Kelurahan',
				'description'						=> 'Laporan Berita Acara Hasil Musrenbang Kelurahan',
				'icon'								=> 'mdi-chart-arc',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'ba_hasil_musrenbang_kelurahan',
				'parameter'							=> array
				(
					'kelurahan'						=> $this->_kelurahan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Hasil Musrenbang Kelurahan',
				'description'						=> 'Laporan Hasil Musrenbang Kelurahan',
				'icon'								=> 'mdi-chart-arc',
				'color'								=> 'bg-teal',
				'placement'							=> 'left',
				'controller'						=> 'hasil_musrenbang_kelurahan',
				'parameter'							=> array
				(
					'kelurahan'						=> $this->_kelurahan(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Musrenbang Kelurahan',
				'description'						=> 'Rekapitulasi Musrenbang Kelurahan',
				'icon'								=> 'mdi-chart-areaspline',
				'color'								=> 'bg-danger',
				'placement'							=> 'left',
				'controller'						=> 'rekapitulasi_musrenbang_kelurahan',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang Kelurahan per Program',
				'description'						=> 'Musrenbang Kelurahan per Program',
				'icon'								=> 'mdi-chart-bar',
				'color'								=> 'bg-maroon',
				'placement'							=> 'left',
				'controller'						=> 'hasil_musrenbang_kelurahan_per_program',
				'parameter'							=> array
				(
					'kelurahan'						=> $this->_kelurahan(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang Kelurahan per Bidang Bappeda',
				'description'						=> 'Musrenbang Kelurahan per Bidang Bappeda',
				'icon'								=> 'mdi-chart-bar-stacked',
				'color'								=> 'bg-success',
				'placement'							=> 'left',
				'controller'						=> 'hasil_musrenbang_kelurahan_per_bidang_bappeda',
				'parameter'							=> array
				(
					'kelurahan'						=> $this->_kelurahan(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Musrenbang Kelurahan Berdasarkan Isu',
				'description'						=> 'Rekapitulasi Musrenbang Kelurahan Berdasarkan Isu',
				'icon'								=> 'mdi-chart-bell-curve',
				'color'								=> 'bg-info',
				'placement'							=> 'left',
				'controller'						=> 'rekapitulasi_musrenbang_kelurahan_per_isu',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Daftar Urutan Kegiatan Prioritas Kelurahan Menurut Perangkat Daerah',
				'description'						=> 'Daftar Urutan Kegiatan Prioritas Kelurahan Menurut Perangkat Daerah',
				'icon'								=> 'mdi-chart-bubble',
				'color'								=> 'bg-warning',
				'placement'							=> 'left',
				'controller'						=> 'daftar_prioritas_kelurahan',
				'parameter'							=> array
				(
					'kelurahan'						=> $this->_kelurahan(),
					'status'						=> $this->_status(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Berita Acara Hasil Musrenbang Kecamatan',
				'description'						=> 'Laporan Berita Acara Hasil Musrenbang Kecamatan',
				'icon'								=> 'mdi-chart-gantt',
				'color'								=> 'bg-primary',
				'placement'							=> 'right',
				'controller'						=> 'ba_hasil_musrenbang_kecamatan',
				'parameter'							=> array
				(
					'kecamatan'						=> $this->_kecamatan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang Kecamatan',
				'description'						=> 'Musrenbang Kecamatan',
				'icon'								=> 'mdi-chart-donut-variant',
				'color'								=> 'bg-teal',
				'placement'							=> 'right',
				'controller'						=> 'hasil_musrenbang_kecamatan',
				'parameter'							=> array
				(
					'kecamatan'						=> $this->_kecamatan(),
					'status'						=> $this->_status('kecamatan'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Musrenbang Kecamatan',
				'description'						=> 'Rekapitulasi Musrenbang Kecamatan',
				'icon'								=> 'mdi-chart-histogram',
				'color'								=> 'bg-danger',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_musrenbang_kecamatan',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang Kecamatan per Program',
				'description'						=> 'Musrenbang Kecamatan per Program',
				'icon'								=> 'mdi-chart-line',
				'color'								=> 'bg-maroon',
				'placement'							=> 'right',
				'controller'						=> 'hasil_musrenbang_kecamatan_per_program',
				'parameter'							=> array
				(
					'kecamatan'						=> $this->_kecamatan(),
					'status'						=> $this->_status('kecamatan'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang Kecamatan per Bidang Bappeda',
				'description'						=> 'Musrenbang Kecamatan per Bidang Bappeda',
				'icon'								=> 'mdi-chart-line-stacked',
				'color'								=> 'bg-success',
				'placement'							=> 'right',
				'controller'						=> 'hasil_musrenbang_kecamatan_per_bidang_bappeda',
				'parameter'							=> array
				(
					'kecamatan'						=> $this->_kecamatan(),
					'status'						=> $this->_status('kecamatan'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Daftar Urutan Kegiatan Prioritas Kecamatan Menurut Perangkat Daerah',
				'description'						=> 'Daftar Urutan Kegiatan Prioritas Kecamatan Menurut Perangkat Daerah',
				'icon'								=> 'mdi-chart-pie',
				'color'								=> 'bg-info',
				'placement'							=> 'right',
				'controller'						=> 'daftar_prioritas_kecamatan',
				'parameter'							=> array
				(
					'kecamatan'						=> $this->_kecamatan(),
					'status'						=> $this->_status('kecamatan'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang SKPD',
				'description'						=> 'Musrenbang SKPD',
				'icon'								=> 'mdi-chart-scatterplot-hexbin',
				'color'								=> 'bg-warning',
				'placement'							=> 'right',
				'controller'						=> 'hasil_musrenbang_skpd',
				'parameter'							=> array
				(
					'unit'							=> $this->_unit(),
					'status'						=> $this->_status('skpd'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Musrenbang SKPD',
				'description'						=> 'Rekapitulasi Musrenbang SKPD',
				'icon'								=> 'mdi-chart-timeline',
				'color'								=> 'bg-fuchsia',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_musrenbang_skpd',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang SKPD Per Program',
				'description'						=> 'Musrenbang SKPD Per Program',
				'icon'								=> 'mdi-movie-roll',
				'color'								=> 'bg-olive',
				'placement'							=> 'right',
				'controller'						=> 'hasil_musrenbang_skpd_per_program',
				'parameter'							=> array
				(
					'unit'							=> $this->_unit(),
				//	'jenis_usulan'					=> $this->_jenis_usulan(),
					'status'						=> $this->_status('skpd'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang SKPD Per Bidang Bappeda',
				'description'						=> 'Musrenbang SKPD Per Bidang Bappeda',
				'icon'								=> 'mdi-folder-search-outline',
				'color'								=> 'bg-green',
				'placement'							=> 'right',
				'controller'						=> 'hasil_musrenbang_skpd_per_bidang_bappeda',
				'parameter'							=> array
				(
					'unit'							=> $this->_unit(),
					'status'						=> $this->_status('skpd'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Musrenbang SKPD Untuk Bidang Bappeda',
				'description'						=> 'Musrenbang SKPD Untuk Bidang Bappeda',
				'icon'								=> 'mdi-food-croissant',
				'color'								=> 'bg-orange',
				'placement'							=> 'right',
				'controller'						=> 'hasil_musrenbang_skpd_bidang_bappeda',
				'parameter'							=> array
				(
					'bidang_bappeda'				=> $this->_bidang_bappeda(),
					'status'						=> $this->_status('skpd'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
		);
		
	}
	
	private function _unit()
	{
		if(get_userdata('group_id') > 1 AND get_userdata('group_id') != 8 ) return false;
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
					UNIT
				</label>
				<select name="unit" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _kecamatan()
	{
		if(get_userdata('group_id') > 1 AND get_userdata('group_id') != 5 AND get_userdata('group_id') != 8 ) return false;
		$output										= null;
		$query										= $this->model->get('ref__kecamatan')->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kode'] . '. ' . $val['kecamatan'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Kecamatan
				</label>
				<select name="id_kec" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _kelurahan()
	{
		if(!in_array(get_userdata('group_id'), array(1, 2, 8))) return false;
		$output										= null;
		if(2 == get_userdata('group_id'))
		{
			$this->model->where('ref__kecamatan.id', get_userdata('sub_unit'));
		}
		$query										= $this->model->select('ref__kecamatan.kode, ref__kecamatan.kecamatan, ref__kelurahan.id, ref__kelurahan.kode as kode_kelurahan, ref__kelurahan.nama_kelurahan')->join('ref__kecamatan', 'ref__kecamatan.id = ref__kelurahan.id_kec')->get('ref__kelurahan')->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kode'] . '.' . $val['kode_kelurahan'] . '. ' . $val['nama_kelurahan'] . ' - ' . $val['kecamatan'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Kelurahan
				</label>
				<select name="id_kel" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _bidang_bappeda()
	{
		return '
			<div class="row form-group">
				<div class="col-sm-12">
					<label class="control-label">
						Bidang Bappeda
					</label>
					<br />
					<label style="margin-right:20px">
						<input type="radio" name="bidang_bappeda" value="1" checked>
						IPW
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="bidang_bappeda" value="2">
						PMM
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="bidang_bappeda" value="3">
						ESDA
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="bidang_bappeda" value="4">
						Semua
					</label>
				</div>
			</div>
		';
	}
	
	private function _status($request = 'kelurahan')
	{
		/**
		 * @param string untuk jenis permintaan
		 * @default 'kelurahan'
		*/
		if('kelurahan' == $request)
		{
			$option 								= '
				<option value="1">1. Usulan RW</option>
				<option value="2">2. Diterima Kelurahan</option>
				<option value="3">3. Ditolak Kelurahan</option>
				<option value="4">4. Usulan Kelurahan</option>
				<option value="5">5. Pilih Semua</option>
			';
		}
		elseif('kecamatan' == $request)
		{
			$option 								= '
				<option value="1">1. Usulan Kelurahan</option>
				<option value="2">2. Diterima Kecamatan</option>
				<option value="3">3. Ditolak Kecamatan</option>
				<option value="4">4. Usulan Kecamatan</option>
				<option value="5">5. Semua Status</option>
				<option value="6">6. Diterima dan Usulan Kecamatan</option>
			';
		}
		else
		{
			$option 								= '
				<option value="1">1. Usulan kecamatan</option>
				<option value="2">2. Diterima SKPD</option>
				<option value="3">3. Ditolak SKPD</option>
				<option value="4">4. Semua Status</option>
			';
		}		
		return '
			<div class="form-group">
				<label class="control-label">
					Status
				</label>
				<br />
				<select name="status" class="form-control input-sm" placeholder="Silakan pilih status">
					' . $option . '
				</select>
			</div>
		';
	}
	
	/**
	 * getting the volume variable
	 */
	public function get_volume($variable = array())
	{
		/* safe return if $variable is not array or empty */
		if(!is_array($variable) || sizeof($variable) < 0) return false;
		
		/* get the variable key (variable id) */
		$variable_key								= array_keys($variable);
		
		/* query */
		$query										= $this->model
		->select
		('
			id,
			nama_variabel,
			satuan
		')
		/* where in variable */
		->where_in('id', $variable_key)
		->get('ref__musrenbang_variabel')
		->result();
		
		$output										= null;
		if($query)
		{
			/* loop the result */
			foreach($query as $key => $val)
			{
				/* append variable found */ 
				$output								.= ($output ? ' ' : null) . $val->nama_variabel . ': ' . (isset($variable[$val->id]) ? (is_int($variable[$val->id]) ? number_format($variable[$val->id]) : $variable[$val->id]) : 0) . ' ' . $val->satuan;
			}
		}
		
		/* throwback output into view */
		return $output;
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
