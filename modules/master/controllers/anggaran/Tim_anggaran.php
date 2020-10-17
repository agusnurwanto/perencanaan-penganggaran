<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tim_anggaran extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
		
		$this->set_upload_path('anggaran');
	}
	
	public function index()
	{
		$this->set_title('Master Tim Anggaran')
		->set_icon('fa fa-comment-o')
		->unset_column('id, tahun')
		->unset_field('id, tahun')
		->unset_view('id, tahun')
		->unset_truncate('jabatan_tim')
		->column_order('kode, nama_tim, jabatan_tim, nip_tim, opd, ttd')
		->field_order('kode, jabatan_tim, nama_tim, nip_tim, opd, ttd')
		->set_alias 
			(
				array
				(
					'opd'							=> 'SKPD',
					'nip_tim'						=> 'NIP Tim',
					'ttd'							=> 'TTD'
				)
			)
		
		->set_field
		(
			array
			(
				'kode'								=> 'last_insert',
				'nama_tim'							=> 'textarea',
				'jabatan_tim'						=> 'textarea',
				'ttd'								=> 'image',
				'status'							=> 'boolean'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'required',
				'nama_tim'							=> 'required',
				'jabatan_tim'						=> 'required',
				'nip_tim'							=> 'required',
				'opd'								=> 'required'
			)
		)
		->add_class
		(
			array
			(
				'nama_tim'							=> 'autofocus'
			)
		)
		->set_default
		(
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->field_position
		(
			array
			(
				'opd'								=> 2,
				'ttd'								=> 2
			)
		)
		->order_by('kode', 'ASC')
		->render('ref__tim_anggaran');
	}
}