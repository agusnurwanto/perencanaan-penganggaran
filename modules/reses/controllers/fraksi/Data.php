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
			generateMessages(301, 'Silakan memilih fraksi terlebih dahulu...', 'reses/fraksi');
		}
		$this->set_upload_path('kegiatan');
	}
	
	public function index()
	{
		$this->add_filter($this->_filter());
		if(1 == get_userdata('group_id'))
		{
			if($this->_id_sub && 'all' != $this->_id_sub)
			{
				$sub_unit							= $this->model->select('kode, nama_fraksi')->get_where('ref__dprd_fraksi', array('id' => $this->_id_sub), 1)->row();
				$this->set_description
				('
					<div class="row">
						<label class="control-label col-md-2 col-xs-4 text-sm text-muted text-uppercase">
							Fraksi
						</label>
						<label class="control-label col-md-10  col-xs-8 text-sm text-uppercase">
							' . $sub_unit->kode . '. ' . $sub_unit->nama_fraksi . '
						</label>
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
					'ref__dprd_fraksi.id',
					'{ref__dprd_fraksi.kode}. {ref__dprd_fraksi.nama_fraksi}'
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
		$this->set_breadcrumb
		(
			array
			(
				'reses'								=> 'Reses'
			)
		)
		->set_title('Reses Fraksi')		
		->unset_column
		('
			id,
			id_musrenbang,
			id_reses,
			id_model,
			id_kel,
			jenis_anggaran,
			latar_belakang_perubahan,
			asistensi_ready,
			capaian_program
			pengusul,
			flag,
			map_coordinates,
			map_address,
			alamat_detail,
			kelurahan,
			kecamatan,
			images,
			jenis_kegiatan_renja,
			input_kegiatan,
			kelompok_sasaran,
			waktu_pelaksanaan,
			variabel_usulan,
			created,
			updated,
			survey,
			variabel,
			pilihan,
			tahun,
			kode,
			riwayat_skpd,
			capaian_program,
			pengusul,
			jenis_usulan,
			lock_kegiatan,
			pagu_1,
			id_sumber_dana,
			nama_pekerjaan,
			kegiatan_judul_baru
		')
		->unset_field
		('
			id,
			id_musrenbang,
			flag,
			tahun
		')
		->column_order('kd_urusan, nm_program, jns_kegiatan, kegiatan')
		->field_order('id_sub, id_prog')
		->merge_content('{kd_urusan}.{kd_bidang}.{kd_program}.{kd_keg}', 'Kode')
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
				'map_coordinates'					=> 'coordinate',
				'map_address'						=> 'textarea',
				'nilai'								=> 'number_format',
				'kd_bidang'							=> 'sprintf',
				'kd_unit'							=> 'sprintf',
				'kd_sub'							=> 'sprintf',
				'kd_program'						=> 'sprintf',
				'kd_id_prog'						=> 'sprintf',
				'kd_keg'							=> 'sprintf, last_insert',
				'kegiatan'							=> 'textarea'
			)
		)
		->set_field
		(
			'pilihan',
			'radio',
			array
			(
				1									=> 'Mengggunakan Model',
				0									=> 'Input Pra RKA'
			)
		)
		->set_default
		(
			array
			(
				'tahun'								=> get_userdata('year'),
				'pengusul'							=> 3,
				'flag'								=> 0,
				'id_prog'							=> $this->model->select('id_prog')->get_where('ref__musrenbang_jenis_pekerjaan', array('id' => $this->input->post('jenis_kegiatan')), 1)->row('id_prog')
			)
		)
		->set_alias
		(
			array
			(
				'nm_program'						=> 'Nama Program'
			)
		)
		->set_relation
		(
			'id_prog',
			'ref__program.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__program.kd_program} {ref__program.nm_program}',
			null,
			array
			(
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
				'map_coordinates'					=> 'required',
				'map_address'						=> 'required',
				'variabel'							=> 'required',
				'survey'							=> 'required|callback_survey_checker'
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
		->where('pengusul', 3)
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
		if(!$this->_id) return false;
		$query										= $this->model
		->select
		('
			ta__reses.*,
			ref__musrenbang_isu.nama_isu,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.deskripsi,
			ref__dprd_fraksi.nama_fraksi
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
		->join
		(
			'ref__dprd_fraksi',
			'ref__dprd_fraksi.id = ta__reses.id_reses'
		)
		->get_where
		(
			'ta__reses',
			array
			(
				'ta__reses.id'					=> $this->_id
			),
			1
		)
		->row();
		if(isset($query->variabel))
		{
			$query->variabel						= json_decode($query->variabel);
		}
		if(isset($query->jenis_pekerjaan))
		{
			$variabel_output						= array();
			$variabel								= $this->model
			->get_where
			(
				'ref__musrenbang_variabel',
				array
				(
					'id_musrenbang_jenis_pekerjaan'	=> $query->jenis_pekerjaan
				)
			)
			->result();
			if($variabel)
			{
				foreach($variabel as $key => $val)
				{
					$id								= $val->id;
					$variabel_output[$id]			= array
					(
						'kode_variabel'				=> $val->kode_variabel,
						'nama_variabel'				=> $val->nama_variabel,
						'nilai'						=> $query->variabel->$id,
						'satuan'					=> $val->satuan
					);
				}
				$query->variabel					= $variabel_output;
			}
		}
		return $query;
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
			variabel
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
		->row('variabel');
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
								<input type="number" name="variabel[' . $val['id'] . ']" class="form-control input-sm bordered variable_value" value="' . (isset($existing_variable[$val['id']]) ? $existing_variable[$val['id']] : 0) . '" />
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
							<input type="text" name="nilai" class="form-control input-sm nilai_awal" value="' . ('update' == $this->_method && isset($existing->nilai) ? round($existing->nilai) : 0) . '" data-nilai="' . $description->nilai_satuan . '" role="price-format" style="padding: 0 8px" readonly />
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
			$this->model->where('id', get_userdata('id_sub'));
		}
		$query										= $this->model
		->get
		(
			'ref__dprd_fraksi'
		)
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('id_sub') ? ' selected' : '') . '>
						' . $val['kode'] . '. ' . $val['nama_fraksi'] . '
					</option>
				';
			}
		} 
		$output										= '
			<select name="id_sub" class="form-control input-sm bordered" placeholder="Filter berdasar Fraksi">
				<option value="all">Semua Fraksi</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}