<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Dashboard_model extends CI_Model
{
	function __construct()
	{
		parent::__construct();
	}
	
	public function card()
	{
		$pendapatan_query							= $this->db->query
		('
			SELECT
				Sum(ta__anggaran_pendapatan_rinci.total) AS total
			FROM
				ta__anggaran_pendapatan_rinci
			LIMIT 1
		')
		->row('total');
		$belanja_query							= $this->db->query
		('
			SELECT
				Sum(ta__belanja_rinci.total) AS total
			FROM
				ta__belanja_rinci
			LIMIT 1
		')
		->row('total');
		$pembiayaan_penerimaan_query			= $this->db->query
		('
			SELECT
				Sum(ta__anggaran_pembiayaan_rinci.total) AS total
			FROM
				ta__anggaran_pembiayaan_rinci
			INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
			INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ref__rek_1.kd_rek_1 = 6
			AND ref__rek_2.kd_rek_2 = 1
			LIMIT 1
		')
		->row('total');
		$pembiayaan_pengeluaran_query			= $this->db->query
		('
			SELECT
				Sum(ta__anggaran_pembiayaan_rinci.total) AS total
			FROM
				ta__anggaran_pembiayaan_rinci
			INNER JOIN ta__anggaran_pembiayaan ON ta__anggaran_pembiayaan_rinci.id_anggaran_pembiayaan = ta__anggaran_pembiayaan.id
			INNER JOIN ref__rek_6 ON ta__anggaran_pembiayaan.id_rek_6 = ref__rek_6.id
			INNER JOIN ref__rek_5 ON ref__rek_6.id_ref_rek_5 = ref__rek_5.id
			INNER JOIN ref__rek_4 ON ref__rek_5.id_ref_rek_4 = ref__rek_4.id
			INNER JOIN ref__rek_3 ON ref__rek_4.id_ref_rek_3 = ref__rek_3.id
			INNER JOIN ref__rek_2 ON ref__rek_3.id_ref_rek_2 = ref__rek_2.id
			INNER JOIN ref__rek_1 ON ref__rek_2.id_ref_rek_1 = ref__rek_1.id
			WHERE
				ref__rek_1.kd_rek_1 = 6
			AND ref__rek_2.kd_rek_2 = 2
			LIMIT 1
		')
		->row('total');
		$output										= array
		(
			'pendapatan'							=> $pendapatan_query,
			'belanja'								=> $belanja_query,
			'pembiayaan_penerimaan'					=> $pembiayaan_penerimaan_query,
			'pembiayaan_pengeluaran'				=> $pembiayaan_pengeluaran_query
		);
		//print_r($output);exit;
		return $output;
	}
	
	private function _count_items($flag = 0)
	{
		return $this->model->select('id')->get_where('ta__saldo_awal', array('id' => $flag))->num_rows();
	}
}