<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * I18N
 * Collect required language to be used in the javascript translation
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class I18n extends Aksara
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$locale										= ($this->session->userdata('language') ? $this->session->userdata('language') : get_setting('app_language'));
		$phrases									= @file_get_contents('public/languages/' . $locale . '.json');
		
		$this->output->set_content_type('js', 'utf-8');
		$this->output->set_output('var phrase = ' . preg_replace('/"([^"]+)"\s*:\s*/', '$1:', json_encode(json_decode($phrases), JSON_PRETTY_PRINT)) . ';');
	}
}