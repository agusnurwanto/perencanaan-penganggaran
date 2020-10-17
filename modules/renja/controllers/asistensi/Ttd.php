<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Ttd extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('sub_kegiatan');
		$this->set_theme('backend');
		$this->set_method('read');
	}
	
	public function index()
	{
		$this->set_title('Tanda Tangan')
		->set_icon('fa fa-bookmark')
		->unset_view('id, id_keg_sub')
		->set_template('read', 'read')
		->set_output
		(
			array
			(
				'tim_anggaran'						=> $this->model->query
				('
					SELECT
						ref__tim_anggaran.id,
						ref__tim_anggaran.kode,
						ref__tim_anggaran.nama_tim,
						ref__tim_anggaran.nip_tim,
						ref__tim_anggaran.jabatan_tim
					FROM
						ref__tim_anggaran
					WHERE
						ref__tim_anggaran.tahun = ' . get_userdata('year') . '
						AND
						ref__tim_anggaran.status = 1
					ORDER BY
						ref__tim_anggaran.kode
				')
				->result(),
				
				'verified'							=> $this->model->get_where('ta__asistensi_setuju', array('id_keg_sub' => $this->_primary), 1)->row()
			)
		)
		->modal_size('modal-lg')
		->render('ta__asistensi_setuju');
	}
	
	public function get_ttd($id = 0)
	{
		$ttd										= $this->model->select('ttd')->get_where('ref__tim_anggaran', array('id' => $id), 1)->row('ttd');
		
		if($ttd)
		{
			$ttd									= '<img src="' . get_image('anggaran', $ttd) . '" width="80" class="img-responsive" />';
		}
		
		return $ttd;
	}
}
