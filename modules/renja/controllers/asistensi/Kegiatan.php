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
			return throw_exception(301, 'Silakan memilih Sub Unit terlebih dahulu.', go_to('../'));
		}
		$this->_unit								= $this->model->select('id_unit')->get_where('ref__sub', array('id' => $this->_sub_unit), 1)->row('id_unit');
		$this->set_theme('backend');
		$this->set_permission();
	}
	
	public function index()
	{
		$this->_header								= 	$this->model
														->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__unit.nm_unit, ref__sub.kd_sub, ref__sub.nm_sub')
														->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
														->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
														->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
														->get_where('ref__sub', array('ref__sub.id' => $this->_sub_unit), 1)
														->row();
	
		$this->set_breadcrumb
		(
			array
			(
				'renja/asistensi/sub_unit'			=> 'Sub Unit'
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
				<div class="col-6 col-sm-2 text-muted text-sm">
					SUB UNIT
				</div>
				<div class="col-6 col-sm-6 font-weight text-sm">
					' . (isset($this->_header->nm_sub) ?  $this->_header->kd_urusan . '.' . sprintf('%02d', $this->_header->kd_bidang) . '.' . sprintf('%02d', $this->_header->kd_unit) . '.' . sprintf('%02d', $this->_header->kd_sub) . ' ' . $this->_header->nm_sub : '-') . '
				</div>
			</div>
			<div class="row border-bottom">
				<div class="col-6 col-sm-2 text-muted text-uppercase text-sm">
					Plafon Unit
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($maksimal_pagu) ? number_format_indo($maksimal_pagu, 2) : '0') . '</b>
				</div>
				<div class="col-6 col-sm-2 text-muted text-uppercase text-sm">
					Anggaran
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($anggaran) ? number_format_indo($anggaran, 2) : '0') . '</b>
				</div>
				<div class="col-6 col-sm-2 text-muted text-uppercase text-sm">
					Selisih
				</div>
				<div class="col-6 col-sm-2 font-weight-bold text-sm">
					<b class="text-danger">Rp. ' . (isset($selisih) ? number_format_indo($selisih, 2) : '0') . '</b>
				</div>
			</div>
		');
		
			// filter program
		$this->add_filter($this->_filter());
		if($this->input->get('id_sub_filter') && 'all' != $this->input->get('id_sub_filter'))
		{
			$this->where('ta__program.id', $this->input->get('id_sub_filter'));
		}
		
		$this->set_title('Kegiatan' . ' ' . ucwords(strtolower($this->_header->nm_unit)))
		->set_icon('mdi mdi-guy-fawkes-mask')
		->column_order('kd_program, kegiatan, files')
		->field_order('id_prog, kode')
		->view_order('kd_program, nm_program, kegiatan, files')
		->unset_action('create, update, delete, print, export, pdf')
		->unset_column('id, tahun, kd_id_prog, id_kegiatan, capaian_program, nm_program, kd_kegiatan, nm_kegiatan, created, updated,riwayat_skpd, kd_urusan, kd_bidang')
		//->unset_field('id, tahun, capaian_program, id_kegiatan, created, updated, riwayat_skpd, capaian_program, kd_keg, kegiatan')
		->unset_view('id, id_kegiatan, kd_urusan, kd_bidang, capaian_program, created, updated, riwayat_skpd, tahun')
		->unset_truncate('kegiatan')
		->merge_content('{kd_program}.{kd_keg}', 'Kode')
		//->merge_field('kd_urusan, kd_bidang')
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
			'renja/asistensi/sub_kegiatan',
			array
			(
				'unit'								=> $this->_unit,
				'kegiatan'							=> 'id'
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
		//->modal_size('modal-l')
		->render($this->_table);
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