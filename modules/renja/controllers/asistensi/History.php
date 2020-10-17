<?php defined('BASEPATH') OR exit('No direct script access allowed');
class History extends Aksara
{
	private $_table									= 'ta__kegiatan';
	function __construct()
	{
		parent::__construct();
		$this->_primary								= (12 == get_userdata('group_id') ? get_userdata('user_id') : $this->input->get('user_id'));
		if(!in_array(get_userdata('group_id'), array(1, 12)))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk melihat usulan', base_url('dashboard'));
		}
		if(!$this->_primary)
		{
			generateMessages(301, 'Silakan memilih TIM terlebih dahulu.', go_to('../tim'));
		}
		$this->set_theme('backend');
		$this->set_permission();
	}
	
	public function index()
	{
		if(in_array(get_userdata('group_id'), array(1, 12)))
		{
			$this->add_filter($this->_filter());
			if($this->input->get('id_sub_filter') && 'all' != $this->input->get('id_sub_filter'))
			{
				$this->where('ref__sub.id', $this->input->get('id_sub_filter'));
			}
		}
		if(1 != get_userdata('group_id'))
		{
			$this->unset_action('print, export, pdf');
		}
		$this->set_breadcrumb
		(
			array
			(
				'../renja'							=> 'Renja',
				'../renja/asistensi/tim'			=> phrase('tim')
			)
		);
		$nama_tim						= $this->model
											->get_where('app__users', array('app__users.user_id' => $this->_primary), 1)
											->row('first_name');
		$this->set_description
		('
			<div class="row">
				<div class="col-sm-4">
					<div class="row">
						<label class="control-label col-md-4 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Tim Asistensi
						</label>
						<label class="control-label col-md-8  col-xs-8 text-sm text-uppercase no-margin">
							' . $nama_tim . '
						</label>
					</div>
				</div>
			</div>
		');
		$this->set_title(phrase('History'))
		->set_icon('fa fa-check-square-o')
		//->add_action('toolbar', 'verified', 'Asistensi Terverifikasi', 'btn-info ajaxLoad', 'fa fa-certificate')
		->add_action('option', '../../../laporan/anggaran/rka_221', 'Cetak RKA 2.2.1', 'btn-danger', 'fa fa-print', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'print'), true)
		->add_action('option', '../../../laporan/anggaran/lembar_asistensi', 'Lembar Asistensi', 'btn-warning', 'fa fa-bookmark', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'print'), true)
		->add_action('option', '../kegiatan/kak', 'KAK', 'btn-primary btn-holo ajax', 'fa fa-car', array('id_keg' => 'id', 'do' => 'edit'))
		->add_action('option', '../kegiatan/pendukung', 'Pendukung', 'btn-primary btn-info ajax', 'fa fa-book', array('id_keg' => 'id'))
		->add_action('dropdown', '../../../laporan/anggaran/rka_221', 'Pratinjau RKA 2.2.1', 'btn-primary', 'fa fa-search', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'preview'), true)
		->add_action('dropdown', '../../../laporan/anggaran/lembar_asistensi', 'Pratinjau Lembar Asistensi', 'btn-primary', 'fa fa-search', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'preview'), true)
		->column_order('kd_urusan, kegiatan, pagu')
		->field_order('id_prog, id_sub, kode, nama, pagu')
		->view_order('kd_program, nama, pagu')
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
			jenis_usulan,
			id_prog
		')
		->unset_field('id, tahun, kd_id_prog, nm_program')
		->unset_view('id, tahun, id_model')
		->unset_truncate('kegiatan')
		->merge_content('<b>{kd_urusan}.{kd_bidang}.{kd_unit}.{kd_sub}.{kd_program}.{kd_keg}</b>', phrase('kode'))
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
				'nilai_usulan'						=> 'number_format'
			)
		)
		->set_alias
		(
			array
			(
				'nama'								=> 'Kegiatan'
			)
		)
		->set_field
		(
			array
			(
				'pagu'								=> 'number_format'
			)
		)
		->where
		(
			array
			(
				'ta__asistensi.id_operator'			=> $this->_primary,
				'tahun'								=> get_userdata('year')
			)
		)
		->join('ta__asistensi', 'ta__asistensi.id_keg = ' . $this->_table . '.id')
		->join('ta__program', 'ta__program.id = ' . $this->_table . '.id_prog')
		->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
		->join('ref__program', 'ref__program.id = ta__program.id_prog')
		->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->group_by('ta__asistensi.id_keg')
		->select
		('
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ref__program.kd_program,
			ta__kegiatan.id AS id_keg_label,
			ta__kegiatan.id AS id_keg_respond
		')
		->merge_content('{id_keg_label}', 'Asistensi', 'callback_label_asistensi')
		->merge_content('{id_keg_respond}', 'Respon', 'callback_count_respond')
		->order_by
		(
			array
			(
				'ta__asistensi.tanggal'				=> 'DESC'
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
		->autoload_form(false)
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
			'ta__asistensi_setuju.id_keg = ta__kegiatan.id'
		)
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'					=> $params['id_keg_label']
			),
			1
		)
		->row();
		return '
			<a href="' . go_to('verifikatur', array('id_keg' => $params['id_keg_label'])) . '" class="ajax">
				<span class="badge bg-' . (isset($query->perencanaan) && 1 == $query->perencanaan ? 'green' : 'red') . '">B</span>
				<span class="badge bg-' . (isset($query->keuangan) && 1 == $query->keuangan ? 'green' : 'red') . '">K</span>
				<span class="badge bg-' . (isset($query->setda) && 1 == $query->setda ? 'green' : 'red') . '">P</span>
			</a>
		';
	}
	
	public function count_respond($params = array())
	{
		if(!isset($params['id_keg_respond'])) return 0;
		$total										= $this->model
		->select('sum(ta__asistensi.id) as total')
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id = ta__asistensi.id_keg'
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
				'ta__asistensi.id_keg'				=> $params['id_keg_respond']
			)
		)
		->row('total');
		
		$comments									= $this->model->select('count(comments) as comments')->get_where('ta__asistensi', array('id_keg' => $params['id_keg_respond'], 'comments !=' => ''))->row('comments');
		
		$tanggapan									= $this->model->select('count(tanggapan) as tanggapan')->get_where('ta__asistensi', array('id_keg' => $params['id_keg_respond'], 'tanggapan !=' => ''))->row('tanggapan');
		
		return '<a href="' . my_base_url('master/renja/tanggapan', array('id_keg' => $params['id_keg_respond'])) . '" class="render-notification"><span class="badge bg-blue" data-toggle="tooltip" title="' . (isset($comments) ? $comments : 0) . ' komentar">' . ($comments > 0 ? $comments : 0) . '</span>&nbsp;<span class="badge bg-green" data-toggle="tooltip" title="' . ($tanggapan > 0 ? $tanggapan : 0) . ' tanggapan">' . ($tanggapan > 0 ? $tanggapan : 0) . '</span></a>';
	}
	
	private function _filter()
	{
		$output										= null;
		$query										= $this->model
				->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__sub.id, ref__sub.kd_sub, ref__sub.nm_sub')
				->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
				->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
				->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
				->get('ref__sub')
				->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('id_sub_filter') ? ' selected' : '') . '>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_sub']) . ' ' . $val['nm_sub'] . '</option>';
			}
		}
		$output										= '
			<select name="id_sub_filter" class="form-control input-sm bordered" placeholder="Filter berdasar Program">
				<option value="all">Berdasarkan semua Sub Unit</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}