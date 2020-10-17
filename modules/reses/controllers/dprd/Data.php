<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends Aksara
{
	private $_table									= 'ta__reses';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_id_sub								= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_sub'));
		$this->_id									= $this->input->get('id');
		if(!$this->_id_sub)
		{
			generateMessages(301, 'Silakan memilih fraksi terlebih dahulu...', 'reses/dprd');
		}
		$this->set_upload_path('kegiatan');
	}
	
	public function index()
	{
		//$this->add_filter($this->_filter());
		//$maksimal_usulan							= $this->model->get_where('ref__dprd', array('id' => $this->_id_sub))->row('pagu');
		//$input_usulan								= $this->model->select_sum('nilai_usulan')->get_where('ta__reses', array('id_reses' => $this->_id_sub, 'pengusul' => 2))->row('nilai_usulan');
		//$selisih_usulan								= $maksimal_usulan - $input_usulan;
		//$diverifikasi_skpd							= $this->model->select_sum('nilai_usulan')->get_where('ta__reses', array('id_reses' => $this->_id_sub, 'pengusul' => 2, 'flag >' => 0))->row('nilai_usulan');
		//$ditolak_skpd								= $this->model->select_sum('nilai_usulan')->get_where('ta__reses', array('id_reses' => $this->_id_sub, 'pengusul' => 2, 'flag >' => 2))->row('nilai_usulan');
		//$diterima_skpd								= $diverifikasi_skpd - $ditolak_skpd;
		//$belum_verifikasi							= $input_usulan - $diverifikasi_skpd;
		/*if('create' == $this->_method)
		{			
			if($input_usulan >= $maksimal_usulan)
			{
				generateMessages(403, 'Anda telah menginput Rp. ' . number_format($input_usulan) . ' usulan dan tidak dapat menambah lagi.', base_url('reses/dprd'));
			}
		}*/
		if(1 == get_userdata('group_id') || 7 == get_userdata('group_id'))
		{
			if($this->_id_sub && 'all' != $this->_id_sub)
			{
				$sub_unit							= $this->model
				->select
				('
					ref__dprd_fraksi.kode,
					ref__dprd_fraksi.nama_fraksi,
					ref__dprd.kode as kd_dewan,
					ref__dprd.nama_dewan
				')
				->join
				(
					'ref__dprd_fraksi',
					'ref__dprd_fraksi.id = ref__dprd.id_fraksi'
				)
				->get_where
				(
					'ref__dprd',
					array
					(
						'ref__dprd.id'				=> $this->_id_sub
					),
					1
				)
				->row();
				$this->set_description
				('
					<div class="row">
						<div class="col-sm-4">
							<div class="row">
								<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
									Fraksi
								</label>
								<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
									' . $sub_unit->kode . '. ' . $sub_unit->nama_fraksi . '
								</label>
							</div>
						</div>
						<div class="col-sm-4">
							<div class="row">
								<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
									DPRD
								</label>
								<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
									' . $sub_unit->kd_dewan . '. ' . $sub_unit->nama_dewan . '
								</label>
							</div>
						</div>
					</div>			
				')
				->where('id_reses', $this->_id_sub)
				->set_default
				(
					array
					(
						'id_reses'					=> $this->_id_sub
					)
				)
				->unset_column
				('
					id_reses
				')
				->unset_field
				('
					id_reses
				');
			}
			else
			{
				$this->set_relation
				(
					'id_reses',
					'ref__dprd.id',
					'{ref__dprd.kode}. {ref__dprd.nama_dewan}'
				);
			}
		}
		else
		{
			$this
			->where('id_reses', $this->_id_sub)
			->set_default
			(
				array
				(
					'id_reses'						=> $this->_id_sub
				)
			)
			->unset_column
			('
				id_reses
			')
			->unset_field
			('
				id_reses
			');
		}
		if('isu' == $this->input->post('method'))
		{
			return $this->_jenis_pekerjaan();
		}
		elseif('jenis_pekerjaan' == $this->input->post('method'))
		{
			return $this->_variabel();
		}
		if('create' == $this->_method)
		{
			$this->set_default('created', date('Y-m-d H:i:s'));
		}
		elseif('update' == $this->_method)
		{
			$this->set_default('updated', date('Y-m-d H:i:s'));
		}
		$this->set_breadcrumb
		(
			array
			(
				'reses/dprd'								=> 'Reses'
			)
		)
		->set_title('Reses DPRD')		
		->unset_column
		('
			id,
			id_musrenbang,
			id_reses,
			id_model,
			alamat_detail,
			nama_kelurahan,
			kecamatan_ref__kecamatan,
			kelurahan,
			kecamatan,
			images,
			pengusul,
			capaian_program,
			input_kegiatan,
			kelompok_sasaran,
			waktu_pelaksanaan,
			variabel_usulan,
			map_coordinates,
			map_address,
			jns_kegiatan,
			jenis_kegiatan_renja,
			survey,
			variabel,
			pilihan,
			tahun,
			kode,
			kd_isu,
			kd_jenis_pekerjaan,
			created,
			updated,
			pagu,
			riwayat_skpd,
			jenis_usulan,
			lock_kegiatan,
			flag,
			pagu_1,
			id_sumber_dana,
			nama_pekerjaan,
			kegiatan,
			asistensi_ready,
			jenis_anggaran,
			latar_belakang_perubahan
		')
		->unset_field
		('
			id,
			id_musrenbang,
			flag,
			tahun
		')
		->unset_truncate('kegiatan_judul_baru')
		->column_order('kd_urusan, nm_program, jns_kegiatan, kegiatan_judul_baru')
		->field_order('id_sub, id_prog')
		->merge_content('{kd_urusan}.{kd_bidang}.{kd_program}.{kd_keg}', 'Kode')
		->add_class
		(
			array
			(
				'map_address'						=> 'address-placeholder',
				'isu'								=> 'isu',
				'kegiatan'							=> 'hahahihi-reses'
			)
		)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> '<label class="label bg-navy">Usulan Reses</label>',
				1									=> '<label class="label bg-green">Diterima SKPD</label>',
				2									=> '<label class="label bg-yellow">Ditolak SKPD</label>'
			)
		)
		->set_field
		(
			array
			(
				'map_coordinates'					=> 'coordinate',
				'map_address'						=> 'textarea, readonly',
				'alamat_detail'						=> 'textarea',
				'images'							=> 'images',
				'nilai'								=> 'number_format',
				'kd_bidang'							=> 'sprintf',
				'kd_unit'							=> 'sprintf',
				'kd_sub'							=> 'sprintf',
				'kd_program'						=> 'sprintf',
				'kd_id_prog'						=> 'sprintf',
				'kd_keg'							=> 'sprintf, last_insert',
				'kegiatan'							=> 'textarea, readonly',
				'kegiatan_judul_baru'				=> 'textarea',
				'nilai_usulan'						=> 'number_format'
			)
		)
		->set_relation
		(
			'id_kel',
			'ref__kelurahan.id',
			'{ref__kecamatan.kode}.{ref__kelurahan.kode}. {ref__kelurahan.nama_kelurahan} - {ref__kecamatan.kecamatan}',
			NULL,
			array
			(
				array
				(
					'ref__kecamatan',
					'ref__kecamatan.id = ref__kelurahan.id_kec'
				)
			),
			array
			(
				'ref__kecamatan.kode'					=> 'ASC',
				'ref__kelurahan.kode'					=> 'ASC'
			)
		)
		->set_default
		(
			array
			(
				'tahun'								=> get_userdata('year'),
				'pengusul'							=> 2,
				'flag'								=> 0,
				'id_prog'							=> $this->model->select('id_prog')->get_where('ref__musrenbang_jenis_pekerjaan', array('id' => $this->input->post('jenis_kegiatan')), 1)->row('id_prog')
			)
		)
		->set_alias
		(
			array
			(
				'kode'								=> 'No',
				'nm_program'						=> 'Program',
				'jenis_kegiatan'					=> 'Kelompok Kegiatan',
				'flag'								=> 'Status',
				'id_kel'							=> 'Kelurahan'
			)
		)
		->set_relation
		(
			'id_prog',
			'ta__program.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__program.kd_program} {ref__program.nm_program}',
			null,
			array
			(
				array
				(
					'ref__program',
					'ref__program.id = ta__program.id_prog'
				),
				array
				(
					'ref__bidang',
					'ref__bidang.id = ref__program.id_bidang'
				),
				array
				(
					'ref__urusan',
					'ref__urusan.id = ref__bidang.id_urusan'
				)
			),
			array
			(
				'ref__urusan.kd_urusan'				=> 'ASC',
				'ref__bidang.kd_bidang'				=> 'ASC',
				'ref__program.kd_program'			=> 'ASC'
			)
		)
		->set_relation
		(
			'jenis_kegiatan',
			'ref__musrenbang_jenis_pekerjaan.id',
			'{ref__musrenbang_jenis_pekerjaan.nama_pekerjaan}'
			//'{ref__musrenbang_jenis_pekerjaan.nama_pekerjaan as jns_kegiatan}'
		)
		->set_output
		(
			array
			(
				'isu'								=> $this->_isu(),
				'jenis_kegiatan'					=> $this->_jenis_pekerjaan(false),
				'variabel'							=> $this->_variabel(false),
				'view_data'							=> $this->_view_data()
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required|is_unique[ta__reses.kode.id.' . $this->_id . ']',
				'jenis_kegiatan'					=> 'required|numeric',
				'nilai'								=> 'required',
				//'map_coordinates'					=> 'required',
				//'map_address'						=> 'required',
				'judul_kegiatan_baru'				=> 'required',
				'variabel_usulan'					=> 'required',
				'id_kel'							=> 'required'
				//'survey'							=> 'required|callback_survey_checker'
			)
		)
		->order_by
		(
			array
			(
				'kd_urusan'							=> 'ASC',
				'kd_bidang'							=> 'ASC',
				'kd_program'						=> 'ASC',
				'kd_keg'							=> 'ASC'
			)
		)
		->where('pengusul', 2)
		->set_template
		(
			array
			(
				'form'								=> 'form',
				'read'								=> 'read'
			)
		)
		->modal_size('modal-lg')
		->render($this->_table); 
	}
	
	public function survey_checker()
	{
		$checker									= $this->input->post('survey');
		if(is_array($checker))
		{
			if(sizeof($checker) > 0)
			{
				$answered							= true;
				foreach($checker as $key => $val)
				{
					if(!in_array($val, array('0', 1)))
					{
						$answered					= false;
					}
				}
				if(!$answered)
				{
					$this->form_validation->set_message('survey_checker', 'Anda harus menjawab seluruh pertanyaan yang diberikan');
					return false;
				}
			}
		}
		return true;
	}
	
	private function _view_data()
	{
		$output										= $this->model
		->select
		('
			ta__reses.*,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__program.kd_program,
			ref__musrenbang_jenis_pekerjaan.kode AS kd_jenis_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.deskripsi,
			ref__musrenbang_isu.kode AS kode_isu,
			ref__musrenbang_isu.nama_isu,
			ref__dprd.kode AS kode_reses,
			ref__dprd.nama_dewan
		')
		->join('ta__program', 'ta__program.id = ta__reses.id_prog', 'LEFT')
		->join('ref__program', 'ref__program.id = ta__program.id_prog', 'LEFT')
		->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang', 'LEFT')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan', 'LEFT')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__reses.jenis_kegiatan', 'LEFT')
		->join('ref__musrenbang_isu', 'ref__musrenbang_isu.id = ta__reses.jenis_kegiatan', 'LEFT')
		->join('ref__dprd', 'ref__dprd.id = ta__reses.id_reses', 'LEFT')
		->get_where('ta__reses', array('ta__reses.id' => $this->_id), 1)
		->row();
		$capaian_program							= (isset($output->capaian_program) ? json_decode($output->capaian_program) : array());
		$survey										= (isset($output->survey) ? json_decode($output->survey) : array());
		$variabel_usulan							= (isset($output->variabel_usulan) ? json_decode($output->variabel_usulan) : array());
		$variabel									= (isset($output->variabel) ? json_decode($output->variabel) : array());
		$images										= (isset($output->images) ? json_decode($output->images) : array());
		if($capaian_program)
		{
			$output->capaian_program				= array();
			foreach($capaian_program as $key => $val)
			{
				$capaian_program_output				= $this->model->get_where('ta__program_capaian', array('id' => $key), 1)->row();
				if($capaian_program_output)
				{
					$output->capaian_program[]		= $capaian_program_output->kode . '. ' . $capaian_program_output->tolak_ukur . ' ' . $capaian_program_output->target . ' ' . $capaian_program_output->satuan;
				}
			}
		}
		if($survey)
		{
			$output->survey							= array();
			foreach($survey as $key => $val)
			{
				$survey_question					= $this->model->get_where('ref__musrenbang_pertanyaan', array('id' => $key), 1)->row();
				if($survey_question)
				{
					$survey_question->value			= $val;
					$output->survey[]				= $survey_question;
				}
			}
		}
		if($variabel_usulan)
		{
			$output->variabel_usulan				= array();
			foreach($variabel_usulan as $key => $val)
			{
				$variabel_usulan_output				= $this->model->get_where('ref__musrenbang_variabel', array('id' => $key), 1)->row();
				if($variabel_usulan_output)
				{
					$variabel_usulan_output->value	= $val;
					$output->variabel_usulan[]		= $variabel_usulan_output;
				}
			}
		}
		if($variabel)
		{
			$output->variabel						= array();
			foreach($variabel as $key => $val)
			{
				$variabel_output					= $this->model->get_where('ref__musrenbang_variabel', array('id' => $key), 1)->row();
				if($variabel_output)
				{
					$variabel_output->value			= $val;
					$output->variabel[]				= $variabel_output;
				}
			}
		}
		if($images)
		{
			$output->images							= array();
			foreach($images as $key => $val)
			{
				$output->images[$key]				= $val;
			}
		}
		//print_r($output);exit;
		return $output;
	}
	
	private function _isu()
	{
		$selected									= $this->_id;
		if($selected)
		{
			$selected								= $this->model
			->select
			('
				ref__musrenbang_isu.id
			')
			->join
			(
				'ref__musrenbang_jenis_pekerjaan',
				'ref__musrenbang_jenis_pekerjaan.id = ta__reses.jenis_kegiatan'
			)
			->join
			(
				'ref__musrenbang_isu',
				'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu'
			)
			->get_where
			(
				'ta__reses',
				array
				(
					'ta__reses.id'				=> $selected
				),
				1
			)
			->row('id');
		}
		$output										= '<option value="">Silakan pilih isu</option>';
		$query										= $this->model
		->order_by
		('
			kode
		')
		->get('ref__musrenbang_isu')
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<option value="' . $val['id'] . '"' . ($val['id'] == $selected ? ' selected' : null) . '>
						' . sprintf('%02d', $val['kode']) . '. ' . $val['nama_isu'] . '
					</option>
				';
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
		$selected									= $this->_id;
		if($selected)
		{
			$selected								= $this->model
			->select
			('
				jenis_kegiatan
			')
			->get_where
			(
				'ta__reses',
				array
				(
					'id'							=> $selected
				),
				1
			)
			->row('jenis_kegiatan');
		}
		if($this->input->post('primary'))
		{
			$primary								= $this->input->post('primary');
		}
		else
		{
			$primary								= $this->model
			->select
			('
				ref__musrenbang_isu.id AS id_isu
			')
			->join
			(
				'ref__musrenbang_isu',
				'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu'
			)
			->get_where
			(
				'ref__musrenbang_jenis_pekerjaan',
				array
				(
					'ref__musrenbang_jenis_pekerjaan.id'		=> $selected
				)
			)
			->row('id_isu');
		}
		$options									= '<option value="">Silakan pilih jenis pekerjaan</option>';
		$query										= $this->model
		->order_by
		('
			kode
		')
		->get_where
		(
			'ref__musrenbang_jenis_pekerjaan',
			array
			(
				'id_isu'							=> $primary
			)
		)
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options							.= '
					<option value="' . $val['id'] . '"' . ($val['id'] == $selected ? ' selected' : null) . '>
						' . sprintf('%02d', $val['kode']) . '. ' . $val['nama_pekerjaan'] . '
					</option>
				';
			}
		}
		$output										= '
			<select name="jenis_kegiatan" class="form-control jenis_pekerjaan" data-url="' . current_page() . '">
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
			$selected								= $this->model
			->select
			('
				jenis_kegiatan
			')
			->get_where
			('
				ta__reses',
				array
				(
					'id'							=> $this->_id
				),
				1
			)
			->row('jenis_kegiatan');
		}
		$query										= $this->model
		->order_by
		('
			kode_variabel
		')
		->get_where
		(
			'ref__musrenbang_variabel',
			array
			(
				'id_musrenbang_jenis_pekerjaan'		=> $selected
			)
		)
		->result_array();
		$description								= $this->model
		->select
		('
			nama_pekerjaan,
			deskripsi,
			nilai_satuan
		')
		->get_where
		(
			'ref__musrenbang_jenis_pekerjaan',
			array
			(
				'id'								=> $selected
			),
			1
		)
		->row();
		$existing_variable							= $this->model
		->select
		('
			variabel_usulan
		')
		->get_where
		(
			'ta__reses',
			array
			(
				'id'								=> $this->_id
			),
			1
		)
		->row('variabel_usulan');
		//echo $this->model->last_query();exit;
		//print_r($existing_variable);exit;
		$existing_variable							= json_decode($existing_variable, true);
		if($this->_id)
		{
			$existing								= $this->model
			->get_where
			(
				'ta__reses',
				array
				(
					'id'							=> $this->_id
				),
				1
			)
			->row();
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
								<input type="number" min="0" name="variabel_usulan[' . $val['id'] . ']" class="form-control input-sm bordered variable_value" value="' . (isset($existing_variable[$val['id']]) ? $existing_variable[$val['id']] : 0) . '" />
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
							<input type="text" name="nilai_usulan" class="form-control input-sm nilai_awal" value="' . ('update' == $this->_method && isset($existing->nilai_usulan) ? $existing->nilai_usulan : 0) . '" data-nilai="' . (isset($description->nilai_satuan) ? $description->nilai_satuan : 0) . '" role="price-format" style="padding: 0 8px" readonly />
						</div>
					</div>
				</div>
			';
		}
		$survey										= null;
		$pertanyaan									= $this->model
		->get_where
		(
			'ref__musrenbang_pertanyaan',
			array
			(
				'id_musrenbang_jenis_pekerjaan'		=> $selected
			)
		)
		->result_array();
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
						<input type="hidden" name="survey[' . $val['id'] . ']" class="input-answer" value="0" />
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
		$kegiatan									= (isset($description->nama_pekerjaan) ? $description->nama_pekerjaan : null);
		if($ajax)
		{
			make_json
			(
				array
				(
					'variable'						=> $output,
					'survey'						=> $survey,
					'kegiatan'						=> $kegiatan
				)
			);
		}
		else
		{
			return $output;
		}
	}
	
	/*
	
	private function _variabel($ajax = true)
	{
		$existing									= null;
		$output										= null;
		$selected									= $this->input->post('primary');
		if(!$selected)
		{
			$selected								= $this->model
			->select
			('
				jenis_kegiatan
			')
			->get_where
			('
				ta__reses',
				array
				(
					'id'							=> $this->_id
				),
				1
			)
			->row('jenis_kegiatan');
		}
		$query										= $this->model
		->order_by
		('
			kode_variabel
		')
		->get_where
		(
			'ref__renja_variabel',
			array
			(
				'id_renja_jenis_pekerjaan'		=> $selected
			)
		)
		->result_array();
		$description								= $this->model
		->select
		('
			nama_pekerjaan,
			deskripsi,
			pilihan
		')
		->get_where
		(
			'ref__renja_jenis_pekerjaan',
			array
			(
				'id'								=> $selected
			),
			1
		)
		->row();
		$existing_variable							= $this->model
		->select
		('
			variabel_usulan
		')
		->get_where
		(
			'ta__musrenbang',
			array
			(
				'id'								=> $this->_id
			),
			1
		)
		->row('variabel_usulan');
		$existing_variable							= json_decode($existing_variable, true);
		if($this->_id)
		{
			$existing								= $this->model
			->get_where
			(
				'ta__reses',
				array
				(
					'id'							=> $this->_id
				),
				1
			)
			->row();
		}
		if(isset($description->pilihan) && 1 != $description->pilihan)
		{
			$output									= '
				<div class="row form-group">
					<div class="col-sm-12">
						<input type="text" name="input_kegiatan" class="form-control input_pekerjaan" value="' . (isset($existing->input_kegiatan) ? $existing->input_kegiatan : null) . '" placeholder="Silakan masukkan kegiatan" data-pekerjaan="' . $description->nama_pekerjaan . '" />
					</div>
				</div>
			';
		}
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
								<input type="number" min="0" name="variabel_usulan[' . $val['id'] . ']" class="form-control input-sm bordered variable_value" value="' . (isset($existing_variable[$val['id']]) ? $existing_variable[$val['id']] : 0) . '" />
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
							<input type="text" name="nilai_usulan" class="form-control input-sm nilai_awal" value="' . ('update' == $this->_method && isset($existing->nilai_usulan) ? $existing->nilai_usulan : 0) . '" data-nilai="' . (isset($description->nilai_satuan) ? $description->nilai_satuan : 0) . '" role="price-format" style="padding: 0 8px" readonly />
						</div>
					</div>
				</div>
			';
		}
		$survey										= null;
		$pertanyaan									= $this->model
		->get_where
		(
			'ref__renja_pertanyaan',
			array
			(
				'id_musrenbang_jenis_pekerjaan'		=> $selected
			)
		)
		->result_array();
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
						<input type="hidden" name="survey[' . $val['id'] . ']" class="input-answer" value="0" />
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
		$kegiatan									= (isset($description->pilihan) && 1 == $description->pilihan ? $description->nama_pekerjaan : null);
		if($ajax)
		{							
			make_json
			(
				array
				(
					'variable'						=> $output,
					'survey'						=> $survey,
					'kegiatan'						=> $kegiatan
				)
			);
		}
		else
		{
			return $output;
		}
	}
	
	*/
	
	private function _rate()
	{
		$coefficient								= $this->input->post('isu');
		$coefficient								= $this->model
		->select
		('
			koefisien
		')
		->get_where
		(
			'ref__musrenbang_isu',
			array
			(
				'id'								=> $coefficient
			),
			1
		)
		->row('koefisien');
		$average									= array_avg($this->input->post('survey'));
		if(isset($average[1]['avg']))
		{
			return $coefficient * $average[1]['avg'];
		}
		else
		{
			return 0;
		}
	}
	
	private function _filter()
	{
		$output										= null;
		if(1 != get_userdata('group_id'))
		{
			$this->model->where('ref__dprd.id', get_userdata('id_sub'));
		}
		$query										= $this->model
		->select
		('
			ref__dprd.*,
			ref__dprd_fraksi.kode AS kd_fraksi,
			ref__dprd_fraksi.nama_fraksi
		')
		->join
		(
			'ref__dprd_fraksi',
			'ref__dprd_fraksi.id = ref__dprd.id_fraksi'
		)
		->get
		(
			'ref__dprd'
		)
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('id_sub') ? ' selected' : '') . '>
						' . $val['kd_fraksi'] . '.' . $val['kode'] . '. ' . $val['nama_dewan'] . ' (' . $val['nama_fraksi'] . ')
					</option>
				';
			}
		} 
		$output										= '
			<select name="id_sub" class="form-control input-sm bordered" placeholder="Filter berdasar Dewan">
				<option value="all">Semua Dewan</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}