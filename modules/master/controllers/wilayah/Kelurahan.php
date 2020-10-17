<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kelurahan extends Aksara
{
	private $_table									= 'ref__kelurahan';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_kecamatan							= $this->input->get('id_kec');
		$this->_title								= '';//$this->select('kecamatan')->get_where('ref__kecamatan', array('id' => $this->_kecamatan), 1)->row('kecamatan');
		
		if($this->_title)
		{
			$this->_title							= 'Kecamatan ' . $this->_title;
		}
		
	}
	
	public function index()
	{
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			if($this->_kecamatan)
			{
				$this
				->set_default('id_kec', $this->_kecamatan)
				->where('id_kec', $this->_kecamatan)
				->unset_column('id_kec')
				->unset_field('id_kec')
				->join('ref__kecamatan', 'ref__kecamatan.id = ref__kelurahan.id_kec')
				;
			}
			else
			{
				$this->set_relation
				(
					'id_kec',
					'ref__kecamatan.id',
					'{ref__kecamatan.kode}. {ref__kecamatan.kecamatan}'
				);
			}
			$this->set_breadcrumb
			(
				array
				(
					'master'							=> phrase('master'),
					'kecamatan'							=> phrase('kecamatan')
				)
			)
			->set_title('Data Kelurahan ' . $this->_title)
			->set_field('nama_kelurahan', 'hyperlink', 'master/wilayah/rw', array('id_kel' => 'id'))
			->set_field('kecamatan', 'hyperlink', 'kecamatan')
			->set_field('kode', 'last_insert')
			->unset_column('id, singkat_kelurahan')
			->unset_field('id')
			->column_order('kode_ref__kecamatan, kecamatan')
			->merge_content('{kode_ref__kecamatan}.{kode}', 'kode')
			->add_class
			(
				array
				(
					'id_kec'							=> 'trigger_kode',
					'kode'								=> 'kode_input',
					'nama_kelurahan'					=> 'autofocus'
				)
			)
			->set_alias
			(
				array
				(
					'id_kec'							=> phrase('kecamatan')
				)
			)
			->set_validation
			(
				array
				(
					'kode'								=> 'required',
					'nama_kelurahan'					=> 'required'
				)
			)
			->order_by
			(
				array
				(
					'ref__kecamatan.kode'				=> 'ASC', 
					'ref__kelurahan.kode'				=> 'ASC'
				)
			)
			->field_position
			(
				array
				(
					'nama_lurah'						=> 2,
					'nip_lurah'							=> 2,
					'jabatan_lurah'						=> 2
				)
			)
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ref__kelurahan', array('id_kec' => $this->input->post('isu')), 1)->row('kode');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}