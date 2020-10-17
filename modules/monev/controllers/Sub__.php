<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sub extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
		
		$this->_id_unit								= (in_array(get_userdata('group_id'), array(5)) ? get_userdata('sub_unit') : $this->input->get('id_unit'));
		
		if(in_array(get_userdata('group_id'), array(5, 11)))
		{
			$checker								= $this->model->select('id')->get_where('ref__sub', array('id_unit' => $this->_id_unit))->result();
			if($checker && sizeof($checker) == 1)
			{
				generateMessages(301, 'Silakan memilih kegiatan', go_to('../kegiatan', array('id_sub' => $checker[0]->id)));
			}
		}
	}
	
	public function index()
	{
		if(in_array(get_userdata('group_id'), array(1, 9, 12)) )
		{
			$this->add_filter($this->_filter());
		}
		if($this->_id_unit && 'all' != $this->_id_unit)
		{
			$this->where('ref__sub.id_unit', $this->_id_unit);
		}
		$this->set_title('Silakan pilih SKPD')
		->set_icon('fa fa-institution')
		->set_field
		(
			array
			(
				'kode'								=> 'sprintf',
				'kd_bidang'							=> 'sprintf',
				'kd_unit'							=> 'sprintf',
				'kd_sub'							=> 'sprintf'
			)
		)
		->unset_column('id, tahun, id_unit, singkat, jabatan_ppk_skpd, nama_ppk_skpd, nip_ppk_skpd')
		->set_field('nm_sub', 'hyperlink', 'monev', array('id_sub' => 'id'))
		->unset_action('create, read, update, delete')
		->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->merge_content('{kd_urusan}.{kd_bidang}.{kd_unit}.{kd_sub}', 'Kode')
		->column_order('kd_urusan')
		
		/*->select
		('
			ref__sub.id AS count_kegiatan,
			ref__sub.id AS count_siap_diasistensi
		')
		->merge_content('{count_kegiatan}', 'Jumlah Kegiatan', 'callback_count_kegiatan')
		->merge_content('{count_siap_diasistensi}', 'Siap Diasistensi', 'callback_count_siap_diasistensi')*/
		->render('ref__sub');
	}
	
	/*public function count_kegiatan($params = array())
	{
		if(!isset($params['count_kegiatan'])) return false;
		$query										= $this->model->select
		('
			count(*) as total
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
		
		return '<span class="badge bg-blue">' . $query . '</span> kegiatan';
	}
	
	public function count_siap_diasistensi($params = array())
	{
		if(!isset($params['count_siap_diasistensi'])) return false;
		$query										= $this->model->select
		('
			count(*) as total
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
				'ref__sub.id'						=> $params['count_siap_diasistensi'],
				'ta__kegiatan.asistensi_ready'		=> 1
			)
		)
		->row('total');
		
		return '<span class="badge bg-green">' . $query . '</span> kegiatan';
	}*/
	
	private function _filter()
	{
		$output										= null;
		if(get_userdata('group_id') == 9)
		{
			$query									= $this->model
													->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.id, ref__unit.kd_unit, ref__unit.nm_unit')
													->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
													->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
													->get_where('ref__unit', array('ref__unit.id_bidang_bappeda' => get_userdata('sub_unit')))->result_array();
		}
		else
		{
			$query										= $this->model->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.id, ref__unit.kd_unit, ref__unit.nm_unit')->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')->get('ref__unit')->result_array();
		}
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->_id_unit ? ' selected' : '') . '>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . ' ' . $val['nm_unit'] . '</option>';
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