<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Skpd extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_id_unit								= (5 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_unit'));
		$this->_id_sub								= (5 == get_userdata('group_id') ? get_userdata('sub_unit') : $this->input->get('id_sub'));
		
		if(!in_array(get_userdata('group_id'), array(1, 5, 9, 11, 12, 13)))
		{
			generateMessages(403, 'Anda tidak mempunyai hak akses yang cukup untuk mengunjungi halaman yang diminta.', base_url('dashboard'));
		}
		elseif(5 == get_userdata('group_id') or 11 == get_userdata('group_id'))
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
		if(in_array(get_userdata('group_id'), array(1, 9, 12)))
		{
			$this->add_filter($this->_filter());
			if($this->_id_unit && 'all' != $this->_id_unit)
			{
				$unit								= $this->model->select('nm_unit')->get_where('ref__unit', array('id' => $this->_id_unit), 1)->row('nm_unit');
				$this->set_description
				('
					<div class="row">
						<label class="control-label col-md-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
							SKPD
						</label>
						<label class="control-label col-md-10  col-xs-8 text-sm text-uppercase no-margin">
							' . $unit . '
						</label>
					</div>
				')
				->where('ref__sub.id_unit', $this->_id_unit);
			}
		}
		else
		{
			$unit									= $this->model->select('ref__unit.nm_unit')->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')->get_where('ref__sub', array('ref__sub.id' => $this->_id_sub), 1)->row('nm_unit');
			$this->set_description
			('
				<div class="row">
					<label class="control-label col-md-2 col-xs-4 text-sm text-muted text-uppercase no-margin">
						SKPD
					</label>
					<label class="control-label col-md-10  col-xs-8 text-sm text-uppercase no-margin">
						' . $unit . '
					</label>
				</div>
			')
			->where('ref__sub.id_unit', $this->_id_sub);
		}
		if(get_userdata('group_id') == 9)
		{
			$this->where('ref__unit.id_bidang_bappeda', get_userdata('sub_unit'));
		}

		$this->set_title('Silakan pilih SKPD')
		->set_icon('mdi mdi-nature-people')
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
		->set_field('nm_sub', 'hyperlink', 'musrenbang/skpd/data', array('id_sub' => 'id'))
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