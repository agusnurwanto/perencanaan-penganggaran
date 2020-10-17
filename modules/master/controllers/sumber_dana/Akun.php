<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Akun extends Aksara
{
	private $_table									= 'ref__sumber_dana_rek_1';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/sumber_dana/akun'							=> 'Akun'
			)
		);
		
		$this->set_title('Master Sumber Dana Akun')
		->set_icon('mdi mdi-access-point')
		->unset_column('id, tahun')
		->unset_field('id, tahun')
		->unset_view('id, tahun')
		->set_default('tahun', get_userdata('year'))
		//->merge_field('kd_urusan, nm_urusan')
		->field_size
		(
			array
			(
				'kd_sumber_dana_rek_1'				=> 'col-3',
				'uraian'							=> 'col-12'
			)
		)
		->set_field('uraian', 'textarea, hyperlink', 'master/sumber_dana/kelompok', array('id_sumber_dana_rek_1' => 'id'))
		->set_field
		(
			array
			(
				'kd_sumber_dana_rek_1'				=> 'last_insert',
			)
		)
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus'
			)
		)
		->set_alias
		(
			array
			(
				'kd_sumber_dana_rek_1'				=> 'Kode',
				'uraian'							=> 'Sumber Dana Akun'
			)
		)
		->set_validation
		(
			array
			(
				'kd_sumber_dana_rek_1'				=> 'required|is_unique[ref__sumber_dana_rek_1.kd_sumber_dana_rek_1.id.' . $this->input->get('id') . ']',
				'uraian'							=> 'required'
			)
		)
		->where('tahun', get_userdata('year'))
		->order_by('kd_sumber_dana_rek_1')
		->render($this->_table);
	}
}