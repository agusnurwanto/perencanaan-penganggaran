<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sub_unit extends Aksara
{
	function __construct()
	{
		parent::__construct();
		/*$this->_id_sub								= (in_array(get_userdata('group_id'), array(5, 11)) ? get_userdata('sub_unit') : $this->input->get('id_sub'));
		
		if(!in_array(get_userdata('group_id'), array(1, 5, 9, 11, 12, 13)))
		{
			return throw_exception(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		elseif(in_array(get_userdata('group_id'), array(5, 11)))
		{
			$checker								= $this->model->get_where('ref__sub', array('unit' => $this->_id_sub))->num_rows();
			if($checker <= 1)
			{
				redirect(go_to());
			}
		}*/
		
		// Untuk Grup User Sub Unit
		if(in_array(get_userdata('group_id'), array(11, 12)))
		{
			if(get_userdata('sub_level_1') == NULL)
			{
				return throw_exception(301, 'User Anda belum diberikan hak akses Individual Sub Unit', current_page('../dashboard'));
			}
			return throw_exception(301, 'Silakan Pilih Kegiatan...', current_page('../', array('sub_unit' => get_userdata('sub_level_1'))));
		}
		//print_r(get_userdata('sub_unit'));exit;
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		//print_r(get_userdata());exit;
		// Untuk Grup User SuperAdmin, Admin Perencanaan, Admin Keuangan, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		if(in_array(get_userdata('group_id'), array(1, 2, 3, 18, 19, 20, 21)) )
		{
			$this->add_filter($this->_filter());
			if($this->input->get('unit') && 'all' != $this->input->get('unit'))
			{
				$this->where('ref__sub.id_unit', $this->input->get('unit'));
			}
		}
		/*else
		{
			$checker								= $this->model
			->select
			('
				count(ref__sub.id) as total,
				ref__sub.id
			')
			->join
			(
				'ref__unit',
				'ref__unit.id = ref__sub.id_unit'
			)
			->join
			(
				'ref__bidang',
				'ref__bidang.id = ref__unit.id_bidang'
			)
			->join
			(
				'ref__urusan',
				'ref__urusan.id = ref__bidang.id_urusan'
			)
			->get_where
			(
				'ref__sub',
				array
				(
					'ref__unit.id'					=> get_userdata('sub_unit')
				)
			)
			->row();
			if(isset($checker->total) && isset($checker->id) && $checker->total == 1)
			{
				generateMessages(301, null, go_to('data', array('id_sub' => $checker->id)));
			}
			$this->where('ref__unit.id', get_userdata('sub_unit'));
		}*/
		
		// Untuk Grup Bidang Bappeda
		if(get_userdata('group_id') == 18)
		{
			$this->where('ref__unit.id_bidang_bappeda', get_userdata('sub_unit'));
		}
		$this->set_title('Silakan pilih Sub Unit')
		->set_icon('mdi mdi-nature-people')
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
		->set_field('nm_sub', 'hyperlink', 'renja/kegiatan', array('sub_unit' => 'id'))
		->unset_action('create, read, update, delete')
		->set_alias
		(
			array
			(
				'nm_sub'								=> 'Sub Unit',
			)
		)
		->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->merge_content('{kd_urusan}.{kd_bidang}.{kd_unit}.{kd_sub}', 'Kode')
		->column_order('kd_urusan')
		
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
				'ref__sub.id'						=> $params['count_kegiatan'],
				'ta__kegiatan.flag'					=> 1
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
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('unit') ? ' selected' : '') . '>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . ' ' . $val['nm_unit'] . '</option>';
			}
		}
		$output										= '
			<select name="unit" class="form-control input-sm bordered" placeholder="Filter Berdasar Unit">
				<option value="all">Pilih Semua Unit</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}