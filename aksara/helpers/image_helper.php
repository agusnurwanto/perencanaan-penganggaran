<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Image Helper
 *
 * This helper manages the images object
 *
 * @package		Image
 * @version		1.0
 * @author 		Aby Dahana <abydahana@gmail.com>
 * @copyright 	Copyright (c) 2016, Aby Dahana
 * @link		https://www.facebook.com/abyprogrammer
**/

if(!function_exists('get_image'))
{
	function get_image($type = null, $name = null, $dimension = null)
	{
		$CI						=& get_instance();
		if($dimension == 'thumb')
		{
			$placeholder		= 'uploads/' . $type . '/thumbs/placeholder.png';
			$file				= 'uploads/' . $type . '/thumbs/' . $name;
		}
		elseif($dimension == 'icon')
		{
			$placeholder		= 'uploads/' . $type . '/icons/placeholder.png';
			$file				= 'uploads/' . $type . '/icons/' . $name;
		}
		else
		{
			$placeholder		= 'uploads/' . $type . '/placeholder.png';
			$file				= 'uploads/' . $type . '/' . $name;
		}
		
		if(is_file($file) && file_exists($file))
		{
			$image				= $file;
		}
		else
		{
			$image				= $placeholder;
		}
		
		$method					= array_reverse($CI->uri->segment_array());
		$method					= (isset($method[0]) ? $method[0] : null);
		
		if((in_array($CI->input->get('method'), array('print', 'embed', 'pdf', 'download')) || 'document' == $CI->input->get('r')) && 'print' != $method && 'embed' != $method && defined('PDF_LIB') && 'mpdf' == strtolower(PDF_LIB))
		{
			$type				= pathinfo(FCPATH . $image, PATHINFO_EXTENSION);
			$data				= file_get_contents($image);
			return 'data:image/' . $type . ';base64,' . base64_encode($data);
		}
		
		return base_url($image);
	}
}

if(!function_exists('get_og_image'))
{
	function get_og_image($content = null)
	{
		preg_match('/<img id="og-image" src="(.*?)"/', $content, $match);
		
		if(isset($match[1]) && !empty($match[1]))
		{
			return $match[1];
		}
		else
		{
			return get_image('settings', get_setting('app_logo'));
		}
	}
}