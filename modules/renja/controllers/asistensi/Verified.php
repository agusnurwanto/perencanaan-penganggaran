<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Verified extends Aksara
{
	private $_table									= 'ta__kegiatan';
	private $_title									= null;
	private $_kelurahan								= null;
	private $_kecamatan								= null;
	function __construct()
	{
		parent::__construct();
		$this->_id									= $this->input->get('id');
		$this->_id_sub								= $this->input->get('id_sub');
		if(5 == get_userdata('group_id'))
		{
			$id_unit								= get_userdata('sub_unit');
			$query									= $this->model->get_where('ref__sub', array('id_unit' => $id_unit))->num_rows();
			if(1 == $query)
			{
				$this->_id_sub						= $this->model->select('id')->get_where('ref__sub', array('id_unit' => $id_unit), 1)->row('id');
			}
		}
		$this->_id_unit								= $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_id_sub), 1)->row('id_unit');
		if(!in_array(get_userdata('group_id'), array(1, 8, 9, 12, 13)))
		{
			$this->_id_unit							= get_userdata('sub_unit');
			if(!$this->_id_unit)
			{
				generateMessages(301, 'Silakan memilih SKPD terlebih dahulu.', go_to('kegiatan'));
			}
		}
		if(!$this->_id_sub)
		{
			generateMessages(301, 'Silakan memilih SKPD terlebih dahulu.', go_to('kegiatan'));
		}
		if(!in_array(get_userdata('group_id'), array(1, 5, 8, 9, 12, 13)))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk melihat usulan', base_url('dashboard'));
		}
		$this->set_theme('backend');
		$this->set_permission();
	}
	
	public function index()
	{
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
		if(get_userdata('group_id') == 1 || get_userdata('group_id') == 13)
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
		else
		{
			//$this->add_action('option', '../ubah_skpd', 'Ubah SKPD', 'btn-warning ajax', 'fa fa-exchange', array('id' => 'id'));
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
				'../renja'							=> 'Renja',
				'../renja/asistensi'				=> phrase('sub_unit')
			)
		);
		$maksimal_pagu						= $this->model
											->get_where('ref__unit', array('ref__unit.id' => $this->_id_unit))
											->row('pagu');
		$anggaran							= $this->model
											->select('ref__sub.id')
											->select_sum('pagu')
											->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog')
											->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
											->get_where('ta__kegiatan', array('ref__sub.id_unit' => $this->_id_unit))
											->row();
		//$this->_id_sub						= $anggaran->id;
		$selisih							= $maksimal_pagu - $anggaran->pagu;
		$this->set_description
		('
			<div class="row">
				<div class="col-sm-4">
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Plafon
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
							' . number_format($anggaran->pagu) . '
						</label>
					</div>
				</div>
				<div class="col-sm-4">
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
		//->add_action('toolbar', '../../../laporan/anggaran/rka_22', 'Preview RKA 2.2', 'btn-success ajax', 'fa fa-print', array('unit' => $this->input->get('id_unit'), 'method' => 'preview', 'tanggal_cetak' => date('Y-m-d'), 'per_page' => null), true)
		//->add_action('toolbar', '../../../laporan/anggaran/rka_22', 'Cetak RKA 2.2', 'btn-info ajax', 'fa fa-print', array('unit' => $this->input->get('id_unit'), 'method' => 'print', 'tanggal_cetak' => date('Y-m-d'), 'per_page' => null), true)
		//->add_action('option', '../indikator', 'Indikator', 'btn-danger', 'fa fa-battery-half', array('id_keg' => 'id', 'id_prog' => 'id_prog', 'per_page' => null))
		//->add_action('option', 'data', 'Preview RKA 2.2.1', 'btn-primary', 'fa fa-print', array('id_keg' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'preview'), true)
		//->add_action('option', 'leker', 'Cetak Lembar Kerja', 'btn-warning', 'fa fa-three', array('id_keg' => 'id'), true)
		//->add_action('option', 'rab', 'Cetak RAB', 'btn-info', 'fa fa-four', array('id_keg' => 'id'), true)
		//->add_action('option', '../kak', 'KAK', 'btn-primary btn-holo ajax', 'fa fa-car', array('id_keg' => 'id', 'do' => 'edit'))
		//->add_action('option', 'kelog', 'Cetak KeLog', 'btn-default', 'fa fa-six', array('id_keg' => 'id'), true)
		//->add_action('option', 'simda', 'Kirim ke Simda', 'btn-danger ajaxLoad', 'fa fa-seven', array('id_keg' => 'id'))
		->add_action('toolbar', '../', 'Menunggu Verifikasi', 'btn-info ajaxLoad', 'fa fa-star-half-full')
		->column_order('kd_urusan, kegiatan, nilai_usulan, pagu, pengusul')
		->field_order('id_prog, id_sub, kode, nama, pagu')
		->view_order('kd_urusan, nama, pagu')
		->unset_action('create, update, delete, print, export, pdf')
		->unset_column
		('
			id,
			tahun,
			kd_id_prog,
			capaian_program,
			nm_program,
			alamat_detail,
			images,
			map_coordinates,
			map_address,
			kelurahan,
			kecamatan,
			survey,
			kelompok_sasaran,
			variabel_usulan,
			waktu_pelaksanaan,
			variabel,
			id_musrenbang,
			id_reses,
			flag,
			jenis_kegiatan,
			pilihan,
			nm_sub,
			nm_model,
			kd_isu,
			kd_jenis_pekerjaan,
			nama_pekerjaan,
			nilai_usulan,
			created,
			updated,
			riwayat_skpd,
			pilihan_ref__renja_jenis_pekerjaan,
			input_kegiatan,
			nama_jenis_usulan,
			kode,
			lock_kegiatan,
			kegiatan_judul_baru,
			pagu_1,
			id_sumber_dana,
			asistensi_ready,
			pengusul,
			jenis_kegiatan_renja,
			id_model,
			jenis_usulan
		')
		->unset_field('id, tahun, kd_id_prog, nm_program')
		->unset_view('id, tahun, id_model')
		->unset_truncate('kegiatan')
		->merge_content('<b>{kd_urusan}.{kd_bidang}.{kd_unit}.{kd_sub}.{kd_program}.{kd_keg}</b>', phrase('kode'))
		//->merge_content('{kegiatan} - {input_kegiatan}')
		->set_field
		(
			'kegiatan',
			'hyperlink',
			'renja/asistensi/data',
			array
			(
				'id_keg'							=> 'id'
			),
			true
		)
		->set_field
		(
			array
			(
				'kd_bidang'							=> 'sprintf',
				'kd_unit'							=> 'sprintf',
				'kd_sub'							=> 'sprintf',
				'kd_program'						=> 'sprintf',
				'kd_id_prog'						=> 'sprintf',
				'kd_keg'							=> 'sprintf',
				'kegiatan'							=> 'textarea, readonly',
				'nilai_usulan'						=> 'number_format'
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
		->set_alias
		(
			array
			(
				'nama'								=> 'Kegiatan',
				'nm_program'						=> 'Program'
			)
		)
		->set_validation
		(
			array
			(
				'id_prog'							=> 'required',
				'kd_keg'							=> 'required|is_unique[' . $this->_table . '.kd_keg.id.' . $this->input->get('id') . '.id_prog.' . $this->input->post('id_prog') . ']',
				'kegiatan'							=> 'required',
				'map_coordinates'					=> 'required|callback_validate_location',
				'map_address'						=> (in_array($this->_id_sub, array(19, 20)) ? 'required' : null),
				'kelurahan'							=> 'is_unique[' . $this->_table . '.kelurahan.id.' . $this->input->get('id') . ']',
				'kecamatan'							=> 'is_unique[' . $this->_table . '.kecamatan.id.' . $this->input->get('id') . ']'
			)
		)
		->select('ta__asistensi_setuju.perencanaan, ta__asistensi_setuju.keuangan, ta__asistensi_setuju.setda, ta__asistensi.id_keg')
		->join('ta__asistensi', 'ta__asistensi.id_keg = ta__kegiatan.id', 'left')
		->join('ta__asistensi_setuju', 'ta__asistensi_setuju.id_keg = ta__kegiatan.id', 'left')
		->merge_content('{perencanaan} {keuangan} {setda}', 'Asistensi', 'callback_label_asistensi')
		->merge_content('{id_keg}', 'Respon', 'callback_count_respond')
		->where
		(
			array
			(
				'ta__kegiatan.asistensi_ready'		=> 1,
				'ta__program.id_sub'				=> $this->_id_sub,
				'ta__kegiatan.flag'					=> 1,
				'ta__asistensi_setuju.perencanaan'	=> 1,
				'ta__asistensi_setuju.keuangan'		=> 1,
				'ta__asistensi_setuju.setda'		=> 1
			)
		)
		->order_by('kd_urusan, kd_bidang, kd_unit, kd_sub, kd_program, kd_id_prog, kd_keg')
		->set_template
		(
			array
			(
				'form'								=> 'form',
				'read'								=> 'read'
			)
		)
		//->autoload_form(false)
		->render($this->_table);
	}
	
	public function label_asistensi($params = array())
	{
		$output										= null;
		if(is_array($params) && sizeof($params) > 0)
		{
			foreach($params as $key => $val)
			{
				if('perencanaan' == $key)
				{
					$label							= 'B';
				}
				elseif('keuangan' == $key)
				{
					$label							= 'K';
				}
				else
				{
					$label							= 'P';
				}
				$output								.= '<span class="badge bg-' . (1 == $val ? 'green' : 'red') . '">' . $label . '</span>&nbsp;';
			}
		}
		return $output;
	}
	
	public function count_respond($params = array())
	{
		if(!isset($params['id_keg'])) return 0;
		$query										= $this->model->select('count(comments) as comments, count(tanggapan) as tanggapan')->get_where('ta__asistensi', array('id_keg' => $params['id_keg']))->row();
		
		return '<span class="badge bg-blue" data-toggle="tooltip" title="' . (isset($query->comments) ? $query->comments : 0) . ' komentar">' . (isset($query->comments) ? $query->comments : 0) . '</span>&nbsp;<span class="badge bg-blue" data-toggle="tooltip" title="' . (isset($query->tanggapan) ? $query->tanggapan : 0) . ' tanggapan">' . (isset($query->tanggapan) ? $query->tanggapan : 0) . '</span>';
	}
	
	public function after_insert()
	{
		if(1 == $this->input->post('pilihan'))
		{
			$this->_insert_rka();
		}
	}
	
	public function after_update()
	{
		if(1 == $this->input->post('pilihan'))
		{
			$this->_insert_rka();
		}
		else
		{
			$this->model->delete('rka__belanja', array('id_keg' => $this->input->get('id')));
		}
	}
	
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
				ta__model_belanja_rinc.id AS id_belanja_rinc,
				ta__model_belanja_rinc_sub.id AS id_belanja_rinc_sub,
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
				ta__model_belanja_rinc.uraian AS nm_rinc,
				ta__model_belanja_rinc_sub.uraian AS nm_rinc_sub,
				ta__model_belanja_rinc.kd_belanja_rinc,
				ta__model_belanja_rinc_sub.kd_belanja_rinc_sub,
				ta__model_belanja_rinc_sub.vol_1,
				ta__model_belanja_rinc_sub.satuan_1,
				ta__model_belanja_rinc_sub.vol_2,
				ta__model_belanja_rinc_sub.satuan_2,
				ta__model_belanja_rinc_sub.vol_3,
				ta__model_belanja_rinc_sub.satuan_3,
				ta__model_belanja_rinc_sub.nilai,
				ta__model_belanja_rinc_sub.satuan_123,
				ta__kegiatan.variabel
			FROM
				ta__model_belanja_rinc_sub
			INNER JOIN ta__model_belanja_rinc ON ta__model_belanja_rinc_sub.id_belanja_rinc = ta__model_belanja_rinc.id
			INNER JOIN ta__model_belanja ON ta__model_belanja_rinc.id_belanja = ta__model_belanja.id
			INNER JOIN ta__model ON ta__model_belanja.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			INNER JOIN ref__rek_5 ON ta__model_belanja.id_rek_5 = ref__rek_5.id
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
				ta__model_belanja_rinc.kd_belanja_rinc ASC,
				ta__model_belanja_rinc_sub.kd_belanja_rinc_sub ASC
		')
		->result_array();
		if($belanja_query)
		{
			$kd_rek_1								= 0;
			$kd_rek_2								= 0;
			$kd_rek_3								= 0;
			$kd_rek_4								= 0;
			$kd_rek_5								= 0;
			$id_belanja_rinc						= 0;
			$id_belanja_rinc_sub					= 0;
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
					$output[]						= array
					(
						'tahun'						=> get_userdata('year'),
						'id_keg'					=> $id_keg,
						'id_rek_1'					=> $val['id_rek_1'],
						'id_rek_2'					=> $val['id_rek_2'],
						'id_rek_3'					=> $val['id_rek_3'],
						'id_rek_4'					=> $val['id_rek_4'],
						'id_rek_5'					=> $val['id_rek_5'],
						'id_belanja_rinc'			=> $val['id_belanja_rinc'],
						'id_belanja_rinc_sub'		=> $val['id_belanja_rinc_sub'],
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
						'kd_rinc'					=> $val['kd_belanja_rinc'],
						'nm_rinc'					=> $val['nm_rinc'],
						'kd_rinc_sub'				=> $val['kd_belanja_rinc_sub'],
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
					);
				}
				$where								= array
				(
					'id_keg'						=> $id_keg
				);
			}
			$checker								= $this->model->get_where('rka__belanja', $where)->num_rows();
			if($checker > 0)
			{
				$this->model->delete('rka__belanja', $where);
				$this->model->insert_batch('rka__belanja', $output);
			}
			else
			{
				$this->model->insert_batch('rka__belanja', $output);
			}
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
		$query										= $this->model->get_where('ta__kegiatan', array('map_address' => $this->input->post('map_address'), 'id !=' => $this->input->get('id')), 1)->num_rows();
		if($query > 0 && 19 == $this->_id_sub)
		{
			$this->form_validation->set_message('validate_location', 'Data untuk alamat tersebut sudah ada');
			return false;
		}
		return true;
	}
	
	private function _program()
	{
		$existing									= array();
		if($this->_id)
		{
			$existing								= $this->model->select('capaian_program')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('capaian_program');
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
		$last_insert								= $this->model->select_max('kd_keg')->get_where('ta__kegiatan', array('id_prog' => $this->input->post('id')), 1)->row('kd_keg');
		make_json
		(
			array
			(
				'html'								=> $output,
				'last_insert'						=> ($last_insert > 0 ? $last_insert + 1 : 1)
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
			nama_pekerjaan,
			deskripsi,
			pilihan,
			pagu
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
						<input type="text" name="input_kegiatan" class="form-control input_pekerjaan" value="' . (isset($existing->input_kegiatan) ? $existing->input_kegiatan : null) . '" placeholder="Silakan masukkan kegiatan" data-pekerjaan="' . $description->nama_pekerjaan . '" />
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
					'pagu'							=> (isset($description->pagu) ? $description->pagu : 0),
					'selected'						=> $selected,
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
		$query										= $this->model->get_where('ta__model_variabel', array('id_model' => $this->input->post('primary')))->result_array();
		$existing_variabel							= $this->model->select('variabel')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('variabel');
		if($existing_variabel)
		{
			$existing_variabel						= json_decode($existing_variabel, true);
		}
		$output										= ($description ? '<div class="alert alert-info">' . $description . '</div><div class="form-group"><i class="text-muted text-sm"><b>Powered by e-Pordget Model</b></i></div>' : null);
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
	}
	
	private function _capaian_program()
	{
		$query										= $this->model->select('capaian_program')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('capaian_program');
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
	
	private function _riwayat_skpd()
	{
		$query										= $this->model->select('riwayat_skpd')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('riwayat_skpd');
		$query										= json_decode($query);
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$operator							= $this->model->select('full_name')->get_where('app__users', array('user_id' => $val->id_operator), 1)->row('full_name');
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