<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Urusan extends Aksara
{
	private $_table									= 'ref__urusan';
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
				'master/data/urusan'							=> phrase('urusan')
			)
		);
		
		$this->set_title('Master Urusan')
		->set_icon('mdi mdi-access-point')
		->unset_column('id, tahun')
		->unset_field('id, tahun')
		->unset_view('id, tahun')
		->unset_truncate('nm_urusan')
		->set_default('tahun', get_userdata('year'))
		//->merge_field('kd_urusan, nm_urusan')
		->field_size
		(
			array
			(
				'kd_urusan'							=> 'col-3',
				'nm_urusan'							=> 'col-12'
			)
		)
		->set_field('nm_urusan', 'textarea, hyperlink', 'master/data/bidang', array('urusan' => 'id'), null, null, null, 'Urusan')
		->set_field
		(
			array
			(
				'kd_urusan'							=> 'last_insert',
			)
		)
		->add_class
		(
			array
			(
				'nm_urusan'							=> 'autofocus'
			)
		)
		->set_alias
		(
			array
			(
				'kd_urusan'							=> 'Kode',
				'nm_urusan'							=> 'Urusan'
			)
		)
		->set_validation
		(
			array
			(
				'kd_urusan'							=> 'required|is_unique[ref__urusan.kd_urusan.id.' . $this->input->get('id') . ']',
				'nm_urusan'							=> 'required'
			)
		)
		->where('tahun', get_userdata('year'))
		->order_by('kd_urusan')
		->render($this->_table);
	}
}