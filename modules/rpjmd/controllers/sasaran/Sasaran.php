<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Sasaran extends Aksara
{
	private $_table									= 'ta__rpjmd_sasaran'; 
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('indikator_tujuan');
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
												ta__rpjmd_tujuan.tujuan, 
												ta__rpjmd_tujuan_indikator.kode, 
												ta__rpjmd_tujuan_indikator.uraian')
											->join('ta__rpjmd_tujuan', 'ta__rpjmd_tujuan.id = ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan')
											->join('ta__rpjmd_misi', 'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi')
											->get_where('ta__rpjmd_tujuan_indikator', array('ta__rpjmd_tujuan_indikator.id' => $this->_primary), 1)
											->row();
				$this
				->set_description('
					<div class="row">
						<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase">
							Tujuan
						</label>
						<label class="control-label col-sm-10  col-xs-8 text-sm text-uppercase">
							' . $header->kode_misi . '. ' . $header->kode_tujuan . '. ' . $header->tujuan . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase">
							Indikator Tujuan
						</label>
						<label class="control-label col-sm-10  col-xs-8 text-sm text-uppercase">
							' . $header->kode_misi . '.' . $header->kode_tujuan . '.' . $header->kode . '. ' . $header->uraian . '
						</label>
					</div>
				')
				->unset_column('id, id_rpjmd_tujuan_indikator')
				->unset_field('id, id_rpjmd_tujuan_indikator')
				->field_order('kode, sasaran')
				->where
				(
					array
					(
						'id_rpjmd_tujuan_indikator'	=> $this->_primary
					)
				)
				->set_default
				(
					array
					(
						'id_rpjmd_tujuan_indikator'	=> $this->_primary
					)
				)
				->select('ta__rpjmd_misi.kode AS kode_misi, ta__rpjmd_tujuan.kode AS kode_tujuan, ta__rpjmd_tujuan_indikator.kode AS kode_tujuan_indikator')
				->join('ta__rpjmd_tujuan_indikator', 'ta__rpjmd_tujuan_indikator.id = ta__rpjmd_sasaran.id')
				->join('ta__rpjmd_tujuan', 'ta__rpjmd_tujuan.id = ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan')
				->join('ta__rpjmd_misi', 'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi')
				;
			}
			else
			{
				$this
				->unset_column('id')
				->unset_field('id')
				->field_order('id_rpjmd_tujuan_indikator, kode, sasaran')
				->set_relation
				(
					'id_rpjmd_tujuan_indikator',
					'ta__rpjmd_tujuan_indikator.id',
					'{ta__rpjmd_misi.kode AS kode_misi}.{ta__rpjmd_tujuan.kode AS kode_tujuan}.{ta__rpjmd_tujuan_indikator.kode AS kode_tujuan_indikator} - {ta__rpjmd_tujuan_indikator.uraian}',
					null,
					array
					(
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
						'ta__rpjmd_tujuan_indikator.kode'		=> 'ASC'
					)
				)
				;
			}
			$this->set_breadcrumb
			(
				array
				(
					'rpjmd'								=> phrase('rpjmd')
				)
			)
			->add_filter($this->_filter())
			->set_title('Sasaran')
			->unset_truncate('sasaran')
			/*->set_field
			(
				array
				(
					'kd_bidang'							=> 'sprintf',
					'kd_unit'							=> 'sprintf',
					'sasaran'							=> 'textarea'
				)
			)*/
			->set_field('sasaran', 'hyperlink', 'rpjmd/sasaran/indikator', array('sasaran' => 'id', 'per_page' => null))
			//->set_field('kecamatan', 'hyperlink', 'master/kecamatan')
			//->set_field('kode', 'last_insert')*/
			//->add_action('option', '../indikator_sasaran', 'Indikator', 'btn-danger', 'fa fa-credit-card', array('sasaran' => 'id', 'per_page' => null))
			->add_action('option', '../sasaran/strategi', 'Strategi', 'btn-default', 'mdi mdi-settings-outline', array('sasaran' => 'id', 'per_page' => null))
			->add_action('option', '../sasaran/kebijakan', 'Kebijakan', 'btn-success', 'mdi mdi-resize', array('sasaran' => 'id', 'per_page' => null))
			->merge_content('{kode_misi}.{kode_tujuan}.{kode_tujuan_indikator}.{kode}', 'Kode')
			->column_order('kode_misi, sasaran')
			->set_alias
			(
				array
				(
					'id_rpjmd_tujuan_indikator'			=> 'Indikator Tujuan'
				)
			)
			->order_by
			(
				array
				(
					'ta__rpjmd_misi.kode'				=> 'ASC',
					'ta__rpjmd_tujuan.kode'				=> 'ASC',
					'ta__rpjmd_tujuan_indikator.kode'	=> 'ASC',
					'ta__rpjmd_sasaran.kode'			=> 'ASC'
				)
			)
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ta__rpjmd_sasaran', array('id_rpjmd_tujuan_indikator' => $this->input->post('isu')), 1)->row('kode');
		
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
		$output										= '<option value="all">Semua Indikator Tujuan</option>';
		$query										= $this->model
		->select
		('
			ta__rpjmd_tujuan_indikator.id,
			ta__rpjmd_misi.kode AS kode_misi,
			ta__rpjmd_tujuan.kode AS kode_tujuan,
			ta__rpjmd_tujuan_indikator.kode,
			ta__rpjmd_tujuan_indikator.uraian
		')
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
		->order_by('kode_misi, kode_tujuan, kode')
		->get('ta__rpjmd_tujuan_indikator')
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->_primary ? ' selected' : '') . '>' . $val['kode_misi'] . '.' . sprintf('%02d', $val['kode_tujuan']) . '.' . sprintf('%02d', $val['kode']) . '. ' . $val['uraian'] . '</option>';
			}
		}
		$output										= '
			<select name="indikator_tujuan" class="form-control input-sm bordered" placeholder="Filter Berdasarkan Indikator Tujuan">
				' . $output . '
			</select>
		';
		return $output;
	}
}