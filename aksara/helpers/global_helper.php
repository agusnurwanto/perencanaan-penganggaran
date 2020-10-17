<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Global Helper
 *
 * This helper is preparing all required data for site needs
 * such as menus and application's settings
 *
 * @package		Global
 * @version		1.0
 * @author 		Aby Dahana <abydahana@gmail.com>
 * @copyright 	Copyright (c) 2016, Aby Dahana
 * @link		https://www.facebook.com/abyprogrammer
**/

if(!function_exists('get_setting'))
{
	function get_setting($field = null)
	{
		$CI											=& get_instance();
		
		if($field == 'build_version')
		{
			return SOFTWARE_VERSION . CI_VERSION;
		}
		elseif($field == 'aksara_version')
		{
			return SOFTWARE_VERSION;
		}
		
		$CI->load->database();
		if($CI->db->field_exists($field, 'app__settings'))
		{
			$query									= $CI->db->select($field)->get('app__settings', 1);
			return $query->row($field);
		}
		else
		{
			return false;
		}
	}
}

if(!function_exists('truncate'))
{
	function truncate($string = null, $limit = 0, $break = '.', $pad = '...')
	{
		$string										= preg_replace('/<iframe.*?\/iframe>/i','', $string);
		$string										= preg_replace('/<script.*?\/script>/i','', $string);
		$string										= preg_replace('/<noscript.*?\/noscript>/i','', $string);
		$string										= preg_replace('/<style.*?\/style>/i','', $string);
		$string										= preg_replace('/<link.*/i','', $string);
		$string										= preg_replace('/<embed.*?\/embed>/i','', $string);
		$string										= preg_replace('/<object.*?\/object>/i','', $string);
		$string										= str_replace('&nbsp;', ' ', $string);
		$string										= strip_tags($string);
		$string										= strip_tags(str_replace(array("\r", "\n"), '', $string));
		if($limit && strlen($string) >= $limit)
		{
			$string									= substr($string, 0, $limit) . $pad;
		}
		else
		{
			$string									=  $string;
		}
		
		return $string;
	}
}

if(!function_exists('is_json'))
{
	function is_json($string = null)
	{
		if(is_string($string))
		{
			$string									= json_decode($string, true);
			
			if(json_last_error() == JSON_ERROR_NONE)
			{
				return $string;
			}
			else
			{
				return array();
			}
		}
		else
		{
			return array();
		}
	}
}

if(!function_exists('ip_in_range'))
{
	function ip_in_range($whitelist = array())
	{
		$ip											= $_SERVER['REMOTE_ADDR'];
		
		if(in_array($ip, $whitelist))
		{
			return true;
		}
		else
		{
			foreach($whitelist as $i)
			{
				$wildcardPos						= strpos($i, '*');
				if($wildcardPos !== false && substr($_SERVER['REMOTE_ADDR'], 0, $wildcardPos) . '*' == $i)
				{
					return true;
				}
			}
		}

		return false;
	}
}

if(!function_exists('get_userdata'))
{
	function get_userdata($field = null)
	{
		$CI											=& get_instance();
		$CI->load->database();
		$CI->load->library('session');
		
		if($CI->session->userdata($field))
		{
			return $CI->session->userdata($field);
		}
		elseif($CI->session->userdata('outlet_id') && $CI->db->field_exists($field, 'pos__employees'))
		{
			return $CI->db->select($field)->get_where
			(
				'pos__employees',
				array
				(
					'employee_id'					=> $CI->session->userdata('user_id'),
					'outlet_id'						=> $CI->session->userdata('outlet_id')
				),
				1
			)
			->row($field);
		}
		elseif($CI->db->field_exists($field, 'app__users'))
		{
			return $CI->db->select($field)->get_where
			(
				'app__users',
				array
				(
					'user_id'						=> $CI->session->userdata('user_id')
				),
				1
			)
			->row($field);
		}
	}
}

if(!function_exists('generate_token'))
{
	function generate_token($data = null)
	{
		if(is_array($data))
		{
			$data									= http_build_query($data);
		}
		return substr(sha1($data . SALT), 6, 6);
	}
}

