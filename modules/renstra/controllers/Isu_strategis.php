<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Isu_strategis extends Aksara
{
	private $_table									= 'ta__rkpd_isu_strategis';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= (get_userdata('id_sub') ? get_userdata('id_sub') : $this->input->get('id_sub'));
	}
	
	public function index()
	{
		$this
		->set_breadcrumb
		(
			array
			(
				'rpjmd/visi'						=> 'RPJMD'
			)
		)
		->set_title('Isu Strategis')
		->add_class
		(
			array
			(
				'isu_strategis'						=> 'autofocus'
			)
		)
		->set_field('isu_strategis', 'hyperlink', 'renstra/prioritas_pembangunan', array('isu_strategis' => 'id'))
		->set_field('kode', 'last_insert')
		->unset_view('id')
		->unset_column('id')
		->unset_field('id')
		//->merge_content('{kd_urusan}.{kd_bidang}.{kd_unit}.{kd_sub}.{kd_program}.{kd_id_prog}', 'Kode')
		//->column_order('kd_urusan, nm_program')
		//->field_order('id_sub, id_prog')
		/*->set_default
		(
			array
			(
				'tahun'							=> get_userdata('year')
			)
		)
		->set_alias
		(
			array
			(
				'nm_program'						=> 'Nama Program'
			)
		)
		->set_relation
		(
			'id_sub',
			'ref__sub.id',
			'{ref__unit.kd_unit}.{ref__sub.kd_sub} {ref__sub.nm_sub}',
			null,
			array
			(
				array
				(
					'ref__unit',
					'ref__unit.id = ref__sub.id_unit'
				)
			),
			array
			(
				'ref__unit.kd_unit'								=> 'ASC',
				'ref__sub.kd_sub'								=> 'ASC'
			)
		)
		->set_relation
		(
			'id_prog',
			'ref__program.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__program.kd_program} {ref__program.nm_program}',
			null,
			array
			(
				array
				(
					'ref__bidang',
					'ref__bidang.id = ref__program.id_bidang'
				),
				array
				(
					'ref__urusan',
					'ref__urusan.id = ref__bidang.id_urusan'
				)
			),
			array
			(
				'ref__urusan.kd_urusan'							=> 'ASC',
				'ref__bidang.kd_bidang'							=> 'ASC',
				'ref__program.kd_program'						=> 'ASC'
			)
		)*/
		//->order_by('kode')
		->render($this->_table); 
	}
}