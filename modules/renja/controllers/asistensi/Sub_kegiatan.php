<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Sub_kegiatan extends Aksara
{
	private $_table									= 'ta__kegiatan_sub';
	private $_title									= null;
	private $_kelurahan								= null;
	private $_kecamatan								= null;
	function __construct()
	{
		parent::__construct();
		$this->_id									= $this->input->get('id');
		$this->_sub_unit							= $this->input->get('sub_unit');
		$this->_kegiatan							= $this->input->get('kegiatan');
		
		if(!$this->_sub_unit)
		{
			return throw_exception(301, 'Silakan memilih Sub Unit terlebih dahulu.', go_to('../sub_unit'));
		}
		if(!$this->_kegiatan)
		{
			return throw_exception(301, 'Silakan memilih Kegiatan terlebih dahulu.', go_to('../kegiatan'));
		}
		$this->_unit								= $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_sub_unit), 1)->row('id_unit');
		$this->set_theme('backend');
		$this->set_permission();
	}
	
	public function index()
	{
		//Untuk Mengunci Sub Kegiatan
		/*$locked										= $this->model
													->select('lock_kegiatan_sub')
													->get_where('ta__kegiatan_sub', array('id' => $this->_id), 1)
													->row('lock_kegiatan_sub');
		$ref_kegiatan_id							= $this->model
													->select('ref__kegiatan.id')
													->join('ref__kegiatan', 'ref__kegiatan.id = ta__kegiatan.id_kegiatan')
													->get_where('ta__kegiatan', array('ta__kegiatan.id' => $this->_kegiatan), 1)
													->row('id');
		if(('update' == $this->_method || 'delete' == $this->_method) && $locked)
		{
			return throw_exception(403, 'Anda tidak dapat menghapus atau memodifikasi Sub Kegiatan yang telah dikunci!', go_to());
		}
		// Memasukan Kecamatan dan Kelurahan dari Maps Address
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
		if(1 != get_userdata('group_id'))
		{
			$this->unset_action('print, export, pdf');
		}
		else
		{
			$this
			->add_action('option', 'ubah_skpd', 'Ubah SKPD', 'btn-warning --modal', 'mdi mdi-shuffle-variant ', array('id' => 'id'))
			->add_action('option', 'lock_kegiatan_sub', 'Lock', 'btn-success inout', 'mdi mdi-lock', array('id' => 'id'), array('key' => 'locked', 'value' => 1, 'label' => 'Unlock', 'icon' => 'mdi mdi-lock-open', 'class' => 'btn-outline-success inout'));;
		}
		$this->add_action('option', 'asistensi_ready', 'Klik untuk diasistensi', 'btn-toggle inactive --modal', 'handle', array('id' => 'id'), array('key' => 'ready_asistensi', 'value' => 1, 'label' => 'Klik untuk batal diasistensi', 'icon' => 'handle', 'class' => 'btn-toggle active --modal'));
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
		}*/
		
		$this->set_breadcrumb
		(
			array
			(
				'renja/asistensi/sub_unit'			=> 'Sub Unit',
				'../kegiatan'						=> 'Kegiatan'
			)
		);
		$maksimal_pagu						= $this->model->query
											('
												SELECT
													Sum(ta__kegiatan_sub.pagu) AS plafon
												FROM
													ta__kegiatan_sub
												INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
												INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
												INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
												WHERE
													ref__sub.id_unit = ' . $this->_unit . '
												LIMIT 1
											')
											->row('plafon');
		$anggaran							= $this->model->query
											('
												SELECT
													Sum(ta__belanja_rinci.total) AS anggaran
												FROM
													ta__belanja_rinci
												INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
												INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
												INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
												INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
												INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
												INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
												WHERE
													ref__sub.id_unit = ' . $this->_unit . '
												LIMIT 1
											')
											->row('anggaran');
		$selisih							= $maksimal_pagu - $anggaran;
		//print_r($this->_header());exit;
		$header								= $this->_header();
		$this->set_description
		('
			<div class="row">
				<div class="col-6 col-sm-2 text-muted text-sm">
					SUB UNIT
				</div>
				<div class="col-6 col-sm-6 font-weight text-sm">
					' . (isset($header->nm_sub) ?  $header->kd_urusan . '.' . sprintf('%02d', $header->kd_bidang) . '.' . sprintf('%02d', $header->kd_unit) . '.' . sprintf('%02d', $header->kd_sub) . ' ' . $header->nm_sub : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="col-6 col-sm-2 text-muted text-sm">
					PROGRAM
				</div>
				<div class="col-6 col-sm-10 font-weight text-sm">
					' . (isset($header->nm_program) ?  $header->kd_program . ' ' . $header->nm_program : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="col-6 col-sm-2 text-muted text-sm">
					KEGIATAN
				</div>
				<div class="col-6 col-sm-10 font-weight text-sm">
					' . (isset($header->kegiatan) ?  $header->kd_program . '.' . sprintf('%02d', $header->kd_keg) . ' ' . $header->kegiatan : '-') . '
				</div>
			</div>
			<div class="row border-bottom">
				<div class="col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					PLAFON UNIT
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format_indo((isset($maksimal_pagu) ? $maksimal_pagu : 0), 2) . '
					</b>
				</div>			
				<div class="col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					ANGGARAN
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format_indo((isset($anggaran) ? $anggaran : 0), 2) . '
					</b>
				</div>
				<div class="col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					SELISIH
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format_indo(((isset($selisih) ? $selisih : 0)), 2) . '
					</b>
				</div>
			</div>
		');
		$this->set_title('Sub Kegiatan' . ' ' . ucwords(strtolower($header->nm_sub)))
		->set_icon('mdi mdi-set-none')
	//	->column_order('kd_keg_sub, kegiatan, nilai_usulan,jenis_usulan, pagu, pagu_1, pengusul')
		->column_order('kd_keg_sub, kegiatan_sub, pagu, pilihan')
		->field_order('id_kegiatan_sub, kd_keg_sub, kegiatan_sub, kelompok_sasaran, waktu_pelaksanaan_mulai, waktu_pelaksanaan_sampai, pagu, pagu_1, map_address, alamat_detail, id_kel, id_sumber_dana, jenis_usulan, pilihan, id_model, id_jenis_anggaran, latar_belakang_perubahan, files,')
		->view_order('kd_urusan, nama, pagu')
		->unset_action('create, update, delete, read, print, export, pdf')
		->unset_column('id, id_reses, kd_kegiatan_sub, id_sumber_dana, id_kel, jenis_kegiatan_renja, id_kegiatan_sub, id_model, jenis_usulan, id_jenis_anggaran, nm_kegiatan_sub, map_coordinates, nilai_usulan, pagu_1, pengusul, id_keg, capaian_kegiatan, id_musrenbang, flag, map_address, alamat_detail, kelurahan, kecamatan, files, jenis_kegiatan, input_kegiatan, kelompok_sasaran, waktu_pelaksanaan_mulai, waktu_pelaksanaan_sampai, survey, variabel_usulan, variabel, tahun, created, updated, riwayat_skpd, nama_jenis_anggaran, latar_belakang_perubahan, lock_kegiatan_sub, asistensi_ready, nm_model, kd_jenis_pekerjaan, nama_pekerjaan, kode, nama_jenis_usulan, nama_kelurahan, nama_sumber_dana, pilihan, id_prioritas_pembangunan, id_prioritas_pembangunan_provinsi')
		->unset_field('id, tahun, id_keg, kd_keg_sub, kegiatan_sub, id_musrenbang, id_reses, kd_id_prog, nm_program, pengusul, flag, kelurahan, kecamatan, jenis_kegiatan, jenis_kegiatan_renja, input_kegiatan, survey, variabel_usulan, variabel, created, updated, riwayat_skpd, lock_kegiatan_sub, asistensi_ready, nilai_usulan, capaian_kegiatan')
		->unset_truncate('kegiatan_sub')
		->merge_content('<b>{kd_keg_sub}</b>', phrase('kode'))
		
		//->add_action('toolbar', 'copy_rka', 'Copy RKA', 'btn-warning --modal', 'mdi mdi-content-copy', array('sub_unit' => $this->_sub_unit))
		//->add_action('toolbar', '../../laporan/anggaran/rka_22', 'Preview RKA 2.2', 'btn-success --modal', 'mdi mdi-printer', array('unit' => $this->_unit, 'method' => 'preview', 'tanggal_cetak' => date('Y-m-d'), 'per_page' => null), true)
		//->add_action('toolbar', '../../laporan/anggaran/rka_22', 'Cetak RKA 2.2', 'btn-info --modal', 'mdi mdi-printer', array('unit' => $this->_unit, 'method' => 'print', 'tanggal_cetak' => date('Y-m-d'), 'per_page' => null), true)
		//->add_action('option', 'indikator', 'Indikator', 'btn-danger', 'mdi mdi-shield-key-outline', array('kegiatan_sub' => 'id', 'id_keg' => 'id_keg', 'per_page' => null))
		->add_action('option', '../../laporan/anggaran/rka_221', 'Cetak RKA 2.2.1', 'btn-primary', 'mdi mdi-printer', array('kegiatan_sub' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'print'), true)
		->add_action('option', '../../laporan/anggaran/lembar_asistensi', 'Lembar Asistensi', 'btn-warning', 'mdi mdi-file-document', array('kegiatan_sub' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'print'), true)
		->add_action('option', '../kak', 'Asistensi KAK', 'btn-primary btn-holo --modal', 'mdi mdi-car', array('kegiatan_sub' => 'id', 'do' => 'edit'))
		->add_action('option', 'cetak_kak', 'Cetak KAK', 'btn-success', 'mdi mdi-printer', array('kegiatan_sub' => 'id'), true)
		->add_action('option', 'pendukung', 'Pendukung', 'btn-primary btn-info --modal', 'mdi mdi-book', array('kegiatan_sub' => 'id'))
		
		->add_action('dropdown', '../../laporan/anggaran/rka_221', 'Pratinjau RKA 2.2.1', 'btn-primary', 'mdi mdi-magnify', array('kegiatan_sub' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'preview'), true)
		->add_action('dropdown', '../../laporan/anggaran/lembar_asistensi', 'Pratinjau Lembar Asistensi', 'btn-primary', 'mdi mdi-magnify', array('kegiatan_sub' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'preview'), true)
		
		->set_field
		(
			array
			(
				'files'								=> 'files',
				'kd_bidang'							=> 'sprintf',
				'kd_unit'							=> 'sprintf',
				'kd_sub'							=> 'sprintf',
				'kd_program'						=> 'sprintf',
				'kd_id_prog'						=> 'sprintf',
				'kd_keg_sub'						=> 'sprintf',
				'kegiatan_sub'						=> 'textarea',
				'pagu'								=> 'price_format',
				'pagu_1'							=> 'price_format'
			)
		)
		->set_field
		(
			'kegiatan_sub',
			'hyperlink',
			'renja/asistensi/data',
			array
			(
				'sub_kegiatan'						=> 'id'
			)
		)
		
		->set_alias
		(
			array
			(
				'kegiatan_sub'						=> 'Sub Kegiatan'
			)
		)
		->where
		(
			array
			(
				'id_keg'							=> $this->_kegiatan,
				'tahun'								=> get_userdata('year'),
				'flag'								=> 1,
				
			)
		)
		->order_by('kd_program, kd_id_prog, kd_keg, kd_keg_sub')
		
		// label informasi
		->select
		('
			ta__kegiatan_sub.id AS id_keg_label,
			ta__kegiatan_sub.id AS id_keg_tapd,
			ta__kegiatan_sub.id AS id_keg_respond
		')
		->merge_content('{id_keg_label}', 'Asistensi', 'callback_label_asistensi')
		->merge_content('{id_keg_tapd}', 'TAPD', 'callback_label_tapd')
		->merge_content('{id_keg_respond}', 'Respon', 'callback_count_respond')
		
		
		->render($this->_table);
	}
	
	public function label_asistensi($params = array())
	{
		if(!isset($params['id_keg_label'])) return false;
		$output										= null;
		$query										= $this->model
		->select
		('
			ta__asistensi_setuju.perencanaan,
			ta__asistensi_setuju.keuangan,
			ta__asistensi_setuju.setda
		')
		->join
		(
			'ta__asistensi_setuju',
			'ta__asistensi_setuju.id_keg_sub = ta__kegiatan_sub.id'
		)
		->get_where
		(
			'ta__kegiatan_sub',
			array
			(
				'ta__kegiatan_sub.id'				=> $params['id_keg_label']
			),
			1
		)
		->row();
		return '
			<a href="' . current_page('../verifikatur', array('sub_kegiatan' => $params['id_keg_label'])) . '" class="--modal" style="white-space:nowrap">
				<span class="badge badge-' . (isset($query->perencanaan) && 1 == $query->perencanaan ? 'success' : 'danger') . '">B</span>
				<span class="badge badge-' . (isset($query->keuangan) && 1 == $query->keuangan ? 'success' : 'danger') . '">K</span>
				<span class="badge badge-' . (isset($query->setda) && 1 == $query->setda ? 'success' : 'danger') . '">P</span>
			</a>
		';
	}
	
	public function label_tapd($params = array())
	{
		if(!isset($params['id_keg_tapd'])) return false;
		$output										= null;
		$query										= $this->model
		->select
		('
			ta__asistensi_setuju.ttd_1,
			ta__asistensi_setuju.ttd_2,
			ta__asistensi_setuju.ttd_3
		')
		->join
		(
			'ta__asistensi_setuju',
			'ta__asistensi_setuju.id_keg_sub = ta__kegiatan_sub.id'
		)
		->get_where
		(
			'ta__kegiatan_sub',
			array
			(
				'ta__kegiatan_sub.id'				=> $params['id_keg_tapd']
			),
			1
		)
		->row();
		
		return '
			<a href="' . current_page('../ttd', array('sub_kegiatan' => $params['id_keg_tapd'])) . '" class="--modal" style="white-space:nowrap">
				<span class="badge badge-' . (isset($query->ttd_1) && 1 == $query->ttd_1 ? 'success' : 'danger') . '">1</span>
				<span class="badge badge-' . (isset($query->ttd_2) && 1 == $query->ttd_2 ? 'success' : 'danger') . '">2</span>
				<span class="badge badge-' . (isset($query->ttd_3) && 1 == $query->ttd_3 ? 'success' : 'danger') . '">3</span>
			</a>
		';
	}
	
	public function count_respond($params = array())
	{
		if(!isset($params['id_keg_respond'])) return 0;
		$total										= $this->model
		->select
		('
			sum(ta__asistensi.id) as total
		')
		->join
		(
			'ta__kegiatan_sub',
			'ta__kegiatan_sub.id = ta__asistensi.id_keg_sub'
		)
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id = ta__kegiatan_sub.id_keg'
		)
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.id_keg_sub'			=> $params['id_keg_respond'],
				'ta__program.id_sub'				=> $this->_sub_unit
			)
		)
		->row('total');
		
		$comments									= $this->model->select('count(comments) as comments')->get_where('ta__asistensi', array('id_keg_sub' => $params['id_keg_respond'], 'comments !=' => ''))->row('comments');
		
		$tanggapan									= $this->model->select('count(tanggapan) as tanggapan')->get_where('ta__asistensi', array('id_keg_sub' => $params['id_keg_respond'], 'tanggapan !=' => ''))->row('tanggapan');
		
		return '<a href="' . base_url('master/renja/tanggapan', array('sub_kegiatan' => $params['id_keg_respond'])) . '" class="--modal render-notification" style="white-space:nowrap"><span class="badge badge-primary" data-toggle="tooltip" title="' . (isset($comments) ? $comments : 0) . ' komentar">' . ($comments > 0 ? $comments : 0) . '</span>&nbsp;<span class="badge badge-success" data-toggle="tooltip" title="' . ($tanggapan > 0 ? $tanggapan : 0) . ' tanggapan">' . ($tanggapan > 0 ? $tanggapan : 0) . '</span></a>';
	}
	
	private function _program()
	{
		$capaian_program							= 0;
		if($this->_id)
		{
			$capaian_program						= $this->model->select('capaian_program')->get_where('ta__kegiatan_sub', array('id' => $this->_id), 1)->row('capaian_program');
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
		$last_insert								= $this->model->select_max('kd_keg_sub')->get_where('ta__kegiatan_sub', array('id_keg' => $this->input->post('id')), 1)->row('kd_keg_sub');
		make_json
		(
			array
			(
				'html'								=> $output,
				'last_insert'						=> ('create' == $this->_method ? ($last_insert > 0 ? $last_insert + 1 : 1) : 'ignore')
			)
		);
	}
	
	private function _header()
	{
		$query										= $this->model->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__kegiatan.kd_keg,
				ref__sub.nm_sub,
				ref__program.nm_program,
				ref__kegiatan.nm_kegiatan AS kegiatan
			FROM
				ta__kegiatan
			INNER JOIN ref__kegiatan ON ref__kegiatan.id = ta__kegiatan.id_kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ta__kegiatan.id = ' . $this->_kegiatan . '
			LIMIT 1
		')
		->row();
		return $query;
	}
}