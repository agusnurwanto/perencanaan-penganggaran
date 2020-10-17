<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rw extends Aksara
{
	private $_table									= 'ref__rw';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_kelurahan							= $this->input->get('id_kel');
		$this->_title								= ''; //$this->select('nama_kelurahan')->get_where('ref__kelurahan', array('id' => $this->_kelurahan), 1)->row('nama_kelurahan');
		if($this->_title)
		{
			$this->_title							= 'Kelurahan ' . $this->_title;
		}
	}
	
	public function index()
	{
		if($this->_kelurahan)
		{
			$this
			->set_default('id_kel', $this->_kelurahan)
			->where('id_kel', $this->_kelurahan)
			->unset_column('id_kel')
			->unset_field('id_kel')
			->join('ref__kelurahan', 'ref__kelurahan.id = ref__rw.id_kel')
			;
		}
		else
		{
			$this->set_relation
			(
				'id_kel',
				'ref__kelurahan.id',
				'{ref__kelurahan.nama_kelurahan}'
			);
		}
		$this->set_breadcrumb
		(
			array
			(
				'master'							=> phrase('master'),
				'kecamatan'							=> phrase('kecamatan'),
				'kelurahan'							=> phrase('kelurahan')
			)
		)
		->set_title('Data RW ' . $this->_title)
		//->set_field('rw', 'hyperlink', 'master/rt', array('id_rw' => 'id'))
		//->set_field('nama_kelurahan', 'hyperlink', 'master/kelurahan')
		//->set_field('kode', 'last_insert')
		->unset_column('id')
		->unset_field('id')		
		->set_alias
		(
			array
			(
				'id_kel'							=> 'Kelurahan'
			)
		)/*
		->set_relation
		(
			'nama',
			'ref__penduduk.id',
			'{ref__penduduk.nama} - {ref__penduduk.alamat}',
			null,
			null,
			'ref__penduduk.nama'
		)*/
		->column_order('kode, nama_kelurahan')
		->render($this->_table);
	}

}