<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Blog Comments
 * Manage blog comment submitted by visitors
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Comments extends Aksara
{
	private $_table									= 'blogs__replies';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(array(1, 2)); // only user with group id as listed can access this module
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('blog_comments'))
		->set_icon('mdi mdi-comment-outline')
		->unset_action('create')
		->unset_column('id, parent_id')
		->unset_field('id, parent_id, post_id, timestamp, user_id')
		->unset_view('id, parent_id')
		->column_order('post_title, comment, first_name, last_name, status')
		->set_field
		(
			array
			(
				'comment'							=> 'textarea',
				'timestamp'							=> 'current_timestamp',
				'status'							=> 'boolean'
			)
		)
		->set_relation
		(
			'post_id',
			'blogs.post_id',
			'{blogs.post_title}'
		)
		->set_relation
		(
			'user_id',
			'app__users.user_id',
			'{app__users.first_name} {app__users.last_name}'
		)
		->merge_content('{first_name} {last_name}', phrase('user'))
		->render($this->_table);
	}
}