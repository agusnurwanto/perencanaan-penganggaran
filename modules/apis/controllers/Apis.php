<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Apis extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->load->library('rest');
	}
	
	public function index()
	{
		return make_json
		(
			array
			(
				'error'								=> 'No method were selected'
			)
		);
	}
}