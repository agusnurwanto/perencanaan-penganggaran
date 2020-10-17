<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Scan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		
		$this->_id_kec								= (2 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_kec'));
		if(1 != get_userdata('group_id'))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		elseif(!$this->_id_kec)
		{
			generateMessages(301, 'Silakan pilih Kecamatan terlebih dahulu.', base_url('musrenbang/kecamatan'));
		}
		
		$this->set_method('update')
		->parent_module('musrenbang/kecamatan')
		->insert_on_update_fail()
		->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_title('Upload Hasil Scan')
		->set_icon('fa fa-qrcode')
		->set_primary('id_kec')
		->unset_field('id, id_kec, tanggal_upload')
		->unset_view('id, id_kec, tanggal_upload')
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
				'id_kec'							=> $this->_id_kec,
				'tanggal_upload'					=> date('Y-m-d')
			)
		)
		->where
		(
			array
			(
				'id_kec'							=> $this->_id_kec
			)
		)
		//->set_template('form', 'form')
		->render('ta__musrenbang_kecamatan_berkas');
	}
}