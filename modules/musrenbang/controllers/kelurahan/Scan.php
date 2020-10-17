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
			generateMessages(301, 'Silakan pilih Kelurahan terlebih dahulu.', base_url('musrenbang/kelurahan'));
		}
		
		$this->set_method('update')
		->parent_module('musrenbang/kelurahan/rw')
		->insert_on_update_fail()
		->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_title('Upload Hasil Scan')
		->set_icon('fa fa-qrcode')
		->set_primary('id_kel')
		->unset_field('id, id_kel, tanggal_upload')
		->unset_view('id, id_kel, tanggal_upload')
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
				'id_kel'							=> $this->_id_kel,
				'tanggal_upload'					=> date('Y-m-d')
			)
		)
		->where
		(
			array
			(
				'id_kel'							=> $this->_id_kel
			)
		)
		//->set_template('form', 'form')
		->render('ta__musrenbang_kelurahan_berkas');
	}
}