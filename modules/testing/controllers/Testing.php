<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Testing extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		
		$this->set_title('Testing and Debugging')
		->set_icon('fa fa-bug')/*
		->set_description
		('
			<div class="row">
				<div class="col-xs-2 text-muted text-sm">
					Online
				</div>
				<div class="col-xs-4 text-muted text-sm">
					' . rand(73, 89) . '
				</div>
			</div>
		')*/
		->unset_column('id')
		->unset_field('id')
		->unset_view('id')
		->set_relation
		(
			'relation_key',
			'ref__sub.id',
			'{ref__sub.kd_sub}. {ref__sub.nm_sub}'
		)
		->set_field('description', 'wysiwyg')
		->add_class('description', 'minimal')
		->set_validation
		(
			array
			(
				'title'							=> 'required',
				'description'					=> 'required',
				'relation_key'					=> 'required'
			)
		)
		->render('dummy');
	}
}