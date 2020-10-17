<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Ubah_skpd extends Aksara
{
	private $_table									= 'ta__kegiatan';
	function __construct()
	{
		parent::__construct();
		$this->_id									= $this->input->get('id');
		if(!$this->_id || 1 != get_userdata('group_id'))
		{
			generateMessages(403, 'Anda tidak mempunya hak akses yang cukup untuk mengubah SKPD', go_to('../data'));
		}
		$this->set_method('update')
		->parent_module('renja/kegiatan/data')
		->set_theme('backend')
		->set_permission();
	}
	
	public function index()
	{
		$this->set_title('Ubah SKPD')
		->set_icon('fa fa-exchange')
		->unset_field
		('
			id,
			capaian_program,
			id_musrenbang,
			id_reses,
			pengusul,
			flag,
			pilihan,
			map_coordinates,
			map_address,
			alamat_detail,
			images,
			kd_keg,
			jenis_kegiatan,
			jenis_kegiatan_renja,
			input_kegiatan,
			kegiatan,
			kelompok_sasaran,
			waktu_pelaksanaan,
			survey,
			variabel_usulan,
			nilai_usulan,
			pagu,
			id_model,
			variabel,
			tahun,
			created,
			updated,
			id_sumber_dana,
			kecamatan,
			kelurahan,
			kegiatan_judul_baru,
			pagu_1,
			riwayat_skpd,
			jenis_usulan,
			lock_kegiatan,
			asistensi_ready
		')
		->set_relation
		(
			'id_prog',
			'ta__program.id',
			'{ref__urusan.kd_urusan}.{ref__bidang.kd_bidang}.{ref__unit.kd_unit}.{ref__sub.kd_sub}.{ref__program.kd_program}. {ref__sub.nm_sub} - {ref__program.nm_program}',
			array
			(
				'ta__program.tahun'					=> get_userdata('year')
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
		->set_validation('id_prog', 'required|numeric')
		->render($this->_table);
	}
	
	public function after_update()
	{
		$riwayat									= $this->model->select('riwayat_skpd')->get_where('ta__kegiatan', array('id' => $this->_id), 1)->row('riwayat_skpd');
		$riwayat									= json_decode($riwayat, true);
		$riwayat									= ($riwayat ? $riwayat : array());
		$riwayat[]									= array
		(
			'id_prog'								=> $this->input->post('id_prog'),
			'tanggal_update'						=> date('Y-m-d H:i:s'),
			'id_operator'							=> get_userdata('user_id')
		);
		$riwayat									= json_encode($riwayat);
		$this->model->update('ta__kegiatan', array('riwayat_skpd' => $riwayat), array('id' => $this->_id), 1);
	}
}