<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Blog Read
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Read extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->parent_module('blogs');
	}
	
	public function index($category = null, $slug = null)
	{
		if($this->input->get('post_slug'))
		{
			$slug									= $this->input->get('post_slug');
			$category								= $this->model->select('blogs__categories.category_slug')->join
			(
				'blogs__categories',
				'blogs__categories.category_id = blogs.post_category'
			)
			->get_where
			(
				'blogs',
				array
				(
					'blogs.post_slug'			=> $slug
				),
				1
			)
			->row('category_slug');
		}
		
		$this->set_title('{post_title}', phrase('no_post_were_found'))
		->set_icon('mdi mdi-newspaper')
		->set_description('{post_excerpt}')
		->set_output
		(
			array
			(
				'categories'						=> $this->model
				->select
				('
					COUNT(blogs.post_id) AS total_data,
					blogs__categories.category_slug,
					blogs__categories.category_title,
					blogs__categories.category_image
				')
				->join
				(
					'blogs',
					'blogs.post_category = blogs__categories.category_id'
				)
				->where
				(
					array
					(
						'blogs.status'				=> 1
					)
				)
				->order_by('total_data', 'DESC')
				->group_by('category_id')
				->get('blogs__categories')
				->result_array(),
				
				'similar'							=> $this->model
				->select
				('
					blogs.post_slug,
					blogs.post_title,
					blogs.featured_image,
					blogs__categories.category_slug,
					blogs__categories.category_title,
					blogs__categories.category_image
				')
				->join
				(
					'blogs__categories',
					'blogs__categories.category_id = blogs.post_category'
				)
				->where
				(
					array
					(
						'blogs__categories.category_slug'	=> $category,
						'blogs.post_slug !='		=> $slug,
						'blogs.status'				=> 1
					)
				)
				->order_by('post_name', 'RANDOM')
				->limit(10)
				->get('blogs')
				->result_array()
			)
		)
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
		->where
		(
			array
			(
				'blogs.post_slug'					=> $slug,
				'blogs.status'						=> 1
			)
		)
		->limit(1)
		->render('blogs');
	}
}