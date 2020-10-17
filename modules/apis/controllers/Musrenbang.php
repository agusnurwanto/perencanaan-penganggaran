<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Musrenbang extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('rest');
	}
	
	public function index()
	{
		if(is_numeric($this->input->get('limit')))
		{
			$this->model->limit($this->_limit, $this->_offset);
		}
		$query										= $this->model
		->select
		('
			ref__kecamatan.kecamatan,
			ref__kelurahan.nama_kelurahan AS kelurahan,
			ref__rw.rw,
			ref__rt.rt,
			ref__musrenbang_jenis_pekerjaan.nama_pekerjaan,
			map_address,
			flag
		')
		->join('ref__kecamatan', 'ref__kecamatan.id = ta__musrenbang.id_kec')
		->join('ref__kelurahan', 'ref__kelurahan.id = ta__musrenbang.id_kel')
		->join('ref__rw', 'ref__rw.id = ta__musrenbang.id_rw')
		->join('ref__rt', 'ref__rt.id = ta__musrenbang.id_rt')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan')
		->get('ta__musrenbang')
		->result();
		$this->rest->set_output($query);
	}
}