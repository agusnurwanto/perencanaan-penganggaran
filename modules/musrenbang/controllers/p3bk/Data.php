<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_id_kel								= $this->input->get('id_kel');
		$this->_id_kec								= (2 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_kec'));
		$this->_primary								= $this->input->get('id');
		if(!in_array(get_userdata('group_id'), array(1, 2)))
		{
			generateMessages(403, 'Anda tidak mempunya hak akses yang cukup untuk melihat usulan', base_url('musrenbang'));
		}
		elseif(!$this->_id_kel)
		{
			generateMessages(301, 'Silakan memilih Kelurahan terlebih dahulu.', base_url('musrenbang/p3bk/kecamatan'));
		}
		/*elseif('update' == $this->_method && $this->_primary)
		{
			$checker								= $this->model->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
			if(($checker && isset($checker->nilai_kelurahan) && $checker->nilai_kelurahan) || ($checker && isset($checker->variabel_kelurahan) && $checker->variabel_kelurahan))
			{
				generateMessages(301, null, current_page('../../verifikasi', array('id' => $this->_primary)));
			}
		}*/
		
		$this->set_theme('backend');
		$this->set_permission();
		$this->set_upload_path('kegiatan');
	}
	
	public function index()
	{		
		$header										= $this->model
		->select
		('
			ref__kecamatan.id AS id_kec,
			ref__kecamatan.kecamatan,
			ref__kelurahan.id AS id_kel,
			ref__kelurahan.nama_kelurahan
		')
		->join
		(
			'ref__kecamatan',
			'ref__kecamatan.id = ref__kelurahan.id_kec'
		)
		->get_where
		(
			'ref__kelurahan',
			array
			(
				'ref__kelurahan.id'						=> $this->_id_kel
			),
			1
		)
		->row();
		if(!$header)
		{
			if(1 == get_userdata('group_id'))
			{
				generateMessages(302, 'Silakan pilih RW terlebih dahulu', go_to('kecamatan'));
			}
			else
			{
				generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta', go_to('dashboard'));
			}
		}
		$this->set_description
		('
			<div class="row">
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Kecamatan
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
							' . $header->kecamatan . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Kelurahan
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
							' . $header->nama_kelurahan . '
						</label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Maksimal Usulan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								500.000.000
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								' . $header->nama_kelurahan . '
							</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Selisih
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>
								
							</b>
						</label>
					</div>
				</div>
			</div>
		');
		$this->set_breadcrumb
		(
			array
			(
				'musrenbang/p3bk/kecamatan'			=> 'Kelurahan'
			)
		)
		->set_title('Musrenbang P3BK')
		->set_icon('fa fa-fort-awesome')
		->unset_action('export, pdf, print')
		->unset_column('id, tahun, id_musrenbang')
		//->unset_view('id, tahun, id_kec, id_kel, id_rw, map_coordinates, variabel_usulan, alasan_kelurahan, variabel_kelurahan, nilai_kelurahan, prioritas_kecamatan, alasan_kecamatan, variabel_kecamatan, nilai_kecamatan, prioritas_skpd, alasan_skpd, variabel_skpd, nilai_skpd')
		->unset_field('id, tahun')
		//->add_action('toolbar', '../../../laporan/musrenbang/hasil_musrenbang_kecamatan', 'Cetak Laporan', 'btn-info ajax', 'fa fa-print', array('id_kec' => $this->_id_kec, 'method' => 'preview', 'status' => 5, 'tanggal_cetak' => date('Y-m-d')), true)		
		->set_default
		(
			array
			(
				'tahun'							=> get_userdata('year'),
				'id_kec'						=> $this->_id_kec,
				'id_kel'						=> $this->_id_kel
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
		->select
		('
			ref__kecamatan.kecamatan,
			ref__kelurahan.nama_kelurahan,
			ref__rw.rw,
			ref__rt.rt,
			ta__musrenbang.map_address,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ta__musrenbang.flag
		')
		->join('ta__musrenbang', 'ta__musrenbang.id = ta__musrenbang_p3bk.id_musrenbang')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->join('ref__rt', 'ref__rt.id = ta__musrenbang.id_rt')
		->join('ref__rw', 'ref__rw.id = ref__rt.id_rw')
		->join('ref__kelurahan', 'ref__kelurahan.id = ref__rw.id_kel')
		->join('ref__kecamatan', 'ref__kecamatan.id = ref__kelurahan.id_kec')
		->form_callback('save_p3bk')
		->set_output('form_data', $this->_p3bk())
		->set_template('form', 'form')
		->render('ta__musrenbang_p3bk');
	}
	
	public function save_p3bk()
	{
		//print_r($this->input->post());exit;
		if($this->input->post('id_transaksi') && is_array($this->input->post('id_transaksi')) && sizeof($this->input->post('id_transaksi')) > 0)
		{
			$total								= 0;
			foreach($this->input->post('id_transaksi') as $key => $val)
			{
				$total							+= $this->model->select('nilai_usulan')->get_where('ta__musrenbang', array('id' => $val), 1)->row('nilai_usulan');
			}
			if($total > 500000000)
			{
				generateMessages(400, array('Nilai anggaran yang dipilih lebih besar <b class="primary">' . number_format($total - 500000000) . '</b> dari batas maksimal yang ditentukan'));
			}
			else
			{
				foreach($this->input->post('id_transaksi') as $key => $val)
				{
					$prepare					= array
					(
						'id_musrenbang'			=> $val,
						'tahun'					=> get_userdata('year')
					);
					if($this->get_where('ta__musrenbang_p3bk', $prepare)->num_rows() < 1)
					{
						$this->model->insert('ta__musrenbang_p3bk', $prepare);
					}
				}
				generateMessages(301, 'Belanja berhasil disimpan');
			}
		}
	}
	
	private function _p3bk()
	{
		$data_transaksi								= $this->model
		->select('ref__rw.rw, ta__musrenbang.id, ref__rt.rt, ta__musrenbang.map_address, ref__musrenbang_jenis_pekerjaan.nama_pekerjaan, ta__musrenbang.flag, ta__musrenbang.nilai_usulan')
		->join('ref__rw', 'ref__rw.id = ta__musrenbang.id_rw')
		->join('ref__rt', 'ref__rt.id = ta__musrenbang.id_rt')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->order_by('ref__rw.rw', 'ASC')
		->order_by('ref__rt.rt', 'ASC')
		->where_in('ta__musrenbang.flag', array(2, 5, 8))
		->get_where('ta__musrenbang', array('ta__musrenbang.id_kel' => $this->input->get('id_kel')))
		->result_array();
		return array
		(
			'transaksi'								=> array
			(
				'data'								=> $data_transaksi
			)
		);
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
		if(isset($query->variabel_kecamatan))
		{
			$query->variabel_kecamatan				= json_decode($query->variabel_kecamatan);
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
						'nilai'						=> (isset($query->variabel_kecamatan->$id) ? $query->variabel_kecamatan->$id : 0),
						'satuan'					=> $val->satuan
					);
				}
				$query->variabel_kecamatan				= $variabel_output;
			}
		}
		//print_r($query);exit;
		return $query;
	}
	
	private function _rt()
	{
		$options									= '<option value="">Silakan pilih RT</option>';
		$query										= $this->model->get_where('ref__rt', array('id_rw' => $this->input->post('primary')))->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val['id'] . '">' . $val['rt'] . '</option>';
			}
		}
		make_json
		(
			array
			(
				'html'								=> $options
			)
		);
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
								<input type="number" name="variabel_kelurahan[' . $val['id'] . ']" class="form-control input-sm bordered variable_value" value="' . (isset($existing_variable[$val['id']]) ? $existing_variable[$val['id']] : 0) . '" />
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