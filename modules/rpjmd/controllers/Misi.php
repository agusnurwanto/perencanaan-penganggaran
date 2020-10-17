<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Misi extends Aksara
{
	private $_table									= 'ta__rpjmd_misi'; 
	function __construct()
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
				'rpjmd/visi'						=> 'Visi'
			)
		)
		->set_title('Misi RPJMD')
		->unset_view('id')
		->unset_column('id, visi, tahun_awal, tahun_akhir')
		->unset_field('id')
		->column_order('kode, misi, deskripsi')
		->field_order('id_visi, kode, misi')
		->unset_truncate('misi, deskripsi')
		->set_field('misi', 'hyperlink', 'rpjmd/tujuan', array('misi' => 'id'))
		->set_field
		(
			array
			(
				'kode'								=> 'last_insert',
				'misi'								=> 'textarea',
				'deskripsi'							=> 'textarea'
			)
		)
		->set_alias
		(
			array
			(
				'id_visi'							=> 'Visi'
			)
		)
		->set_relation
		(
			'id_visi',
			'ta__rpjmd_visi.id',
			'{ta__rpjmd_visi.tahun_awal} s/d {ta__rpjmd_visi.tahun_akhir} {ta__rpjmd_visi.visi}',
			null,
			null,
			null
		)
		->set_validation
		(
			array
			(
				'id_visi'							=> 'required',
				'kode'								=> 'required|numeric',
				'misi'								=> 'required'
			)
		)
		->field_position
		(
			array
			(
				'deskripsi'							=> 2
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