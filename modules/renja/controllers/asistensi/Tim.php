<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tim extends Aksara
{
	function __construct()
	{
		parent::__construct();
		if(!in_array(get_userdata('group_id'), array(1)))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'../renja'							=> 'Renja'
			)
		);
		$this->set_title('Silakan pilih tim')
		->set_icon('fa fa-users')
		->unset_action('create, read, update, delete, print, export, pdf')
		->unset_column('user_id, username, email, password, last_name, bio, address, phone, kode_pos, photo, serialized_customization, language, group_id, last_login, status')
		->set_field('first_name', 'hyperlink', 'renja/asistensi/history', array('user_id' => 'user_id'))
		//->set_field('first_name', 'hyperlink', go_to('../history', array('user_id' => 'user_id')), 'ajaxLoad')
		->set_alias('first_name', 'Nama Tim')
		->where('group_id', 12)
		->order_by('first_name')
		->render('app__users');
	}
}