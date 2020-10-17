<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Rekening extends Aksara
{
	private $_table									= 'ref__standar_rekening';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_permission();
		$this->set_theme('backend');
		$this->_primary								= $this->input->get('standar_harga');
		if('program' == $this->input->post('method'))
		{
			return $this->_keterangan_rekening($this->input->post('id'));
		}
	}
	
	public function index()
	{
		$this->set_title('Standar Rekening')
		->set_icon('mdi mdi-shield-account')
		->unset_column('id, id_standar_harga')
		->unset_field('id, id_standar_harga')
		->unset_view('id, id_standar_harga')
		->unset_truncate('nm_rek_6')
		->set_breadcrumb
		(
			array
			(
				'renja/verifikasi_standar_harga'		=> 'Standar Harga'
			)
		)
		->set_relation
		(
			'id_rek_6',
			'ref__rek_6.id',
			'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5}.{ref__rek_6.kd_rek_6} {ref__rek_6.uraian AS nm_rek_6}',
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
		->merge_content('{kd_rek_1}.{kd_rek_2}.{kd_rek_3}.{kd_rek_4}.{kd_rek_5}.{kd_rek_6}', 'Kode')
		->add_class
		(
			array
			(
				'id_rek_6'							=> 'program'
			)
		)
		
		/* set kolom sebagai hyperlink ke modul di bawahnya */
		->set_field('uraian', 'hyperlink', 'standar/kelompok', array('akun' => 'id'))
		->set_field
		(
			array
			(
				'no_urut'							=> 'last_insert'
			)
		)
		
		/* penyesuaian validasi */
		->set_validation
		(
			array
			(
				'no_urut'							=> 'required|numeric|is_unique[ref__standar_rekening.no_urut.id.' . $this->input->get('id') . ']',
				'id_rek_6'							=> 'required|xss_clean'
			)
		)
		
		->where('id_standar_harga', $this->_primary)
		->set_default('id_standar_harga', $this->_primary)
		
		->set_alias
		(
			array
			(
				'id_rek_6'							=> 'Rekening',
				'nm_rek_6'							=> 'Uraian'
			)
		)
		->order_by
		(
			array
			(
				'ref__rek_1.kd_rek_1'				=> 'ASC',
				'ref__rek_2.kd_rek_2'				=> 'ASC',
				'ref__rek_3.kd_rek_3'				=> 'ASC',
				'ref__rek_4.kd_rek_4'				=> 'ASC',
				'ref__rek_5.kd_rek_5'				=> 'ASC',
				'ref__rek_6.kd_rek_6'				=> 'ASC'
			)
		)
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