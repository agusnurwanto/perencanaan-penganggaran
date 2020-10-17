<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Rka extends Aksara
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
			// Grup User Sub Unit
		if(in_array(get_userdata('group_id'), array(11, 12)))
		{
			$this->_unit							= $this->model
													->select('id_unit')
													->limit(1)
													->get_where('ref__sub', array('id' => get_userdata('sub_level_1')))
													->row('id_unit');
			$this->_sub_unit						= get_userdata('sub_level_1');
		}
		$this->_program								= ($this->input->get('program') ? $this->input->get('program') : null);
		$this->_kegiatan							= ($this->input->get('kegiatan') ? $this->input->get('kegiatan') : null);
		$this->_sub_kegiatan						= ($this->input->get('sub_kegiatan') ? $this->input->get('sub_kegiatan') : null);
		$this->_rekening							= ($this->input->get('rekening') ? $this->input->get('rekening') : null);
		$this->_tanggal_cetak						= ($this->input->get('tanggal_cetak') ? $this->input->get('tanggal_cetak') : date('Y-m-d'));
		
				
		//$this->_jenis_usulan						= ($this->input->get('jenis_usulan') ? $this->input->get('jenis_usulan') : 0);
		
		$this->load->model('anggaran/Rka_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
		
		/**
		 * Generate the unique initial for shortlink
		 */
		$unique										= sha1(current_page());
		$f_unique									= substr($unique, 0, 3);
		$l_unique									= substr($unique, -3);
		$unique										= $f_unique . $l_unique;
		$this->_shortlink							= base_url('s/' . $unique);
		
		/**
		 * Save initialized shortlink into database
		 */
		if(!$this->model->get_where('app__shortlink', array('hash' => $unique), 1)->row())
		{
			$this->model->insert('app__shortlink', array('hash' => $unique, 'url' => current_page(), 'session' => json_encode($this->session->userdata())));
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan Rencana Kerja Anggaran (RKA)')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function rka_skpd()
	{
		if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA SKPD', current_page('../'));
		}
		
		$this->load->library('ciqrcode');
		
		$config										= array
		(
			'cacheable'								=> true,
			'cachedir'								=> 'uploads/___qrcode/',
			'errorlog'								=> 'uploads/___qrcode/',
			'imagedir'								=> 'uploads/___qrcode/',
			'size'									=> 100
		);
		
		$this->ciqrcode->initialize($config);
		
		$filename									= sha1(current_page(null, array('catch' => null)) . SALT) . '.png';
		$params										= array
		(
			'data'									=> $this->_shortlink,
			'savename'								=> FCPATH . $config['imagedir'] . $filename
		);
		
		$this->ciqrcode->generate($params);
		
		$this->_title								= 'Laporan RKA - SKPD';
		$this->_output								= $this->report->rka_skpd($this->_tahun, $this->_unit, $this->_sub_unit);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function rka_pendapatan_skpd()
	{
		if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA Pendapatan SKPD', current_page('../'));
		}
		
		$this->load->library('ciqrcode');
		
		$config										= array
		(
			'cacheable'								=> true,
			'cachedir'								=> 'uploads/___qrcode/',
			'errorlog'								=> 'uploads/___qrcode/',
			'imagedir'								=> 'uploads/___qrcode/',
			'size'									=> 100
		);
		
		$this->ciqrcode->initialize($config);
		
		$filename									= sha1(current_page(null, array('catch' => null)) . SALT) . '.png';
		$params										= array
		(
			'data'									=> $this->_shortlink,
			'savename'								=> FCPATH . $config['imagedir'] . $filename
		);
		
		$this->ciqrcode->generate($params);
		
		$this->_title								= 'Laporan RKA Pendapatan SKPD';
		$this->_output								= $this->report->rka_pendapatan_skpd($this->_tahun, $this->_unit, $this->_sub_unit);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function rka_belanja_skpd()
	{
		if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA Belanja SKPD', current_page('../'));
		}
		
		$this->_title								= 'Laporan RKA Belanja SKPD';
		$this->_output								= $this->report->rka_belanja_skpd($this->_tahun, $this->_unit);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function rka_rincian_belanja()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih kegiatan untuk melakukan ' . phrase($this->_request) . ' RKA Rincian Belanja!', current_page('../'));
		}
		
		/*$jenis_anggaran								= $this->model->select('jenis_anggaran')->get_where(
			'ta__kegiatan_sub',
			array
			(
				'id'								=> $this->_sub_kegiatan
			)
		)
		->row('jenis_anggaran');
		
		if($jenis_anggaran < 2)
		{
			return throw_exception(301, 'Kegiatan Perubahan', current_page('../rkap_221'));
		}
		*/
		$this->load->library('ciqrcode');
		
		$config										= array
		(
			'cacheable'								=> true,
			'cachedir'								=> 'uploads/___qrcode/',
			'errorlog'								=> 'uploads/___qrcode/',
			'imagedir'								=> 'uploads/___qrcode/',
			'size'									=> 100
		);
		
		$this->ciqrcode->initialize($config);
		
		$filename									= sha1(current_page(null, array('catch' => null)) . SALT) . '.png';
		$params										= array
		(
			'data'									=> $this->_shortlink,
			'savename'								=> FCPATH . $config['imagedir'] . $filename
		);
		
		$this->ciqrcode->generate($params);
		
		$this->_title								= 'Laporan RKA Rincian Belanja';
		$this->_output								= $this->report->rka_rincian_belanja($this->_tahun, $this->_sub_unit, $this->_program, $this->_kegiatan);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function rka_sub_kegiatan()
	{
		if(!$this->_sub_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih Sub kegiatan untuk melakukan ' . phrase($this->_request) . ' RKA Sub Kegiatan!', current_page('../'));
		}
		
		/*$jenis_anggaran								= $this->model->select('jenis_anggaran')->get_where(
			'ta__kegiatan_sub',
			array
			(
				'id'								=> $this->_sub_kegiatan
			)
		)
		->row('jenis_anggaran');
		
		if($jenis_anggaran < 2)
		{
			return throw_exception(301, 'Kegiatan Perubahan', current_page('../rkap_221'));
		}*/
		
		$this->load->library('ciqrcode');
		
		$config										= array
		(
			'cacheable'								=> true,
			'cachedir'								=> 'uploads/___qrcode/',
			'errorlog'								=> 'uploads/___qrcode/',
			'imagedir'								=> 'uploads/___qrcode/',
			'size'									=> 100
		);
		
		$this->ciqrcode->initialize($config);
		
		$filename									= sha1(current_page(null, array('catch' => null)) . SALT) . '.png';
		$params										= array
		(
			'data'									=> $this->_shortlink,
			'savename'								=> FCPATH . $config['imagedir'] . $filename
		);
		
		$this->ciqrcode->generate($params);
		
		$this->_title								= 'Laporan RKA Sub Kegiatan';
		$this->_output								= $this->report->rka_sub_kegiatan($this->_tahun, $this->_sub_unit, $this->_program, $this->_kegiatan, $this->_sub_kegiatan);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function rka_pembiayaan_skpd()
	{
		if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA Pembiayaan SKPD', current_page('../'));
		}
		
		$this->load->library('ciqrcode');
		
		$config										= array
		(
			'cacheable'								=> true,
			'cachedir'								=> 'uploads/___qrcode/',
			'errorlog'								=> 'uploads/___qrcode/',
			'imagedir'								=> 'uploads/___qrcode/',
			'size'									=> 100
		);
		
		$this->ciqrcode->initialize($config);
		
		$filename									= sha1(current_page(null, array('catch' => null)) . SALT) . '.png';
		$params										= array
		(
			'data'									=> $this->_shortlink,
			'savename'								=> FCPATH . $config['imagedir'] . $filename
		);
		
		$this->ciqrcode->generate($params);
		
		$this->_title								= 'Laporan RKA Pembiayaan SKPD';
		$this->_output								= $this->report->rka_pembiayaan_skpd($this->_tahun, $this->_unit, $this->_sub_unit);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function rekening()
	{
		if(!$this->_rekening)
		{
			return throw_exception(404, 'Silakan pilih Rekening untuk melakukan ' . phrase($this->_request) . ' Rekening!', current_page('../'));
		}
		
		$this->_title								= 'Laporan Rekening';
		$this->_output								= $this->report->rekening($this->_tahun, $this->_rekening);
		
		$this->_execute();
	}
	
	public function sumber_dana()
	{		
		$this->_title								= 'Laporan Sumber Dana';
		$this->_output								= $this->report->sumber_dana($this->_tahun);
		
		$this->_execute();
	}
	
	public function anggaran_kas()
	{
		if(!$this->_sub_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih Sub Kegiatan untuk melakukan ' . phrase($this->_request) . ' Laporan Anggaran Kas!', current_page('../'));
		}
		
		$this->_title								= 'Laporan Anggaran Kas';
		$this->_output								= $this->report->anggaran_kas($this->_tahun, $this->_sub_kegiatan);
		
		$this->_execute();
	}
	/*
	public function rekapitulasi_anggaran_kas_kegiatan()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih kegiatan untuk melakukan ' . phrase($this->_request) . ' Rekapitulasi Anggaran Kas Kegiatan!', current_page('../'));
		}
		
		$this->_title								= 'Rekapitulasi Anggaran Kas Kegiatan';
		$this->_output								= $this->report->rekapitulasi_anggaran_kas_kegiatan($this->_kegiatan);
		
		$this->_execute();
	}
	
	public function rekapitulasi_anggaran_kas_per_bulan()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih kegiatan untuk melakukan ' . phrase($this->_request) . ' Rekapitulasi Anggaran Kas Per Bulan!', current_page('../'));
		}
		
		$this->_title								= 'Rekapitulasi Anggaran Kas Kegiatan';
		$this->_output								= $this->report->rekapitulasi_anggaran_kas_per_bulan($this->_kegiatan);
		
		$this->_execute();
	}
	
	public function rekapitulasi_model_rka()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih kegiatan untuk melakukan ' . phrase($this->_request) . ' Rekapitulasi Model RKA!', current_page('../'));
		}
		
		$this->_title								= 'Rekapitulasi Anggaran Kas Kegiatan';
		$this->_output								= $this->report->rekapitulasi_anggaran_kas_per_bulan($this->_kegiatan);
		
		$this->_execute();
	}
	
	public function ringkasan()
	{
		if(!$this->_jenis_data)
		{
			return throw_exception(403, 'Silakan pilih Jenis Data untuk ' . phrase($this->_request) . ' Laporan Ringkasan!', go_to());
		}
		$this->_title								= 'Ringkasan';
		$this->_output								= $this->report->ringkasan($this->_jenis_data, $this->_tahun);
		$this->_execute();
	}
	
	public function perbandingan_plafon_anggaran_kegiatan()
	{
		if(!$this->_unit)
		{
			return throw_exception(403, 'Silakan pilih SKPD untuk ' . phrase($this->_request) . ' Laporan Perbandingan Plafon dengan Anggaran Per Kegiatan!', go_to());
		}
		$this->_title								= 'Perbandingan Plafon dengan Anggaran Per Kegiatan';
		$this->_output								= $this->report->perbandingan_plafon_anggaran_kegiatan($this->_unit);
	
		$this->_execute();
	}
	
	public function perbandingan_plafon_anggaran_skpd()
	{
		$this->_title								= 'Perbandingan Plafon dengan Anggaran Per Kegiatan';
		$this->_output								= $this->report->perbandingan_plafon_anggaran_skpd($this->_tahun);

		$this->_execute();
	}
	
	public function standar_harga()
	{
		if(!$this->_jenis_standar_harga or !$this->_standar_harga_pilihan)
		{
			return throw_exception(403, 'Silakan pilih Standar Harga dan Jenis nya untuk melihat ' . phrase($this->_request) . ' Laporan Standar Harga!', go_to());
		}
		$this->_title								= 'Laporan Standar Harga';
		$this->_output								= $this->report->standar_harga($this->_jenis_standar_harga, $this->_standar_harga_pilihan, $this->_tahun);
	
		$this->_execute();
	}
	
	public function rekapitulasi_standar_harga()
	{
		$this->_title								= 'Laporan Rekapitulasi Standar Harga';
		$this->_output								= $this->report->rekapitulasi_standar_harga($this->_tahun);
	
		$this->_execute();
	}
	
	public function lembar_asistensi()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(403, 'Silakan pilih Kegiatan untuk ' . phrase($this->_request) . ' Lembar Asistensi!', go_to());
		}
		$this->_title								= 'Lembar Asistensi';
		$this->_output								= $this->report->lembar_asistensi($this->_kegiatan);
		
		$this->_execute();
	}
	
	public function lembar_kak()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(403, 'Silakan pilih Kegiatan untuk ' . phrase($this->_request) . ' Lembar Verifikasi KAK!', go_to());
		}
		$this->_title								= 'Lembar Verifikasi KAK';
		$this->_output								= $this->report->lembar_kak($this->_kegiatan);
		
		$this->_execute();
	}
	
	public function rekapitulasi_rekening()
	{
		if(!$this->_unit)
		{
			return throw_exception(403, 'Silakan pilih SKPD untuk ' . phrase($this->_request) . ' Laporan Rekapitulasi Rekening!', go_to());
		}
		$this->_title								= 'Rekapitulasi Rekening';
		$this->_output								= $this->report->rekapitulasi_rekening($this->_unit);
		
		$this->_execute();
	}
	*/
	
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
				'title'								=> 'Laporan RKA - SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran SKPD',
				'icon'								=> 'mdi-chart-arc',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'rka_skpd',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					//'sub_kegiatan'					=> $this->_sub_kegiatan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Laporan RKA - Pendapatan SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Pendapatan SKPD',
				'icon'								=> 'mdi-chart-bell-curve',
				'color'								=> 'bg-teal',
				'placement'							=> 'left',
				'controller'						=> 'rka_pendapatan_skpd',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					//'skpd'							=> $this->_unit(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
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
					//'tanggal_cetak'					=> $this->_tanggal_cetak()
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
					//'tanggal_cetak'					=> $this->_tanggal_cetak()
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
					'jenis_anggaran'				=> $this->_jenis_anggaran()
					//'tanggal_cetak'					=> $this->_tanggal_cetak()
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
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekening',
				'description'						=> 'Laporan Anggaran Kas',
				'icon'								=> 'mdi-comment-check-outline',
				'color'								=> 'bg-primary',
				'placement'							=> 'right',
				'controller'						=> 'rekening',
				'parameter'							=> array
				(
					'rek_1'							=> $this->_rek_1(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Sumber Dana',
				'description'						=> 'Laporan Sumber Dana',
				'icon'								=> 'mdi-chart-pie',
				'color'								=> 'bg-teal',
				'placement'							=> 'right',
				'controller'						=> 'sumber_dana',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Anggaran Kas',
				'description'						=> 'Laporan Anggaran Kas',
				'icon'								=> 'mdi-chart-timeline',
				'color'								=> 'bg-danger',
				'placement'							=> 'right',
				'controller'						=> 'anggaran_kas',
				'parameter'							=> array
				(
					'sub_kegiatan'					=> $this->_sub_kegiatan(),
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
	
	private function _kegiatan()
	{
		$output										= null;
			// Super Admin, Admin Perencanaan, Admin Keuangan, Admin Monev, Admin RUP, Tim Asistensi, TAPD TTD, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		if(in_array(get_userdata('group_id'), array(1, 2, 3, 4, 5, 16, 17, 18, 19, 20, 21)))
		{
			$query									= $this->model
			->select
			('
				ref__sub.id,
				ref__sub.nm_sub,
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub
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
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . $val['kd_unit'] . '.' . $val['kd_sub'] . ' ' . $val['nm_sub'] . '</option>';
				}
				$output								.= '
					<div class="form-group">
						<label class="control-label">
							Sub Unit
						</label>
						<select name="sub_unit" class="form-control form-control-sm report-dropdown" to-change=".program">
							<option value="">Silakan pilih Sub Unit</option>
							' . $options . '
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
					'ref__program.id !='			=> NULL,
					'ref__sub.id'					=> $this->_sub_unit,
					'ref__sub.id_unit'				=> $this->_unit
				)
			)
			->result_array();
			
			if($query)
			{
				$options							= null;
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_program'] . '. ' . $val['nm_program'] . '</option>';
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
			$query									= $this->model
			->select
			('
				ref__sub.id,
				ref__sub.nm_sub,
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub
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
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . $val['kd_unit'] . '.' . $val['kd_sub'] . ' ' . $val['nm_sub'] . '</option>';
				}
				$output								.= '
					<div class="form-group">
						<label class="control-label">
							Sub Unit
						</label>
						<select name="sub_unit" class="form-control form-control-sm report-dropdown" to-change=".program">
							<option value="">Silakan pilih Sub Unit</option>
							' . $options . '
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
					'ref__program.id !='			=> NULL,
					'ref__sub.id'					=> $this->_sub_unit,
					'ref__sub.id_unit'				=> $this->_unit
				)
			)
			->result_array();
			
			if($query)
			{
				$options							= null;
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_program'] . '. ' . $val['nm_program'] . '</option>';
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
			$query									= $this->model
			->select('id, kd_sub, nm_sub')
			->order_by('kd_sub')
			->get_where('ref__sub', array('id_unit' => $primary))
			->result_array();
			if($query)
			{
				$options							= ('unit' == $this->input->post('referer') ? '<option value="all">Semua Sub Unit</option>' : '<option value="">Silakan pilih Sub Unit</option>');
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_sub'] . '. ' . $val['nm_sub'] . '</option>';
				}
			}
		}
		elseif('.program' == $element)
		{
			$query									= $this->model
													->select
													('
														ta__program.id,
														ref__program.kd_program,
														ref__program.nm_program
													')
													->join('ta__program', 'ta__program.id_prog = ref__program.id')
													->order_by('ref__program.kd_program')
													->get_where('ref__program', array('ta__program.id_sub' => $primary))
													->result_array();
			if($query)
			{
				$options							= '<option value="">Silakan pilih Program</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_program'] . '. ' . $val['nm_program'] . '</option>';
				}
			}
		}
		elseif('.kegiatan' == $element)
		{
			$query									= $this->model
			->select('id, kd_keg, kegiatan')
			->order_by('kd_keg')
			->get_where('ta__kegiatan', array('id_prog' => $primary))
			->result_array();
			if($query)
			{
				$options							= '<option value="">Silakan pilih Kegiatan</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_keg'] . '. ' . $val['kegiatan'] . '</option>';
				}
			}
		}
		elseif('.sub_kegiatan' == $element)
		{
			$query									= $this->model
			->select('id, kd_keg_sub, kegiatan_sub')
			->order_by('kd_keg_sub')
			->get_where('ta__kegiatan_sub', array('id_keg' => $primary))
			->result_array();
			
			if($query)
			{
				$options							= '<option value="">Silakan pilih Sub Kegiatan</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_keg_sub'] . '. ' . $val['kegiatan_sub'] . '</option>';
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
	
	private function _unit()
	{
		// Super Admin, Admin Perencanaan, Admin Keuangan, Admin Monev, Admin RUP, Tim Asistensi, TAPD TTD, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		if(!in_array(get_userdata('group_id'), array(1, 2, 3, 4, 5, 16, 17, 18, 19, 20, 21))) return false;
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
					Unit
				</label>
				<select name="unit" class="form-control form-control-sm report-dropdown" to-change=".sub_unit">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _unit_sub_unit()
	{	
		// Global Administrator, Admin Perencanaan, Admin Keuangan, Admin Monev, Verifikatur SSH, Tim Asistensi, TAPD TTD, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa, Anggaran Pendapatan, Anggaran Pembiayaan
		if(in_array(get_userdata('group_id'), array(1, 2, 3, 4, 15, 16, 17, 18, 19, 20, 21, 22, 23, 24))) 
		{
			$id_unit								= $this->input->post('primary');
			if($id_unit)
			{
				$query		= $this->model->query
							('
								SELECT
									ref__sub.id,
									ref__sub.kd_sub,
									ref__sub.nm_sub
								FROM
									ref__sub
								WHERE
									ref__sub.id_unit = ' . $id_unit . '
								ORDER BY
									ref__sub.kd_sub ASC
							')
							->result();
				$options							= '<option value="all">Pilih Semua Sub Unit</option>';
				
				if($query)
				{
					foreach($query as $key => $val)
					{
						$options					.= '<option value="' . $val->id . '">' . $val->kd_sub . '. ' . $val->nm_sub . '</option>';
					}
				}
				
				make_json
				(
					array
					(
						'html'						=> $options
					)
				);
			}
			else
			{
				$query		= $this->model->query
							('
								SELECT
									ref__unit.id,
									ref__urusan.kd_urusan,
									ref__bidang.kd_bidang,
									ref__unit.kd_unit,
									ref__unit.nm_unit
								FROM
									ref__unit
								INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
								INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
								ORDER BY
									ref__urusan.kd_urusan ASC,
									ref__bidang.kd_bidang ASC,
									ref__unit.kd_unit ASC
							')
							->result();
				
				$options							= '<option value="">Silakan Pilih Unit</option>';
				if($query)
				{
					foreach($query as $key => $val)
					{
						$options					.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '. ' . $val->nm_unit . '</option>';
					}
					return '
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
			}
		}
		elseif(in_array(get_userdata('group_id'), array(13, 14))) // Unit
		{
			$id_sub			= get_userdata('sub_unit');
			$query			= $this->model->query
							('
								SELECT
									ref__sub.id,
									ref__sub.kd_sub,
									ref__sub.nm_sub
								FROM
									ref__sub
								WHERE
									ref__sub.id_unit = ' . $id_sub . '
								ORDER BY
									ref__sub.kd_sub ASC
							')
							->result();
			$options								= null;
			
			if($query)
			{
				$options							= '<option value="all">Pilih Semua Sub Unit</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val->id . '">' . $val->kd_sub . '. ' . $val->nm_sub . '</option>';
				}
			}
			
			return '
				<div class="form-group">
					<label class="control-label">
						Sub Unit
					</label>
					<select name="sub_unit" class="form-control bordered form-control-sm" placeholder="Silakan pilih Sub Unit">
						' . $options . '
					</select>
				</div>
			';
		}
	}
	
	private function _rek_1()
	{
		$output										= null;
		$query										= $this->model
												->select('ref__rek_1.id, ref__rek_1.kd_rek_1, ref__rek_1.uraian')
												->order_by('ref__rek_1.kd_rek_1', 'ASC')
												->get_where('ref__rek_1', array('ref__rek_1.tahun' => get_userdata('year')))
												->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kd_rek_1'] . '. ' . $val['uraian'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Rekening
				</label>
				<select name="rekening" class="form-control form-control-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _jenis_standar_harga()
	{
		return '
			<div class="row form-group">
				<div class="col-sm-12">
					<label class="control-label">
						Standar Harga
					</label>
				</div>
				<div class="col-sm-12">
					<label style="margin-right:20px">
						<input type="radio" name="jenis_standar_harga" value="sht" checked>
						SHT
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="jenis_standar_harga" value="sbm">
						SBM
					</label>
				</div>
			</div>
		';
	}
	
	private function _jenis_data()
	{
		return '
			<div class="row form-group">
				<div class="col-sm-12">
					<label class="control-label">
						Jenis Data
					</label>
				</div>
				<div class="col-sm-12">
					<label style="margin-right:20px">
						<input type="radio" name="jenis_data" value="renja_awal" checked>
						Renja Awal
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="jenis_data" value="renja">
						Renja
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="jenis_data" value="renja_akhir">
						Renja Akhir
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="jenis_data" value="rkpd_awal">
						RKPD Awal
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="jenis_data" value="rkpd_akhir">
						RKPD Akhir
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="jenis_data" value="rancangan_kua_ppas">
						Rancangan KUA PPAS
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="jenis_data" value="kua_ppas">
						KUA PPAS
					</label>
				</div>
			</div>
		';
	}
	
	private function _standar_harga_pilihan()
	{
		return '
			<div class="row form-group">
				<div class="col-sm-12">
					<label class="control-label">
						Jenis
					</label>
				</div>
				<div class="col-sm-12">
					<label style="margin-right:20px">
						<input type="radio" name="pilihan_standar_harga" value="usulan" checked>
						Usulan
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="pilihan_standar_harga" value="disetujui">
						Disetujui
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="pilihan_standar_harga" value="ditolak">
						Ditolak
					</label>
				</div>
			</div>
		';
	}
	
	private function _jenis_anggaran()
	{
			$output									= '<option value="aktual">Aktual</option>';
		$query										= 	$this->model->query
		('
			SELECT
				ref__renja_jenis_anggaran.id,
				ref__renja_jenis_anggaran.kode,
				ref__renja_jenis_anggaran.nama_jenis_anggaran
			FROM
				ref__renja_jenis_anggaran
			WHERE
				ref__renja_jenis_anggaran.id > 7
			ORDER BY
				ref__renja_jenis_anggaran.kode ASC
		')
		->result();
		
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output									.= '<option value="' . $val->id . '">' . $val->kode . '. ' . $val->nama_jenis_anggaran . '</option>';
			}
		}
		
		$output										= '
			<div class="form-group mb-2">
				<label class="d-block text-muted">
					Jenis Anggaran
				</label>
				<select name="jenis_anggaran" class="form-control form-control-sm">
					' . $output . '
				</select>
			</div>
		';
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
						<input type="text" name="tanggal_cetak" class="form-control form-control-sm bordered" placeholder="Pilih Tanggal" value="' . date('d M Y') . '" role="datepicker" readonly />
					</div>
				</div>
			</div>
		';
		
		return $options;
	}
	
	public function get_tim_asistensi($kegiatan_sub = 0, $kode_perubahan = 0)
	{
		$query										= $this->model->query
		('
			SELECT
				ta__asistensi_setuju.perencanaan,
				ta__asistensi_setuju.waktu_verifikasi_perencanaan,
				ta__asistensi_setuju.nama_operator_perencanaan,
				ta__asistensi_setuju.keuangan,
				ta__asistensi_setuju.waktu_verifikasi_keuangan,
				ta__asistensi_setuju.nama_operator_keuangan,
				ta__asistensi_setuju.setda,
				ta__asistensi_setuju.waktu_verifikasi_setda,
				ta__asistensi_setuju.nama_operator_setda,
				ta__asistensi_setuju.ttd_1,
				ta__asistensi_setuju.ttd_2,
				ta__asistensi_setuju.ttd_3
			FROM
				ta__asistensi_setuju
			WHERE
				ta__asistensi_setuju.id_keg_sub = ' . $kegiatan_sub . '
				-- AND
				-- ta__asistensi_setuju.kode_perubahan = ' . $kode_perubahan . '
			LIMIT 1
		')
		->row();
		
		return $query;
	}
}
