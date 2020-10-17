<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kegiatan extends Aksara
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->set_title('Demografis Data Kegiatan')
		->set_icon('fa fa-map-marker')
		->set_output
		(
			array
			(
				'coordinate'							=> get_setting('office_map'),
				'results'								=> $this->model
				->select
				('
					ref__kecamatan.id as id_kec,
					ref__kecamatan.kecamatan,
					count(ta__musrenbang.id) as total
				')
				->join
				(
					'ta__musrenbang',
					'ta__musrenbang.id_kec = ref__kecamatan.id'
				)
				->join
				(
					'ref__musrenbang_jenis_pekerjaan',
					'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan'
				)
				->join
				(
					'ref__musrenbang_isu',
					'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu'
				)
				->group_by('id_kec')
				->get_where
				(
					'ref__kecamatan',
					array
					(
						'ref__kecamatan.id > '			=> 0
					)
				)
				->result()
			)
		)
		->render();
	}
	
	public function _get_thread($id_kec = 0)
	{
		$query											= $this->model
		->select
		('
			count(ta__musrenbang.id) as total,
			ref__musrenbang_isu.id as id_isu,
			ref__musrenbang_isu.kode,
			ref__musrenbang_isu.nama_isu
		')
		->join
		(
			'ref__musrenbang_jenis_pekerjaan',
			'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan'
		)
		->join
		(
			'ref__musrenbang_isu',
			'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu'
		)
		->group_by('id_isu')
		->get_where
		(
			'ta__musrenbang',
			array
			(
				'ta__musrenbang.id_kec'					=> $id_kec
			)
		)
		->result();
		
		$output											= '
			<div class="form-group">
				<input type="checkbox" role="check-all" checker-parent=".panel-body" id="checker_' . $id_kec . '" />
				<label style="display:block;margin-top:-22px;margin-left:30px" for="checker_' . $id_kec . '">
					Semua Isu
				</label>
			</div>
		';
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output									.= '
					<div class="form-group">
						<input type="checkbox" name="isu[]" class="check-all-children" value="' . $id_kec . '_' . $val->id_isu . '" id="isu_' . $id_kec . '_' . $val->id_isu . '_' . $key . '" />
						<label style="display:block;margin-top:-22px;margin-left:30px" for="isu_' . $id_kec . '_' . $val->id_isu . '_' . $key . '">
							<span class="label bg-green pull-right" style="margin-top:5px">' . $val->total . '</span>
							' . $val->nama_isu . '
						</label>
					</div>
				';
			}
		}
		return $output;
	}
}