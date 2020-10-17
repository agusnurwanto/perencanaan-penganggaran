<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Scan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		
		$this->_id_kel								= (3 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_kel'));
		
		if(1 != get_userdata('group_id'))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		elseif(!$this->_id_kel)
		{
			generateMessages(301, 'Silakan pilih Kelurahan terlebih dahulu.', base_url('musrenbang/usulan/kelurahan'));
		}
		
		$this->set_method('update')
		->insert_on_update_fail()
		->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
	}
}