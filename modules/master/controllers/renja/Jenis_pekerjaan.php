<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Jenis_pekerjaan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		if('isu' == $this->input->post('method'))
		{
			$this->_get_last_code();
		}
		else
		{
			$this->set_title('Master Jenis Pekerjaan')
			->set_icon('mdi mdi-warning')
			->unset_column('id, kd_urusan, kd_bidang, kd_unit, kd_sub, kd_program, kd_id_prog')
			->unset_field('id')
			->unset_view('id')
			->unset_action('pdf, print')
			//->merge_content('{kode_isu}.{kode}', 'kode')
			->add_action('option', '../variabel', 'Variabel', 'btn-success ajaxLoad', 'mdi mdi-chemical-weapon', array('id_musrenbang_jenis_pekerjaan' => 'id', 'per_page' => null))
			->add_action('option', '../pertanyaan', 'Pertanyaan', 'btn-warning ajaxLoad', 'mdi mdi-calendar-question', array('id_musrenbang_jenis_pekerjaan' => 'id', 'per_page' => null))
			->add_action('toolbar', '../../../laporan/master/jenis_pekerjaan_variabel', 'Laporan Variabel', 'btn-info ajax', 'fa fa-print', array('method' => 'preview'), true)
			->add_action('toolbar', '../../../laporan/master/jenis_pekerjaan_pertanyaan', 'Laporan Pertanyaan', 'btn-danger ajax', 'fa fa-print', array('method' => 'preview'), true)
			->column_order('kode, nama_pekerjaan, deskripsi')
			->field_order('kode, nama_pekerjaan, deskripsi, pilihan, id_sub, pagu, pagu_1, id_sumber_dana ')
			->merge_content('{kd_sumber_dana_rek_1}.{kd_sumber_dana_rek_2}.{kd_sumber_dana_rek_3}.{kd_sumber_dana_rek_4}.{kd_sumber_dana_rek_5}.{kd_sumber_dana_rek_6}.{nama_sumber_dana}', 'Sumber Dana')
			->set_field
			(
				array
				(
					'kode'								=> 'last_insert',
					'nama_pekerjaan'					=> 'textarea',
					'pagu'								=> 'number_format'
				)
			)
			->set_field
			(
				'pilihan',
				'radio',
				array
				(
					0									=> '<label class="badge badge-primary">Isian</label>',
					1									=> '<label class="badge badge-success">Alamat</label>',
					2									=> '<label class="badge badge-warning">Alamat Detail</label>'
				)
			)
			->set_validation
			(
				array
				(
					'kode'								=> 'required|is_unique[ref__musrenbang_jenis_pekerjaan.kode.id.' . $this->input->get('id') . '.id_isu.' . $this->input->post('id_isu') . ']',
					'nama_pekerjaan'					=> 'required'
				)
			)/*
			->set_relation
			(
				'id_isu',
				'ref__musrenbang_isu.id',
				'{ref__musrenbang_isu.kode AS kode_isu}. {ref__musrenbang_isu.nama_isu}',
				null,
				null,
				'ref__musrenbang_isu.kode'
			)*/
			->set_alias
			(
				array
				(
					'nm_program'							=> 'Program',
					'id_sub'								=> 'Sub Unit',
					'nm_sub'								=> 'Sub Unit',
					'pilihan'								=> 'Pilihan Kegiatan',
					'id_sumber_dana'						=> 'Sumber Dana',
					'nama_sumber_dana'						=> 'Sumber Dana'
				)
			)
			->add_class
			(
				array
				(
					'kode'									=> 'kode_input'
				)
			)
			->set_relation
			(
				'id_sub',
				'ref__sub.id',
				'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__unit.kd_unit}.{ref__sub.kd_sub} {ref__sub.nm_sub}',
				array
				(
					'ref__sub.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__unit',
						'ref__unit.id = ref__sub.id_unit'
					),
					array
					(
						'ref__bidang',
						'ref__bidang.id = ref__unit.id_bidang'
					),
					array
					(
						'ref__urusan',
						'ref__urusan.id = ref__bidang.id_urusan'
					)
				)
			)
			->set_relation
			(
				'id_sumber_dana',
				'ref__sumber_dana_rek_6.id',
				'{ref__sumber_dana_rek_1.kd_sumber_dana_rek_1}.{ref__sumber_dana_rek_2.kd_sumber_dana_rek_2}.{ref__sumber_dana_rek_3.kd_sumber_dana_rek_3}.{ref__sumber_dana_rek_4.kd_sumber_dana_rek_4}.{ref__sumber_dana_rek_5.kd_sumber_dana_rek_5}.{ref__sumber_dana_rek_6.kode AS kd_sumber_dana_rek_6}. {ref__sumber_dana_rek_6.nama_sumber_dana}',
				NULL,
				array
				(
					array
					(
						'ref__sumber_dana_rek_5',
						'ref__sumber_dana_rek_5.id = ref__sumber_dana_rek_6.id_sumber_dana_rek_5'
					),
					array
					(
						'ref__sumber_dana_rek_4',
						'ref__sumber_dana_rek_4.id = ref__sumber_dana_rek_5.id_sumber_dana_rek_4'
					),
					array
					(
						'ref__sumber_dana_rek_3',
						'ref__sumber_dana_rek_3.id = ref__sumber_dana_rek_4.id_sumber_dana_rek_3'
					),
					array
					(
						'ref__sumber_dana_rek_2',
						'ref__sumber_dana_rek_2.id = ref__sumber_dana_rek_3.id_sumber_dana_rek_2'
					),
					array
					(
						'ref__sumber_dana_rek_1',
						'ref__sumber_dana_rek_1.id = ref__sumber_dana_rek_2.id_sumber_dana_rek_1'
					)
				),
				array
				(
					'ref__sumber_dana_rek_1.kd_sumber_dana_rek_1'	=> 'ASC',
					'ref__sumber_dana_rek_2.kd_sumber_dana_rek_2'	=> 'ASC',
					'ref__sumber_dana_rek_3.kd_sumber_dana_rek_3'	=> 'ASC',
					'ref__sumber_dana_rek_4.kd_sumber_dana_rek_4'	=> 'ASC',
					'ref__sumber_dana_rek_5.kd_sumber_dana_rek_5'	=> 'ASC',
					'ref__sumber_dana_rek_6.kode'					=> 'ASC'
				)
			)
			->field_position
			(
				array
				(
					'kode'									=> 1,
					'nama_pekerjaan'						=> 1,
					'deskripsi'								=> 1,
					'pilihan'								=> 1,
					'id_sub'								=> 2,
					'pagu'									=> 2,
					'pagu_1'								=> 2,
					'id_sumber_dana'						=> 2
				)
			)
			->order_by
			(
				array
				(
					'kode'									=> 'ASC'
				)
			)
			->render('ref__renja_jenis_pekerjaan');
		}
	}
	
	private function _get_last_code()
	{
		$last_code											= $this->model->select_max('kode')->get_where('ref__musrenbang_jenis_pekerjaan', array('id_isu' => $this->input->post('isu')), 1)->row('kode');
		
		make_json
		(
			array
			(
				'html'										=> $last_code + 1
			)
		);
	}
}