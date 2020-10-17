<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Belanja extends Aksara
{
	private $_table									= 'ta__model_belanja';
	
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_model');
		$this->_title								= phrase('belanja') . ' - ' .  phrase('model') . ' ' . $this->model->select('nm_model')->get_where('ta__model', array('id' => $this->_primary), 1)->row('nm_model');
		$this->set_permission();
		$this->set_theme('backend');
		
		if('program' == $this->input->post('method'))
		{
			return $this->_keterangan_rekening($this->input->post('id'));
		}
	}
	
	public function index()
	{
		if($this->_primary)
		{
			$this->set_default
			(
				array
				(
					'id_model'						=> $this->_primary
				)
			);
			$this->where
			(
				array
				(
					'id_model'						=> $this->_primary
				)
			);
		}
		$this->set_breadcrumb
		(
			array
			(
				'model'								=> 'Model'
			)
		)
		->add_class
		(
			array
			(
				'id_rek_6'							=> 'program'
			)
		)
		->set_title($this->_title)
		->set_icon('mdi mdi-microscope')
		->unset_column('id, id_model, kode')
		->unset_field('id, id_model')
		->unset_view('id,  id_model')
		->unset_action('export, print, pdf')
		->set_field('uraian', 'hyperlink', 'model/rekening', array('belanja' => 'id'))
		->set_field('kode', 'last_insert')
		->set_field
		(
			array
			(
				'kd_rek_1_ref__rek_1'				=> 'sprintf',
				'kd_rek_2_ref__rek_2'				=> 'sprintf',
				'kd_rek_3_ref__rek_3'				=> 'sprintf',
				'kd_rek_4_ref__rek_4'				=> 'sprintf',
				'kd_rek_5_ref__rek_5'				=> 'sprintf',
				'kd_rek_6_ref__rek_6'				=> 'sprintf'
			)
		)
		->merge_content('{kd_rek_1}.{kd_rek_2}.{kd_rek_3}.{kd_rek_4}.{kd_rek_5}.{kd_rek_6}', phrase('kode'))
		->set_relation
		(
			'id_rek_6',
			'ref__rek_6.id',
			'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5}.{ref__rek_6.kd_rek_6} - {ref__rek_6.uraian}',
			array
			(
				'ref__rek_1.kd_rek_1'				=> 5,
				'ref__rek_6.tahun'					=> get_userdata('year')
			),
			array
			(
				array
				(
					'ref__rek_5',
					'ref__rek_5.id = ref__rek_6.id_ref_rek_5'
				),
				array
				(
					'ref__rek_4',
					'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
				),
				array
				(
					'ref__rek_3',
					'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
				),
				array
				(
					'ref__rek_2',
					'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
				),
				array
				(
					'ref__rek_1',
					'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
				)
			),
			array
			(
				'ref__rek_1.kd_rek_1'				=> 'ASC',
				'ref__rek_2.kd_rek_2'				=> 'ASC',
				'ref__rek_3.kd_rek_3'				=> 'ASC',
				'ref__rek_4.kd_rek_4'				=> 'ASC',
				'ref__rek_5.kd_rek_5'				=> 'ASC',
				'ref__rek_6.kd_rek_6'				=> 'ASC'
			),
			NULL,
			100
		)
		->set_alias
		(
			array
			(
				'id_rek_6'							=> 'Rekening'
			)
		)
		->set_validation
		(
			array
			(
				'id_rek_5'							=> 'required|is_unique[' . $this->_table . '.id_rek_6.id.' . $this->input->get('id') . '.id_model.' . $this->_primary . ']',
				'id_sumber_dana'					=> 'required'
			)
		)
		->order_by('kd_rek_1, kd_rek_2, kd_rek_3, kd_rek_4, kd_rek_5, kd_rek_6')
		->render($this->_table);
	}
	
	private function _keterangan_rekening($id = 0)
	{
		if($this->input->post('id'))
		{
			$urusan									= $this->model->query
			('
				SELECT
					ref__rek_1.kd_rek_1,
					ref__rek_2.kd_rek_2,
					ref__rek_3.kd_rek_3,
					ref__rek_4.kd_rek_4,
					ref__rek_5.kd_rek_5,
					ref__rek_1.uraian AS nm_rek_1,
					ref__rek_2.uraian AS nm_rek_2,
					ref__rek_3.uraian AS nm_rek_3,
					ref__rek_4.uraian AS nm_rek_4,
					ref__rek_5.uraian AS nm_rek_5
				FROM
					ref__rek_6
				INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
				INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
				INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
				INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
				INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
				WHERE
					ref__rek_6.id = ' . $this->input->post('id') . '
				LIMIT 1
			')
			->row();
			
			$detail_rekening							= '
				<table class="table table-bordered table-sm">
					<tbody>
						<tr>
							<td class="text-sm" width="25%">
								Akun
							</td>
							<td class="text-sm" width="18%">
								' . (isset($urusan->kd_rek_1) ? $urusan->kd_rek_1 : 0) . '
							</td>
							<td class="text-sm" width="57%">
								<a href="' . base_url('laporan/anggaran/rka/rekening', array('rekening' => 8, 'method' => 'embed', 'tanggal_cetak' => date('Y-m-d'))) . '" class="btn btn-info btn-sm float-right" target="_blank">
									<i class="mdi mdi-printer"></i>
								</a>
								' . (isset($urusan->nm_rek_1) ? $urusan->nm_rek_1 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Kelompok
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_rek_2) ? $urusan->kd_rek_1 . '.' . $urusan->kd_rek_2 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->nm_rek_2) ? $urusan->nm_rek_2 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Jenis
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_rek_3) ? $urusan->kd_rek_1 . '.' . $urusan->kd_rek_2 . '.' . $urusan->kd_rek_3 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->nm_rek_3) ? $urusan->nm_rek_3 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_rek_4) ? $urusan->kd_rek_1 . '.' . $urusan->kd_rek_2 . '.' . $urusan->kd_rek_3 . '.' . $urusan->kd_rek_4 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->nm_rek_4) ? $urusan->nm_rek_4 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Rincian Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_rek_5) ? $urusan->kd_rek_1 . '.' . $urusan->kd_rek_2 . '.' . $urusan->kd_rek_3 . '.' . $urusan->kd_rek_4 . '.' . $urusan->kd_rek_5 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->nm_rek_5) ? $urusan->nm_rek_5 : NULL) . '
							</td>
						</tr>
					</tbody>
				</table>
			';
		}
		else
		{
			$detail_rekening							= '';
		}
		
		$query										= $this->model->select('keterangan')->get_where
		(
			'ref__rek_6',
			array
			(
				'id'								=> $this->input->post('id')
			)
		)
		->row('keterangan');
		
		return make_json
		(
			array
			(
				'detail_program'					=> $detail_rekening,
				'html'								=> '<div class="alert alert-info checkbox-wrapper" style="margin-top:10px">' . ($query ? $query : 'Belum ada keterangan untuk rekening yang dipilih') . '</div>'
			)
		);
	}
}