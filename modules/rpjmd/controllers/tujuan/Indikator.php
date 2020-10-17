<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Indikator extends Aksara
{
	private $_table									= 'ta__rpjmd_tujuan_indikator'; 
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('tujuan');
		/*if(!$this->_primary)
		{
			return generateMessages(301, 'Silakan Memilih Tujuan Terlebih Dahulu', go_to('../tujuan'));
		}*/
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
											->select('ta__rpjmd_misi.kode AS kode_misi, ta__rpjmd_misi.misi, ta__rpjmd_tujuan.kode, ta__rpjmd_tujuan.tujuan')
											->join('ta__rpjmd_misi', 'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi')
											->get_where('ta__rpjmd_tujuan', array('ta__rpjmd_tujuan.id' => $this->_primary), 1)
											->row();
				$this
				->set_description('
					<div class="row">
						<div class="col-4 col-sm-2 text-muted text-sm">
							MISI
						</div>
						<div class="col-8 col-sm-6 font-weight text-sm">
							' . $header->kode_misi . '. ' . $header->misi . '
						</div>
					</div>
					<div class="row">
						<div class="col-4 col-sm-2 text-muted text-sm">
							TUJUAN
						</div>
						<div class="col-8 col-sm-6 font-weight text-sm">
							' . $header->kode_misi . '.' . $header->kode . '. ' . $header->tujuan . '
						</div>
					</div>
				')
				->field_order('kode, uraian')
				->unset_column('id, id_rpjmd_tujuan')
				->unset_field('id, id_rpjmd_tujuan')
				->where
				(
					array
					(
						'ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan'				=> $this->_primary
					)
				)
				->set_default
				(
					array
					(
						'ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan'				=> $this->_primary
					)
				)
				->select('ta__rpjmd_misi.kode AS kode_misi, ta__rpjmd_tujuan.kode')
				->join('ta__rpjmd_tujuan', 'ta__rpjmd_tujuan.id = ta__rpjmd_tujuan_indikator.id_rpjmd_tujuan')
				->join('ta__rpjmd_misi', 'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi')
				;
			}
			else
			{
				$this
				->field_order('id_rpjmd_tujuan, kode, uraian')
				->unset_column('id')
				->unset_field('id')
				->set_relation
				(
					'id_rpjmd_tujuan',
					'ta__rpjmd_tujuan.id',
					'{ta__rpjmd_misi.kode AS kode_misi}.{ta__rpjmd_tujuan.kode}. {ta__rpjmd_tujuan.tujuan}',
					NULL,
					array
					(
						array
						(
							'ta__rpjmd_misi',
							'ta__rpjmd_misi.id = ta__rpjmd_tujuan.id_misi'
						)
					),
					array
					(
						'ta__rpjmd_misi.kode'			=> 'ASC',
						'ta__rpjmd_tujuan.kode'			=> 'ASC'
					)
				)
				;
			}
			$this->set_breadcrumb
			(
				array
				(
					'rpjmd/visi'						=> 'Visi',
					'../misi'							=> 'Misi',
					'../tujuan'							=> 'Tujuan'
				)
			)
			->add_filter($this->_filter())
			->set_title('Indikator Tujuan')
			->column_order('kode_misi, uraian')
			->merge_content('{kode_misi}.{kode_ta__rpjmd_tujuan}.{kode}', 'Kode')
			->set_field('uraian', 'hyperlink', 'rpjmd/sasaran', array('indikator_tujuan' => 'id', 'per_page' => null))
			->add_class
			(
				array
				(
					'uraian'							=> 'autofocus'
				)
			)
			->set_field
			(
				array
				(
					'kode'								=> 'last_insert',
					'uraian'							=> 'textarea'
				)
			)
			->set_alias
			(
				array
				(
					'id_rpjmd_tujuan'					=> 'Tujuan'
				)
			)
			->field_position
			(
				array
				(
					'tahun_1'							=> 2,
					'tahun_2'							=> 2,
					'tahun_3'							=> 2,
					'tahun_4'							=> 3,
					'tahun_5'							=> 3
				)
			)
			->set_validation
			(
				array
				(
					'kode'								=> 'required|numeric',
					'uraian'							=> 'required|xss_clean',
					'tahun_1'							=> 'required',
					'tahun_2'							=> 'required',
					'tahun_3'							=> 'required',
					'tahun_4'							=> 'required',
					'tahun_5'							=> 'required'
				)
			)
			->order_by
			(
				array
				(
					'ta__rpjmd_misi.kode'				=> 'ASC',
					'ta__rpjmd_tujuan.kode'				=> 'ASC',
					'ta__rpjmd_tujuan_indikator.kode'	=> 'ASC'
				)
			)
			->render($this->_table);
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ref__rpjmd_tujuan_indikator', array('id_rpjmd_tujuan' => $this->input->post('isu')), 1)->row('kode');
		
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
		$output										= '<option value="all">Semua Tujuan</option>';
		if(1 != get_userdata('group_id'))
		{
			$this->model->where('id', get_userdata('tujuan'));
		}
		$query										= $this->model
		->select
		('
			ta__rpjmd_tujuan.id,
			ta__rpjmd_tujuan.kode,
			ta__rpjmd_tujuan.tujuan
		')
		->order_by('kode')
		->get('ta__rpjmd_tujuan')
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->_primary ? ' selected' : '') . '>' . $val['kode'] . '. ' . $val['tujuan'] . '</option>';
			}
		}
		$output										= '
			<select name="tujuan" class="form-control input-sm bordered" placeholder="Filter Berdasarkan Tujuan">
				' . $output . '
			</select>
		';
		return $output;
	}
}