if(!function_exists('format_slug'))
{
	function format_slug($string = null)
	{
		$string										= strtolower(preg_replace('/[\-\s]+/', '-', preg_replace('/[^A-Za-z0-9-]+/', '-', trim($string))));
		if(!preg_match('/(\d{10})/', $string))
		{
			$string									= $string;
		}
		return $string;
	}
}

if(!function_exists('get_filesize'))
{
	function get_filesize($file = null)
	{
		$size										= array('B','kB','MB','GB','TB','PB','EB','ZB','YB');
		$bytes										= @filesize($file);
		$factor										= floor((strlen($bytes) - 1) / 3);
		return sprintf('%.2f', ($bytes / pow(1024, $factor))) . @$size[$factor];
	}
}

if(!function_exists('calculate'))
{
	function calculate($string = null, $variable = null)
	{
		$string										= str_replace('<?php', '', strtolower($string));
		$string										= str_replace('<?', '', $string);
		$string										= str_replace('?>', '', $string);
		$string										= str_replace(';', '', $string);
		$string										= str_replace('return', '', $string);
		$variable									= json_decode($variable, true);
		if(is_array($variable))
		{
			foreach($variable as $key => $val)
			{
				$string								= str_replace('{' . $key . '}', $val, $string);
			}
			$string									= eval('return ' . $string . ';');
		}
		return $string;
	}
}

if(!function_exists('array_sort'))
{
	function make_cmp($data = array())
	{
		return function ($a, $b) use (&$data)
		{
			foreach ($data as $column => $sort)
			{
				if(!$sort)
				{
					$sort							= 'asc';
				}
				$diff								= strcmp((is_object($a) ? $a->$column : $a[$column]), (is_object($b) ? $b->$column : $b[$column]));
				if($diff !== 0)
				{
					if('asc' === strtolower($sort))
					{
						return $diff;
					}
					return $diff * -1;
				}
			}
			return 0;
		};
	}
	
	function array_sort($data = null, $order_by = array(), $sort = 'asc')
	{
		if(!is_array($order_by) && is_string($order_by))
		{
			$order_by								= array($order_by => $sort);
		}
		usort($data, make_cmp($order_by));
		return $data;
	}
}

if(!function_exists('gmaps_api'))
{
	function gmaps_api()
	{
		$data										= array_map('trim', explode(',', get_setting('google_maps_api_key')));
		return $data[rand(0, sizeof($data) - 1)];
	}
}

if(!function_exists('debug'))
{
	function debug($code = null)
	{
		$CI											=& get_instance();
		$CI->load->database();
		echo '
			<h2>CODE: 1 (Last DB Queries)</h2>
			<code>
				' . $CI->db->last_query() . '
			</code>
		';
		exit;
	}
}

if(!function_exists('get_active_years'))
{

	function get_active_years($json = false)
	{
		$CI											=& get_instance();
		$CI->load->database();
		$query										= ($CI->db->table_exists('ref__tahun') ? $CI->db->order_by('tahun')->get_where('ref__tahun', array('aktif' => 1))->result() : array());
		
		$options									= array();
		
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options[]							= array
				(
					'year'							=> $val->tahun,
					'default'						=> $val->default
				);
			}
		}
		
		if(!$options)
		{
			$options								= array
			(
				array
				(
					'year'							=> 2018,
					'default'						=> 1
				)
			);
		}
		
		return ($json ? json_encode($options) : json_decode(json_encode($options)));
	}
}

if(!function_exists('generate_token'))
{

	function generate_token()
	{
		$CI											=& get_instance();
		$CI->load->library('encryption');
		return $CI->encryption->encrypt(SALT);
	}
}

if(!function_exists('in_array_r'))
{
	function in_array_r($needle = null, $haystack = array(), $strict = false)
	{
		foreach($haystack as $key => $item)
		{
			if(($strict ? $item === $needle : $item == $needle) || (is_array($item) && in_array_r($needle, $item, $strict)))
			{
				return true;
			}
		}
		return false;
	}
}

if(!function_exists('array_to_xml'))
{
	function array_to_xml($array, &$xml)
	{
		foreach($array as $key => $value)
		{               
			if(is_array($value))
			{            
				if(!is_numeric($key))
				{
					$subnode						= $xml->addChild($key);
					array_to_xml($value, $subnode);
				}
				else
				{
					array_to_xml($value, $subnode);
				}
			}
			else
			{
				$xml->addChild($key, $value);
			}
		}        
	}
}

