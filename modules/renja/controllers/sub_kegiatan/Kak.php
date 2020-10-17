<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kak extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('kegiatan_sub');
		$this->set_permission();
		$this->set_theme('backend');
		$this->parent_module('renja/sub_kegiatan');
		$this->insert_on_update_fail(true);
	}
	
	public function index()
	{
		$this->set_method('update')
		->set_title(phrase('detail'))
		->set_icon('mdi mdi-information-circle')
		->unset_column('id, id_keg_sub')
		->unset_field('id, id_keg_sub')
		->unset_view('id, id_keg_sub')
		->set_primary('id_keg_sub')
		->where('id_keg_sub', $this->_primary)
		->set_default('id_keg_sub', $this->_primary)
		->limit(1)
		->modal_size('modal-xl')
		->render('ta__kak');
	}
	
	public function validate_form($data = array())
	{
		$this->load->library('form_validation');
		$this->load->helper('security');
		
		$this->form_validation->set_rules('dasar_hukum[]', 'Dasar Hukum', 'trim|xss_clean');
		$this->form_validation->set_rules('gambaran_umum[]', 'Gambaran Umum', 'trim|xss_clean');
		$this->form_validation->set_rules('penerima_manfaat', 'Penerima Manfaat', 'trim|xss_clean');
		$this->form_validation->set_rules('metode_pelaksanaan[]', 'Metode Pelaksanaan', 'trim|xss_clean');
		$this->form_validation->set_rules('tahapan_pelaksanaan[]', 'Tahapan Pelaksanaan', 'trim|xss_clean');
		$this->form_validation->set_rules('waktu_pelaksanaan', 'Waktu Pelaksanaan', 'trim|xss_clean');
		$this->form_validation->set_rules('biaya[a]', 'Biaya', 'trim|numeric');
		$this->form_validation->set_rules('biaya[b][]', 'Biaya', 'trim|is_boolean');
		
		if($this->form_validation->run() === false)
		{
			return throw_exception(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
		}
		
		$prepare									= array
		(
			'id_keg_sub'							=> $this->_primary,
			'dasar_hukum'							=> json_encode($this->input->post('dasar_hukum')),
			'gambaran_umum'							=> json_encode($this->input->post('gambaran_umum')),
			'penerima_manfaat'						=> $this->input->post('penerima_manfaat'),
			'metode_pelaksanaan'					=> json_encode($this->input->post('metode_pelaksanaan')),
			'tahapan_pelaksanaan'					=> json_encode($this->input->post('tahapan_pelaksanaan')),
			'waktu_pelaksanaan'						=> $this->input->post('waktu_pelaksanaan'),
			'biaya'									=> json_encode($this->input->post('biaya'))
		);
		
		$this->update_data('ta__kak', $prepare, array('id_keg_sub' => $this->_primary));
	}
}