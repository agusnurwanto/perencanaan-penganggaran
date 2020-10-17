<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Data extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_id_kel								= $this->input->get('id_kel');
		$this->_id_kec								= (2 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_kec'));
		$this->_primary								= $this->input->get('id');
		/*
		if('update' == $this->_method && $this->_primary)
		{
			$checker								= $this->model->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
			if(($checker && isset($checker->nilai_kelurahan) && $checker->nilai_kelurahan) || ($checker && isset($checker->variabel_kelurahan) && $checker->variabel_kelurahan))
			{
				generateMessages(301, null, current_page('../../verifikasi', array('id' => $this->_primary)));
			}
		}
		*/
		if(!in_array(get_userdata('group_id'), array(1, 2)))
		{
			throw_exception(403, 'Anda tidak mempunya hak akses yang cukup untuk melihat usulan', base_url('musrenbang/kecamatan'));
		}
		elseif(!$this->_id_kel)
		{
			throw_exception(301, 'Silakan memilih Kelurahan terlebih dahulu.', base_url('musrenbang/kecamatan'));
		}
		
		$this->set_theme('backend');
		$this->set_permission();
		$this->set_upload_path('kegiatan');
	}
	
	public function index()
	{
		if('rw' == $this->input->post('method') && $this->input->post('primary'))
		{
			return $this->_rt();
		}
		/*$max_usulan_diterima						= $this->model->query
		('
			SELECT
			ROUND(((Count(ref__rw.id) * 4 * 75 / 100) + (kelurahan.jumlah_kelurahan * 20)) * 50 / 100) AS max_approve_kecamatan
			FROM
			ref__rw
			INNER JOIN ref__kelurahan ON ref__rw.id_kel = ref__kelurahan.id
			LEFT JOIN (
				SELECT
					ref__kelurahan.id_kec,
					Count(ref__kelurahan.id) AS jumlah_kelurahan
				FROM
					ref__kelurahan
				GROUP BY
					ref__kelurahan.id_kec
				HAVING
					ref__kelurahan.id_kec = ' . $this->_id_kec . '
			) AS kelurahan ON kelurahan.id_kec = ref__kelurahan.id_kec
			WHERE
			ref__kelurahan.id_kec = ' . $this->_id_kec
		)
		->row('max_approve_kecamatan');*/
		$usulan_kelurahan_semua						= $this->model->get_where('ta__musrenbang', array('id_kec' => $this->_id_kec, 'flag >' => 0, 'pengusul <' => 3))->num_rows();
		$ditolak_kelurahan							= $this->model->get_where('ta__musrenbang', array('id_kec' => $this->_id_kec, 'flag' => 2))->num_rows();
		//$usulan_kelurahan							= $usulan_kelurahan_semua - $ditolak_kelurahan;
		$usulan_kelurahan							= $this->model->query //musrenbang
		('
			SELECT
				Count(ta__musrenbang.id) AS usulan_kelurahan
			FROM
				ta__musrenbang
			WHERE
				ta__musrenbang.id_kec = ' . $this->_id_kec . ' AND
				ta__musrenbang.flag IN(1,3,4,5,7,8) AND
				ta__musrenbang.pengusul IN(1,2) AND
				ta__musrenbang.jenis_usulan>=1

		')
		->row('usulan_kelurahan');
		$usulan_kelurahan_musrenbang							= $this->model->query //musrenbang
		('
			SELECT
				Count(ta__musrenbang.id) AS usulan_kelurahan_musrenbang
			FROM
				ta__musrenbang
			WHERE
				ta__musrenbang.id_kec = ' . $this->_id_kec . ' AND
				ta__musrenbang.flag IN(1,3,4,5,7,8) AND
				ta__musrenbang.pengusul IN(1,2) AND
				ta__musrenbang.jenis_usulan=2
				
		')
		->row('usulan_kelurahan_musrenbang');

		$max_usulan_diterima						= round($usulan_kelurahan);
		$diverifikasi_kecamatan						= $this->model->get_where('ta__musrenbang', array('id_kec' => $this->_id_kec, 'flag >' => 3, 'pengusul !=' => 3))->num_rows();
		$ditolak_kecamatan							= $this->model->get_where('ta__musrenbang', array('id_kec' => $this->_id_kec, 'flag' => 5))->num_rows();
		//$diterima_kecamatan							= $diverifikasi_kecamatan - $ditolak_kecamatan;
		$diterima_kecamatan							= $this->model->query
		('
			SELECT
				Count(ta__musrenbang.id) AS diterima_kecamatan
			FROM
				ta__musrenbang
			WHERE
				ta__musrenbang.id_kec = ' . $this->_id_kec . ' AND
				ta__musrenbang.flag IN(4,7,8) AND
				ta__musrenbang.pengusul IN(1,2) AND
				ta__musrenbang.jenis_usulan =2
				
		')
		->row('diterima_kecamatan');
		$usulan_terverifikasi						= $this->model->where_in('flag', array(3, 4))->get_where('ta__musrenbang', array('id_kec' => $this->_id_kec))->num_rows();
		$input_usulan								= $this->model->get_where('ta__musrenbang', array('pengusul' => 3,'jenis_usulan' => 2,'id_kec' => $this->_id_kec))->num_rows();
		$maksimal_kecamatan							= 30;
		$selisih									= $maksimal_kecamatan - $input_usulan;
		$checker									= $this->model->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
		if('create' == $this->_method)
		{
			if($input_usulan >= $maksimal_kecamatan)
			{
				throw_exception(403, 'Anda telah mencapai batas maksimal usulan.', go_to());
			}
		}
		elseif('update' == $this->_method)
		{
			/*if($usulan_terverifikasi >= $max_usulan_diterima)
			{
				throw_exception(403, 'Anda telah mencapai batas maksimal verifikasi.', go_to());
			}*/
			//print_r($checker->pengusul);exit;
			if($diterima_kecamatan >= $max_usulan_diterima AND $checker->jenis_usulan==2)
			{
				throw_exception(403, 'Anda telah mencapai batas maksimal verifikasi.', go_to());
			}
			elseif(($checker->pengusul == 1 || $checker->pengusul == 2) AND ($checker->flag == 1 || $checker->flag == 3 || $checker->flag == 4 || $checker->flag == 5 ))
			{
				throw_exception(301, null, current_page('../../verifikasi', array('id' => $this->_primary)));
			}
			elseif($checker->flag > 6)
			{
				throw_exception(403, 'Anda tidak dapat mengubah data yang telah di verifikasi.', go_to());
			}
		}
		elseif('delete' == $this->_method)
		{
			if( $checker->pengusul == 1 || $checker->pengusul == 2 )
			{
				throw_exception(403, 'Anda tidak dapat menghapus usulan RW atau Usulan Kelurahan.', go_to());
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
		$header											= $this->model
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
				throw_exception(302, 'Silakan pilih RW terlebih dahulu', go_to('kecamatan'));
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
							Usulan Kelurahan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>' . $usulan_kelurahan . '</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Maks. Usulan Diterima
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>' . $max_usulan_diterima . '</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Diterima Kecamatan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>' . $diterima_kecamatan . '</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Ditolak Kecamatan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>' . $ditolak_kecamatan . '</b>
						</label>
					</div>
				</div>
				<div class="col-sm-3">
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Maks. Input Usulan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>' . $maksimal_kecamatan . '</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Usulan Kecamatan
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>' . $input_usulan . '</b>
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-8 col-xs-8 text-sm text-muted text-uppercase no-margin">
							Selisih
						</label>
						<label class="control-label col-md-4  col-xs-4 text-sm text-uppercase no-margin">
							<b>' . $selisih . '</b>
						</label>
					</div>
				</div>
			</div>
		');
		if(get_userdata('group_id') == 1)
		{
			$this->add_action('option', '../menjadi_kelurahan', 'Menjadi Kelurahan', 'btn-success ajaxLoad', 'fa fa-two', array('id' => 'id'));
		}
		$this->set_breadcrumb
		(
			array
			(
				'musrenbang'						=> 'Musrenbang',
				'../musrenbang/kecamatan'			=> 'Kecamatan'
			)
		)
		->set_title('Musrenbang Kecamatan')
		->set_icon('fa fa-fort-awesome')
		->unset_action('export, pdf, print')
		->unset_column('id, tahun, kode, id_kec, id_kel, id_rw, pengusul, map_coordinates, variabel_usulan, nilai_usulan, urgensi, prioritas_kelurahan, variabel_kelurahan, prioritas_kecamatan, alasan_kecamatan, variabel_kecamatan, alasan_kelurahan, prioritas_skpd, alasan_skpd, variabel_skpd, nilai_skpd, images, survey, rate, nama_pekerjaan, uraian, kode_ref__prioritas_pembangunan,kd_unit,kode_ref__renja_jenis_usulan')
		//->unset_view('id, tahun, id_kec, id_kel, id_rw, map_coordinates, variabel_usulan, alasan_kelurahan, variabel_kelurahan, nilai_kelurahan, prioritas_kecamatan, alasan_kecamatan, variabel_kecamatan, nilai_kecamatan, prioritas_skpd, alasan_skpd, variabel_skpd, nilai_skpd')
		->unset_field('id, tahun, flag')
		->unset_truncate('nama_kegiatan')
		->add_action('toolbar', '../../../laporan/musrenbang/hasil_musrenbang_kecamatan', 'Cetak Laporan', 'btn-info ajax', 'fa fa-print', array('id_kec' => $this->_id_kec, 'method' => 'preview', 'status' => 5, 'tanggal_cetak' => date('Y-m-d')), true)
		->add_class
		(
			array
			(
				'map_address'						=> 'address-placeholder',
				'id_rw'								=> 'rw'
			)
		)
		->set_field
		(
			array
			(
				'kode'								=> 'last_insert',
				'map_coordinates'					=> 'coordinate',
				'map_address'						=> 'readonly, textarea',
				'nama_kegiatan'						=> 'textarea',
				'images'							=> 'images',
				'nilai_usulan'						=> 'number_format',
				'nilai_kelurahan'					=> 'number_format',
				'nilai_kecamatan'					=> 'number_format'
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
		/*->set_relation
		(


			'id_unit',
			'ref__unit.id',
			'{ref__unit.kd_unit}. {ref__unit.nm_unit}',
			NULL,
			NULL,
			'ref__unit.kd_unit'
		)*/
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
				'ref__rw.id_kel'					=> $header->id_kel
			),
			array
			(
				array
				(
					'ref__rw',
					'ref__rw.id = ref__rt.id_rw'
				)
			),
			'ref__rw.rw'
		)
		->set_relation
		(
			'id_rw',
			'ref__rw.id',
			'{ref__rw.rw}',
			array
			(
				'ref__rw.id_kel'					=> $header->id_kel
			),
			null,
			'ref__rw.rw'
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required|is_unique[ta__musrenbang.kode.id.' . $this->_primary . '.id_rt.' . $this->input->post('id_rt') . ']',
				'id_rt'								=> 'required|numeric',
				'jenis_kegiatan'					=> 'required|numeric',
				'nilai'								=> 'required',
				'map_coordinates'					=> 'required',
				'map_address'						=> 'required',
				'urgensi'							=> 'required',
				'jenis_pekerjaan'					=> 'required|numeric',
				'nilai_kecamatan'					=> 'required',
				'variabel_kecamatan'				=> 'required',
				'id_prioritas_pembangunan'			=> 'required'
			)
		)
		->set_default
		(
			array
			(
				'tahun'								=> get_userdata('year'),
				'id_kec'							=> $header->id_kec,
				'id_kel'							=> $header->id_kel,
				'flag'								=> 6, // usulan kecamatan
				'pengusul'							=> 3 // usulan kecamatan
			)
		)
		->where
		(
			array
			(
				'tahun'								=> get_userdata('year'),
				'id_kec'							=> $header->id_kec,
				'id_kel'							=> $header->id_kel
			)
		)
		->where_in('flag', array(1,3,4,5,6,7,8))
		->set_output
		(
			array
			(
				'isu'								=> $this->_isu(),
				'jenis_pekerjaan'					=> $this->_jenis_pekerjaan(false),
				'variabel'							=> $this->_variabel(false),
				'view_data'							=> $this->_view_data()
			)
		)
		->set_alias
		(
			array
			(
				'kode'								=> 'No',
				'nama_pekerjaan'					=> 'Kelompok Pekerjaan',
				'map_address'						=> 'Alamat',
				'flag'								=> 'Status',
				'id_rt'								=> 'RT',
				'id_rw'								=> 'RW',
				'id_prioritas_pembangunan'			=> 'Prioritas Pembangunan',
				'nama_isu'							=> 'Isu/OPD',
				'nama_jenis_usulan'					=> 'Jenis Usulan'
			)
		)
		->order_by
		(
			array
			(
				'rw'								=> 'ASC',
				'rt'								=> 'ASC'
			)
		)
		->column_order('rw, rt, kode, nama_kegiatan, nama_pekerjaan, map_address, nilai_kelurahan, flag, rate, nilai_kecamatan')
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
		$selected									= $this->model->select('id_rt')->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row('id_rt');
		$options									= '<option value="">Silakan pilih RT</option>';
		$query										= $this->model->get_where('ref__rt', array('id_rw' => $this->input->post('primary')))->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val['id'] . '"' . ($val['id'] == $selected ? ' selected' : null) . '>' . $val['rt'] . '</option>';
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
		$existing_variable							= $this->model->select('variabel_kecamatan')->get_where('ta__musrenbang', array('id' => $this->input->get('id')), 1)->row('variabel_kecamatan');
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
								<input type="number" name="variabel_kecamatan[' . $val['id'] . ']" class="form-control input-sm bordered variable_value" value="' . (isset($existing_variable[$val['id']]) ? $existing_variable[$val['id']] : 0) . '" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" />
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
							<input type="text" name="nilai_kecamatan" class="form-control input-sm nilai_awal" value="' . ('update' == $this->_method && isset($existing->nilai_kecamatan) ? $existing->nilai_kecamatan : 0) . '" data-nilai="' . $description->nilai_satuan . '" role="price-format" style="padding: 0 8px" readonly />
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