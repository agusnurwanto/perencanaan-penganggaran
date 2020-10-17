<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Variabel extends Aksara
{
	private $_table									= 'ref__musrenbang_variabel';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_musrenbang_jenis_pekerjaan');
		$this->_title								= $this->model->select('nama_pekerjaan')->get_where('ref__musrenbang_jenis_pekerjaan', array('id' => $this->_primary), 1)->row('nama_pekerjaan');
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		if($this->_primary)
		{
			$this->set_default('id_musrenbang_jenis_pekerjaan', $this->_primary)
			->where('id_musrenbang_jenis_pekerjaan', $this->_primary);
		}
		else
		{
			$this->set_relation
			(
				'id_musrenbang_jenis_pekerjaan',
				'ref__musrenbang_jenis_pekerjaan.id',
				'{ref__musrenbang_jenis_pekerjaan.kode} - {ref__musrenbang_jenis_pekerjaan.nama_pekerjaan}'
			);
		}
		$this->set_title('Variabel - ' . $this->_title)
		->add_class
		(	
			array
			(
				'nama_variabel'					=> 'autofocus'
			)
		)
		->set_breadcrumb
		(
			array
			(
				'master'									=> phrase('master'),
				'musrenbang/jenis_pekerjaan'					=> phrase('jenis_pekerjaan')
			)
		)
		->unset_column('id, id_musrenbang_jenis_pekerjaan')
		->unset_field('id, id_musrenbang_jenis_pekerjaan')
		->unset_view('id, id_musrenbang_jenis_pekerjaan')
		->set_field('kode_variabel', 'last_insert')
		->order_by('kode_variabel')
		->render($this->_table);
	}
}