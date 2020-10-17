<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Updater > Check
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Check extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		//$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> false,
				CURLOPT_RETURNTRANSFER				=> true,
				CURLOPT_URL							=> 'https://siencang.bekasikota.go.id/updater',
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* simulate the request through AJAX */
				CURLOPT_HTTPHEADER					=> array
				(
					'Content-Type: application/x-www-form-urlencoded'
				),
				
				/* simulate POST method of form submission */
				CURLOPT_CUSTOMREQUEST				=> 'POST',
				
				/* set the form submission parameter */
				CURLOPT_POSTFIELDS					=> http_build_query
				(
					array
					(
						'version'					=> get_setting('build_version')
					)
				)
			)
		);
		
		/* execute cURL */
		$output										= json_decode(curl_exec($curl));
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		return make_json($output);
	}
}
