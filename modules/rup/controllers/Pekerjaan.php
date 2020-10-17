<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pekerjaan extends Aksara
{
	private $_table									= 'ta__rup_pekerjaan';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('sub_kegiatan');
		if(!$this->_primary)
		{
			return throw_exception(301, 'Silakan Memilih Sub Kegiatan terlebih dahulu', go_to('../sub_kegiatan'));
		}
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$header										= $this->_header();
		$this->set_breadcrumb
		(
			array
			(
				'rup/sub'							=> 'sub_unit'
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
					' . (isset($header['nm_sub']) ?  $header['kd_sub'] . '. ' . $header['nm_sub'] : '-') . '
				</label>
			</div>
			<div class="row">
				<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Program
				</label>
				<label class="control-label col-sm-6 col-xs-8 text-sm text-uppercase no-margin">
					' . (isset($header['nm_program']) ?  $header['kd_sub'] . '.' . $header['kd_program'] . '. ' . $header['nm_program'] : '-') . '
				</label>
			</div>
			<div class="row">
				<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Kegiatan
				</label>
				<label class="control-label col-sm-6 col-xs-4 text-sm text-uppercase no-margin">
					' . (isset($header['nm_kegiatan']) ?  $header['kd_sub'] . '.' . $header['kd_program'] . '.' . $header['kd_kegiatan'] . '. ' . $header['nm_kegiatan'] : '-') . '
				</label>
			</div>		
			<div class="row">
				<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					PLAFON
				</label>
				<label class="col-sm-2 col-xs-8 text-sm text-muted text-uppercase no-margin">
					<b class="text-danger">
						RP. ' . number_format($header['pagu'], 2) . '
					</b>
				</label>	
			</div>
		')
	
		->set_title('Pekerjaan')
		->set_icon('fa fa-check-square-o')
		->column_order('no, pekerjaan, jenis_pekerjaan')
		->field_order('no, pekerjaan, jenis_pekerjaan')
		->view_order('no, pekerjaan, jenis_pekerjaan')
		->unset_action('print, export, pdf')
		->unset_column('id, id_keg')
		->unset_field('id, id_keg')
		->unset_view('id, id_keg')
		->add_class
		(
			array
			(
				'pekerjaan'							=> 'autofocus'
			)
		)
		->set_field
		(
			array
			(
				'no'								=> 'last_insert'
			)
		)
		->set_field
		(
			'pekerjaan',
			'hyperlink',
			'rup/rinci',
			array
			(
				'pekerjaan'							=> 'id'
			),
			true
		)
		->set_validation
		(
			array
			(
				'no'								=> 'required',
				'pekerjaan'							=> 'required',
				'jenis_pekerjaan'					=> 'required'
			)
		)
		->set_field
		(
			'jenis_pekerjaan',
			'radio',
			array
			(
				0									=> '<label class="label bg-navy">Tender</label>',
				1									=> '<label class="label bg-green">Seleksi</label>',
				2									=> '<label class="label bg-yellow">Pengadaan Langsung</label>',
				3									=> '<label class="label bg-red">Swakelola</label>',
				4									=> '<label class="label bg-blue">E-Purchasing</label>'
			)
		)
		->set_default
		(
			array
			(
				'id_keg'							=> $this->_primary
			)
		)
		->where
		(
			array
			(
				'id_keg'							=> $this->_primary
			)
		)
		->order_by('no')
		->render($this->_table); 
	}
	private function _header()
	{
		$query										= $this->model->select
		('
			ref__sub.kd_sub,
			ref__sub.nm_sub,
			ref__program.kd_program,
			ref__program.nm_program,
			ta__kegiatan.kd_keg AS kd_kegiatan,
			ta__kegiatan.kegiatan AS nm_kegiatan,
			ta__kegiatan.pagu
		')
		->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog', 'INNER')
		->join('ref__program', 'ref__program.id = ta__program.id_prog', 'INNER')
		->join('ref__sub', 'ref__sub.id = ta__program.id_sub', 'INNER')
		->get_where('ta__kegiatan', array('ta__kegiatan.id' => $this->_primary))
		->result_array();
		
		return $query[0];
	}
}