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
			->set_icon('fa fa-warning')
			->unset_column('id, kd_urusan, kd_bidang, kd_unit, kd_sub, kd_program, kd_id_prog')
			->unset_field('id')
			->unset_truncate('deskripsi')
			->unset_view('id')
			//->unset_action('pdf, export, print')
			->merge_content('{kode_isu}.{kode}', 'kode')
			->add_action('option', '../variabel', 'Variabel', 'btn-primary ajaxLoad', 'mdi mdi-format-list-bulleted-type', array('id_musrenbang_jenis_pekerjaan' => 'id', 'per_page' => null))
			->add_action('option', '../pertanyaan', 'Pertanyaan', 'btn-danger ajaxLoad', 'mdi mdi-account-question-outline', array('id_musrenbang_jenis_pekerjaan' => 'id', 'per_page' => null))
			->add_action('toolbar', '../../../laporan/master/jenis_pekerjaan_variabel', 'Laporan Variabel', 'btn-info ajax', 'fa fa-print', array('method' => 'preview'), true)
			->add_action('toolbar', '../../../laporan/master/jenis_pekerjaan_pertanyaan', 'Laporan Pertanyaan', 'btn-danger ajax', 'fa fa-print', array('method' => 'preview'), true)
			->column_order('kode_isu, nama_isu, nama_pekerjaan, deskripsi')
			->field_order('id_isu, kode, nama_pekerjaan, deskripsi')
			->set_field
			(
				array
				(
					'kode'								=> 'last_insert',
					'nama_pekerjaan'					=> 'textarea',
					'nilai_satuan'						=> 'number_format',
					'images'							=> 'image'
				)
			)
			->set_validation
			(
				array
				(
					'kode'								=> 'required|is_unique[ref__musrenbang_jenis_pekerjaan.kode.id.' . $this->input->get('id') . '.id_isu.' . $this->input->post('id_isu') . ']',
					'nama_pekerjaan'					=> 'required',
					'id_isu'							=> 'required'
				)
			)
			->set_relation
			(
				'id_isu',
				'ref__musrenbang_isu.id',
				'{ref__musrenbang_isu.kode AS kode_isu}. {ref__musrenbang_isu.nama_isu}',
				null,
				null,
				'ref__musrenbang_isu.kode'
			)
			->set_alias
			(
				array
				(
					'id_isu'								=> 'Isu',
					'nm_program'							=> 'Program',
					'nm_sub'								=> 'SKPD',
					'images'								=> 'Gambar'
				)
			)
			->add_class
			(
				array
				(
					'id_isu'								=> 'trigger_kode',
					'kode'									=> 'kode_input'
				)
			)
			->set_relation
			(
				'id_prog',
				'ta__program.id',
				'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__unit.kd_unit}.{ref__sub.kd_sub}.{ref__program.kd_program}.{ta__program.kd_id_prog} {ref__program.nm_program} - {ref__sub.nm_sub}',
				array
				(
					'ta__program.tahun'				=> get_userdata('year')
				),
				array
				(
					array
					(
						'ref__sub',
						'ref__sub.id = ta__program.id_sub'
					),
					array
					(
						'ref__unit',
						'ref__unit.id = ref__sub.id_unit'
					),
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
				)
			)
			->order_by
			(
				array
				(
					'ref__musrenbang_isu.kode'				=> 'ASC',
					'kode'									=> 'ASC'
				)
			)
			->field_position
			(
				array
				(
					'nilai_satuan'								=> 2,
					'id_prog'									=> 2,
					'images'									=> 2
				)
			)
			->render('ref__musrenbang_jenis_pekerjaan');
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