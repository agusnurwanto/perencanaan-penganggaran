<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Integrasi > SIPD > Pencocokan SIPD > Sub Unit
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Sub_unit extends Aksara
{
	private $_table									= 'tmp__sub_unit';
	
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
		
		$this->set_title('Pencocokan Sub Unit SIPD')
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
		$query										= $this->model->order_by('kode_sipd, kode_siencang')->get($this->_table)->result();
		
		$data										= array
		(
			'title'									=> 'Lembar Kontrol Sub Unit',
			'results'								=> $query
		);
		
		$this->load->view($this->_template, $data);
	}
	
	private function _data()
	{
		$this->model->truncate($this->_table);
		
		$query_sipd									= $this->model->select
		('
			IFNULL(sipd__ref__urusan.kd_urusan, 0) AS kd_urusan,
			IFNULL(urusan_2.kd_urusan, 0) AS kd_urusan_2,
			IFNULL(urusan_3.kd_urusan, 0) AS kd_urusan_3,
			IFNULL(sipd__ref__bidang.kd_bidang, 0) AS kd_bidang,
			IFNULL(bidang_2.kd_bidang, 0) AS kd_bidang_2,
			IFNULL(bidang_3.kd_bidang, 0) AS kd_bidang_3,
			sipd__ref__unit.kd_unit,
			sipd__ref__sub.kd_sub,
			sipd__ref__sub.nm_sub,
			"sipd" AS source
		')
		->join
		(
			'sipd__ref__unit',
			'sipd__ref__unit.id = sipd__ref__sub.id_unit'
		)
		->join
		(
			'sipd__ref__bidang',
			'sipd__ref__bidang.id = sipd__ref__unit.id_bidang'
		)
		->join
		(
			'sipd__ref__bidang bidang_2',
			'bidang_2.id = sipd__ref__unit.id_bidang_2',
			'left'
		)
		->join
		(
			'sipd__ref__bidang bidang_3',
			'bidang_3.id = sipd__ref__unit.id_bidang_3',
			'left'
		)
		->join
		(
			'sipd__ref__urusan',
			'sipd__ref__urusan.id = sipd__ref__bidang.id_urusan'
		)
		->join
		(
			'sipd__ref__urusan urusan_2',
			'urusan_2.id = bidang_2.id_urusan',
			'left'
		)
		->join
		(
			'sipd__ref__urusan urusan_3',
			'urusan_3.id = bidang_3.id_urusan',
			'left'
		)
		->order_by('sipd__ref__urusan.kd_urusan, sipd__ref__bidang.kd_bidang, sipd__ref__unit.kd_unit')
		->get_where
		(
			'sipd__ref__sub',
			array
			(
				'sipd__ref__sub.tahun'				=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_siencang								= $this->model->select
		('
			IFNULL(ref__urusan.kd_urusan, 0) AS kd_urusan,
			IFNULL(urusan_2.kd_urusan, 0) AS kd_urusan_2,
			IFNULL(urusan_3.kd_urusan, 0) AS kd_urusan_3,
			IFNULL(ref__bidang.kd_bidang, 0) AS kd_bidang,
			IFNULL(bidang_2.kd_bidang, 0) AS kd_bidang_2,
			IFNULL(bidang_3.kd_bidang, 0) AS kd_bidang_3,
			ref__unit.kd_unit,
			ref__sub.kd_sub,
			ref__sub.nm_sub,
			"siencang" AS source
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
			'ref__bidang bidang_2',
			'bidang_2.id = ref__unit.id_bidang_2',
			'left'
		)
		->join
		(
			'ref__bidang bidang_3',
			'bidang_3.id = ref__unit.id_bidang_3',
			'left'
		)
		->join
		(
			'ref__urusan',
			'ref__urusan.id = ref__bidang.id_urusan'
		)
		->join
		(
			'ref__urusan urusan_2',
			'urusan_2.id = bidang_2.id_urusan',
			'left'
		)
		->join
		(
			'ref__urusan urusan_3',
			'urusan_3.id = bidang_3.id_urusan',
			'left'
		)
		->order_by('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__unit.kd_unit')
		->get_where
		(
			'ref__sub',
			array
			(
				'ref__sub.tahun'					=> get_userdata('year')
			)
		)
		->result_array();
		
		$results									= array_merge($query_sipd, $query_siencang);
		$output										= array();
		
		foreach($query_sipd as $key => $val)
		{
			$kode									= $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . $val['kd_urusan_2'] . '.' . sprintf('%02d', $val['kd_bidang_2']) . '.' . $val['kd_urusan_3'] . '.' . sprintf('%02d', $val['kd_bidang_3']) . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_sub']);
			
			$output[$kode]							= $val;
			
			$this->model->insert
			(
				$this->_table,
				array
				(
					'kode_sipd'						=> $kode,
					'label_sipd'					=> $val['nm_sub']
				)
			);
		}
		
		foreach($query_siencang as $key => $val)
		{
			$kode									= $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . $val['kd_urusan_2'] . '.' . sprintf('%02d', $val['kd_bidang_2']) . '.' . $val['kd_urusan_3'] . '.' . sprintf('%02d', $val['kd_bidang_3']) . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_sub']);
			
			if(isset($output[$kode]))
			{
				$this->model->update
				(
					$this->_table,
					array
					(
						'kode_siencang'				=> $kode,
						'label_siencang'			=> $val['nm_sub']
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
						'label_siencang'			=> $val['nm_sub']
					)
				);
			}
		}
	}
}
