<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kegiatan extends Aksara
{
	private $_table									= 'ref__kegiatan';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('program');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/data/urusan'				=> phrase('urusan'),
				'../bidang'							=> phrase('bidang'),
				'../program'						=> phrase('program')
			)
		);
		if($this->_primary)
		{
			$query									= $this->model->select('ref__urusan.kd_urusan, ref__urusan.nm_urusan, ref__bidang.kd_bidang, ref__bidang.nm_bidang, ref__program.kd_program, ref__program.nm_program')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			->get_where
			(
				'ref__program',
				array
				(
					'ref__program.id'				=> $this->_primary
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
				<div class="row border-bottom">
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
			')
			->where
			(
				array
				(
					'id_program'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_program'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_program')
			->unset_field('id_program')
			->unset_view('id_program')
			->select('kd_urusan, kd_bidang, kd_program')
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
				'id_program',
				'ref__program.id',
				'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__program.kd_program} {ref__program.nm_program}',
				array
				(
					'ref__program.tahun'				=> get_userdata('year')
				),
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
				)
			//	'ref__bidang.kd_bidang, ref__program.kd_program'
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_kegiatan'))
			->set_icon('mdi mdi-access-point')
			->unset_column('id, id_program, tahun')
			->unset_view('id, id_program, tahun')
			->unset_field('id, tahun')
			->set_field('nm_kegiatan', 'textarea, hyperlink', 'master/data/sub_kegiatan', array('kegiatan' => 'id'))
			->set_field
			(
				array
				(
					'kd_kegiatan'						=> 'last_insert'
				)
			)
			->add_class
			(
				array
				(
					'id_program'						=> 'trigger_kode',
					'kd_kegiatan'						=> 'kode_input',
					'nm_kegiatan'						=> 'autofocus'
				)
			)
			->column_order('kd_urusan, nm_kegiatan')
			->field_order('id_program, kd_kegiatan')
			->merge_content('{kd_urusan}.{kd_bidang}.{kd_program}.{kd_kegiatan}', phrase('kode'))
			->set_alias
			(
				array
				(
					'id_program'						=> 'Program',
					'kd_kegiatan'						=> 'Kode',
					'nm_kegiatan'						=> 'Kegiatan',
					'nm_program'						=> 'Nama Program'
				)
			)
			->set_validation
			(
				array
				(
					'kd_kegiatan'						=> 'required',
					'nm_kegiatan'						=> 'required'
				)
			)
			/*->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang
			')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')*/
			->order_by('kd_urusan, kd_bidang, kd_program, kd_kegiatan')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_kegiatan')->get_where('ref__kegiatan', array('id_program' => $this->input->post('isu')), 1)->row('kd_kegiatan');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}