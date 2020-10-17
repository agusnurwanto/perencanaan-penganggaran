<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Blogs
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Blogs extends Aksara
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->set_title(phrase('our_news_updates'))
		->set_description(phrase('follow_an_updates_from_us'))
		->set_icon('mdi mdi-newspaper')
		->set_output
		(
			array
			(
				/* get headline news */
				'headline'							=> $this->model->select
				('
					blogs__categories.*,
					blogs.post_id,
					blogs.post_slug,
					blogs.post_title,
					blogs.post_excerpt,
					blogs.post_tags,
					blogs.featured_image,
					blogs.updated_timestamp
				')
				->join
				(
					'blogs__categories',
					'blogs__categories.category_id = blogs.post_category'
				)
				->order_by('updated_timestamp', 'DESC')
				->get_where
				(
					'blogs',
					array
					(
						'blogs.status'				=> 1,
						'blogs.headline'			=> 1
					),
					10
				)
				->result(),
				
				/* get the latest galleries */
				'latest_galleries'					=> $this->model->get_where
				(
					'galleries',
					array
					(
						'status'					=> 1
					),
					4
				)
				->result()
			)
		)
		->select
		('
			blogs__categories.*,
			COUNT(blogs.post_id) AS total_data
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
				'blogs__categories.status'			=> 1,
				'blogs.status'						=> 1
			)
		)
		->order_by('category_title', 'RANDOM')
		->group_by('category_id')
		->render('blogs__categories');
	}
	
	public function get_articles($category_id = 0)
	{
		return $this->model->select
		('
			blogs__categories.*,
			blogs.post_id,
			blogs.post_slug,
			blogs.post_title,
			blogs.post_excerpt,
			blogs.post_tags,
			blogs.featured_image,
			blogs.updated_timestamp
		')
		->join
		(
			'blogs',
			'blogs.post_category = blogs__categories.category_id'
		)
		->order_by('blogs.updated_timestamp', 'DESC')
		->get_where
		(
			'blogs__categories',
			array
			(
				'blogs__categories.category_id'		=> $category_id,
				'blogs__categories.status'			=> 1,
				'blogs.status'						=> 1
			),
			6
		)
		->result();
	}
}