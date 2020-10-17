<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kak extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_keg');
		$this->set_permission();
		$this->set_theme('backend');
		$this->parent_module('renja/kegiatan/data');
		$this->insert_on_update_fail(true);
	}
	
	public function index()
	{
		$this->set_method('update')
		->set_title(phrase('detail'))
		->set_icon('fa fa-info-circle')
		->unset_column('id, id_keg')
		->unset_field('id, id_keg')
		->unset_view('id, id_keg')
		->set_primary('id_keg')
		->where('id_keg', $this->_primary)
		->set_default('id_keg', $this->_primary)
		->limit(1)
		->form_callback('_validate_form')
		->render('ta__kak', 'form');
	}
	
	public function _validate_form()
	{
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
			return generateMessages(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
		}
		
		$prepare									= array
		(
			'id_keg'								=> $this->_primary,
			'dasar_hukum'							=> json_encode($this->input->post('dasar_hukum')),
			'gambaran_umum'							=> json_encode($this->input->post('gambaran_umum')),
			'penerima_manfaat'						=> $this->input->post('penerima_manfaat'),
			'metode_pelaksanaan'					=> json_encode($this->input->post('metode_pelaksanaan')),
			'tahapan_pelaksanaan'					=> json_encode($this->input->post('tahapan_pelaksanaan')),
			'waktu_pelaksanaan'						=> $this->input->post('waktu_pelaksanaan'),
			'biaya'									=> json_encode($this->input->post('biaya'))
		);
		
		$this->update_data('ta__kak', $prepare, array('id_keg' => $this->_primary));
	}
}