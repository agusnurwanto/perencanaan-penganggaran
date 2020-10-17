<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Musrenbang extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title('Master Musrenbang')
		->set_icon('fa fa-fighter-jet')
		->render();
	}
}