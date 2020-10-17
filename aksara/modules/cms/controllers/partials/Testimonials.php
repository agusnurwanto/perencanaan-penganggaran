<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Testimonials Management
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Testimonials extends Aksara
{
	private $_table									= 'testimonials';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('manage_testimonials'))
		->set_icon('mdi mdi-comment-account-outline')
		->set_primary('testimonial_id')
		->unset_column('testimonial_id, testimonial_content, timestamp')
		->unset_field('testimonial_id')
		->unset_view('testimonial_id')
		->set_field
		(
			array
			(
				'testimonial_content'				=> 'textarea',
				'timestamp'							=> 'current_timestamp',
				'status'							=> 'boolean'
			)
		)
		->set_relation
		(
			'language_id',
			'app__languages.id',
			'{app__languages.language}',
			array
			(
				'app__languages.status'				=> 1
			)
		)
		->set_validation
		(
			array
			(
				'testimonial_title'					=> 'required|xss_clean',
				'testimonial_content'				=> 'required|xss_clean',
				'first_name'						=> 'required|xss_clean',
				'last_name'							=> 'xss_clean',
				'language_id'						=> 'required',
				'status'							=> 'is_boolean'
			)
		)
		->set_alias
		(
			array
			(
				'testimonial_title'					=> phrase('title'),
				'testimonial_content'				=> phrase('testimony'),
				'first_name'						=> phrase('first_name'),
				'last_name'							=> phrase('last_name'),
				'language_id'						=> phrase('language'),
				'status'							=> phrase('status')
			)
		)
		->merge_field('first_name, last_name')
		->merge_content('{first_name} {last_name}', phrase('full_name'))
		->render($this->_table);
	}
}