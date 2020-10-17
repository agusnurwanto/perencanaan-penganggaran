<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rekening extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('master_rekening'))
		->set_icon('fa fa-dollar')
		->render();
	}
}