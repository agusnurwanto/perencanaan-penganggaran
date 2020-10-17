<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Reses extends Aksara
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
		
		$this->load->model('perencanaan/Reses_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan Reses')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function hasil_reses_dprd()
	{
		if(!$this->_dprd)
		{
			return throw_exception(403, 'Silakan pilih DPRD ' . phrase($this->_request) . ' Laporan Hasil Reses DPRD!', go_to());
		}
		$this->_title								= 'Laporan Hasil Reses DPRD';
		$this->_output								= $this->report->hasil_reses_dprd($this->_dprd);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function rekapitulasi_reses_dprd()
	{
		$this->_title								= 'Rekapitulasi Reses DPRD';
		$this->_output								= $this->report->rekapitulasi_reses_dprd();
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function hasil_reses_dprd_per_program()
	{
		if(!$this->_dprd)
		{
			return throw_exception(403, 'Silakan pilih DPRD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Reses DPRD Per Program!', go_to());
		}
		$this->_title								= 'Hasil Reses DPRD per Program';
		$this->_output								= $this->report->hasil_reses_dprd_per_program($this->_dprd, $this->_status);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function hasil_reses_dprd_per_bidang_bappeda()
	{
		if(!$this->_dprd)
		{
			return throw_exception(403, 'Silakan pilih DPRD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Reses DPRD per Bidang Bappeda!', go_to());
		}
		$this->_title								= 'Laporan Hasil Reses DPRD per Bidang Bappeda';
		$this->_output								= $this->report->hasil_reses_dprd_per_bidang_bappeda($this->_dprd);
		//$this->wkhtmltopdf->pageSize('13.5in', '8.5in');
		//$this->wkhtmltopdf->pageMargin(15);
		$this->_execute();
	}
	
	public function hasil_reses_dprd_per_skpd()
	{
		if(!$this->_skpd)
		{
			return throw_exception(403, 'Silakan pilih SKPD dan Status untuk ' . phrase($this->_request) . ' Laporan Hasil Reses DPRD per SKPD!', go_to());
		}
		$this->_title								= 'Laporan Hasil Reses DPRD per SKPD';
		$this->_output								= $this->report->hasil_reses_dprd_per_skpd($this->_skpd);
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
				'title'								=> 'Hasil Reses DPRD',
				'description'						=> 'Laporan Hasil Reses DPRD',
				'icon'								=> 'mdi-cellphone-wireless',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'hasil_reses_dprd',
				'parameter'							=> array
				(
					'dprd'							=> $this->_dprd(),
					'status'						=> $this->_status('dprd'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Reses DPRD',
				'description'						=> 'Laporan Rekapitulasi Reses DPRD',
				'icon'								=> 'mdi-chart-bar-stacked',
				'color'								=> 'bg-teal',
				'placement'							=> 'left',
				'controller'						=> 'rekapitulasi_reses_dprd',
				'parameter'							=> array
				(
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Hasil Reses DPRD per Program',
				'description'						=> 'Laporan Hasil Reses DPRD per Program',
				'icon'								=> 'mdi-charity',
				'color'								=> 'bg-danger',
				'placement'							=> 'left',
				'controller'						=> 'hasil_reses_dprd_per_program',
				'parameter'							=> array
				(
					'dprd'							=> $this->_dprd('all'),
					'status'						=> $this->_status('dprd'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Hasil Reses DPRD per Bidang Bappeda',
				'description'						=> 'Laporan Hasil Reses DPRD per Bidang Bappeda',
				'icon'								=> 'mdi-chart-scatterplot-hexbin',
				'color'								=> 'bg-primary',
				'placement'							=> 'right',
				'controller'						=> 'hasil_reses_dprd_per_bidang_bappeda',
				'parameter'							=> array
				(
					'dprd'							=> $this->_dprd(),
					'status'						=> $this->_status('dprd'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Hasil Reses DPRD per SKPD',
				'description'						=> 'Laporan Hasil Reses DPRD per SKPD',
				'icon'								=> 'mdi-flask-outline',
				'color'								=> 'bg-teal',
				'placement'							=> 'right',
				'controller'						=> 'hasil_reses_dprd_per_skpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd_all(),
					'status'						=> $this->_status('dprd'),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
		);
	}
	
	private function _dprd($method = null)
	{		
		if(get_userdata('group_id') > 1 AND get_userdata('group_id') != 8 ) return false;
		$output										= null;
		$query										= $this->model
		->select('ref__dprd.id, ref__dprd_fraksi.kode AS kode_fraksi, ref__dprd.kode, ref__dprd.nama_dewan')
		->join('ref__dprd_fraksi', 'ref__dprd_fraksi.id = ref__dprd.id_fraksi')
		->order_by('ref__dprd_fraksi.kode ASC, ref__dprd.kode ASC')
		->get('ref__dprd')
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kode_fraksi'] . '.' . $val['kode'] . '. ' . $val['nama_dewan'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					DPRD
				</label>
				<select name="id_dprd" class="form-control input-sm">
					<option value="99">Pilih Semua DPRD</option>
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _status($request = 'dprd')
	{
		/**
		 * @param string untuk jenis permintaan
		 * @default 'kelurahan'
		*/
		if('dprd' == $request)
		{
			$option 								= '
				<option value="1">1. Usulan DPRD</option>
				<option value="2">2. Diterima SKPD</option>
				<option value="3">3. Ditolak SKPD</option>
				<option value="4">4. Pilih Semua</option>
			';
		}/*
		elseif('kecamatan' == $request)
		{
			$option 								= '
				<option value="1">1. Usulan Kelurahan</option>
				<option value="2">2. Diterima Kecamatan</option>
				<option value="3">3. Ditolak Kecamatan</option>
				<option value="4">4. Usulan Kecamatan</option>
				<option value="5">5. Semua Status</option>
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
		}*/
		return '
			<div class="form-group">
				<label class="control-label">
					' . phrase('status') . '
				</label>
				<br />
				<select name="status" class="form-control input-sm" placeholder="Silakan pilih status">
					' . $option . '
				</select>
			</div>
		';
	}
	
	private function _skpd_all()
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
