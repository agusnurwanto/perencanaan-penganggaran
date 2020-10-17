<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Master extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('master_data'))
		->set_icon('fa fa-cogs')
		->render();
	}
}