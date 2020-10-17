<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Scan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		
		$this->_id_sub								= (2 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_sub'));
		if(1 != get_userdata('group_id'))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		elseif(!$this->_id_sub)
		{
			generateMessages(301, 'Silakan pilih Kecamatan terlebih dahulu.', base_url('musrenbang/kecamatan'));
		}
		
		$this->set_method('update')
		->parent_module('musrenbang/skpd')
		->insert_on_update_fail()
		->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_title('Upload Hasil Scan')
		->set_icon('fa fa-qrcode')
		->set_primary('id_sub')
		->unset_field('id, id_sub, tanggal_upload')
		->unset_view('id, id_sub, tanggal_upload')
		->set_field
		(
			array
			(
				'file'								=> 'file',
				'description'						=> 'textarea'
			)
		)
		->set_default
		(
			array
			(
				'id_sub'							=> $this->_id_sub,
				'tanggal_upload'					=> date('Y-m-d')
			)
		)
		->where
		(
			array
			(
				'id_sub'							=> $this->_id_sub
			)
		)
		//->set_template('form', 'form')
		->render('ta__musrenbang_skpd_berkas');
	}
}