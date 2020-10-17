<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Posting extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_year					= get_userdata('year');
		$this->set_theme('backend');
		$this->set_method('update');
		$this->parent_module('master');
		$this->set_permission();
	}
	
	public function index()
	{
		if('dropdown' == $this->input->post('trigger'))
		{
			return $this->_dropdown();
		}
		$this->set_title('Posting')
		->set_icon('mdi mdi-send')
		/*->set_output
		(
			array
			(
				'skpd'								=> $this->_skpd(),
				'program'							=> $this->_program(),
				'kegiatan'							=> $this->_kegiatan()
			)
		)*/
		->set_output
		(
			array
			(
				'sub_kegiatan'						=> $this->_sub_kegiatan()
			)
		)
		->set_validation
		(
			array
			(
				'kode_perubahan'					=> 'required|in_list[1,2,3,4,5,6,7,8,9,10,11,12,13,14,15,16,17,18,19,20]'
			)
		)
		->form_callback('do_posting')
		->render(null, 'form');
	}
	
	public function do_posting()
	{
		$this->permission->must_ajax();
		
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', -1);
		$where										= array
		(
			'kode_perubahan'						=> $this->input->post('kode_perubahan')
		);
		if($this->input->post('sub_kegiatan'))
		{
			$where['id_keg_sub']					= $this->input->post('sub_kegiatan');
			$where['id_keg']						= $this->input->post('kegiatan');
			$where['id_program']					= $this->input->post('program');
		}
		elseif($this->input->post('kegiatan'))
		{
			$where['id_keg']						= $this->input->post('kegiatan');
			$where['id_program']					= $this->input->post('program');
		}
		elseif($this->input->post('program'))
		{
			$where['id_program']					= $this->input->post('program');
		}
		elseif($this->input->post('sub_unit'))
		{
			$where['id_sub']						= $this->input->post('sub_unit');
		}
		$query_program								= $this->model->query
		('
			SELECT
				ta__program.tahun,
				' . $this->input->post('kode_perubahan') . ' AS kode_perubahan,
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				(CASE WHEN bidang_2.id != NULL THEN bidang_2.id ELSE 0 END) AS id_bidang_2,
				(CASE WHEN bidang_3.id != NULL THEN bidang_3.id ELSE 0 END) AS id_bidang_3,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_prog,
				ta__program.id AS id_program,
				ref__urusan.kd_urusan AS kode_urusan,
				(CASE WHEN urusan_2.kd_urusan != NULL THEN urusan_2.kd_urusan ELSE 0 END) AS kode_urusan_2,
				(CASE WHEN urusan_3.kd_urusan != NULL THEN urusan_3.kd_urusan ELSE 0 END) AS kode_urusan_3,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__program.kd_program AS kode_prog,
				ta__program.kd_id_prog AS kode_id_prog,
				ta__program.id_sasaran_indikator
			FROM
				ta__program
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__urusan urusan_2 ON bidang_2.id_urusan = urusan_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			LEFT JOIN ref__urusan urusan_3 ON bidang_3.id_urusan = urusan_3.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			' . 
				(
					$this->input->post('program') ? 'WHERE ta__program.id = ' . $this->input->post('program') : 
					(
						$this->input->post('sub_unit') ? 'WHERE ref__sub.id = ' . $this->input->post('sub_unit') : ''
					)
				) 
			. '
		')
		->result_array();
		//print_r($query_program);exit;
		$query_capaian_program						= $this->model->query
		('
			SELECT
				ta__program.tahun,
				' . $this->input->post('kode_perubahan') . ' AS kode_perubahan,
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				(CASE WHEN bidang_2.id != NULL THEN bidang_2.id ELSE 0 END) AS id_bidang_2,
				(CASE WHEN bidang_3.id != NULL THEN bidang_3.id ELSE 0 END) AS id_bidang_3,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_prog,
				ta__program.id AS id_program,
				ta__program_capaian.id AS id_program_capaian,
				ref__urusan.kd_urusan AS kode_urusan,
				(CASE WHEN urusan_2.kd_urusan != NULL THEN urusan_2.kd_urusan ELSE 0 END) AS kode_urusan_2,
				(CASE WHEN urusan_3.kd_urusan != NULL THEN urusan_3.kd_urusan ELSE 0 END) AS kode_urusan_3,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__program.kd_program AS kode_prog,
				ta__program.kd_id_prog AS kode_id_prog,
				ta__program_capaian.kode AS kode_capaian,
				ta__program_capaian.tolak_ukur,
				ta__program_capaian.tahun_1_target,
				ta__program_capaian.tahun_1_satuan,
				ta__program_capaian.tahun_2_target,
				ta__program_capaian.tahun_2_satuan,
				ta__program_capaian.tahun_3_target,
				ta__program_capaian.tahun_3_satuan,
				ta__program_capaian.tahun_4_target,
				ta__program_capaian.tahun_4_satuan,
				ta__program_capaian.tahun_5_target,
				ta__program_capaian.tahun_5_satuan,
				ta__program_capaian.status
			FROM
				ta__program_capaian
			INNER JOIN ta__program ON ta__program_capaian.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__urusan urusan_2 ON bidang_2.id_urusan = urusan_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			LEFT JOIN ref__urusan urusan_3 ON bidang_3.id_urusan = urusan_3.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			' . 
				(
					$this->input->post('program') ? 'WHERE ta__program.id = ' . $this->input->post('program') : 
					(
						$this->input->post('sub_unit') ? 'WHERE ref__sub.id = ' . $this->input->post('sub_unit') : ''
					)
				) 
			. '
		')
		->result_array();
		//print_r($query_capaian_program);exit;
		$query_kegiatan								= $this->model->query
		('
			SELECT
				ta__kegiatan.tahun,
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				(CASE WHEN bidang_2.id != NULL THEN bidang_2.id ELSE 0 END) AS id_bidang_2,
				(CASE WHEN bidang_3.id != NULL THEN bidang_3.id ELSE 0 END) AS id_bidang_3,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_prog,
				ta__program.id AS id_program,
				ta__kegiatan.id AS id_keg,
				' . $this->input->post('kode_perubahan') . ' AS kode_perubahan,
				ref__urusan.kd_urusan AS kode_urusan,
				(CASE WHEN urusan_2.kd_urusan != NULL THEN urusan_2.kd_urusan ELSE 0 END) AS kode_urusan_2,
				(CASE WHEN urusan_3.kd_urusan != NULL THEN urusan_3.kd_urusan ELSE 0 END) AS kode_urusan_3,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__program.kd_program AS kode_prog,
				ta__program.kd_id_prog AS kode_id_prog,
				ta__kegiatan.kd_keg AS kode_keg,
				ta__kegiatan.capaian_program,
				ta__kegiatan.id_kegiatan,
				ta__kegiatan.kegiatan,
				ta__kegiatan.files,
				ta__kegiatan.created,
				ta__kegiatan.updated,
				ta__kegiatan.riwayat_skpd
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__urusan urusan_2 ON bidang_2.id_urusan = urusan_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			LEFT JOIN ref__urusan urusan_3 ON bidang_3.id_urusan = urusan_3.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			' . 
				(
					$this->input->post('kegiatan') ? 'WHERE ta__kegiatan.id = ' . $this->input->post('kegiatan') : 
					(
						$this->input->post('program') ? 'WHERE ta__program.id = ' . $this->input->post('program') : 
						(
							$this->input->post('sub_unit') ? 'WHERE ref__sub.id = ' . $this->input->post('sub_unit') : ''
						)
					)
				) 
			. '
		')
		->result_array();
		//print_r($query_kegiatan);exit;
		$query_indikator							= $this->model->query
		('
			SELECT
				ta__kegiatan.tahun,
				' . $this->input->post('kode_perubahan') . ' AS kode_perubahan,
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				(CASE WHEN bidang_2.id != NULL THEN bidang_2.id ELSE 0 END) AS id_bidang_2,
				(CASE WHEN bidang_3.id != NULL THEN bidang_3.id ELSE 0 END) AS id_bidang_3,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_prog,
				ta__program.id AS id_program,
				ta__kegiatan.id AS id_keg,
				ta__indikator.id AS id_indikator,
				ref__urusan.kd_urusan,
				(CASE WHEN urusan_2.kd_urusan != NULL THEN urusan_2.kd_urusan ELSE 0 END) AS kd_urusan_2,
				(CASE WHEN urusan_3.kd_urusan != NULL THEN urusan_3.kd_urusan ELSE 0 END) AS kd_urusan_3,
				ref__bidang.kd_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kd_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kd_bidang_3,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__program.kd_id_prog,
				ta__kegiatan.kd_keg,
				ta__indikator.kd_indikator,
				ref__indikator.kd_indikator AS jns_indikator,
				ta__indikator.tolak_ukur,
				ta__indikator.target,
				ta__indikator.satuan
			FROM
				ta__indikator
			INNER JOIN ta__kegiatan ON ta__indikator.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__urusan urusan_2 ON bidang_2.id_urusan = urusan_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			LEFT JOIN ref__urusan urusan_3 ON bidang_3.id_urusan = urusan_3.id
			INNER JOIN ref__indikator ON ref__indikator.id = ta__indikator.jns_indikator
			' . 
				(
					$this->input->post('kegiatan') ? 'WHERE ta__kegiatan.id = ' . $this->input->post('kegiatan') : 
					(
						$this->input->post('program') ? 'WHERE ta__program.id = ' . $this->input->post('program') : 
						(
							$this->input->post('sub_unit') ? 'WHERE ref__sub.id = ' . $this->input->post('sub_unit') : ''
						)
					)
				) 
			. '
		')
		->result_array();
		//print_r($query_indikator);exit;
		$query_sub_kegiatan							= $this->model->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				(CASE WHEN bidang_2.id != NULL THEN bidang_2.id ELSE 0 END) AS id_bidang_2,
				(CASE WHEN bidang_3.id != NULL THEN bidang_3.id ELSE 0 END) AS id_bidang_3,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_prog,
				ta__program.id AS id_program,
				ta__kegiatan.id AS id_keg,
				ta__kegiatan_sub.id AS id_keg_sub,
				ta__kegiatan_sub.id_sumber_dana,
				' . $this->input->post('kode_perubahan') . ' AS kode_perubahan,
				ref__urusan.kd_urusan AS kode_urusan,
				(CASE WHEN urusan_2.kd_urusan != NULL THEN urusan_2.kd_urusan ELSE 0 END) AS kode_urusan_2,
				(CASE WHEN urusan_3.kd_urusan != NULL THEN urusan_3.kd_urusan ELSE 0 END) AS kode_urusan_3,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__program.kd_program AS kode_prog,
				ta__program.kd_id_prog AS kode_id_prog,
				ta__kegiatan.kd_keg AS kode_keg,
				ta__kegiatan_sub.kd_keg_sub AS kode_kegiatan_sub,
				ta__kegiatan_sub.jenis_kegiatan_renja,
				ta__kegiatan_sub.kegiatan_sub,
				ta__kegiatan_sub.map_coordinates,
				ta__kegiatan_sub.map_address,
				ta__kegiatan_sub.alamat_detail,
				ta__kegiatan_sub.kelurahan,
				ta__kegiatan_sub.kecamatan,
				ta__kegiatan_sub.files,
				ta__kegiatan_sub.kelompok_sasaran,
				ta__kegiatan_sub.waktu_pelaksanaan_mulai,
				ta__kegiatan_sub.waktu_pelaksanaan_sampai,
				ta__kegiatan_sub.capaian_kegiatan,
				ta__kegiatan_sub.survey,
				ta__kegiatan_sub.pagu,
				ta__kegiatan_sub.pagu_1,
				ta__kegiatan_sub.jenis_usulan,
				ta__kegiatan_sub.tahun,
				ta__kegiatan_sub.created,
				ta__kegiatan_sub.updated
			FROM
				ta__kegiatan_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan.id = ta__kegiatan_sub.id_keg
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__urusan urusan_2 ON bidang_2.id_urusan = urusan_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			LEFT JOIN ref__urusan urusan_3 ON bidang_3.id_urusan = urusan_3.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			' . 
				(
					$this->input->post('sub_kegiatan') ? 'WHERE ta__kegiatan_sub.id = ' . $this->input->post('sub_kegiatan') : 
					(
						$this->input->post('kegiatan') ? 'WHERE ta__kegiatan.id = ' . $this->input->post('kegiatan') : 
						(
							$this->input->post('program') ? 'WHERE ta__program.id = ' . $this->input->post('program') : 
							(
								$this->input->post('sub_unit') ? 'WHERE ref__sub.id = ' . $this->input->post('sub_unit') : ''
							)
						)
					)
				) 
			. '
				AND
				ta__kegiatan_sub.flag = 1
		')
		->result_array();
		//print_r($query_sub_kegiatan);exit;
		$query_indikator_sub						= $this->model->query
		('
			SELECT
				' . $this->input->post('kode_perubahan') . ' AS kode_perubahan,
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				(CASE WHEN bidang_2.id != NULL THEN bidang_2.id ELSE 0 END) AS id_bidang_2,
				(CASE WHEN bidang_3.id != NULL THEN bidang_3.id ELSE 0 END) AS id_bidang_3,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_prog,
				ta__program.id AS id_program,
				ta__kegiatan.id AS id_keg,
				ta__kegiatan_sub.id AS id_keg_sub,
				ta__indikator_sub.id AS id_indikator_sub,
				ref__urusan.kd_urusan,
				(CASE WHEN urusan_2.kd_urusan != NULL THEN urusan_2.kd_urusan ELSE 0 END) AS kd_urusan_2,
				(CASE WHEN urusan_3.kd_urusan != NULL THEN urusan_3.kd_urusan ELSE 0 END) AS kd_urusan_3,
				ref__bidang.kd_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kd_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kd_bidang_3,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__program.kd_id_prog,
				ta__kegiatan.kd_keg,
				ta__kegiatan_sub.kd_keg_sub,
				ta__indikator_sub.kd_indikator,
				ref__indikator.kd_indikator AS jns_indikator,
				ta__indikator_sub.tolak_ukur,
				ta__indikator_sub.target,
				ta__indikator_sub.satuan
			FROM
				ta__indikator_sub
			INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__indikator_sub.id_keg_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__urusan urusan_2 ON bidang_2.id_urusan = urusan_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			LEFT JOIN ref__urusan urusan_3 ON bidang_3.id_urusan = urusan_3.id
			INNER JOIN ref__indikator ON ref__indikator.id = ta__indikator_sub.jns_indikator
			' . 
				(
					$this->input->post('sub_kegiatan') ? 'WHERE ta__kegiatan_sub.id = ' . $this->input->post('sub_kegiatan') : 
					(
						$this->input->post('kegiatan') ? 'WHERE ta__kegiatan.id = ' . $this->input->post('kegiatan') : 
						(
							$this->input->post('program') ? 'WHERE ta__program.id = ' . $this->input->post('program') : 
							(
								$this->input->post('sub_unit') ? 'WHERE ref__sub.id = ' . $this->input->post('sub_unit') : ''
							)
						)
					)
				) 
			. '
			AND
				ta__kegiatan_sub.flag = 1
		')
		->result_array();
		$query_belanja								= $this->model->query
		('
			SELECT
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				(CASE WHEN bidang_2.id != NULL THEN bidang_2.id ELSE 0 END) AS id_bidang_2,
				(CASE WHEN bidang_3.id != NULL THEN bidang_3.id ELSE 0 END) AS id_bidang_3,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_prog,
				ta__program.id AS id_program,
				ta__kegiatan.id AS id_keg,
				ta__kegiatan_sub.id AS id_keg_sub,
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ref__rek_6.id AS id_rek_6,
				ta__belanja_rinci.id_sumber_dana,
				ta__belanja.id AS id_belanja,
				ta__belanja_sub.id AS id_belanja_sub,
				ta__belanja_rinci.id AS id_belanja_rinci,
				' . $this->input->post('kode_perubahan') . ' AS kode_perubahan,
				ref__urusan.kd_urusan AS kd_urusan,
				(CASE WHEN urusan_2.kd_urusan != NULL THEN urusan_2.kd_urusan ELSE 0 END) AS kd_urusan_2,
				(CASE WHEN urusan_3.kd_urusan != NULL THEN urusan_3.kd_urusan ELSE 0 END) AS kd_urusan_3,
				ref__bidang.kd_bidang AS kd_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kd_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kd_bidang_3,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.kd_program,
				ta__program.kd_id_prog,
				ta__kegiatan.kd_keg,
				ta__kegiatan_sub.kd_keg_sub,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ta__belanja_sub.kd_belanja_sub,
				ta__belanja_rinci.kd_belanja_rinci,
				ta__belanja_sub.uraian AS uraian_belanja_sub,
				ta__belanja_rinci.id_standar_harga,
				ta__belanja_rinci.uraian AS uraian_belanja_rinci,
				ta__belanja_rinci.vol_1,
				ta__belanja_rinci.vol_2,
				ta__belanja_rinci.vol_3,
				ta__belanja_rinci.satuan_1,
				ta__belanja_rinci.satuan_2,
				ta__belanja_rinci.satuan_3,
				ta__belanja_rinci.nilai,
				ta__belanja_rinci.vol_123,
				ta__belanja_rinci.satuan_123,
				ta__belanja_rinci.total,
				ref__rek_1.tahun
			FROM
				ta__belanja_rinci
			INNER JOIN ta__belanja_sub ON ta__belanja_rinci.id_belanja_sub = ta__belanja_sub.id
			INNER JOIN ta__belanja ON ta__belanja_sub.id_belanja = ta__belanja.id
			INNER JOIN ref__rek_6 ON ref__rek_6.id = ta__belanja.id_rek_6
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__urusan urusan_2 ON bidang_2.id_urusan = urusan_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			LEFT JOIN ref__urusan urusan_3 ON bidang_3.id_urusan = urusan_3.id
			' . 
				(
					$this->input->post('sub_kegiatan') ? 'WHERE ta__kegiatan_sub.id = ' . $this->input->post('sub_kegiatan') : 
					(
						$this->input->post('kegiatan') ? 'WHERE ta__kegiatan.id = ' . $this->input->post('kegiatan') : 
						(
							$this->input->post('program') ? 'WHERE ta__program.id = ' . $this->input->post('program') : 
							(
								$this->input->post('sub_unit') ? 'WHERE ref__sub.id = ' . $this->input->post('sub_unit') : ''
							)
						)
					)
				) 
			. '
			AND
				ta__kegiatan_sub.flag = 1
		')
		->result_array();
		$query_rencana								= $this->model->query
		('
			SELECT
				' . $this->_year . ' AS tahun,
				' . $this->input->post('kode_perubahan') . ' AS kode_perubahan,
				ref__urusan.id AS id_urusan,
				ref__bidang.id AS id_bidang,
				(CASE WHEN bidang_2.id != NULL THEN bidang_2.id ELSE 0 END) AS id_bidang_2,
				(CASE WHEN bidang_3.id != NULL THEN bidang_3.id ELSE 0 END) AS id_bidang_3,
				ref__unit.id AS id_unit,
				ref__sub.id AS id_sub,
				ref__program.id AS id_prog,
				ta__program.id AS id_program,
				ta__kegiatan.id AS id_keg,
				ta__kegiatan_sub.id AS id_keg_sub,
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ref__rek_6.id AS id_rek_6,
				ta__belanja.id AS id_belanja,
				ref__urusan.kd_urusan AS kode_urusan,
				(CASE WHEN urusan_2.kd_urusan != NULL THEN urusan_2.kd_urusan ELSE 0 END) AS kode_urusan_2,
				(CASE WHEN urusan_3.kd_urusan != NULL THEN urusan_3.kd_urusan ELSE 0 END) AS kode_urusan_3,
				ref__bidang.kd_bidang AS kode_bidang,
				(CASE WHEN bidang_2.kd_bidang != NULL THEN bidang_2.kd_bidang ELSE 0 END) AS kode_bidang_2,
				(CASE WHEN bidang_3.kd_bidang != NULL THEN bidang_3.kd_bidang ELSE 0 END) AS kode_bidang_3,
				ref__unit.kd_unit AS kode_unit,
				ref__sub.kd_sub AS kode_sub,
				ref__program.kd_program AS kode_prog,
				ta__program.kd_id_prog AS kode_id_prog,
				ta__kegiatan.kd_keg AS kode_keg,
				ta__kegiatan_sub.kd_keg_sub AS kode_keg_sub,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ta__rencana.jan,
				ta__rencana.feb,
				ta__rencana.mar,
				ta__rencana.apr,
				ta__rencana.mei,
				ta__rencana.jun,
				ta__rencana.jul,
				ta__rencana.agt,
				ta__rencana.sep,
				ta__rencana.okt,
				ta__rencana.nop,
				ta__rencana.des
			FROM
				ta__rencana
			INNER JOIN ta__belanja ON ta__rencana.id_belanja = ta__belanja.id
			INNER JOIN ref__rek_6 ON ref__rek_6.id = ta__belanja.id_rek_6
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			INNER JOIN ta__kegiatan_sub ON ta__kegiatan_sub.id = ta__belanja.id_keg_sub
			INNER JOIN ta__kegiatan ON ta__kegiatan_sub.id_keg = ta__kegiatan.id
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			LEFT JOIN ref__bidang bidang_2 ON ref__unit.id_bidang_2 = bidang_2.id
			LEFT JOIN ref__urusan urusan_2 ON bidang_2.id_urusan = urusan_2.id
			LEFT JOIN ref__bidang bidang_3 ON ref__unit.id_bidang_3 = bidang_3.id
			LEFT JOIN ref__urusan urusan_3 ON bidang_3.id_urusan = urusan_3.id
			' . 
				(
					$this->input->post('sub_kegiatan') ? 'WHERE ta__kegiatan_sub.id = ' . $this->input->post('sub_kegiatan') : 
					(
						$this->input->post('kegiatan') ? 'WHERE ta__kegiatan.id = ' . $this->input->post('kegiatan') : 
						(
							$this->input->post('program') ? 'WHERE ta__program.id = ' . $this->input->post('program') : 
							(
								$this->input->post('sub_unit') ? 'WHERE ref__sub.id = ' . $this->input->post('sub_unit') : ''
							)
						)
					)
				) 
			. '
			AND
				ta__kegiatan_sub.flag = 1
		')
		->result_array();
		//print_r($query_rencana);exit;
		$length_program								= sizeof($query_program);
		$length_program_capaian						= sizeof($query_capaian_program);
		$length_kegiatan							= sizeof($query_kegiatan);
		$length_indikator							= sizeof($query_indikator);
		$length_sub_kegiatan						= sizeof($query_sub_kegiatan);
		$length_indikator_sub						= sizeof($query_indikator_sub);
		$length_belanja								= sizeof($query_belanja);
		$length_rencana								= sizeof($query_rencana);
		//print_r($this->input->post('kegiatan'));exit();
		//print_r($where['id_keg_sub']);exit();
		if($length_kegiatan)
		{
			$execute_program						= false;
			$execute_program_capaian				= false;
			$execute_kegiatan						= false;
			$execute_indikator						= false;
			$execute_sub_kegiatan					= false;
			$execute_indikator_sub					= false;
			$execute_belanja						= false;
			$execute_rencana						= false;
			
			$where_program							= $where;
			$where_kegiatan							= $where;
			unset($where_program['id_keg'], $where_program['id_keg_sub']);
			unset($where_kegiatan['id_keg_sub']);
			//print_r($where);exit();
			
			if($length_program && $this->model->delete('ta__program_arsip', $where_program))
			{
				$execute_program					= $this->model->insert_batch('ta__program_arsip', $query_program);
			}
			if($length_program_capaian && $this->model->delete('ta__program_capaian_arsip', $where_program))
			{
				$execute_program_capaian			= $this->model->insert_batch('ta__program_capaian_arsip', $query_capaian_program);
			}
			if($length_kegiatan && $this->model->delete('ta__kegiatan_arsip', $where_kegiatan))
			{
				$execute_kegiatan					= $this->model->insert_batch('ta__kegiatan_arsip', $query_kegiatan);
			}
			if($length_indikator && $this->model->delete('ta__indikator_arsip', $where_kegiatan))
			{
				$execute_indikator					= $this->model->insert_batch('ta__indikator_arsip', $query_indikator);
			}
			if($length_sub_kegiatan && $this->model->delete('ta__kegiatan_sub_arsip', $where))
			{
				$execute_sub_kegiatan				= $this->model->insert_batch('ta__kegiatan_sub_arsip', $query_sub_kegiatan);
			}
			if($query_indikator_sub && $this->model->delete('ta__indikator_sub_arsip', $where))
			{
				$execute_indikator_sub				= $this->model->insert_batch('ta__indikator_sub_arsip', $query_indikator_sub);
			}
			if($query_belanja && $this->model->delete('ta__belanja_arsip', $where))
			{
				$execute_belanja					= $this->model->insert_batch('ta__belanja_arsip', $query_belanja);
			}
			if($query_rencana && $this->model->delete('ta__rencana_arsip', $where))
			{
				$execute_rencana					= $this->model->insert_batch('ta__rencana_arsip', $query_rencana);
			}
			if($execute_sub_kegiatan || $execute_indikator_sub || $execute_belanja || $execute_rencana)
			{
				return throw_exception(200, 'Berhasil memposting ' . $length_program . ' Program, ' . $length_program_capaian . ' Capaian Program, ' . $length_kegiatan . ' Kegiatan, ' . $length_indikator . ' Indikator Kegiatan, ' . $length_sub_kegiatan . ' Sub Kegiatan, ' . $length_indikator_sub . ' Indikator Sub Kegiatan, ' . $length_rencana . ' Rencana Anggaran, ' . $length_belanja . ' Rincian Belanja . ');
			}
			else
			{
				return throw_exception(400, array(array('<i class="mdi mdi-information-outline"></i> Gagal memposting beberapa data. Silakan mencoba kembali.')));
			}
		}
		else
		{
			return throw_exception(400, array(array('<i class="mdi mdi-information-outline"></i> Tidak ada data yang dapat diproses.')));
		}
	}
	
	private function _sub_kegiatan()
	{
		$output										= null;
		if(1 == get_userdata('group_id'))
		{
			$query									= $this->model
			->select
			('
				ref__sub.id,
				ref__sub.nm_sub,
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub
			')
			->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
			->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
			->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
			->get_where('ref__sub', array('ref__sub.id !=' => NULL))
			->result_array();
			if($query)
			{
				$options							= null;
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_sub']) . ' ' . $val['nm_sub'] . '</option>';
				}
				$output								.= '
					<div class="form-group">
						<label class="d-block">
							SUB UNIT
						</label>
						<select name="sub_unit" class="form-control form-control-sm report-dropdown" to-change=".program">
							<option value="">Silakan pilih Sub Unit</option>
							' . $options . '
						</select>
					</div>
					<div class="form-group">
						<label class="d-block">
							PROGRAM
						</label>
						<select name="program" class="form-control form-control-sm report-dropdown program" to-change=".kegiatan" disabled>
							<option value="">Silakan pilih Sub Unit terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="d-block">
							KEGIATAN
						</label>
						<select name="kegiatan" class="form-control form-control-sm report-dropdown kegiatan" to-change=".sub_kegiatan" disabled>
							<option value="">Silakan pilih Program terlebih dahulu</option>
						</select>
					</div>
					<div class="form-group">
						<label class="d-block">
							Sub Kegiatan
						</label>
						<select name="sub_kegiatan" class="form-control form-control-sm sub_kegiatan" disabled>
							<option value="">Silakan pilih kegiatan terlebih dahulu</option>
						</select>
					</div>
				';
			}
		}
		else
		{
			return false;
		}
		return $output;
	}
	
	private function _dropdown()
	{
		$primary									= $this->input->post('primary');
		$element									= $this->input->post('element');
		$options									= null;
		if('.program' == $element)
		{
			$query									= $this->model
			->select
			('
				ta__program.id,
				ref__program.kd_program,
				ref__program.nm_program
			')
			->join('ta__program', 'ta__program.id_prog = ref__program.id')
			->order_by('ref__program.kd_program')
			->get_where('ref__program', array('ta__program.id_sub' => $primary))
			->result_array();
			if($query)
			{
				$options							= '<option value="">Silakan pilih Program</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_program'] . '. ' . $val['nm_program'] . '</option>';
				}
			}
		}
		elseif('.kegiatan' == $element)
		{
			$query									= $this->model
			->order_by('kd_keg')
			->get_where('ta__kegiatan', array('id_prog' => $primary))
			->result_array();
			if($query)
			{
				$options							= '<option value="">Silakan pilih Kegiatan</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_keg'] . '. ' . $val['kegiatan'] . '</option>';
				}
			}
		}
		elseif('.sub_kegiatan' == $element)
		{
			$query									= $this->model
			->order_by('kd_keg_sub')
			->get_where('ta__kegiatan_sub', array('id_keg' => $primary))
			->result_array();
			if($query)
			{
				$options							= '<option value="">Silakan pilih Sub Kegiatan</option>';
				foreach($query as $key => $val)
				{
					$options						.= '<option value="' . $val['id'] . '">' . $val['kd_keg_sub'] . '. ' . $val['kegiatan_sub'] . '</option>';
				}
			}
		}
		make_json
		(
			array
			(
				'results'							=> $options,
				'element'							=> $element,
				'html'								=> ($options ? $options : '<option value="">Data yang dipilih tidak mendapatkan hasil</options>')
			)
		);
	}
	/*
	private function _skpd()
	{
		$query										= $this->model
													->select('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit, ref__sub.id, ref__sub.kd_sub, ref__sub.nm_sub')
													->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
													->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
													->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
													->get('ref__sub')->result();
		$options									= '<option value="">Silakan pilih SKPD</option>';
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '.' . $val->kd_sub . ' ' . $val->nm_sub . '</option>';
			}
		}
		return '
			<select name="id_sub" class="form-control" placeholder="Silakan pilih SKPD">
				' . $options . '
			</select>
		';
	}
	
	private function _program()
	{
		$query										= $this->model->select('ta__program.id, ref__program.nm_program')->join('ref__program', 'ref__program.id = ta__program.id_prog')->get('ta__program')->result();
		$options									= '<option value="">Silakan pilih Program</option>';
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val->id . '">' . $val->nm_program . '</option>';
			}
		}
		return '
			<select name="id_prog" class="form-control" placeholder="Silakan pilih Program">
				' . $options . '
			</select>
		';
	}
	
	private function _kegiatan()
	{
		$query										= $this->model->get('ta__kegiatan')->result();
		$options									= '<option value="">Silakan pilih Kegiatan</option>';
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val->id . '">' . $val->kegiatan . '</option>';
			}
		}
		return '
			<select name="id_keg" class="form-control" placeholder="Silakan pilih Kegiatan">
				' . $options . '
			</select>
		';
	}*/
}