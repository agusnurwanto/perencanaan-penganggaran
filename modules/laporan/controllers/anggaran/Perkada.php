<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Perkada extends Aksara
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
		$this->_jenis_anggaran						= ($this->input->get('jenis_anggaran') ? $this->input->get('jenis_anggaran') : null);
		$this->_tanggal_cetak						= ($this->input->get('tanggal_cetak') ? $this->input->get('tanggal_cetak') : date('Y-m-d'));
		
		$this->load->model('anggaran/Perkada_model', 'report');
		
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
		$this->set_title('Laporan Peraturan Kepala Daerah')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function lampiran_1()
	{
		/*if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA SKPD', current_page('../'));
		}*/
		
		$this->_title								= 'Lampiran I Peraturan Kepala Daerah';
		$this->_output								= $this->report->lampiran_1($this->_tahun, $this->_unit, $this->_sub_unit, $this->_jenis_anggaran);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function lampiran_2()
	{
		/*if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA Pendapatan SKPD', current_page('../'));
		}*/
		
		$this->_title								= 'Lampiran II Peraturan Kepala Daerah';
		$this->_output								= $this->report->lampiran_2($this->_tahun, $this->_unit, $this->_sub_unit, $this->_jenis_anggaran);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function lampiran_3()
	{
		/*if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA Pendapatan SKPD', current_page('../'));
		}*/
		
		$this->_title								= 'Lampiran III Peraturan Kepala Daerah';
		$this->_output								= $this->report->lampiran_3($this->_tahun, $this->_unit, $this->_sub_unit, $this->_jenis_anggaran);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function lampiran_4()
	{
		/*if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA Pendapatan SKPD', current_page('../'));
		}*/
		
		$this->_title								= 'Lampiran IV Peraturan Kepala Daerah';
		$this->_output								= $this->report->lampiran_4($this->_tahun, $this->_unit, $this->_sub_unit, $this->_jenis_anggaran);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function lampiran_5()
	{
		/*if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA Pendapatan SKPD', current_page('../'));
		}*/
		
		$this->_title								= 'Lampiran V Peraturan Kepala Daerah';
		$this->_output								= $this->report->lampiran_5($this->_tahun, $this->_unit, $this->_sub_unit, $this->_jenis_anggaran);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function lampiran_6()
	{
		/*if(!$this->_unit)
		{
			return throw_exception(404, 'Silakan pilih Unit untuk melakukan ' . phrase($this->_request) . ' RKA Pendapatan SKPD', current_page('../'));
		}*/
		
		$this->_title								= 'Lampiran VI Peraturan Kepala Daerah';
		$this->_output								= $this->report->lampiran_6($this->_tahun, $this->_unit, $this->_sub_unit, $this->_jenis_anggaran);
		
		/* execute the thread */
		$this->_execute();
	}
	
	public function lampiran_7()
	{
		/*if(!$this->_rekening)
		{
			return throw_exception(404, 'Silakan pilih Rekening untuk melakukan ' . phrase($this->_request) . ' Rekening!', current_page('../'));
		}*/
		
		$this->_title								= 'Lampiran VII Peraturan Kepala Daerah';
		$this->_output								= $this->report->lampiran_7($this->_tahun, $this->_unit, $this->_sub_unit, $this->_jenis_anggaran);
		
		$this->_execute();
	}
	
	public function lampiran_8()
	{		
		$this->_title								= 'Lampiran VIII Peraturan Kepala Daerah';
		$this->_output								= $this->report->lampiran_8($this->_tahun, $this->_unit, $this->_sub_unit, $this->_jenis_anggaran);
		
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
				'title'								=> 'Lampiran I',
				'description'						=> 'Ringkasan Penjabaran APBD Yang Diklasifikasi Menurut Kelompok, Jenis, Objek, Rincian Objek, dan Sub Rincian Objek Pendapatan, Belanja, dan Pembiayaan',
				'icon'								=> 'mdi-chart-arc',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'lampiran_1',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'jenis_anggaran'				=> $this->_jenis_anggaran()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lampiran II',
				'description'						=> 'Penjabaran APBD Menurut Urusan Pemerintahan Daerah, Organisasi, Program, Kegiatan, Sub Kegiatan, Kelompok, Jenis, Objek, Rincian Objek, dan Sub Rincian Objek Pendapatan, Belanja, dan Pembiayaan',
				'icon'								=> 'mdi-chart-bell-curve',
				'color'								=> 'bg-teal',
				'placement'							=> 'left',
				'controller'						=> 'lampiran_2',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'jenis_anggaran'				=> $this->_jenis_anggaran()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lampiran III',
				'description'						=> 'Daftar Nama Penerima, Alamat Penerima, dan Besaran Hibah',
				'icon'								=> 'mdi-chart-areaspline',
				'color'								=> 'bg-danger',
				'placement'							=> 'left',
				'controller'						=> 'lampiran_3',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'jenis_anggaran'				=> $this->_jenis_anggaran()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lampiran IV',
				'description'						=> 'Daftar Nama Penerima, Alamat Penerima, dan Besaran Bantuan Sosial',
				'icon'								=> 'mdi-chart-bar',
				'color'								=> 'bg-maroon',
				'placement'							=> 'left',
				'controller'						=> 'lampiran_4',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'jenis_anggaran'				=> $this->_jenis_anggaran()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lampiran V',
				'description'						=> 'Rincian Dana Otonomi Khusus Menurut Urusan Pemerintahan Daerah, Organisasi, Program, Kegiatan, Sub Kegiatan, Kelompok, Jenis, Objek, Rincian Objek dan Sub Rincian Objek Pendapatan, Belanja dan Pembiayaan',
				'icon'								=> 'mdi-comment-check-outline',
				'color'								=> 'bg-primary',
				'placement'							=> 'right',
				'controller'						=> 'lampiran_5',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'jenis_anggaran'				=> $this->_jenis_anggaran()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lampiran VI',
				'description'						=> 'Rincian DBH-SDA Pertambangan Minyak Bumi dan Pertambangan Gas Alam/ Tambahan DBH-Minyak dan Gas Bumi*) Menurut Urusan Pemerintahan Daerah, Organisasi, Program, Kegiatan, Sub Kegiatan, Kelompok, Jenis, Objek, Rincian Objek  dan Sub Rincian Objek Pendapatan, Belanja dan Pembiayaan',
				'icon'								=> 'mdi-chart-pie',
				'color'								=> 'bg-teal',
				'placement'							=> 'right',
				'controller'						=> 'lampiran_6',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'jenis_anggaran'				=> $this->_jenis_anggaran()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lampiran VII',
				'description'						=> 'Rincian Dana Tambahan Infrastuktur Menurut Urusan Pemerintahan Daerah, Organisasi, Program, Kegiatan, Sub Kegiatan, Kelompok, Jenis, Objek, Rincian Objek dan Sub Rincian Objek Pendapatan, Belanja dan Pembiayaan',
				'icon'								=> 'mdi-chart-timeline',
				'color'								=> 'bg-danger',
				'placement'							=> 'right',
				'controller'						=> 'lampiran_7',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'jenis_anggaran'				=> $this->_jenis_anggaran()
				)
			),
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Lampiran VIII',
				'description'						=> 'Sinkronisasi Kebijakan Pemerintah Provinsi/ Kabupaten/Kota pada Daerah Perbatasan Dalam Rancangan Perda tentang APBD dan Rancangan Perkada tentang Penjabaran APBD dengan Program Prioritas Perbatasan Negara',
				'icon'								=> 'mdi-credit-card',
				'color'								=> 'bg-maroon',
				'placement'							=> 'right',
				'controller'						=> 'lampiran_8',
				'parameter'							=> array
				(
					'unit_sub_unit'					=> $this->_unit_sub_unit(),
					'jenis_anggaran'				=> $this->_jenis_anggaran()
				)
			)
		);
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
		elseif('.sub_unit' == $element)
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
				<select name="unit" class="form-control input-sm report-dropdown" to-change=".sub_unit">
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
				
				if($query)
				{
					$options						= '<option value="all">Silakan Pilih Semua Unit</option>';
					foreach($query as $key => $val)
					{
						$options					.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '. ' . $val->nm_unit . '</option>';
					}
					return '
						<div class="form-group">
							<label class="control-label">
								Unit
							</label>
							<select name="unit" class="form-control bordered input-sm report-dropdown" placeholder="Silakan Pilih Unit" to-change=".sub_unit">
								' . $options . '
							</select>
						</div>
						<div class="form-group">
							<label class="control-label">
								Sub Unit
							</label>
							<select name="sub_unit" class="form-control bordered input-sm sub_unit" placeholder="Pilih Unit terlebih dahulu" disabled>
								
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
					<select name="sub_unit" class="form-control bordered input-sm" placeholder="Silakan pilih Sub Unit">
						' . $options . '
					</select>
				</div>
			';
		}
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
						<input type="text" name="tanggal_cetak" class="form-control input-sm bordered" placeholder="Pilih Tanggal" value="' . date('d M Y') . '" role="datepicker" readonly />
					</div>
				</div>
			</div>
		';
		
		return $options;
	}
}