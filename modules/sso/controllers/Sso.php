<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * SSO
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Sso extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_app_id								= 1;
		$this->_api_key								= '12341234';
	}
	
	public function index()
	{
		$this->set_title('SSO Test')
		->set_icon('mdi mdi-lock')
		->set_output
		(
			array
			(
				'results'							=> get_userdata()
			)
		)
		->render();
	}
	
	public function handshake()
	{
		$query										= array();
		
		if(get_userdata('user_id'))
		{
			$query									= $this->model->get_where
			(
				'oauth__login',
				array
				(
					'user_id'						=> get_userdata('user_id')
				),
				1
			)
			->row();
		}
		
		$service_url								= 'https://sso.dwitrimedia.com/apis/auth/handshake';
		$curl										= curl_init();
		
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> 0,
				CURLOPT_RETURNTRANSFER				=> 1,
				CURLOPT_URL							=> $service_url,
				CURLOPT_FOLLOWLOCATION				=> true,
				CURLOPT_HTTPHEADER					=> array
				(
					'Accept: application/json',
					'Authorization: Bearer ' . $this->_api_key, // API Key
					(isset($query->access_token) ? 'Access-Token: ' . $query->access_token : null)
				)
			)
		);
		
		$execute									= json_decode(curl_exec($curl));
		
		curl_close($curl);
		
		if(!($execute->status) || $execute->status !== 200)
		{
			return throw_exception(301, 'Autentikasi Gagal!', (isset($execute->redirect) ? $execute->redirect : 'https://sso.dwitrimedia.com/auth/sign_in/' . $this->_app_id), true);
		}
		
		return throw_exception(301, 'Autentikasi berhasil dilakukan', (isset($execute->redirect) ? $execute->redirect : base_url('sso')));
	}
	
	public function validate_token($access_token = null, $email = null)
	{
		$query										= $this->model->get_where
		(
			'app__users',
			array
			(
				'email'								=> $email
			),
			1
		)
		->row();
		
		if($query)
		{
			$checker								= $this->model->get_where
			(
				'oauth__login',
				array
				(
					'user_id'						=> $query->user_id
				),
				1
			)
			->row();
			
			if($checker)
			{
				$this->model->update
				(
					'oauth__login',
					array
					(
						'access_token'				=> $access_token
					),
					array
					(
						'user_id'					=> $query->user_id
					)
				);
			}
			else
			{
				$this->model->insert
				(
					'oauth__login',
					array
					(
						'user_id'					=> $query->user_id,
						'service_provider'			=> 'sso',
						'access_token'				=> $access_token,
						'status'					=> 1
					)
				);
			}
			
			$this->session->set_userdata
			(
				array
				(
					'user_id'						=> $query->user_id,
					'username'						=> $query->username,
					'group_id'						=> $query->group_id,
					'language'						=> $this->model->select('code')->get_where('app__languages', array('id' => $query->language_id))->row('code'),
					'is_logged'						=> true,
					'year'							=> 2021
				)
			);
			
			return throw_exception(301, 'Anda berhasil masuk aplikasi dengan SSO!', base_url());
		}
		elseif($access_token)
		{
			// register user?
			//$this->_register_user($access_token);
		}
		
		return throw_exception(403, 'Gagal melakukan login dengan SSO!', current_page(null, array('access_token' => null, 'email' => null)));
	}
	
	private function _register_user($access_token = null)
	{
		$service_url								= 'https://sso.dwitrimedia.com/apis/passport';
		$curl										= curl_init();
		
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> 0,
				CURLOPT_RETURNTRANSFER				=> 1,
				CURLOPT_URL							=> $service_url,
				CURLOPT_FOLLOWLOCATION				=> true,
				CURLOPT_HTTPHEADER					=> array
				(
					'Accept: application/json',
					'Authorization: Bearer ' . $this->_api_key, // API Key
					'Access-Token: ' . $access_token
				)
			)
		);
		
		$execute									= json_decode(curl_exec($curl));
		
		curl_close($curl);
		
		print_r($execute);exit;
	}
}