/**
 * throw_exception
 * Create exception message
 */
if(!function_exists('throw_exception'))
{
	function throw_exception($code = 500, $data = array(), $target = null, $redirect = false)
	{
		$CI											=& get_instance();
		
		/* check if the request isn't through xhr */
		if(!$CI->input->is_ajax_request() && $target)
		{
			/* check if data isn't an array */
			if($data && !is_array($data))
			{
				/* set the flashdata */
				if(in_array($code, array(200, 301)))
				{
					/* success */
					$CI->session->set_flashdata('success', $data);
				}
				elseif(in_array($code, array(403, 404)))
				{
					/* warning */
					$CI->session->set_flashdata('warning', $data);
				}
				else
				{
					/* unexpected error */
					$CI->session->set_flashdata('error', $data);
				}
			}
			
			/* redirect into target */
			redirect($target);
		}
		
		$exception									= array();
		
		if(is_array($data))
		{
			foreach($data as $key => $val)
			{
				$key								= str_replace('[]', null, $key);
				$exception[$key]					= $val;
			}
		}
		else
		{
			$exception								= $data;
		}
		
		/* format to json */
		$CI->output->set_content_type('application/json');
		$CI->output->set_output
		(
			json_encode
			(
				array
				(
					'status'						=> $code,
					'exception'						=> $exception,
					'target'						=> $target,
					'redirect'						=> $redirect
				)
			)
		);
		$CI->output->_display();
		exit;
	}
}

/**
 * show_flashdata
 * Pop the flashdata up
 */
if(!function_exists('show_flashdata'))
{
	function show_flashdata()
	{
		$CI											=& get_instance();
		if($CI->session->flashdata())
		{
			return '
				<div class="alert ' . ($CI->session->flashdata('success') ? 'alert-success' : ($CI->session->flashdata('warning') ? 'alert-warning' : 'alert-danger')) . ' alert-dismissable fade' . ($CI->session->flashdata() ? ' show' : null) . ' exception text-center rounded-0 fixed-top">
					<i class="mdi mdi-' . ($CI->session->flashdata('success') ? 'check' : ($CI->session->flashdata('warning') ? 'alert-octagram-outline' : 'emoticon-sad-outline')) . '"></i>
					' . ($CI->session->flashdata('success') ? $CI->session->flashdata('success') : ($CI->session->flashdata('warning') ? $CI->session->flashdata('warning') : $CI->session->flashdata('error'))) . '
				</div>
			';
		}
		return false;
	}
}

/**
 * get_announcements
 * Get active announcements
 *
 * @params		bool
 * @params		int
 * @return		string
 */
if(!function_exists('get_announcements'))
{
	function get_announcements($placement = null, $limit = null)
	{
		$CI											=& get_instance();
		$query										= $CI->db
		->order_by('announcement_id', 'desc')
		->get_where
		(
			'app__announcements',
			array
			(
				'status'							=> 1,
				'placement'							=> (1 == $placement ? 1 : 0),
				'start_date <= '					=> date('Y-m-d'),
				'end_date >= '						=> date('Y-m-d')
			),
			(is_numeric($limit) ? $limit : 10)
		)
		->result();
		
		$item										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$item								.= '<li><a href="' . base_url('announcements/' . $val->announcement_slug) . '" target="_blank">' . $val->title . '</a></li><li class="ticker-spacer"><img src="' . get_image('settings', get_setting('app_icon'), 'icon') . '" height="16" /></li>';
			}
		}
		
		return ($item ? '<ul role="announcements" class="announcements-ticker alias-announcements-ticker">' . $item . '</ul>' : false);
	}
}

/**
 * make_json
 * Generate the output to JSON format
 */
if(!function_exists('make_json'))
{
	function make_json($data = array())
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', -1);
		$CI											=& get_instance();
		$output										= trim(preg_replace('/(?!<pre[^>]*?>)[\t\n\r\s]+(?![^<]*?<\/pre>)/i', ' ', json_encode(json_decode(json_encode($data)))));
		$CI->output->set_status_header(200);
		$CI->output->set_header('Content-Length: ' . strlen($output));
		$CI->output->set_content_type('application/json');
		$CI->output->set_output($output);
		$CI->output->_display();
		exit;
	}
}

