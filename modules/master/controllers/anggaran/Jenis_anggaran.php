<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Jenis_anggaran extends Aksara
{
	private $_table									= 'ref__renja_jenis_anggaran';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Jenis Anggaran')
		->set_icon('mdi mdi-egg-easter')
		->unset_column('id, keterangan, nomor_perda, nomor_perkada')
		->unset_field('id')
		->unset_view('id')
		
		->set_field
		(
			array
			(
				'kode'								=> 'last_insert, readonly',
				'keterangan'						=> 'textarea',
				'nomor_perda'						=> 'textarea',
				'nomor_perkada'						=> 'textarea',
				'tanggal_rka'						=> 'datepicker',
				'tanggal_anggaran_kas'				=> 'datepicker',
				'tanggal_perda'						=> 'datepicker',
				'tanggal_perkada'					=> 'datepicker',
				'tanggal_dpa'						=> 'datepicker'
			)
		)
		
		->merge_content('{kode}. {nama_jenis_anggaran}', 'Jenis Anggaran')
		->merge_field('kode, nama_jenis_anggaran')
		->merge_field('tanggal_rka, tanggal_anggaran_kas')
		->merge_field('nomor_perda, tanggal_perda')
		->merge_field('nomor_perkada, tanggal_perkada')
		->merge_field('tanggal_dpa, keterangan')
		->field_size
		(
			array
			(
				'kode'								=> 'col-3',
				'nama_jenis_anggaran'				=> 'col-9',
				'tanggal_perda'						=> 'col-5',
				'tanggal_perkada'					=> 'col-5',
				'tanggal_dpa'						=> 'col-5'
			)
		)
		/*->field_position
		(
			array
			(
				'tanggal_rka'						=> 2,
				'tanggal_dpa'						=> 2
			)
		)*/
		->add_class('nama_jenis_anggaran', 'autofocus')
		
		->set_validation
		(
			array
			(
				'kode'								=> 'required', //|is_unique[' . $this->_table . '.kode.kode.' . $this->input->get('kode') . ']',
				'nama_jenis_anggaran'				=> 'required',
				'tanggal_rka'						=> 'required',
				'tanggal_anggaran_kas'				=> 'required',
				'tanggal_dpa'						=> 'required'
			)
		)
		->set_alias
		(
			array
			(
				'tanggal_rka'						=> 'Tanggal RKA',
				'tanggal_dpa'						=> 'Tanggal DPA'
			)
		)
		->order_by
		(
			array
			(
				'kode'								=> 'ASC'
			)
		)
		
		->render($this->_table);
	}
}