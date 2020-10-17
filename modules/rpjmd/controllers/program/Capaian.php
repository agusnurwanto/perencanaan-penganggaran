<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Capaian extends Aksara
{
	private $_table									= 'ta__program_capaian';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= ($this->input->get('id_prog') ? $this->input->get('id_prog') : null);
	}
	
	public function index()
	{
		$program									= $this->model
													->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__program.kd_program, ref__program.nm_program')
													->join('ref__program', 'ref__program.id = ta__program.id_prog')
													->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
													->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
													->get_where('ta__program', array('ta__program.id' => $this->input->get('id_prog')), 1)
													->row();
		$sub_unit									= $this->model
													->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__sub.kd_sub, ref__sub.nm_sub')
													->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
													->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
													->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
													->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
													->get_where('ta__program', array('ta__program.id' => $this->input->get('id_prog')), 1)
													->row();
		//print_r($info);exit;
		//$this->db->last_query();
		$this->set_description
		('
			<div class="row">
				<div class="col-4 col-sm-2 text-muted text-sm">
					Sub Unit
				</div>
				<div class="col-8 col-sm-6 font-weight text-sm">
					' . $sub_unit->kd_urusan . '.' . sprintf('%02d', $sub_unit->kd_bidang) . '.' . sprintf('%02d', $sub_unit->kd_unit) . '.' . sprintf('%02d', $sub_unit->kd_sub) . ' ' . $sub_unit->nm_sub . '
				</div>
			</div>
			<div class="row border-bottom">
				<div class="col-4 col-sm-2 text-muted text-sm">
					Program
				</div>
				<div class="col-8 col-sm-6 font-weight text-sm">
					' . $program->kd_urusan . '.' . sprintf('%02d', $program->kd_bidang) . '.' . sprintf('%02d', $program->kd_program) . ' ' . $program->nm_program . '
				</div>
			</div>
		');
		if($this->_primary)
		{
			$this->set_default('id_prog', $this->_primary)
			->where('id_prog', $this->_primary);
		}
		else
		{
			$this->set_relation
			(
				'id_prog',
				'ta__program.id',
				'{ta__program.kd_id_prog}'
			);
		}
		$this->set_breadcrumb
		(
			array
			(
				'rpjmd/program'								=> phrase('program')
			)
		)
		->set_title('Data Capaian Program')
		->set_field('kode', 'last_insert')
		->unset_column('id, id_prog, target, satuan')
		->unset_field('id, id_prog, target, satuan')
		
		->merge_content('{tahun_1_target} {tahun_1_satuan}', 'tahun_1')
		->merge_content('{tahun_2_target} {tahun_2_satuan}', 'tahun_2')
		->merge_content('{tahun_3_target} {tahun_3_satuan}', 'tahun_3')
		->merge_content('{tahun_4_target} {tahun_4_satuan}', 'tahun_4')
		->merge_content('{tahun_5_target} {tahun_5_satuan}', 'tahun_5')
		->merge_content('{target_akhir} {satuan_akhir}', 'akhir')
		->add_class
		(
			array
			(
				'tolak_ukur'						=> 'autofocus'
			)
		)
		->set_field
		(
			array
			(
				'tolak_ukur'						=> 'textarea'
			)
		)
		->set_field
		(
			'status',
			'radio',
			array
			(
				0									=> '<label class="badge badge-primary">Positif</label>',
				1									=> '<label class="badge badge-success">Negatif</label>',
				2									=> '<label class="badge badge-danger">Flat</label>'
			)
		)
		->where
		(
			array
			(
				'id_keg'							=> $this->_primary
			)
		)
		->set_default
		(
			array
			(
				'id_prog'							=> $this->_primary
			)
		)
		->field_position
		(
			array
			(
				'kode'								=> 1,
				'tolak_ukur'						=> 1,
				'tahun_1_target'					=> 2,
				'tahun_1_satuan'					=> 2,
				'tahun_2_target'					=> 2,
				'tahun_2_satuan'					=> 2,
				'tahun_3_target'					=> 3,
				'tahun_3_satuan'					=> 3,
				'tahun_4_target'					=> 3,
				'tahun_4_satuan'					=> 3,
				'tahun_5_target'					=> 4,
				'tahun_5_satuan'					=> 4,
				'target_akhir'						=> 4,
				'satuan_akhir'						=> 4
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required',
				'tolak_ukur'						=> 'required',
				'status'							=> 'required',
				'tahun_1_target'					=> 'required|xss_clean',
				'tahun_1_satuan'					=> 'required|xss_clean',
				'tahun_2_target'					=> 'required|xss_clean',
				'tahun_2_satuan'					=> 'required|xss_clean',
				'tahun_3_target'					=> 'required|xss_clean',
				'tahun_3_satuan'					=> 'required|xss_clean',
				'tahun_4_target'					=> 'required|xss_clean',
				'tahun_4_satuan'					=> 'required|xss_clean',
				'tahun_5_target'					=> 'required|xss_clean',
				'tahun_5_satuan'					=> 'required|xss_clean'
			)
		)
		//->order_by('kode')
		->render($this->_table); 
	}	
	
	private function _filter()
	{
		$output										= null;
		if(1 != get_userdata('group_id'))
		{
			$this->model->where('id', get_userdata('id_sub'));
		}
		$query										= $this->model->select('id, kd_sub, nm_sub')->order_by('kd_sub')->get('ref__sub')->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->_primary ? ' selected' : '') . '>' . sprintf('%02d', $val['kd_sub']) . '. ' . $val['nm_sub'] . '</option>';
			}
		}
		$output										= '
			<select name="id_sub" class="form-control input-sm bordered" placeholder="' . phrase('filter_berdasar_sub_unit') . '">
				<option value="all">' . phrase('semua_sub_unit') . '</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}
