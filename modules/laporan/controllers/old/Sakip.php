<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Laporan > Sakip
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Sakip extends Aksara
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
		
		$this->load->model('Musrenbang_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan Sakip')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function hasil_musrenbang_rw()
	{
		if(!$this->_kelurahan)
		{
			//return throw_exception(404, 'Silakan pilih Tahapan dan Sub Unit untuk melakukan ' . phrase($this->_request) . ' Nota Dinas Permohonan Persetujuan!', current_page('../'));
		}
		
		$this->_title								= 'Hasil Musrenbang RW';
		$this->_output								= $this->report->hasil_musrenbang_rw($this->_rw);
		
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
				'title'								=> 'Hasil Musrenbang RW',
				'description'						=> 'Hasil Musrenbang RW',
				'icon'								=> 'mdi-chart-bubble',
				'color'								=> 'bg-pink',
				'placement'							=> 'left',
				'controller'						=> 'hasil_musrenbang_rw',
				'parameter'							=> array
				(
					'kelurahan'						=> $this->_kelurahan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			)
		);
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
