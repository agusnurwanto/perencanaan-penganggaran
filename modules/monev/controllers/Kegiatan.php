<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
class Kegiatan extends Aksara
{
	private $_table									= 'ta__kegiatan';
	//private $_header								= null;
	function __construct()
	{
		parent::__construct();
		$this->_id									= $this->input->get('id');
			// Grup Sub Unit, Sub Unit 2
		if(in_array(get_userdata('group_id'), array(11, 12)))
		{
			$this->_sub_unit						= get_userdata('sub_level_1');
		}
		else
		{
			$this->_sub_unit						= $this->input->get('sub_unit');
		}
		if(!$this->_sub_unit)
		{
			return throw_exception(301, 'Silakan memilih Sub Unit terlebih dahulu.', go_to('../../dashboard'));
		}
		$this->_unit								= $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_sub_unit), 1)->row('id_unit');
		$this->set_theme('backend');
		$this->set_permission();
	}
	
	public function index()
	{
		if('program' == $this->input->post('method'))
		{
			return $this->_program();
		}
		
		$this->_header								= 	$this->model
														->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__unit.nm_unit, ref__sub.kd_sub, ref__sub.nm_sub')
														->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
														->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
														->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
														->get_where('ref__sub', array('ref__sub.id' => $this->_sub_unit), 1)
														->row();
			// Grup SuperAdmin, Admin Perencanaan, Admin Keuangan, Bidang Bappeda, Keuangan, Sekretariat
		if(in_array(get_userdata('group_id'), array(1, 2, 3, 18, 19, 20)))
		{
			$this->unset_action('print, export, pdf');
		}
		else
		{
			$this
			->add_action('option', 'ubah_skpd', 'Ubah SKPD', 'btn-warning ajax', 'mdi mdi-shuffle-variant ', array('id' => 'id'));
		} 
		$this->set_breadcrumb
		(
			array
			(
				'renja/kegiatan/sub_unit'			=> 'Sub Unit'
			)
		);
		$maksimal_pagu						= $this->model->query
											('
												SELECT
													Sum(ta__kegiatan_sub.pagu) AS plafon
												FROM
													ta__kegiatan_sub
												INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
												INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
												INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
												WHERE
													ref__sub.id_unit = ' . $this->_unit . '
												LIMIT 1
											')
											->row('plafon');
		$anggaran							= $this->model->query
											('
												SELECT
													Sum(ta__belanja_rinci.total) AS anggaran
												FROM
													ta__belanja_rinci
												INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
												INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
												INNER JOIN ta__kegiatan_sub ON ta__belanja.id_keg_sub = ta__kegiatan_sub.id
												INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
												INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
												INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
												WHERE
													ref__sub.id_unit = ' . $this->_unit . '
												LIMIT 1
											')
											->row('anggaran');
		$selisih							= $maksimal_pagu - $anggaran;
		$this->set_description
		('
			<div class="row">
				<div class="col-4 col-sm-2 text-muted text-sm">
					SUB UNIT
				</div>
				<div class="col-8 col-sm-6 font-weight text-sm">
					' . (isset($this->_header->nm_sub) ?  $this->_header->kd_urusan . '.' . sprintf('%02d', $this->_header->kd_bidang) . '.' . sprintf('%02d', $this->_header->kd_unit) . '.' . sprintf('%02d', $this->_header->kd_sub) . ' ' . $this->_header->nm_sub : '-') . '
				</div>
			</div>
			<div class="row border-bottom">
				<div class="col-4 col-sm-2 text-muted text-uppercase text-sm">
					Plafon Unit
				</div>
				<div class="col-8 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($maksimal_pagu) ? number_format_indo($maksimal_pagu, 2) : '0') . '</b>
				</div>
				<div class="col-4 col-sm-2 text-muted text-uppercase text-sm">
					Anggaran
				</div>
				<div class="col-8 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($anggaran) ? number_format_indo($anggaran, 2) : '0') . '</b>
				</div>
				<div class="col-4 col-sm-2 text-muted text-uppercase text-sm">
					Selisih
				</div>
				<div class="col-8 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($selisih) ? number_format_indo($selisih, 2) : '0') . '</b>
				</div>
			</div>
		');
		if('create' == $this->_method)
		{
			$this->set_default
			(
				array
				(
					'created'						=> date('Y-m-d H:i:s')
				)
			);
		}
		elseif('update' == $this->_method)
		{
			$this->set_default('updated', date('Y-m-d H:i:s'));
		}
		elseif('read' == $this->_method)
		{
			$this->set_output('capaian_program', $this->_capaian_program());
		}
		
		if($this->input->post('id_kegiatan'))
		{
			$kegiatan								= $this->model->select
			('
				kd_kegiatan,
				nm_kegiatan
			')
			->get_where
			(
				'ref__kegiatan',
				array
				(
					'id'							=> $this->input->post('id_kegiatan'),
					'tahun'							=> get_userdata('year')
				)
			)
			->row();
		}
		
			// filter program
		$this->add_filter($this->_filter());
		if($this->input->get('id_sub_filter') && 'all' != $this->input->get('id_sub_filter'))
		{
			$this->where('ta__program.id', $this->input->get('id_sub_filter'));
		}
		
		$this->set_title('Kegiatan' . ' ' . ucwords(strtolower($this->_header->nm_unit)))
		->set_icon('mdi mdi-guy-fawkes-mask')
		->column_order('kd_program, kegiatan, count_sub_kegiatan, files')
		->field_order('id_prog, kode')
		->view_order('kd_urusan, nama')
		->unset_action('print, export, pdf')
		->unset_column('id, tahun, kd_id_prog, capaian_program, nm_program, kd_kegiatan, nm_kegiatan, created, updated,riwayat_skpd, kd_urusan, kd_bidang')
		->unset_field('id, tahun, created, updated, riwayat_skpd, capaian_program, kd_keg, kegiatan')
		->unset_view('id, tahun')
		->unset_truncate('kegiatan')
		->add_action('toolbar', '../../laporan/anggaran/rka/rka_belanja_skpd', 'Cetak RKA Belanja SKPD', 'btn-success ajax', 'mdi mdi-printer', array('unit' => $this->_unit, 'method' => 'embed', 'tanggal_cetak' => date('Y-m-d')), true)
		->add_action('option', 'indikator', 'Indikator', 'btn-success', 'mdi mdi-shield-key-outline', array('kegiatan' => 'id', 'per_page' => null))
		->add_action('option', '../../laporan/anggaran/rka/rka_rincian_belanja', 'Cetak RKA Kegiatan', 'btn-primary', 'mdi mdi-printer', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'embed'), true)
		->add_action('dropdown', '../../laporan/anggaran/rka/rka_rincian_belanja', 'Pratinjau RKA Rincian belanja SKPD', null, 'mdi mdi-magnify', array('kegiatan' => 'id', 'tanggal_cetak' => date('Y-m-d'), 'method' => 'preview'), true)
		->merge_content('{kd_program}.{kd_keg}', 'Kode')
		->set_field
		(
			array
			(
				'kd_bidang'							=> 'sprintf',
				'kd_program'						=> 'sprintf',
				'kd_id_prog'						=> 'sprintf',
				'kd_keg'							=> 'sprintf',
				'files'								=> 'files'
			)
		)
		->set_field
		(
			'kegiatan',
			'hyperlink',
			'monev/sub_kegiatan',
			array
			(
				'unit'								=> $this->_unit,
				'kegiatan'							=> 'id'
			)
		)
		->add_class
		(
			array
			(
				'id_prog'							=> 'program',
				'id_kegiatan'						=> 'kegiatan'
			)
		)
		->add_attribute
		(
			array
			(
				'id_prog'							=> 'to-change=".kegiatan"',
				'id_kegiatan'						=> 'disabled'
			)
		)
		->set_relation
		(
			'id_prog',
			'ta__program.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__program.kd_program}. {ref__program.nm_program}',
			array
			(
				'ta__program.tahun'					=> get_userdata('year'),
				'ta__program.id_sub'				=> $this->_sub_unit
			),
			array
			(
				array
				(
					'ref__program',
					'ref__program.id = ta__program.id_prog'
				),
				array
				(
					'ref__bidang',
					'ref__bidang.id = ref__program.id_bidang'
				),
				array
				(
					'ref__urusan',
					'ref__urusan.id = ref__bidang.id_urusan'
				)
			),
			array
			(
					'ref__urusan.kd_urusan'			=> 'ASC',
					'ref__bidang.kd_bidang'			=> 'ASC',
					'ref__program.kd_program'		=> 'ASC',
					'ta__program.kd_id_prog'		=> 'ASC'
			)
		)
		->set_relation
		(
			'id_kegiatan',
			'ref__kegiatan.id',
			'{ref__kegiatan.kd_kegiatan}. {ref__kegiatan.nm_kegiatan}',
			array
			(
				'ref__kegiatan.tahun'				=> get_userdata('year')
			),
			NULL,
			array
			(
					'ref__kegiatan.kd_kegiatan'		=> 'ASC'
			)
		)
		->set_alias
		(
			array
			(
				'id_prog'							=> 'Program',
				'nm_program'						=> 'Program',
				'id_kegiatan'						=> 'Kegiatan',
				'nm_kegiatan'						=> 'Kegiatan',
				'kd_keg'							=> 'Kode'
			)
		)
		->set_field
		(
			array
			(
				'kd_keg'							=> 'last_insert',
				'nama'								=> 'textarea'
			)
		)
		->set_default
		(
			array
			(
				'tahun'								=> get_userdata('year'),
				'kd_keg'							=> (isset($kegiatan->kd_kegiatan) ? $kegiatan->kd_kegiatan : 0),
				'kegiatan'							=> (isset($kegiatan->nm_kegiatan) ? $kegiatan->nm_kegiatan : ''),
				'capaian_program'					=> $this->input->post('capaian_program')
			)
		)
		->set_output
		(
			array
			(
				'riwayat_skpd'						=> $this->_riwayat_skpd()
			)
		)
		->set_validation
		(
			array
			(
				'id_prog'							=> 'required',
				'kd_keg'							=> 'required|is_unique[' . $this->_table . '.kd_keg.id.' . $this->input->get('id') . '.id_prog.' . $this->input->post('id_prog') . ']',
				'id_kegiatan'						=> 'required|is_unique[' . $this->_table . '.id_kegiatan.id.' . $this->input->get('id') .'.id_prog.' . $this->input->post('id_prog') . ']'
			)
		)
		->where
		(
			array
			(
				'ta__program.id_sub'				=> $this->_sub_unit
				
			)
		)
		->order_by('kd_program, kd_id_prog, kd_keg')
		->select
		('
			ta__kegiatan.id AS count_sub_kegiatan
		')
		->merge_content('{count_sub_kegiatan}', 'Sub_Kegiatan', 'callback_count_sub_kegiatan')
		->modal_size('modal-l')
		->render($this->_table);
	}
	
	public function count_sub_kegiatan($params = array())
	{
		if(!isset($params['count_sub_kegiatan'])) return false;
		$query										= $this->model->select
		('
			count(*) as total
		')
		->join
		(
			'ta__kegiatan_sub',
			'ta__kegiatan_sub.id_keg = ta__kegiatan.id'
		)
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.id'						=> $params['count_sub_kegiatan']
			)
		)
		->row('total');
		
		return '<span class="badge bg-success">' . $query . '</span> Sub Kegiatan';
	}
	
	public function after_update()
	{
		return throw_exception(301, phrase('data_was_successfully_updated'), current_page('../'));
	}
	
	private function _program()
	{
		if($this->input->post('id'))
		{
			$urusan									= $this->model->query
			('
				SELECT
					ref__urusan.kd_urusan,
					ref__bidang.kd_bidang,
					ref__urusan.nm_urusan,
					ref__bidang.nm_bidang
				FROM
					ta__program
				INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
				INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
				INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
				WHERE
					ta__program.id = ' . $this->input->post('id') . '
				LIMIT 1
			')
			->row();
			$detail_program							= '
				<table class="table table-bordered table-sm">
					<tbody>
						<tr>
							<td>
								Urusan
							</td>
							<td>
								' . (isset($urusan->kd_urusan) ? $urusan->kd_urusan : 0) . '
							</td>
							<td>
								' . (isset($urusan->nm_urusan) ? $urusan->nm_urusan : NULL) . '
							</td>
						</tr>
						<tr>
							<td>
								Bidang
							</td>
							<td>
								' . (isset($urusan->kd_urusan) ? $urusan->kd_urusan . '.' . $urusan->kd_bidang : 0) . '
							</td>
							<td>
								' . (isset($urusan->nm_bidang) ? $urusan->nm_bidang : NULL) . '
							</td>
						</tr>
					</tbody>
				</table>
			';
		}
		else
		{
			$detail_program							= '';
		}
		
		$capaian_program							= 0;
		if($this->_id)
		{
			$capaian_program						= $this->model->select('capaian_program')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('capaian_program');
		}
		$query										= $this->model->get_where('ta__program_capaian', array('id_prog' => $this->input->post('id')))->result_array();
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<label class="control-label" style="display:block">
						<input type="radio" name="capaian_program" value="' . $val['id'] . '"' . ($capaian_program == $val['id'] ? ' checked' : null) . ' />
						' . $val['tolak_ukur'] . '
					</label>
				';
			}
			$output									= '
				<div class="alert alert-warning checkbox-wrapper" style="margin-top:12px">
					' . $output . '
					<label class="control-label" style="display:block">
						<input type="radio" name="capaian_program" value="0"' . (!$capaian_program ? ' checked' : null) . ' />
						Tidak satupun
					</label>
				</div>
			';
		}
		
		$kegiatan									= null;
		$selected_kegiatan							= $this->model->select('id_kegiatan')->get_where
		(
			$this->_table,
			array
			(
				'id'								=> $this->_id
			)
		)
		->row('id_kegiatan');
		
		$id_program									= $this->model->select('id_prog')->get_where('ta__program', array('id' => $this->input->post('id')), 1)->row('id_prog');
		
		$query_kegiatan								= $this->model->select
		('
			ref__kegiatan.id,
			ref__kegiatan.kd_kegiatan,
			ref__kegiatan.nm_kegiatan
		')
		->get_where
		(
			'ref__kegiatan',
			array
			(
				'tahun'								=> get_userdata('year'),
				'id_program'						=> $id_program
				
			)
			
		)
		->result();
		
		if($query_kegiatan)
		{
			foreach($query_kegiatan as $key => $val)
			{
				$kegiatan							.= '<option value="' . $val->id . '"' . ($selected_kegiatan == $val->id ? ' selected' : null) . '>' . $val->kd_kegiatan . '. ' . $val->nm_kegiatan . '</option>';
			}
		}
		
		$last_insert								= $this->model->select_max('kd_keg')->get_where('ta__kegiatan', array('id_prog' => $this->input->post('id')), 1)->row('kd_keg');
		
		make_json
		(
			array
			(
				'detail_program'					=> $detail_program,
				'html'								=> $output,
				'kegiatan'							=> $kegiatan,
				'last_insert'						=> ('create' == $this->_method ? ($last_insert > 0 ? $last_insert + 1 : 1) : 'ignore')
			)
		);
	}
	
	private function _capaian_program()
	{
		$capaian_program							= $this->model->select('capaian_program')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('capaian_program');
		$output										= null;
		if(1 == $capaian_program)
		{
			$capaian								= $this->model->get_where('ta__program_capaian', array('id' => $capaian_program), 1)->row();
			if($capaian)
			{
				$output								.= '
					<div class="row">
						<div class="col-xs-1">
							<h4>
								' . $capaian->kode . '
							</h4>
						</div>
						<div class="col-xs-11">
							<h4>
								' . $capaian->tolak_ukur . '
							</h4>
						</div>
					</div>
				';
			}
		}
		return $output;
	}
	
	private function _riwayat_skpd()
	{
		$query										= $this->model->select('riwayat_skpd')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('riwayat_skpd');
		$query										= json_decode($query);
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$operator							= $this->model->select('first_name')->get_where('app__users', array('user_id' => $val->id_operator), 1)->row('first_name');
				$program							= $this->model
				->select
				('
					ta__program.kd_id_prog,
					ref__program.kd_program,
					ref__program.nm_program,
					ref__sub.kd_sub,
					ref__sub.nm_sub,
					ref__unit.kd_unit,
					ref__bidang.kd_bidang,
					ref__urusan.kd_urusan
				')
				->join('ref__program', 'ref__program.id = ta__program.id_prog')
				->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
				->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
				->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
				->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
				->get_where('ta__program', array('ta__program.id' => $val->id_prog), 1)
				->row();
				$output								.= '
					<li style="margin-bottom:12px">
						<b>
							' . $program->kd_urusan . '.' . $program->kd_bidang . '.' . $program->kd_unit . '.' . $program->kd_sub . '.' . $program->kd_program . '.' . $program->kd_id_prog . ' ' . $program->nm_sub . ' - ' . $program->nm_program . '
						</b>
						<br />
						Diubah oleh ' . $operator . ' pada tanggal ' . $val->tanggal_update . '
					</li>
				';
			}
		}
		if($output)
		{
			return '
				<ul>
					' . $output . '
				</ul>
			';
		}
		return false;
	}
	
	private function _filter()
	{
		$output										= null;
		$query										= $this->model
										->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__program.kd_program, ta__program.id, ta__program.kd_id_prog, ref__program.nm_program')
										->join('ref__program', 'ref__program.id = ta__program.id_prog')
										->join('ref__bidang', 'ref__bidang.id = ref__program.id_bidang')
										->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
										->order_by('ref__program.kd_program ASC, ta__program.kd_id_prog ASC, ref__program.nm_program ASC')
										->get_where('ta__program',array('ta__program.id_sub' => $this->_sub_unit))
										->result_array();
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '<option value="' . $val['id'] . '"' . ($val['id'] == $this->input->get('id_sub_filter') ? ' selected' : '') . '>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_program']) . ' ' . $val['nm_program'] . '</option>';
			}
		}
		$output										= '
			<select name="id_sub_filter" class="form-control input-sm bordered" placeholder="Filter berdasar Program">
				<option value="all">Pilih semua Program</option>
				' . $output . '
			</select>
		';
		return $output;
	}
}