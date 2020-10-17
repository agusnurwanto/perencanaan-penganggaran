<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Home extends Aksara
{
	function __construct()
	{
		parent::__construct();
	}
	public function index()
	{
		if('maps' == $this->input->post('render'))
		{
			$this->_render_maps();
		}
		else
		{
			$carousels										= json_decode($this->model->select('carousel_content')->get_where('pages__carousels', array('carousel_id' => 1), 1)->row('carousel_content'), true);
			$random_galleries								= $this->model->order_by('gallery_id', 'RANDOM')->get_where('galleries', array('status' => 1), 4)->result_array();
			$random_news									= $this->model->order_by('post_id', 'RANDOM')->get_where('blogs', array('status' => 1), 6)->result_array();
			$random_peoples									= $this->model->order_by('people_id', 'RANDOM')->get_where('peoples', array('status' => 1), 4)->result_array();
			
			$this->set_title(phrase('welcome_to') . ' ' . get_setting('app_name'))
			->set_description(get_setting('app_description'))
			->set_output
			(
				array
				(
					'carousels'								=> $carousels,
					'latest_galleries'						=> $random_galleries,
					'latest_news'							=> $random_news,
					'latest_peoples'						=> $random_peoples,
					'datasets_total'						=> $this->model->get_where('galleries', array('status' => 1))->num_rows(),
					'verified_datasets_total'				=> $this->model->get_where('blogs', array('status' => 1))->num_rows(),
					'dataset_categories_total'				=> $this->model->get_where('peoples', array('status' => 1))->num_rows()
				)
			)
			->render();
		}
	}
	
	private function _render_maps()
	{
		$data_category										= ($this->input->post('data') ? $this->input->post('data') : array(null));
		$center												= json_decode(get_setting('office_map'), true);
		$c_latitude											= (isset($center['lat']) ? $center['lat'] : null);
		$c_longitude										= (isset($center['lng']) ? $center['lng'] : null);
		$output												= array
		(
			'latitude'										=> $c_latitude,
			'longitude'										=> $c_longitude,
			'zoom'											=> 14
		);
		$coordinates										= array();
		$query												= $this->model->get_where('ta__musrenbang_kelurahan')->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$target										= 'data';
				$background									= null;
				$tag_output									= null;
				$images										= json_decode($val['map_images'], true);
				if($images)
				{
					foreach($images as $src => $alt)
					{
						if($src)
						{
							$background						= $src;
							break;
						}
					}
				}
				$latlong									= json_decode($val['map_coordinates'], true);
				$latitude									= (isset($latlong['lat']) ? $latlong['lat'] : null);
				$longitude									= (isset($latlong['lng']) ? $latlong['lng'] : null);
				$coordinates[]								= array
				(
					'latitude'								=> $latitude,
					'longitude'								=> $longitude,
					'title'									=> $val['usulan'],
					'description'							=> '<h4 class="no-margin text-stroke">' . $val['usulan'] . '</h4><h5 class="no-margin text-stroke">' . truncate($val['map_content'], 160) . '</h5><h5><a href="javascript:void(0);" class="btn btn-sm btn-primary ajaxMap"><i class="fa fa-search-plus"></i> ' . phrase('details') . '</a></h5>',
					'icon'									=> get_image('kelurahan', $background, 'icon'),
					'background'							=> get_image('kelurahan', $background, 'thumb')
				);
			}
		}
		$output['coordinates']								= $coordinates;
		make_json
		(
			$output
		);
	}
}