<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dprd extends Aksara
{
	private $_table									= 'ref__dprd';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_id_sub								= (get_userdata('sub_unit') ? get_userdata('sub_unit') : $this->input->get('id_sub'));
		if($this->_id_sub)
		{
			generateMessages(301, null, 'data');
		}
	}
	
	public function index()
	{
		$this->set_title('Silakan pilih Dewan')
		->set_icon('fa fa-user')
		->unset_action('create, read, update, delete, export, print, pdf')
		->unset_column('id, pagu')
		->set_field('nama_dewan', 'hyperlink', 'reses/dprd/data', array('id_sub' => 'id'))
		->set_relation
		(
			'id_fraksi',
			'ref__dprd_fraksi.id',
			'{ref__dprd_fraksi.kode AS kode_fraksi}. {ref__dprd_fraksi.nama_fraksi}'
		)
		->merge_content('{kode_fraksi}.{kode}')
		->column_order('kode_fraksi, nama_dewan, jabatan_dewan, nama_fraksi')
		->render($this->_table);
	}
}