<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kegiatan extends Aksara
{
	private $_table									= 'ta__kegiatan';
	private $_title									= null;
	private $_kelurahan								= null;
	private $_kecamatan								= null;
	function __construct()
	{
		parent::__construct();
		$this->_id									= $this->input->get('id');
		$this->_sub_unit							= $this->input->get('sub_unit');
		if(!$this->_sub_unit)
		{
			return throw_exception(301, 'Silakan memilih Sub Unit terlebih dahulu.', go_to('../'));
		}
		$this->unit								= $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_sub_unit), 1)->row('id_unit');
		/*if(5 == get_userdata('group_id') || 11 == get_userdata('group_id'))
		{
			$id_unit								= get_userdata('sub_unit');
			$query									= $this->model->get_where('ref__sub', array('id_unit' => $id_unit))->num_rows();
			if(1 == $query)
			{
				$this->_sub_unit						= $this->model->select('id')->get_where('ref__sub', array('id_unit' => $id_unit), 1)->row('id');
			}
		}
		if(!in_array(get_userdata('group_id'), array(1, 8, 9, 12, 13)))
		{
			$this->unit							= get_userdata('sub_unit');
			if(!$this->unit)
			{
				return throw_exception(301, 'Silakan memilih SKPD terlebih dahulu.', go_to('../'));
			}
		}
		if(!in_array(get_userdata('group_id'), array(1, 5, 8, 9, 11, 12, 13)))
		{
			return throw_exception(403, 'Anda tidak mempunyai hak akses yang cukup untuk melihat usulan', base_url('dashboard'));
		}*/
		$this->set_theme('backend');
		$this->set_permission();
	}
	
	public function index()
	{
		//print_r($this->input->post());exit;
	
		$locked										= $this->model
													->select('lock_kegiatan')
													->get_where('ta__kegiatan', array('id' => $this->_id), 1)
													->row('lock_kegiatan');
		if(('update' == $this->_method || 'delete' == $this->_method) && $locked)
		{
			return throw_exception(403, 'Anda tidak dapat menghapus atau memodifikasi kegiatan yang telah dikunci!', go_to());
		}
		
		$kelkec										= $this->input->post('map_address');
		$kelkec										= explode(',', $kelkec);
		if(3 == sizeof($kelkec))
		{
			if(isset($kelkec[1]))
			{
				$this->_kelurahan					= trim($kelkec[1]);
			}
			if(isset($kelkec[2]))
			{
				$this->_kecamatan					= trim($kelkec[2]);
			}
		}
		elseif(2 == sizeof($kelkec))
		{
			if(isset($kelkec[0]))
			{
				$this->_kelurahan					= trim($kelkec[0]);
			}
			if(isset($kelkec[1]))
			{
				$this->_kecamatan					= trim($kelkec[1]);
			}
		}
		
		/*if(get_userdata('group_id') == 1 || get_userdata('group_id') == 5 || get_userdata('group_id') == 9 || get_userdata('group_id') == 12 || get_userdata('group_id') == 13)
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
		}*/
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
		if($this->_sub_unit && 'all' != $this->_sub_unit)
		{
			$this->_title							= $this->model->select('nm_sub')->get_where('ref__sub', array('id' => $this->_sub_unit), 1)->row('nm_sub');
			$this->where
			(
				array
				(
					'ref__sub.id'					=> $this->_sub_unit,
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
		$this
		//->add_action('toolbar', 'copy_rka', 'Copy RKA', 'btn-warning ajax', 'fa fa-copy', array('unit' => $this->unit))
		//->add_action('toolbar', '../../laporan/anggaran/rka_22', 'Preview RKA 2.2', 'btn-success ajax', 'mdi mdi-printer', array('unit' => $this->unit, 'method' => 'preview', 'tanggal_cetak' => date('Y-m-d'), 'per_page' => null), true)
		//->add_action('toolbar', '../../laporan/anggaran/rka_22', 'Cetak RKA 2.2', 'btn-info ajax', 'mdi mdi-printer', array('unit' => $this->unit, 'method' => 'print', 'tanggal_cetak' => date('Y-m-d'), 'per_page' => null), true)
		->add_action('option', 'indikator', 'Indikator', 'btn-danger', 'mdi mdi-shield-key-outline', array('kegiatan' => 'id', 'per_page' => null));
		//->add_action('option', '../../laporan/anggaran/rka_221', 'Cetak RKA 2.2.1', 'btn-primary', 'mdi mdi-printer', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'print'), true)
		//->add_action('option', '../../laporan/anggaran/lembar_asistensi', 'Lembar Asistensi', 'btn-warning', 'mdi mdi-file-document', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'print'), true)
		//->add_action('option', 'kak', 'KAK', 'btn-primary btn-holo ajax', 'mdi mdi-car', array('id_keg' => 'id', 'do' => 'edit'))
		//->add_action('option', 'cetak_kak', 'Cetak KAK', 'btn-success', 'mdi mdi-printer', array('id_keg' => 'id'), true)
		//->add_action('option', 'pendukung', 'Pendukung', 'btn-primary btn-info ajax', 'mdi mdi-book', array('id_keg' => 'id'))
		//->add_action('dropdown', '../../laporan/anggaran/rka_221', 'Pratinjau RKA 2.2.1', 'btn-primary', 'mdi mdi-magnify', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'preview'), true)
		//->add_action('dropdown', '../../laporan/anggaran/lembar_asistensi', 'Pratinjau Lembar Asistensi', 'btn-primary', 'mdi mdi-magnify', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'preview'), true)
		//->add_action('option', 'asistensi_ready', 'Klik untuk diasistensi', 'btn-toggle inactive --modal', 'handle', array('id' => 'id'), array('key' => 'ready_asistensi', 'value' => 1, 'label' => 'Klik untuk batal diasistensi', 'icon' => 'handle', 'class' => 'btn-toggle active --modal'));
	
		if(1 != get_userdata('group_id'))
		{
			$this->unset_action('print, export, pdf');
		}
		else
		{
			$this
			->add_action('option', 'ubah_skpd', 'Ubah SKPD', 'btn-warning ajax', 'mdi mdi-shuffle-variant ', array('id' => 'id'))
			->add_action('option', 'lock_kegiatan', 'Lock', 'btn-success inout', 'mdi mdi-lock', array('id' => 'id'), array('key' => 'locked', 'value' => 1, 'label' => 'Unlock', 'icon' => 'mdi mdi-lock-open', 'class' => 'btn-outline-success inout'));;
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
				'renja/kegiatan/sub_unit'			=> 'Sub Unit'
			)
		);
		$maksimal_pagu						= $this->model
											->get_where('ref__unit', array('ref__unit.id' => $this->unit))
											->row('pagu');
		$anggaran							= $this->model
											->select('ref__sub.id')
											->select_sum('pagu')
											->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog')
											->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
											->get_where('ta__kegiatan', array('ref__sub.id_unit' => $this->unit))
											->row();
		$selisih							= $maksimal_pagu - $anggaran->pagu;
		$this->set_description
		('
			<div class="row border-bottom">
				<div class="col-6 col-sm-2 text-muted text-uppercase text-sm">
					Plafon Unit
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($maksimal_pagu) ? number_format_indo($maksimal_pagu) : '0') . '</b>
				</div>
				<div class="col-6 col-sm-2 text-muted text-uppercase text-sm">
					Anggaran
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($anggaran->pagu) ? number_format_indo($anggaran->pagu) : '0') . '</b>
				</div>
				<div class="col-6 col-sm-2 text-muted text-uppercase text-sm">
					Selisih
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($selisih) ? number_format_indo($selisih) : '0') . '</b>
				</div>
			</div>
		');
		if('create' == $this->_method)
		{
			$this->set_default
			(
				array
				(
					'pengusul'						=> 1,
					'created'						=> date('Y-m-d H:i:s')
				)
			);
		}
		elseif('update' == $this->_method)
		{
			$this->set_default('updated', date('Y-m-d H:i:s'));
		}
		elseif('read' == $this->_method)
		{
			$this->set_output('capaian_program', $this->_capaian_program());
		}
		if($this->input->post('jenis_anggaran') > 1)
		{
			$this->set_validation('latar_belakang_perubahan', 'xss_clean');
		}
		
		$this->add_filter($this->_filter());
		if($this->input->get('id_sub_filter') && 'all' != $this->input->get('id_sub_filter'))
		{
			$this->where('ta__program.id', $this->input->get('id_sub_filter'));
		}
		
		$this->set_title('Kegiatan' . ' ' . ucwords(strtolower($this->_title)))
		
		->set_icon('fa fa-check-square-o')
		->column_order('kd_program, kegiatan, nilai_usulan,jenis_usulan, pagu, pagu_1, pengusul')
		->field_order('id_prog, id_sub, kode, nama, pagu')
		->view_order('kd_urusan, nama, pagu')
		->unset_action('print, export, pdf')
		->unset_column
		('id, nama_kelurahan, kecamatan_ref__kecamatan,	tahun, kd_id_prog, capaian_program,	nm_program,	alamat_detail, images, map_coordinates,	map_address, kelurahan, kecamatan, survey, kelompok_sasaran, variabel_usulan, waktu_pelaksanaan, variabel, id_musrenbang, id_reses,	flag, jenis_kegiatan, nm_sub, nm_model, kd_isu,	kd_jenis_pekerjaan,	nama_pekerjaan,	nilai_usulan, created, updated, riwayat_skpd, pilihan_ref__renja_jenis_pekerjaan, input_kegiatan, kode, pagu_1, pengusul, lock_kegiatan, kegiatan_judul_baru, nama_sumber_dana, kd_urusan, kd_bidang, kd_unit, kd_sub, asistensi_ready, jenis_anggaran, latar_belakang_perubahan, nama_jenis_usulan, pilihan')
		->unset_field('id, tahun, kd_id_prog, nm_program')
		->unset_view('id, tahun, id_model')
		->unset_truncate('kegiatan')
		->merge_content('{kd_program}.{kd_keg}', 'Kode')
		//->merge_content('{kegiatan} - {input_kegiatan}')
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
				'kegiatan'							=> 'textarea',
				'pagu'								=> 'price_format',
				'pagu_1'							=> 'price_format',
				'kelompok_sasaran'					=> 'textarea',
				'latar_belakang_perubahan'			=> 'textarea',
				'nilai_usulan'						=> 'number_format'
			)
		)
		->set_field
		(
			'kegiatan',
			'hyperlink',
			'renja/sub_kegiatan',
			array
			(
				'kegiatan'							=> 'id'
			)
		)
		->field_prepend
		(
			array
			(
				'pagu'    							=> 'Rp',
				'pagu_1'    						=> 'Rp'
			)
		)
		->add_class
		(
			array
			(
				'map_address'						=> 'map-address-listener',
				'id_prog'							=> 'program',
				'jenis_kegiatan_renja'				=> 'jenis-pekerjaan',
				'kegiatan'							=> 'hahahihi',
				'jenis_anggaran'					=> 'jenis-anggaran'
			)
		)
		->set_attribute('jenis_kegiatan_renja', 'data-pilihan="{ref__renja_jenis_pekerjaan.pilihan}"')
		->set_relation
		(
			'id_prog',
			'ta__program.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__unit.kd_unit}.{ref__sub.kd_sub}.{ref__program.kd_program}. {ref__program.nm_program}',
			array
			(
				'ta__program.tahun'					=> get_userdata('year'),
				'ta__program.id_sub'				=> $this->_sub_unit
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
			array
			(
					'ref__urusan.kd_urusan'			=> 'ASC',
					'ref__bidang.kd_bidang'			=> 'ASC',
					'ref__unit.kd_unit'				=> 'ASC',
					'ref__sub.kd_sub'				=> 'ASC',
					'ref__program.kd_program'		=> 'ASC',
					'ta__program.kd_id_prog'		=> 'ASC'
			),
			'ref__program.id'
		)
		->set_relation
		(
			'id_model',
			'ta__model.id',
			'{ta__model.nm_model}'
		)
		->set_relation
		(
			'jenis_kegiatan_renja',
			'ref__renja_jenis_pekerjaan.id',
			'{ref__renja_jenis_pekerjaan.kode AS kd_jenis_pekerjaan}. {ref__renja_jenis_pekerjaan.nama_pekerjaan}',
			array
			(
				'ref__renja_jenis_pekerjaan.id_sub'	=> $this->_sub_unit
			),
			null,
			'ref__renja_jenis_pekerjaan.kode ASC'
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
			'id_sumber_dana',
			'ref__sumber_dana.id',
			'{ref__sumber_dana.kode}. {ref__sumber_dana.nama_sumber_dana}',
			NULL,
			NULL,
			array
			(
				'ref__sumber_dana.kode'					=> 'ASC'
			)
		)
		->set_alias
		(
			array
			(
				'nama'								=> 'Kegiatan',
				'nm_program'						=> 'Program',
				'pagu_1'							=> 'Pagu N+1',
				'id_sumber_dana'					=> 'Sumber Dana',
				'id_kel'							=> 'Kelurahan'
			)
		)
		->set_field
		(
			array
			(
				'kd_keg'							=> 'last_insert',
				'pagu'								=> (in_array(get_userdata('group_id'), array(1, 9, 13)) ? 'number_format' : 'number_format'),
				'pagu_1'							=> (in_array(get_userdata('group_id'), array(1, 9, 13)) ? 'number_format' : 'number_format'),
				'nama'								=> 'textarea'
			)
		)
		->set_field
		(
			'pengusul',
			'dropdown',
			array
			(
				0									=> '<label class="badge badge-primary">Musrenbang</label>',
				1									=> '<label class="badge badge-success">SKPD</label>',
				2									=> '<label class="badge badge-danger">DPRD</label>',
				3									=> '<label class="badge badge-warning">Fraksi</label>'
			)
		)
		->set_field
		(
			'pilihan',
			'dropdown',
			array
			(
				0									=> '<label class="badge badge-success">RKA</label>',
				1									=> '<label class="badge badge-warning">Model</label>'
			)
		)
		->set_field
		(
			'jenis_anggaran',
			'dropdown',
			array
			(
				1									=> '<label class="label bg-red">Murni</label>',
				2									=> '<label class="label bg-green">Parsial 1</label>',
				3									=> '<label class="label bg-yellow">Parsial 2</label>',
				4									=> '<label class="label bg-blue">Parsial 3</label>',
				9									=> '<label class="label bg-black">Perubahan</label>'
			)
		)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> '<label class="badge badge-primary">Usulan</label>',
				1									=> '<label class="badge badge-success">Diterima SKPD</label>',
				2									=> '<label class="badge badge-danger">Ditolak SKPD</label>'
			)
		)
		->set_default
		(
			array
			(
				'flag'								=> 1,
				'tahun'								=> get_userdata('year'),
				'kelurahan'							=> $this->_kelurahan,
				'kecamatan'							=> $this->_kecamatan
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
				'model_variabel'					=> $this->_model_variabel(false),
				'riwayat_skpd'						=> $this->_riwayat_skpd()
			)
		)
		->set_validation
		(
			array
			(
				'id_prog'							=> 'required',
				//'kd_keg'							=> 'required|is_unique[' . $this->_table . '.kd_keg.id.' . $this->input->get('id') . '.id_prog.' . $this->input->post('id_prog') . ']',
				'kegiatan'							=> 'required',
				'kelompok_sasaran'					=> 'required',
				'waktu_pelaksanaan'					=> 'required',
				'pagu'								=> 'required',
				'pagu_1'							=> 'required',
				'alamat_detail'						=> 'required',
				'id_sumber_dana'					=> 'required',
				'id_kel'							=> 'required',
				'jenis_usulan'						=> 'required',
				'jenis_anggaran'					=> 'required',
				'kelurahan'							=> 'is_unique[' . $this->_table . '.kelurahan.id.' . $this->input->get('id') . ']',
				'kecamatan'							=> 'is_unique[' . $this->_table . '.kecamatan.id.' . $this->input->get('id') . ']'
			)
		)
		->where
		(
			array
			(
				'ta__program.id_sub'				=> $this->_sub_unit,
				'flag'								=> 1,
				
			)
		)
		->order_by('kd_program, kd_id_prog, kd_keg')
		->set_template
		(
			array
			(
				'form'								=> 'form',
				'read'								=> 'read'
			)
		)
		->modal_size('modal-xl')
		->render($this->_table);
	}
	
	public function after_update()
	{
		return throw_exception(301, phrase('data_was_successfully_updated'), current_page('../'));
	}
	
	public function after_insert()
	{
		if(1 == $this->input->post('pilihan'))
		{
			$this->_insert_rka();
		}
	}
	
	/*public function after_update()
	{
		if(1 == $this->input->post('pilihan'))
		{
			$this->_insert_rka();
		}
		else
		{
			$this->model->delete('rka__belanja', array('id_keg' => $this->input->get('id')));
		}
	}*/
	
	private function _insert_rka()
	{
		$id_keg										= ($this->input->get('id') ? $this->input->get('id') : $this->model->insert_id());
		$rekening									= $this->model->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__program.kd_id_prog,
				ta__kegiatan.kd_keg
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ta__pejabat ON ref__sub.id = ta__pejabat.id_sub
			WHERE
				ta__kegiatan.id = ' . $id_keg . '
			LIMIT 1
		')
		->row();
		$belanja_query								= $this->model->query
		('
			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ref__rek_6.id AS id_rek_6,
				ta__model_belanja.id_sumber_dana,
				ta__model_belanja_rinci.id AS id_belanja_rinci,
				ta__model_belanja_rinci_sub.id AS id_belanja_rinci_sub,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				ref__rek_4.uraian AS nm_rek_4,
				ref__rek_5.uraian AS nm_rek_5,
				ta__model_belanja_rinci.uraian AS nm_rinc,
				ta__model_belanja_rinci_sub.uraian AS nm_rinc_sub,
				ta__model_belanja_rinci.kd_belanja_rinci,
				ta__model_belanja_rinci_sub.kd_belanja_rinci_sub,
				ta__model_belanja_rinci_sub.vol_1,
				ta__model_belanja_rinci_sub.satuan_1,
				ta__model_belanja_rinci_sub.vol_2,
				ta__model_belanja_rinci_sub.satuan_2,
				ta__model_belanja_rinci_sub.vol_3,
				ta__model_belanja_rinci_sub.satuan_3,
				ta__model_belanja_rinci_sub.nilai,
				ta__model_belanja_rinci_sub.satuan_123,
				ta__kegiatan.variabel
			FROM
				ta__model_belanja_rinci_sub
			INNER JOIN ta__model_belanja_rinci ON ta__model_belanja_rinci_sub.id_belanja_rinci = ta__model_belanja_rinci.id
			INNER JOIN ta__model_belanja ON ta__model_belanja_rinci.id_belanja = ta__model_belanja.id
			INNER JOIN ta__model ON ta__model_belanja.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			INNER JOIN ref__rek_6 ON ta__model_belanja.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC,
				ref__rek_4.kd_rek_4 ASC,
				ref__rek_5.kd_rek_5 ASC,
				ta__model_belanja_rinci.kd_belanja_rinci ASC,
				ta__model_belanja_rinci_sub.kd_belanja_rinci_sub ASC
		')
		->result_array();
		if($belanja_query)
		{
			$id_bel									= null;
			$id_bel_sub								= null;
			$id_rek_6								= 0;
			$id_belanja_sub							= 0;
			$id_belanja_rinci						= 0;
			$kd_rek_1								= 0;
			$kd_rek_2								= 0;
			$kd_rek_3								= 0;
			$kd_rek_4								= 0;
			$kd_rek_5								= 0;
			$belanja								= array();
			$belanja_sub							= array();
			$belanja_rinci							= array();
			$output									= array();
			foreach($belanja_query as $key => $val)
			{
				$kd_rek_1							= $val['kd_rek_1'];
				$kd_rek_2							= $val['kd_rek_2'];
				$kd_rek_3							= $val['kd_rek_3'];
				$kd_rek_4							= $val['kd_rek_4'];
				$kd_rek_5							= $val['kd_rek_5'];
				$vol_1								= 1;
				$vol_2								= 1;
				$vol_3								= 1;
				$nilai								= 1;
				$variabel							= $val['variabel'];
				$vol_1								= calculate($val['vol_1'], $variabel);
				$vol_2								= calculate($val['vol_2'], $variabel);
				$vol_3								= calculate($val['vol_3'], $variabel);
				$nilai								= calculate($val['nilai'], $variabel);
				$volume								= $vol_1 * ($vol_2 > 0 ? $vol_2 : 1) * ($vol_3 > 0  ? $vol_3 : 1);
				$satuan_123							= $val['satuan_123'] ? $val['satuan_123'] : $val['satuan_1'] . ($val['satuan_2'] || $val['satuan_3']  ? '/' : '') . $val['satuan_2'] . ($val['satuan_3'] ? '/' : '') . $val['satuan_3'];
				$jumlah								= $vol_1 * ($vol_2 > 0  ? $vol_2 : 1) * ($vol_3 > 0  ? $vol_3 : 1) * ($nilai > 0 ? $nilai : 1);
				if($jumlah > 0)
				{
					if($id_rek_6 != $val['id_rek_6'])
					{
						$belanja						= array
						(
							'id_keg'					=> $id_keg,
							'id_rek_6'					=> $val['id_rek_6'],
							'id_sumber_dana'			=> $val['id_sumber_dana']
						);
						if(!$id_bel)
						{
							$this->model->delete('ta__belanja', array('id_keg' => $id_keg));
						}
						$this->model->insert('ta__belanja', $belanja);
						$id_bel							= $this->model->insert_id();
					}
					if($id_bel && $id_belanja_sub != $val['id_belanja_rinci'])
					{
						$belanja_sub					= array
						(
							'id_belanja'				=> $id_bel,
							'kd_belanja_sub'			=> $val['kd_belanja_rinci'],
							'uraian'					=> $val['nm_rinc']
						);
						$this->model->insert('ta__belanja_sub', $belanja_sub);
						$id_bel_sub						= $this->model->insert_id();
					}
					if($id_bel_sub && $id_belanja_rinci != $val['id_belanja_rinci_sub'])
					{
						$belanja_rinci					= array
						(
							'id_belanja_sub'			=> $id_bel_sub,
							'id_standar_harga'			=> 0,
							'kd_belanja_rinci'			=> $val['kd_belanja_rinci_sub'],
							'uraian'					=> $val['nm_rinc_sub'],
							'vol_1'						=> ($vol_1 ? $vol_1 : ''),
							'vol_2'						=> ($vol_2 ? $vol_2 : ''),
							'vol_3'						=> ($vol_3 ? $vol_3 : ''),
							'satuan_1'					=> ($val['satuan_1'] ? $val['satuan_1'] : ''),
							'satuan_2'					=> ($val['satuan_2'] ? $val['satuan_2'] : ''),
							'satuan_3'					=> ($val['satuan_3'] ? $val['satuan_3'] : ''),
							'nilai'						=> ($nilai ? $nilai : 0),
							'vol_123'					=> ($volume ? $volume : ''),
							'satuan_123'				=> ($satuan_123 ? $satuan_123 : ''),
							'total'						=> ($jumlah ? $jumlah : ''),
						);
						$this->model->insert('ta__belanja_rinci', $belanja_rinci);
					}
					$id_rek_6 = $val['id_rek_6'];
					$id_belanja_sub = $val['id_belanja_rinci'];
					$id_belanja_rinci = $val['id_belanja_rinci_sub'];
					// ke table rka__belanja
					/*$output[]						= array
					(
						'tahun'						=> get_userdata('year'),
						'id_keg'					=> $id_keg,
						'id_rek_1'					=> $val['id_rek_1'],
						'id_rek_2'					=> $val['id_rek_2'],
						'id_rek_3'					=> $val['id_rek_3'],
						'id_rek_4'					=> $val['id_rek_4'],
						'id_rek_5'					=> $val['id_rek_5'],
						'id_rek_6'					=> $val['id_rek_6'],
						'id_belanja_rinci'			=> $val['id_belanja_rinci'],
						'id_belanja_rinci_sub'		=> $val['id_belanja_rinci_sub'],
						'kd_urusan'					=> (isset($rekening->kd_urusan) ? $rekening->kd_urusan : 0),
						'kd_bidang'					=> (isset($rekening->kd_bidang) ? $rekening->kd_bidang : 0),
						'kd_unit'					=> (isset($rekening->kd_unit) ? $rekening->kd_unit : 0),
						'kd_sub'					=> (isset($rekening->kd_sub) ? $rekening->kd_sub : 0),
						'kd_prog'					=> (isset($rekening->kd_program) ? $rekening->kd_program : 0),
						'id_prog'					=> (isset($rekening->kd_id_prog) ? $rekening->kd_id_prog : 0),
						'kd_keg'					=> (isset($rekening->kd_keg) ? $rekening->kd_keg : 0),
						'kd_rek_1'					=> $val['kd_rek_1'],
						'kd_rek_2'					=> $val['kd_rek_2'],
						'kd_rek_3'					=> $val['kd_rek_3'],
						'kd_rek_4'					=> $val['kd_rek_4'],
						'kd_rek_5'					=> $val['kd_rek_5'],
						'nm_rek_1'					=> $val['nm_rek_1'],
						'nm_rek_2'					=> $val['nm_rek_2'],
						'nm_rek_3'					=> $val['nm_rek_3'],
						'nm_rek_4'					=> $val['nm_rek_4'],
						'nm_rek_5'					=> $val['nm_rek_5'],
						'kd_rinc'					=> $val['kd_belanja_rinci'],
						'nm_rinc'					=> $val['nm_rinc'],
						'kd_rinc_sub'				=> $val['kd_belanja_rinci_sub'],
						'nm_rinc_sub'				=> $val['nm_rinc_sub'],
						'nilai'						=> ($nilai ? $nilai : 0),
						'vol_1'						=> ($vol_1 ? $vol_1 : ''),
						'satuan_1'					=> ($val['satuan_1'] ? $val['satuan_1'] : ''),
						'vol_2'						=> ($vol_2 ? $vol_2 : ''),
						'satuan_2'					=> ($val['satuan_2'] ? $val['satuan_2'] : ''),
						'vol_3'						=> ($vol_3 ? $vol_3 : ''),
						'satuan_3'					=> ($val['satuan_3'] ? $val['satuan_3'] : ''),
						'vol_123'					=> ($volume ? $volume : ''),
						'satuan_123'				=> ($satuan_123 ? $satuan_123 : ''),
						'total'						=> ($jumlah ? $jumlah : ''),
					);*/
				}
				$where								= array
				(
					'id_keg'						=> $id_keg
				);
			}
			//print_r($belanja_sub);exit;
			//insert ke table rka__belanja
			/*$checker								= $this->model->get_where('rka__belanja', $where)->num_rows();
			if($checker > 0)
			{
				$this->model->delete('rka__belanja', $where);
				$this->model->insert_batch('rka__belanja', $output);
			}
			else
			{
				$this->model->insert_batch('rka__belanja', $output);
			}*/
		}

		$indikator_query								= $this->model->query
		('
			SELECT
				ta__kegiatan.id AS id_keg,
				ta__kegiatan.variabel,
				ta__model_indikator.jns_indikator,
				ta__model_indikator.kd_indikator,
				ta__model_indikator.tolak_ukur,
				ta__model_indikator.target,
				ta__model_indikator.satuan
			FROM
				ta__model_indikator
			INNER JOIN ta__model ON ta__model_indikator.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			WHERE
				ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
				ta__model_indikator.jns_indikator,
				ta__model_indikator.kd_indikator
		')
		->result_array();
		//print_r($indikator_query);exit;
		if($indikator_query)
		{
			$indikator								= array();
			$this->model->delete('ta__indikator', array('id_keg' => $id_keg));
			foreach($indikator_query as $key => $val)
			{
				$target								= calculate($val['target'], $val['variabel']);
				
				$indikator						= array
				(
					'id_keg'					=> $id_keg,
					'jns_indikator'				=> $val['jns_indikator'],
					'kd_indikator'				=> $val['kd_indikator'],
					'tolak_ukur'				=> $val['tolak_ukur'],
					'target'					=> $target,
					'satuan'					=> $val['satuan']
				);
				$this->model->insert('ta__indikator', $indikator);
			}
			//print_r($indikator);exit;
		}
	}
	
	private function _get_data($token = array())
	{
		return										$this->model
		->select
		('
			ta__kegiatan.variabel,
			ta__model.id,
			ta__model.nm_model
		')
		->join
		(
			'ta__model',
			'ta__model.id = ta__kegiatan.id_model',
			'left'
		)
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'						=> $this->_id
			),
			1
		)
		->row();
	}
	
	private function _get_desc($token = array())
	{
		$this->model->select('ta__model.desc');
		$this->model->limit(1);
		$this->model->join('ta__model', 'ta__model.id = ta__kegiatan.id_model', 'left');
		foreach($token as $key => $val)
		{
			$this->model->where('ta__kegiatan.id', $val);
		}
		$query										= $this->model->get('ta__kegiatan')->row('desc');
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
			<div class="form-group">
				<div class="alert alert-info">
					' . $desc . '
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
	
	public function validate_location($value = null)
	{
		$query										= $this->model->get_where('ta__kegiatan', array('map_address' => $this->input->post('map_address'), 'id !=' => $this->input->get('id'), 'jenis_kegiatan_renja' => $this->input->post('jenis_kegiatan_renja')), 1)->num_rows();
		if($query > 0)
		{
			$this->form_validation->set_message('validate_location', 'Data untuk alamat tersebut sudah ada');
			return false;
		}
		return true;
	}
	
	private function _program()
	{
		$capaian_program							= 0;
		if($this->_id)
		{
			$capaian_program						= $this->model->select('capaian_program')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('capaian_program');
		}
		$query										= $this->model->get_where('ta__program_capaian', array('id_prog' => $this->input->post('id')))->result_array();
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<label class="control-label" style="display:block">
						<input type="radio" name="capaian_program" value="' . $val['id'] . '"' . ($capaian_program == $val['id'] ? ' checked' : null) . ' />
						' . $val['tolak_ukur'] . '
					</label>
				';
			}
			$output									= '
				<div class="alert alert-warning checkbox-wrapper" style="margin-top:12px">
					' . $output . '
					<label class="control-label" style="display:block">
						<input type="radio" name="capaian_program" value="0"' . (!$capaian_program ? ' checked' : null) . ' />
						Tidak satupun
					</label>
				</div>
			';
		}
		$last_insert								= $this->model->select_max('kd_keg')->get_where('ta__kegiatan', array('id_prog' => $this->input->post('id')), 1)->row('kd_keg');
		make_json
		(
			array
			(
				'html'								=> $output,
				'last_insert'						=> ('create' == $this->_method ? ($last_insert > 0 ? $last_insert + 1 : 1) : 'ignore')
			)
		);
	}
	
	private function _variabel($ajax = true)
	{
		$pagu										= 0;
		$existing									= null;
		$output										= null;
		$selected									= $this->input->post('primary');
		if(!$selected)
		{
			$selected								= $this->model
			->select
			('
				jenis_kegiatan_renja
			')
			->get_where
			('
				ta__kegiatan',
				array
				(
					'id'							=> $this->_id
				),
				1
			)
			->row('jenis_kegiatan_renja');
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
				'id_renja_jenis_pekerjaan'			=> $selected
			)
		)
		->result_array();
		$description								= $this->model
		->select
		('
			id,
			nama_pekerjaan,
			deskripsi,
			pilihan,
			pagu,
			pagu_1,
			id_sumber_dana
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
				'ta__kegiatan',
				array
				(
					'id'							=> $this->_id
				),
				1
			)
			->row();
		}
		if(isset($description->pilihan) && !in_array($description->pilihan, array(1, 2)))
		{
			$output									= '
				<div class="row form-group">
					<div class="col-sm-12">
						<input type="text" name="input_kegiatan" class="form-control input_pekerjaan" value="' . (isset($existing->input_kegiatan) ? $existing->input_kegiatan : null) . '" placeholder="Silakan masukkan kegiatanx" data-pekerjaan="' . $description->nama_pekerjaan . '" />
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
				'id_renja_jenis_pekerjaan'			=> $selected
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
		if($survey)
		{
			$survey									= '
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
		}
		$kegiatan									= (isset($description->pilihan) && in_array($description->pilihan, array(1, 2)) ? $description->nama_pekerjaan . ' ' : '');
		if($ajax)
		{							
			make_json
			(
				array
				(
					'pagu'							=> (isset($existing->jenis_kegiatan_renja) && isset($description->id) && $existing->jenis_kegiatan_renja == $description->id ? (isset($existing->pagu) ? $existing->pagu : 0) : (isset($description->pagu) ? $description->pagu : 0)),
					'pagu_1'						=> (isset($existing->jenis_kegiatan_renja) && isset($description->id) && $existing->jenis_kegiatan_renja == $description->id ? (isset($existing->pagu_1) ? $existing->pagu_1 : 0) : (isset($description->pagu_1) ? $description->pagu_1 : 0)),
					'selected'						=> $selected,
					'variable'						=> $output,
					'survey'						=> $survey,
					'kegiatan'						=> $kegiatan,
					'id_sumber_dana'				=> (isset($existing->jenis_kegiatan_renja) && isset($description->id) && $existing->jenis_kegiatan_renja == $description->id ? (isset($existing->id_sumber_dana) ? $existing->id_sumber_dana : 0) : (isset($description->id_sumber_dana) ? $description->id_sumber_dana : 0))
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
				'ta__model.id = ta__kegiatan.id_model'
			)
			->join
			(
				'ta__model_isu',
				'ta__model_isu.id = ta__model.id_isu'
			)
			->get_where
			(
				'ta__kegiatan',
				array
				(
					'ta__kegiatan.id'				=> $selected
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
				ta__kegiatan.variabel,
				ta__model.nm_model,
				ta__model_isu.nama_isu
			')
			->join('ta__model', 'ta__model.id = ta__kegiatan.id_model')
			->join('ta__model_isu', 'ta__model_isu.id = ta__model.id_isu')
			->get_where('ta__kegiatan', array('ta__kegiatan.id' => $this->_id), 1)
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
				->get_where('ta__kegiatan', array('id' => $selected), 1)
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
		$description								= $this->model->select('desc')->get_where('ta__model', array('id' => $this->input->post('primary')), 1)->row('desc');
		$query										= $this->model->order_by('kd_variabel')->get_where('ta__model_variabel', array('id_model' => $this->input->post('primary')))->result_array();
		$existing_variabel							= $this->model->select('variabel')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('variabel');
		if($existing_variabel)
		{
			$existing_variabel						= json_decode($existing_variabel, true);
		}
		$output										= ($description ? '<div class="alert alert-info">' . $description . '</div><div class="form-group"><i class="text-muted text-sm"><b>Powered by e-Prodget Model</b></i></div>' : null);
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<div class="form-group row">
						<label class="col-2 col-sm-1">
							' . $val['kd_variabel'] . '
						</label>
						<label class="col-4 col-sm-5">
							' . $val['nm_variabel'] . '
						</label>
						<div class="col-3">
							<input type="text" name="variabel[' . $val['id'] . ']" class="form-control input-sm bordered" value="' . (isset($existing_variabel[$val['id']]) ? $existing_variabel[$val['id']] : 0) . '" min="0" pattern="[0-9]+([\.,][0-9]+)?" step="0.01" />
						</div>
						<label class="col-3">
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
	}
	
	private function _capaian_program()
	{
		$capaian_program							= $this->model->select('capaian_program')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('capaian_program');
		$output										= null;
		if(1 == $capaian_program)
		{
			$capaian								= $this->model->get_where('ta__program_capaian', array('id' => $capaian_program), 1)->row();
			if($capaian)
			{
				$output								.= '
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
		return $output;
	}
	
	private function _riwayat_skpd()
	{
		$query										= $this->model->select('riwayat_skpd')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('riwayat_skpd');
		$query										= json_decode($query);
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$operator							= $this->model->select('first_name')->get_where('app__users', array('user_id' => $val->id_operator), 1)->row('first_name');
				$program							= $this->model
				->select
				('
					ta__program.kd_id_prog,
					ref__program.kd_program,
					ref__program.nm_program,
					ref__sub.kd_sub,
					ref__sub.nm_sub,
					ref__unit.kd_unit,
					ref__bidang.kd_bidang,
					ref__urusan.kd_urusan
				')
				->join('ref__program', 'ref__program.id = ta__program.id_prog')
				->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
				->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
				->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
				->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
				->get_where('ta__program', array('ta__program.id' => $val->id_prog), 1)
				->row();
				$output								.= '
					<li style="margin-bottom:12px">
						<b>
							' . $program->kd_urusan . '.' . $program->kd_bidang . '.' . $program->kd_unit . '.' . $program->kd_sub . '.' . $program->kd_program . '.' . $program->kd_id_prog . ' ' . $program->nm_sub . ' - ' . $program->nm_program . '
						</b>
						<br />
						Diubah oleh ' . $operator . ' pada tanggal ' . $val->tanggal_update . '
					</li>
				';
			}
		}
		if($output)
		{
			return '
				<ul>
					' . $output . '
				</ul>
			';
		}
		return false;
	}
	
	private function _filter()
	{
		$output										= null;
		$query										= $this->model
										->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__program.kd_program, ta__program.id, ta__program.kd_id_prog, ref__program.nm_program')
										->join('ref__program', 'ref__program.id = ta__program.id_prog')
										->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
										->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
										->order_by('ref__program.kd_program ASC, ta__program.kd_id_prog ASC, ref__program.nm_program ASC')
										->get_where('ta__program',array('ta__program.id_sub' => $this->_sub_unit))
										->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('id_sub_filter') ? ' selected' : '') . '>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_program']) . ' ' . $val['nm_program'] . '</option>';
			}
		}
		$output										= '
			<select name="id_sub_filter" class="form-control input-sm bordered" placeholder="Filter berdasar Program">
				<option value="all">Pilih semua Program</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}