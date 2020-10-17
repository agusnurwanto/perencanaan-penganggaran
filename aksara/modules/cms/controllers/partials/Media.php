<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Media Management
 * Manage uploaded media.
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Media extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->restrict_on_demo();
		
		$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('manage_media'))
		->set_icon('mdi mdi-folder-image')
		->set_output('results', $this->_extract_array())
		->render();
	}
	
	private function _extract_array($data = array(), $folder = null)
	{
		/* load required helper */
		$this->load->helper('directory');
		
		if(!$data)
		{
			$data									= directory_map(UPLOAD_PATH);
		}
		$output										= null;
		$num										= 0;
		foreach($data as $key => $val)
		{
			if(is_array($val) && sizeof($val) > 0)
			{
				$this->_extract_array($val, str_replace('\\', '', $key));
			}
			elseif(!is_array($val))
			{
				if($folder)
				{
					$output							.= '
						<div class="col-md-2">
							<div class="card text-center">
								<i class="mdi mdi-folder-image"></i>
								<br />
								' . $folder . '
							</div>
						</div>
					';
				}
				else
				{
					$output							.= '
						<div class="col-md-2">
							<div class="card text-center">
								<i class="mdi mdi-image-size-select-actual"></i>
								<br />
								' . $val . '
							</div>
						</div>
					';
				}
			}
			if(($num + 1) % 6 == 0)
			{
				$output								.= '</div><div class="row">';
			}
			$num++;
		}
		return '
			<div class="row">
				' . $output . '
			</div>
		';
	}
}