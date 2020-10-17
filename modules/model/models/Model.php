<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function get_rka($id_model = null)
	{
		$header_query								= $this->db->query
		('
			SELECT
				ta__model.kd_model,
				ta__model.nm_model,
				ta__model.`desc`
			FROM
				ta__model
			WHERE
				ta__model.id = ' . $id_model . '
			LIMIT 1
		')
		->row();
		$indikator_query								= $this->db->query
		('
			SELECT
				ta__model_indikator.jns_indikator,
				ref__indikator.nm_indikator,
				ta__model_indikator.kd_indikator,
				ta__model_indikator.tolak_ukur,
				ta__model_indikator.target,
				ta__model_indikator.satuan
			FROM
				ta__model_indikator
			INNER JOIN ref__indikator ON ta__model_indikator.jns_indikator = ref__indikator.id
			WHERE
				ta__model_indikator.id_model = ' . $id_model . '
			ORDER BY
				ref__indikator.kd_indikator,
				ta__model_indikator.kd_indikator
		')
		->result();
		$belanja_query								= $this->db->query
		('
			SELECT
				ref__rek_1.id AS id_rek_1,
				ref__rek_2.id AS id_rek_2,
				ref__rek_3.id AS id_rek_3,
				ref__rek_4.id AS id_rek_4,
				ref__rek_5.id AS id_rek_5,
				ref__rek_6.id AS id_rek_6,
				ta__model_belanja_sub.id AS id_belanja_sub,
				ta__model_belanja_rinci.id AS id_belanja_rinci,
				ref__rek_1.kd_rek_1,
				ref__rek_2.kd_rek_2,
				ref__rek_3.kd_rek_3,
				ref__rek_4.kd_rek_4,
				ref__rek_5.kd_rek_5,
				ref__rek_6.kd_rek_6,
				ta__model_belanja_sub.kd_belanja_sub,
				ta__model_belanja_rinci.kd_belanja_rinci,
				ref__rek_1.uraian AS nm_rek_1,
				ref__rek_2.uraian AS nm_rek_2,
				ref__rek_3.uraian AS nm_rek_3,
				ref__rek_4.uraian AS nm_rek_4,
				ref__rek_5.uraian AS nm_rek_5,
				ref__rek_6.uraian AS nm_rek_6,
				ta__model_belanja_sub.uraian AS nm_belanja_sub,
				ta__model_belanja_rinci.uraian AS nm_belanja_rinci,
				ta__model_belanja_rinci.vol_1,
				ta__model_belanja_rinci.vol_2,
				ta__model_belanja_rinci.vol_3,
				ta__model_belanja_rinci.satuan_1,
				ta__model_belanja_rinci.satuan_2,
				ta__model_belanja_rinci.satuan_3,
				ta__model_belanja_rinci.satuan_123,
				ta__model_belanja_rinci.nilai
			FROM
				ta__model_belanja_rinci
			INNER JOIN ta__model_belanja_sub ON ta__model_belanja_rinci.id_belanja_sub = ta__model_belanja_sub.id
			INNER JOIN ta__model_belanja ON ta__model_belanja_sub.id_belanja = ta__model_belanja.id
			INNER JOIN ref__rek_6 ON ta__model_belanja.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ta__model_belanja.id_model = ' . $id_model . '
			ORDER BY
				ref__rek_1.kd_rek_1 ASC,
				ref__rek_2.kd_rek_2 ASC,
				ref__rek_3.kd_rek_3 ASC,
				ref__rek_4.kd_rek_4 ASC,
				ref__rek_5.kd_rek_5 ASC,
				ref__rek_6.kd_rek_6 ASC,
				ta__model_belanja_sub.kd_belanja_sub ASC,
				ta__model_belanja_rinci.kd_belanja_rinci ASC
		')
		->result();
		$variabel									= $this->db->query
		('
			SELECT
				ta__model_variabel.id,
				ta__model_variabel.kd_variabel,
				ta__model_variabel.nm_variabel,
				ta__model_variabel.satuan
			FROM
				ta__model_variabel
			WHERE
				ta__model_variabel.id_model = ' . $id_model . '
			ORDER BY
				ta__model_variabel.kd_variabel ASC
		')
		->result();
		$output										= array
		(
			'header'								=> $header_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query,
			'variabel'								=> $variabel
		);
		//print_r($output);exit;
		return $output;
	}
	
	public function insert_rka($data = array(), $where = array())
	{
		$checker									= $this->db->get_where('rka__belanja', $where)->num_rows();
		if($checker > 0)
		{
			$this->db->where($where)->delete('rka__belanja');
			$this->db->insert_batch('rka__belanja', $data);
		}
		else
		{
			$this->db->insert_batch('rka__belanja', $data);
		}
	}
	
	public function get_rka_final($id_keg = null)
	{
		$header_query								= $this->db->query
		('
			SELECT
			ta__kegiatan.tahun,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ref__program.kd_program,
			ta__kegiatan.kd_keg,
			ref__urusan.nm_urusan,
			ref__bidang.nm_bidang,
			ref__unit.nm_unit,
			ref__sub.nm_sub,
			ref__program.nm_program,
			ta__kegiatan.kegiatan,
			ta__kegiatan.nilai
			FROM
			ta__kegiatan
			INNER JOIN ta__program ON ta__program.id_prog = ta__kegiatan.id_prog
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
		')
		->result_array();
		$indikator_query							= $this->db->query
		('
			SELECT
			ta__model_indikator.jns_indikator,
			ta__model_indikator.kd_indikator,
			ta__model_indikator.tolak_ukur,
			ta__model_indikator.target,
			ta__model_indikator.satuan,
			ta__kegiatan.variabel
			FROM
			ta__model_indikator
			INNER JOIN ta__model ON ta__model_indikator.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
			ta__model_indikator.jns_indikator ASC
		')
		->result_array();
		$belanja_query								= $this->db->query
		('
			SELECT
			rka__belanja.id,
			rka__belanja.tahun,
			rka__belanja.id_keg,
			rka__belanja.id_rek_1,
			rka__belanja.id_rek_2,
			rka__belanja.id_rek_3,
			rka__belanja.id_rek_4,
			rka__belanja.id_rek_5,
			rka__belanja.id_belanja_rinc,
			rka__belanja.id_belanja_rinc_sub,
			rka__belanja.kd_urusan,
			rka__belanja.kd_bidang,
			rka__belanja.kd_unit,
			rka__belanja.kd_sub,
			rka__belanja.kd_prog,
			rka__belanja.id_prog,
			rka__belanja.kd_keg,
			rka__belanja.kd_rek_1,
			rka__belanja.kd_rek_2,
			rka__belanja.kd_rek_3,
			rka__belanja.kd_rek_4,
			rka__belanja.kd_rek_5,
			rka__belanja.nm_rek_1,
			rka__belanja.nm_rek_2,
			rka__belanja.nm_rek_3,
			rka__belanja.nm_rek_4,
			rka__belanja.nm_rek_5,
			rek_1.subtotal_rek_1,
			rek_2.subtotal_rek_2,
			rek_3.subtotal_rek_3,
			rek_4.subtotal_rek_4,
			rek_5.subtotal_rek_5,
			rka__belanja.kd_rinc,
			rka__belanja.nm_rinc,
			rka__belanja.kd_rinc_sub,
			rka__belanja.nm_rinc_sub,
			rincian.subtotal_rinc,
			rka__belanja.nilai,
			rka__belanja.vol_1,
			rka__belanja.satuan_1,
			rka__belanja.vol_2,
			rka__belanja.satuan_2,
			rka__belanja.vol_3,
			rka__belanja.satuan_3,
			rka__belanja.vol_123,
			rka__belanja.satuan_123,
			rka__belanja.total
			FROM
			rka__belanja
			LEFT JOIN (
				SELECT
				id_belanja_rinc,
				Sum(rka__belanja.total) AS subtotal_rinc
				FROM
				rka__belanja
				WHERE
				rka__belanja.id_keg = ' . $id_keg . '
				GROUP BY
				rka__belanja.id_belanja_rinc
			) AS rincian ON rincian.id_belanja_rinc = rka__belanja.id_belanja_rinc
			LEFT JOIN (
				SELECT
				id_rek_5,
				Sum(rka__belanja.total) AS subtotal_rek_5
				FROM
				rka__belanja
				WHERE
				rka__belanja.id_keg = ' . $id_keg . '
				GROUP BY
				rka__belanja.id_rek_5
			) AS rek_5 ON rek_5.id_rek_5 = rka__belanja.id_rek_5
			LEFT JOIN (
				SELECT
				id_rek_4,
				Sum(rka__belanja.total) AS subtotal_rek_4
				FROM
				rka__belanja
				WHERE
				rka__belanja.id_keg = ' . $id_keg . '
				GROUP BY
				rka__belanja.id_rek_4
			) AS rek_4 ON rek_4.id_rek_4 = rka__belanja.id_rek_4
			LEFT JOIN (
				SELECT
				id_rek_3,
				Sum(rka__belanja.total) AS subtotal_rek_3
				FROM
				rka__belanja
				WHERE
				rka__belanja.id_keg = ' . $id_keg . '
				GROUP BY
				rka__belanja.id_rek_3
			) AS rek_3 ON rek_3.id_rek_3 = rka__belanja.id_rek_3
			LEFT JOIN (
				SELECT
				id_rek_2,
				Sum(rka__belanja.total) AS subtotal_rek_2
				FROM
				rka__belanja
				WHERE
				rka__belanja.id_keg = ' . $id_keg . '
				GROUP BY
				rka__belanja.id_rek_2
			) AS rek_2 ON rek_2.id_rek_2 = rka__belanja.id_rek_2
			LEFT JOIN (
				SELECT
				id_rek_1,
				Sum(rka__belanja.total) AS subtotal_rek_1
				FROM
				rka__belanja
				WHERE
				rka__belanja.id_keg = ' . $id_keg . '
				GROUP BY
				rka__belanja.id_rek_1
			) AS rek_1 ON rek_1.id_rek_1 = rka__belanja.id_rek_1
			WHERE
			rka__belanja.id_keg = ' . $id_keg . '
			ORDER BY
			rka__belanja.kd_rek_1 ASC,
			rka__belanja.kd_rek_2 ASC,
			rka__belanja.kd_rek_3 ASC,
			rka__belanja.kd_rek_4 ASC,
			rka__belanja.kd_rek_5 ASC,
			rka__belanja.kd_rinc ASC,
			rka__belanja.kd_rinc_sub ASC
		')
		->result_array();
		$output										= array
		(
			'header'								=> $header_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query
		);
		return $output;
	}
	
	public function get_leker($id_sub = null, $id_keg = null)
	{
		$header_query								= $this->db->query
		('
			SELECT
			ta__kegiatan.tahun,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ta__kegiatan.kd_keg,
			ref__urusan.nm_urusan,
			ref__bidang.nm_bidang,
			ref__unit.nm_unit,
			ref__sub.nm_sub,
			ta__kegiatan.kegiatan,
			ta__kegiatan.nilai
			FROM
			ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
		')
		->result_array();
		$indikator_query								= $this->db->query
		('
			SELECT
			ta__model_indikator.jns_indikator,
			ta__model_indikator.kd_indikator,
			ta__model_indikator.tolak_ukur,
			ta__model_indikator.target,
			ta__model_indikator.satuan
			FROM
			ta__model_indikator
			INNER JOIN ta__model ON ta__model_indikator.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
			ta__model_indikator.jns_indikator ASC
		')
		->result_array();
		$belanja_query								= $this->db->query
		('
			SELECT
			ref__rek_1.id AS id_rek_1,
			ref__rek_2.id AS id_rek_2,
			ref__rek_3.id AS id_rek_3,
			ref__rek_4.id AS id_rek_4,
			ref__rek_5.id AS id_rek_5,
			ta__model_belanja_rinc.id AS id_belanja_rinc,
			ta__model_belanja_rinc_sub.id AS id_belanja_rinc_sub,
			ref__rek_1.kd_rek_1,
			ref__rek_2.kd_rek_2,
			ref__rek_3.kd_rek_3,
			ref__rek_4.kd_rek_4,
			ref__rek_5.kd_rek_5,
			ref__rek_1.uraian AS nm_rek_1,
			ref__rek_2.uraian AS nm_rek_2,
			ref__rek_3.uraian AS nm_rek_3,
			ref__rek_4.uraian AS nm_rek_4,
			ref__rek_5.uraian AS nm_rek_5,
			ta__model_belanja_rinc.uraian AS nm_rinc,
			ta__model_belanja_rinc_sub.uraian AS nm_rinc_sub,
			ta__model_belanja_rinc_sub.vol_1,
			ta__model_belanja_rinc_sub.satuan_1,
			ta__model_belanja_rinc_sub.vol_2,
			ta__model_belanja_rinc_sub.satuan_2,
			ta__model_belanja_rinc_sub.vol_3,
			ta__model_belanja_rinc_sub.satuan_3,
			ta__model_belanja_rinc_sub.nilai,
			ta__model_belanja_rinc_sub.satuan_123,
			ta__kegiatan.variabel
			FROM
			ta__model_belanja_rinc_sub
			INNER JOIN ta__model_belanja_rinc ON ta__model_belanja_rinc_sub.id_belanja_rinc = ta__model_belanja_rinc.id
			INNER JOIN ta__model_belanja ON ta__model_belanja_rinc.id_belanja = ta__model_belanja.id
			INNER JOIN ta__model ON ta__model_belanja.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			INNER JOIN ref__rek_5 ON ta__model_belanja.id_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
			ref__rek_1.kd_rek_1 ASC,
			ref__rek_2.kd_rek_2 ASC,
			ref__rek_3.kd_rek_3 ASC,
			ref__rek_4.kd_rek_4 ASC,
			ref__rek_5.kd_rek_5 ASC,
			ta__model_belanja_rinc.kd_belanja_rinc ASC,
			ta__model_belanja_rinc_sub.kd_belanja_rinc_sub ASC
		')
		->result_array();
		$output										= array
		(
			'header'								=> $header_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query
		);
		return $output;
	}
	
	public function get_kelog($id_sub = null, $id_keg = null)
	{
		$header_query								= $this->db->query
		('
			SELECT
			ta__kegiatan.tahun,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ta__kegiatan.kd_keg,
			ref__urusan.nm_urusan,
			ref__bidang.nm_bidang,
			ref__unit.nm_unit,
			ref__sub.nm_sub,
			ta__kegiatan.kegiatan,
			ta__kegiatan.nilai
			FROM
			ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
		')
		->result_array();
		$indikator_query								= $this->db->query
		('
			SELECT
			ta__model_indikator.jns_indikator,
			ta__model_indikator.kd_indikator,
			ta__model_indikator.tolak_ukur,
			ta__model_indikator.target,
			ta__model_indikator.satuan
			FROM
			ta__model_indikator
			INNER JOIN ta__model ON ta__model_indikator.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
			ta__model_indikator.jns_indikator ASC
		')
		->result_array();
		$belanja_query								= $this->db->query
		('
			SELECT
			ref__rek_1.id AS id_rek_1,
			ref__rek_2.id AS id_rek_2,
			ref__rek_3.id AS id_rek_3,
			ref__rek_4.id AS id_rek_4,
			ref__rek_5.id AS id_rek_5,
			ta__model_belanja_rinc.id AS id_belanja_rinc,
			ta__model_belanja_rinc_sub.id AS id_belanja_rinc_sub,
			ref__rek_1.kd_rek_1,
			ref__rek_2.kd_rek_2,
			ref__rek_3.kd_rek_3,
			ref__rek_4.kd_rek_4,
			ref__rek_5.kd_rek_5,
			ref__rek_1.uraian AS nm_rek_1,
			ref__rek_2.uraian AS nm_rek_2,
			ref__rek_3.uraian AS nm_rek_3,
			ref__rek_4.uraian AS nm_rek_4,
			ref__rek_5.uraian AS nm_rek_5,
			ta__model_belanja_rinc.uraian AS nm_rinc,
			ta__model_belanja_rinc_sub.uraian AS nm_rinc_sub,
			ta__model_belanja_rinc_sub.vol_1,
			ta__model_belanja_rinc_sub.satuan_1,
			ta__model_belanja_rinc_sub.vol_2,
			ta__model_belanja_rinc_sub.satuan_2,
			ta__model_belanja_rinc_sub.vol_3,
			ta__model_belanja_rinc_sub.satuan_3,
			ta__model_belanja_rinc_sub.nilai,
			ta__model_belanja_rinc_sub.satuan_123,
			ta__kegiatan.variabel
			FROM
			ta__model_belanja_rinc_sub
			INNER JOIN ta__model_belanja_rinc ON ta__model_belanja_rinc_sub.id_belanja_rinc = ta__model_belanja_rinc.id
			INNER JOIN ta__model_belanja ON ta__model_belanja_rinc.id_belanja = ta__model_belanja.id
			INNER JOIN ta__model ON ta__model_belanja.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			INNER JOIN ref__rek_5 ON ta__model_belanja.id_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
			ref__rek_1.kd_rek_1 ASC,
			ref__rek_2.kd_rek_2 ASC,
			ref__rek_3.kd_rek_3 ASC,
			ref__rek_4.kd_rek_4 ASC,
			ref__rek_5.kd_rek_5 ASC,
			ta__model_belanja_rinc.kd_belanja_rinc ASC,
			ta__model_belanja_rinc_sub.kd_belanja_rinc_sub ASC
		')
		->result_array();
		$output										= array
		(
			'header'								=> $header_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query
		);
		return $output;
	}
	
	public function get_rab($id_sub = null, $id_keg = null)
	{
		$header_query								= $this->db->query
		('
			SELECT
			ta__kegiatan.tahun,
			ref__urusan.kd_urusan,
			ref__bidang.kd_bidang,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ta__kegiatan.kd_keg,
			ref__urusan.nm_urusan,
			ref__bidang.nm_bidang,
			ref__unit.nm_unit,
			ref__sub.nm_sub,
			ref__program.nm_program,
			ta__kegiatan.kegiatan,
			ta__kegiatan.nilai
			FROM
			ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ref__program.id = ta__program.id_prog
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			INNER JOIN ref__bidang ON ref__unit.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
		')
		->result_array();
		$indikator_query								= $this->db->query
		('
			SELECT
			ta__model_indikator.jns_indikator,
			ta__model_indikator.kd_indikator,
			ta__model_indikator.tolak_ukur,
			ta__model_indikator.target,
			ta__model_indikator.satuan
			FROM
			ta__model_indikator
			INNER JOIN ta__model ON ta__model_indikator.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
			ta__model_indikator.jns_indikator ASC
		')
		->result_array();
		$belanja_query								= $this->db->query
		('
			SELECT
			ref__rek_1.id AS id_rek_1,
			ref__rek_2.id AS id_rek_2,
			ref__rek_3.id AS id_rek_3,
			ref__rek_4.id AS id_rek_4,
			ref__rek_5.id AS id_rek_5,
			ta__model_belanja_rinc.id AS id_belanja_rinc,
			ta__model_belanja_rinc_sub.id AS id_belanja_rinc_sub,
			ref__rek_1.kd_rek_1,
			ref__rek_2.kd_rek_2,
			ref__rek_3.kd_rek_3,
			ref__rek_4.kd_rek_4,
			ref__rek_5.kd_rek_5,
			ref__rek_1.uraian AS nm_rek_1,
			ref__rek_2.uraian AS nm_rek_2,
			ref__rek_3.uraian AS nm_rek_3,
			ref__rek_4.uraian AS nm_rek_4,
			ref__rek_5.uraian AS nm_rek_5,
			ta__model_belanja_rinc.uraian AS nm_rinc,
			ta__model_belanja_rinc_sub.uraian AS nm_rinc_sub,
			ta__model_belanja_rinc_sub.vol_1,
			ta__model_belanja_rinc_sub.satuan_1,
			ta__model_belanja_rinc_sub.vol_2,
			ta__model_belanja_rinc_sub.satuan_2,
			ta__model_belanja_rinc_sub.vol_3,
			ta__model_belanja_rinc_sub.satuan_3,
			ta__model_belanja_rinc_sub.nilai,
			ta__model_belanja_rinc_sub.satuan_123,
			ta__kegiatan.variabel
			FROM
			ta__model_belanja_rinc_sub
			INNER JOIN ta__model_belanja_rinc ON ta__model_belanja_rinc_sub.id_belanja_rinc = ta__model_belanja_rinc.id
			INNER JOIN ta__model_belanja ON ta__model_belanja_rinc.id_belanja = ta__model_belanja.id
			INNER JOIN ta__model ON ta__model_belanja.id_model = ta__model.id
			INNER JOIN ta__kegiatan ON ta__kegiatan.id_model = ta__model.id
			INNER JOIN ref__rek_5 ON ta__model_belanja.id_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
			ta__kegiatan.id = ' . $id_keg . '
			ORDER BY
			ref__rek_1.kd_rek_1 ASC,
			ref__rek_2.kd_rek_2 ASC,
			ref__rek_3.kd_rek_3 ASC,
			ref__rek_4.kd_rek_4 ASC,
			ref__rek_5.kd_rek_5 ASC,
			ta__model_belanja_rinc.kd_belanja_rinc ASC,
			ta__model_belanja_rinc_sub.kd_belanja_rinc_sub ASC
		')
		->result_array();
		$output										= array
		(
			'header'								=> $header_query,
			'indikator'								=> $indikator_query,
			'belanja'								=> $belanja_query
		);
		return $output;
	}
}