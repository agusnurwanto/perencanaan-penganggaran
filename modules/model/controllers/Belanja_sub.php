<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Belanja_sub extends Aksara
{
	private $_table									= 'ta__model_belanja_rinci';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('belanja_sub');
		$this->_title								= phrase('sub_belanja') . ' ' . $this->model->select('uraian')->get_where('ta__model_belanja_sub', array('id' => $this->_primary), 1)->row('uraian');
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'model'								=> 'Model',
				'belanja'							=> 'Rekening',
				'../rekening'						=> 'Rincian Sub'
			)
		)
		->set_title($this->_title)
		->set_icon('mdi mdi-shuffle-variant')
		->unset_column('id, id_belanja_sub')
		->unset_field('id, id_belanja_sub')
		->unset_view('id')
		->unset_action('export, print, pdf')
		->set_field('kd_belanja_rinci', 'last_insert')
		->set_field('uraian', 'textarea')
		->set_field('satuan_123', 'disabled')
		->column_order('kd_belanja_rinci, uraian, vol_1, satuan_1, vol_2, satuan_2, vol_3, satuan_3, nilai, satuan_123')
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus',
				'nilai'								=> 'sum_field',
				'vol_1'								=> 'sum_field vol_1',
				'vol_2'								=> 'sum_field vol_2',
				'vol_3'								=> 'sum_field vol_3',
				'satuan_1'							=> 'satuan satuan_1',
				'satuan_2'							=> 'satuan satuan_2',
				'satuan_3'							=> 'satuan satuan_3',
				'satuan_123'						=> 'satuan_total'
			)
		)
		->set_alias
		(
			array
			(
				'kd_belanja_rinci'					=> 'Kode'
			)
		)
		->set_validation
		(
			array
			(
				'kode'								=> 'trim',
				'satuan_123'						=> 'xss_clean'
			)
		)
		->set_default('id_belanja_sub', $this->_primary)
		->where('id_belanja_sub', $this->_primary)
		->order_by('kd_belanja_rinci')
		
		->set_output
		(
			array
			(
				'result'							=> $this->_result(),
				'variable'							=> $this->_variable()
			)
		)
		->render($this->_table);
	}
	
	private function _result()
	{
		if('create' == $this->_method)
		{
			$query									= $this->model->select
			('
				ta__model_variabel.id,
				ta__model_variabel.nm_variabel
			')
			->join
			(
				'ta__model_belanja',
				'ta__model_belanja.id = ta__model_belanja_sub.id_belanja'
			)
			->join
			(
				'ta__model_variabel',
				'ta__model_variabel.id_model = ta__model_belanja.id_model'
			)
			->get_where
			(
				'ta__model_belanja_sub',
				array
				(
					'ta__model_belanja_sub.id'		=> $this->input->get('id')
				)
			)
			->result();
		}
		else
		{
			$query									= $this->model->select
			('
				ta__model_variabel.id,
				ta__model_variabel.nm_variabel
			')
			->join
			(
				'ta__model_belanja_sub',
				'ta__model_belanja_sub.id = ta__model_belanja_rinci.id_belanja_rinc'
			)
			->join
			(
				'ta__model_belanja',
				'ta__model_belanja.id = ta__model_belanja_sub.id_belanja'
			)
			->join
			(
				'ta__model_variabel',
				'ta__model_variabel.id_model = ta__model_belanja.id_model'
			)
			->get_where
			(
				'ta__model_belanja_rinci',
				array
				(
					'ta__model_belanja_rinci.id'	=> $this->input->get('id')
				)
			)
			->result();
		}
		
		return $query;
	}
	
	private function _variable()
	{
		$query										= $this->model->select
		('
			ta__model_variabel.kd_variabel,
			ta__model_variabel.nm_variabel,
			ta__model_variabel.id
		')
		->join
		(
			'ta__model',
			'ta__model.id = ta__model_variabel.id_model',
			'INNER'
		)
		->join
		(
			'ta__model_belanja',
			'ta__model_belanja.id_model = ta__model.id',
			'INNER'
		)
		->join
		(
			'ta__model_belanja_rinci',
			'ta__model_belanja_rinci.id_belanja = ta__model_belanja.id',
			'INNER'
		)
		->order_by('ta__model_variabel.kd_variabel', 'ASC')
		->get_where
		(
			'ta__model_variabel',
			array
			(
				'ta__model_belanja_rinci.id'		=> $this->input->get('id_belanja_rinc')
			)
		)
		->result();
		
		return $query;
	}
}
