<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Verifikasi extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id');
		$this->_id_rw								= $this->input->get('id_rw');
		if(!in_array(get_userdata('group_id'), array(1, 3)))
		{
			generateMessages(403, 'Anda tidak mempunya hak akses yang cukup untuk melihat usulan', base_url('musrenbang'));
		}
		elseif(!$this->_primary)
		{
			generateMessages(301, 'Silakan memilih Usulan terlebih dahulu', base_url('musrenbang/kelurahan/data'));
		}
		$this->set_upload_path('kegiatan')
		->parent_module('musrenbang/kelurahan/data')
		->set_method('update')
		->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
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
				'id_rt'								=> ('create' == $this->_method ? null : 'disabled'),
				'map_coordinates'					=> ('create' == $this->_method ? 'map_coordinate' : 'map_marker'),
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
				1									=> '<label class="label bg-teal">Diterima Kelurahan</label>',
				2									=> '<label class="label bg-yellow">Ditolak Kelurahan</label>',
				3									=> '<label class="label bg-green">Usulan Kelurahan</label>',
				4									=> '<label class="label bg-red">Diterima Kecamatan</label>',
				5									=> '<label class="label bg-aqua">Ditolak Kecamatan</label>',
				6									=> '<label class="label bg-maroon">Usulan Kecamatan</label>',
				7									=> '<label class="label bg-maroon">Diterima SKPD</label>',
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
				'prioritas_kelurahan'				=> 'Prioritas',
				'id_rt'								=> 'RT'
			)
		)
		->set_output
		(
			array
			(
				'rt'								=> $this->model->select('ref__rt.rt')->join('ref__rt', 'ref__rt.id = ta__musrenbang.id_rt')->get_where('ta__musrenbang', array('ta__musrenbang.id' => $this->_primary), 1)->row('rt'),
				'isu'								=> $this->_isu(),
				'jenis_pekerjaan'					=> $this->_jenis_pekerjaan(),
				'variabel'							=> $this->_variabel(),
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
		if($this->form_validation->run() === false)
		{
			return generateMessages(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
		}
		$prepare									= array
		(
			'flag'									=> (1 == $this->input->post('tolak') ? 5 : 4),
			'variabel_kecamatan'					=> json_encode($this->input->post('variabel_kelurahan')),
			'nilai_kecamatan'						=> str_replace(',', '', $this->input->post('nilai_kelurahan')),
			'prioritas_kecamatan'					=> $this->input->post('prioritas_kelurahan'),
			'alasan_kecamatan'						=> $this->input->post('alasan_kelurahan')
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
		return $this->model
		->select('ref__musrenbang_isu.nama_isu')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->join('ref__musrenbang_isu', 'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu')
		->get_where('ta__musrenbang', array('ta__musrenbang.id' => $this->_primary), 1)
		->row('nama_isu');
	}
	
	private function _jenis_pekerjaan()
	{
		return $this->model
		->select('ref__musrenbang_jenis_pekerjaan.nama_pekerjaan')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->get_where('ta__musrenbang', array('ta__musrenbang.id' => $this->_primary), 1)
		->row('nama_pekerjaan');
		$usulan										= $this->model->select('nilai_usulan, variabel_usulan')->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
	}
	
	private function _variabel()
	{
		if($this->_primary)
		{
			$query									= $this->model
			->select
			('
				ta__musrenbang.variabel_usulan,
				ta__musrenbang.variabel_kelurahan,
				ref__musrenbang_jenis_pekerjaan.id as id_pekerjaan,
				ref__musrenbang_jenis_pekerjaan.deskripsi,
				ref__musrenbang_jenis_pekerjaan.nilai_satuan
			')
			->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
			->get_where
			(
				'ta__musrenbang',
				array
				(
					'ta__musrenbang.id'				=> $this->_primary
				),
				1
			)
			->row();
			if($query)
			{
				$nilai								= 1;
				$output_variabel					= null;
				$variabel_kelurahan					= json_decode($query->variabel_kelurahan, true);
				$variabel_usulan					= json_decode($query->variabel_usulan, true);
				$variabel							= $this->model->get_where('ref__musrenbang_variabel', array('id_musrenbang_jenis_pekerjaan' => $query->id_pekerjaan))->result_array();
				if($variabel)
				{
					foreach($variabel as $key => $val)
					{
						if(isset($variabel_kelurahan[$val['id']]))
						{
							$value					= $variabel_kelurahan[$val['id']];
						}
						elseif(isset($variabel_usulan[$val['id']]))
						{
							$value					= $variabel_usulan[$val['id']];
						}
						else
						{
							$value					= 0;
						}
						$nilai						*= $value;
						$output_variabel			.= '
							<div class="row form-group">
								<label class="control-label col-sm-5">
									' . $val['kode_variabel'] . '. ' . $val['nama_variabel'] . '
								</label>
								<div class="col-sm-7">
									<div class="input-group">
										<input type="text" name="variabel_kelurahan[' . $val['id'] . ']" class="form-control input-sm bordered variable_value" value="' . $value . '" />
										<span class="input-group-addon">' . $val['satuan'] . '</span>
									</div>
								</div>
							</div>
						';
					}
				}
				return '
					<div class="form-group">
						<div class="alert alert-info">
							' . $query->deskripsi . '
						</div>
					</div>
					' . $output_variabel . '
					<div class="row form-group">
						<label class="control-label col-sm-5">
							Nilai
						</label>
						<div class="col-sm-7">
							<div class="input-group">
								<span class="input-group-addon">Rp.</span>
								<input type="text" name="nilai_kelurahan" class="form-control input-sm nilai_awal" value="' . $nilai * $query->nilai_satuan . '" data-nilai="' . $query->nilai_satuan . '" role="price-format" style="padding: 0 8px" readonly />
							</div>
						</div>
					</div>
				';
			}
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
						<a href="' . get_image('usulan', $file) . '" target="_blank">
							<img src="' . get_image('usulan', $file, 'thumb') . '" class="img-thumb img-responsive" />
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