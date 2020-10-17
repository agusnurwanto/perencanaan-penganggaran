<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pengajuan_standar_harga extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->parent_module('anggaran/rincian');
		$this->set_theme('backend');
		$this->set_permission();
		
	}
	
	public function index()
	{
		$id_rek_6									= $this->model->select('id_rek_6')->get_where('ta__belanja', array('id' => $this->input->get('belanja')))->row('id_rek_6');
		
		$this->set_method('create')
		->set_primary('id')
		->set_upload_path('verifikasi_standar_harga')
		->unset_field('id, tahun, id_unit, approved_time, approved_by, approve, dilihat, alasan')
		->field_order('id_rek_6, flag, uraian, deskripsi, nilai, satuan_1, satuan_2, satuan_3, images, url')
		->add_class('uraian', 'autofocus')
		->set_field
		(
			array
			(
				'uraian'							=> 'textarea',
				'nilai'								=> 'number_format',
				'deskripsi'							=> 'textarea',
				'url'								=> 'textarea',
				'images'							=> 'files'
			)
		)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> 'SHT',
				1									=> 'SBM'
			)
		)
		->set_default
		(
			array
			(
				'tahun'								=> get_userdata('year'),
				'id_unit'							=> get_userdata('sub_unit')
			)
		)
		->default_value
		(
			array
			(
				'id_rek_6'							=> ($id_rek_6 ? $id_rek_6 : 0),
				'uraian'							=> $this->input->get('u'),
				'nilai'								=> $this->input->get('n'),
				'satuan_1'							=> $this->input->get('s1'),
				'satuan_2'							=> $this->input->get('s2'),
				'satuan_3'							=> $this->input->get('s3')
			)
		)
		->set_validation
		(
			array
			(
				'uraian'							=> 'required|is_unique[ref__standar_harga.uraian.nilai.' . $this->input->post('nilai') . ']',
				'id_rek_6'							=> 'required|trim',
				'nilai'								=> 'required|trim',
				'satuan_1'							=> 'required|trim',
				'flag'								=> 'required|trim',
				'deskripsi'							=> 'required|trim'
			)
		)
		->set_relation
		(
			'id_rek_6',
			'ref__rek_6.id',
			'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5}.{ref__rek_6.kd_rek_6} {ref__rek_6.uraian}',
			NULL,
			array
			(
				array
				(
					'ref__rek_5',
					'ref__rek_5.id = ref__rek_6.id_ref_rek_5'
				),
				array
				(
					'ref__rek_4',
					'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
				),
				array
				(
					'ref__rek_3',
					'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
				),
				array
				(
					'ref__rek_2',
					'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
				),
				array
				(
					'ref__rek_1',
					'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
				)
			),
			array
			(
				'ref__rek_1.kd_rek_1'			=> 'ASC',
				'ref__rek_2.kd_rek_2'			=> 'ASC',
				'ref__rek_3.kd_rek_3'			=> 'ASC',
				'ref__rek_4.kd_rek_4'			=> 'ASC',
				'ref__rek_5.kd_rek_5'			=> 'ASC',
				'ref__rek_6.kd_rek_6'			=> 'ASC'
			)
		)
		->set_alias
		(
			array
			(
				'satuan_1'							=> 'Satuan',
				'flag'								=> 'Jenis Usulan',
				'id_rek_6'							=> 'Rekening'
			)
		)
		->field_position
		(
			array
			(
				'nilai'								=> 2,
				'satuan_1'							=> 2,
				'satuan_2'							=> 2,
				'satuan_3'							=> 2,
				'images'							=> 3,
				'url'								=> 3,
				'flag'								=> 3
			)
		)
		->render('ref__standar_harga');
	}
	
	public function after_update()
	{
		$prepare									= array
		(
			'approved_time'							=> date('Y-m-d H:i:s'),
			'approved_by'							=> get_userdata('user_id')
		);
		$this->model->update('ref__standar_harga', $prepare, array('id' => $this->input->get('id')), 1);
	}
}