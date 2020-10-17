<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Musrenbang extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		if(1 != get_userdata('group_id') && get_userdata('sub_unit'))
		{
			redirect(go_to('kelurahan/data'));
		}
	}
	
	public function index()
	{
	}
}