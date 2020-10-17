<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Tujuan extends Aksara
{
	private $_table									= 'ta__rpjmd_tujuan'; 
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('id_misi');
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
				$header									= $this->model
														->select('ta__rpjmd_visi.tahun_awal, ta__rpjmd_visi.tahun_akhir, ta__rpjmd_visi.visi, ta__rpjmd_misi.misi')
														->join('ta__rpjmd_visi','ta__rpjmd_visi.id = ta__rpjmd_misi.id_visi')
														->get_where('ta__rpjmd_misi', array('ta__rpjmd_misi.id' => $this->_primary), 1)
														->row();
				$this
				->set_description('
					<div class="row">
						<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase">
							Visi
						</label>
						<label class="control-label col-sm-5  col-xs-8 text-sm text-uppercase">
							' . (isset($header->tahun_awal) ?  $header->tahun_awal . ' s/d ' . $header->tahun_akhir . ' - ' . $header->visi : '-') . '
						</label>
					</div>
					<div class="row">
						<label class="control-label col-sm-2 col-xs-4 text-sm text-muted text-uppercase">
							Misi
						</label>
						<label class="control-label col-sm-5  col-xs-8 text-sm text-uppercase">
							' . (isset($header->misi) ?  $header->misi : '-') . '
						</label>
					</div>
					')
				->where
				(
					array
					(
						'id_misi'						=> $this->_primary
					)
				)
				->set_default
				(
					array
					(
						'id_misi'						=> $this->_primary
					)
				)
				->unset_column('id_misi, misi')
				->unset_field('id_misi')
				->unset_view('id_misi');
			}
			else
			{
				$this->add_filter($this->_filter());
				$this->set_breadcrumb
				(
					array
					(
						'rpjmd/visi'						=> 'Visi',
						'../misi'							=> 'Misi'
					)
				)
				->set_title('Tujuan')
				->set_field
				(
					array
					(
						'tujuan'							=> 'textarea',
						'kode'								=> 'last_insert'
					)
				)
				->set_field('tujuan', 'hyperlink', 'rpjmd/tujuan/indikator', array('tujuan' => 'id', 'per_page' => null))
				//->add_action('option', '../tujuan/indikator', 'Indikator', 'btn-success', 'fa fa-credit-card', array('tujuan' => 'id', 'per_page' => null))
				->unset_column('id, misi')
				->unset_field('id')
				->unset_truncate('tujuan')
				->merge_content('{kode_misi}.{kode}', 'Kode')
				->column_order('kode_misi, misi, kd_urusan')
				->field_order('id_unit, misi')
				->add_class
				(
					array
					(
						'id_misi'							=> 'trigger_kode',
						'kode'								=> 'kode_input',
						'tujuan'							=> 'autofocus'
					)
				)
				->set_alias
				(
					array
					(
						'id_misi'							=> 'Misi'
					)
				)
				->set_relation
				(
					'id_misi',
					'ta__rpjmd_misi.id',
					'{ta__rpjmd_misi.kode AS kode_misi}. {ta__rpjmd_misi.misi}',
					null,
					null,
					array
					(
						'ta__rpjmd_misi.kode'				=> 'ASC'
					)
				)
				->field_position
				(
					array
					(
						'kode'								=> 2,
						'tujuan'							=> 2
					)
				)
				->set_validation
				(
					array
					(
						'id_misi'							=> 'required',
						'kode'								=> 'required|numeric',
						'tujuan'							=> 'required'
					)
				)
				->order_by
				(
					array
					(
						'ta__rpjmd_misi.kode'				=> 'ASC',
						'ta__rpjmd_tujuan.kode'				=> 'ASC'
					)
				)
				->render($this->_table);
			}
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ref__rpjmd_tujuan', array('id_misi' => $this->input->post('isu')), 1)->row('kode');
		
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
		$output										= '<option value="all">Semua Misi</option>';
		$query										= $this->model
		->select
		('
			ta__rpjmd_misi.id,
			ta__rpjmd_misi.kode,
			ta__rpjmd_misi.misi
		')
		->order_by('kode')
		->get('ta__rpjmd_misi')
		->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->_primary ? ' selected' : '') . '>' . $val['kode'] . '. ' . $val['misi'] . '</option>';
			}
		}
		$output										= '
			<select name="id_misi" class="form-control input-sm bordered" placeholder="Filter Berdasar Misi">
				' . $output . '
			</select>
		';
		return $output;
	}
}