<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sub_kegiatan extends Aksara
{
	private $_table									= 'ref__kegiatan_sub';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('kegiatan');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/data/urusan'				=> phrase('urusan'),
				'../bidang'							=> phrase('bidang'),
				'../program'						=> phrase('program'),
				'../kegiatan'						=> phrase('kegiatan')
			)
		);
		if($this->_primary)
		{
			$query									= $this->model->select('ref__urusan.kd_urusan, ref__urusan.nm_urusan, ref__bidang.kd_bidang, ref__bidang.nm_bidang, ref__program.kd_program, ref__program.nm_program, ref__kegiatan.kd_kegiatan, ref__kegiatan.nm_kegiatan')
			->join('ref__program', 'ref__program.id = ref__kegiatan.id_program')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			->get_where
			(
				'ref__kegiatan',
				array
				(
					'ref__kegiatan.id'				=> $this->_primary
				), 1
			)
			->row();
			$this
			->set_description
			('
				<div class="row">
					<div class="col-12 col-sm-2 text-muted text-uppercase">
						Urusan
					</div>
					<div class="col-4 col-sm-1 font-weight-bold">
						' . (isset($query->kd_urusan) ? $query->kd_urusan : 0) . '
					</div>
					<div class="col-8 col-sm-9 font-weight-bold">
						' . (isset($query->nm_urusan) ? $query->nm_urusan : null) . '
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-2 text-muted text-uppercase">
						Bidang
					</div>
					<div class="col-4 col-sm-1 font-weight-bold">
						' . (isset($query->kd_urusan) ? $query->kd_urusan : 0) . '.' . (isset($query->kd_bidang) ? $query->kd_bidang : 0) . '
					</div>
					<div class="col-8 col-sm-9 font-weight-bold">
						' . (isset($query->nm_bidang) ? $query->nm_bidang : null) . '
					</div>
				</div>
				<div class="row">
					<div class="col-12 col-sm-2 text-muted text-uppercase">
						Program
					</div>
					<div class="col-4 col-sm-1 font-weight-bold">
						' . (isset($query->kd_urusan) ? $query->kd_urusan : 0) . '.' . (isset($query->kd_bidang) ? $query->kd_bidang : 0) . '.' . (isset($query->kd_program) ? $query->kd_program : 0) . '
					</div>
					<div class="col-8 col-sm-9 font-weight-bold">
						' . (isset($query->nm_program) ? $query->nm_program : null) . '
					</div>
				</div>
				<div class="row border-bottom">
					<div class="col-12 col-sm-2 text-muted text-uppercase">
						Kegiatan
					</div>
					<div class="col-4 col-sm-1 font-weight-bold">
						' . (isset($query->kd_urusan) ? $query->kd_urusan : 0) . '.' . (isset($query->kd_bidang) ? $query->kd_bidang : 0) . '.' . (isset($query->kd_program) ? $query->kd_program : 0) . '.' . (isset($query->kd_kegiatan) ? $query->kd_kegiatan : 0) . '
					</div>
					<div class="col-8 col-sm-9 font-weight-bold">
						' . (isset($query->nm_kegiatan) ? $query->nm_kegiatan : null) . '
					</div>
				</div>
			')
			->where
			(
				array
				(
					'id_kegiatan'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_kegiatan'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_kegiatan')
			->unset_field('id_kegiatan')
			->unset_view('id_kegiatan')
			->select('kd_urusan, kd_bidang, kd_program, kd_kegiatan')
			->join('ref__kegiatan', 'ref__kegiatan.id = ref__kegiatan_sub.id_kegiatan')
			->join('ref__program', 'ref__program.id = ref__kegiatan.id_program')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			;
		}
		else
		{
			$this->where
			(
				array
				(
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
			->set_relation
			(
				'id_kegiatan',
				'ref__kegiatan.id',
				'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__program.kd_program}.{ref__kegiatan.kd_kegiatan} {ref__kegiatan.nm_kegiatan}',
				array
				(
					'ref__kegiatan.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__program',
						'ref__program.id = ref__kegiatan.id_program'
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
				)
			//	'ref__program.kd_program, ref__kegiatan.kd_kegiatan'
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_sub_kegiatan') . ' ' . $this->_title)
			->set_icon('fa fa-user-circle-o')
			->unset_column('id, id_kegiatan, tahun')
			->unset_view('id, id_kegiatan, tahun')
			->unset_field('id, tahun')
			->unset_truncate('nm_kegiatan_sub')
			//->set_field('nm_kegiatan_sub', 'textarea, hyperlink', 'master/data/Sub_kegiatan', array('id_kegiatan_sub' => 'id'))
			->set_field
			(
				array
				(
					'kd_kegiatan_sub'						=> 'last_insert'
				)
			)
			->add_class
			(
				array
				(
					'id_kegiatan'							=> 'trigger_kode',
					'kd_kegiatan_sub'						=> 'kode_input',
					'nm_kegiatan_sub'						=> 'autofocus'
				)
			)
			->column_order('kd_urusan, nm_kegiatan_sub')
			->field_order('id_kegiatan, kd_kegiatan_sub')
			->merge_content('{kd_urusan}.{kd_bidang}.{kd_program}.{kd_kegiatan}.{kd_kegiatan_sub}', phrase('kode'))
			->set_alias
			(
				array
				(
					'id_kegiatan'						=> 'Kegiatan',
					'kd_kegiatan_sub'					=> 'Kode',
					'nm_kegiatan_sub'					=> 'Sub Kegiatan',
					'nm_kegiatan'						=> 'Nama Kegiatan'
				)
			)
			->set_validation
			(
				array
				(
					'kd_kegiatan_sub'					=> 'required',
					'nm_kegiatan_sub'					=> 'required'
				)
			)
			/*->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang
			')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')*/
			->order_by('kd_urusan, kd_bidang, kd_program, kd_kegiatan, kd_kegiatan_sub')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_kegiatan_sub')->get_where('ref__kegiatan_sub', array('id_kegiatan' => $this->input->post('isu')), 1)->row('kd_kegiatan_sub');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}