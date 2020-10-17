<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Json extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->permission->must_ajax();
		$this->_id_kec									= 0;
		$this->_data									= $this->input->post('isu');
		$this->_flag									= $this->input->post('flag');
	}
	
	public function index()
	{
		$this->_isu										= array();
		if($this->_data && is_array($this->_data) && sizeof($this->_data) > 0)
		{
			foreach($this->_data as $key => $val)
			{
				$data									= explode('_', $val);
				if(isset($data[0]) && isset($data[1]) && is_numeric($data[0]) && is_numeric($data[1]))
				{
					$this->_id_kec						= $data[0];
					$this->_isu[]						= $data[1];
				}
			}
		}
		
		if($this->_isu)
		{
			$this->model->where_in('ref__musrenbang_isu.id', $this->_isu);
		}
		
		if($this->_flag)
		{
			$this->model->where_in('ta__musrenbang.flag', $this->_flag);
		}
		
		$markers										= $this->model
		->select
		('
			ta__musrenbang.id,
			ta__musrenbang.map_coordinates,
			ta__musrenbang.map_address,
			ref__musrenbang_jenis_pekerjaan.kode,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.deskripsi,
			ref__musrenbang_jenis_pekerjaan.images
		')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->join('ref__musrenbang_isu', 'ref__musrenbang_isu.id = ref__musrenbang_jenis_pekerjaan.id_isu')
		->get_where
		(
			'ta__musrenbang',
			array
			(
				'ta__musrenbang.id_kec'					=> $this->_id_kec
			)
		)
		->result();
		//echo $this->model->last_query();exit;
		$coordinates									= array();
		
		foreach($markers as $key => $val)
		{
			$coordinates[]								= array
			(
				$output[]								= array
				(
					'coordinate'						=> json_decode($val->map_coordinates),
					
					'title'								=> '<div class="row"><div class="col-3 pt-1 pr-0"><img src="' . get_image('musrenbang', $val->images, 'thumb') . '" class="img-fluid rounded" alt="..." /></div><label class="col font-weight-bold text-muted">' . $val->nama_pekerjaan . '</label></div></div>',
					
					'description'						=> '<p>' . truncate($val->deskripsi, 160) . '</p><a href="' . base_url('maps/details/' . $val->id) . '" class="btn btn-sm btn-outline-primary"><i class="fa fa-search-plus"></i> ' . phrase('details') . '</a>',
					
					'icon'								=> get_image('musrenbang', $val->images, 'icon')
				);
			);
		}
		
		if($coordinates)
		{
			return make_json($coordinates);
		}
		else
		{
			generateMessages(404, 'Tidak ada data koordinat yang ditemukan');
		}
	}
}