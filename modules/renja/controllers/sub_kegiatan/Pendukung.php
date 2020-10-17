<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pendukung extends Aksara
{
	private $_table									= 'ta__kegiatan_pendukung';
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_keg');
		$this->set_permission();
		$this->set_theme('backend');
		$this->parent_module('kegiatan/data');
		$this->insert_on_update_fail(true);
	}
	
	public function index()
	{
		if(!in_array(get_userdata('group_id'), array(1, 12, 9, 10)))
		{
			$this->set_field
			(
				array
				(
					'tanggapan_kak'					=> 'disabled',
					'tanggapan_rkbu'				=> 'disabled',
					'tanggapan_rab'					=> 'disabled',
					'tanggapan_gambar'				=> 'disabled'
				)
			);
		}
		$this->set_method('update')
		->set_title('Berkas Bukti')
		->set_description('Berkas Bukti Pendukung')
		->set_icon('fa fa-files-o')
		->unset_field('id, id_keg, berkas_kak, tanggapan_kak') 
		->set_field
		(
			array
			(
				'berkas_kak'						=> 'files',
				'tanggapan_kak'						=> 'textarea',
				'berkas_rkbu'						=> 'files',
				'tanggapan_rkbu'					=> 'textarea',
				'berkas_rab'						=> 'files',
				'tanggapan_rab'						=> 'textarea',
				'berkas_gambar'						=> 'files',
				'tanggapan_gambar'					=> 'textarea'
			)
		)
		->field_position
		(
			array
			(
				'berkas_kak'						=> 1,
				'berkas_rkbu'						=> 1,
				'berkas_rab'						=> 1,
				'tanggapan_kak'						=> 2,
				'tanggapan_rkbu'					=> 2,
				'tanggapan_rab'						=> 2,
				'tanggapan_gambar'					=> 2
			)
		)
		->where('id_keg', $this->_primary)
		->set_default('id_keg', $this->_primary)
		->limit(1)
		->render($this->_table);
	}
}