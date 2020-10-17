<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Shortlink
 *
 * @author		Aby Dahana
 * @profile		abydahana.github.io
 */
class Shortlink extends Aksara
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		/* get the existing link */
		$query										= $this->model->get_where('app__shortlink', array('hash' => $this->uri->segment(2)), 1)->row();
		
		if($query)
		{
			/* set the one time temporary session */
			if(!$this->session->userdata('is_logged'))
			{
				$session							= json_decode($query->session, true);
				$session['sess_destroy_after']		= 'once';
				
				$this->session->set_userdata($session);
			}
			
			/* redirect to real URL */
			redirect($query->url);
		}
		
		/* existing link is not found, throw the exception */
		return throw_exception(404, phrase('the_page_you_requested_does_not_exists'), base_url());
	}
}
