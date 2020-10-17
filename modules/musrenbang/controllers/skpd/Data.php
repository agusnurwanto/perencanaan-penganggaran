<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id');
		$this->_id_sub								= $this->input->get('id_sub');
		$this->_id_unit								= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_id_sub))->row('id_unit'));
		$this->_id									= $this->input->get('id');
		if(!$this->_id_sub)
		{
			$id_unit								= get_userdata('sub_unit');
			$this->_id_sub							= $this->model->select('id')->get_where('ref__sub', array('id_unit' => $id_unit), 1)->row('id');
			if(!$this->_id_sub)
			{
				throw_exception(301, 'Silakan memilih SKPD terlebih dahulu.', go_to('../'));
			}
		}
		if(!in_array(get_userdata('group_id'), array(1, 5,9)))
		{
			throw_exception(403, 'Anda tidak mempunya hak akses yang cukup untuk melihat usulan', base_url('dashboard'));
		}
		
		$this->set_theme('backend');
		$this->set_permission();
		$this->set_upload_path('kegiatan');
	}
	
	public function index()
	{
		$file_scan									= $this->model->select('file')->get_where('ta__musrenbang_skpd_berkas', array('id_sub' => $this->_id_sub), 1)->row('file');
		$file_scan									= json_decode($file_scan, true);
		if($file_scan)
		{
			$file_scan								= key($file_scan);
		}
		if($file_scan)
		{
			$this->add_action('toolbar', '../../../uploads/skpd/' . $file_scan, 'Lihat Berkas', 'btn-primary ajax', 'fa fa-search', null, true);
		}
		if(in_array(get_userdata('group_id'), array(1, 5, 9, 12)) && $this->_id_sub && 'all' != $this->_id_sub)
		{
			$this->add_action('toolbar', '../scan', 'Upload Scan', 'btn-info ajax', 'fa fa-qrcode', array('id_sub' => $this->_id_sub));
		}
		
		//print_r($this->input->post());exit;
		if('isu' == $this->input->post('method'))
		{
			return $this->_jenis_pekerjaan();
		}
		elseif('jenis_pekerjaan' == $this->input->post('method'))
		{
			return $this->_variabel();
		}
		$maksimal_verifikasi						= $this->model
													->select('pagu_musrenbang')
													->get_where('ref__unit', array('ref__unit.id' => $this->_id_unit))
													->row('pagu_musrenbang');
		$hasil_verifikasi							= $this->model
													->select_sum('nilai_skpd')
													->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
													->join('ta__program', 'ta__program.id = ref__musrenbang_jenis_pekerjaan.id_prog')
													->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
													->get_where('ta__musrenbang', array('ref__sub.id_unit' => $this->_id_unit, 'ta__musrenbang.flag' => 7))
													->row('nilai_skpd');
		$selisih									= $maksimal_verifikasi - $hasil_verifikasi;
		$skpd										= $this->model->select('ref__unit.nm_unit, ref__sub.nm_sub')->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')->get_where('ref__sub', array('ref__sub.id' => $this->_id_sub), 1)->row();
		if(!$skpd)
		{
			throw_exception(403, 'Sub Unit yang Anda pilih tidak tersedia', go_to('../'));
		}
		/*
		if('update' == $this->_method)
		{
			if($hasil_verifikasi >= $maksimal_verifikasi)
			{
				throw_exception(403, 'Anda telah mencapai batas maksimal verifikasi.', go_to());
			}
		}
		*/


		
		

if('update' == $this->_method || 'delete' == $this->_method)
		{
			$checker								= $this->model->get_where('ta__musrenbang', array('id' => $this->_id, 'flag >=' => 7))->num_rows();
			if($checker)
			{
				throw_exception(403, 'Anda tidak dapat ' . ('update' == $this->_method ? 'mengubah' : 'menghapus') . ' karena sudah terverifikasi', base_url('musrenbang/skpd'));
			}
		}



		$this->set_description
		('
			<div class="row">
				<div class="col-sm-5">
					<div class="row">
						<label class="control-label col-md-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
							SKPD
						</label>
						<label class="control-label col-md-10 col-xs-8 text-sm text-uppercase no-margin">
							' . $skpd->nm_unit . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
							SUB
						</label>
						<label class="control-label col-md-10 col-xs-8 text-sm text-uppercase no-margin">
							' . $skpd->nm_sub . '
						</label>
					</div>
				</div>
				<div class="col-sm-5">
					<div class="row">
						<label class="control-label col-md-6 col-xs-6 text-sm text-muted text-uppercase no-margin">
							Maks Verifikasi
						</label>
						<label class="control-label col-md-6  col-xs-6 text-sm text-uppercase no-margin">
							' . number_format($maksimal_verifikasi) . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-6 col-xs-6 text-sm text-muted text-uppercase no-margin">
							Hasil Verifikasi
						</label>
						<label class="control-label col-md-6  col-xs-6 text-sm text-uppercase no-margin">
							' . number_format($hasil_verifikasi) . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-6 col-xs-6 text-sm text-muted text-uppercase no-margin">
							Selisih
						</label>
						<label class="control-label col-md-6  col-xs-6 text-sm text-uppercase no-margin">
							' . number_format($selisih) . '
						</label>
					</div>
				</div>
			</div>
		');
		if(get_userdata('group_id') == 1)
		{
			$this->add_action('option', '../menjadi_kecamatan', 'Menjadi Kecamatan', 'btn-success ajaxLoad', 'fa fa-three', array('id' => 'id'));
		}
		$this->set_breadcrumb
		(
			array
			(
				'musrenbang'						=> 'Musrenbang',
				'../musrenbang/skpd'				=> 'SKPD'
			)
		)
		->set_title('Musrenbang SKPD')
		->set_icon('fa fa-fort-awesome')
		->unset_action('create, delete, export, print, pdf')
		->unset_column('id, kode, pengusul, tahun, id_kec, id_kel, map_coordinates, survey, nilai_usulan, variabel_usulan, urgensi, variabel_kelurahan, nilai_kelurahan, prioritas_kelurahan, alasan_kelurahan, variabel_kecamatan, alasan_kecamatan, prioritas_kecamatan, alasan_skpd, prioritas_skpd, variabel_skpd, images, id_prioritas_pembangunan, rate, nama_pekerjaan, rw, rt,kode_ref__prioritas_pembangunan, uraian, nama_pekerjaan,kd_unit,kode_ref__renja_jenis_usulan,id_ref__unit,id_unit')
		->unset_view('id, tahun, id_kec, id_kel, map_coordinates, nilai_usulan, variabel_usulan, variabel_kelurahan, prioritas_kelurahan, alasan_kelurahan, variabel_kecamatan, prioritas_skpd, alasan_skpd, variabel_skpd, nilai_skpd')
		->unset_field('id, tahun, flag, prioritas_skpd')
		->column_order('kecamatan, nama_kelurahan, rw, rt, rate, nama_kegiatan, map_address, nilai_kecamatan, flag, nilai_skpd')
		->set_field
		(
			array
			(
				'kode'								=> 'disabled',
				'id_rt'								=> 'disabled',
				'map_coordinates'					=> 'map_coordinate',
				'map_address'						=> 'textarea, readonly',
				'urgensi'							=> 'textarea',
				'nilai_kecamatan'					=> 'number_format',
				'nilai_skpd'						=> 'number_format',
				'prioritas_skpd'					=> 'numeric',
				'alasan_skpd'						=> 'textarea',
				'prioritas_skpd'					=> 'last_insert',
				'images'							=> 'images',
				//'jenis_usulan'						=> 'disabled'
			)
		)
		->add_class
		(
			array
			(
				'map_address'						=> 'address-placeholder'
			)
		)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> '<label class="label bg-silver">Usulan RW</label>',
				1									=> '<label class="label bg-green">Diterima Kelurahan</label>',
				2									=> '<label class="label bg-yellow">Ditolak Kelurahan</label>',
				3									=> '<label class="label bg-aqua">Usulan Kelurahan</label>',
				4									=> '<label class="label bg-blue">Diterima Kecamatan</label>',
				5									=> '<label class="label bg-purple">Ditolak Kecamatan</label>',
				6									=> '<label class="label bg-primary">Usulan Kecamatan</label>',
				7									=> '<label class="label bg-teal">Diterima SKPD</label>',
				8									=> '<label class="label bg-maroon">Ditolak SKPD</label>'
			)
		)
		->set_relation
		(
			'jenis_pekerjaan',
			'ref__musrenbang_jenis_pekerjaan.id',
			'{ref__musrenbang_jenis_pekerjaan.nama_pekerjaan}',
			null,
			null,
			'ref__musrenbang_jenis_pekerjaan.kode'
		)
		->set_relation
		(
			'id_rt',
			'ref__rt.id',
			'{ref__rt.rt}'
		)
		->set_relation
		(
			'jenis_pekerjaan',
			'ref__musrenbang_jenis_pekerjaan.id',
			'{ref__musrenbang_isu.nama_isu}',
			null,
			array
			(
				array
				(
					'ref__musrenbang_isu',
					'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu'
				)
			),
			'ref__musrenbang_jenis_pekerjaan.kode'
		)
		->set_relation
		(
			'id_rw',
			'ref__rw.id',
			'{ref__rw.rw}'
		)
		->set_relation
		(
			'jenis_usulan',
			'ref__renja_jenis_usulan.id',
			'{ref__renja_jenis_usulan.kode}. {ref__renja_jenis_usulan.nama_jenis_usulan}'
		)
		->set_relation
		(
			'id_kel',
			'ref__kelurahan.id',
			'{ref__kelurahan.nama_kelurahan}'
		)
		->set_relation
		(
			'id_kec',
			'ref__kecamatan.id',
			'{ref__kecamatan.kecamatan}'
		)
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->join('ref__musrenbang_isu', 'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu')
		->join('ta__program', 'ta__program.id = ref__musrenbang_jenis_pekerjaan.id_prog')
		->group_where
		(
			array
			(
				'ta__musrenbang.flag = 4 OR ta__musrenbang.flag >= 6'
			)
		)
		->where
		(
			array
			(
				'ta__program.id_sub'				=> $this->_id_sub
			//	'ta__musrenbang.flag'				=> 7
			)
		)
		->order_by
		(
			array
			(
				'kecamatan' 						=> 'ASC',
				'kelurahan'							=> 'ASC',
				'rw' 								=> 'ASC',
				'rt' 								=> 'ASC',
				'rate'								=> 'ASC'
			)
		)
		->set_alias
		(
			array
			(
				'map_address'						=> 'alamat',
				'flag'								=> 'Status',
				'alasan_skpd'						=> 'Alasan',
				'nama_kelurahan'					=> 'Kelurahan',
				'nama_jenis_usulan'					=> 'Jenis Usulan'
			)
		)
		->set_output
		(
			array
			(
				'isu'								=> $this->_isu(),
				'jenis_pekerjaan'					=> $this->_jenis_pekerjaan(false),
				'variabel'							=> $this->_variabel(false),
				'survey'							=> $this->_survey(),
				'view_data'							=> $this->_view_data()
			)
		)
	//	->autoload_form(false)
		->set_template
		(
			array
			(
				'form'								=> 'form',
				'read'								=> 'read'
			)
		)
		->render('ta__musrenbang');
	}
	
	public function validate_form($data = array())
	{
		$this->form_validation->set_rules('nilai_skpd', 'Nilai', 'required|callback_validasi_maks_verifikasi');
		if($this->form_validation->run() === FALSE)
		{
			return throw_exception(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
		}
		$prepare									= array
		(
			'flag'									=> (1 == $this->input->post('tolak') ? 8 : (0 == $this->input->post('proses_renja') ? 7 : 0)),
			'variabel_skpd'							=> json_encode($this->input->post('variabel_skpd')),
			'nilai_skpd'							=> str_replace(',', '', $this->input->post('nilai_skpd')),
			'prioritas_skpd'						=> $this->input->post('prioritas_skpd'),
			'alasan_skpd'							=> $this->input->post('alasan_skpd'),
			'map_coordinates'						=> $this->input->post('map_coordinates'),
			'jenis_usulan'							=> $this->input->post('jenis_usulan')
			
		);
		//print_r($prepare);exit;
		if(1 != $this->input->post('tolak'))
		{
			$callback								= 'insert_to_kegiatan';
		}
		else
		{
			$callback								= 'remove_to_kegiatan';
		}
		$this->update_data('ta__musrenbang', $prepare, array('id' => $this->_primary), go_to(), $callback);
	}
	
	public function validasi_maks_verifikasi($value = 0)
	{
		$value										= str_replace(',', '', $value);
		$maksimal_verifikasi						= $this->model->select('pagu_musrenbang')->get_where('ref__unit', array('id' => $this->_id_unit), 1)->row('pagu_musrenbang');
		
		$hasil_verifikasi							= $this->model
		->select_sum('nilai_skpd')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->join('ta__program', 'ta__program.id = ref__musrenbang_jenis_pekerjaan.id_prog')
		->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
		->get_where('ta__musrenbang', array('ta__musrenbang.id !=' => $this->input->get('id'), 'ref__sub.id_unit' => $this->_id_unit, 'ta__musrenbang.flag' => 7))
		->row('nilai_skpd');
		if($maksimal_verifikasi - ($hasil_verifikasi + $value) < 0)
		{
			$this->form_validation->set_message('validasi_maks_verifikasi', '%s verifikasi melebihi nilai maksimal verifikasi sebesar ' . number_format(($hasil_verifikasi + $value) - $maksimal_verifikasi));
			return false;
		}
		return true;
		
	}
	
	public function insert_to_kegiatan()
	{
		$checker									= $this->model->get_where('ta__kegiatan', array('id' => $this->_primary))->num_rows();
		$pekerjaan									= $this->model->get_where('ref__musrenbang_jenis_pekerjaan', array('id' => $this->input->post('jenis_pekerjaan')), 1)->row();
		$id_prog									= (isset($pekerjaan->id_prog) ? $pekerjaan->id_prog : 0);
		if (($this->input->post('jenis_usulan'))!=='')
			{
			$jenis_usulan							= $this->input->post('jenis_usulan');
		}
		else
		{
			$jenis_usulan							= 4;
		}
		$kegiatan									= (isset($pekerjaan->nama_pekerjaan) ? $pekerjaan->nama_pekerjaan . ' (' . $this->input->post('map_address') . ')' : null);
		$prepare									= array
		(
			'id_prog'								=> $id_prog,
			'id_musrenbang'							=> $this->_primary,
			'pengusul'								=> 0,
			'flag'									=> 1,
			'map_coordinates'						=> $this->input->post('map_coordinates'),
			'map_address'							=> $this->input->post('map_address'),
			'kegiatan'								=> $this->input->post('nama_kegiatan'),
			'nilai_usulan'							=> str_replace(',', '', $this->input->post('nilai_skpd')),
			'pagu'									=> str_replace(',', '', $this->input->post('nilai_skpd')),
			'variabel_usulan'						=> json_encode($this->input->post('variabel_skpd')),
			'tahun'									=> get_userdata('year'),
			'jenis_usulan'							=> $jenis_usulan

		




		);
		if($checker > 0)
		{
			$this->update_data('ta__kegiatan', $prepare, array('id' => $this->_primary), go_to());
		}
		else
		{
			$this->insert_data('ta__kegiatan', $prepare);
		}
	}
	
	public function remove_to_kegiatan()
	{
		$this->delete_data('ta__kegiatan', array('id_musrenbang' => $this->_primary));
	}
	
	private function _view_data()
	{
		if(!$this->input->get('id')) return false;
		$query										= $this->model
		->select
		('
			ref__rt.rt,
			ref__rw.rw,
			ref__kelurahan.nama_kelurahan,
			ref__kecamatan.kecamatan,
			ref__musrenbang_isu.nama_isu,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.deskripsi,
			ta__musrenbang.*
		')
		->join('ref__rt', 'ref__rt.id = ta__musrenbang.id_rt')
		->join('ref__rw', 'ref__rw.id = ref__rt.id_rw')
		->join('ref__kelurahan', 'ref__kelurahan.id = ref__rw.id_kel')
		->join('ref__kecamatan', 'ref__kecamatan.id = ref__kelurahan.id_kec')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->join('ref__musrenbang_isu', 'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu')
		
		->get_where
		(
			'ta__musrenbang',
			array
			(
				'ta__musrenbang.id'					=> $this->input->get('id')
			),
			1
		)
		->row();
		if(isset($query->variabel_skpd))
		{
			$query->variabel_skpd					= json_decode($query->variabel_skpd);
		}
		if(isset($query->images))
		{
			$query->images							= json_decode($query->images);
		}
		if(isset($query->jenis_pekerjaan))
		{
			$variabel_output						= array();
			$variabel								= $this->model->get_where('ref__musrenbang_variabel', array('id_musrenbang_jenis_pekerjaan' => $query->jenis_pekerjaan))->result();
			if($variabel)
			{
				foreach($variabel as $key => $val)
				{
					$id								= $val->id;
					$variabel_output[$id]			= array
					(
						'kode_variabel'				=> $val->kode_variabel,
						'nama_variabel'				=> $val->nama_variabel,
						'nilai'						=> (isset($query->variabel_skpd->$id) ? $query->variabel_skpd->$id : 0),
						'satuan'					=> $val->satuan
					);
				}
				$query->variabel_skpd				= $variabel_output;
			}
		}
		//print_r($query);exit;
		return $query;
	}
	
	private function _isu()
	{
		$selected									= $this->input->get('id');
		if($selected)
		{
			$selected								= $this->model->select('ref__musrenbang_isu.id')->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')->join('ref__musrenbang_isu', 'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu')->get_where('ta__musrenbang', array('ta__musrenbang.id' => $selected), 1)->row('id');
			//echo $this->model->last_query(); exit;
		}
		$output										= '<option value="">Silakan pilih isu</option>';
		$query										= $this->model->order_by('kode')->get('ref__musrenbang_isu')->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				if($val['id'] != $selected) continue;
				$output								.= '<option value="' . $val['id'] . '" selected>' . sprintf('%02d', $val['kode']) . '. ' . $val['nama_isu'] . '</option>';
			}
		}
		$output										= '
			<select name="isu" class="form-control isu" data-url="' . current_page() . '" readonly>
				' . $output . '
			</select>
		';
		return $output;
	}
	
	private function _jenis_pekerjaan($ajax = true)
	{
		$selected									= $this->input->get('id');
		if($selected)
		{
			$selected								= $this->model->select('jenis_pekerjaan')->get_where('ta__musrenbang', array('id' => $selected), 1)->row('jenis_pekerjaan');
		}
		$primary									= ($this->input->post('primary') ? $this->input->post('primary') : $this->model->select('ref__musrenbang_isu.id AS id_isu')->join('ref__musrenbang_isu', 'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu')->get_where('ref__musrenbang_jenis_pekerjaan', array('ref__musrenbang_jenis_pekerjaan.id' => $selected))->row('id_isu'));
		$options									= '<option value="">Silakan pilih jenis pekerjaan</option>';
		$query										= $this->model->order_by('kode')->get_where('ref__musrenbang_jenis_pekerjaan', array('id_isu' => $primary))->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				if($val['id'] != $selected) continue;
				$options							.= '<option value="' . $val['id'] . '" selected>' . sprintf('%02d', $val['kode']) . '. ' . $val['nama_pekerjaan'] . '</option>';
			}
		}
		$output										= '
			<select name="jenis_pekerjaan" class="form-control jenis_pekerjaan readonly" data-url="' . current_page() . '" readonly>
				' . $options . '
			</select>
		';
		if($ajax)
		{
			make_json
			(
				array
				(
					'html'							=> $options
				)
			);
		}
		else
		{
			return $output;
		}
	}
	
	private function _variabel($ajax = true)
	{
		$output										= null;
		$selected									= $this->input->post('primary');
		if(!$selected)
		{
			$selected								= $this->model->select('jenis_pekerjaan')->get_where('ta__musrenbang', array('id' => $selected), 1)->row('jenis_pekerjaan');
		}
		$query										= $this->model->order_by('kode_variabel')->order_by('kode_variabel')->get_where('ref__musrenbang_variabel', array('id_musrenbang_jenis_pekerjaan' => $selected))->result_array();
		$description								= $this->model->select('deskripsi, nilai_satuan')->get_where('ref__musrenbang_jenis_pekerjaan', array('id' => $selected), 1)->row();
		$existing_variable							= $this->model->select('variabel_skpd')->get_where('ta__musrenbang', array('id' => $this->_id), 1)->row('variabel_skpd');
		if(!$existing_variable)
		{
			$existing_variable						= $this->model->select('variabel_kecamatan')->get_where('ta__musrenbang', array('id' => $this->_id), 1)->row('variabel_kecamatan');
		}
		$existing_variable							= json_decode($existing_variable, true);
		if($this->_id)
		{
			$existing								= $this->model->get_where('ta__musrenbang', array('id' => $this->_id), 1)->row();
		}
		$output										= '
			<div class="row form-group">
				<div class="col-sm-12">
					<div class="alert alert-info">
						' . (isset($description->deskripsi) ? $description->deskripsi : null) . '
					</div>
				</div>
			</div>
		';
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<div class="row form-group">
						<label class="control-label col-sm-5">
							' . $val['kode_variabel'] . '. ' . $val['nama_variabel'] . '
						</label>
						<div class="col-sm-7">
							<div class="input-group">
								<input type="number" name="variabel_skpd[' . $val['id'] . ']" class="form-control input-sm bordered variable_value" value="' . (isset($existing_variable[$val['id']]) ? $existing_variable[$val['id']] : 0) . '" />
								<span class="input-group-addon">' . $val['satuan'] . '</span>
							</div>
						</div>
					</div>
				';
			}
			$output									.= '
				<div class="row form-group">
					<label class="control-label col-sm-5">
						Nilai
					</label>
					<div class="col-sm-7">
						<div class="input-group">
							<span class="input-group-addon">Rp.</span>
							<input type="text" name="nilai_skpd" class="form-control input-sm nilai_awal" value="' . ('update' == $this->_method && isset($existing->nilai_skpd) && $existing->nilai_skpd > 0 ? $existing->nilai_skpd : (isset($existing->nilai_kecamatan) ? $existing->nilai_kecamatan : 0)) . '" data-nilai="' . $description->nilai_satuan . '" role="price-format" style="padding: 0 8px" readonly />
						</div>
					</div>
				</div>
			';
		}
		$survey										= null;
		$pertanyaan									= $this->model->get_where('ref__musrenbang_pertanyaan', array('id_musrenbang_jenis_pekerjaan' => $selected))->result_array();
		if($pertanyaan)
		{
			foreach($pertanyaan as $key => $val)
			{
				$survey								.= '
					<div class="item animated fadeIn' . ($key == 0 ? ' active' : '') . '">
						<div class="text-center">
							' . $val['kode'] . '. ' . $val['pertanyaan'] . '
							<br />
							<button class="btn btn-success btn-xs button-answer" data-answer="1">
								<i class="fa fa-check-circle"></i>
								' . phrase('true') . '
							</button>
							<button class="btn btn-danger btn-xs button-answer" data-answer="0">
								<i class="fa fa-times-circle"></i>
								' . phrase('false') . '
							</button>
						</div>
						<input type="hidden" name="survey[' . $val['id'] . ']" class="input-answer" value="n" />
					</div>
				';
			}
		}
		$survey										= '
			<div class="form-group animated zoomIn">
				<label class="control-label big-label text-muted text-uppercase" for="survey">
					<span class="text-sm text-capitalize text-danger pull-right">' . phrase('required') . '</span>
					Survey
				</label>
				<div class="alert alert-success">
					<div id="survey" role="carousel">
						<div class="carousel-inner" role="listbox">
							' . $survey . '
						</div>
					</div>
				</div>
			</div>
		';
		if($ajax)
		{
			make_json
			(
				array
				(
					'variable'						=> $output,
					'survey'						=> $survey
				)
			);
		}
		else
		{
			return $output;
		}
	}
	
	private function _survey()
	{
		$output										= null;
		$survey										= $this->model->select('survey')->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row('survey');
		$survey										= json_decode($survey, true);
		if($survey)
		{
			foreach($survey as $key => $val)
			{
				$pertanyaan							= $this->model->select('pertanyaan')->get_where('ref__musrenbang_pertanyaan', array('id' => $key), 1)->row('pertanyaan');
				$output								.= '
					<label>
						' . $pertanyaan . '
						<b class="text-' . (1 == $val ? 'success' : 'danger') . '">
							' . (1 == $val ? phrase('true') : phrase('false')) . '
						</b>
					</label>
				';
			}
			$output									= '
				<div class="panel panel-default">
					<div class="panel-heading no-padding">
						<a data-toggle="collapse" data-parent="#data-table" href="#collapse_data-table">
							<div class="info-box bg-default no-margin">
								<span class="info-box-icon">
									<i class="fa fa-info-circle"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-number">
										Hasil Survey
									</span>
									<span class="info-box-text">
										Klik untuk melihat detail
									</span>
								</div>
							</div>
						</a>
					</div>
					<div id="collapse_data-table" class="panel-collapse collapse">
						<div class="panel-body">
							' . $output . '
						</div>
					</div>
				</div>
			';
		}
		return $output;
	}
}