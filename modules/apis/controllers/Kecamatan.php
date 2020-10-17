<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kecamatan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('rest');
	}
	
	public function index()
	{
		$query										= $this->model->get('ref__kecamatan')->result();
		$this->rest->set_output($query);
	}
}