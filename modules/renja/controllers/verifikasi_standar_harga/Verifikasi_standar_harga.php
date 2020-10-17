<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Verifikasi_standar_harga extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
		
		/*if('program' == $this->input->post('method'))
		{
			return $this->_keterangan_rekening($this->input->post('id'));
		}
		else*/
		if('sumber_dana' == $this->input->post('method'))
		{
			return $this->_standar_harga($this->input->post('id'));
		}
	}
	
	public function index()
	{
			// untuk Grup Sub Unit
		if(in_array(get_userdata('group_id'), array(11, 12)))
		{
			$this
			->set_default
			(
				array
				(
					'id_sub'							=> get_userdata('sub_level_1'),
					'tahun'								=> get_userdata('year')
				)
			)
			->unset_field('id, id_sub, dilihat, approve, alasan, tahun')
			->where
			(
				array
				(
					'id_sub'							=> get_userdata('sub_level_1'),
					'approve !='						=> 1					
				)
			);
		}
		else
		{
			$this->set_relation
			(
				'id_sub',
				'ref__sub.id',
				'{ref__sub.nm_sub}'
			)
			->set_default
			(
				array
				(
					'id_sub'							=> 0,
					'tahun'								=> get_userdata('year')
				)
			)
			->unset_field('id, id_sub, dilihat, tahun');
		}
		$this->set_title('Pengajuan Standar Harga')
		->set_icon('mdi mdi-nfc-tap')
		
		->add_action('toolbar', 'import', 'Impor Data', 'btn-success --modal', 'mdi mdi-import')
		
		->unset_column('id, id_rek_6, spesifikasi, id_sub, dilihat, url, deskripsi, alasan, approved_time, approved_by, approved_by_user, operator, tahun')
		->unset_field('approved_time, approved_by, operator, id_rek_6')
		->unset_view('id, dilihat, approved_by_user, tahun')
		->unset_truncate('uraian')
		->unset_action('export, print, pdf')
		->column_order('kd_standar_harga_1, kode, uraian, nilai, satuan_1, satuan_2, satuan_3, deskripsi, alasan, nm_sub, images, flag')
		->field_order('id_standar_harga_7, uraian, nilai, satuan_1, satuan_2, satuan_3, deskripsi, flag, approve, alasan, url')
		->view_order('kd_standar_harga_1, kode, nilai, satuan_1, satuan_2, satuan_3, deskripsi, flag, approve, alasan, url, nm_sub, approved_time')
		->set_alias
		(
			array
			(
				//'id_rek_6'								=> 'Rekening',
				'id_standar_harga_7'					=> 'Standar Harga',
				'nm_sub'								=> 'Sub Unit',
				'id_sub'								=> 'Sub Unit',
				'images'								=> 'Gambar'
			)
		)
		->set_field
		(
			array
			(
				'uraian'							=> 'textarea',
				'deskripsi'							=> 'textarea',
				'url'								=> 'textarea',
				'images'							=> 'files',
				'approved_time'						=> 'datetime'
			)
		)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> '<label class="badge badge-primary">SHT</label>',
				1									=> '<label class="badge badge-success">SBM</label>'
			)
		)
		->set_field
		(
			'approve',
			'radio',
			array
			(
				0									=> '<label class="badge badge-warning">Belum Disetujui</label>',
				1									=> '<label class="badge badge-success">Disetujui</label>',
				2									=> '<label class="badge badge-danger">Ditolak</label>'
			)
		)
		->set_field('nilai', 'price_format', 4)
		->set_field('uraian', 'hyperlink', 'renja/verifikasi_standar_harga/rekening', array('standar_harga' => 'id'))
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus',
				//'id_rek_6'							=> 'program',
				'id_standar_harga_7'				=> 'sumber_dana'
			)
		)
		->set_relation
		(
			'approved_by',
			'app__users.user_id',
			'{app__users.first_name AS approved_by_user}'
		)
		->merge_content('{satuan_1} {satuan_2} {satuan_3}', 'Satuan')
		//->merge_content('{kode}. {uraian}', 'Uraian')
		//->merge_content('{kd_rek_1}.{kd_rek_2}.{kd_rek_3}.{kd_rek_4}.{kd_rek_5}.{kd_rek_6} {nm_rek_6}', 'Rekening')
		->merge_content('{kd_standar_harga_1}.{kd_standar_harga_2}.{kd_standar_harga_3}.{kd_standar_harga_4}.{kd_standar_harga_5}.{kd_standar_harga_6}.{kd_standar_harga_7} {nm_standar_harga_7}', 'BMD')
		/*->set_relation
		(
			'id_rek_6',
			'ref__rek_6.id',
			'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5}.{ref__rek_6.kd_rek_6} {ref__rek_6.uraian AS nm_rek_6}',
			array
			(
				'ref__rek_6.tahun'					=> get_userdata('year'),
				'ref__rek_1.kd_rek_1'				=> 5
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
				'ref__rek_1.kd_rek_1'			=> 'ASC',
				'ref__rek_2.kd_rek_2'			=> 'ASC',
				'ref__rek_3.kd_rek_3'			=> 'ASC',
				'ref__rek_4.kd_rek_4'			=> 'ASC',
				'ref__rek_5.kd_rek_5'			=> 'ASC',
				'ref__rek_6.kd_rek_6'			=> 'ASC'
			)
		)*/
		->set_relation
		(
			'id_standar_harga_7',
			'ref__standar_harga_7.id',
			'{ref__standar_harga_1.kd_standar_harga_1}.{ref__standar_harga_2.kd_standar_harga_2}.{ref__standar_harga_3.kd_standar_harga_3}.{ref__standar_harga_4.kd_standar_harga_4}.{ref__standar_harga_5.kd_standar_harga_5}.{ref__standar_harga_6.kd_standar_harga_6}.{ref__standar_harga_7.kd_standar_harga_7} {ref__standar_harga_7.uraian AS nm_standar_harga_7}',
			array
			(
				'ref__standar_harga_7.tahun'		=> get_userdata('year')
			),
			array
			(
				array
				(
					'ref__standar_harga_6',
					'ref__standar_harga_6.id = ref__standar_harga_7.id_standar_harga_6'
				),
				array
				(
					'ref__standar_harga_5',
					'ref__standar_harga_5.id = ref__standar_harga_6.id_standar_harga_5'
				),
				array
				(
					'ref__standar_harga_4',
					'ref__standar_harga_4.id = ref__standar_harga_5.id_standar_harga_4'
				),
				array
				(
					'ref__standar_harga_3',
					'ref__standar_harga_3.id = ref__standar_harga_4.id_standar_harga_3'
				),
				array
				(
					'ref__standar_harga_2',
					'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2'
				),
				array
				(
					'ref__standar_harga_1',
					'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1'
				)
			),
			array
			(
				'ref__standar_harga_1.kd_standar_harga_1'	=> 'ASC',
				'ref__standar_harga_2.kd_standar_harga_2'	=> 'ASC',
				'ref__standar_harga_3.kd_standar_harga_3'	=> 'ASC',
				'ref__standar_harga_4.kd_standar_harga_4'	=> 'ASC',
				'ref__standar_harga_5.kd_standar_harga_5'	=> 'ASC',
				'ref__standar_harga_6.kd_standar_harga_6'	=> 'ASC',
				'ref__standar_harga_7.kd_standar_harga_7'	=> 'ASC'
			),
			null,
			50
		)
		->set_validation
		(
			array
			(
				'id_standar_harga_7'				=> 'required',
				'uraian'							=> 'required',
				'nilai'								=> 'required|numeric',
				'satuan'							=> 'required',
				'satuan_1'							=> 'required',
				'deskripsi'							=> 'required'
			)
		)
		->field_prepend
		(
			array
			(
				'nilai'								=> 'Rp'
			)
		)
		->field_position
		(
			array
			(
				'id_standar_harga_7'				=> 1,
				'uraian'							=> 1,
				'nilai'								=> 2,
				'flag'								=> 2,
				'nm_sub'							=> 2,
				'satuan_1'							=> 2,
				'satuan_2'							=> 2,
				'satuan_3'							=> 2,
				'deskripsi'							=> 3,
				'url'								=> 3,
				'approve'							=> 3,
				'approved_time'						=> 3,
				'images'							=> 3,
				'alasan'							=> 3
			)
		)
		->order_by
		(
			array
			(
				'kd_standar_harga_1'				=> 'ASC',
				'kd_standar_harga_2'				=> 'ASC',
				'kd_standar_harga_3'				=> 'ASC',
				'kd_standar_harga_4'				=> 'ASC',
				'kd_standar_harga_5'				=> 'ASC',
				'kd_standar_harga_6'				=> 'ASC',
				'kd_standar_harga_7'				=> 'ASC'			
			)
		)
		->render('ref__standar_harga');
	}
	
	public function after_update()
	{
		$prepare									= array
		(
			'approved_by'							=> get_userdata('user_id')
		);
		$this->model->update('ref__standar_harga', $prepare, array('id' => $this->input->get('id')), 1);
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
	
	private function _standar_harga()
	{
		if($this->input->post('id'))
		{
			$urusan									= $this->model->query
			('
				SELECT
					ref__standar_harga_1.kd_standar_harga_1,
					ref__standar_harga_2.kd_standar_harga_2,
					ref__standar_harga_3.kd_standar_harga_3,
					ref__standar_harga_4.kd_standar_harga_4,
					ref__standar_harga_5.kd_standar_harga_5,
					ref__standar_harga_6.kd_standar_harga_6,
					ref__standar_harga_7.kd_standar_harga_7,
					ref__standar_harga_1.uraian AS uraian_harga_1,
					ref__standar_harga_2.uraian AS uraian_harga_2,
					ref__standar_harga_3.uraian AS uraian_harga_3,
					ref__standar_harga_4.uraian AS uraian_harga_4,
					ref__standar_harga_5.uraian AS uraian_harga_5,
					ref__standar_harga_6.uraian AS uraian_harga_6,
					ref__standar_harga_7.uraian AS uraian_harga_7
				FROM
					ref__standar_harga_7
				INNER JOIN ref__standar_harga_6 ON ref__standar_harga_7.id_standar_harga_6 = ref__standar_harga_6.id
				INNER JOIN ref__standar_harga_5 ON ref__standar_harga_6.id_standar_harga_5 = ref__standar_harga_5.id
				INNER JOIN ref__standar_harga_4 ON ref__standar_harga_5.id_standar_harga_4 = ref__standar_harga_4.id
				INNER JOIN ref__standar_harga_3 ON ref__standar_harga_4.id_standar_harga_3 = ref__standar_harga_3.id
				INNER JOIN ref__standar_harga_2 ON ref__standar_harga_3.id_standar_harga_2 = ref__standar_harga_2.id
				INNER JOIN ref__standar_harga_1 ON ref__standar_harga_2.id_standar_harga_1 = ref__standar_harga_1.id
				WHERE
					ref__standar_harga_7.id = ' . $this->input->post('id') . '
				LIMIT 1
			')
			->row();
			
			$detail_standar_harga						= '
				<table class="table table-bordered table-sm">
					<tbody>
						<tr>
							<td class="text-sm" width="25%">
								Akun
							</td>
							<td class="text-sm" width="18%">
								' . (isset($urusan->kd_standar_harga_1) ? $urusan->kd_standar_harga_1 : 0) . '
							</td>
							<td class="text-sm" width="57%">
								<a href="' . base_url('laporan/anggaran/rka/sumber_dana', array('method' => 'embed', 'tanggal_cetak' => date('Y-m-d'))) . '" class="btn btn-success btn-sm float-right" target="_blank">
									<i class="mdi mdi-printer"></i>
								</a>
								' . (isset($urusan->uraian_harga_1) ? $urusan->uraian_harga_1 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Kelompok
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_standar_harga_2) ? $urusan->kd_standar_harga_1 . '.' . $urusan->kd_standar_harga_2 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_harga_2) ? $urusan->uraian_harga_2 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Jenis
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_standar_harga_3) ? $urusan->kd_standar_harga_1 . '.' . $urusan->kd_standar_harga_2 . '.' . $urusan->kd_standar_harga_3 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_harga_3) ? $urusan->uraian_harga_3 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_standar_harga_4) ? $urusan->kd_standar_harga_1 . '.' . $urusan->kd_standar_harga_2 . '.' . $urusan->kd_standar_harga_3 . '.' . $urusan->kd_standar_harga_4 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_harga_4) ? $urusan->uraian_harga_4 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Rincian Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_standar_harga_5) ? $urusan->kd_standar_harga_1 . '.' . $urusan->kd_standar_harga_2 . '.' . $urusan->kd_standar_harga_3 . '.' . $urusan->kd_standar_harga_4 . '.' . $urusan->kd_standar_harga_5 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_harga_5) ? $urusan->uraian_harga_5 : NULL) . '
							</td>
						</tr>
						<tr>
							<td class="text-sm">
								Sub Rincian Objek
							</td>
							<td class="text-sm">
								' . (isset($urusan->kd_standar_harga_6) ? $urusan->kd_standar_harga_1 . '.' . $urusan->kd_standar_harga_2 . '.' . $urusan->kd_standar_harga_3 . '.' . $urusan->kd_standar_harga_4 . '.' . $urusan->kd_standar_harga_5 . '.' . $urusan->kd_standar_harga_6 : 0) . '
							</td>
							<td class="text-sm">
								' . (isset($urusan->uraian_harga_6) ? $urusan->uraian_harga_6 : NULL) . '
							</td>
						</tr>
					</tbody>
				</table>
			';
		}
		else
		{
			$detail_standar_harga						= '';
		}
		
		return make_json
		(
			array
			(
				'detail_sumber_dana'				=> $detail_standar_harga
			)
		);
	}
}