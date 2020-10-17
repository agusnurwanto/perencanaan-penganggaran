<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Akun extends Aksara
{
	private $_table									= 'ref__standar_harga_1';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Standar Akun')
		->set_icon('mdi mdi-shield-account')
		->unset_column('id, tahun')
		->unset_field('id, tahun')
		->unset_view('id, tahun')
		->set_field
		(
			array
			(
				'kd_standar_harga_1'				=> 'last_insert',
				'uraian'							=> 'textarea',
				'deskripsi'							=> 'wysiwyg'
			)
		)
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus'
			)
		)
		
		/* set kolom sebagai hyperlink ke modul di bawahnya */
		->set_field('uraian', 'hyperlink', 'master/standar/kelompok', array('akun' => 'id'))
		
		/* penyesuaian validasi */
		->set_validation
		(
			array
			(
				'kode'								=> 'required|numeric|is_unique[ref__standar_harga_1.kd_standar_harga_1.id.' . $this->input->get('id') . ']',
				'uraian'							=> 'required|xss_clean'
			)
		)
		
		/* set value default sehingga pengguna tidak dapat mengubah */
		->set_default('tahun', get_userdata('year'))
		
		->where('tahun', get_userdata('year'))
		
		/* mengatur ukuran merge field */
		->field_size
		(
			array
			(
				'kd_standar_harga_1'				=> 'col-3'
			)
		)
		/*->field_position
		(
			array
			(
				'deskripsi'							=> 2
			)
		)*/
		->set_alias
		(
			array
			(
				'kd_standar_harga_1'				=> 'Kode'
			)
		)
		->order_by
		(
			array
			(
				'kd_standar_harga_1'				=> 'ASC'
			)
		)
		->render($this->_table);
	}
}