/**
 * array_avg
 * Get the average of array
 */
if(!function_exists('array_avg'))
{
	function array_avg($array = array(), $round = 1)
	{
		if(!is_array($array)) return false;
		$num										= count($array);
		return array_map
		(
			function($val) use ($num,$round)
			{
				return array
				(
					'count'							=> $val,
					'avg'							=> round($val / $num * 100, $round)
				);
			},
			array_count_values($array)
		);
	}
}

/**
 * time_ago
 * Convert timestamp to elapsed time
 */
if(!function_exists('time_ago'))
{
	function time_ago($datetime = null, $full = false)
	{
		$now										= new DateTime;
		$ago										= new DateTime($datetime);
		$diff										= $now->diff($ago);

		$diff->w									= floor($diff->d / 7);
		$diff->d									-= $diff->w * 7;

		$string										= array
		(
			'y'										=> phrase('year'),
			'm'										=> phrase('month'),
			'w'										=> phrase('week'),
			'd'										=> phrase('day'),
			'h'										=> phrase('hour'),
			'i'										=> phrase('minute'),
			's'										=> phrase('second'),
		);
		foreach($string as $k => &$v)
		{
			if($diff->$k)
			{
				$v									= $diff->$k . ' ' . $v . ($diff->$k > 1 ? strtolower(phrase('s')) : '');
			}
			else
			{
				unset($string[$k]);
			}
		}

		if(!$full)
		{
			$string								= array_slice($string, 0, 1);
		}
		return $string ? implode(', ', $string) . ' ' . phrase('ago') : phrase('just_now');
	}
}

if(!function_exists('make_json'))
{
	function make_json($data = array())
	{
		if(!is_array($data)) return false;
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', -1);
		$CI											=& get_instance();
		$CI->output->set_status_header(200);
		$CI->output->set_content_type('application/json');
		$CI->output->set_output
		(
			json_encode
			(
				$data
			)
		);
		$CI->output->_display();
		exit;
	}
}

if(!function_exists('load_asset'))
{
	function load_asset($asset = null)
	{
		if($asset)
		{
			return base_url(ASSET_PATH . '/' . $asset);
		}
	}
}

/**
 * spell_number
 * Spell the number format
 */
if(!function_exists('spell_number'))
{
	function spell_number($number = null)
	{
		$huruf										= array('', 'Satu', 'Dua', 'Tiga', 'Empat', 'Lima', 'Enam', 'Tujuh', 'Delapan', 'Sembilan', 'Sepuluh', 'Sebelas');
		if($number < 12)
		{
			return (isset($huruf[$number]) ? ' ' . $huruf[$number] : 'Nol');
		}
		elseif($number < 20)
		{
			return spell_number($number - 10) . ' Belas';
		}
		elseif($number < 100)
		{
			return spell_number($number / 10) . ' Puluh' . spell_number($number % 10);
		}
		elseif($number < 200)
		{
			return ' Seratus' . spell_number($number - 100);
		}
		elseif($number < 1000)
		{
			return spell_number($number / 100) . ' Ratus' . spell_number($number % 100);
		}
		elseif($number < 2000)
		{
			return ' Seribu' . spell_number($number - 1000);
		}
		elseif($number < 1000000)
		{
			return spell_number($number / 1000) . ' Ribu' . spell_number($number % 1000);
		}
		elseif($number < 1000000000)
		{
			return spell_number($number / 1000000) . ' Juta' . spell_number($number % 1000000);
		}
		elseif($number < 1000000000000)
		{
			return spell_number($number / 1000000000) . ' Milyar' . spell_number($number % 1000000000);
		}
		elseif($number < 1000000000000000)
		{
			return spell_number($number / 1000000000000) . ' Trilyun' . spell_number($number % 1000000000000);
		}
		elseif($number <= 1000000000000000)
		{
			return 'Maaf Tidak Dapat di Proses Karena Jumlah Uang Terlalu Besar ';
		}
		return false;
	}
}

