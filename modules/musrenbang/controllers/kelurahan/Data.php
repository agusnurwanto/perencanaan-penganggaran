<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_id_kel								= (3 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_kel'));
		$this->_id_rw								= $this->input->get('id_rw');
		$this->_primary								= $this->input->get('id');
		if(!in_array(get_userdata('group_id'), array(1, 3)))
		{
			throw_exception(403, 'Anda tidak mempunyai hak akses yang cukup untuk melihat usulan', base_url('musrenbang'));
		}
		elseif(!$this->_id_rw)
		{
			throw_exception(301, 'Silakan memilih RW terlebih dahulu', base_url('musrenbang/kelurahan/rw'));
		}
		/*elseif('update' == $this->_method && $this->_primary)
		{
			//$checker								= $this->model->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
			//if(($checker && isset($checker->nilai_usulan) && $checker->nilai_usulan) || ($checker && isset($checker->variabel_usulan) && $checker->variabel_usulan))
			$checker								= $this->model->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
			if((isset($checker->flag) == 0 ) || (isset($checker->flag) == 2 ))
			{
				throw_exception(301, null, current_page('../../verifikasi', array('id' => $this->_primary)));
			}
		}*/
		
		$this->set_theme('backend');
		$this->set_permission();
		$this->set_upload_path('kegiatan');
	}
	
	public function index()
	{
		$approval_max								= $this->model
		->query
		('
			SELECT
			ROUND(Count(ref__rw.id) * 4 * 75 / 100) AS max_approve_kelurahan
			FROM
			ref__rw
			WHERE
			ref__rw.id_kel = ' . $this->_id_kel. '
		')
		->row('max_approve_kelurahan');
		//print_r($approval_max);exit;
		//$input_usulan								= $this->model->get_where('ta__musrenbang', array('flag' => 1, 'variabel_usulan' => NULL, 'id_kel' => $this->_id_kel))->num_rows();
		//$pengusul									= 
		$usulan_rw									= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'pengusul =' => 1))->num_rows();		
		$usulan_diverifikasi						= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'flag >' => 0, 'pengusul' => 1))->num_rows();
		//echo $this->model->last_query();exit;
		$usulan_ditolak								= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'pengusul' => 1, 'flag' => 2))->num_rows();
		$usulan_kelurahan							= $this->model->get_where('ta__musrenbang', array('id_kel' => $this->_id_kel, 'pengusul' => 2, 'jenis_usulan' => 2,'flag' => 3 ))->num_rows();
		$usulan_diterima							= $usulan_diverifikasi - $usulan_ditolak;
		$maksimal_kelurahan							= 20 ;
		$selisih_kelurahan							= $maksimal_kelurahan - $usulan_kelurahan ;
		$checker									= $this->model->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
		//print_r($input_left);exit; 
		if('create' == $this->_method )
		{
			if($usulan_kelurahan >= $maksimal_kelurahan+10000)
			{
				throw_exception(403, 'Anda telah mencapai batas maksimal usulan.', go_to());
			}
		}
		elseif('update' == $this->_method)
		{
			//print_r($checker->pengusul);exit;
			if($checker->pengusul == 1 && $checker->flag == 0 && $usulan_diterima >= $approval_max)
			{
				throw_exception(403, 'Anda telah mencapai batas maksimal verifikasi. ' . $approval_max . '', go_to());
			}
			elseif($checker->pengusul == 1 && ($checker->flag == 0 || $checker->flag == 1 || $checker->flag == 2 ) && $usulan_diterima < $approval_max)
			{
				throw_exception(301, null, current_page('../../verifikasi', array('id' => $this->_primary)));
			}
			elseif($checker->flag > 3)
			{
				throw_exception(403, 'Anda tidak dapat mengubah data yang telah di verifikasi.', go_to());
			}
		}
		elseif('delete' == $this->_method)
		{
			if( $checker->pengusul == 1 )
			{
				throw_exception(403, 'Anda tidak dapat menghapus usulan RW.', go_to());
			}
		}
		if('isu' == $this->input->post('method'))
		{
			return $this->_jenis_pekerjaan();
		}
		elseif('jenis_pekerjaan' == $this->input->post('method'))
		{
			return $this->_variabel();
		}
		$header										= $this->model
		->select
		('
			ref__kecamatan.id AS id_kec,
			ref__kecamatan.kecamatan,
			ref__kelurahan.id AS id_kel,
			ref__kelurahan.nama_kelurahan,
			ref__rw.id AS id_rw,
			ref__rw.rw
		')
		->join
		(
			'ref__kelurahan',
			'ref__kelurahan.id = ref__rw.id_kel'
		)
		->join
		(
			'ref__kecamatan',
			'ref__kecamatan.id = ref__kelurahan.id_kec'
		)
		->get_where
		(
			'ref__rw',
			array
			(
				'ref__rw.id'						=> $this->_id_rw
			),
			1
		)
		->row();
		if(!$header)
		{
			if(1 == get_userdata('group_id'))
			{
				throw_exception(302, 'Silakan pilih RW terlebih dahulu', go_to('rw'));
			}
			else
			{
				throw_exception(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta', go_to('dashboard'));
			}
		}		
		$this->set_description
		('
			<div class="row">
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-4 col-xs-6 text-sm text-muted text-uppercase no-margin">
							Kecamatan
						</label>
						<label class="control-label col-md-8 col-xs-6 text-sm text-uppercase no-margin">
							' . $header->kecamatan . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-4 col-xs-6 text-sm text-muted text-uppercase no-margin">
							Kelurahan
						</label>
						<label class="control-label col-md-8 col-xs-6 text-sm text-uppercase no-margin">
							' . $header->nama_kelurahan . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-4 col-xs-6 text-sm text-muted text-uppercase no-margin">
							RW
						</label>
						<label class="control-label col-md-8 col-xs-6 text-sm text-uppercase no-margin">
							' . $header->rw . '
						</label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan RW
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $usulan_rw . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Max Diterima
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $approval_max . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan Diterima
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $usulan_diterima . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan Ditolak
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $usulan_ditolak . '
							</b>
						</label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Maks. Usulan Kelurahan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $maksimal_kelurahan . '
							</b>
						</label> 
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan Kelurahan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $usulan_kelurahan . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Tersisa
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $selisih_kelurahan . '
							</b>
						</label>
					</div>
				</div>
			</div>
		');
		if(get_userdata('group_id') == 1)
		{
			$this->add_action('option', '../make_usulan', 'Menjadi Usulan', 'btn-success ajaxLoad', 'mdi mdi-numeric-1', array('id' => 'id'));
		}
		$this->set_breadcrumb
		(
			array
			(
				'musrenbang/kelurahan'				=> 'Kelurahan',
				'../kelurahan/rw'						=> 'RW'
			)
		)
		->set_title('Musrenbang Kelurahan')
		->set_icon('mdi mdi-snowman')
		->unset_action('export, print, pdf')
		->unset_column('id, tahun, id_kec, id_kel, id_rw, pengusul, map_coordinates, variabel_usulan, prioritas_kelurahan, alasan_kelurahan, variabel_kelurahan, prioritas_kecamatan, alasan_kecamatan, variabel_kecamatan, nilai_kecamatan, prioritas_skpd, alasan_skpd, variabel_skpd, nilai_skpd, images, survey, urgensi, rate, kode_ref__prioritas_pembangunan, uraian, nama_pekerjaan,kd_unit,kode_ref__renja_jenis_usulan,id_ref__unit,nm_unit')
		//->unset_view('id, tahun, id_kec, id_kel, id_rw, map_coordinates, variabel_usulan, alasan_kelurahan, variabel_kelurahan, nilai_kelurahan, prioritas_kecamatan, alasan_kecamatan, variabel_kecamatan, nilai_kecamatan, prioritas_skpd, alasan_skpd, variabel_skpd, nilai_skpd')
		->column_order('rt, kode, nama_kegiatan, map_address, nilai_usulan, flag, nilai_kelurahan, nama_jenis_usulan, nama_isu')
		->unset_field('id, tahun, flag')
		->unset_truncate('nama_kegiatan')
		->add_action('toolbar', '../../../laporan/musrenbang/hasil_musrenbang_kelurahan', 'Cetak Laporan', 'btn-info ajax', 'fa fa-print', array('id_kel' => $this->_id_kel, 'method' => 'preview', 'tanggal_cetak' => date('Y-m-d'), 'status' => 5), true)
		->add_class
		(
			array
			(
				'map_address'					=> 'address-placeholder',
				'isu'							=> 'isu'
			)
		)
		->set_field
		(
			array
			(
				'kode'							=> 'last_insert',
				'map_coordinates'				=> 'coordinate',
				'map_address'					=> 'readonly, textarea',
				'alamat_detail'					=> 'textarea',
				'nama_kegiatan'					=> 'textarea',
				'images'						=> 'images',
				'nilai_usulan'					=> 'number_format',
				'nilai_kelurahan'				=> 'number_format',
				'rate'							=> 'percent_format'
			)
		)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> '<label class="label bg-navy">Usulan RW</label>',
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
			'id_rt',
			'ref__rt.id',
			'{ref__rt.rt}',
			array
			(
				'ref__rt.id_rw'					=> $header->id_rw
			),
			null,
			'ref__rt.rt'
		)
		->set_validation
		(
			array
			(
				'kode'							=> 'required|is_unique[ta__musrenbang.kode.id.' . $this->_primary . '.id_rt.' . $this->input->post('id_rt') . ']',
				'id_rt'							=> 'required|numeric',
				'jenis_kegiatan'				=> 'required|numeric',
				'nilai'							=> 'required',
				//'map_coordinates'				=> 'required',
				//'map_address'					=> 'required',
				//'urgensi'						=> 'required',
				'nama_kegiatan'					=> 'required',
				'isu_id'						=> '',
				'jenis_pekerjaan'				=> 'required|numeric',
				'id_prioritas_pembangunan'		=> '',
				'nilai_kelurahan'				=> 'required',
				'jenis_usulan'					=> 'required',
				'variabel_kelurahan'			=> 'required'
				//'survey'						=> 'required|callback_survey_checker'
			)
		)
		->set_default
		(
			array
			(
				'tahun'							=> get_userdata('year'),
				'id_kec'						=> $header->id_kec,
				'id_kel'						=> $header->id_kel,
				'id_rw'							=> $header->id_rw,
				'flag'							=> 3,
				'pengusul'						=> 2
			)
		)
		->where
		(
			array
			(
				'tahun'							=> get_userdata('year'),
				'id_kec'						=> $header->id_kec,
				'id_kel'						=> $header->id_kel,
				'id_rw'							=> $header->id_rw
			)
		)
		->set_output
		(
			array
			(
				'isu'							=> $this->_isu(),
				'jenis_pekerjaan'				=> $this->_jenis_pekerjaan(false),
				'variabel'						=> $this->_variabel(false),
				'view_data'						=> $this->_view_data()
			)
		)
		->set_alias
		(
			array
			(
				'kode'							=> 'no',
				'isu_id'						=> 'id isu',
				'nama_isu'						=> 'ISU/OPD',
				'jenis_pekerjaan'				=> 'Kelompok Kegiatan',
				'map_address'					=> 'alamat',
				'flag'							=> 'Status',
				'id_rt'							=> 'RT',
				'id_prioritas_pembangunan'		=> 'Prioritas Pembangunan',
				//'id_unit'						=> 'Perangkat Daerah',
				//'nm_unit'						=> 'OPD',
				'nama_jenis_usulan'				=> 'Jenis Usulan'
			)
		)
		->order_by
		(
			array
			(
				'rt'							=> 'ASC',
				'kode'							=> 'ASC'
			)
		)
		->set_template
		(
			array
			(
				'form'								=> 'form',
				'read'								=> 'read'
			)
		)
		->modal_size('modal-lg')
		->render('ta__musrenbang');
	}
	
	public function survey_checker()
	{
		$checker								= $this->input->post('survey');
		if(is_array($checker))
		{
			if(sizeof($checker) > 0)
			{
				$answered						= true;
				foreach($checker as $key => $val)
				{
					if(!in_array($val, array('0', 1)))
					{
						$answered				= false;
					}
				}
				if(!$answered)
				{
					$this->form_validation->set_message('survey_checker', 'Anda harus menjawab semua pertanyaan yang diberikan');
					return false;
				}
			}
		}
		return true;
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
			$variabel_usulan_output					= array();
			$variabel_kelurahan_output				= array();
			$variabel_usulan						= $this->model
			->get_where
			(
				'ref__musrenbang_variabel', 
				array
				(
					'id_musrenbang_jenis_pekerjaan' => $query->jenis_pekerjaan
				)
			)
			->result();
			$variabel_kelurahan						= $this->model
			->get_where
			(
				'ref__musrenbang_variabel', 
				array
				(
					'id_musrenbang_jenis_pekerjaan' => $query->jenis_pekerjaan
				)
			)
			->result();
			if($variabel_usulan)
			{
				foreach($variabel_usulan as $key => $val)
				{
					$id								= $val->id;
					$variabel_usulan_output[$id]	= array
					(
						'kode_variabel'				=> $val->kode_variabel,
						'nama_variabel'				=> $val->nama_variabel,
						'nilai'						=> (isset($query->variabel_usulan->$id) ? $query->variabel_usulan->$id : 0),
						'satuan'					=> $val->satuan
					);
				}
				$query->variabel_usulan				= $variabel_usulan_output;
			}
			if($variabel_kelurahan)
			{
				foreach($variabel_kelurahan as $key => $val)
				{
					$id								= $val->id;
					$variabel_kelurahan_output[$id]	= array
					(
						'kode_variabel'				=> $val->kode_variabel,
						'nama_variabel'				=> $val->nama_variabel,
						'nilai'						=> (isset($query->variabel_kelurahan->$id) ? $query->variabel_kelurahan->$id : 0),
						'satuan'					=> $val->satuan
					);
				}
				$query->variabel_kelurahan			= $variabel_kelurahan_output;
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
			//$selected								= $this->model->select('jenis_pekerjaan')->get_where('ta__musrenbang', array('id' => $selected), 1)->row('jenis_pekerjaan');
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
							<input type="text" name="nilai_kelurahan" class="form-control input-sm nilai_awal" value="' . ('update' == $this->_method && isset($existing->nilai_kelurahan) ? $existing->nilai_kelurahan : 0) . '" data-nilai="' . $description->nilai_satuan . '" role="price-format" style="padding: 0 8px" readonly />
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
}