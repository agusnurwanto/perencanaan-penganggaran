<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rt extends Aksara
{
	private $_table									= 'ref__rt';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_rw									= $this->input->get('id_rw');
		$this->_title								= '';//$this->select('rw')->get_where('ref__rw', array('id' => $this->_rw), 1)->row('rw');
		if($this->_title)
		{
			$this->_title							= $this->_title;
		}
	}
	
	public function index()
	{
		if($this->_rw)
		{
			$this->set_default('id_rw', $this->_rw)
			->unset_column('id_rw')
			->unset_field('id_rw');
		}
		else
		{
			$this->set_relation
			(
				'id_rw',
				'ref__rw.id',
				'{ref__rw.rw}'
			);
		}
		$this->set_breadcrumb
		(
			array
			(
				'master'							=> phrase('master'),
				'kecamatan'							=> phrase('kecamatan'),
				'kelurahan'							=> phrase('kelurahan'),
				'rw'								=> 'RW'
			)
		)
		->set_title('Data RT ' . $this->_title)
		->set_field('rw', 'hyperlink', 'master/wilayah/rw', array('id_rw' => 'id_rw'))
		//->set_field('rt', 'hyperlink', 'master/penduduk', array('id_rt' => 'id'))
		->set_field('kode', 'last_insert')
		->unset_column('id')
		->unset_field('id')
		/*->set_relation
		(
			'nama',
			'ref__penduduk.id',
			'{ref__penduduk.nama} - {ref__penduduk.alamat}',
			null,
			null,
			'ref__penduduk.nama'
		)*/
		->set_alias
		(
			array
			(
				'id_rw'								=> 'Rukun Warga'
			)
		)
		->column_order('kode, rw')
		->render($this->_table);
	}

}