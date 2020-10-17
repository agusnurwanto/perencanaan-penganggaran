<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Bidang extends Aksara
{
	private $_table									= 'ref__bidang';
	private $_title									= null;
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('urusan');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/data/urusan'				=> phrase('urusan')
			)
		);
		if($this->_primary)
		{
			$query									= $this->model->select('ref__urusan.kd_urusan, ref__urusan.nm_urusan')
			->get_where
			(
				'ref__urusan',
				array
				(
					'ref__urusan.id'				=> $this->_primary
				), 1
			)
			->row();
			$this
			->set_description
			('
				<div class="row border-bottom">
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
			')
			->where
			(
				array
				(
					'id_urusan'						=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'id_urusan'						=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->unset_column('id_urusan')
			->unset_field('id_urusan')
			->unset_view('id_urusan')
			->select('kd_urusan')
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
				'id_urusan',
				'ref__urusan.id',
				'{ref__urusan.kd_urusan}. {ref__urusan.nm_urusan}',
				array
				(
					'ref__urusan.tahun'				=> get_userdata('year')
				),
				NULL,
				array
				(
					'ref__urusan.kd_urusan'			=> 'ASC'
				)
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('master_bidang'))
			->set_icon('mdi mdi-access-point')
			->unset_column('id, tahun')
			->unset_view('id, tahun')
			->unset_field('id, tahun')
			->unset_truncate('nm_bidang')
			->set_field('nm_bidang', 'textarea, hyperlink', 'master/data/program', array('bidang' => 'id'), null, null, null, 'Bidang')
			->set_field
			(
				array
				(
					'kd_bidang'						=> 'last_insert'
				)
			)
			->add_class
			(
				array
				(
					'id_urusan'						=> 'trigger_kode',
					'kd_bidang'						=> 'kode_input',
					'nm_bidang'						=> 'autofocus'
				)
			)
			->column_order('kd_urusan, nm_bidang')
			->field_order('id_urusan')
			->merge_content('{kd_urusan}.{kd_bidang}', phrase('kode'))
			->set_alias
			(
				array
				(
					'id_urusan'							=> 'Urusan',
					'kd_bidang'							=> 'Kode',
					'nm_bidang'							=> 'Bidang',
					'nm_urusan'							=> 'Nama Urusan'
				)
			)
			->set_validation
			(
				array
				(
					'kd_bidang'							=> 'required',
					'nm_bidang'							=> 'required'
				)
			)
			/*->select
			('
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang
			')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')*/
			->order_by('kd_urusan, kd_bidang')
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_bidang')->get_where('ref__bidang', array('id_urusan' => $this->input->post('isu')), 1)->row('kd_bidang');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}