<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User Registration
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Register extends Aksara
{
	private $_year_table							= 'ref__tahun';
	private $_year_field_name						= 'tahun';
	private $_year_status_field_name				= 'aktif';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		/* check if user is already signed in */
		if(get_userdata('is_logged'))
		{
			return throw_exception(301, phrase('you_have_been_signed_in'), base_url('dashboard'), true);
		}
		/* check if registration is opened */
		elseif(!get_setting('enable_frontend_registration'))
		{
			return throw_exception(403, phrase('the_registration_is_temporary_disabled'), base_url('auth'));
		}
		
		/* unlink old captcha if any */
		if(file_exists(UPLOAD_PATH . '/captcha/' . $this->session->userdata('captcha_file')))
		{
			@unlink(UPLOAD_PATH . '/captcha/' . $this->session->userdata('captcha_file'));
		}
	}
	
	public function index()
	{
		/* captcha challenge */
		if(!$this->input->post('token'))
		{
			$this->load->helper('captcha');
			$captcha								= create_captcha
			(
				array
				(
					'img_path'						=> UPLOAD_PATH . '/captcha/',
					'img_url'						=> base_url(UPLOAD_PATH . '/captcha'),
					'img_width'						=> 120,
					'img_height'					=> 30,
					'expiration'					=> 3600,
					'word_length'					=> 6,
					'pool'							=> '123456789ABCDEF',
					'colors'						=> array
					(
						'background'				=> array(52, 58, 64),
						'border'					=> array(52, 58, 64),
						'grid'						=> array(52, 58, 64),
						'text'						=> array(255, 255, 255)
					)
				)
			);
			
			/* set captcha word into session, used to next validation */
			$this->session->set_userdata
			(
				array
				(
					'captcha'						=> $captcha['word'],
					'captcha_file'					=> $captcha['filename']
				)
			);
			$this->set_output('captcha', base_url(UPLOAD_PATH . '/captcha/' . $captcha['filename']));
		}
		
		$this->set_title(phrase('register_an_account'))
		->set_icon('mdi mdi-account-plus')
		->set_description(phrase('fill_all_the_required_field_below_to_take_a_new_account'))
		->form_callback('_validate_form')
		->render();
	}
	
	/**
	 * validate form
	 */
	public function _validate_form()
	{
		/* load additional library and helper */
		$this->load->library('form_validation');
		$this->load->helper('security');
		
		$this->form_validation->set_rules('first_name', phrase('full_name'), 'required|max_length[32]');
		$this->form_validation->set_rules('username', phrase('username'), 'required|alpha_numeric|is_unique[app__users.username]');
		$this->form_validation->set_rules('email', phrase('email_address'), 'required|valid_email|is_unique[app__users.email]');
		$this->form_validation->set_rules('phone', phrase('phone_number'), 'required|min_length[8]|max_length[16]');
		$this->form_validation->set_rules('password', phrase('password'), 'required|min_length[6]');
		
		/* validate captcha */
		$this->form_validation->set_rules('captcha', phrase('bot_challenge'), 'required|regex_match[/' . $this->session->userdata('captcha') . '/i]');
		
		/* run form validation */
		if($this->form_validation->run() === false)
		{
			return throw_exception(400, $this->form_validation->error_array());
		}
		
		/* prepare the insert data */
		$prepare									= array
		(
			'first_name'							=> $this->input->post('first_name'),
			'username'								=> $this->input->post('username'),
			'email'									=> $this->input->post('email'),
			'phone'									=> $this->input->post('phone'),
			'password'								=> password_hash($this->input->post('password') . SALT, PASSWORD_DEFAULT),
			'group_id'								=> (get_setting('default_membership_group') ? get_setting('default_membership_group') : 2),
			'registered_date'						=> date('Y-m-d'),
			'last_login'							=> time(),
			'status'								=> (get_setting('auto_active_registration') ? 1 : 0)
		);
		
		/* insert user with safe checkpoint */
		if($this->model->insert('app__users', $prepare, 1))
		{
			$insert_id								= $this->model->insert_id();
			
			/* insert subscription */
			$this->_insert_subscription($insert_id);
			
			/* unset stored captcha */
			$this->session->unset_userdata(array('captcha', 'captcha_file'));
			
			if(get_setting('auto_active_registration'))
			{
				$default_membership_group			= (get_setting('default_membership_group') ? get_setting('default_membership_group') : 2);
				/* set the user credential into session */
				$this->session->set_userdata
				(
					array
					(
						'user_id'					=> $insert_id,
						'group_id'					=> $default_membership_group,
						'is_logged'					=> true
					)
				);
				
				/* return to previous page */
				return throw_exception(301, phrase('your_account_has_been_registered_successfully'), base_url('dashboard'), true);
			}
			else
			{
				/* return to previous page */
				return throw_exception(301, phrase('your_account_has_been_registered_successfully_but_need_to_be_activated_before_you_can_sign_in'), base_url(), true);
			}
		}
		else
		{
			/* throw error message */
			return throw_exception(500, phrase('unable_to_register_your_account') . ', ' . phrase('please_try_again_later'));
		}
	}
	
	private function _insert_subscription($user_id = 0)
	{
		/**
		 * Insert subscription
		 */
		$this->model->insert
		(
			'pos__subscriptions',
			array
			(
				'user_id'							=> $user_id,
				'subscription_type'					=> 1,
				'referrer_id'						=> ($this->session->userdata('referrer') ? $this->session->userdata('referrer') : 0),
				'subscription_timestamp'			=> date('Y-m-d H:i:s'),
				'subscription_status'				=> 1
			)
		);
		
		$insert_id									= $this->model->insert_id();
		
		/**
		 * Insert master tax
		 */
		$this->model->insert
		(
			'pos__taxes',
			array
			(
				'subscription_id'					=> $insert_id,
				'name'								=> phrase('no_tax'),
				'tax'								=> 0,
				'status'							=> 1
			)
		);
	}
}
