<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pertanyaan extends Aksara
{
	private $_table									= 'ref__musrenbang_pertanyaan';
	
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
		$this->set_title('Pertanyaan - ' . $this->_title)
		->add_class
		(	
			array
			(
				'pertanyaan'						=> 'autofocus'
			)
		)
		->set_breadcrumb
		(
			array
			(
				'master'							=> phrase('master'),
				'renja/jenis_pekerjaan'				=> phrase('jenis_pekerjaan')
			)
		)
		->unset_column('id, id_musrenbang_jenis_pekerjaan')
		->unset_field('id, id_musrenbang_jenis_pekerjaan')
		->unset_view('id, id_musrenbang_jenis_pekerjaan')
		->set_field('kode', 'last_insert')
		->order_by('kode')
		->render($this->_table);
	}
}