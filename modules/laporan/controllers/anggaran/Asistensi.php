<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Asistensi extends Aksara
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
		
		$this->load->model('anggaran/Asistensi_model', 'report');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
		
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
	}
	
	public function index()
	{
		$this->set_title('Laporan Asistensi')
		->set_icon('mdi mdi-chart-bar')
		->set_output('results', $this->_reports())
		->render();
	}
	
	public function asistensi_kegiatan()
	{
		if(!$this->_unit)
		{
			return throw_exception(403, 'Silakan pilih SKPD untuk ' . phrase($this->_request) . ' Rekapatitulasi Asistensi per Kegiatan!', go_to());
		}
		$this->_title								= 'Rekapatitulasi Asistensi per Kegiatan';
		$this->_output								= $this->report->asistensi_kegiatan($this->_unit, $this->_jenis_anggaran);
		
		$this->_execute();
	}
	
	public function asistensi_skpd()
	{
		$this->_title								= 'Rekapitulasi RKA Yang sudah di asistensi Per SKPD';
		$this->_output								= $this->report->asistensi_skpd($this->_tahun, $this->_bidang_bappeda, $this->_jenis_anggaran);
		
		$this->_execute();
	}
	
	public function ttd_tapd_asistensi_skpd()
	{
		$this->_title								= 'Rekapitulasi RKA yang Sudah di TTD TAPD Per SKPD';
		$this->_output								= $this->report->ttd_tapd_asistensi_skpd($this->_tahun, $this->_bidang_bappeda, $this->_jenis_anggaran);
		
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
				'title'								=> 'Rekapitulasi Asistensi Per Kegiatan',
				'description'						=> 'Rekapitulasi RKA Yang sudah di asistensi Per Kegiatan',
				'icon'								=> 'mdi-newspaper',
				'color'								=> 'bg-primary',
				'placement'							=> 'left',
				'controller'						=> 'asistensi_kegiatan',
				'parameter'							=> array
				(
					'skpd'							=> $this->_skpd(),
					'jenis_anggaran'				=> $this->_jenis_anggaran(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi Asistensi Per SKPD',
				'description'						=> 'Rekapitulasi RKA Yang sudah di asistensi Per SKPD',
				'icon'								=> 'mdi-format-float-left',
				'color'								=> 'bg-primary',
				'placement'							=> 'right',
				'controller'						=> 'asistensi_skpd',
				'parameter'							=> array
				(
					'bidang_bappeda'				=> $this->_bidang_bappeda(),
					'jenis_anggaran'				=> $this->_jenis_anggaran(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			),
			
			array
			(
				//'user_group'						=> array(1),
				'title'								=> 'Rekapitulasi TTD TAPD Per SKPD',
				'description'						=> 'Rekapitulasi RKA yang Sudah di TTD TAPD Per SKPD',
				'icon'								=> 'mdi-notebook',
				'color'								=> 'bg-teal',
				'placement'							=> 'right',
				'controller'						=> 'ttd_tapd_asistensi_skpd',
				'parameter'							=> array
				(
					'bidang_bappeda'				=> $this->_bidang_bappeda(),
					'jenis_anggaran'				=> $this->_jenis_anggaran(),
					'tanggal_cetak'					=> $this->_tanggal_cetak()
				)
			)
		);
	}
	
	private function _kegiatan()
	{
		$output										= null;
		if(in_array(get_userdata('group_id'), array(1, 8, 9, 10, 12, 13)) )
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
		elseif(5 == get_userdata('group_id') or 11 == get_userdata('group_id'))
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
	
	private function _bidang_bappeda()
	{
		$output										= null;
		$query										= $this->model
													->select('ref__bidang_bappeda.id, ref__bidang_bappeda.kode, ref__bidang_bappeda.nama_bidang')
													->order_by('ref__bidang_bappeda.kode', 'ASC')
													->get('ref__bidang_bappeda')
													->result_array();
		if($query)
		{
			$output								.= '<option value="all">Pilih Semua Bidang Bappeda</option>';
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '">' . $val['kode'] . '. ' . $val['nama_bidang'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Bidang Bappeda
				</label>
				<select name="bidang_bappeda" class="form-control input-sm">
					' . $output . '
				</select>
			</div>
		';
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
	
	private function _skpd()
	{
		if(!in_array(get_userdata('group_id'), array(1, 8, 9, 10, 12, 13)) ) return false;
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
		$output										= null;
		$query										= $this->model
													->select('ref__renja_jenis_anggaran.id, ref__renja_jenis_anggaran.kode, ref__renja_jenis_anggaran.nama_jenis_anggaran')
													->order_by('ref__renja_jenis_anggaran.kode', 'ASC')
													->get('ref__renja_jenis_anggaran')
													->result_array();
		if($query)
		{
			$output								.= '<option value="all">Pilih Semua Jenis Anggaran</option>';
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['kode'] . '">' . $val['kode'] . '. ' . $val['nama_jenis_anggaran'] . '</option>';
			}
		}
		$output										= '
			<div class="form-group">
				<label class="control-label">
					Jenis Anggaran
				</label>
				<select name="jenis_anggaran" class="form-control input-sm">
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
