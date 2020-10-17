<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Details extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->parent_module('maps');
	}
	
	public function index($slug = null)
	{
		if($this->input->post('modal'))
		{
			$this->set_template('index', 'index_modal');
		}
		$this->set_title('Detail Kegiatan')
		->set_icon('fa fa-map-marker')
		->select
		('
			ta__musrenbang.*,
			ref__musrenbang_jenis_pekerjaan.kode,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			ref__musrenbang_jenis_pekerjaan.deskripsi,
			ref__musrenbang_jenis_pekerjaan.images
		')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan', 'LEFT')
		->where('ta__musrenbang.id', $slug)
		->render('ta__musrenbang');
	}
}