<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Gallery Categories
 * Show the gallery under the category
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Category extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->_primary								= $this->input->get('gallery_id');
		$this->parent_module('galleries');
	}
	
	public function index($slug = null)
	{
		if(!$slug && $this->input->get('gallery_slug'))
		{
			$slug									= $this->input->get('gallery_slug');
		}
		
		$this->set_title('{gallery_title}', phrase('gallery_was_not_found'))
		->set_description('{gallery_description}')
		->set_icon('mdi mdi-image')
		->where('gallery_slug', $slug)
		->limit(1)
		->render('galleries');
	}
}