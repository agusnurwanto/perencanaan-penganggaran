<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Master > Data > Bidang
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Indikator_bank extends Aksara
{
	private $_table									= 'ref__indikator_bank';
	
	public function __construct()
	{
		parent::__construct();
	
		$this->set_permission();
		$this->set_theme('backend');
		
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master/indikator_bank/indikator_bank'							=> phrase('bank_indikator')
			)
		);
		
		
		$this->set_title('Bank Indikator')
		->set_icon('mdi mdi-alpha-b-box-outline')
		->unset_column('id, tahun')
		->unset_field('id, tahun')
		->unset_view('id, tahun')
		->set_default('tahun', get_userdata('year'))
		->column_order('kd_indikator_bank, nm_indikator, tolak_ukur')
		->view_order('kd_indikator_bank, nm_indikator, tolak_ukur')
		->field_order('id_indikator, kd_indikator_bank')
		
		->set_field
		(
			array
			(
				'kd_indikator_bank'					=> 'last_insert',
				'tolak_ukur'						=> 'textarea'
			)
		)
		
		->set_relation
		(
			'id_indikator',
			'ref__indikator.id',
			'{ref__indikator.nm_indikator}'
		)
		
		
		->set_validation
		(
			array
			(
				'kd_indikator_bank'					=> 'required|is_unique[ref__indikator_bank.kd_indikator_bank.id.' . $this->input->get('id') . ']',
				'tolak_ukur'						=> 'required|xss_clean',
				'satuan'							=> 'required|xss_clean'
			)
		)
		
		->add_class
		(
			array
			(
				'tolak_ukur'							=> 'autofocus'
			)
		)
		
		->set_alias
		(
			array
			(
				'kd_indikator_bank'					=> 'Kode',
				'nm_indikator'						=> 'Nama Indikator',
				'id_indikator'						=> 'Nama Indikator'
			)
		)
		->order_by('kd_indikator_bank')
		->render($this->_table);
	}
}