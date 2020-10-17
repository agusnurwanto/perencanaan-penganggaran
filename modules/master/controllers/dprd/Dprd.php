<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dprd extends Aksara
{
	private $_table									= 'ref__dprd';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_fraksi								= $this->input->get('id_fraksi');
		$this->_title								='';// $this->select('nama_fraksi')->get_where('ref__dprd_fraksi', array('id' => $this->_fraksi), 1)->row('nama_fraksi');
		if($this->_title)
		{
			$this->_title							= 'nama_fraksi ' . $this->_title;
		}
	}
	
	public function index()
	{
		if($this->_fraksi)
		{
			$this
			->set_default('id_fraksi', $this->_fraksi)
			->where('id_fraksi', $this->_fraksi)
			->unset_column('id_fraksi')
			->unset_field('id_fraksi')
			->join('ref__dprd_fraksi', 'ref__dprd_fraksi.id = ref__dprd.id_fraksi');
		}
		else
		{
			$this->set_relation
			(
				'id_fraksi',
				'ref__dprd_fraksi.id',
				'{ref__dprd_fraksi.kode}. {ref__dprd_fraksi.nama_fraksi}'
			);
		}
		
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_breadcrumb
			(
				array
				(
					'master'							=> phrase('master'),
					'id_fraksi'							=> phrase('Fraksi')
				)
			)
			->set_title('Data DRPD ' . $this->_title)
			->set_field('kode', 'last_insert')
			->unset_column('id')
			->unset_field('id')
			->column_order('kode_ref__dprd_fraksi, nama_dewan, jabatan_dewan, maksimal_usulan')
			->field_order('id_fraksi, kode, nama_dewan, jabatan_dewan, maksimal_usulan')
			->merge_content('{kode_ref__dprd_fraksi}.{kode}', phrase('kode'))
			->add_class
			(
				array
				(
					'id_fraksi'							=> 'trigger_kode',
					'kode'								=> 'kode_input',
					'nama_dewan'						=> 'autofocus'
				)
			)
			->set_alias
			(
				array
				(
					'id_fraksi'							=> phrase('Fraksi')
				)
			)
			->set_field
			(
				array
				(
					'pagu'								=> 'number_format'
				)
			)
			->order_by
			(
				array
				(
					'ref__dprd_fraksi.kode'				=> 'ASC',
					'ref__dprd.kode'					=> 'ASC'
				)
			)
			->set_validation
			(
				array
				(
					'id_fraksi'							=> 'required',
					'kode'								=> 'required',
					'nama_dewan'						=> 'required',
					'pagu'								=> 'required|numeric'
				)
			)
			->field_position
			(
				array
				(
					'nama_dewan'						=> 2,
					'jabatan_dewan'						=> 2,
					'pagu'								=> 2
				)
			)
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ref__dprd', array('id_fraksi' => $this->input->post('isu')), 1)->row('kode');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}