<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Integrasi > SIPD > Pencocokan SIPD > Kegiatan
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Kegiatan extends Aksara
{
	private $_table									= 'tmp__kegiatan';
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_theme('backend');
		
		$this->unset_action('create, read, delete, export, print, pdf');
		
		$this->_template							= $this->uri->segment(2) . ($this->uri->segment(3) ? '/' . $this->uri->segment(3) : null) . ($this->uri->segment(4) ? '/' . $this->uri->segment(4) : null);
		$this->_id_sub								= ($this->input->post('sub_unit') ? $this->input->post('sub_unit') : 0);
		$this->_year								= get_userdata('year');
	}
	
	public function index()
	{
		$this->_data();
		
		$this->set_title('Pencocokan Kegiatan SIPD')
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
			'title'									=> 'Lembar Kontrol Kegiatan',
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
			IFNULL(sipd__ref__bidang.kd_bidang, 0) AS kd_bidang,
			sipd__ref__program.kd_program,
			sipd__ref__kegiatan.kd_kegiatan AS kd_kegiatan,
			sipd__ta__kegiatan.kd_keg,
			sipd__ta__kegiatan.kegiatan,
			"sipd" AS source
		')
		->join
		(
			'sipd__ref__kegiatan',
			'sipd__ref__kegiatan.id = sipd__ta__kegiatan.id_kegiatan'
		)
		->join
		(
			'sipd__ref__program',
			'sipd__ref__program.id = sipd__ref__kegiatan.id_program'
		)
		->join
		(
			'sipd__ref__bidang',
			'sipd__ref__bidang.id = sipd__ref__program.id_bidang'
		)
		->join
		(
			'sipd__ref__urusan',
			'sipd__ref__urusan.id = sipd__ref__bidang.id_urusan'
		)
		->order_by('sipd__ref__urusan.kd_urusan, sipd__ref__bidang.kd_bidang, sipd__ref__program.kd_program, sipd__ref__kegiatan.kd_kegiatan, sipd__ta__kegiatan.kd_keg')
		->get_where
		(
			'sipd__ta__kegiatan',
			array
			(
				'sipd__ta__kegiatan.tahun'			=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_siencang								= $this->model->select
		('
			IFNULL(ref__urusan.kd_urusan, 0) AS kd_urusan,
			IFNULL(ref__bidang.kd_bidang, 0) AS kd_bidang,
			ref__program.kd_program,
			ref__kegiatan.kd_kegiatan AS kd_kegiatan,
			ta__kegiatan.kd_keg,
			ta__kegiatan.kegiatan,
			"siencang" AS source
		')
		->join
		(
			'ref__kegiatan',
			'ref__kegiatan.id = ta__kegiatan.id_kegiatan'
		)
		->join
		(
			'ref__program',
			'ref__program.id = ref__kegiatan.id_program'
		)
		->join
		(
			'ref__bidang',
			'ref__bidang.id = ref__program.id_bidang'
		)
		->join
		(
			'ref__urusan',
			'ref__urusan.id = ref__bidang.id_urusan'
		)
		->order_by('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__program.kd_program, ref__kegiatan.kd_kegiatan, ta__kegiatan.kd_keg')
		->get_where
		(
			'ta__kegiatan',
			array
			(
				'ta__kegiatan.tahun'				=> get_userdata('year')
			)
		)
		->result_array();
		
		$results									= array_merge($query_sipd, $query_siencang);
		$output										= array();
		
		foreach($query_sipd as $key => $val)
		{
			$kode									= $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . sprintf('%02d', $val['kd_program']) . '.' . sprintf('%02d', $val['kd_kegiatan']) . '.' . sprintf('%02d', $val['kd_keg']);
			
			$output[$kode]							= $val;
			
			$this->model->insert
			(
				$this->_table,
				array
				(
					'kode_sipd'						=> $kode,
					'label_sipd'					=> $val['kegiatan']
				)
			);
		}
		
		foreach($query_siencang as $key => $val)
		{
			$kode									= $val['kd_urusan'] . '.' . $val['kd_bidang'] . '.' . sprintf('%02d', $val['kd_program']) . '.' . sprintf('%02d', $val['kd_kegiatan']) . '.' . sprintf('%02d', $val['kd_keg']);
			
			if(isset($output[$kode]))
			{
				$this->model->update
				(
					$this->_table,
					array
					(
						'kode_siencang'				=> $kode,
						'label_siencang'			=> $val['kegiatan']
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
						'label_siencang'			=> $val['kegiatan']
					)
				);
			}
		}
	}
}
