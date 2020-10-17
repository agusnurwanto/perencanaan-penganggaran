<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Verifikasi extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id');
		$this->_id_rw								= $this->input->get('id_rw');
		$this->_id_kel								= $this->input->get('id_kel');
		if(!in_array(get_userdata('group_id'), array(1, 2)))
		{
			generateMessages(403, 'Anda tidak mempunya hak akses yang cukup untuk melihat usulan', base_url('musrenbang/kecamatan'));
		}
		elseif(!$this->_primary)
		{
			generateMessages(301, 'Silakan memilih Usulan terlebih dahulu', base_url('musrenbang/kecamatan/data'));
		}
		$this->set_upload_path('kegiatan')
		->parent_module('musrenbang/kecamatan/data')
		->set_method('update')
		->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		if('isu' == $this->input->post('method'))
		{
			return $this->_jenis_pekerjaan();
		}
		elseif('jenis_pekerjaan' == $this->input->post('method'))
		{
			return $this->_variabel();
		}
		$this->set_title('Verifikasi Usulan')
		->set_icon('fa fa-bookmark')
		->add_class
		(
			array
			(
				'map_address'						=> 'address-placeholder',
				'isu'								=> 'isu'
			)
		)
		->set_field
		(
			array
			(
				'kode'								=> ('create' == $this->_method ? 'last_insert' : 'disabled'),
				'id_rw'								=> ('create' == $this->_method ? null : 'disabled'),
				'id_rt'								=> ('create' == $this->_method ? null : 'disabled'),
				'map_coordinates'					=> ('create' == $this->_method ? 'map_coordinate' : 'map_coordinate'), // map_marker
				'map_address'						=> 'textarea' . ('create' == $this->_method ? null : ', disabled'),
				'urgensi'							=> 'textarea' . ('create' == $this->_method ? null : ', disabled'),
				'images'							=> 'images',
				'nilai_kelurahan'					=> 'number_format',
				'nilai_usulan'						=> 'number_format',
				'alasan_kelurahan'					=> 'textarea',
				'prioritas_kelurahan'				=> 'last_insert'
			)
		)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> '<label class="label bg-primary">Usulan</label>',
				1									=> '<label class="label bg-green">Diterima Kelurahan</label>',
				2									=> '<label class="label bg-red">Ditolak Kelurahan</label>',
				3									=> '<label class="label bg-primary">Usulan Kelurahan</label>',
				4									=> '<label class="label bg-green">Diterima Kecamatan</label>',
				5									=> '<label class="label bg-red">Ditolak Kecamatan</label>',
				6									=> '<label class="label bg-primary">Usulan Kecamatan</label>',
				7									=> '<label class="label bg-green">Diterima SKPD</label>',
				8									=> '<label class="label bg-red">Ditolak SKPD</label>'
			)
		)
		->set_relation
		(
			'id_prioritas_pembangunan',
			'ref__prioritas_pembangunan.id',
			'{ref__prioritas_pembangunan.kode}. {ref__prioritas_pembangunan.uraian}',
			null,
			null,
			'ref__prioritas_pembangunan.kode'
		)
		->set_relation
		(
			'jenis_usulan',
			'ref__renja_jenis_usulan.id',
			'{ref__renja_jenis_usulan.kode}. {ref__renja_jenis_usulan.nama_jenis_usulan}'
		)
		/*->set_relation
		(
			'jenis_pekerjaan',
			'ref__musrenbang_jenis_pekerjaan.id',
			'{ref__musrenbang_jenis_pekerjaan.nama_pekerjaan}',
			null,
			null,
			'ref__musrenbang_jenis_pekerjaan.kode'
		)*/
		->set_relation
		(
			'id_rw',
			'ref__rw.id',
			'{ref__rw.rw}',
			array
			(
				'ref__rw.id_kel'					=> $this->_id_kel
			),
			null,
			'ref__rw.rw'
		)
		->set_relation
		(
			'id_rt',
			'ref__rt.id',
			'{ref__rt.rt}',
			array
			(
				'ref__rt.id_rw'						=> $this->_id_rw
			),
			null,
			'ref__rt.rt'
		)
		->set_alias
		(
			array
			(
				'map_address'						=> 'alamat',
				'flag'								=> 'Status',
				'prioritas_kecamatan'				=> 'Prioritas',
				'id_prioritas_pembangunan'			=> 'Prioritas Pembangunan',
				'id_rt'								=> 'RT'
			)
		)
		->set_output
		(
			array
			(
				'rt'								=> $this->model->select('ref__rt.rt')->join('ref__rt', 'ref__rt.id = ta__musrenbang.id_rt')->get_where('ta__musrenbang', array('ta__musrenbang.id' => $this->_primary), 1)->row('rt'),
				'isu'								=> $this->_isu(),
				'jenis_pekerjaan'					=> $this->_jenis_pekerjaan(false),
				'variabel'							=> $this->_variabel(false),
				'survey'							=> $this->_survey(),
				'images'							=> $this->_images(),
				'view_data'							=> $this->_view_data()
			)
		)
		->where('id', $this->_primary)
		->set_template('form', 'form')
		->render('ta__musrenbang');
	}
	
	public function validate_form($data = array())
	{
		$this->form_validation->set_rules('alasan_kecamatan', 'Alasan Kecamatan', 'required|min_length[10]');
		$this->form_validation->set_rules('id_prioritas_pembangunan', 'Prioritas Pembangunan', 'required');
		//$this->form_validation->set_rules('alamat', 'Alamat', 'required');
		if($this->form_validation->run() === false)
		{
			return generateMessages(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
		}
		$prepare									= array
		(
			'flag'									=> (1 == $this->input->post('tolak') ? 5 : 4),
			'variabel_kecamatan'					=> json_encode($this->input->post('variabel_kelurahan')),
			'nilai_kecamatan'						=> str_replace(',', '', $this->input->post('nilai_kelurahan')),
			'prioritas_kecamatan'					=> $this->input->post('prioritas_kecamatan'),
			'id_prioritas_pembangunan'				=> $this->input->post('id_prioritas_pembangunan'),
			'jenis_usulan'						=> $this->input->post('jenis_usulan'),
			'alasan_kecamatan'						=> $this->input->post('alasan_kecamatan')
		);
		$this->update_data('ta__musrenbang', $prepare, array('id' => $this->_primary), go_to());
	}
	
	private function _view_data()
	{
		if(!$this->_primary) return false;
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
				'ta__musrenbang.id'					=> $this->_primary
			),
			1
		)
		->row();
		if(isset($query->variabel_usulan))
		{
			$query->variabel_usulan					= json_decode($query->variabel_usulan);
		}
		if(isset($query->variabel_kelurahan))
		{
			$query->variabel_kelurahan				= json_decode($query->variabel_kelurahan);
		}
		if(isset($query->images))
		{
			$query->images							= json_decode($query->images);
		}
		if(isset($query->jenis_pekerjaan))
		{
			$variabel_usulan						= array();
			$variabel_kelurahan						= array();
			$variabel								= $this->model->get_where('ref__musrenbang_variabel', array('id_musrenbang_jenis_pekerjaan' => $query->jenis_pekerjaan))->result();
			if($variabel)
			{
				foreach($variabel as $key => $val)
				{
					$id								= $val->id;
					$variabel_usulan[$id]			= array
					(
						'kode_variabel'				=> $val->kode_variabel,
						'nama_variabel'				=> $val->nama_variabel,
						'nilai'						=> (isset($query->variabel_usulan->$id) ? $query->variabel_usulan->$id : 0),
						'satuan'					=> $val->satuan
					);
					$variabel_kelurahan[$id]		= array
					(
						'kode_variabel'				=> $val->kode_variabel,
						'nama_variabel'				=> $val->nama_variabel,
						'nilai'						=> (isset($query->variabel_kelurahan->$id) ? $query->variabel_kelurahan->$id : 0),
						'satuan'					=> $val->satuan
					);
				}
				$query->variabel_usulan				= $variabel_usulan;
				$query->variabel_kelurahan			= $variabel_kelurahan;
			}
		}
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
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $selected ? ' selected' : null) . '>' . sprintf('%02d', $val['kode']) . '. ' . $val['nama_isu'] . '</option>';
			}
		}
		$output										= '
			<select name="isu" class="form-control isu" data-url="' . current_page() . '">
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
				$options							.= '<option value="' . $val['id'] . '"' . ($val['id'] == $selected ? ' selected' : null) . '>' . sprintf('%02d', $val['kode']) . '. ' . $val['nama_pekerjaan'] . '</option>';
			}
		}
		$output										= '
			<select name="jenis_pekerjaan" class="form-control jenis_pekerjaan" data-url="' . current_page() . '">
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
		$existing_variable							= $this->model->select('variabel_kelurahan')->get_where('ta__musrenbang', array('id' => $this->input->get('id')), 1)->row('variabel_kelurahan');
		$existing_variable							= json_decode($existing_variable, true);
		if($this->input->get('id'))
		{
			$existing								= $this->model->get_where('ta__musrenbang', array('id' => $this->input->get('id')), 1)->row();
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
								<input type="number" name="variabel_kelurahan[' . $val['id'] . ']" class="form-control input-sm bordered variable_value" value="' . (isset($existing_variable[$val['id']]) ? $existing_variable[$val['id']] : 0) . '" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" />
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
							<input type="text" name="nilai_kelurahan" class="form-control input-sm nilai_awal" value="' . ('update' == $this->_method && isset($existing->nilai_kecamatan) ? $existing->nilai_kelurahan : 0) . '" data-nilai="' . $description->nilai_satuan . '" role="price-format" style="padding: 0 8px" readonly />
						</div>
					</div>
				</div>
			';
		}
		
		if($ajax)
		{
			make_json
			(
				array
				(
					'variable'						=> $output
					
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
	
	private function _images()
	{
		$images										= $this->model->select('images')->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row('images');
		$images										= json_decode($images, true);
		if($images)
		{
			$image_list								= null;
			foreach($images as $file => $label)
			{
				$image_list							.= '
					<div class="col-xs-6 col-sm-3">
						<a href="' . get_image('kegiatan', $file) . '" target="_blank">
							<img src="' . get_image('kegiatan', $file, 'thumb') . '" class="img-thumb img-responsive" />
						</a>
					</div>
				';
			}
			return '
				<div class="row">
					' . $image_list . '
				</div>
			';
		}
	}
}
