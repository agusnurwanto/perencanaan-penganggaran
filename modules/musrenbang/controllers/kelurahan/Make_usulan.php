<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Make_usulan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id');
		if(!in_array(get_userdata('group_id'), array(1, 3)))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk melihat usulan', base_url('musrenbang'));
		}
		elseif(!$this->_primary)
		{
			generateMessages(301, 'Silakan memilih Usulan terlebih dahulu', base_url('musrenbang/kelurahan/data'));
		}
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$checker									= $this->model->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
		if($checker->flag == 1 || $checker->flag == 2)
		{
			$prepare								= array
			(
				'flag'								=> 0
			);
			//print_r($prepare);exit;
			$this->update_data('ta__musrenbang', $prepare, array('id' => $this->_primary), go_to('../data'));
		}
		else
		{
			generateMessages(403, 'Anda tidak dapat mengubah data yang sudah di verifikasi menjadi usulan RW.', current_page('../data'));
		}
	}
}