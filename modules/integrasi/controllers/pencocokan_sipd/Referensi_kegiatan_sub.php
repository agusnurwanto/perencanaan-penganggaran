<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Integrasi > SIPD > Pencocokan SIPD > Referensi Kegiatan
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Referensi_kegiatan_sub extends Aksara
{
	private $_table									= 'tmp__referensi_kegiatan_sub';
	
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
		
		$this->set_title('Pencocokan Referensi Kegiatan SIPD')
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
			sipd__ref__kegiatan.kd_kegiatan,
			sipd__ref__kegiatan_sub.kd_kegiatan_sub,
			sipd__ref__kegiatan_sub.nm_kegiatan_sub,
			"sipd" AS source
		')
		->join
		(
			'sipd__ref__kegiatan',
			'sipd__ref__kegiatan.id = sipd__ref__kegiatan_sub.id_kegiatan'
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
		->order_by('sipd__ref__urusan.kd_urusan, sipd__ref__bidang.kd_bidang, sipd__ref__program.kd_program, sipd__ref__kegiatan.kd_kegiatan, sipd__ref__kegiatan_sub.kd_kegiatan_sub')
		->get_where
		(
			'sipd__ref__kegiatan_sub',
			array
			(
				'sipd__ref__kegiatan_sub.tahun'		=> get_userdata('year')
			)
		)
		->result_array();
		
		$query_siencang								= $this->model->select
		('
			IFNULL(ref__urusan.kd_urusan, 0) AS kd_urusan,
			IFNULL(ref__bidang.kd_bidang, 0) AS kd_bidang,
			ref__program.kd_program,
			ref__kegiatan.kd_kegiatan,
			ref__kegiatan_sub.kd_kegiatan_sub,
			ref__kegiatan_sub.nm_kegiatan_sub,
			"siencang" AS source
		')
		->join
		(
			'ref__kegiatan',
			'ref__kegiatan.id = ref__kegiatan_sub.id_kegiatan'
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
		->order_by('ref__urusan.kd_urusan, ref__bidang.kd_bidang, ref__program.kd_program, ref__kegiatan.kd_kegiatan, ref__kegiatan_sub.kd_kegiatan_sub')
		->get_where
		(
			'ref__kegiatan_sub',
			array
			(
				'ref__kegiatan_sub.tahun'			=> get_userdata('year')
			)
		)
		->result_array();
		
		$results									= array_merge($query_sipd, $query_siencang);
		$output										= array();
		
		foreach($query_sipd as $key => $val)
		{
			$kode									= $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_program']) . '.' . sprintf('%02d', $val['kd_kegiatan']) . '.' . sprintf('%02d', $val['kd_kegiatan_sub']);
			
			$output[$kode]							= $val;
			
			$this->model->insert
			(
				$this->_table,
				array
				(
					'kode_sipd'						=> $kode,
					'label_sipd'					=> $val['nm_kegiatan_sub']
				)
			);
		}
		
		foreach($query_siencang as $key => $val)
		{
			$kode									= $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_program']) . '.' . sprintf('%02d', $val['kd_kegiatan']) . '.' . sprintf('%02d', $val['kd_kegiatan_sub']);
			
			if(isset($output[$kode]))
			{
				$this->model->update
				(
					$this->_table,
					array
					(
						'kode_siencang'				=> $kode,
						'label_siencang'			=> $val['nm_kegiatan_sub']
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
						'label_siencang'			=> $val['nm_kegiatan_sub']
					)
				);
			}
		}
	}
}
