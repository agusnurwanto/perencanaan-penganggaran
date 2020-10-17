<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Program extends Aksara
{
	private $_table									= 'ref__program';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('bidang');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/data/urusan'				=> phrase('urusan'),
				'../bidang'							=> phrase('bidang')
			)
		);
		if($this->_primary)
		{
			$query									= $this->model->select('ref__urusan.kd_urusan, ref__urusan.nm_urusan, ref__bidang.kd_bidang, ref__bidang.nm_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			->get_where
			(
				'ref__bidang',
				array
				(
					'ref__bidang.id'				=> $this->_primary
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
				<div class="row border-bottom">
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
			')
			->where
			(
				array
				(
					'id_bidang'						=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_bidang'						=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_bidang')
			->unset_field('id_bidang')
			->unset_view('id_bidang')
			->select('kd_urusan, kd_bidang')
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
				'id_bidang',
				'ref__bidang.id',
				'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang} {ref__bidang.nm_bidang}',
				array
				(
					'ref__bidang.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__urusan',
						'ref__urusan.id = ref__bidang.id_urusan'
					)
				),
				'ref__urusan.kd_urusan, ref__bidang.kd_bidang'
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_program'))
			->set_icon('mdi mdi-access-point')
			->unset_column('id, tahun')
			->unset_view('id, tahun')
			->unset_field('id, tahun')
			->unset_truncate('nm_program')
			->set_field('nm_program', 'textarea, hyperlink', 'master/data/kegiatan', array('program' => 'id'), null, null, null, 'Program')
			->set_field
			(
				array
				(
					'kd_program'						=> 'last_insert'
				)
			)
			->add_class
			(
				array
				(
					'id_bidang'							=> 'trigger_kode',
					'kd_program'						=> 'kode_input',
					'nm_program'						=> 'autofocus'
				)
			)
			->column_order('kd_urusan, nm_program')
			->field_order('id_bidang')
			->merge_content('{kd_urusan}.{kd_bidang}.{kd_program}', phrase('kode'))
			->set_alias
			(
				array
				(
					'id_bidang'							=> 'Bidang',
					'kd_program'						=> 'Kode',
					'nm_program'						=> 'Program',
					'nm_bidang'							=> 'Nama Bidang'
				)
			)
			->set_validation
			(
				array
				(
					'kd_program'						=> 'required',
					'nm_program'						=> 'required'
				)
			)
			/*->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang
			')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')*/
			->order_by('kd_urusan, kd_bidang, kd_program')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_program')->get_where('ref__program', array('id_bidang' => $this->input->post('isu')), 1)->row('kd_program');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}