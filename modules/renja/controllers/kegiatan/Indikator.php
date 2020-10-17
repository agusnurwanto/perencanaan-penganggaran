<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Indikator extends Aksara
{
	private $_table									= 'ta__indikator';
	
	function __construct()
	{
		parent::__construct();
		$this->_kegiatan							= $this->input->get('kegiatan');
		$this->_kegiatan							= $this->input->get('kegiatan');
		$this->set_permission();
		$this->set_theme('backend');
		
		if('indikator_bank' == $this->input->post('method'))
		{
			return $this->_indikator_bank();
		}
		elseif('jenis_indikator' == $this->input->post('method'))
		{
			return $this->_jenis_indikator();
		}
	}
	
	public function index()
	{
		$header										= $this->_header();
		$bank_indikator								= $this->model->select('bank_indikator')->get_where('ref__settings', array('tahun' => get_userdata('year')), 1)->row('bank_indikator');
		$this->set_breadcrumb
		(
			array
			(
				'renja/kegiatan'					=> 'kegiatan'
			)
		);
		$this->set_title('Indikator Kegiatan')
		->set_description
		('
			<div class="row">
				<div class="col-6 col-sm-2 text-muted text-sm">
					SUB UNIT
				</div>
				<div class="col-6 col-sm-6 font-weight text-sm">
					' . (isset($header->nm_sub) ?  $header->kd_urusan . '.' . sprintf('%02d', $header->kd_bidang) . '.' . sprintf('%02d', $header->kd_unit) . '.' . sprintf('%02d', $header->kd_sub) . ' ' . $header->nm_sub : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="col-6 col-sm-2 text-muted text-sm">
					PROGRAM
				</div>
				<div class="col-6 col-sm-10 font-weight text-sm">
					' . (isset($header->nm_program) ?  $header->kd_program . ' ' . $header->nm_program : '-') . '
				</div>
			</div>
			<div class="row">
				<div class="col-6 col-sm-2 text-muted text-sm">
					KEGIATAN
				</div>
				<div class="col-6 col-sm-10 font-weight text-sm">
					' . (isset($header->kegiatan) ?  $header->kd_program . '.' . sprintf('%02d', $header->kd_keg) . ' ' . $header->kegiatan : '-') . '
				</div>
			</div>
		');
		//if($header['lock_kegiatan'] == 0 AND $header['pilihan'] == 0)
		/*if($header->lock_kegiatan == 0)
		{
			$this
			->unset_action('pdf, export, print');
		}
		else
		{
			$this
			->unset_action('create, update, delete, pdf, export, print');
		}*/
		if($bank_indikator == 0)
		{
			$this
			->unset_column('id, id_keg, id_indikator_bank')
			->unset_field('id, id_keg, id_indikator_bank')
			->unset_view('id, id_keg')
			->set_field
			(
				array
				(
					'kd_indikator'						=> 'last_insert',
					'tolak_ukur'						=> 'textarea'
				)
			)
			;
		}
		else
		{
			$this
			->unset_column('id, id_keg')
			->unset_field('id, id_keg, tolak_ukur')
			->unset_view('id, id_keg')
			->add_class
			(
				array
				(
					'id_indikator_bank'					=> 'indikator_bank'
				)
			)
			->set_field
			(
				array
				(
					'kd_indikator'						=> 'last_insert',
					'tolak_ukur'						=> 'textarea',
					'satuan'							=> 'readonly'
				)
			)
			->set_relation
			(
				'id_indikator_bank',
				'ref__indikator_bank.id',
				'{ref__indikator_bank.tolak_ukur}',
				array
				(
					'ref__indikator_bank.tahun'			=> get_userdata('year')
				),
				NULL,
				array
				(
					'ref__indikator_bank.tolak_ukur'	=> 'ASC'
				)
			)
			;
		}
		$this
		->set_default('id_keg', $this->_kegiatan)
		->column_order('nm_indikator, kd_indikator')
		->field_order('kd_indikator_ref__indikator')
		->unset_truncate('tolak_ukur')
		->unset_action('pdf, export, print')
		->add_action('toolbar', '../../../laporan/anggaran/rka/rka_rincian_belanja', 'RKA Kegiatan', 'btn-success ajax', 'mdi mdi-printer', array('kegiatan' => $this->_kegiatan, 'method' => 'embed'), true)
		//->add_action('toolbar', '../../../laporan/anggaran/rka_221', 'Cetak RKA', 'btn-info ajax', 'fa fa-print', array('kegiatan' => $this->input->get('id_keg'), 'method' => 'print'), true)
		->set_alias
		(
			array
			(
				'kd_indikator'						=> 'kode',
				'jns_indikator'						=> 'Jenis Indikator',
				'nm_indikator'						=> 'Indikator',
				'id_indikator_bank'					=> 'Tolak Ukur'
			)
		)
		->set_field('target', 'price_format', 2)
		//->merge_content('{kd_indikator_ref__indikator} - {nm_indikator}', 'Indikator')
		->set_relation
		(
			'jns_indikator',
			'ref__indikator.id',
			'{ref__indikator.kd_indikator} - {ref__indikator.nm_indikator}',
			array
			(
				'ref__indikator.kd_indikator <'		=> 5
			),
			NULL,
			array
			(
				'ref__indikator.kd_indikator'		=> 'ASC'
			)
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
		->set_validation
		(
			array
			(
				'jns_indikator'						=> 'required',
				'kd_indikator'						=> 'required|is_unique[' . $this->_table . '.kd_indikator.id.' . $this->input->get('id') . '.jns_indikator.' . $this->input->post('jns_indikator') . '.id_keg.' . $this->_kegiatan . ']',
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
		->where('id_keg', $this->_kegiatan)
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
	
	private function _indikator_bank()
	{
		$indikator_bank								= $this->input->post('id');
		$query										= $this->model->get_where
		(
			'ref__indikator_bank',
			array
			(
				'id'								=> $indikator_bank,
				'tahun'								=> get_userdata('year')
			)
		)
		->row();
		
		return make_json
		(
			array
			(
				'satuan'							=> (isset($query->satuan) ? $query->satuan : '')
			)
		);
	}
	
	private function _jenis_indikator()
	{
		$query										= $this->model->query
		('
			SELECT
				IFNULL(MAX(kd_indikator) + 1, 1) AS kd_indikator
			FROM
				ta__indikator
			WHERE
				jns_indikator = ' . $this->input->post('id') . '
				AND
				id_keg = ' . $this->_kegiatan . '
		')
		->row('kd_indikator');
		
		return make_json
		(
			array
			(
				'kode'								=> $query
			)
		);
	}
	
	public function cek_kd_indikator($value = 0)
	{
		$query										= $this->model->select('kd_indikator')->get_where('ta__indikator', array('jns_indikator' => $this->input->post('jns_indikator'), 'kd_indikator' => $this->input->post('kd_indikator'), 'id_keg' => $this->input->get('id_keg')), 1)->row('kd_indikator');
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
		$query										= $this->model->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__kegiatan.kd_keg,
				ref__sub.nm_sub,
				ref__program.nm_program,
				ref__kegiatan.nm_kegiatan AS kegiatan
			FROM
				ta__kegiatan
			INNER JOIN ref__kegiatan ON ref__kegiatan.id = ta__kegiatan.id_kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
				ta__kegiatan.id = ' . $this->_kegiatan . '
			LIMIT 1
		')
		->row();
		return $query;
	}
	
	public function after_insert()
	{
		return throw_exception(301, phrase('data_was_successfully_updated'), current_page('../'));
	}
	
	public function after_update()
	{
		return throw_exception(301, phrase('data_was_successfully_updated'), current_page('../'));
	}
}