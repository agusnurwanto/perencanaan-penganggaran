<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Blog Search
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Search extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_keywords							= htmlspecialchars(($this->input->post('q') ? $this->input->post('q') : $this->input->get('q')));
		$this->parent_module('blogs');
	}
	
	public function index()
	{
		if('autocomplete' == $this->input->post('method'))
		{
			return $this->_autocomplete();
		}
		$this->set_title(phrase('search'))
		->set_description(phrase('search_result_for') . ' ' . ($this->_keywords ? $this->_keywords : phrase('all')))
		->set_icon('mdi mdi-magnify')
		->select
		('
			blogs.post_slug,
			blogs.post_title,
			blogs.post_excerpt,
			blogs.post_tags,
			blogs.featured_image,
			blogs.updated_timestamp,
			blogs__categories.category_slug,
			blogs__categories.category_title,
			blogs__categories.category_description,
			blogs__categories.category_image,
			app__users.first_name,
			app__users.last_name,
			app__users.username,
			app__users.photo
		')
		->join
		(
			'blogs__categories',
			'blogs__categories.category_id = blogs.post_category'
		)
		->join
		(
			'app__users',
			'app__users.user_id = blogs.author'
		)
		->like
		(
			array
			(
				'blogs.post_title'					=> $this->_keywords
			)
		)
		->or_like
		(
			array
			(
				'blogs.post_excerpt'				=> $this->_keywords
			)
		)
		->order_by('blogs.updated_timestamp', 'DESC')
		->render('blogs');
	}
	
	/**
	 * query for autocomplete search
	 */
	private function _autocomplete()
	{
		$query										= $this->model
		->select
		('
			blogs.post_slug,
			blogs.post_title,
			blogs.post_excerpt,
			blogs.post_tags,
			blogs.featured_image,
			blogs.updated_timestamp,
			blogs__categories.category_slug,
			blogs__categories.category_title,
			blogs__categories.category_description,
			blogs__categories.category_image,
			app__users.first_name,
			app__users.last_name
		')
		->join
		(
			'blogs__categories',
			'blogs__categories.category_id = blogs.post_category'
		)
		->join
		(
			'app__users',
			'app__users.user_id = blogs.author'
		)
		->like
		(
			array
			(
				'blogs.post_title'					=> $this->_keywords
			)
		)
		->or_like
		(
			array
			(
				'blogs.post_excerpt'				=> $this->_keywords
			)
		)
		->get_where
		(
			'blogs',
			array
			(
				'blogs.status'						=> 1
			)
		)
		->result();
		
		$suggestions								= array();
		
		if($query)
		{
			foreach($query as $key => $val)
			{
				$suggestions[]						= array
				(
					'value'							=> $val->post_slug,
					'label'							=> $val->post_title,
					'description'					=> truncate($val->post_excerpt, 120),
					'image'							=> get_image('blogs', $val->featured_image, 'icon'),
					'target'						=> go_to(array($val->category_slug, $val->post_slug))
				);
			}
		}
		
		return make_json
		(
			array
			(
				'suggestions'						=> ($suggestions ? $suggestions : null)
			)
		);
	}
}