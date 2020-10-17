<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Fraksi extends Aksara
{
	private $_table									= 'ref__dprd_fraksi';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_id_sub								= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_sub'));
		if($this->_id_sub)
		{
			generateMessages(301, null, 'data');
		}
	}
	
	public function index()
	{
		$this->set_title('Silakan pilih Fraksi')
		->set_icon('fa fa-flag-o')
		->unset_action('create, read, update, delete, export, print, pdf')
		->unset_column('id')
		->set_field('nama_fraksi', 'hyperlink', 'reses/fraksi/data', array('id_sub' => 'id'))
		->render($this->_table);
	}
}