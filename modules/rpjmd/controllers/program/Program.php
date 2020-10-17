<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Program extends Aksara
{
	private $_table									= 'ta__program';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= ($this->input->get('sub_unit') ? $this->input->get('sub_unit') : 'all');
		/*$this->_kecamatan							= $this->input->get('id_kec');
		$this->_title								= $this->select('kecamatan')->get_where('ref__kecamatan', array('id' => $this->_kecamatan), 1)->row('kecamatan');
		if($this->_title)
		{
			$this->_title							= 'Kecamatan ' . $this->_title;
		}*/
	}
	
	public function index()
	{
			// Grup Super Admin, Admin Perencanaan, Admin Keuangan, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		if(in_array(get_userdata('group_id'), array(1, 2, 3, 18, 19, 20, 21)))
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
		if($this->input->get('fetch_model') && $this->input->post('model'))
		{
			return $this->_fetch_model();
		}
		if($this->_primary && 'all' != $this->_primary)
		{
			$this->_title							= $this->model->select('nm_sub')->get_where('ref__sub', array('id' => $this->_primary), 1)->row('nm_sub');
			$this->where
			(
				array
				(
					'ref__sub.id'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'ref__sub.id'					=> $this->_primary,
					'tahun'							=> get_userdata('year')
				)
			)
			->join('ta__program', 'ta__program.id = ' . $this->_table . '.id_prog')
			->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
			->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
			->join('ref__program', 'ref__program.id = ta__program.id_prog')
			->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan');
		}
		else
		{
			$this->where
			(
				array
				(
					'tahun'							=> get_userdata('year')
				)
			)
			->set_default
			(
				array
				(
					'pengusul'						=> 1,
					'tahun'							=> get_userdata('year')
				)
			);
		}
		$this->add_filter($this->_filter());
		
		if($this->_primary && 'all' != $this->_primary)
		{
			$sub_unit								= $this->model->select('nm_unit')->get_where('ref__unit', array('ref__unit.id' => $this->_primary), 1)->row('nm_unit');
			$this->set_description
			('
				<div class="row">
					<label class="control-label col-md-2 col-xs-4 text-sm text-muted text-uppercase">
						Sub Unit
					</label>
					<label class="control-label col-md-10  col-xs-8 text-sm text-uppercase">
						' . $sub_unit . '
					</label>
				</div>
			')
			->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
			->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
			->where('ref__unit.id', $this->_primary);
		}
		$this->set_breadcrumb
		(
			array
			(
				'rpjmd'								=> phrase('rpjmd')
			)
		)
		->set_title('Data Program')
		->set_field
		(
			array
			(
				'kd_bidang'							=> 'sprintf',
				'kd_bidang_'						=> 'sprintf',
				'kd_bidang_3'						=> 'sprintf',
				'kd_bidang_program'					=> 'sprintf',
				'kd_unit'							=> 'sprintf',
				'kd_sub'							=> 'sprintf',
				'kd_id_prog'						=> 'readonly'
			)
		)
		->set_field('nm_program', 'hyperlink', 'rpjmd/program/capaian', array('id_prog' => 'id'))
		->add_class
		(
			array
			(
				'id_prog'							=> 'trigger-program',
				'kd_id_prog'						=> 'kode-program'
			)
		)
		//->set_field('kecamatan', 'hyperlink', 'master/kecamatan')
		//->set_field('kode', 'last_insert')
		->unset_column('id, id_sub, kd_id_prog, tahun')
		->unset_field('id, tahun')
		->unset_truncate('nm_program')
		->merge_content('{kd_urusan}.{kd_bidang} . {kd_urusan_2}.{kd_bidang_2} . {kd_urusan_3}.{kd_bidang_3} . {kd_unit}.{kd_sub} . {kd_urusan_program}.{kd_bidang_program} . {kd_program}', 'Kode')
		->merge_content('{kode_sasaran}.{kode_indikator_sasaran} {satuan}', 'Sasaran')
		//->merge_content('{kd_unit}.{kd_sub}', 'Kode_SKPD')
		->column_order('kd_urusan, nm_program, nm_sub, kode_sasaran')
		->field_order('id_sub, id_prog')
		//->add_action('option', '../program/indikator', 'Indikator Program', 'btn-primary ajaxLoad', 'fa fa-coffee', array('id_prog' => 'id'))
		->set_default
		(
			array
			(
				'tahun'							=> get_userdata('year')
			)
		)
		->set_alias
		(
			array
			(
				'nm_program'						=> 'Nama Program',
				'id_sasaran_indikator'				=> 'Indikator Sasaran',
				'nm_sub'							=> 'Sub Unit',
				'id_sub'							=> 'Sub Unit',
				'id_prog'							=> 'Program',
				'kd_id_prog'						=> 'Kode Program'
			)
		)
		->set_relation
		(
			'id_sub',
			'ref__sub.id',
			'{IFNULL(ref__urusan.kd_urusan, 0) AS kd_urusan}.{IFNULL(ref__bidang.kd_bidang, 0) AS kd_bidang}.{IFNULL(ref__urusan_2.kd_urusan, 0) AS kd_urusan_2}.{IFNULL(ref__bidang_2.kd_bidang, 0) AS kd_bidang_2}.{IFNULL(ref__urusan_3.kd_urusan, 0) AS kd_urusan_3}.{IFNULL(ref__bidang_3.kd_bidang, 0) AS kd_bidang_3}.{ref__unit.kd_unit}{ref__sub.kd_sub} {ref__sub.nm_sub}',
			array
			(
				'ref__sub.tahun'					=> get_userdata('year')
			),
			array
			(
				array
				(
					'ref__unit',
					'ref__unit.id = ref__sub.id_unit'
				),
				array
				(
					'ref__bidang',
					'ref__bidang.id = ref__unit.id_bidang'
				),
				array
				(
					'ref__urusan',
					'ref__urusan.id = ref__bidang.id_urusan'
				),
				array
				(
					'ref__bidang ref__bidang_2',
					'ref__bidang_2.id = ref__unit.id_bidang_2',
					'LEFT'
				),
				array
				(
					'ref__urusan ref__urusan_2',
					'ref__urusan_2.id = ref__bidang_2.id_urusan',
					'LEFT'
				),
				array
				(
					'ref__bidang ref__bidang_3',
					'ref__bidang_3.id = ref__unit.id_bidang_3',
					'LEFT'
				),
				array
				(
					'ref__urusan ref__urusan_3',
					'ref__urusan_3.id = ref__bidang_3.id_urusan',
					'LEFT'
				)
			),
			array
			(
				'ref__urusan.kd_urusan'				=> 'ASC',
				'ref__bidang.kd_bidang'				=> 'ASC',
				'ref__urusan_2.kd_urusan'			=> 'ASC',
				'ref__bidang_2.kd_bidang'			=> 'ASC',
				'ref__urusan_3.kd_urusan'			=> 'ASC',
				'ref__bidang_3.kd_bidang'			=> 'ASC',
				'ref__unit.kd_unit'					=> 'ASC',
				'ref__sub.kd_sub'					=> 'ASC'
			)
		)
		->set_relation
		(
			'id_sasaran_indikator',
			'ta__rpjmd_sasaran_indikator.id',
			'{ta__rpjmd_sasaran.kode AS kode_sasaran}.{ta__rpjmd_sasaran_indikator.kode AS kode_indikator_sasaran} {ta__rpjmd_sasaran_indikator.satuan}',
			null,
			array
			(
				array
				(
					'ta__rpjmd_sasaran',
					'ta__rpjmd_sasaran.id = ta__rpjmd_sasaran_indikator.id_rpjmd_sasaran'
				)
			),
			array
			(
				'ta__rpjmd_sasaran.kode'			=> 'ASC',
				'ta__rpjmd_sasaran_indikator.kode'	=> 'ASC'
			)
		)
		->set_relation
		(
			'id_prog',
			'ref__program.id',
			'{alias_urusan.kd_urusan AS kd_urusan_program}.{alias_bidang.kd_bidang AS kd_bidang_program}.{ref__program.kd_program} {ref__program.nm_program}',
			array
			(
				'ref__program.tahun'				=> get_userdata('year')
			),
			array
			(
				array
				(
					'ref__bidang alias_bidang',
					'alias_bidang.id = ref__program.id_bidang'
				),
				array
				(
					'ref__urusan alias_urusan',
					'alias_urusan.id = alias_bidang.id_urusan'
				)
			),
			array
			(
				'alias_urusan.kd_urusan'				=> 'ASC',
				'alias_bidang.kd_bidang'				=> 'ASC',
				'ref__program.kd_program'			=> 'ASC'
			)
		)
		->set_option_label('id_prog', '{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__program.kd_program}')
		//->select('sub.kd_sub AS alias_kd_sub')
		//->join('ref__sub AS sub', 'sub.id = ta__program.id_sub')
		//->join('ref__unit AS unit', 'unit.id = sub.id_unit')
		->order_by('kd_urusan, kd_bidang, kd_urusan_2, kd_bidang_2, kd_urusan_3, kd_bidang_3, kd_unit, kd_sub, kd_program')
		->render($this->_table); 
	}
	
	private function _filter()
	{
		$output										= '<option value="all">' . phrase('semua_unit') . '</option>';
			// Grup Super Admin, Admin Perencanaan, Admin Keuangan, Bidang Bappeda, Keuangan, Sekretariat, Pemeriksa
		if(!in_array(get_userdata('group_id'), array(1, 2, 3, 18, 19, 20, 21)))
		{
			$this->model->where('ref__unit.id', get_userdata('id_sub'));
		}
		$query										= $this->model
		->select
		('
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__unit.id,
			ref__unit.nm_unit
		')
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
		->order_by('kd_urusan, kd_bidang, kd_unit')
		->get('ref__unit')
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('id_unit') ? ' selected' : '') . '>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '. ' . $val['nm_unit'] . '</option>';
			}
		}
		$output										= '
			<select name="id_unit" class="form-control input-sm bordered" placeholder="' . phrase('filter_berdasar_unit') . '">
				' . $output . '
			</select>
		';
		return $output;
	}
}