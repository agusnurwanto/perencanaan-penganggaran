<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Page Management
 * Manage frontend pages
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Pages extends Aksara
{
	private $_table									= 'pages';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(array(1, 2)); // only user with group id 1 can access this module
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('manage_frontend_pages'))
		->set_icon('mdi mdi-file-document-outline')
		->set_primary('page_id')
		->unset_column('page_id, author, page_slug, page_content, created_timestamp')
		->unset_field('page_id, author')
		->unset_view('page_id, author')
		->column_order('page_title, page_description, carousel_title, faq_title, updated, status')
		->field_order('page_title, page_description, carousel_id, faq_id, created_timestamp, updated_timestamp, status')
		->set_field
		(
			array
			(
				'page_description'					=> 'textarea',
				'page_content'						=> 'wysiwyg',
				'created_timestamp'					=> 'current_timestamp',
				'updated_timestamp'					=> 'current_timestamp',
				'status'							=> 'boolean'
			)
		)
		->set_field('page_slug', 'to_slug', 'page_title')
		->set_field('page_title', 'hyperlink', 'pages', array('page_slug' => 'page_slug'), true)
		
		->add_action('option', '../../pages', phrase('view_page'), 'btn-success', 'mdi mdi-eye', array('page_slug' => 'page_slug'), true)
		
		->set_relation
		(
			'carousel_id',
			'pages__carousels.carousel_id',
			'{pages__carousels.carousel_title}',
			array
			(
				'pages__carousels.status'			=> 1
			)
		)
		->set_relation
		(
			'faq_id',
			'pages__faqs.faq_id',
			'{pages__faqs.faq_title}',
			array
			(
				'pages__faqs.status'				=> 1
			)
		)
		->set_validation
		(
			array
			(
				'page_title'						=> 'required|max_length[64]|is_unique[' . $this->_table . '.page_title.page_id.' . $this->input->get('page_id') . ']',
				'page_content'						=> 'required',
				'status'							=> 'is_boolean'
			)
		)
		->set_default
		(
			array
			(
				'author'							=> get_userdata('user_id')
			)
		)
		->set_alias
		(
			array
			(
				'page_title'						=> phrase('title'),
				'page_description'					=> phrase('description'),
				'page_slug'							=> phrase('slug'),
				'page_content'						=> phrase('content'),
				'carousel_id'						=> phrase('carousel'),
				'carousel_title'					=> phrase('carousel'),
				'faq_id'							=> phrase('faq'),
				'faq_title'							=> phrase('faq'),
				'created_timestamp'					=> phrase('created'),
				'updated_timestamp'					=> phrase('updated')
			)
		)
		->field_position
		(
			array
			(
				'carousel_id'						=> 2,
				'faq_id'							=> 2,
				'created_timestamp'					=> 2,
				'updated_timestamp'					=> 2,
				'status'							=> 2
			)
		)
		->column_size
		(
			array
			(
				1									=> 'col-md-8',
				2									=> 'col-md-4'
			)
		)
		->modal_size('modal-xl')
		->render($this->_table);
	}
}