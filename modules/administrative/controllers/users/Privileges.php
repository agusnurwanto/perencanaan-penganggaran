<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * User Privileges
 * Set the individual user privilege.
 *
 * @version			2.1.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */
class Privileges extends Aksara
{
	private $_table									= 'app__users_privileges';
	
	public function __construct()
	{
		parent::__construct();
		
		/* insert if user doesn't exist in the app__users_privileges table */
		$this->insert_on_update_fail();
		
		/* set the parent module */
		$this->parent_module('administrative/users');
		
		$this->set_permission(1); // only user with group id 1 can access this module
		$this->set_theme('backend');
		$this->set_method('update');
		
		/* set the primary id */
		$this->_primary								= $this->input->get('user_id');
		
		/* get user from database */
		$this->_user								= $this->model->select
		('
			app__users.user_id,
			app__users.username,
			app__users.first_name,
			app__users.last_name,
			app__users.photo,
			app__users.group_id,
			app__groups.group_name,
			' . $this->_table . '.sub_level_1,
			' . $this->_table . '.sub_level_2,
			' . $this->_table . '.access_year
		')
		->join
		(
			'app__groups',
			'app__groups.group_id = app__users.group_id'
		)
		->join
		(
			$this->_table,
			$this->_table . '.user_id = app__users.user_id',
			'left'
		)
		->get_where
		(
			'app__users',
			array
			(
				'app__users.user_id'				=> $this->_primary
			),
			1
		)
		->row();
		
		/* check if user is exists */
		if(!$this->_user || in_array($this->_user->group_id, array(1)))
		{
			/* otherwise, throw the exception */
			return throw_exception(404, phrase('you_are_not_allowed_to_modify_the_selected_user'), current_page('../'));
		}
	}
	
	public function index()
	{
		if('ambil-kegiatan' == $this->input->post('method'))
		{
			$this->_ambil_kegiatan();
		}
		
		if(!$this->input->post('menus'))
		{
			$this->set_default('menus', '[]');
		}
		//print_r($this->input->post());exit;
		$sub_level									= $this->_sub_level_1();
		
		$this->set_title(phrase('custom_user_privileges'))
		->set_icon('mdi mdi-account-check-outline')
		->set_primary('user_id')
		->set_output
		(
			array
			(
				'results'							=> $sub_level['results'],
				'sub_unit'							=> $sub_level['sub_unit'],
				'visible_menu'						=> $this->_visible_menu(),
				'checked_sub_kegiatan'				=> ($this->_user->sub_level_2 ? $this->_user->sub_level_2 : json_encode(array()))
			)
		)
		->set_default
		(
			array
			(
				'user_id'							=> $this->_primary,
				'access_year'						=> $this->input->post('year')
			)
		)
		->where('user_id', $this->_primary)
		->limit(1)
		->render($this->_table);
	}
	
	private function _sub_level_1()
	{
		$sub_unit									= null;
		$results									= null;
		// Grup RW
		if(6 == $this->_user->group_id)
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__rw.id AS id_sub, ref__rw.rw AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__rw', 'ref__rw.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('ref__rw.id, CONCAT("Kec. ", ref__kecamatan.kecamatan, " Kel. ", ref__kelurahan.nama_kelurahan, " ", ref__rw.rw) AS nama_gabung')->join('ref__kelurahan', 'ref__kelurahan.id = ref__rw.id_kel')->join('ref__kecamatan', 'ref__kecamatan.id = ref__kelurahan.id_kec')->order_by('ref__kecamatan.kecamatan, ref__kelurahan.nama_kelurahan, ref__rw.rw')->get('ref__rw')->result_array();
		}
		// Grup Kelurahan
		elseif(7 == $this->_user->group_id)
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__kelurahan.id AS id_sub, ref__kelurahan.nama_kelurahan AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__kelurahan', 'ref__kelurahan.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('ref__kelurahan.id, CONCAT(ref__kecamatan.kode, ".", ref__kelurahan.kode, " ") AS kode_gabung, CONCAT(kecamatan, " - ", nama_kelurahan) AS nama_gabung')->join('ref__kecamatan','ref__kecamatan.id = ref__kelurahan.id_kec')->order_by('ref__kecamatan.kode, ref__kelurahan.kode')->get('ref__kelurahan')->result_array();
		}
		// Grup Kecamatan
		elseif(8 == $this->_user->group_id)
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__kecamatan.id AS id_sub, ref__kecamatan.kecamatan AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__kecamatan', 'ref__kecamatan.id = app__users_privileges.sub_level_1', 'LEFT')
			->order_by('ref__kecamatan.kode ASC')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('id, CONCAT(kode, ". ") AS kode_gabung, kecamatan AS nama_gabung')->order_by('ref__kecamatan.kode ASC')->get('ref__kecamatan')->result_array();
		}
		// Grup Fraksi
		elseif(9 == $this->_user->group_id)
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__dprd_fraksi.id as id_sub, ref__dprd_fraksi.nama_fraksi AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__dprd_fraksi', 'ref__dprd_fraksi.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('id, CONCAT(kode, ". ") AS kode_gabung, nama_fraksi AS nama_gabung')->order_by('kode')->get('ref__dprd_fraksi')->result_array();
		}
		// Grup DPRD
		elseif(10 == $this->_user->group_id)
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__dprd.id as id_sub, ref__dprd.nama_dewan AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__dprd', 'ref__dprd.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('id, CONCAT(kode, ". ") AS kode_gabung, nama_dewan AS nama_gabung')->get('ref__dprd')->result_array();
		}
		// Grup Sub Unit dan Sub Unit 2
		elseif(in_array($this->_user->group_id, array(11, 12, 22)))
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__sub.id as id_sub, ref__sub.nm_sub AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__sub', 'ref__sub.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('ref__sub.id, CONCAT(ref__urusan.kd_urusan, ".", ref__bidang.kd_bidang, ".", ref__unit.kd_unit, ".", ref__sub.kd_sub, " ") AS kode_gabung, ref__sub.nm_sub AS nama_gabung')->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')->order_by('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__sub.kd_sub')->get('ref__sub')->result_array();
		}
		// Grup Unit Unit dan Unit 2
		elseif(in_array($this->_user->group_id, array(13, 14)))
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__unit.id as id_sub, ref__unit.nm_unit AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__unit', 'ref__unit.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('ref__unit.id, CONCAT(ref__urusan.kd_urusan, ".", ref__bidang.kd_bidang, ".", ref__unit.kd_unit, " ") AS kode_gabung, ref__unit.nm_unit AS nama_gabung')->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')->order_by('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit')->get('ref__unit')->result_array();
		}
		// Grup Verifikasi SSH
		elseif(in_array($this->_user->group_id, array(15)))
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__sub.id as id_sub, ref__sub.nm_sub AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__sub', 'ref__sub.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
		}
		// Grup Tim Asistensi, TAPD TTD, Bidang Bappeda
		elseif(in_array($this->_user->group_id, array(16, 17, 18)))
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__bidang_bappeda.id as id_sub, ref__bidang_bappeda.nama_bidang AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__bidang_bappeda', 'ref__bidang_bappeda.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('id, CONCAT(kode, ". ") AS kode_gabung, nama_bidang AS nama_gabung')->order_by('kode')->get('ref__bidang_bappeda')->result_array();
		}
		// Grup Keuangan
		elseif(in_array($this->_user->group_id, array(19)))
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__tim_anggaran.id as id_sub, ref__tim_anggaran.nama_tim AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__tim_anggaran', 'ref__tim_anggaran.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
			$sub_unit								= $this->model->select('id, CONCAT(kode, ". ") AS kode_gabung, nama_tim AS nama_gabung')->get('ref__tim_anggaran')->result_array();
		}
		// Grup Sekretariat dan Pemeriksa
		elseif(in_array($this->_user->group_id, array(20)))
		{
			$results								= $this->model
			->select('app__users.*, app__groups.group_name, ref__sub.id as id_sub, ref__sub.nm_sub AS sub_unit')
			->join('app__groups', 'app__groups.group_id = app__users.group_id')
			->join('app__users_privileges', 'app__users_privileges.user_id = app__users.user_id', 'LEFT')
			->join('ref__sub', 'ref__sub.id = app__users_privileges.sub_level_1', 'LEFT')
			->get_where('app__users', array('app__users.user_id' => $this->_primary))
			->row();
		}
		
		return array
		(
			'sub_unit'								=> $sub_unit,
			'results'								=> $results
		);
	}
	
	/**
	 * List the visible menu
	 */
	private function _visible_menu()
	{
		/* get existing user menu if any */
		$existing_menu								= $this->model->select('menus')->get_where($this->_table, array('user_id' => $this->_primary), 1)->row('menus');
		$existing_menu								= json_decode($existing_menu);
		
		/* get sidebar menu by user group from the database */
		$visible_menu								= $this->model
		->select
		('
			app__menus.serialized_data
		')
		->join
		(
			'app__groups',
			'app__groups.group_id = app__users.group_id'
		)
		->join
		(
			'app__menus',
			'app__menus.group_id = app__groups.group_id'
		)
		->get_where
		(
			'app__users',
			array
			(
				'app__users.user_id'				=> $this->_primary,
				'app__menus.menu_placement'			=> 'sidebar'
			),
			1
		)
		->row('serialized_data');
		
		/* decode serialized menu */
		$visible_menu								= json_decode($visible_menu);
		
		/* set default item */
		$items										= null;
		if($visible_menu)
		{
			foreach($visible_menu as $menu => $item)
			{
				if(!isset($item->id) || !isset($item->slug) || !isset($item->label)) continue;
				$order								= $item->order;
				$id									= $item->id;
				$items								.= '
					<li' . (isset($item->children) && $item->children ? ' class="check-group"' : null) . '>
						<label class="control-label big-label">
							<input type="checkbox"name="menus[' . $order . ']" value="' . $id . '"' . (isset($item->children) && $item->children ? ' role="checker" data-parent=".check-group"' : null) . (isset($existing_menu->$order) && $existing_menu->$order == $id ? ' checked' : null) . ' />
							&nbsp;
							<i class="' . (isset($item->icon) ? $item->icon : 'mdi mdi-circle-outline') . '"></i>
							' . phrase($item->label) . '
						</label>
						' . (isset($item->children) ? $this->_children_menu($item->children, $existing_menu) : null) . '
					</li>
				';
			}
			$items							= '
				<ul class="list-unstyled">
					' . $items . '
				</ul>
			';
		}
		
		return $items;
	}
	
	/**
	 * Re-loop the available menu to find the children
	 */
	private function _children_menu($children = array(), $existing_menu = array())
	{
		$items										= null;
		if($children)
		{
			foreach($children as $menu => $item)
			{
				if(!isset($item->id) || !isset($item->slug) || !isset($item->label)) continue;
				$order								= $item->order;
				$id									= $item->id;
				$items								.= '
					<li' . (isset($item->children) && $item->children ? ' class="check-group"' : null) . '>
						<label class="control-label big-label">
							<input type="checkbox"name="menus[' . $order . ']" value="' . $id . '" class="checker-children"' . (isset($item->children) && $item->children ? ' role="checker" data-parent=".check-group"' : null) . (isset($existing_menu->$order)  && $existing_menu->$order == $id ? ' checked' : null) . ' />
							&nbsp;
							<i class="' . (isset($item->icon) ? $item->icon : 'mdi mdi-circle-outline') . '"></i>
							' . phrase($item->label) . '
						</label>
						' . (isset($item->children) ? $this->_children_menu($item->children) : null) . '
					</li>
				';
			}
			$items									= '
				<ul class="list-unstyled ml-3">
					' . $items . '
				</ul>
			';
		}
		return $items;
	}
	
	private function _ambil_kegiatan()
	{
		$query										= $this->model->select
		('
			ta__kegiatan_sub.id,
			ta__kegiatan_sub.kegiatan_sub
		')
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id = ta__kegiatan_sub.id_keg'
		)
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->get_where
		(
			'ta__kegiatan_sub',
			array
			(
				'ta__program.id_sub'				=> $this->input->post('primary'),
				'ta__kegiatan_sub.tahun'			=> get_userdata('year')
			)
		)
		->result();
		
		return make_json
		(
			array
			(
				'data'								=> $query
			)
		);
	}
	
	private function _connector()
	{
		/* get sql server connection */
		$connection									= $this->model->get_where('ref__koneksi', array('tahun' => date('Y')), 1)->row();
		
		/* check if connection is found */
		if(!$connection)
		{
			/* otherwise, throw the exception */
			return false;
		}
		
		/* define config */
		$config										= array
		(
			'hostname' 								=> $this->encryption->decrypt($connection->hostname) . ($this->encryption->decrypt($connection->port) ? ',' . $this->encryption->decrypt($connection->port) : null),
			'username'								=> $this->encryption->decrypt($connection->username),
			'password' 								=> $this->encryption->decrypt($connection->password),
			'database' 								=> $this->encryption->decrypt($connection->database),
			'dbdriver' 								=> $connection->driver
		);
		
		/* load the new database connection with the defined config */
		return $this->load->database($config, TRUE);
	}
}