<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Integrasi > SIPD > Pencocokan SIPD
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Pencocokan_sipd extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		$this->set_theme('backend');
		
		$this->_year								= get_userdata('year');
	}
	
	public function index()
	{
		$this->set_title('Pencocokan SIPD')
		->set_icon('mdi mdi-refresh')
		->set_output
		(
			array
			(
				'sub_unit'							=> $this->_sub_unit()
			)
		)
		->render();
	}
	
	private function _sub_unit()
	{
		$query										= $this->model->select
		('
			ref__sub.id,
			IFNULL(ref__urusan.kd_urusan, 0) AS kd_urusan,
			IFNULL(ref__bidang.kd_bidang, 0) AS kd_bidang,
			IFNULL(ref__urusan_2.kd_urusan, 0) AS kd_urusan_2,
			IFNULL(ref__bidang_2.kd_bidang, 0) AS kd_bidang_2,
			IFNULL(ref__urusan_3.kd_urusan, 0) AS kd_urusan_3,
			IFNULL(ref__bidang_3.kd_bidang, 0) AS kd_bidang_3,
			IFNULL(ref__unit.kd_unit, 0) AS kd_unit,
			IFNULL(ref__sub.kd_sub, 0) AS kd_sub,
			ref__sub.nm_sub
		')
		->join
		(
			'ref__unit',
			'ref__unit.id = ref__sub.id_unit'
		)
		->join
		(
			'ref__bidang',
			'ref__bidang.id = ref__unit.id_bidang'
		)
		->join
		(
			'ref__bidang ref__bidang_2',
			'ref__bidang_2.id = ref__unit.id_bidang_2',
			'LEFT'
		)
		->join
		(
			'ref__bidang ref__bidang_3',
			'ref__bidang_3.id = ref__unit.id_bidang_3',
			'LEFT'
		)
		->join
		(
			'ref__urusan',
			'ref__urusan.id = ref__bidang.id_urusan'
		)
		->join
		(
			'ref__urusan ref__urusan_2',
			'ref__urusan_2.id = ref__bidang_2.id_urusan',
			'LEFT'
		)
		->join
		(
			'ref__urusan ref__urusan_3',
			'ref__urusan_3.id = ref__bidang_3.id_urusan',
			'LEFT'
		)
		->get_where
		(
			'ref__sub',
			array
			(
				'ref__sub.tahun'					=> $this->_year
			)
		)
		->result();
		
		return $query;
	}
}
