<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Script
 * Merge multiple javascript into single file.
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Scripts extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->load->library('user_agent');
	}
	
	public function index()
	{
		$language									= $this->db->select('locale')->get_where
		(
			'app__languages',
			array
			(
				'code'								=> ($this->session->userdata('language') ? $this->session->userdata('language') : '')
			)
		)
		->row('locale');
		$language									= strstr($language, ',', true);
		
		$file_list									= array
		(
			ASSET_PATH . '/jquery/jquery.min.js',
			ASSET_PATH . '/local/js/i18n.min.js',
			ASSET_PATH . '/local/js/require.min.js',
			ASSET_PATH . '/popper/popper.min.js',
			ASSET_PATH . '/bootstrap/bootstrap.min.js',
			ASSET_PATH . '/sprintf/sprintf.min.js',
			ASSET_PATH . '/actual/actual.min.js',
			ASSET_PATH . '/mcustomscrollbar/jquery.mousewheel.min.js',
			ASSET_PATH . '/mcustomscrollbar/jquery.mCustomScrollbar.min.js',
			ASSET_PATH . '/select2/select2.min.js',
			ASSET_PATH . '/datepicker/datepicker.min.js',
			ASSET_PATH . '/fileuploader/fileuploader.min.js',
			(strtolower($this->agent->browser()) == 'internet explorer' ? ASSET_PATH . '/local/js/ie.fix.min.js' : null), /* only applied to IE */
			ASSET_PATH . '/visible/visible.min.js',
			ASSET_PATH . '/detection/scanner.min.js',
			ASSET_PATH . '/lazyload/lazyload.min.js',
			ASSET_PATH . '/local/js/function.min.js',
			ASSET_PATH . '/local/js/retrigger.min.js',
			ASSET_PATH . '/local/js/global.min.js',
			ASSET_PATH . '/local/js/component.min.js',
			ASSET_PATH . '/local/js/simda.min.js',
			ASSET_PATH . '/local/js/interact.min.js'
		);

		/**
		 * Ideally, you wouldn't need to change any code beyond this point.
		 */
		$buffer										= '
			var config =
			{
				base_url: "' . htmlspecialchars(base_url()) . '",
				asset_url: "' . htmlspecialchars(asset_url()) . '",
				app_name: "' . htmlspecialchars(get_setting('app_name')) . '",
				app_icon: "' . htmlspecialchars(get_image('settings', get_setting('app_icon'), 'icon')) . '",
				content_wrapper: "#content-wrapper",
				login_annually: ' . (int) get_setting('login_annually') . ',
				registration_enabled: ' . (int) get_setting('enable_frontend_registration') . ',
				active_years: ' . get_active_years(true) . ',
				language: "' . htmlspecialchars($language) . '",
				map_provider: "' . htmlspecialchars(get_setting('maps_provider')) . '",
				google_maps_api_key: "' . htmlspecialchars(gmaps_api()) . '",
				openlayers_search_provider: "' . htmlspecialchars(get_setting('openlayers_search_provider')) . '",
				openlayers_search_key: "' . htmlspecialchars(get_setting('openlayers_search_key')) . '",
				map_center: ' . (json_decode(get_setting('office_map')) ? get_setting('office_map') : '{}') . ',
				google_auth: ' . (get_setting('google_client_id') && get_setting('google_client_secret') ? 'true' : 'false') . ',
				facebook_auth: ' . (get_setting('facebook_app_id') && get_setting('facebook_app_secret') ? 'true' : 'false') . '
				
			},
			phrase									= ' . json_encode(json_decode($this->_i18n()), JSON_UNESCAPED_SLASHES) . ';
		';
		foreach($file_list as $js => $src)
		{
			if(file_exists($src))
			{
				$buffer								.= file_get_contents($src);
			}
		}
		$this->output->set_content_type('js', 'utf-8');
		$this->output->set_output($buffer);
	}
	
	private function _i18n()
	{
		if($this->session->userdata('language'))
		{
			$locale									= $this->session->userdata('language');
		}
		else
		{
			$language_id							= (get_setting('app_language') ? get_setting('app_language') : 1);
			$locale									= $this->db->select('code')->get_where
			(
				'app__languages',
				array
				(
					'id'							=> $language_id
				)
			)
			->row('code');
		}
		
		if(file_exists(file_get_contents('public/languages/' . $locale . '.json')))
		{
			$phrases								= file_get_contents('public/languages/' . $locale . '.json');
		}
		else
		{
			$phrases								= file_get_contents('public/languages/' . $locale . '.json');
		}
		
		return $phrases;
	}
}