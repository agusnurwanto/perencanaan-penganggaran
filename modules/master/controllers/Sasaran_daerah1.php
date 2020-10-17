<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sasaran_daerah extends Aksara
{
	private $_table									= 'ref__sasaran_daerah';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_prioritas							= $this->input->get('id_prioritas');
		$this->_title								= '';//$this->select('uraian')->get_where('ref__prioritas_pembangunan', array('id' => $this->_prioritas), 1)->row('uraian');
		if($this->_title)
		{
			$this->_title							= 'uraian ' . $this->_title;
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
			if($this->_prioritas)
			{
				$this->set_default('id_kec', $this->_prioritas)
				->unset_column('id_prioritas')
				->unset_field('id_prioritas')
				->select('ref__prioritas_pembangunan.kode')
				->join('ref__prioritas_pembangunan', 'ref__prioritas_pembangunan.id = ref__sasaran_daerah.id_prioritas')
				->set_default('id_prioritas', $this->_prioritas)
				->where('id_prioritas', $this->_prioritas)
				;
			}
			else
			{
				$this->set_relation
				(
					'id_prioritas',
					'ref__prioritas_pembangunan.id',
					'{ref__prioritas_pembangunan.kode}. {ref__prioritas_pembangunan.uraian}'
				);
			}
			$this->set_breadcrumb
			(
				array
				(
					'master'							=> 'master',
					'prioritas_pembangunan'				=> 'Prioritas Pembangunan'
				)
			)
			->set_title('Sasaran Daerah ' . $this->_title)
			//->set_field('nama_kelurahan', 'hyperlink', 'master/rw', array('id_kel' => 'id'))
			//->set_field('kecamatan', 'hyperlink', 'master/kecamatan')
			->set_field('kode', 'last_insert')
			->unset_column('id')
			->unset_field('id')
			->unset_truncate('uraian')
			->column_order('kode_ref__prioritas_pembangunan, kecamatan')
			->merge_content('{kode_ref__prioritas_pembangunan}.{kode}', 'kode')
			->add_class
			(
				array
				(
					'id_prioritas'						=> 'trigger_kode',
					'kode'								=> 'kode_input',
					'uraian'							=> 'autofocus'
				)
			)
			->set_alias
			(
				array
				(
					'id_prioritas'						=> 'Prioritas Pembangunan',
					'uraian_ref__prioritas_pembangunan'	=> 'Prioritas Pembangunan'
				)
			)
			->set_validation
			(
				array
				(
					'kode'								=> 'required',
					'uraian'							=> 'required'
				)
			)
			->order_by
			(
				array
				(
					'ref__prioritas_pembangunan.kode'	=> 'ASC', 
					'ref__sasaran_daerah.kode'			=> 'ASC'
				)
			)
			/*->field_position
			(
				array
				(
					'nama_lurah'						=> 2,
					'nip_lurah'							=> 2,
					'jabatan_lurah'						=> 2
				)
			)*/
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ref__sasaran_daerah', array('id_prioritas' => $this->input->post('isu')), 1)->row('kode');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}