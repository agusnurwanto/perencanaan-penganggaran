<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Laporan > E-Monev
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Emonev extends Aksara
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
		
		$this->load->model('Emonev_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan Emonev')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function kemajuan_kegiatan()
	{
		if(!$this->_unit || !$this->input->get('jenis_usulan') || !$this->input->get('sumber_dana'))
		{
			return throw_exception(403, 'Silakan pilih sub unit, jenis usulan dan sumberdana untuk ' . phrase($this->_request) . ' Kemajuan Kegiatan!', go_to());
		}
		$this->_title								= 'Kemajuan Kegiatan';
		$this->_output								= $this->report->kemajuan_kegiatan($this->_unit, $this->_tahun, $this->input->get('jenis_usulan'), $this->input->get('sumber_dana'));
		
		$this->_execute();
	}
	
	public function evaluasi_hasil_rkpd()
	{
		if(!$this->_unit)
		{
			return throw_exception(403, 'Silakan pilih sub unit ' . phrase($this->_request) . ' Kemajuan Kegiatan!', go_to());
		}
		$this->_title								= 'Evaluasi Hasil RKPD';
		$this->_output								= $this->report->evaluasi_hasil_rkpd($this->_unit, $this->_tahun);
		
		$this->_execute();
	}
	
	public function rekapitulasi_kemajuan_kegiatan()
	{
		$this->_title								= 'Rekapitulasi Kemanjuan Kegiatan';
		$this->_output								= $this->report->rekapitulasi_kemajuan_kegiatan($this->_periode_awal, $this->_periode_akhir, $this->_tahun);
		
		$this->_execute();
	}
	
	public function konsolidasi_triwulanan()
	{
		$this->_title								= 'Konsolidasi Triwulanan';
		$this->_output								= $this->report->konsolidasi_triwulanan($this->_tahun);
		
		$this->_execute();
	}
	
	public function target_realisasi_urusan()
	{
		$this->_title								= 'Target Realisasi Urusan';
		$this->_output								= $this->report->target_realisasi_urusan($this->_tahun);
		
		$this->_execute();
	}
	
	public function target_realisasi_program()
	{
		$this->_title								= 'Target Realisasi Program';
		$this->_output								= $this->report->target_realisasi_program($this->_tahun);
		
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
				'title'								=> 'Kemajuan Kegiatan',
				'description'						=> 'Laporan Kemajuan Kegiatan',
				'icon'								=> 'mdi-camera-iris',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'kemajuan_kegiatan',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd(),
					'jenis_usulan'					=> $this->_jenis_usulan(),
					'sumber_dana'					=> $this->_sumber_dana(),
					'triwulan'						=> $this->_triwulan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Konsolidasi Triwulan',
				'description'						=> 'Laporan Konsolidasi Triwulan',
				'icon'								=> 'mdi-map-marker-path',
				'color'								=> 'bg-info',
				'placement'							=> 'left',
				'controller'						=> 'konsolidasi_triwulanan',
				'parameter'							=> array
				(
					'triwulan'						=> $this->_triwulan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Kemajuan Kegiatan',
				'description'						=> 'Rekapitulasi Kemajuan Kegiatan',
				'icon'								=> 'mdi-file-move',
				'color'								=> 'bg-success',
				'placement'							=> 'left',
				'controller'						=> 'rekapitulasi_kemajuan_kegiatan',
				'parameter'							=> array
				(
					'periode'						=> $this->_periode(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Evaluasi Hasil RKPD',
				'description'						=> 'Laporan Evaluasi Hasil RKPD',
				'icon'								=> 'mdi-file-search-outline',
				'color'								=> 'bg-warning',
				'placement'							=> 'right',
				'controller'						=> 'evaluasi_hasil_rkpd',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd_all(),
					'triwulan'						=> $this->_triwulan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Target Realisasi Urusan',
				'description'						=> 'Laporan Target Realisasi Urusan',
				'icon'								=> 'mdi-signal-cellular-2',
				'color'								=> 'bg-danger',
				'placement'							=> 'right',
				'controller'						=> 'target_realisasi_urusan',
				'parameter'							=> array
				(
					'triwulan'						=> $this->_triwulan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Target Realisasi Program',
				'description'						=> 'Laporan Target Realisasi Program',
				'icon'								=> 'mdi-cart-plus',
				'color'								=> 'bg-red',
				'placement'							=> 'right',
				'controller'						=> 'target_realisasi_program',
				'parameter'							=> array
				(
					'triwulan'						=> $this->_triwulan(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			)
			
		);
	}
	
	private function _kecamatan()
	{
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
	
	private function _skpd()
	{
		if(!in_array(get_userdata('group_id'), array(1, 9, 12, 13)) ) return false;
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
				<select name="unit" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _skpd_all()
	{
		if(!in_array(get_userdata('group_id'), array(1, 9, 12, 13)) ) return false;
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
			$output								.= '<option value="all">Pilih Semua SKPD</option>';
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
				<select name="unit" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
		return $output;
	}
	
	private function _triwulan()
	{
		$output					= '
								<option value="1">Triwulan I</option>
								<option value="2">Triwulan II</option>
								<option value="3">Triwulan III</option>
								<option value="4">Triwulan IV</option>
								';
		
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Triwulan
				</label>
				<select name="triwulan" class="form-control input-sm">
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
	
	private function _jenis_usulan()
	{
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Belanja Langsung
				</label>
				<select name="jenis_usulan" class="form-control input-sm" placeholder="Jenis Belanja Langsung">
					<option value="1">1. Semua</option>
					<option value="2">2. Belanja Langsung Penunjang Urusan</option>
					<option value="3">3. Belanja Langsung Urusan</option>
				</select>
			</div>
		';
		return $output;
	}
	
	private function _periode()
	{
		$options									= null;
		$options									= '
			<div class="row form-group">
				<div class="col-sm-6">
					<label class="d-block text-muted">
						Periode Awal
					</label>
					<input type="text" name="periode_awal" class="form-control form-control-sm" placeholder="Periode Awal" value="01 ' . date('M', strtotime(date('Y') . '-01-01')) . ' ' . date('Y') . '" role="datepicker" readonly />
				</div>
				<div class="col-sm-6">
					<label class="d-block text-muted">
						Periode Akhir
					</label>
					<input type="text" name="periode_akhir" class="form-control form-control-sm" placeholder="Periode Akhir" value="01 ' . date('M Y') . '" role="datepicker" readonly />
				</div>
			</div>
		';
		return $options;
	}
	
	private function _status()
	{
		return '
			<div class="row form-group">
				<div class="col-sm-12">
					<label class="control-label">
						' . phrase('status') . '
					</label>
					<br />
					<label style="margin-right:20px">
						<input type="radio" name="status" value="0" checked>
						Usulan
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="status" value="1">
						Diterima
					</label>
					<label style="margin-right:20px">
						<input type="radio" name="status" value="2">
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