/**
 * date_indo
 * Format date into Indonesian format
 */
if(!function_exists('date_indo'))
{
	function date_indo($date = null, $day_name = false, $separator = ' ', $hour = false)
	{
		$date							= strtotime($date);
		$day							= ucwords(phrase(date('l', $date)));
		$month							= (3 == $day_name ? phrase(date('M', $date)) : phrase(date('F', $date)));
		$year						 	= date('Y', $date);
		return ($day_name && 3 != $day_name ? $day . ', ' : '') . date('d', $date) . $separator . $month . $separator . ($year > 0 ? $year : 1970) . ($hour ? ' ' . date('H:i:s', $date) : null);
	}
}

/**
 * get_begin_month
 */
if(!function_exists('get_begin_month'))
{
	function get_begin_month($date = null)
	{
		$date										= strtotime($date);
		$month										= date('n', $date);
		$year										= date('Y', $date);
		return $year .'-' . $month . '-01';
	}
}

/**
 * get_last_month
 */
if(!function_exists('get_last_month'))
{
	function get_last_month($date = null)
	{
		$date										= strtotime($date);
		$month										= date('n', $date);
		$year										= date('Y', $date);
		if ($month == 1)
		{
			return $year .'-01-31';
		}
		elseif ($month == 2)
		{
			return $year .'-02-29';
		}
		elseif ($month == 3)
		{
			return $year .'-03-31';
		}
		elseif ($month == 4)
		{
			return $year .'-04-30';
		}
		elseif ($month == 5)
		{
			return $year .'-05-31';
		}
		elseif ($month == 6)
		{
			return $year .'-06-30';
		}
		elseif ($month == 7)
		{
			return $year .'-07-31';
		}
		elseif ($month == 8)
		{
			return $year .'-08-31';
		}
		elseif ($month == 9)
		{
			return $year .'-09-30';
		}
		elseif ($month == 10)
		{
			return $year .'-10-31';
		}
		elseif ($month == 11)
		{
			return $year .'-11-30';
		}
		elseif ($month == 12)
		{
			return $year .'-12-31';
		}
		elseif ($month == 0)
		{
			return 'Belum diset';
		}
	}
}

/**
 * spell_triwulan
 */
if(!function_exists('spell_triwulan'))
{
	function spell_triwulan($date = null)
	{
		$date										= strtotime($date);
		$month										= date('n', $date);
		if ($month == 1)
		{
			return 'Triwulan 1';
		}
		elseif ($month == 4)
		{
			return 'Triwulan 2';
		}
		elseif ($month == 7)
		{
			return 'Triwulan 3';
		}
		elseif ($month == 10)
		{
			return 'Triwulan 4';
		}
		elseif ($month == 0)
		{
			return 'Belum diset';
		}
	}
}

if(!function_exists('get_begin_periode_triwulan'))
{
	function get_begin_periode_triwulan($date = null)
	{
		$date										= strtotime($date);
		$month										= date('n', $date);
		$year										= date('Y', $date);
		if ($month < 4)
		{
			return $year .'-01-01';
		}
		elseif ($month < 7)
		{
			return $year .'-04-01';
		}
		elseif ($month < 10)
		{
			return $year .'-07-01';
		}
		elseif ($month > 9)
		{
			return $year .'-10-01';
		}
		elseif ($month == 0)
		{
			return 'Belum diset';
		}
	}
}

if(!function_exists('get_last_periode_triwulan'))
{
	function get_last_periode_triwulan($date = null)
	{
		$date										= strtotime($date);
		$month										= date('n', $date);
		$year										= date('Y', $date);
		if ($month < 4)
		{
			return $year .'-03-31';
		}
		elseif ($month < 7)
		{
			return $year .'-06-30';
		}
		elseif ($month < 10)
		{
			return $year .'-09-30';
		}
		elseif ($month > 9)
		{
			return $year .'-12-31';
		}
		elseif ($month == 0)
		{
			return 'Belum diset';
		}
	}
}

if(!function_exists('number_format_indo'))
{
	function number_format_indo($number = null, $decimal = null)
	{
		$output										= number_format($number, $decimal, ',', '.');
		return $output;
	}
}