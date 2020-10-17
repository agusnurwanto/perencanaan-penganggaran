<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * XHR
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Xhr extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		redirect(base_url());
	}
}