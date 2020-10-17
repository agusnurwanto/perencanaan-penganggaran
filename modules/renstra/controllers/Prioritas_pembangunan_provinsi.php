<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Prioritas_pembangunan_provinsi extends Aksara
{
	private $_table									= 'ta__rkpd_prioritas_pembangunan_provinsi'; 
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('isu_strategis');
	}
	
	public function index()
	{
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			if(isset($this->_primary))
			{
				$header								= $this->_header();
				$this
				->set_description
				('
					<div class="row">
						<div class="col-4 col-sm-2 text-muted text-sm">
							Isu Strategis
						</div>
						<div class="col-8 col-sm-6 font-weight text-sm">
							' . (isset($header->isu_strategis) ?  $header->kode . '. ' . $header->isu_strategis : '-') . '
						</div>
					</div>
				')
				->unset_field('id, id_isu_strategis')
				->where
				(
					array
					(
						'id_isu_strategis'				=> $this->_primary
					)
				)
				->set_default
				(
					array
					(
						'id_isu_strategis'				=> $this->_primary
					)
				);
			}
			else
			{
				$this
				->unset_field('id');
			}
			$this->set_breadcrumb
			(
				array
				(
					'renstra/isu_strategis'				=> 'Isu Strategis'
				)
			)
			->set_title('Prioritas Pembangunan Provinsi')
			->set_field
			(
				array
				(
					'prioritas_pembangunan'				=> 'textarea'
				)
			)
			//->set_field('nm_program', 'hyperlink', 'rpjmd/capaian', array('id_prog' => 'id'))
			//->set_field('kecamatan', 'hyperlink', 'master/kecamatan')*/
			->set_field('kode', 'last_insert')
			->unset_view('id')
			->unset_column('id')
			->unset_truncate('prioritas_pembangunan')
			->merge_content('{kode_isu_strategis}.{kode}', 'Kode')
			->column_order('kode_isu_strategis, isu_strategis, prioritas_pembangunan')
			->field_order('id_isu_strategis, kode, prioritas_pembangunan')
			->set_alias
			(
				array
				(
					'id_isu_strategis'				=> 'Isu Strategis'
				)
			)
			->add_class
			(
				array
				(
					'id_isu_strategis'				=> 'trigger_kode',
					'kode'							=> 'kode_input',
					'prioritas_pembangunan'			=> 'autofocus'
				)
			)
			->set_relation
			(
				'id_isu_strategis',
				'ta__rkpd_isu_strategis.id',
				'{ta__rkpd_isu_strategis.kode AS kode_isu_strategis}. {ta__rkpd_isu_strategis.isu_strategis}'
			)
			->set_validation
			(
				array
				(
					'id_isu_strategis'				=> 'required',
					'kode'							=> 'required',
					'prioritas_pembangunan'			=> 'required'
				)
			)
			->order_by
			(
				array
				(
					'ta__rkpd_isu_strategis.kode'	=> 'ASC',
					'kode'							=> 'ASC'
				)
			)
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where($this->_table, array('id_isu_strategis' => $this->input->post('isu')), 1)->row('kode');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
	
	private function _header()
	{
		$query										= $this->model->query
		('
			SELECT
				ta__rkpd_isu_strategis.kode,
				ta__rkpd_isu_strategis.isu_strategis
			FROM
				ta__rkpd_isu_strategis
			WHERE
				ta__rkpd_isu_strategis.id = ' . $this->_primary . '
			LIMIT 1
		')
		->row();
		return $query;
	}
}