<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');
/**
 * Rest API Library
 *
 * @version		2.1.1
 * @author		Aby Dahana
 * @profile		abydahana.github.io
 */
class Rest
{
	private $_api_key								= null;
	private $_secret_key							= null;
	private $_status								= 200;
	private $_results								= array();
	public function __construct()
	{
		$this->_ci									=& get_instance();
		$this->_ci->_limit							= ($this->_ci->input->get('limit') ? $this->_ci->input->get('limit') : 25);
		$this->_ci->_offset							= ($this->_ci->input->get('offset') ? $this->_ci->input->get('offset') : null);
		$this->_api_key								= $this->_ci->input->post('X-API-KEY');
		$this->_secret_key							= $this->_ci->input->post('X-SECRET-KEY');
		$this->_client_ip							= $_SERVER['REMOTE_ADDR'];
		$this->validate();
	}
	
	/**
	 * validate
	 *
	 * validate the request before returning the request
	 *
	 * @return		mixed
	 */
	public function validate()
	{
		/* validate api key and secret key including the valid time */
		$this->_client_api							= $this->_ci->model->get_where
		(
			'app__rest_clients',
			array
			(
				'api_key'							=> $this->_api_key,
				'secret_key'						=> $this->_secret_key,
				'TIME(valid_until) <= '				=> time(date('Y-m-d H:i:s'))
			)
		)
		->row();
		
		/* check the result */
		if(!$this->_client_api)
		{
			$this->_status							= 403;
			$this->_results							= phrase('the_token_you_used_is_not_valid_or_already_expired');
			
			$this->_ci->output
			->set_status_header($this->_status)
			->set_content_type('application/json')
			->set_header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT')
			->set_header('Cache-Control: no-store, no-cache, must-revalidate')
			->set_header('Pragma: no-cache')
			->set_output
			(
				json_encode
				(
					array
					(
						'STATUS'					=> $this->_status,
						'IP'						=> $this->_client_ip,
						'REQUEST_TIME'				=> date('Y-m-d H:i:s'),
						'RESULTS'					=> $this->_results
					)
				)
			)
			->_display();
			exit;
		}
		elseif(!in_array($this->_client_ip, array_map('trim', explode(',', ($this->_client_api->ip_range)))))
		{
			/* result is found, but how about client ip? */
			$this->_status							= 403;
			$this->_results							= phrase('you_are_not_allowed_to_access_the_page');
			
			$this->_ci->output
			->set_status_header($this->_status)
			->set_content_type('application/json')
			->set_header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT')
			->set_header('Cache-Control: no-store, no-cache, must-revalidate')
			->set_header('Pragma: no-cache')
			->set_output
			(
				json_encode
				(
					array
					(
						'STATUS'					=> $this->_status,
						'IP'						=> $this->_client_ip,
						'REQUEST_TIME'				=> date('Y-m-d H:i:s'),
						'RESULTS'					=> $this->_results
					)
				)
			)
			->_display();
			exit;
		}
	}
	
	/**
	 * set_output
	 *
	 * returning the callback data into the request
	 *
	 * @param		array		$output
	 * @return		mixed
	 */
	public function set_output($output = array())
	{
		if(in_array($this->_status, array(403)))
		{
			$this->_results							= $this->_results;
		}
		else
		{
			$this->_results							= $output;
		}
		
		if('xml' == $this->_ci->input->get('format'))
		{
			// Under construction
		}
		else
		{
			$this->_ci->output
			->set_status_header($this->_status)
			->set_content_type('application/json')
			->set_header('Last-Modified: ' . date('D, d M Y H:i:s') . ' GMT')
			->set_header('Cache-Control: no-store, no-cache, must-revalidate')
			->set_header('Pragma: no-cache')
			->set_output
			(
				json_encode
				(
					array
					(
						'STATUS'					=> $this->_status,
						'IP'						=> $this->_client_ip,
						'REQUEST_TIME'				=> date('Y-m-d H:i:s'),
						'RESULTS'					=> $this->_results
					)
				)
			)
			->_display();
			exit;
		}
	}
}