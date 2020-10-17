<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Menjadi_kelurahan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id');
		if(!in_array(get_userdata('group_id'), array(1)))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk melihat usulan', base_url('musrenbang'));
		}
		elseif(!$this->_primary)
		{
			generateMessages(301, 'Silakan memilih Data terlebih dahulu', base_url('musrenbang/kecamatan/data'));
		}
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$checker									= $this->model->get_where('ta__musrenbang', array('id' => $this->_primary), 1)->row();
		if($checker->flag == 4 || $checker->flag == 5)
		{
			if($checker->pengusul == 1)
			{
				$prepare								= array
				(
					'flag'								=> 1,
					'variabel_kecamatan'				=> '',
					'nilai_kecamatan'					=> 0
				);
			}
			elseif($checker->pengusul == 2)
			{
				$prepare								= array
				(
					'flag'								=> 3,
					'variabel_kecamatan'				=> '',
					'nilai_kecamatan'					=> 0
				);
			}
			//print_r($prepare);exit;
			$this->update_data('ta__musrenbang', $prepare, array('id' => $this->_primary), go_to('../data'));
		}
		else
		{
			generateMessages(403, 'Anda tidak dapat mengubah data yang sudah di verifikasi menjadi usulan Kelurahan.', current_page('../data'));
		}
	}
}