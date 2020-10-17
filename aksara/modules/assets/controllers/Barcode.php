<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Barcode
 * Generate the barcode
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Barcode extends Aksara
{
	public function __construct()
	{
		parent::__construct();
	}
	
	public function index()
	{
		$this->load->library('zend');
		$this->zend->load('Zend/Barcode');
		
		return Zend_Barcode::render
		(
			'code128',
			'image',
			array
			(
				'text'								=> $this->input->get('code')
			)
		);
	}
}
