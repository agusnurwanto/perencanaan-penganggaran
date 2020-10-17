<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Dpa extends Aksara
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
		$this->_rekening							= ($this->input->get('rekening') ? $this->input->get('rekening') : null);
		$this->_tanggal_cetak						= ($this->input->get('tanggal_cetak') ? $this->input->get('tanggal_cetak') : date('Y-m-d'));
		
				
		//$this->_jenis_usulan						= ($this->input->get('jenis_usulan') ? $this->input->get('jenis_usulan') : 0);
		
		$this->load->model('anggaran/Dpa_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan Dokumen Pelaksanaan Anggaran (DPA)')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function dpa_skpd()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih kegiatan untuk melakukan ' . phrase($this->_request) . ' DPA SKPD', current_page('../'));
		}
		
		$this->_title								= 'Laporan DPA - SKPD';
		$this->_output								= $this->report->dpa_skpd($this->_kegiatan);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function dpa_pendapatan_skpd()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih kegiatan untuk melakukan ' . phrase($this->_request) . ' DPA Pendapatan SKPD', current_page('../'));
		}
		
		$this->_title								= 'Laporan DPA Pendapatan SKPD';
		$this->_output								= $this->report->dpa_pendapatan_skpd($this->_kegiatan);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function dpa_belanja_skpd()
	{
		if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' DPA Belanja SKPD', current_page('../'));
		}
		
		$this->_title								= 'Laporan DPA Belanja SKPD';
		$this->_output								= $this->report->dpa_belanja_skpd($this->_tahun, $this->_unit);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function dpa_rincian_belanja()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih kegiatan untuk melakukan ' . phrase($this->_request) . ' DPA Rincian Belanja!', current_page('../'));
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
		//	'data'									=> $this->_shortlink,
			'savename'								=> FCPATH . $config['imagedir'] . $filename
		);
		
		$this->ciqrcode->generate($params);*/
		
		$this->_title								= 'Laporan DPA Rincian Belanja';
		$this->_output								= $this->report->dpa_rincian_belanja($this->_tahun, $this->_sub_unit, $this->_program, $this->_kegiatan);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function dpa_sub_kegiatan()
	{
		if(!$this->_sub_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih Sub kegiatan untuk melakukan ' . phrase($this->_request) . ' DPA Sub Kegiatan!', current_page('../'));
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
		//	'data'									=> $this->_shortlink,
			'savename'								=> FCPATH . $config['imagedir'] . $filename
		);
		
		$this->ciqrcode->generate($params);*/
		
		$this->_title								= 'Laporan DPA Sub Kegiatan';
		$this->_output								= $this->report->dpa_sub_kegiatan($this->_tahun, $this->_sub_unit, $this->_program, $this->_kegiatan, $this->_sub_kegiatan);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function dpa_pembiayaan_skpd()
	{
		if(!$this->_kegiatan)
		{
			return throw_exception(404, 'Silakan pilih kegiatan untuk melakukan ' . phrase($this->_request) . ' DPA Pembiayaan SKPD', current_page('../'));
		}
		
		$this->_title								= 'Laporan DPA Pembiayaan SKPD';
		$this->_output								= $this->report->dpa_pembiayaan_skpd($this->_kegiatan);
		
		/* execute the thread */
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
				'title'								=> 'Laporan DPA - SKPD',
				'description'						=> 'Laporan Dokumen Pelaksanaan Anggaran SKPD',
				'icon'								=> 'mdi-chart-arc',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'dpa_skpd',
				'parameter'							=> array
				(
					'sub_kegiatan'					=> $this->_sub_kegiatan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Laporan DPA - Pendapatan SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Pendapatan SKPD',
				'icon'								=> 'mdi-chart-bell-curve',
				'color'								=> 'bg-teal',
				'placement'							=> 'left',
				'controller'						=> 'dpa_pendapatan_skpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_unit(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Laporan DPA - Belanja SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Belanja SKPD',
				'icon'								=> 'mdi-chart-areaspline',
				'color'								=> 'bg-danger',
				'placement'							=> 'left',
				'controller'						=> 'dpa_belanja_skpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_unit(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Laporan DPA - Rincian Belanja SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Rincian Belanja',
				'icon'								=> 'mdi-chart-bar',
				'color'								=> 'bg-maroon',
				'placement'							=> 'left',
				'controller'						=> 'dpa_rincian_belanja',
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
				'title'								=> 'laporan DPA - Sub Kegiatan SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Sub Kegiatan SKPD',
				'icon'								=> 'mdi-comment-account-outline',
				'color'								=> 'bg-success',
				'placement'							=> 'left',
				'controller'						=> 'dpa_sub_kegiatan',
				'parameter'							=> array
				(
					'sub_kegiatan'					=> $this->_sub_kegiatan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Laporan DPA - Pembiayaan SKPD',
				'description'						=> 'Laporan Rencana Kerja Anggaran Pembiayaan SKPD',
				'icon'								=> 'mdi-chart-bar-stacked',
				'color'								=> 'bg-info',
				'placement'							=> 'left',
				'controller'						=> 'dpa_pembiayaan_skpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_unit(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			)/*,
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
			),
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
						<select name="sub_unit" class="form-control input-sm report-dropdown" to-change=".program">
							<option value="">Silakan pilih Sub Unit</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Program
						</label>
						<select name="program" class="form-control input-sm report-dropdown program" to-change=".kegiatan" disabled>
							<option value="">Silakan pilih Sub Unit terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Kegiatan
						</label>
						<select name="kegiatan" class="form-control input-sm kegiatan" disabled>
							<option value="">Silakan pilih Program terlebih dahulu</option>
						</select>
					</div>
				';
			}
		}
			// Grup Sub Unit
		elseif(in_array(get_userdata('group_id'), array(11, 12)))
		{
			$query									= $this->model
													->select
													('
														ta__program.id,
														ref__program.kd_program,
														ref__program.nm_program
													')
													->join('ta__program', 'ref__program.id = ta__program.id_prog')
													->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
													->order_by('ref__program.kd_program', 'ASC')
													->get_where('ref__program', array('ref__program.id !=' => NULL, 'ref__sub.id_unit' =>$this->_unit))
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
						<select name="program" class="form-control input-sm report-dropdown" to-change=".kegiatan">
							<option value="">Silakan pilih Program</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Kegiatan
						</label>
						<select name="kegiatan" class="form-control input-sm kegiatan" disabled>
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
						<select name="sub_unit" class="form-control input-sm report-dropdown" to-change=".program">
							<option value="">Silakan pilih Sub Unit</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Program
						</label>
						<select name="program" class="form-control input-sm report-dropdown program" to-change=".kegiatan" disabled>
							<option value="">Silakan pilih Sub Unit terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Kegiatan
						</label>
						<select name="kegiatan" class="form-control input-sm report-dropdown kegiatan" to-change=".sub_kegiatan" disabled>
							<option value="">Silakan pilih Program terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Sub Kegiatan
						</label>
						<select name="sub_kegiatan" class="form-control input-sm sub_kegiatan" disabled>
							<option value="">Silakan pilih Kegiatan terlebih dahulu</option>
						</select>
					</div>
				';
			}
		}
			// Grup Sub Unit
		elseif(in_array(get_userdata('group_id'), array(11, 12)))
		{
			$query									= $this->model
													->select
													('
														ta__program.id,
														ref__program.kd_program,
														ref__program.nm_program
													')
													->join('ta__program', 'ref__program.id = ta__program.id_prog')
													->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
													->order_by('ref__program.kd_program', 'ASC')
													->get_where('ref__program', array('ref__program.id !=' => NULL, 'ref__sub.id_unit' => $this->_unit))
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
						<select name="program" class="form-control input-sm report-dropdown" to-change=".kegiatan">
							<option value="">Silakan pilih Program</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Kegiatan
						</label>
						<select name="kegiatan" class="form-control input-sm kegiatan" to-change=".sub_kegiatan" disabled>
							<option value="">Silakan pilih Program terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="control-label">
							Sub Kegiatan
						</label>
						<select name="sub_kegiatan" class="form-control input-sm sub_kegiatan" disabled>
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
		if('.program' == $element)
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
				<select name="unit" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
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
				<select name="rekening" class="form-control input-sm">
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
