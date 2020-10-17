<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model extends Aksara
{
	private $_table									= 'ta__model';
	
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title(phrase('model'))
			->set_icon('fa fa-clipboard')
			->unset_column('id, tahun')
			->unset_field('id, tahun')
			->unset_view('id, tahun')
			->unset_action('export, print, pdf')
			->set_field
			(
				array
				(
					'kd_model'							=> 'last_insert',
					'desc'								=> 'textarea'
				)
			)
			->set_alias
			(
				array
				(
					'keterangan'						=> 'ket',
					'desc'								=> 'Keterangan',
					'nm_model'							=> 'Nama Model'
				)
			)
			->add_class
			(
				array
				(
					'id_isu'							=> 'trigger_kode',
					'kd_model'							=> 'kode_input',
					'nm_model'							=> 'autofocus'
				)
			)
			->add_action('option', 'variabel', 'Variabel', 'btn-primary ajaxLoad', 'mdi mdi-barcode-scan', array('id_model' => 'id'))
			->add_action('option', 'belanja', 'Belanja', 'btn-info ajaxLoad', 'mdi mdi-shopping', array('id_model' => 'id'))
			->add_action('option', 'indikator', 'Indikator', 'btn-success ajaxLoad', 'mdi mdi-information-outline', array('id_model' => 'id'))
			->add_action('option', 'kelog', 'Kerangka Kerja Logis', 'btn-warning ajaxLoad', 'mdi mdi-file-document', array('id_model' => 'id'))
			->add_action('option', 'leker', 'Lembar Kerja', 'btn-info ajaxLoad', 'mdi mdi-comment-alert-outline', array('id_model' => 'id'))
			->add_action('option', 'rab', 'RAB', 'btn-primary ajaxLoad', 'mdi mdi-floor-plan', array('id_model' => 'id'))
			->add_action('option', 'cetak_model', 'Cetak Model', 'btn-success', 'mdi mdi-printer', array('id_model' => 'id'), true)
			->where('tahun', get_userdata('year'))
			->set_default('tahun', get_userdata('year'))		
			->set_relation
			(
				'id_isu',
				'ta__model_isu.id',
				'{ta__model_isu.kode}. {ta__model_isu.nama_isu}',
				null,
				null,
				array
				(
					'ta__model_isu.kode'			=> 'ASC'
				)
			)
			->merge_content('{kode}.{kd_model}', phrase('kode'))
			->column_order('kode, nama_isu, nm_model, desc')
			->field_order('id_isu, kd_model, nm_model, desc')
			->set_alias
			(
				array
				(
					'id_isu'						=> 'Isu'
				)
			)
			->set_field
			(
				array
				(
					'desc'							=> 'textarea'
				)
			)
			->order_by
			(
				array
				(
					'ta__model_isu.kode'			=> 'ASC',
					'ta__model.kd_model'			=> 'ASC'
				)
			)
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kd_model')->get_where('ta__model', array('id_isu' => $this->input->post('isu')), 1)->row('kd_model');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}