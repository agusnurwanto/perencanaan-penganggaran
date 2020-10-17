<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rka extends Aksara
{
	private $_table									= 'ta__belanja';
	
	function __construct()
	{
		parent::__construct();
		$this->_token								= $this->input->get('key');
		if($this->_token)
		{
			redirect('model/belanja?key=' . $this->_token);
		}
	}
	
	public function index()
	{
		$this->set_title(phrase('model'));
		$this->set_icon('fa fa-clipboard');
		$this->set_primary('id');
		$this->unset_column('id, tahun');
		$this->unset_field('id, tahun');
		$this->unset_view('id, tahun');
		$this->set_field('nm_model', 'hyperlink', 'key', 'id');
		$this->set_alias(array('kd_model' => phrase('kode'), 'nm_model' => phrase('nama_model'), 'desc' => phrase('keterangan_model')));
		$this->set_default('tahun', get_userdata('year'));
		$this->add_action(base_url('model/rka'), phrase('belanja'), 'btn-default', 'fa-shopping-cart');
		$this->render($this->_table);
	}
}