<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Skpd extends Aksara
{
	function __construct()
	{
		parent::__construct();
		//$this->_id_unit								= $this->input->get('id_unit');
		$this->_id_sub								= (5 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_sub'));
		
		if(!in_array(get_userdata('group_id'), array(1, 5,9)))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		elseif(5 == get_userdata('group_id'))
		{
			$checker								= $this->model->get_where('ref__sub', array('id_unit' => $this->_id_sub))->num_rows();
			if($checker <= 1)
			{
				redirect(go_to('data'));
			}
		}
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		if(in_array(get_userdata('group_id'), array(1, 9, 12)) )

		{
			$this->add_filter($this->_filter());
			if($this->input->get('id_unit') && 'all' != $this->input->get('id_unit'))
			{
				$this->where('ref__sub.id_unit', $this->input->get('id_unit'));
			}
		}
		else
		{
			$this->where('ref__unit.id', get_userdata('sub_unit'));
		}
		if(get_userdata('group_id') == 9)
		{
			$this->where('ref__unit.id_bidang_bappeda', get_userdata('sub_unit'));
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
		->set_field('nm_sub', 'hyperlink', 'reses/skpd/data', array('id_sub' => 'id'))
		->unset_action('create, read, update, delete')
		->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->merge_content('{kd_urusan}.{kd_bidang}.{kd_unit}.{kd_sub}', 'Kode')
		->column_order('kd_urusan')
		->render('ref__sub');
	}
	
	


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