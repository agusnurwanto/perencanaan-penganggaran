<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Visi extends Aksara
{
	private $_table									= 'ta__rpjmd_visi';
	function __construct()
	{
		parent::__construct();
		$this->set_primary('tahun');
		$this->set_permission();
		$this->set_theme('backend');
		$this->parent_module('rpjmd/visi');
		$this->insert_on_update_fail(true);
	}
	
	public function index()
	{
		$this
		->set_method('update')
		->set_title('Visi RPJMD')
		->set_icon('mdi mdi-nintendo-switch')
		->unset_view('id')
		->unset_column('id')
		->unset_field('id')
		//->set_field('visi', 'hyperlink', 'rpjmd/misi', array('visi' => 'id', 'per_page' => null))
		->set_field
		(
			'tahun_ke',
			'dropdown',
			array
			(
				1									=> 1,
				2									=> 2,
				3									=> 3,
				4									=> 4,
				5									=> 5
			)
		)
		->set_field
		(
			array
			(
				'visi'								=> 'textarea',
				'tahun'								=> 'disabled'
			)
		)
		->set_validation
		(
			array
			(
				'tahun_awal'						=> 'required|numeric',
				'tahun_akhir'						=> 'required|numeric',
				'tahun'								=> 'required|numeric',
				'tahun_ke'							=> 'required|numeric',
				'visi'								=> 'required'
			)
		)
		->merge_field('tahun_awal, tahun_akhir')
		->merge_field('tahun, tahun_ke')
		->field_position
		(
			array
			(
				'tahun'								=> 2,
				'tahun_ke'							=> 2
			)
		)
		->set_default
		(
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->where
		(
			array
			(
				'tahun'								=> get_userdata('year')
			)
		)
		->render($this->_table); 
	}
}