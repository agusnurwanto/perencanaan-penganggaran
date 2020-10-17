<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Menjadi_kecamatan extends Aksara
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
		if($checker->flag == 7 || $checker->flag == 8)
		{
			if($checker->pengusul == 1 || $checker->pengusul == 2)
			{
				$prepare								= array
				(
					'flag'								=> 4,
					'variabel_skpd'						=> "",
					'nilai_skpd'						=> 0
				);
			}
			elseif($checker->pengusul == 3)
			{
				$prepare								= array
				(
					'flag'								=> 6,
					'variabel_skpd'						=> "",
					'nilai_skpd'						=> 0
				);
			}
			//print_r($prepare);exit;
			$this->update_data('ta__musrenbang', $prepare, array('id' => $this->_primary), current_page('../data'), '_delete_kegiatan');
		}
		else
		{
			generateMessages(403, 'Anda tidak dapat mengubah data yang sudah di verifikasi menjadi usulan Kecamatan.', current_page('../data'));
		}
	}
	
	public function _delete_kegiatan()
	{
		$this->delete_data('ta__kegiatan', array('pengusul' => 0, 'id_musrenbang' => $this->_primary));
	}
}