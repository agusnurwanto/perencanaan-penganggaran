<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * About the software page
 */
class About extends Aksara
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->set_title(phrase('about_the_project'))
		->set_icon('mdi mdi-information-outline')
		->set_description(phrase('including_the_credits_of_external_resources'))
		->render();
	}
	
	public function version()
	{
		return make_json
		(
			array
			(
				'version'							=> get_setting('build_version')
			)
		);
	}
}