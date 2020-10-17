<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Integrasi > SIPD > Pencocokan SIPD > Rekening
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Rekening extends Aksara
{
	private $_table									= 'tmp__rekening';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_theme('backend');
		
		$this->unset_action('create, read, delete, export, print, pdf');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
	}
	
	public function index()
	{
		$this->_data();
		
		$this->set_title('Pencocokan Rekening SIPD')
		->set_icon('mdi mdi-refresh')
		
		->set_primary('kode_sipd')
		
		->add_action('toolbar', 'lembar_kontrol', 'Lembar Kontrol', 'btn-primary', null, null, true)
		
		->order_by('kode_sipd, kode_siencang')
		
		->select
		('
			' . $this->_table . '.kode_sipd AS status
		')
		->merge_content('{status}', 'Status', 'callback_status')
		->render($this->_table);
	}
	
	public function status($params = array())
	{
		if(!isset($params['status'])) return false;
		
		$query										= $this->model->get_where
		(
			$this->_table,
			array
			(
				'kode_sipd'							=> $params['status']
			),
			1
		)
		->row();
		
		if(!$query || $query->kode_sipd != $query->kode_siencang || $query->label_sipd != $query->label_siencang)
		{
			return '<span class="badge badge-danger">Tidak Sesuai</span>';
		}
		
		return '<span class="badge badge-success">Sesuai</span>';
	}
	
	public function before_update()
	{
	}
	
	public function lembar_kontrol()
	{
		$query										= $this->model->order_by('kode_sipd')->get($this->_table)->result();
		
		$data										= array
		(
			'title'									=> 'Lembar Kontrol Rekening',
			'results'								=> $query
		);
		
		$this->load->view($this->_template, $data);
	}
	
	private function _data()
	{
		$this->model->truncate($this->_table);
		
		$query_sipd									= $this->model->select
		('
			IFNULL(sipd__ref__rek_1.kd_rek_1, 0) AS kd_rek_1,
			IFNULL(sipd__ref__rek_2.kd_rek_2, 0) AS kd_rek_2,
			IFNULL(sipd__ref__rek_3.kd_rek_3, 0) AS kd_rek_3,
			IFNULL(sipd__ref__rek_4.kd_rek_4, 0) AS kd_rek_4,
			IFNULL(sipd__ref__rek_5.kd_rek_5, 0) AS kd_rek_5,
			IFNULL(sipd__ref__rek_6.kd_rek_6, 0) AS kd_rek_6,
			sipd__ref__rek_6.uraian,
			"sipd" AS source
		')
		->join
		(
			'sipd__ref__rek_5',
			'sipd__ref__rek_5.id = sipd__ref__rek_6.id_ref_rek_5'
		)
		->join
		(
			'sipd__ref__rek_4',
			'sipd__ref__rek_4.id = sipd__ref__rek_5.id_ref_rek_4'
		)
		->join
		(
			'sipd__ref__rek_3',
			'sipd__ref__rek_3.id = sipd__ref__rek_4.id_ref_rek_3'
		)
		->join
		(
			'sipd__ref__rek_2',
			'sipd__ref__rek_2.id = sipd__ref__rek_3.id_ref_rek_2'
		)
		->join
		(
			'sipd__ref__rek_1',
			'sipd__ref__rek_1.id = sipd__ref__rek_2.id_ref_rek_1'
		)
		->get_where
		(
			'sipd__ref__rek_6',
			array
			(
				'sipd__ref__rek_1.tahun'			=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_siencang								= $this->model->select
		('
			IFNULL(ref__rek_1.kd_rek_1, 0) AS kd_rek_1,
			IFNULL(ref__rek_2.kd_rek_2, 0) AS kd_rek_2,
			IFNULL(ref__rek_3.kd_rek_3, 0) AS kd_rek_3,
			IFNULL(ref__rek_4.kd_rek_4, 0) AS kd_rek_4,
			IFNULL(ref__rek_5.kd_rek_5, 0) AS kd_rek_5,
			IFNULL(ref__rek_6.kd_rek_6, 0) AS kd_rek_6,
			ref__rek_6.uraian,
			"siencang" AS source
		')
		->join
		(
			'ref__rek_5',
			'ref__rek_5.id = ref__rek_6.id_ref_rek_5'
		)
		->join
		(
			'ref__rek_4',
			'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
		)
		->join
		(
			'ref__rek_3',
			'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
		)
		->join
		(
			'ref__rek_2',
			'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
		)
		->join
		(
			'ref__rek_1',
			'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
		)
		->get_where
		(
			'ref__rek_6',
			array
			(
				'ref__rek_1.tahun'					=> get_userdata('year')
			)
		)
		->result_array();
		
		$results									= array_merge($query_sipd, $query_siencang);
		$output										= array();
		
		foreach($query_sipd as $key => $val)
		{
			$kode									= $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '.' . $val['kd_rek_3'] . '.' . $val['kd_rek_4'] . '.' . $val['kd_rek_5'] . '.' . $val['kd_rek_6'];
			
			$output[$kode]							= $val;
			
			$this->model->insert
			(
				$this->_table,
				array
				(
					'kode_sipd'						=> $kode,
					'label_sipd'					=> $val['uraian']
				)
			);
		}
		
		foreach($query_siencang as $key => $val)
		{
			$kode									= $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '.' . $val['kd_rek_3'] . '.' . $val['kd_rek_4'] . '.' . $val['kd_rek_5'] . '.' . $val['kd_rek_6'];
			
			if(isset($output[$kode]))
			{
				$this->model->update
				(
					$this->_table,
					array
					(
						'kode_siencang'				=> $kode,
						'label_siencang'			=> $val['uraian']
					),
					array
					(
						'kode_sipd'					=> $kode
					)
				);
			}
			else
			{
				$this->model->insert
				(
					$this->_table,
					array
					(
						'kode_siencang'				=> $kode,
						'label_siencang'			=> $val['nm_rek_6']
					)
				);
			}
		}
	}
}
