<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Asistensi_ready extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend');
		$this->set_permission();
		$this->permission->must_ajax();
		
		$this->set_method('index');
		
		$this->_primary						= $this->input->get('id');
	}
	
	public function index()
	{
		$checker							= $this->model->select('asistensi_ready')->get_where('ta__kegiatan', array('id' => $this->_primary), 1)->row('asistensi_ready');
		return make_json
		(
			array
			(
				'status'					=> 200,
				'modal'						=> true,
				'meta'						=> array
				(
					'icon'					=> 'mdi mdi-check',
					'title'					=> 'Ubah Status Asistensi'
				),
				'html'						=> '
					<div class="alert alert-warning text-center mb-0">
						<h3>
							Konfirmasi
						</h3>
						<p>
							' . ($checker ? 'Anda yakin akan membatalkan kegiatan untuk diasistensi?' : 'Anda yakin akan menandai kegiatan ini untuk diasistensi?') . '
						</p>
						<br />
						<div class="btn-group flex-center">
							<a href="' . current_page('update') . '" class="btn btn-warning btn-holo btn-sm inout">
								<i class="mdi mdi-check"></i>
								Lanjutkan
							</a>
							<a href="javascript:void(0)" class="btn btn-warning btn-holo btn-sm" data-dismiss="modal">
								<i class="mdi mdi-window-close"></i>
								Batal
							</a>
						</div>
					</div>
				'
			)
		);
	}
	
	public function update()
	{
		$query								= $this->model->select('asistensi_ready')->get_where('ta__kegiatan', array('id' => $this->_primary), 1)->row('asistensi_ready');
		if(1 == $query)
		{
			if($this->model->update('ta__kegiatan', array('asistensi_ready' => 0), array('id' => $this->_primary), 1))
			{
				$icon						= 'mdi-check';
				$label						= 'Klik untuk diasistensi';
				$status						= 'on';
			}
		}
		else
		{
			if($this->model->update('ta__kegiatan', array('asistensi_ready' => 1), array('id' => $this->_primary), 1))
			{
				$icon						= 'mdi-window-close';
				$label						= 'Klik untuk batal diasistensi';
				$status						= 'off';
			}
		}
		
		if($status == 'off')
		{
			$operator_id					= get_userdata('user_id');
			$operator_name					= get_userdata('first_name') . ' ' . get_userdata('last_name');
			if($this->model->get_where('ta__asistensi_setuju', array('id_keg' => $this->_primary), 1)->num_rows() > 0)
			{
				$this->model->update('ta__asistensi_setuju', array('id_keg' => $this->_primary, 'tanggal_asistensi_ready' => date('Y-m-d H:i:s'), 'operator_asistensi_ready' => $operator_id, 'nama_operator_asistensi_ready' => $operator_name), array('id_keg' => $this->_primary));
			}
			else
			{
				$this->model->insert('ta__asistensi_setuju', array('id_keg' => $this->_primary, 'tanggal_asistensi_ready' => date('Y-m-d H:i:s'), 'operator_asistensi_ready' => $operator_id, 'nama_operator_asistensi_ready' => $operator_name));
			}
		}
		
		make_json
		(
			array
			(
				'icon'						=> $icon,
				'label'						=> $label,
				'status'					=> $status
			)
		);
	}
}