<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sinkronisasi extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		
		$this->unset_action('create, read, update, delete, export, print, pdf');
		
		$this->_year								= get_userdata('year');
	}
	
	public function index()
	{
		$this->set_title(phrase('sinkronisasi'))
		->set_icon('mdi mdi-sync')
		->set_output
		(
			array
			(
				'unit'								=> $this->_unit(),
				'tahun'								=> $this->model->get_where('ref__tahun', array('aktif' => 1))->result()
			)
		)
		->render();
	}
	
	private function _unit()
	{
		$query										= $this->model->select
		('
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.id,
			ref__unit.kd_unit,
			ref__unit.nm_unit
		')
		->join
		(
			'ref__bidang',
			'ref__bidang.id = ref__unit.id_bidang'
		)
		->join
		(
			'ref__urusan',
			'ref__urusan.id = ref__bidang.id_urusan'
		)
		->get_where
		(
			'ref__unit',
			array
			(
				'ref__unit.tahun'					=> $this->_year
			)
		)
		->result();
		
		$options									= null;
		
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '.' . sprintf('%02d', $val->kd_unit) . ' - ' . $val->nm_unit . '</option>';
			}
		}
		
		return '
			<div class="form-group">
				<label class="d-block text-muted text-uppercase">
					Unit
				</label>
				<select name="unit" class="form-control form-control-sm">
					<option value="0">Semua Unit</option>
					' . $options . '
				</select>
			</div>
		';
	}
}