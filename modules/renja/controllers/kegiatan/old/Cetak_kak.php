<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Cetak_kak extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_keg');
		//$this->set_permission();
		$this->set_theme('backend');
		$this->parent_module('kegiatan/data');
		$this->set_method('pdf');
		$this->unset_action('index, create, read, update, delete, print, export');
	}
	
	public function index()
	{
		$data										= $this->_kak();
		$data										= $this->load->view('kegiatan/cetak_kak', $data, true);
		$this->load->library('wkhtmltopdf');
		$this->wkhtmltopdf->pageSize('8.5in', '13in');
		$this->wkhtmltopdf->pageMargin('80px', '100px');
		$this->wkhtmltopdf->load($data, 'KERANGKA ACUAN KERJA', 'embed');
	}
	
	private function _kak()
	{
		$header										= $this->model
		->select
		('
			ta__kegiatan.kd_keg,
			ta__kegiatan.kegiatan,
			ref__program.kd_program,
			ref__program.nm_program,
			ref__sub.kd_sub,
			ref__sub.nm_sub,
			ref__unit.nama_jabatan,
			ref__unit.nama_pejabat,
			ref__unit.nip_pejabat
		')
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->join
		(
			'ref__program',
			'ref__program.id = ta__program.id_prog'
		)
		->join
		(
			'ref__sub',
			'ref__sub.id = ta__program.id_sub'
		)
		->join
		(
			'ref__unit',
			'ref__unit.id = ref__sub.id_unit'
		)
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'					=> $this->_primary
			),
			1
		)
		->row();
		
		$capaian_program										= $this->model
		->select
		('
			ta__program_capaian.kode,
			ta__program_capaian.tolak_ukur,
			ta__program_capaian.tahun_2_target,
			ta__program_capaian.tahun_2_satuan
		')
		->join
		(
			'ta__program',
			'ta__program.id = ta__program_capaian.id_prog'
		)
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id_prog = ta__program.id'
		)
		->get_where
		(
			'ta__program_capaian',
			array
			(
				'ta__kegiatan.id'					=> $this->_primary
			)
		)
		->result();
		
		$indikator									= $this->model
		->select
		('
			ta__indikator.kd_indikator,
			ta__indikator.jns_indikator,
			ta__indikator.tolak_ukur,
			ta__indikator.target,
			ta__indikator.satuan
		')
		->get_where
		(
			'ta__indikator',
			array
			(
				'ta__indikator.id_keg'					=> $this->_primary
				
			)
		)
		->result();
		
		$kak										= $this->model
		->get_where
		(
			'ta__kak',
			array
			(
				'id_keg'							=> $this->_primary
			),
			1
		)
		->row();
		
		//print_r($indikator);exit;
		return array
		(
			'header'								=> $header,
			'capaian_program'						=> $capaian_program,
			'indikator'								=> $indikator,
			'kak'									=> $kak
		);
	}
}
