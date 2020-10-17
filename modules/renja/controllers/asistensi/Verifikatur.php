<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Verifikatur extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('sub_kegiatan');
		$this->set_theme('backend');
		$this->set_method('read');
	}
	
	public function index()
	{
		$this->set_title('Verifikatur')
		->set_icon('fa fa-bookmark')
		->unset_view('id, id_keg')
		->set_template('read', 'read')
		->set_output
		(
			'results',
			$this->model->where(array('id_keg_sub' => $this->_primary))
			->limit(1)
			->get('ta__asistensi_setuju')
			->row()
		)
		->render('ta__asistensi_setuju');
	}
}
