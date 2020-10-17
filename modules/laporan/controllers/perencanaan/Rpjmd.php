<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Rpjmd extends Aksara
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
		$this->_misi								= ($this->input->get('misi') ? $this->input->get('misi') : NULL);
		$this->_unit								= ($this->input->get('unit') ? $this->input->get('unit') : NULL);
		$this->_jenis_bl							= ($this->input->get('jenis_bl') ? $this->input->get('jenis_bl') : NULL);
		
		/*$this->_sub_unit							= ($this->input->get('sub_unit') ? $this->input->get('sub_unit') : 0);
		$this->_kegiatan							= ($this->input->get('kegiatan') ? $this->input->get('kegiatan') : 0);
		$this->_jenis_usulan						= ($this->input->get('jenis_usulan') ? $this->input->get('jenis_usulan') : 0);*/
		$this->_tanggal_cetak						= ($this->input->get('tanggal_cetak') ? $this->input->get('tanggal_cetak') : date('Y-m-d'));
		
		$this->load->model('perencanaan/Rpjmd_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan RPJMD')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function rpjmd_misi()
	{
		if(!$this->_misi)
		{
			return throw_exception(403, 'Silakan pilih Misi untuk ' . phrase($this->_request) . ' RPJMD!', go_to());
		}
		$this->_title								= 'Rencana Pembanguan Jangka Menengah Daerah';
		$this->_output								= $this->report->rpjmd_misi($this->input->get('misi'));
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function program_pembangunan_prioritas_pendanaan()
	{
		$this->_title								= 'Program Pembangunan Prioritas dan Pendanaan';
		$this->_output								= $this->report->program_pembangunan_prioritas_pendanaan($this->_tahun);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function rencana_program_kegiatan()
	{
		$this->_title								= 'Rencana Program, Kegiatan, Indikator Kinerja, Kelompok Sasaran, dan Pendanaan Indikatif';
		$this->_output								= $this->report->rencana_program_kegiatan($this->_tahun);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function capaian_program()
	{
		$this->_title								= 'Laporan Capaian Program';
		$this->_output								= $this->report->capaian_program($this->_tahun);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function rekapitulasi_program()
	{
		$this->_title								= 'Laporan Rekapitulasi Program';
		$this->_output								= $this->report->rekapitulasi_program($this->_tahun, $this->_unit, $this->_jenis_bl);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function program_pembangunan_pagu_indikatif()
	{
		$this->_title								= 'Program Pembanguan Daerah dengan Pagu Indikatif';
		$this->_output								= $this->report->program_pembangunan_pagu_indikatif($this->_tahun);
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
				'title'								=> 'RPJMD',
				'description'						=> 'Laporan Rencana Pembanguan Jangka Menengah Daerah',
				'icon'								=> 'mdi-chart-bubble',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'rpjmd_misi',
				'parameter'							=> array
				(
					'misi'							=> $this->_misi(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Program Pembangunan Prioritas dan Pendanaan',
				'description'						=> 'Laporan Indikasi Program Pembangunan Prioritas dan Pendanaan  Berdasarkan Urusan',
				'icon'								=> 'mdi-chart-donut-variant',
				'color'								=> 'bg-teal',
				'placement'							=> 'left',
				'controller'						=> 'program_pembangunan_prioritas_pendanaan',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rencana Program, Kegiatan, Indikator Kinerja, Kelompok Sasaran, dan Pendanaan Indikatif',
				'description'						=> 'Laporan Rencana Program, Kegiatan, Indikator Kinerja, Kelompok Sasaran, dan Pendanaan Indikatif ',
				'icon'								=> 'mdi-chart-scatterplot-hexbin',
				'color'								=> 'bg-danger',
				'placement'							=> 'left',
				'controller'						=> 'rencana_program_kegiatan',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Capaian Program',
				'description'						=> 'Laporan Capaian Program',
				'icon'								=> 'mdi-chart-bar',
				'color'								=> 'bg-primary',
				'placement'							=> 'right',
				'controller'						=> 'capaian_program',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Program',
				'description'						=> 'Laporan Rekapitulasi Program',
				'icon'								=> 'mdi-chart-pie',
				'color'								=> 'bg-teal',
				'placement'							=> 'right',
				'controller'						=> 'rekapitulasi_program',
				'parameter'							=> array
				(
					'unit'							=> $this->_unit_all(),
					'jenis_bl'						=> $this->_jenis_bl(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Program Pembangunan Daerah (Pagu Indikatif)',
				'description'						=> 'Laporan Pembangunan Daerah yang disertai Pagu Indikatif',
				'icon'								=> 'mdi-chart-timeline',
				'color'								=> 'bg-danger',
				'placement'							=> 'right',
				'controller'						=> 'program_pembangunan_pagu_indikatif',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			)
		);
	}
	
	private function _misi()
	{
		$output										= null;
		$query										= $this->model
			->select('ta__rpjmd_misi.id, ta__rpjmd_misi.kode, ta__rpjmd_misi.misi')
			->order_by('ta__rpjmd_misi.kode', 'ASC')
			->get('ta__rpjmd_misi')
			->result_array();
		if($query)
		{
			$output								.= '<option value="all">Pilih Semua Misi</option>';
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kode'] . '. ' . $val['misi'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Misi
				</label>
				<select name="misi" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _unit_all()
	{
		if (get_userdata('group_id') != 1 && get_userdata('group_id') != 9) return false;
		
		$output										= null;
		$query										= $this->model
		->select('ref__unit.id, ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__unit.nm_unit')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->order_by('ref__urusan.kd_urusan', 'ASC')
		->order_by('ref__bidang.kd_bidang', 'ASC')
		->order_by('ref__unit.kd_unit', 'ASC')
		->get('ref__unit')
		->result();
		if($query)
		{
			$output								.= '<option value="all">Pilih Semua Unit</option>';
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '. ' . $val->nm_unit . '</option>';
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
	
	private function _jenis_bl()
	{
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Belanja Langsung
				</label>
				<select name="jenis_bl" class="form-control input-sm" placeholder="Jenis Belanja Langsung">
					<option value="all">Pilih Semua Jenis Belanja</option>
					<option value="1">1. Belanja Langsung Penunjang Urusan</option>
					<option value="2">2. Belanja Langsung Urusan</option>
				</select>
			</div>
		';
		return $output;
	}
	
	private function _skpd()
	{
		if(get_userdata('group_id') > 1 && !in_array(get_userdata('group_id'), array(8))) return false;
		
		$output										= null;
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
		(
			array
			(
				'ref__urusan.kd_urusan'				=> 'ASC',
				'ref__bidang.kd_bidang'				=> 'ASC',
				'ref__unit.kd_unit'					=> 'ASC'
			)
		)
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
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . $valkd_bidang . '.' . $valkd_unit . '. ' . $val->nm_unit . '</option>';
			}
		}
		
		$output										= '
			<div class="form-group">
				<label class="text-muted d-block">
					SKPD
				</label>
				<select name="id_skpd" class="form-control form-control-sm">
					' . $output . '
				</select>
			</div>
		';
		
		return $output;
	}
	
	private function _kecamatan()
	{
		if(get_userdata('group_id') > 1 && !in_array(get_userdata('group_id'), array(5, 8))) return false;
		
		$output										= null;
		$query										= $this->model->get_where
		(
			'ref__kecamatan',
			array
			(
			)
		)
		->result();
		
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->id . '">' . $val->kode . '. ' . $val->kecamatan . '</option>';
			}
		}
		
		$output										= '
			<div class="form-group">
				<label class="text-muted d-block">
					Kecamatan
				</label>
				<select name="id_kec" class="form-control form-control-sm">
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
		
		if(in_array(get_userdata('group_id'), array(2)))
		{
			$this->model->where('ref__kecamatan.id', get_userdata('sub_unit'));
		}
		
		$query										= $this->model->select
		('
			ref__kecamatan.kode,
			ref__kecamatan.kecamatan,
			ref__kelurahan.id,
			ref__kelurahan.kode as kode_kelurahan,
			ref__kelurahan.nama_kelurahan
		')
		->join
		(
			'ref__kecamatan',
			'ref__kecamatan.id = ref__kelurahan.id_kec'
		)
		->get_where
		(
			'ref__kelurahan'
		)
		->result();
		
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->id . '">' . $val->kode . '.' . $val->kode_kelurahan . '. ' . $val->nama_kelurahan . ' - ' . $val->kecamatan . '</option>';
			}
		}
		
		$output										= '
			<div class="form-group">
				<label class="text-muted d-block">
					Kelurahan
				</label>
				<select name="id_kel" class="form-control form-control-sm">
					' . $output . '
				</select>
			</div>
		';
		
		return $output;
	}
	
	
	
	private function _jenis_usulan()
	{
		$output							= null;
		$query							= $this->model->select
		('
			ref__renja_jenis_usulan.id,
			ref__renja_jenis_usulan.kode,
			ref__renja_jenis_usulan.nama_jenis_usulan
		')
		->get_where
		(
			'ref__renja_jenis_usulan',
			array
			(
			)
		)
		->result();
		
		if($query)
		{
			$output									.= '<option value="all">Pilih Semua Jenis Usulan</option>';
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val->id . '">' . $val->kode . '. ' . $val->nama_jenis_usulan . '</option>';
			}
		}
		
		$output										= '
			<div class="form-group">
				<label class="text-muted d-block">
					Jenis Usulan
				</label>
				<select name="jenis_usulan" class="form-control form-control-sm">
					' . $output . '
				</select>
			</div>
		';
		
		return $output;
	}
	
	private function _bidang_bappeda()
	{
		return '
			<div class="form-group">
				<label class="d-block text-muted">
					Bidang Bappeda
				</label>
				<label class="mr-3">
					<input type="radio" name="bidang_bappeda" value="1" checked>
					IPW
				</label>
				<label class="mr-3">
					<input type="radio" name="bidang_bappeda" value="2">
					PMM
				</label>
				<label class="mr-3">
					<input type="radio" name="bidang_bappeda" value="3">
					ESDA
				</label>
				<label class="mr-3">
					<input type="radio" name="bidang_bappeda" value="4">
					Semua
				</label>
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
			<div class="row">
				<div class="col-sm-6">
					<label class="text-muted d-block">
						' . phrase('status') . '
					</label>
					<br />
					<select name="status" class="form-control form-control-sm" placeholder="Silakan pilih status">
						' . $option . '
					</select>
				</div>
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
		->get_where
		(
			'ref__musrenbang_variabel',
			array
			(
			)
		)
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
