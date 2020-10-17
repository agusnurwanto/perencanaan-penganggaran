<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * SIPD Data Scraping
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Sipd_scraping extends Aksara
{
	private $_cookie_file							= null;
	private $_sipd_hostname							= null;
	private $_sipd_username							= null;
	private $_sipd_password							= null;
	private $_id_daerah								= 0;
	
	public function __construct()
	{
		parent::__construct();
		
		/* set cookie file (must be rewritable) */
		$this->_cookie_file							= '/tmp/sipd-cookie-' . time();
		
		$this->_sipd_hostname						= 'https://bekasi.sipd.kemendagri.go.id';
		$this->_sipd_username						= 'sekdabekasi';
		$this->_sipd_password						= 'sekdabekasiwow';
		$this->_id_unit								= ($this->uri->segment(3) ? $this->uri->segment(3) : 0);
	}
	
	public function index()
	{
		return throw_exception(404, 'Halaman yang Anda minta tidak tersedia atau telah dihapus', base_url());
	}
	
	public function sub_kegiatan_belanja()
	{
		/* session initialization */
		$this->_initialize_session();
		
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> 0,
				CURLOPT_RETURNTRANSFER				=> 1,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah/main/plan/belanja/' . get_userdata('year') . '/giat/tampil-unit/' . $this->_id_daerah . '/' . $this->_id_unit,
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* simulate the request through AJAX */
				CURLOPT_HTTPHEADER					=> array
				(
					'X-Requested-With: XMLHttpRequest'
				),
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= json_decode(curl_exec($curl));
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		return make_json
		(
			array
			(
				'message'							=> 'Data retrieved',
				'id_daerah'							=> $this->_id_daerah,
				'id_unit'							=> $this->_id_unit,
				'results'							=> (isset($output->data) ? $output->data : array())
			)
		);
	}
	
	private function _initialize_session()
	{
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> 0,
				CURLOPT_RETURNTRANSFER				=> 1,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah/logout?idu=MjU=',
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= curl_exec($curl);
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		$curl										= curl_init();
		
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> 0,
				CURLOPT_RETURNTRANSFER				=> 1,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah',
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= curl_exec($curl);
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		/* extract the CSRF Token */
		preg_match('/<meta name="_token" content="(.*?)"/', $output, $token);
		
		if(isset($token[1]))
		{
			/* initialize cURL */
			$curl									= curl_init();
			
			/* set cURL options */
			curl_setopt_array
			(
				$curl,
				array
				(
					CURLOPT_HEADER					=> 0,
					CURLOPT_RETURNTRANSFER			=> 1,
					CURLOPT_URL						=> $this->_sipd_hostname . '/daerah/login',
					CURLOPT_FOLLOWLOCATION			=> true,
					
					/* simulate the request through AJAX */
					CURLOPT_HTTPHEADER				=> array
					(
						'Content-Type: application/x-www-form-urlencoded',
						'X-Requested-With: XMLHttpRequest'
					),
					
					/* simulate POST method of form submission */
					CURLOPT_CUSTOMREQUEST			=> 'POST',
					
					/* set the form submission parameter */
					CURLOPT_POSTFIELDS				=> http_build_query
					(
						array
						(
							'_token'				=> $token[1],
							'env'					=> 'main',
							'region'				=> 'daerah',
							'skrim'					=> base64_encode('user_name=' . $this->_sipd_username . '&user_password=' . $this->_sipd_password)
						)
					),
					
					/* cookie handling */
					CURLOPT_COOKIEJAR				=> $this->_cookie_file,
					CURLOPT_COOKIEFILE				=> $this->_cookie_file
				)
			);
			
			/* execute cURL */
			$output									= json_decode(curl_exec($curl));
			
			/* close a cURL session and frees all resources */
			curl_close($curl);
			
			if(isset($output->result))
			{
				if($output->result == 'success')
				{
					$this->_id_daerah				= $output->id_daerah;
				}
				else
				{
					return make_json($output);
				}
			}
			else
			{
				/* failed to sign in with unknown exception */
				return make_json
				(
					array
					(
						'result'					=> 'failed',
						'message'					=> 'Failed to getting the session. Request timed out.'
					)
				);
			}
		}
		else
		{
			/* failed to get the token, it's maybe caused by server timed out */
			return make_json
			(
				array
				(
					'result'						=> 'failed',
					'message'						=> 'Failed to getting the CSRF token. Request timed out.'
				)
			);
		}
	}
}
