<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Menu Management
 * This module used to manage the navigation menus.
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Menus extends Aksara
{
	private $_table									= 'app__menus';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
		$this->unset_delete('menu_id', array('1', '2')); // prevent user to delete group id 1
	}
	
	public function index()
	{
		$this->set_title(phrase('menu_management'))
		->set_icon('mdi mdi-menu')
		->set_primary('menu_id')
		->set_field('serialized_data', 'custom_format', $this->_menus())
		->set_relation
		(
			'group_id',
			'app__groups.group_id',
			'{app__groups.group_name}',
			array
			(
				'app__groups.status'				=> 1
			)
		)
		->set_field
		(
			'menu_placement',
			'dropdown',
			array
			(
				'header'							=> phrase('header'),
				'sidebar'							=> phrase('sidebar')
			)
		)
		->set_field('status', 'boolean')
		->unset_column('menu_id, serialized_data')
		->unset_view('menu_id')
		->unset_field('menu_id')
		->set_alias
		(
			array
			(
				'menu_placement'					=> phrase('placement'),
				'menu_label'						=> phrase('menu_label'),
				'menu_description'					=> phrase('description'),
				'serialized_data'					=> phrase('menus'),
				'group_id'							=> phrase('group'),
				'group_name'						=> phrase('group'),
				'status'							=> phrase('status')
			)
		)
		->order_by
		(
			array
			(
				'group_id'							=> 'ASC'
			)
		)
		->modal_size('modal-lg')
		->render($this->_table);
	}
	
	private function _menus()
	{
		$output										= null;
		$menus										= null;
		$serialized									= $this->model->select('serialized_data')->get_where($this->_table, array('menu_id' => $this->input->get('menu_id')), 1)->row('serialized_data');
		$serialized_menus							= json_decode($serialized);
		if($serialized_menus)
		{
			foreach($serialized_menus as $key => $val)
			{
				if(!isset($val->id) || !isset($val->label) || !isset($val->slug)) continue;
				$menus								.= '
					<li id="' . $key . '" class="mt-2" data-icon="' . ($val->icon ? $val->icon : 'mdi mdi-radiobox-blank') . '" data-label="' . $val->label . '" data-slug="' . $val->slug . '" data-newtab="' . (isset($val->newtab) ? $val->newtab : 0) . '">
						<div class="input-group input-group-sm">
							<div class="input-group-prepend">
								<button class="btn btn-secondary" type="button">
									<i class="mdi mdi-reorder-horizontal"></i>
								</button>
								<button class="btn btn-secondary menu-icon ignore-sort" type="button" role="iconpicker" data-iconset="materialdesign" data-icon="' . ($val->icon ? $val->icon : 'mdi mdi-radiobox-blank') . '"></button>
							</div>
							<input type="text" class="form-control menu-label ignore-sort" placeholder="' . phrase('menu_label') . '" value="' . $val->label . '" />
							<input type="text" class="form-control menu-slug ignore-sort" placeholder="' . phrase('menu_slug') . '" value="' . $val->slug . '" />
							<div class="input-group-append">
								<div class="input-group-text bg-secondary border-0 ignore-sort pt-0" data-toggle="tooltip" title="' . phrase('open_in_new_tab') . '">
									<input type="checkbox" class="menu-newtab"' . (isset($val->newtab) && 1 == $val->newtab ? ' checked' : null) . ' />
								</div>
								<button type="button" class="btn btn-secondary item-add children ignore-sort">
									<i class="mdi mdi-plus"></i>
								</button>
								<button type="button" class="btn btn-secondary item-remove ignore-sort">
									<i class="mdi mdi-window-close"></i>
								</button>
							</div>
						</div>
						' . (isset($val->children) && is_array($val->children) && sizeof($val->children) > 0 ? $this->_children_check($val->children, $key) : null) . '
					</li>
				';
			}
		}
		$output										= '
			<div class="form-group" id="serialized_data_input">
				<ul class="list sortable" role="sortable-menu">
					<li class="ignore-sort">
						<button type="button" class="btn btn-default btn-sm btn-block item-add masking">
							<i class="mdi mdi-plus mdi-2x"></i>
						</button>
					</li>
					<li id="{{id}}" class="mt-2 item-placeholder hidden" data-icon="mdi mdi-radiobox-blank" data-label="' . phrase('menu_label') . '" data-slug="home" data-newtab="0">
						<div class="input-group input-group-sm">
							<div class="input-group-prepend">
								<button class="btn btn-secondary" type="button">
									<i class="mdi mdi-reorder-horizontal"></i>
								</button>
								<button class="btn btn-secondary menu-icon ignore-sort" type="button" role="iconpicker" data-iconset="materialdesign" data-icon="mdi mdi-radiobox-blank"></button>
							</div>
							<input type="text" class="form-control menu-label ignore-sort" placeholder="' . phrase('menu_label') . '" value="' . phrase('menu_label') . '" />
							<input type="text" class="form-control menu-slug ignore-sort" placeholder="' . phrase('menu_slug') . '" value="" />
							<div class="input-group-append">
								<div class="input-group-text bg-secondary border-0 ignore-sort pt-0" data-toggle="tooltip" title="' . phrase('open_in_new_tab') . '">
									<input type="checkbox" class="menu-newtab" />
								</div>
								<button type="button" class="btn btn-secondary item-add children ignore-sort">
									<i class="mdi mdi-plus"></i>
								</button>
								<button type="button" class="btn btn-secondary item-remove ignore-sort">
									<i class="mdi mdi-window-close"></i>
								</button>
							</div>
						</div>
					</li>
					' . $menus . '
				</ul>
				<input type="hidden" name="serialized_data" value="' . htmlspecialchars($serialized) . '" class="serialized_data" />
			</div>
		';
		if('read' != $this->_method)
		{
			return $output;
		}
		else
		{
			$output									= null;
			$menus									= null;
			if($serialized_menus)
			{
				foreach($serialized_menus as $key => $val)
				{
					if(!isset($val->id) || !isset($val->label) || !isset($val->slug)) continue;
					$menus							.= '
						<li>
							<a href="' . base_url($val->slug) . '" target="_blank">
								<i class="' . ($val->icon ? $val->icon : 'mdi mdi-radiobox-blank') . '"></i>
								&nbsp;
								' . $val->label . '
							</a>
							' . (isset($val->children) && is_array($val->children) && sizeof($val->children) > 0 ? $this->_children_tree($val->children) : null) . '
						</li>
					';
				}
				$output								= '
					<ul class="list-unstyled">
						' . $menus . '
					</ul>
				';
			}
			return $output;
		}
	}
	
	private function _children_check($data = array(), $id = null)
	{
		$output										= null;
		$menus										= null;
		if($data)
		{
			foreach($data as $key => $val)
			{
				$menus								.= '
					<li id="' . $id . '" class="mt-2" data-icon="' . ($val->icon ? $val->icon : 'mdi mdi-radiobox-blank') . '" data-label="' . $val->label . '" data-slug="' . $val->slug . '" data-newtab="' . (isset($val->newtab) ? $val->newtab : 0) . '">
						<div class="input-group input-group-sm">
							<div class="input-group-prepend">
								<button class="btn btn-secondary" type="button">
									<i class="mdi mdi-reorder-horizontal"></i>
								</button>
								<button class="btn btn-secondary menu-icon ignore-sort" type="button" role="iconpicker" data-iconset="materialdesign" data-icon="' . ($val->icon ? $val->icon : 'mdi mdi-radiobox-blank') . '"></button>
							</div>
							<input type="text" class="form-control menu-label ignore-sort" placeholder="' . phrase('menu_label') . '" value="' . $val->label . '" />
							<input type="text" class="form-control menu-slug ignore-sort" placeholder="' . phrase('menu_slug') . '" value="' . $val->slug . '" />
							<div class="input-group-append">
								<div class="input-group-text bg-secondary border-0 ignore-sort pt-0" data-toggle="tooltip" title="' . phrase('open_in_new_tab') . '">
									<input type="checkbox" class="menu-newtab"' . (isset($val->newtab) && 1 == $val->newtab ? ' checked' : null) . ' />
								</div>
								<button type="button" class="btn btn-secondary item-add children ignore-sort">
									<i class="mdi mdi-plus"></i>
								</button>
								<button type="button" class="btn btn-secondary item-remove ignore-sort">
									<i class="mdi mdi-window-close"></i>
								</button>
							</div>
						</div>
						' . (isset($val->children) && is_array($val->children) && sizeof($val->children) > 0 ? $this->_children_check($val->children, $key) : null) . '
					</li>
				';
			}
		}
		if($menus)
		{
			$output									= '
				<ul>
					' . $menus . '
				</ul>
			';
		}
		return $output;
	}
	
	private function _children_tree($data = array())
	{
		$output										= null;
		$menus										= null;
		if($data)
		{
			foreach($data as $key => $val)
			{
				$menus								.= '
					<li>
						<a href="' . base_url($val->slug) . '" target="_blank">
							<i class="' . ($val->icon ? $val->icon : 'mdi mdi-radiobox-blank') . '"></i>
							&nbsp;
							' . $val->label . '
						</a>
						' . (isset($val->children) && is_array($val->children) && sizeof($val->children) > 0 ? $this->_children_tree($val->children) : null) . '
					</li>
				';
			}
		}
		if($menus)
		{
			$output									= '
				<ul class="list-unstyled ml-4">
					' . $menus . '
				</ul>
			';
		}
		return $output;
	}
}