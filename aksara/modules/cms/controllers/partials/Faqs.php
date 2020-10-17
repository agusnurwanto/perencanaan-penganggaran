<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * FAQ Management
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Faqs extends Aksara
{
	private $_table									= 'pages__faqs';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('manage_frequently_asked_questions'))
		->set_icon('mdi mdi-file-question')
		->unset_column('faq_id, created_timestamp, language')
		->unset_field('faq_id')
		->unset_view('faq_id')
		->set_field
		(
			array
			(
				'faq_description'					=> 'textarea',
				'faq_content'						=> 'faqs',
				'language'							=> 'language_picker',
				'created_timestamp'					=> 'current_timestamp',
				'updated_timestamp'					=> 'current_timestamp',
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
				'title'								=> 'required',
				'language_id'						=> 'required',
				'status'							=> 'is_boolean'
			)
		)
		->set_alias
		(
			array
			(
				'faq_title'							=> phrase('title'),
				'faq_description'					=> phrase('description'),
				'faq_content'						=> phrase('contents'),
				'language'							=> phrase('language'),
				'created_timestamp'					=> phrase('created'),
				'updated_timestamp'					=> phrase('updated'),
				'language_id'						=> phrase('language'),
				'status'							=> phrase('status')
			)
		)
		->render($this->_table);
	}
}