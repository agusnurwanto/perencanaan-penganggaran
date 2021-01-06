<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User Authentication
 * This is login page module
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Auth extends Aksara
{
	private $_year_table							= 'ref__tahun';
	private $_year_field_name						= 'tahun';
	private $_year_status_field_name				= 'aktif';
	
	public function __construct()
	{
		parent::__construct();
		
		if(get_setting('google_client_id') && get_setting('google_client_secret'))
		{
			$this->load->library('google');
		}
		
		if(get_setting('facebook_app_id') && get_setting('facebook_app_secret'))
		{
			$this->load->library('fb');
		}
	}
	
	public function index()
	{
		/* check if use is already signed in */
		if(get_userdata('is_logged'))
		{
			return throw_exception(301, phrase('you_have_been_signed_in'), base_url('dashboard'), true);
		}
		
		$this->set_title(phrase('dashboard_access'))
		->set_icon('mdi mdi-lock-open-outline')
		->set_description(phrase('use_your_account_information_to_start_session'))
		->form_callback('_validate_form')
		->render();
	}
	
	/**
	 * validate form
	 */
	public function _validate_form()
	{
		/* check if system apply one device login */
		if(get_setting('one_device_login'))
		{
			// under research
		}
		
		/* load additional library and helper */
		$this->load->library('form_validation');
		$this->load->helper('security');
		
		$this->form_validation->set_rules('username', phrase('username'), 'required');
		$this->form_validation->set_rules('password', phrase('password'), 'required');
		
		/* validate year if enabled */
		if(get_setting('login_annually'))
		{
			$this->form_validation->set_rules('year', phrase('year'), 'required|callback_valid_year');
		}
		
		/* run form validation */
		if($this->form_validation->run() === false)
		{
			return throw_exception(400, $this->form_validation->error_array());
		}
		else
		{
			$username								= $this->input->post('username');
			$password								= $this->input->post('password');
			$execute								= $this->model
			->select
			('
				app__users.user_id,
				app__users.username,
				app__users.password,
				app__users.group_id,
				app__users.language_id,
				app__users.status,
				app__users_privileges.sub_level_1
			')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'left')
			->where('username', $username)
			->or_where('email', $username)
			->get
			(
				'app__users',
				1
			)
			->row();
			
			/* check if user is inactive */
			if($execute && $execute->status != 1)
			{
				return throw_exception(404, phrase('your_account_is_temporary_disabled_or_not_yet_activated'));
			}
			elseif($execute /*&& password_verify($password . SALT, $execute->password)*/)
			// elseif($execute && password_verify($password . SALT, $execute->password))
			{
				/* update the last login timestamp */
				$this->model->update
				(
					'app__users',
					array
					(
						'last_login'				=> time()
					),
					array
					(
						'user_id'					=> $execute->user_id
					),
					1
				);
				
				/* check session store */
				if(1 == $this->input->post('remember_session'))
				{
					/* store session to the current device */
					$this->session->sess_expiration	= 0;
				}
				
				/* set the user credential into session */
				$this->session->set_userdata
				(
					array
					(
						'user_id'					=> $execute->user_id,
						'username'					=> $execute->username,
						'group_id'					=> $execute->group_id,
						'language'					=> $this->model->select('code')->get_where('app__languages', array('id' => $execute->language_id))->row('code'),
						'sub_level_1'				=> $execute->sub_level_1,
						'sub_unit'					=> $execute->sub_level_1,
						'is_logged'					=> true
					)
				);
				
				/* check if system apply the annualy login */
				if(get_setting('login_annually'))
				{
					/* push login year to session */
					$this->session->set_userdata('year', $this->input->post('year'));
				}
				
				return throw_exception(301, phrase('welcome_back') . ', <b>' . get_userdata('first_name') . '</b>! ' . phrase('you_were_logged_in'), base_url('dashboard'), true);
			}
			else
			{
				return throw_exception(400, array('password' => phrase('username_or_email_and_password_did_not_match')));
			}
		}
	}
	
	/**
	 * sign out
	 */
	public function sign_out()
	{
		/**
		 * prepare to revoke google sign token
		 */
		if(get_setting('google_client_id') && get_setting('google_client_secret'))
		{
			$this->google->revokeToken();
		}
		
		$group_id									= get_userdata('group_id');
		
		$this->session->sess_destroy();
		
		return throw_exception(301, phrase('you_were_logged_out'), base_url(), true);
	}
	
	/**
	 * validate selected year
	 */
	public function valid_year($value = null)
	{
		if(!$this->model->table_exists($this->_year_table) || !$this->model->select($this->_year_field_name)->get_where($this->_year_table, array($this->_year_field_name => $value, $this->_year_status_field_name => 1), 1)->row($this->_year_field_name))
		{
			$this->form_validation->set_message('valid_year', phrase('the_year_you_selected_does_not_exists'));
			return false;
		}
		return true;
	}
	
	/**
	 * redirect to google auth url
	 */
	public function google()
	{
		redirect($this->google->get_login_url());
	}
	
	/**
	 * validate google auth
	 */
	public function google_auth()
	{
		$this->load->library('google');
		
		$session									= $this->google->validate();
		
		return $this->_validate($session);
	}
	
	/**
	 * redirect to facebook auth url
	 */
	public function facebook()
	{
		redirect($this->fb->get_login_url());
	}
	
	/**
	 * validate facebook auth
	 */
	public function facebook_auth()
	{
		$this->load->library('fb');
		
		$session									= $this->fb->validate();
		
		return $this->_validate($session);
	}
	
	/**
	 * do validation
	 */
	private function _validate($session = null)
	{
		if($session)
		{
			$query									= $this->model->select
			('
				app__users.user_id,
				app__users.username,
				app__users.group_id,
				app__users.language_id
			')
			->join
			(
				'app__users',
				'app__users.user_id = oauth__login.user_id'
			)
			->get_where
			(
				'oauth__login',
				array
				(
					'oauth__login.oauth_provider'	=> $session->oauth_provider,
					'oauth__login.oauth_uid'		=> $session->oauth_uid
				)
			)
			->row();
			
			if($query)
			{
				/* set the user credential into session */
				$this->session->set_userdata
				(
					array
					(
						'user_id'					=> $query->user_id,
						'username'					=> $query->username,
						'group_id'					=> $query->group_id,
						'language'					=> $this->model->select('code')->get_where('app__languages', array('id' => $query->language_id))->row('code'),
						'is_logged'					=> true
					)
				);
				
				/* check if system apply the annualy login */
				if(get_setting('login_annually'))
				{
					/* push login year to session */
					$this->session->set_userdata('year', date('Y'));
				}
				
				return throw_exception(301, phrase('welcome_back') . ', <b>' . get_userdata('first_name') . '</b>! ' . phrase('you_were_logged_in'), base_url('dashboard'), true);
			}
			else
			{
				$query								= $this->model->select
				('
					user_id
				')
				->get_where
				(
					'app__users',
					array
					(
						'email'						=> $session->email
					)
				)
				->row();
				
				if($query)
				{
					$this->model->insert
					(
						'oauth__login',
						array
						(
							'user_id'				=> $query->user_id,
							'oauth_provider'		=> $session->oauth_provider,
							'oauth_uid'				=> $session->oauth_uid,
							'status'				=> 1
						)
					);
					
					return $this->_validate($session);
				}
			}
			
			$language								= array
			(
				'en'								=> 'english',
				'id'								=> 'indonesian',
				'zh'								=> 'chinese',
				'ja'								=> 'japanese',
				'ru'								=> 'russian',
				'th'								=> 'thai',
				'vi'								=> 'vietnamese',
				'ar'								=> 'arabic'
			);
			
			$photo									= $session->picture;
			$extension								= getimagesize($photo);
			$extension								= image_type_to_extension($extension[2]);
			$upload_name							= sha1(time()) . $extension;
			
			if(copy($photo, UPLOAD_PATH . '/users/' . $upload_name))
			{
				$photo								= $upload_name;
				
				$this->generateThumbnail('users', $upload_name);
			}
			else
			{
				$photo								= 'placeholder.png';
			}
			
			$this->model->insert
			(
				'app__users',
				array
				(
					'email'							=> $session->email,
					'password'						=> '',
					'username'						=> '',
					'first_name'					=> $session->first_name,
					'last_name'						=> $session->last_name,
					'photo'							=> $photo,
					'phone'							=> '',
					'postal_code'					=> '',
					'language_id'					=> $this->model->select('id')->get_where('app__languages', array('code' => (isset($language[$session->language]) ? $language[$session->language] : 'english')))->row('id'),
					'group_id'						=> (get_setting('default_membership_group') ? get_setting('default_membership_group') : 2),
					'registered_date'				=> date('Y-m-d'),
					'last_login'					=> time(),
					'status'						=> (get_setting('auto_active_registration') ? 1 : 0)
				)
			);
			
			if($this->model->affected_rows() > 0)
			{
				$insert_id							= $this->model->insert_id();
				
				$this->model->insert
				(
					'oauth__login',
					array
					(
						'user_id'					=> $insert_id,
						'oauth_provider'			=> $session->oauth_provider,
						'oauth_uid'					=> $session->oauth_uid,
						'status'					=> 1
					)
				);
				
				/* insert subscription */
				$this->_insert_subscription($insert_id);
				
				return $this->_validate($session);
			}
		}
	}
	
	/**
	 * generateThumbnail
	 * Generate the thumbnail of uploaded image
	 *
	 * @access		private
	 */
	private function generateThumbnail($type = null, $source = null)
	{
		/* load and initialize the library */
		$this->load->library('image_lib');
		
		/* initialize for thumbnail creation */
		$this->image_lib->initialize
		(
			array
			(
				'image_library'						=> 'gd2',
				'source_image'						=> UPLOAD_PATH . '/' . $type . '/' . $source,
				'new_image'							=> UPLOAD_PATH . '/' . $type . '/thumbs/' . $source,
				'create_thumb'						=> false,
				'width'								=> (is_numeric(THUMBNAIL_DIMENSION) ? THUMBNAIL_DIMENSION : 250),
				'height'							=> (is_numeric(THUMBNAIL_DIMENSION) ? THUMBNAIL_DIMENSION : 250)
			)
		);
		if($this->image_lib->resize())
		{
			$this->image_lib->clear();
			$this->crop($type, $source, 'thumbs');
		}
			
		/* initialize for icon creation */
		$this->image_lib->initialize
		(
			array
			(
				'image_library'						=> 'gd2',
				'source_image'						=> UPLOAD_PATH . '/' . $type . '/' . $source,
				'new_image'							=> UPLOAD_PATH . '/' . $type . '/icons/' . $source,
				'create_thumb'						=> false,
				'width'								=> (is_numeric(ICON_DIMENSION) ? ICON_DIMENSION : 80),
				'height'							=> (is_numeric(ICON_DIMENSION) ? ICON_DIMENSION : 80)
			)
		);
		if($this->image_lib->resize())
		{
			$this->image_lib->clear();
			$this->crop($type, $source, 'icons');
		}
	}
	
	/**
	 * crop
	 * Crop the uploaded image
	 *
	 * @access		private
	 */
	private function crop($type = null, $source = null, $size = null)
	{
		if('thumbs' == $size)
		{
			$dimension								= (is_numeric(THUMBNAIL_DIMENSION) ? THUMBNAIL_DIMENSION : 250);
		}
		else
		{
			$dimension								= (is_numeric(ICON_DIMENSION) ? ICON_DIMENSION : 80);
		}
		
		$config['image_library'] 					= 'gd2';
		$config['source_image'] 					= UPLOAD_PATH . '/' . $type . '/' . $size . '/' . $source;
		$config['new_image'] 						= UPLOAD_PATH . '/' . $type . '/' . $size . '/' . $source;
		$config['create_thumb'] 					= FALSE;
		$config['maintain_ratio'] 					= FALSE;
		$config['width']     						= $dimension;
		$config['height']   						= $dimension;
		list($width, $height)						= getimagesize($config['source_image']);
		
		if($width >= $height)
		{
			/* master dimension in width because the width is greater or equal to height */
			$config['master_dim']					= 'width';
			$config['x_axis']						= 0;
			$config['y_axis']						= -($width - $height) / 2;
		}
		else
		{
			/* master dimension in height because the height is greater width */
			$config['master_dim']					= 'height';
			$config['x_axis']						= -($height - $width) / 2;
			$config['y_axis']						= 0;
		}
		
		/* load and initialize the library */
		$this->load->library('image_lib');
		$this->image_lib->initialize($config);
		$this->image_lib->crop();
		$this->image_lib->clear();
	}
}
