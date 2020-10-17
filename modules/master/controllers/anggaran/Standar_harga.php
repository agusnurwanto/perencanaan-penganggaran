<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Standar_harga extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_upload_path('verifikasi_standar_harga')
		->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_title('Master Standar Harga')
		->set_icon('fa fa-comment-o')
		->unset_column('id, tahun')
		->unset_field('id, tahun')
		->unset_view('id, tahun')
		->column_order('uraian, nilai, satuan')
		->field_order('uraian, nilai, satuan')
		->set_field
		(
			array
			(
				'uraian'							=> 'textarea'
			)
		)
		->set_field('nilai', 'number_format', 2)
		->set_validation
		(
			array
			(
				'uraian'							=> 'required',
				'nilai'								=> 'required',
				'satuan'							=> 'required',
				'satuan_1'							=> 'required',
				'deskripsi'							=> 'required'
			)
		)
		->set_field
		(
			'approve',
			'radio',
			array
			(
				0									=> '<label class="label bg-yellow">Belum Disetujui</label>',
				1									=> '<label class="label bg-green">Disetujui</label>',
				2									=> '<label class="label bg-red">Ditolak</label>'
			)
		)
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus'
			)
		)
		->order_by
		(
			array
			(
				'approve'							=> 'ASC',
				'uraian'							=> 'ASC'				
			)
		)
		->render('ref__standar_harga');
	}
}