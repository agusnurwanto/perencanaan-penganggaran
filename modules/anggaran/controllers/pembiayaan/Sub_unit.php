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
		->set_breadcrumb
		(
			array
			(
				'sub_unit'						=> 'Sub Unit'
			)
		)
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
		->set_field('nm_sub', 'hyperlink', 'anggaran/pembiayaan/rekening', array('sub_unit' => 'id'))
		->unset_action('create, read, update, delete')
		->unset_truncate('nm_sub')
		->set_alias
		(
			array
			(
				'nm_sub'								=> 'Sub Unit',
			)
		)
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
			// Grup Tim Asistensi
		if(get_userdata('group_id') == 16)
		{
			$query									= $this->model
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