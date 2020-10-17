<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rinci extends Aksara
{
	private $_table									= 'ta__rup_rekening';
	
	function __construct()
	{
		
		parent::__construct();
		$this->_primary								= $this->input->get('pekerjaan');
		$this->id_keg								= $this->input->get('id_keg');
		if(!$this->_primary)
		{
			return generateMessages(301, phrase('silakan_memilih_pekerjaan_terlebih_dahulu'), go_to('../pekerjaan'));
		}
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$header										= $this->_header();
		//print_r($header);exit;
		$this->set_breadcrumb
		(
			array
			(
				'rup/pekerjaan'							=> 'pekerjaan'
			)
		);
		
	/*	$anggaran							= $this->model
											->select('ref__sub.id')
											->select_sum('pagu')
											->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog')
											->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
											->get_where('ta__kegiatan', array('ref__sub.id_unit' => $this->_id_unit))
											->row();
											*/
		
		$this
		->set_description
		('
			<div class="row">
				<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Sub Unit
				</label>
				<label class="control-label col-sm-6 col-xs-8 text-sm text-uppercase no-margin">
					' . (isset($header->nm_sub) ? $header->kd_sub . '. ' . $header->nm_sub : '-') . '
				</label>
			</div>
			<div class="row">
				<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Program
				</label>
				<label class="control-label col-sm-6 col-xs-8 text-sm text-uppercase no-margin">
					' . (isset($header->nm_program) ?  $header->kd_sub . '.' . $header->kd_program . '. ' . $header->nm_program : '-') . '
				</label>
			</div>
			<div class="row">
				<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Kegiatan
				</label>
				<label class="control-label col-sm-6 col-xs-4 text-sm text-uppercase no-margin">
					' . (isset($header->nm_kegiatan) ?  $header->kd_sub . '.' . $header->kd_program . '.' . $header->kd_kegiatan . '. ' . $header->nm_kegiatan : '-') . '
				</label>
			</div>		
			<div class="row">
				<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					PLAFON
				</label>
				<label class="col-sm-2 col-xs-8 text-sm text-muted text-uppercase no-margin">
					<b class="text-danger">
						RP. ' . number_format($header->pagu, 2) . '
					</b>
				</label>	
			</div>
			<div class="row">
				<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					PEKERJAAN
				</label>
				<label class="control-label col-sm-6 col-xs-4 text-sm text-uppercase no-margin">
					' . (isset($header->pekerjaan) ? $header->pekerjaan : '-') . '
				</label>
			</div>		
		')
		->set_title('Rincian Pekerjaan')
		->set_icon('fa fa-check-square-o')
		->column_order('kd_rek_1, nilai')
		->field_order('id_belanja, nilai')
		->view_order('id_belanja, nilai')
		->unset_action('print, export, pdf')
		->unset_column('id, id_pekerjaan')
		->unset_field('id, id_pekerjaan')
		->unset_view('id, id_pekerjaan')
		->merge_content('{kd_rek_1}.{kd_rek_2}.{kd_rek_3}.{kd_rek_4}.{kd_rek_5} {uraian}', 'rekening')
		->set_field
		(
			array
			(
				'nilai'								=> 'price_format'
			)
		)
		->set_default
		(
			array
			(
				'id_pekerjaan'							=> $this->_primary
			)
		)
		->where
		(
			array
			(
				'id_pekerjaan'							=> $this->_primary
			)
		)
		->set_relation
		(
			'id_belanja',
			'ta__belanja.id',
			'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5} {ref__rek_5.uraian}',
			array
			(
				'ta__belanja.id_keg'					=> $this->id_keg
			),
			array
			(
				array
				(
					'ref__rek_5',
					'ref__rek_5.id = ta__belanja.id_rek_5'
				),
				array
				(
					'ref__rek_4',
					'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
				),
				array
				(
					'ref__rek_3',
					'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
				),
				array
				(
					'ref__rek_2',
					'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
				),
				array
				(
					'ref__rek_1',
					'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
				)
			),
			'ref__rek_5.kd_rek_5'
		)
		//->order_by('no')
		->render($this->_table); 
	}
		
	private function _header()
	{
		$query										= $this->model->query
		('
			SELECT
				ref__sub.kd_sub,
				ref__sub.nm_sub,
				ref__program.kd_program,
				ta__kegiatan.kd_keg AS kd_kegiatan,
				ta__kegiatan.pagu,
				ref__program.nm_program,
				ta__kegiatan.kegiatan AS nm_kegiatan,
				ta__rup_pekerjaan.pekerjaan
			FROM
				ta__rup_pekerjaan
			INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__rup_pekerjaan.id_keg
			INNER JOIN ta__program ON ta__program.id = ta__kegiatan.id_prog
			INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
			INNER JOIN ref__sub ON ref__sub.id = ta__program.id_sub
			WHERE
				ta__rup_pekerjaan.id = ' . $this->_primary . '
		')
		->row();
		
		return $query;
	}
}