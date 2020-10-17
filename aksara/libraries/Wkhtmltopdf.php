<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

/**
 * The PDF Library
 * This class is using a wkhtmltopdf wrapper to generate the
 * pdf file. The server require a  wkhtmltopdf binary to run
 * this library.
 *
 * Install the wrapper through composer:
 * * composer require mikehaertl/phpwkhtmltopdf
 *
 * Property of DWITRI Media
 */


class Wkhtmltopdf
{
	private $_params						= array();
	private $_pageWidth						= '8.5in';
	private $_pageHeight					= '13in';
	private $_pageOrientation				= 'portrait';
	private $_pageMarginTop					= 0;
	private $_pageMarginRight				= 0;
	private $_pageMarginBottom				= 0;
	private $_pageMarginLeft				= 0;
	
	function __construct()
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		require_once(APPPATH . '../../vendor/autoload.php');
	}
	
	public function load($html = null, $filename = null, $method = 'embed', $params = array())
	{
		// push parameter
		$this->_params						= array_merge($this->_params, $params);
		
		// default page width (better use "in" a.k.a inches)
		if(!isset($this->_params['page-width']))
		{
			$this->_params['page-width']	= '8.5in';
		}
		// default page height (better use "in" a.k.a inches)
		if(!isset($this->_params['page-height']))
		{
			$this->_params['page-height']	= '13in';
		}
		// default top margin of page
		if(!isset($this->_params['margin-top']))
		{
			$this->_params['margin-top']	= 10;
		}
		// default right margin of page
		if(!isset($this->_params['margin-right']))
		{
			$this->_params['margin-right']	= 10;
		}
		// default bottom margin of page
		if(!isset($this->_params['margin-bottom']))
		{
			$this->_params['margin-bottom']	= 10;
		}
		// default left margin of page
		if(!isset($this->_params['margin-left']))
		{
			$this->_params['margin-left']	= 10;
		}
		
		// grab header and footer if any
		$style								= preg_match_all('/<style[^>]*?>([\\s\\S]*?)<\/style>/', $html, $matches_style);
		$header								= preg_match_all('/<htmlpageheader[^>]*?>([\\s\\S]*?)<\/htmlpageheader>/', $html, $matches_header);
		$footer								= preg_match_all('/<htmlpagefooter[^>]*?>([\\s\\S]*?)<\/htmlpagefooter>/', $html, $matches_footer);
		$html								= preg_replace('/<htmlpagefooter[^>]*?>([\\s\\S]*?)<\/htmlpagefooter>/', null, preg_replace('/<htmlpageheader[^>]*?>([\\s\\S]*?)<\/htmlpageheader>/', null, $html));
		$html								= str_replace('<pagebreak>', '<p style="page-break-before:always"></p>', preg_replace('/<\s* pagebreak [^>]+>/xi', '<p style="page-break-before:always"></p>', $html));
		if(isset($matches_style[1][0]))
		{
			$style							= $matches_style[1][0];
		}
		else
		{
			$style							= null;
		}
		if(isset($matches_header[1][0]) && !empty($matches_header[1][0]))
		{
			$this->_params['header-html']	= '<style type="text/css">' . $style . '</style>' . $matches_header[1][0];
		}
		if(isset($matches_footer[1][0]) && !empty($matches_footer[1][0]))
		{
			$footer_html					= '<style type="text/css">' . $style . '</style>' . $matches_footer[1][0];
			
			// add page number if any
			if(strpos($matches_footer[1][0], '{PAGENO}') !== false || strpos($matches_footer[1][0], '{nb}') !== false)
			{
				$footer_html				= '<html><head><script>function subst() { var vars={}; var x=document.location.search.substring(1).split("&"); for(var i in x) {var z=x[i].split("=",2);vars[z[0]] = unescape(z[1]);} var x=["frompage","topage","page","webpage","section","subsection","subsubsection"]; for(var i in x) { var y = document.getElementsByClassName(x[i]); for(var j=0; j<y.length; ++j) y[j].textContent = vars[x[i]]; }}</script></head><body onload="subst()">' . str_replace(array('{PAGENO}', '{nb}'), array('<span class="page"></span>','<span class="topage"></span>'), $footer_html) . '</body></html>';
			}
			$this->_params['footer-html']	= $footer_html;
		}
		
		// if server is under linux
		if('wkhtmltopdf' == PDF_BINARY_PATH)
		{
			// Explicitly tell wkhtmltopdf that we're using an X environment
			$this->_params['enableXvfb']	= true;
		}
		
		$this->_params['ignoreWarnings']	= true;
		
		// push params to the class
		$pdf								= new \mikehaertl\wkhtmlto\Pdf($this->_params);
		
		// push html or url to the document
		$pdf->addPage($html);
		
		// set the wkhtmltopdf binary location
		$pdf->binary						= PDF_BINARY_PATH . ' --print-media-type --dpi 120 --zoom 1.1'; 
		
		// handle the filename when @filename isn't set
		if(!$filename)
		{
			$filename						= sha1(time());
		}
		
		if('download' == $method)
		{
			// open browser dialog or direct download the result
			if(!$pdf->send($filename . '.pdf'))
			{
				// throw error
				echo $pdf->getError();
			}
		}
		elseif('attach' == $method)
		{
			// attach the result as email attachment
			if(!$pdf->toString())
			{
				// throw error
				echo $pdf->getError();
			}
		}
		else
		{
			// embed or open the result inside the browser
			// make sure the "PDF" extension is unset from the client file downloader
			if(!$pdf->send())
			{
				// throw error
				echo $pdf->getError();
			}
		}
	}
	
	public function pageSize($width = '8.5in', $height = '13in')
	{
		// explode to get initial setup
		$widthHeight						= explode(' ', str_replace('  ', ' ', $width));
		
		if(2 == sizeof($widthHeight))
		{
			// the page size and orientation is sets with units
			$this->_params['page-width']	= $widthHeight[0];
			$this->_params['page-height']	= $widthHeight[1];
		}
		elseif('landscape' == strtolower($height))
		{
			// the page size and orientation is sets with initial, ex: A4, landscape
			$this->_params['page-size']		= $width;
			$this->_params['orientation']	= $height;
		}
		else
		{
			// the page size and orientation is sets with initial, ex: A4, landscape
			$this->_params['page-width']	= $width;
			$this->_params['page-height']	= $height;
		}
		
		return $this;
	}
	
	public function pageMargin($top = 0, $right = 0, $bottom = 0, $left = 0)
	{
		// hack the retard setup
		if($top && !$right && !$bottom && !$left)
		{
			// margin of the edge is equal
			$this->_params['margin-top']	= $top;
			$this->_params['margin-right']	= $top;
			$this->_params['margin-bottom']	= $top;
			$this->_params['margin-left']	= $top;
		}
		elseif($top && $right && !$bottom && !$left)
		{
			// margin-top and bottom is equal, also margin-right and left
			$this->_params['margin-top']	= $top;
			$this->_params['margin-right']	= $right;
			$this->_params['margin-bottom']	= $top;
			$this->_params['margin-left']	= $right;
		}
		elseif($top && $right && $bottom && !$left)
		{
			// only left margin is equal to the right margin
			$this->_params['margin-top']	= $top;
			$this->_params['margin-right']	= $right;
			$this->_params['margin-bottom']	= $bottom;
			$this->_params['margin-left']	= $right;
		}
		else
		{
			// all edge is used custom margin
			$this->_params['margin-top']	= $top;
			$this->_params['margin-right']	= $right;
			$this->_params['margin-bottom']	= $bottom;
			$this->_params['margin-left']	= $left;
		}
		
		return $this;
	}
}
