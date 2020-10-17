<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Indikator extends Aksara
{
	private $_table									= 'ta__rpjmd_program_indikator_sasaran';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= (get_userdata('id_sub') ? get_userdata('id_sub') : $this->input->get('id_sub'));
	}
	
	public function index()
	{
		$info										= $this->model
													->select('ref__sub.nm_sub, ref__program.nm_program')
													->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
													->join('ref__program', 'ref__program.id = ta__program.id_sub')
													->get_where('ta__program', array('ta__program.id' => $this->input->get('id_prog')), 1)
													->row();
		//print_r($info);exit;
		//$this->db->last_query();
		$this->set_description
		('
			<div class="row">
				<div class="col-sm-10">
					<div class="row">
						<label class="control-label col-md-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Sub Unit
						</label>
						<label class="control-label col-md-10  col-xs-8 text-sm text-uppercase no-margin">
							' . $info->nm_sub . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-md-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
							Program
						</label>
						<label class="control-label col-md-10  col-xs-8 text-sm text-uppercase no-margin">
							' . $info->nm_program . '
						</label>
					</div>
				</div>
			</div>
		');
		$this->set_breadcrumb
		(
			array
			(
				'rpjmd/program'								=> phrase('program')
			)
		)
		->set_title('Keterkaitan Antara Program dan Indikator Sasaran')
		->unset_view('id')
		->unset_column('id, id_prog')
		->unset_field('id, id_prog')
		->set_default
		(
			array
			(
				'id_prog'							=> $this->input->get('id_prog')
			)
		)
		->set_relation
		(
			'id_indikator_sasaran',
			'ta__rpjmd_indikator_sasaran.id',
			'{ta__rpjmd_sasaran.kode}. {ta__rpjmd_sasaran.sasaran} - {ta__rpjmd_indikator_sasaran.satuan}',
			null,
			array
			(
				array
				(
					'ta__rpjmd_sasaran',
					'ta__rpjmd_sasaran.id = ta__rpjmd_indikator_sasaran.id_sasaran'
				)
			),
			array
			(
				'ta__rpjmd_sasaran.kode'						=> 'ASC'
			)
		)
		->set_alias
		(
			array
			(
				'id_indikator_sasaran'					=> 'Indikator Sasaran'
			)
		)
		->where
		(
			array
			(
				'id_prog'							=> $this->input->get('id_prog')
			)
		)
		//->order_by('kode')
		->render($this->_table); 
	}
}