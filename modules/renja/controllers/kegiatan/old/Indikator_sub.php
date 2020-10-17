<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Indikator_sub extends Aksara
{
	private $_table									= 'ta__indikator_sub';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_keg_sub');
		$this->_title								= phrase('indikator_sub');
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
				'renja/kegiatan/sub_kegiatan'				=> 'kegiatan_sub'
			)
		);
		$this->set_title('Indikator Sub Kegiatan')
		->set_description('
			<div class="row">
				<div class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Sub Unit
				</div>
				<div class="control-label col-sm-6  col-xs-8 text-sm text-uppercase no-margin">
					' . (isset($header['nm_sub']) ?  $header['kd_sub'] . '. ' . $header['nm_sub'] : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Program
				</div>
				<div class="control-label col-sm-6  col-xs-8 text-sm text-uppercase no-margin">
					' . (isset($header['nm_program']) ?  $header['kd_sub'] . '.' . $header['kd_program'] . '. ' . $header['nm_program'] : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Kegiatan
				</div>
				<div class="control-label col-sm-6  col-xs-4 text-sm text-uppercase no-margin">
					' . (isset($header['nm_kegiatan']) ?  $header['kd_sub'] . '.' . $header['kd_program'] . '.' . $header['kd_kegiatan'] . '. ' . $header['nm_kegiatan'] : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					Sub Kegiatan
				</div>
				<div class="control-label col-sm-6  col-xs-4 text-sm text-uppercase no-margin">
					' . (isset($header['nm_kegiatan']) ?  $header['kd_sub'] . '.' . $header['kd_program'] . '.' . $header['kd_kegiatan'] . '.' . $header['kd_kegiatan_sub'] . '. ' . $header['nm_kegiatan_sub'] : '-') . '
				</div>
			</div>
			<div class="row">				
				<div class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
					ANGGARAN
				</div>
				<div class="col-sm-2 col-xs-8 text-sm text-muted text-uppercase no-margin">
					<b class="text-danger">
						RP. ' . number_format($header['anggaran'], 2) . '
					</b>
				</div>
			</div>
		');
		//if($header['lock_kegiatan'] == 0 AND $header['pilihan'] == 0)
		if($header['lock_kegiatan_sub'] == 0)
		{
			$this
			->unset_action('pdf, export, print');
		}
		else
		{
			$this
			->unset_action('create, update, delete, pdf, export, print');
		}
		$this
		->set_default('id_keg_sub', $this->_primary)
		->unset_column('id, id_keg_sub, penjelasan')
		->unset_field('id, id_keg_sub, penjelasan')
		->unset_view('id, id_keg_sub')
		->unset_truncate('tolak_ukur')
		->add_action('toolbar', '../../../laporan/anggaran/rka_221', 'Preview RKA', 'btn-success ajax', 'fa fa-print', array('kegiatan_sub' => $this->input->get('id_keg_sub'), 'method' => 'preview'), true)
		->add_action('toolbar', '../../../laporan/anggaran/rka_221', 'Cetak RKA', 'btn-info ajax', 'fa fa-print', array('kegiatan_sub' => $this->input->get('id_keg_sub'), 'method' => 'print'), true)
		->set_field
		(
			array
			(
				'kd_indikator'						=> 'last_insert',
				'tolak_ukur'						=> 'textarea',
				'penjelasan'						=> 'wysiwyg'
			)
		)
		->set_alias
		(
			array
			(
				'kd_indikator'						=> 'kode'
			)
		)
		->add_class
		(
			array
			(
				'tolak_ukur'						=> 'autofocus'
			)
		)
		->set_field('target', 'number_format', 2)
		->column_order('kd_indikator_ref__indikator')
		->merge_content('{kd_indikator_ref__indikator} - {nm_indikator}', 'Indikator')
		->set_relation
		(
			'jns_indikator',
			'ref__indikator.id',
			'{ref__indikator.kd_indikator} - {ref__indikator.nm_indikator}'
		)
		->set_validation
		(
			array
			(
				'jns_indikator'						=> 'required',
				'kd_indikator'						=> 'required|callback_cek_kd_indikator',
				'tolak_ukur'						=> 'required',
				'target'							=> 'required',
				'satuan'							=> 'required',
			)
		)
		->field_position
		(
			array
			(
				'target'							=> 2,
				'satuan'							=> 2
			)
		)
		->where('id_keg_sub', $this->_primary)
		->order_by
		(
			array
			(
				'jns_indikator'						=> 'ASC',
				'kd_indikator'						=> 'ASC'
			)
		)
		//->set_template('form', 'form')
		->render($this->_table);
	}
	
	public function cek_kd_indikator($value = 0)
	{
		$query										= $this->model->select('kd_indikator')->get_where('ta__indikator_sub', array('jns_indikator' => $this->input->post('jns_indikator'), 'kd_indikator' => $this->input->post('kd_indikator'), 'id_keg_sub' => $this->input->get('id_keg_sub')), 1)->row('kd_indikator');
		if($query)
		{
			if('update' != $this->_method)
			{
				$this->form_validation->set_message('cek_kd_indikator', 'Kode untuk jenis indikator yang dipilih sudah diinput, silakan gunakan kode lain');
				return false;
			}
		}
		return true;
	}
	
	private function _header()
	{
		$query										= $this->model->select
		('
			ref__sub.kd_sub,
			ref__sub.nm_sub,
			ref__program.kd_program,
			ta__kegiatan.kd_keg AS kd_kegiatan,
			ta__kegiatan_sub.kd_keg_sub AS kd_kegiatan_sub,
			ref__rek_1.kd_rek_1,
			ref__rek_2.kd_rek_2,
			ref__rek_3.kd_rek_3,
			ref__rek_4.kd_rek_4,
			ref__rek_5.kd_rek_5,
			ref__rek_6.kd_rek_6,
			ref__program.nm_program,
			ta__kegiatan.kegiatan AS nm_kegiatan,
			ta__kegiatan_sub.kegiatan_sub AS nm_kegiatan_sub,
			ta__kegiatan_sub.lock_kegiatan_sub,
			ta__kegiatan_sub.pilihan
		')
		->select_sum('ta__belanja_rinci.total', 'anggaran')
		->join('ta__belanja_sub', 'ta__belanja_sub.id = ta__belanja_rinci.id_belanja_sub', 'INNER')
		->join('ta__belanja', 'ta__belanja.id = ta__belanja_sub.id_belanja', 'INNER')
		->join('ta__kegiatan_sub', 'ta__kegiatan_sub.id = ta__belanja.id_keg_sub', 'INNER')
		->join('ta__kegiatan', 'ta__kegiatan.id = ta__kegiatan_sub.id_keg', 'INNER')
		->join('ta__program', 'ta__program.id = ta__kegiatan.id_prog', 'INNER')
		->join('ref__program', 'ref__program.id = ta__program.id_prog', 'INNER')
		->join('ref__sub', 'ref__sub.id = ta__program.id_sub', 'INNER')
		->join('ref__rek_6', 'ref__rek_6.id = ta__belanja.id_rek_6', 'INNER')
		->join('ref__rek_5', 'ref__rek_5.id = ref__rek_6.id_ref_rek_5', 'INNER')
		->join('ref__rek_4', 'ref__rek_4.id = ref__rek_5.id_ref_rek_4', 'INNER')
		->join('ref__rek_3', 'ref__rek_3.id = ref__rek_4.id_ref_rek_3', 'INNER')
		->join('ref__rek_2', 'ref__rek_2.id = ref__rek_3.id_ref_rek_2', 'INNER')
		->join('ref__rek_1', 'ref__rek_1.id = ref__rek_2.id_ref_rek_1', 'INNER')
		->get_where('ta__belanja_rinci', array('ta__belanja.id_keg_sub' => $this->_primary))
		->result_array();
		
		return $query[0];
	}
}