<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Sub_unit extends Aksara
{
	function __construct()
	{
		parent::__construct();
		// Untuk Grup User Sub Unit
		if(in_array(get_userdata('group_id'), array(11, 12)))
		{
			if(get_userdata('sub_level_1') == NULL)
			{
				return throw_exception(301, 'User Anda belum diberikan hak akses Individual Sub Unit', current_page('../dashboard'));
			}
			//$this->_unit					= $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_sub_unit), 1)->row('id_unit');
			return throw_exception(301, 'Silakan Pilih Kegiatan...', current_page('../', array('sub_unit' => get_userdata('sub_level_1'))));
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
				'renja'								=> 'Renja'
			)
		);
			// Grup Super Admin, Admin Perencanaan, Admin Keuangan, Tim Asistensi, TAPD TTD, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		if(in_array(get_userdata('group_id'), array(1, 2, 3, 16, 17, 18, 19, 20, 21)))
		{
			$this->add_filter($this->_filter());
			if($this->input->get('id_unit') && 'all' != $this->input->get('id_unit'))
			{
				$this->where('ref__sub.id_unit', $this->input->get('id_unit'));
					//Untuk Grup Bidang Bappeda
				if(in_array(get_userdata('group_id'), array(18)))
				{
					$this->where('ref__unit.id_bidang_bappeda', get_userdata('sub_level_1'));
				}
			}
		}
		if(in_array(get_userdata('group_id'), array(9, 12, 15)))
		{
			$this->where('ref__unit.id_bidang_bappeda', get_userdata('sub_level_1'));
		}
		elseif(!in_array(get_userdata('group_id'), array(1)))
		{
			$this->where('ref__unit.id', get_userdata('sub_level_1'));
		}
		$this->set_title('Silakan pilih SKPD')
		->set_icon('mdi mdi-deviantart')
		->set_field
		(
			array
			(
				'kd_bidang'							=> 'sprintf',
				'kd_bidang_2'						=> 'sprintf',
				'kd_bidang_3'						=> 'sprintf',
				'kd_unit'							=> 'sprintf',
				'kd_sub'							=> 'sprintf'
			)
		)
		->unset_column('id, tahun, id_unit, singkat, jabatan_ppk_skpd, nama_ppk_skpd, nip_ppk_skpd')
		->set_field('nm_sub', 'hyperlink', 'renja/asistensi/kegiatan', array('sub_unit' => 'id'))
		->unset_action('create, read, update, delete')
		->unset_truncate('nm_sub')
		
		->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, urusan_2.kd_urusan AS kd_urusan_2, bidang_2.kd_bidang AS kd_bidang_2, urusan_3.kd_urusan AS kd_urusan_3, bidang_3.kd_bidang AS kd_bidang_3, ref__unit.kd_unit')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
		->join('ref__bidang AS bidang_3', 'bidang_3.id = ref__unit.id_bidang_3')
		->join('ref__urusan AS urusan_3', 'urusan_3.id = bidang_3.id_urusan')
		->join('ref__bidang AS bidang_2', 'bidang_2.id = ref__unit.id_bidang_2')
		->join('ref__urusan AS urusan_2', 'urusan_2.id = bidang_2.id_urusan')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->merge_content('{kd_urusan}.{kd_bidang} . {kd_urusan_2}.{kd_bidang_2} . {kd_urusan_3}.{kd_bidang_3} . {kd_unit}.{kd_sub}', 'Kode')
		->column_order('kd_urusan')
		->order_by
		(
			array
			(
				'ref__urusan.kd_urusan'				=> 'ASC',
				'ref__bidang.kd_bidang'				=> 'ASC',
				'kd_urusan_2'						=> 'ASC',
				'kd_bidang_2'						=> 'ASC',
				'kd_urusan_3'						=> 'ASC',
				'kd_bidang_3'						=> 'ASC',
				'ref__unit.kd_unit'					=> 'ASC',
				'ref__sub.kd_sub'					=> 'ASC'
			)
		)
		->set_alias
		(
			array
			(
				'nm_sub'								=> 'Sub Unit',
			)
		)
		->select
		('
			ref__sub.id AS count_kegiatan
		')
		->merge_content('{count_kegiatan}', 'Jumlah Kegiatan', 'callback_count_kegiatan')
		//->merge_content('{count_siap_diasistensi}', 'Siap Diasistensi', 'callback_count_siap_diasistensi')
		->render('ref__sub');
	}
	
	public function count_kegiatan($params = array())
	{
		if(!isset($params['count_kegiatan'])) return false;
		$query										= $this->model->select
		('
			count(ref__sub.id) as total
		')
		->join
		(
			'ta__program',
			'ta__program.id_sub = ref__sub.id'
		)
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id_prog = ta__program.id'
		)
		->get_where
		(
			'ref__sub',
			array
			(
				'ref__sub.id'						=> $params['count_kegiatan']
			)
		)
		->row('total');
		
		return '<span class="badge bg-success">' . $query . '</span> kegiatan';
	}
	
	/*public function count_siap_diasistensi($params = array())
	{
		if(!isset($params['count_siap_diasistensi'])) return false;
		$query										= $this->model->select
		('
			count(ref__sub.id) as total
		')
		->join
		(
			'ta__program',
			'ta__program.id_sub = ref__sub.id'
		)
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id_prog = ta__program.id'
		)
		->get_where
		(
			'ref__sub',
			array
			(
				'ref__sub.id'						=> $params['count_siap_diasistensi']
			)
		)
		->row('total');
		
		return '<span class="badge bg-green">' . $query . '</span> kegiatan';
	}*/
	
	private function _filter()
	{
		$output										= null;
			// Grup Tim Asistensi
		if(in_array(get_userdata('group_id'), array(16)))
		{
			$query										= $this->model
													->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.id, ref__unit.kd_unit, ref__unit.nm_unit')
													->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
													->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
													->get_where('ref__unit', array('ref__unit.id_bidang_bappeda' => get_userdata('sub_level_1')))->result_array();
		}
		else
		{
			$query										= $this->model->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.id, ref__unit.kd_unit, ref__unit.nm_unit')->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')->get('ref__unit')->result_array();
		}
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('id_unit') ? ' selected' : '') . '>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . ' ' . $val['nm_unit'] . '</option>';
			}
		}
		$output										= '
			<select name="id_unit" class="form-control input-sm bordered" placeholder="' . phrase('filter_berdasar_unit') . '">
				<option value="all">' . phrase('berdasarkan_semua_unit') . '</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}