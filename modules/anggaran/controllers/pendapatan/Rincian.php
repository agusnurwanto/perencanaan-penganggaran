<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Rincian extends Aksara
{
	private $_table									= 'ta__anggaran_pendapatan_rinci';
	
	function __construct()
	{
		parent::__construct();
		$this->_sub_unit							= $this->input->get('sub_unit');
		$this->_pendapatan							= $this->input->get('pendapatan');
		if(!$this->_pendapatan)
		{
			return throw_exception(301, phrase('silakan memilih Rekening Pendapatan terlebih dahulu'), go_to('../anggaran/pendapatan/rekening'));
		}
		elseif(!$this->_sub_unit)
		{
			return throw_exception(301, phrase('silakan memilih Rekening terlebih dahulu'), go_to('../anggaran/pendapatan/sub_unit'));
		}
		
		$this->set_permission();
		$this->set_theme('backend');
		
	}
	
	public function index()
	{
		$header										= $this->_header();
		$anggaran									= $this->_anggaran();
		$total_rekening								= $this->_total_rekening();
		
		$this->set_breadcrumb
		(
			array
			(
				'anggaran/pendapatan/sub_unit'		=> 'Sub Unit',
				'../rekening'						=> 'Rekening'
			)
		);
		
		$this->set_title('Rincian Anggaran Pendapatan')
		->set_icon('mdi mdi-desk-lamp')
		->set_description
		('
			<div class="row">
				<div class="col-4 col-sm-2 text-muted text-sm">
					SUB UNIT
				</div>
				<div class="col-8 col-sm-6 font-weight text-sm">
					' . (isset($header->nm_sub) ?  $header->kd_urusan . '.' . $header->kd_bidang . '.' . sprintf('%02d', $header->kd_unit) . '.' . sprintf('%02d', $header->kd_sub) . ' ' . $header->nm_sub : '-') . '
				</div>
			</div>
			<div class="row">	
				<div class="col-4 col-sm-2 text-sm text-muted text-uppercase no-margin">
					ANGGARAN
				</div>
				<div class="col-8 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format((isset($anggaran) ? $anggaran : 0), 2) . '
					</b>
				</div>
			</div>
			<div class="row border-bottom">
				<div class="col-4 col-sm-2 text-muted text-sm">
					REKENING
				</div>
				<div class="col-4 col-sm-8 font-weight text-sm">
					' . (isset($header->kd_rek_1) ?  $header->kd_rek_1 . '.' . $header->kd_rek_2 . '.' . $header->kd_rek_3 . '.' . sprintf('%02d', $header->kd_rek_4) . '.' . sprintf('%02d', $header->kd_rek_5) . '.' . sprintf('%02d', $header->kd_rek_6) . ' ' . $header->nm_rek_6 : '-') . '
				</div>
				<div class="col-4 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">
						Rp. ' . number_format(($total_rekening), 2) . '
					</b>
				</div>
			</div>
		')
		->unset_view('id, id_anggaran_pendapatan, tahun, vol_123, satuan_123')
		->unset_field('id, id_anggaran_pendapatan, vol_123, tahun')
		->merge_content('{vol_123} {satuan_123}', phrase('volume'))
		->unset_column('id, id_anggaran_pendapatan, tahun, vol_1, vol_2, vol_3, satuan_1, satuan_2, satuan_3, vol_123')
		->unset_truncate('uraian')
		->column_order('kd_anggaran_pendapatan_rinci, uraian, vol_123, volume, nilai, total')
		->field_order('kd_anggaran_pendapatan_rinci, uraian, nilai, vol_1, satuan_1, vol_2, satuan_2, vol_3, satuan_3, volume, total, satuan_123')
		->view_order('volume')
		->unset_action('pdf, export, print')
		//->add_action('toolbar', '../../laporan/anggaran/rka/rka_sub_kegiatan', 'Preview RKA', 'btn-success ajax', 'mdi mdi-printer-alert', array('kegiatan' => $this->_kegiatan, 'sub_kegiatan' => $this->_sub_kegiatan, 'method' => 'preview'), true)
		//->add_action('toolbar', '../../laporan/anggaran/rka/rka_sub_kegiatan', 'Cetak RKA', 'btn-info ajax', 'mdi mdi-printer', array('kegiatan' => $this->_kegiatan, 'sub_kegiatan' => $this->_sub_kegiatan, 'method' => 'print'), true)
		//->add_action('option', 'rencana_keuangan', phrase('rencana'), 'btn-primary ajax', 'fa fa-id-card', array('id_belanja_rinc' => 'id'))
		->set_alias
		(
			array
			(
				'kd_anggaran_pendapatan_rinci'		=> 'Kode'
			)
		)
		->set_field
		(
			array
			(
				'kd_anggaran_pendapatan_rinci'		=> 'last_insert',
				'uraian'							=> 'textarea',
				'nilai'								=> 'price_format',
				'vol_1'								=> 'price_format',
				'vol_2'								=> 'price_format',
				'vol_3'								=> 'price_format',
				'satuan_123'						=> 'readonly',
				'total'								=> 'price_format, readonly'
			)
		)
		//->set_field('uraian', 'autocomplete', 'ref__standar_harga', 'uraian')
		
		->merge_field('kd_anggaran_pendapatan_rinci, uraian')
		->merge_field('vol_1, satuan_1')
		->merge_field('vol_2, satuan_2')
		->merge_field('vol_3, satuan_3')
		->merge_field('total, satuan_123')
		
		->field_prepend
		(
			array
			(
				'nilai'    							=> 'Rp',
				'total'    							=> 'Rp'
			)
		)
		->field_size
		(
			array
			(
				'kd_anggaran_pendapatan_rinci'		=> 'col-sm-3',
				'nilai'								=> 'col-sm-4',
				'vol_1'								=> 'col-sm-3',
				'vol_2'								=> 'col-sm-3',
				'vol_3'								=> 'col-sm-3'
			)
		)
		
		->set_validation
		(
			array
			(
				'kd_anggaran_pendapatan_rinci'		=> 'required|is_unique[' . $this->_table . '.kd_anggaran_pendapatan_rinci.id.' . $this->input->get('id') . '.id_anggaran_pendapatan.' . $this->_pendapatan . ']',
				'uraian'							=> 'required',
				'vol_1'								=> 'required|numeric',
				'vol_2'								=> 'required|numeric',
				'vol_3'								=> 'required|numeric',
				'nilai'								=> 'required|numeric',
				'total'								=> 'required|callback_validate_total',
				'satuan_123'						=> 'required|callback_validate_satuan_total'
			)
		)
		
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus',
				'nilai'								=> 'sum_field',
				'vol_1'								=> 'sum_field vol_1',
				'vol_2'								=> 'sum_field vol_2',
				'vol_3'								=> 'sum_field vol_3',
				'satuan_1'							=> 'merge-text satuan_1',
				'satuan_2'							=> 'merge-text satuan_2',
				'satuan_3'							=> 'merge-text satuan_3',
				'satuan_123'						=> 'merge-text-result',
				'total'								=> 'sum_total'
			)
		)
		->set_default
		(
			array
			(
				'id_anggaran_pendapatan'			=> $this->_pendapatan,
				'vol_123'							=> ($this->input->post('vol_1') > 0 ? $this->input->post('vol_1') : 1) * ($this->input->post('vol_2') > 0 ? $this->input->post('vol_2') : 1) * ($this->input->post('vol_3') > 0 ? $this->input->post('vol_3') : 1),
				'tahun'								=> get_userdata('year')
			)
		)
		->where
		(
			array
			(
				'id_anggaran_pendapatan'			=> $this->_pendapatan
			)
		)
		->order_by('kd_anggaran_pendapatan_rinci')
		->render($this->_table);
	}
	
	public function after_update()
	{
		return throw_exception(301, phrase('data_was_successfully_updated'), current_page('../'));
	}
	
	public function validate_satuan_total($value = null)
	{
		$satuan										= explode('/', $value);
		if(isset($satuan[0]) && trim($satuan[0]) != trim($this->input->post('satuan_1')) || isset($satuan[1]) && trim($satuan[1]) != trim($this->input->post('satuan_2')) || isset($satuan[2]) && trim($satuan[2]) != trim($this->input->post('satuan_3')))
		{
			$this->form_validation->set_message('validate_satuan_total', '%s harus cocok dengan masing-masing satuan');
			return false;
		}
		return true;
	}
	
	private function _header()
	{
		$query											= $this->model->query
		('
			SELECT
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__unit.nm_unit,
				ref__sub.nm_sub,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ref__rek_6.uraian AS nm_rek_6
			FROM
				ta__anggaran_pendapatan
			INNER JOIN ref__sub ON ta__anggaran_pendapatan.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__rek_6 ON ta__anggaran_pendapatan.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ta__anggaran_pendapatan.id = ' . $this->_pendapatan . '
			LIMIT 1
		')
		->row();
		
		return $query;
	}
	
	private function _anggaran()
	{
		$query										= $this->model->query
		('
			SELECT
				Sum(ta__anggaran_pendapatan_rinci.total) AS anggaran
			FROM
				ta__anggaran_pendapatan_rinci
			INNER JOIN ta__anggaran_pendapatan ON ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ta__anggaran_pendapatan.id
			WHERE
				ta__anggaran_pendapatan.id_sub = ' . $this->_sub_unit . '
			LIMIT 1
		')
		->row('anggaran');
		return $query;
	}
	
	private function _total_rekening()
	{
		$query										= $this->model->query
		('
			SELECT
				SUM(ta__anggaran_pendapatan_rinci.total) AS rekening
			FROM
				ta__anggaran_pendapatan_rinci
			WHERE
				ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ' . $this->_pendapatan . '
			LIMIT 1
		')
		->row('rekening');
		return $query;
	}
	
	public function volume($data = array())
	{
		return (is_numeric($data['vol_1']) ? number_format($data['vol_1']) : $data['vol_1']) . ' ' . $data['satuan_1'] . ($data['vol_2'] > 0 ? ' x ' . (is_numeric($data['vol_2']) ? number_format($data['vol_2']) : $data['vol_2']) . ' ' . $data['satuan_2'] : null) . ($data['vol_3'] > 0 ? ' x ' . (is_numeric($data['vol_3']) ? number_format($data['vol_3']) : $data['vol_3']) . ' ' . $data['satuan_3'] : null);
	}
	
	public function validate_total($value = null)
	{
		$value										= str_replace(',', '', $value);
		$current									= $this->model
													->select('total')
													->get_where('ta__belanja_rinci', array('id' => $this->input->get('id')), 1)
													->row('total');
		$current_rekening							= $this->model->query
													('
														SELECT
															SUM(ta__anggaran_pendapatan_rinci.total) AS total
														FROM
															ta__anggaran_pendapatan_rinci
														WHERE
															ta__anggaran_pendapatan_rinci.id_anggaran_pendapatan = ' . $this->_pendapatan . '
														LIMIT 1
													')
													->row('total');
		
		
		//$realisasi									= $this->model->select_sum('nilai')->get_where('ta_transaksi', array('id_belanja_rinc' => $this->input->get('id')), 1)->row('nilai');
		$checker									= $this->_header();
		$plafon										= (isset($checker->pagu) ? $checker->pagu : 0);
		$nilai										= ($this->input->post('nilai') ? $this->input->post('nilai') : 0);
		$vol_1										= $this->input->post('vol_1');
		$vol_2										= ($this->input->post('vol_2') > 1 ? $this->input->post('vol_2') : 1);
		$vol_3										= ($this->input->post('vol_3') > 1 ? $this->input->post('vol_3') : 1);
		$total										= $nilai * $vol_1 * $vol_2 * $vol_3;
		
		if($total > 0 && $value != $total)
		{
			$this->form_validation->set_message('validate_total', '%s wajib diisi dan harus cocok dengan penjumlahan nilai dan volume');
			return false;
		}
		
		return true;
	}
}