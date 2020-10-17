<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends Aksara
{
	private $_table									= 'ta__reses';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->_id									= $this->input->get('id');
		$this->_id_sub								= $this->input->get('id_sub');
		$this->_id_unit								= $this->input->get('id_unit');
		if(!$this->_id_sub)
		{
			$id_unit								= get_userdata('sub_unit');
			$this->_id_sub							= $this->model->select('id')->get_where('ref__sub', array('id_unit' => $id_unit), 1)->row('id');
			if(!$this->_id_sub)
			{
				generateMessages(301, 'Silakan memilih SKPD terlebih dahulu.', go_to('../'));
			}
		}
		if(!in_array(get_userdata('group_id'), array(1, 5,9)))
		{
			generateMessages(403, 'Anda tidak mempunya hak akses yang cukup untuk melihat usulan', base_url('dashboard'));
		}
		$this->set_permission();
		$this->set_theme('backend');
		$this->set_upload_path('kegiatan');
	}
	
	public function index()
	{
		//print_r($this->input->post());exit;
		if(in_array(get_userdata('group_id'), array(1, 9, 12)) )
		{
			$this->add_filter($this->_filter());
			if($this->input->get('id_sub_filter') && 'all' != $this->input->get('id_sub_filter'))
			{
				$this->where('ta__program.id', $this->input->get('id_sub_filter'));
			}
		}
		else
		{
			$this->where('ref__unit.id', get_userdata('sub_unit'));
		}
		if($this->input->get('fetch_model') && $this->input->post('model'))
		{
			return $this->_fetch_model();
		}
		elseif('jenis_pekerjaan' == $this->input->post('method'))
		{
			return $this->_variabel();
		}
		elseif('program' == $this->input->post('method'))
		{
			return $this->_program();
		}
		elseif('model_isu' == $this->input->post('method'))
		{
			return $this->_model();
		}
		elseif('model_pilihan' == $this->input->post('method'))
		{
			return $this->_model_variabel();
		}
		if($this->_id_sub && 'all' != $this->_id_sub)
		{
			$this->_title							= $this->model->select('nm_sub')->get_where('ref__sub', array('id' => $this->_id_sub), 1)->row('nm_sub');
			$this->where
			(
				array
				(
					'ref__sub.id'					=> $this->_id_sub,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'tahun'							=> get_userdata('year')
				)
			)
			->join('ta__program', 'ta__program.id = ' . $this->_table . '.id_prog')
			->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
			->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
			->join('ref__program', 'ref__program.id = ta__program.id_prog')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan');
		}
		else
		{
			$this->where
			(
				array
				(
					'tahun'							=> get_userdata('year')
				)
			);
		}
		if(1 != get_userdata('group_id'))
		{
			$this->unset_action('print, export, pdf');
		}
		if(1 == $this->input->post('pilihan') && $this->input->post('id_model'))
		{
			$this->set_validation
			(
				array
				(
					'label[]'						=> 'required|callback_label_checker[' . $this->input->post('id_model') . ']',
					'value[]'						=> 'required|numeric'
				)
			);
		}		
		$this->set_breadcrumb
		(
			array
			(
				'renja/kegiatan'					=> phrase('sub_unit')
			)
		);
		$maksimal_pagu						= $this->model
											->get_where('ref__unit', array('ref__unit.id' => $this->_id_unit))
											->row('pagu_reses');
		$anggaran							= $this->model
											->select_sum('pagu')
											->join('ta__program', 'ta__program.id = ta__reses.id_prog')
											->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
											->get_where('ta__reses', array('ref__sub.id_unit' => $this->_id_unit))
											->row('pagu');
		$selisih							= $maksimal_pagu - $anggaran;
		$this->set_description
		('
			<div class="row">
				<div class="col-sm-4">
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Maksimal
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
							' . number_format($maksimal_pagu) . '
						</label>
					</div>
				</div>
				<div class="col-sm-4">
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Anggaran
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
							' . number_format($anggaran) . '
						</label>
					</div>
				</div>
				<div class="col-sm-4 border-bottom">
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Selisih
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
							' . number_format($selisih) . '
						</label>
					</div>
				</div>
			</div>
		');
		if('create' == $this->_method)
		{
			$this->set_default('created', date('Y-m-d H:i:s'));
		}
		elseif('update' == $this->_method)
		{
			$this->set_default('updated', date('Y-m-d H:i:s'));
		}
		elseif('read' == $this->_method)
		{
			$this->set_output('capaian_program', $this->_capaian_program());
		}
		$this->set_title(phrase('kegiatan') . ' ' . ucwords(strtolower($this->_title)))
		->set_icon('fa fa-check-square-o')
		//->add_action('option', 'set_model', 'Set Model', 'btn-success ajax', 'fa fa-one', array('id_keg' => 'id'))
		//->add_action('option', '../indikator', 'Indikator', 'btn-danger', 'fa fa-battery-half', array('id_keg' => 'id', 'id_prog' => 'id_prog'))
		//->add_action('option', 'rka', 'Cetak RKA', 'btn-primary', 'fa fa-two', array('id_keg' => 'id', 'id_prog' => 'id_prog'), true)
		//->add_action('option', 'leker', 'Cetak Lembar Kerja', 'btn-warning', 'fa fa-three', array('id_keg' => 'id'), true)
		//->add_action('option', 'rab', 'Cetak RAB', 'btn-info', 'fa fa-four', array('id_keg' => 'id'), true)
		//->add_action('option', 'kelog', 'Perbarui KeLog', 'btn-primary btn-holo ajax', 'fa fa-five', array('id_keg' => 'id', 'do' => 'edit'))
		//->add_action('option', 'kelog', 'Cetak KeLog', 'btn-default', 'fa fa-six', array('id_keg' => 'id'), true)
		//->add_action('option', 'simda', 'Kirim ke Simda', 'btn-danger ajaxLoad', 'fa fa-seven', array('id_keg' => 'id'))
		->column_order('kd_urusan, kegiatan_judul_baru, nilai_usulan, flag, pagu')
		->field_order('id_prog, id_sub, kode, nama, pagu')
		->view_order('kd_urusan, nama, pagu')
		->unset_action('create, delete, view')
		->unset_column
		('
			id,
			tahun,
			kd_id_prog,
			capaian_program,
			nm_program,
			alamat_detail,
			kode,
			nama_kelurahan,
			kecamatan_ref__kecamatan,
			kelurahan,
			kecamatan,
			images,
			map_coordinates,
			map_address,
			survey,
			kelompok_sasaran,
			variabel_usulan,
			waktu_pelaksanaan,
			variabel,
			id_musrenbang,
			id_reses,
			jenis_kegiatan,
			jenis_kegiatan_renja,
			pilihan,
			nm_sub,
			pengusul,
			nm_model,
			kd_isu,
			kd_jenis_pekerjaan,
			nama_pekerjaan,
			created,
			updated,
			riwayat_skpd,
			jenis_usulan,
			lock_kegiatan,
			status,
			kegiatan,
			pagu_1,
			id_sumber_dana,
			asistensi_ready,
			input_kegiatan,
			visible,
			jenis_anggaran,
			pagu,
			latar_belakang_perubahan
		')
		->unset_field('id, tahun, kd_id_prog, nm_program, jenis_kegiatan, pengusul, flag')
		->unset_view('id, tahun, id_model')
		->unset_truncate('nama')
		->merge_content('<b>{kd_urusan}.{kd_bidang}.{kd_unit}.{kd_sub}.{kd_program}.{kd_keg}</b>', phrase('kode'))
		->merge_content('{kegiatan_judul_baru}')
		->set_field
		(
			'kegiatan',
			'hyperlink',
			'anggaran/rekening',
			array
			(
				'id_keg'							=> 'id'
			),
			array
			(
				'pilihan'							=> 0
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
				'kd_bidang'							=> 'sprintf',
				'kd_unit'							=> 'sprintf',
				'kd_sub'							=> 'sprintf',
				'kd_program'						=> 'sprintf',
				'kd_id_prog'						=> 'sprintf',
				'kd_keg'							=> 'sprintf',
				'kd_keg'							=> 'last_insert',
				'kegiatan'							=> 'textarea',
				'nilai_usulan'						=> 'number_format'
			)
		)
		->add_class
		(
			array
			(
				'map_address'						=> 'address-placeholder',
				'id_prog'							=> 'program',
				'jenis_kegiatan'					=> 'jenis_pekerjaan',
				'kegiatan'							=> 'hahahihi'
			)
		)
		->set_relation
		(
			'id_prog',
			'ta__program.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__unit.kd_unit}.{ref__sub.kd_sub}.{ref__program.kd_program}. {ref__program.nm_program}',
			array
			(
				'ta__program.tahun'					=> get_userdata('year'),
				'ta__program.id_sub'				=> $this->_id_sub
			),
			array
			(
				array
				(
					'ref__program',
					'ref__program.id = ta__program.id_prog'
				),
				array
				(
					'ref__sub',
					'ref__sub.id = ta__program.id_sub'
				),
				array
				(
					'ref__unit',
					'ref__unit.id = ref__sub.id_unit'
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
			null,
			'ref__program.id'
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
		->set_relation
		(
			'id_model',
			'ta__model.id',
			'{ta__model.nm_model}'
		)
		->set_relation
		(
			'jenis_kegiatan',
			'ref__renja_jenis_pekerjaan.id',
			'{ref__renja_jenis_pekerjaan.kode AS kd_jenis_pekerjaan}. {ref__renja_jenis_pekerjaan.nama_pekerjaan}',
			array
			(
				'ref__renja_jenis_pekerjaan.id_sub'	=> $this->_id_sub
			)
		)
		->set_alias
		(
			array
			(
				'nama'								=> 'Kegiatan',
				'nm_program'						=> 'Program',
				'flag'								=> 'Status'
			)
		)
		->set_field
		(
			array
			(
				'pagu'								=> (1 == get_userdata('group_id') ? 'number_format' : 'number_format, readonly'),
				'nama'								=> 'textarea'
			)
		)
		->set_field
		(
			'pengusul',
			'dropdown',
			array
			(
				0									=> 'Siapa',
				1									=> 'SKPD',
				2									=> 'DPRD',
				3									=> 'Fraksi'
			)
		)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> '<label class="label bg-blue">Usulan</label>',
				1									=> '<label class="label bg-green">Diterima SKPD</label>',
				2									=> '<label class="label bg-maroon">Ditolak SKPD</label>'
			)
		)
		->set_default
		(
			array
			(
				'flag'								=> (1 == $this->input->post('tolak') ? 2 : 1),
				'tahun'								=> get_userdata('year'),
				'pagu'								=> (!$this->input->post('tolak') ? str_replace(',', '', $this->input->post('nilai_usulan')) : 0)
			)
		)
		->set_output
		(
			array
			(
				'data'								=> $this->_get_data(),
				'desc'								=> $this->_get_desc(),
				'options'							=> $this->_get_options(),
				'variabel'							=> $this->_variabel(false),
				'model_isu'							=> $this->_model_isu(),
				'model'								=> $this->_model(false),
				'model_variabel'					=> $this->_model_variabel(false)
			)
		)
		->set_validation
		(
			array
			(
				'id_prog'							=> 'required',
				'kd_keg'							=> 'required|is_unique[' . $this->_table . '.kd_keg.id.' . $this->input->get('id') . '.id_prog.' . $this->input->post('id_prog') . ']',
				'kegiatan_judul_baru'				=> 'required',
				'map_coordinates'					=> 'required',
				'map_address'						=> 'required'
			)
		)
		->where
		(
			array
			(
				'ta__program.id_sub'				=> $this->_id_sub,
				'pengusul'							=> 2
			)
		)
		->order_by('kd_urusan, kd_bidang, kd_unit, kd_sub, kd_program, kd_id_prog, kode')
		->modal_size('modal-lg')
		->render($this->_table);
	}
	
	private function _get_data($token = array())
	{
		return										$this->model
		->select
		('
			ta__reses.variabel,
			ta__model.id,
			ta__model.nm_model
		')
		->join
		(
			'ta__model',
			'ta__model.id = ta__reses.id_model',
			'left'
		)
		->get_where
		(
			'ta__reses',
			array
			(
				'ta__reses.id'						=> $this->_id
			),
			1
		)
		->row();
	}
	
	private function _get_desc($token = array())
	{
		$this->model->select('ta__model.desc');
		$this->model->limit(1);
		$this->model->join('ta__model', 'ta__model.id = ta__reses.id_model', 'left');
		foreach($token as $key => $val)
		{
			$this->model->where('ta__reses.id', $val);
		}
		$query										= $this->model->get('ta__reses')->row('desc');
		if(!$query)
		{
			$query									= phrase();
		}
		return $query;
	}
	
	private function _get_options($token = array())
	{
		return $this->model->select('id, kd_model, nm_model')->get('ta__model')->result_array();
	}
	
	private function _fetch_model()
	{
		$output										= null;
		$model										= $this->input->post('model');
		$variable									= $this->input->post('variable');
		$variable									= json_decode($variable, true);
		$query										= $this->model->order_by('kd_variabel')->get_where('ta__model_variabel', array('id_model' => $model))->result_array();
		$desc										= $this->model->select('desc')->get_where('ta__model', array('id' => $model), 1)->row('desc');
		$output										= '
			<div class="row form-group">
				<div class="col-sm-12">
					<div class="alert alert-info">
						' . $desc . '
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
							' . $val['kd_variabel'] . '. ' . $val['nm_variabel'] . '
						</label>
						<div class="col-sm-3">
							<input type="hidden" name="label[' . $val['id'] . ']" value="' . $val['id'] . '" />
							<input type="text" name="value[' . $val['id'] . ']" class="form-control input-sm bordered" value="' . (isset($variable[$val['id']]) ? $variable[$val['id']] : 0) . '" />
						</div>
						<label class="control-label col-sm-4">
							' . $val['satuan'] . '
						</label>
					</div>
				';
			}
		}
		make_json
		(
			array
			(
				'variable'							=> $output
			)
		);
	}
	
	public function label_checker($value = null, $model = null)
	{
		$query										= $this->model->get_where('ta__model_variabel', array('id_model' => $model,  'id' => $value));
		if(!$query)
		{
			$this->form_validation->set_message('label_chsecker', phrase('variabel_yang_anda_pilih_tidak_tersedia'));
			return false;
		}
		return true;
	}
	
	private function _program()
	{
		$existing									= array();
		if($this->_id)
		{
			$existing								= $this->model->select('capaian_program')->get_where('ta__reses', array('id' => $this->_id), 1)->row('capaian_program');
			$existing								= json_decode($existing, true);
		}
		$query										= $this->model->get_where('ta__program_capaian', array('id_prog' => $this->input->post('id')))->result_array();
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<label class="control-label" style="display:block">
						<input type="checkbox" name="capaian_program[' . $val['id'] . ']" val="1"' . (isset($existing[$val['id']]) && 'on' == $existing[$val['id']] ? ' checked' : null) . ' />
						' . $val['tolak_ukur'] . '
					</label>
				';
			}
			$output									= '
				<div class="alert alert-warning checkbox-wrapper" style="margin-top:12px">
					' . $output . '
				</div>
			';
		}
		$id_prog									= $this->model->select('id_prog')->get_where
		(
			'ta__reses',
			array
			(
				'id'								=> $this->_id
			),
			1
		)
		->row('id_prog');
		
		if($id_prog != $this->input->post('id'))
		{
			$last_insert							= $this->model->select_max('kd_keg')
			->join
			(
				'ta__program',
				'ta__program.id = ta__reses.id_prog'
			)
			->get_where
			(
				'ta__reses',
				array
				(
					'ta__program.id_prog'			=> $this->input->post('id')
				),
				1
			)
			->row('kd_keg');
			$last_insert							= $last_insert + 1;
		}
		else
		{
			$last_insert							= $this->model->select('kd_keg')->get_where
			(
				'ta__reses',
				array
				(
					'id'							=> $this->_id
				),
				1
			)
			->row('kd_keg');
		}
		
		make_json
		(
			array
			(
				'last_insert'						=> ($last_insert ? $last_insert : 1),
				'html'								=> $output
			)
		);
	}
	
	private function _variabel($ajax = true)
	{
		$output										= null;
		$selected									= $this->model->select('jenis_kegiatan')->get_where('ta__reses', array('id' => $this->_id), 1)->row('jenis_kegiatan');
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
	
	private function _model_isu()
	{
		$selected									= $this->_id;
		if($selected)
		{
			$selected								= $this->model
			->select
			('
				ta__model_isu.id
			')
			->join
			(
				'ta__model',
				'ta__model.id = ta__reses.id_model'
			)
			->join
			(
				'ta__model_isu',
				'ta__model_isu.id = ta__model.id_isu'
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
		->get('ta__model_isu')
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
			<select name="model_isu" class="form-control model_isu" data-url="' . current_page() . '">
				' . $output . '
			</select>
		';
		return $output;
	}
	
	private function _model($ajax = true)
	{
		if('read' == $this->_method)
		{
			$query									= $this->model
			->select
			('
				ta__reses.variabel,
				ta__model.nm_model,
				ta__model_isu.nama_isu
			')
			->join('ta__model', 'ta__model.id = ta__reses.id_model')
			->join('ta__model_isu', 'ta__model_isu.id = ta__model.id_isu')
			->get_where('ta__reses', array('ta__reses.id' => $this->_id), 1)
			->row();
			$var_output								= null;
			if(isset($query->variabel))
			{
				$variabel							= json_decode($query->variabel);
				if($variabel)
				{
					foreach($variabel as $key => $val)
					{
						$var						= $this->model->get_where('ta__model_variabel', array('id' => $key), 1)->row();
						$var_output					.= '
							<div class="row">
								<label class="control-label col-xs-5">
									' . $var->kd_variabel . '. 
									' . $var->nm_variabel . '
								</label>
								<label class="control-label col-xs-3">
									' . $val . '
								</label>
								<label class="control-label col-xs-4">
									' . $var->satuan . '
								</label>
							</div>
						';
					}
				}
			}
			return array
			(
				'model_isu'							=> (isset($query->nama_isu) ? $query->nama_isu : null),
				'model'								=> (isset($query->nm_model) ? $query->nm_model : null),
				'variabel'							=> $var_output
			);
		}
		else
		{
			$selected								= $this->_id;
			if($selected)
			{
				$selected							= $this->model
				->select
				('
					id_model
				')
				->get_where('ta__reses', array('id' => $selected), 1)
				->row('id_model');
			}
			$options								= '<option value="">Silakan pilih model</option>';
			$query									= $this->model
			->order_by
			('
				kd_model
			')
			->get_where
			(
				'ta__model',
				array
				(
					'id_isu'						=> $this->input->post('primary')
				)
			)
			->result_array();
			if($query)
			{
				foreach($query as $key => $val)
				{
					$options						.= '
						<option value="' . $val['id'] . '"' . ($val['id'] == $selected ? ' selected' : null) . '>
							' . sprintf('%02d', $val['kd_model']) . '. ' . $val['nm_model'] . '
						</option>
					';
				}
			}
			$output									= '
				<select name="id_model" class="form-control model_pilihan" data-url="' . current_page() . '" readonly>
					' . $options . '
				</select>
			';
			if($ajax)
			{
				make_json
				(
					array
					(
						'html'						=> $options
					)
				);
			}
			else
			{
				return $output;
			}
		}
	}
	
	private function _model_variabel($ajax = true)
	{
		$id_model									= $this->model->select('id_model')->get_where('ta__reses', array('id' => $this->_id), 1)->row('id_model');
		$description								= $this->model->select('desc')->get_where('ta__model', array('id' => $id_model), 1)->row('desc');
		$query										= $this->model->get_where('ta__model_variabel', array('id_model' => $id_model))->result_array();
		$existing_variabel							= $this->model->select('variabel')->get_where('ta__reses', array('id' => $this->_id), 1)->row('variabel');
		if($existing_variabel)
		{
			$existing_variabel						= json_decode($existing_variabel, true);
		}
		$output										= ($description ? '<div class="alert alert-info">' . $description . '</div><br />' : null);
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<div class="form-group row">
						<label class="control-label col-xs-2">
							' . $val['kd_variabel'] . '
						</label>
						<label class="control-label col-xs-4">
							' . $val['nm_variabel'] . '
						</label>
						<div class="col-xs-3">
							<input type="number" name="variabel[' . $val['id'] . ']" class="form-control input-sm bordered" value="' . (isset($existing_variabel[$val['id']]) ? $existing_variabel[$val['id']] : 0) . '" min="0"/>
						</div>
						<label class="control-label col-xs-3">
							' . $val['satuan'] . '
						</label>
					</div>
				';
			}
		}
		if($ajax)
		{
			make_json
			(
				array
				(
					'html'							=> $output
				)
			);
		}
		else
		{
			return $output;
		}
	}
	
	private function _capaian_program()
	{
		$query										= $this->model->select('capaian_program')->get_where('ta__reses', array('id' => $this->_id), 1)->row('capaian_program');
		if($query)
		{
			$query									= json_decode($query);
		}
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				if('on' == $val)
				{
					$capaian						= $this->model->get_where('ta__program_capaian', array('id' => $key), 1)->row();
					if($capaian)
					{
						$output						.= '
							<div class="row">
								<div class="col-xs-1">
									<h4>
										' . $capaian->kode . '
									</h4>
								</div>
								<div class="col-xs-11">
									<h4>
										' . $capaian->tolak_ukur . '
									</h4>
								</div>
							</div>
						';
					}
				}
			}
		}
		return $output;
	}
	
	private function _filter()
	{
		$output										= null;
		$query										= $this->model->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__program.kd_program, ta__program.id, ta__program.kd_id_prog, ref__program.nm_program')->join('ref__program', 'ref__program.id = ta__program.id_prog')->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')->get_where('ta__program',array('ta__program.id_sub' => $this->_id_sub))->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('id_sub_filter') ? ' selected' : '') . '>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_program']) . '.' . sprintf('%02d', $val['kd_id_prog']) . ' ' . $val['nm_program'] . '</option>';
			}
		}
		$output										= '
			<select name="id_sub_filter" class="form-control input-sm bordered" placeholder="Filter berdasar Program">
				<option value="all">Berdasarkan semua Program</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}