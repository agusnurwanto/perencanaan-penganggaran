<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Generators extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
		if(1 != get_userdata('group_id'))
		{
			return throw_exception(403, 'Anda tidak diizinkan untuk mengakses halaman yang diminta.');
		}
	}
	
	public function index()
	{
		$this->set_title('Generator Massal')
		->set_icon('mdi mdi-sync')
		->render();
	}
	
	public function execute()
	{
		$renja									= array();
		$query_renja							= $this->model
		->select
		('
			ref__renja_jenis_pekerjaan.id,
			ref__renja_jenis_pekerjaan.nama_pekerjaan,
			ref__renja_jenis_pekerjaan.pilihan,
			ref__renja_jenis_pekerjaan.pagu,
			ref__renja_jenis_pekerjaan.pagu_1,
			ref__renja_jenis_pekerjaan.id_sumber_dana
		')
		->get('ref__renja_jenis_pekerjaan')
		->result();
		
		if($query_renja)
		{
			foreach($query_renja as $key => $val)
			{
				$renja[$val->id]				= $val;
			}
		}
		
		$kegiatan								= $this->model
		->select
		('
			ta__kegiatan.jenis_kegiatan_renja,
			ta__kegiatan.kegiatan,
			ta__kegiatan.input_kegiatan,
			ta__kegiatan.map_address,
			ta__kegiatan.pagu,
			ta__kegiatan.pagu_1,
			ta__kegiatan.id_sumber_dana
		')
		->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog')
		->join('ref__program', 'ref__program.id = ta__program.id_prog')
		->get_where('ta__kegiatan', array('ta__kegiatan.flag' => 1, 'ta__kegiatan.tahun' => get_userdata('year'), 'ref__program.kd_program >' => '15'))
		->result();
		
		$prepare								= array();
		$success								= 0;
		foreach($kegiatan as $key => $val)
		{
			if(isset($renja[$val->jenis_kegiatan_renja]))
			{
				$prepare						= array
				(
					'kegiatan'					=> (1 == $renja[$val->jenis_kegiatan_renja]->pilihan ? $renja[$val->jenis_kegiatan_renja]->nama_pekerjaan . ' (' . $val->map_address . ')' : ($val->input_kegiatan ? $renja[$val->jenis_kegiatan_renja]->nama_pekerjaan . ' ' . $val->input_kegiatan : $renja[$val->jenis_kegiatan_renja]->nama_pekerjaan)),
					'pagu'						=> $renja[$val->jenis_kegiatan_renja]->pagu,
					'pagu_1'					=> $renja[$val->jenis_kegiatan_renja]->pagu_1,
					'id_sumber_dana'			=> $renja[$val->jenis_kegiatan_renja]->id_sumber_dana
				);
				if($this->model->update('ta__kegiatan', $prepare, array('jenis_kegiatan_renja' => $val->jenis_kegiatan_renja)))
				{
					$success++;
				}
			}
		}
		
		if($success)
		{
			return throw_exception(202, 'Judul dan Pagu berhasil disesuaikan dengan Master Jenis Pekerjaan. Berhasil memperbarui ' . number_format($success) . ' data.', go_to());
		}
		else
		{
			return throw_exception(500, 'Judul dan Pagu tidak dapat disesuaikan dengan Master Jenis Pekerjaan', go_to());
		}
	}
}