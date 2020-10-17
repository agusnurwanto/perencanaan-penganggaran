<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kebijakan extends Aksara
{
	private $_table									= 'ta__rpjmd_kebijakan'; 
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('sasaran');
	}
	
	public function index()
	{
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			if($this->_primary && 'all' != $this->_primary)
			{
				$header					= $this->model
											->select('
												ta__rpjmd_misi.kode AS kode_misi, 
												ta__rpjmd_tujuan.kode AS kode_tujuan, 
												ta__rpjmd_tujuan_indikator.kode AS kode_tujuan_indikator, 
												ta__rpjmd_tujuan_indikator.uraian,
												ta__rpjmd_sasaran.kode,
												ta__rpjmd_sasaran.sasaran
											')
											->join('ta__rpjmd_tujuan_indikator', 'ta__rpjmd_tujuan_indikator.id = ta__rpjmd_sasaran.id_rpjmd_tujuan_indikator')
											->join('ta__rpjmd_tujuan', 'ta__rpjmd_tujuan.id = ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan')
											->join('ta__rpjmd_misi', 'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi')
											->get_where('ta__rpjmd_sasaran', array('ta__rpjmd_sasaran.id' => $this->_primary), 1)
											->row();
				$this
				->set_description('
					<div class="row">
						<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase">
							Indikator Tujuan
						</label>
						<label class="control-label col-sm-10  col-xs-8 text-sm text-uppercase">
							' . $header->kode_misi . '.' . $header->kode_tujuan . '.' . $header->kode_tujuan_indikator . '. ' . $header->uraian . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase">
							Sasaran
						</label>
						<label class="control-label col-sm-10  col-xs-8 text-sm text-uppercase">
							' . $header->kode_misi . '.' . $header->kode_tujuan . '.' . $header->kode_tujuan_indikator . '.' . $header->kode . '. ' . $header->sasaran . '
						</label>
					</div>
				')
				->unset_column('id, id_rpjmd_sasaran')
				->unset_field('id, id_rpjmd_sasaran')
				->field_order('kode, satuan, kondisi_awal, tahun_1, tahun_2, tahun_3, tahun_4, tahun_5, kondisi_akhir')
				->where
				(
					array
					(
						'id_rpjmd_sasaran'					=> $this->_primary
					)
				)
				->set_default
				(
					array
					(
						'id_rpjmd_sasaran'					=> $this->_primary
					)
				)
				->select('ta__rpjmd_misi.kode AS kode_misi, ta__rpjmd_tujuan.kode AS kode_tujuan, ta__rpjmd_tujuan_indikator.kode AS kode_tujuan_indikator, ta__rpjmd_sasaran.kode AS kode_sasaran')
				->join('ta__rpjmd_sasaran', 'ta__rpjmd_sasaran.id = ' . $this->_table . '.id_rpjmd_sasaran')
				->join('ta__rpjmd_tujuan_indikator', 'ta__rpjmd_tujuan_indikator.id = ta__rpjmd_sasaran.id_rpjmd_tujuan_indikator')
				->join('ta__rpjmd_tujuan', 'ta__rpjmd_tujuan.id = ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan')
				->join('ta__rpjmd_misi', 'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi')
				;
			}
			else
			{
				$this
				->unset_column('id')
				->unset_field('id')
				->field_order('id_rpjmd_sasaran, kode, satuan, kondisi_awal, tahun_1, tahun_2, tahun_3, tahun_4, tahun_5, kondisi_akhir')
				->set_relation
				(
					'id_rpjmd_sasaran',
					'ta__rpjmd_sasaran.id',
					'{ta__rpjmd_misi.kode AS kode_misi}.{ta__rpjmd_tujuan.kode AS kode_tujuan}.{ta__rpjmd_tujuan_indikator.kode AS kode_tujuan_indikator}.{ta__rpjmd_sasaran.kode AS kode_sasaran} - {ta__rpjmd_sasaran.sasaran}',
					null,
					array
					(
						array
						(
							'ta__rpjmd_tujuan_indikator',
							'ta__rpjmd_tujuan_indikator.id = ta__rpjmd_sasaran.id_rpjmd_tujuan_indikator'
						),
						array
						(
							'ta__rpjmd_tujuan',
							'ta__rpjmd_tujuan.id = ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan'
						),
						array
						(
							'ta__rpjmd_misi',
							'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi'
						)
					),
					array
					(
						'ta__rpjmd_misi.kode'					=> 'ASC',
						'ta__rpjmd_tujuan.kode'					=> 'ASC',
						'ta__rpjmd_tujuan_indikator.kode'		=> 'ASC',
						'ta__rpjmd_sasaran.kode'				=> 'ASC'
					)
				)
				;
			}
			$this->set_breadcrumb
			(
				array
				(
					'rpjmd/sasaran'									=> 'sasaran'
				)
			)
			->add_filter($this->_filter())
			->set_title('Kebijakan')
			/*->set_field
			(
				array
				(
					'kd_bidang'							=> 'sprintf',
					'kd_unit'							=> 'sprintf'
				)
			)*/
			//->set_field('nm_program', 'hyperlink', 'rpjmd/capaian', array('id_prog' => 'id'))
			//->set_field('kecamatan', 'hyperlink', 'master/kecamatan')
			//->set_field('kode', 'last_insert')*/
			->merge_content('{kode_misi}.{kode_tujuan}.{kode_tujuan_indikator}.{kode_sasaran}.{kode}', 'Kode')
			->column_order('kode_misi, satuan')
			->unset_truncate('kebijakan')
			//->field_order('id_unit, misi')
			/*->field_position
			(
				array
				(
					'kondisi_awal'						=> 2,
					'tahun_1'							=> 2,
					'tahun_2'							=> 3,
					'tahun_3'							=> 3,
					'tahun_4'							=> 4,
					'tahun_5'							=> 4,
					'kondisi_akhir'						=> 4
				)
			)*/
			->set_alias
			(
				array
				(
					'id_rpjmd_sasaran'					=> 'Sasaran'
				)
			)
			->order_by
			(
				array
				(
					'ta__rpjmd_misi.kode'				=> 'ASC',
					'ta__rpjmd_tujuan.kode'				=> 'ASC',
					'ta__rpjmd_tujuan_indikator.kode'	=> 'ASC',
					'ta__rpjmd_sasaran.kode'			=> 'ASC',
					'ta__rpjmd_kebijakan.kode'			=> 'ASC'
				)
			)
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ta__rpjmd_sasaran', array('id_rpjmd_kebijakan' => $this->input->post('isu')), 1)->row('kode');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
	
	private function _filter()
	{
		$output										= '<option value="all">Semua Sasaran</option>';
		$query										= $this->model
		->select
		('
			ta__rpjmd_misi.kode AS kode_misi,
			ta__rpjmd_tujuan.kode AS kode_tujuan,
			ta__rpjmd_tujuan_indikator.kode AS kode_indikator_tujuan,
			ta__rpjmd_sasaran.id,
			ta__rpjmd_sasaran.kode,
			ta__rpjmd_sasaran.sasaran
		')
		->join
		(
			'ta__rpjmd_tujuan_indikator',
			'ta__rpjmd_tujuan_indikator.id = ta__rpjmd_sasaran.id_rpjmd_tujuan_indikator'
		)
		->join
		(
			'ta__rpjmd_tujuan',
			'ta__rpjmd_tujuan.id = ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan'
		)
		->join
		(
			'ta__rpjmd_misi',
			'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi'
		)
		->order_by('kode_misi, kode_tujuan, kode_indikator_tujuan, kode')
		->get('ta__rpjmd_sasaran')
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->_primary ? ' selected' : '') . '>' . $val['kode_misi'] . '.' . sprintf('%02d', $val['kode_tujuan']) . '.' . sprintf('%02d', $val['kode_indikator_tujuan']) . '.' . sprintf('%02d', $val['kode']) . '. ' . $val['sasaran'] . '</option>';
			}
		}
		$output										= '
			<select name="sasaran" class="form-control input-sm bordered" placeholder="Filter Berdasarkan Sasaran">
				' . $output . '
			</select>
		';
		return $output;
	}
}