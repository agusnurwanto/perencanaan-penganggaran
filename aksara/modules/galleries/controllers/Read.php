<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Gallery Details
 * Show the individual photo from the gallery
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
	}
	
	public function index($category = null, $slug = null)
	{
		$this->set_title('{gallery_title}', phrase('gallery_was_not_found'))
		->set_description('{gallery_description}')
		->set_icon('mdi mdi-image')
		->set_output
		(
			'similar',
			$this->model
			->select('gallery_images')
			->get_where
			(
				'galleries',
				array
				(
					'gallery_slug'					=> $category
				),
				1
			)
			->row('gallery_images')
		)
		->select
		('
			galleries.*,
			app__users.first_name,
			app__users.last_name,
			app__users.username,
			app__users.photo
		')
		->join
		(
			'app__users',
			'app__users.user_id = galleries.author'
		)
		->where
		(
			array
			(
				'galleries.gallery_slug'			=> $category
			)
		)
		->limit(1)
		->modal_size('modal-lg')
		->render('galleries');
	}
}