<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Verifikasi_sht extends Aksara
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		return make_json
		(
			array
			(
				'error'								=> 'No method were selected'
			)
		);
	}
	
	public function count()
	{
		$query										= $this->model->where
		(
			array
			(
				'approve'							=> 0,
				'tahun'								=> $this->input->post('tahun')
			)
		)
		->count_all_results('ref__standar_harga');
		
		return make_json
		(
			array
			(
				'count'								=> $query
			)
		);
	}
	
	public function get()
	{
		$query										= $this->model->select
		('
			ref__standar_harga.id AS id_siencang,
			"1" AS id_sub_rincian_objek,
			ref__standar_harga.uraian AS nama_barang,
			ref__standar_harga.deskripsi AS spesifikasi,
			ref__standar_harga.nilai AS harga_dasar,
			ref__standar_harga.satuan_1,
			ref__standar_harga.satuan_2,
			ref__standar_harga.satuan_3,
			ref__standar_harga.url AS link_1,
			ref__standar_harga.images AS files_1,
			CURRENT_TIMESTAMP()	AS waktu
		')
		->get_where
		(
			'ref__standar_harga',
			array
			(
				'ref__standar_harga.approve'		=> 0,
				'ref__standar_harga.tahun'			=> $this->input->post('tahun')
			)
		)
		->result();
		
		return make_json
		(
			array
			(
				'results'							=> $query
			)
		);
	}
	
	public function push()
	{
		if($this->input->post('id_siencang'))
		{
			$prepare								= array
			(
				'flag'								=> $this->input->post('flag'),
				'uraian'							=> $this->input->post('uraian'),
				'nilai'								=> $this->input->post('nilai'),
				'satuan_1'							=> $this->input->post('satuan_1'),
				'satuan_2'							=> $this->input->post('satuan_2'),
				'satuan_3'							=> $this->input->post('satuan_3'),
				'deskripsi'							=> $this->input->post('deskripsi'),
				'approve'							=> $this->input->post('status'),
				'alasan'							=> $this->input->post('alasan'),
				'dilihat'							=> $this->input->post('dilihat'),
				'images'							=> $this->input->post('file_1'),
				'url'								=> $this->input->post('link_1'),
				'approved_time'						=> $this->input->post('approved_time'),
				'approved_by'						=> $this->input->post('approved_by')
			);
			return make_json($prepare);
			//$this->model->update('ref__standar_harga', $prepare, array('id' => $this->input->post('id_siencang')));
		}
	}
}
