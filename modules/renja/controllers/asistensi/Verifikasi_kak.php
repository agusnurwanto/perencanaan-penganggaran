<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Verifikasi_kak extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('kegiatan_sub');
		$this->_id_operator							= get_userdata('user_id');
		$this->_nama_operator						= get_userdata('first_name') . ' ' . get_userdata('last_name');
		$this->set_theme('backend');
		$this->set_permission();
		$this->permission->must_ajax();
	}
	
	public function index()
	{
		if('ttd' == $this->input->get('req'))
		{
			$column									= $this->input->get('target');
			$checker								= $this->model->select($column)->get_where('ta__asistensi_kak_setuju', array('id_keg_sub' => $this->_primary), 1)->row($column);
			return make_json
			(
				array
				(
					'status'						=> 200,
					'meta'							=> array
					(
						'title'						=> 'Konfirmasi',
						'icon'						=> 'mdi mdi-check'
					),
					'modal'							=> true,
					'html'							=> '
						<div class="alert alert-warning text-center rounded-0">
							<h3>
								Konfirmasi
							</h3>
							<p>
								' . (1 == $checker ? 'Anda yakin akan membatalkan tanda tangan?' : 'Anda yakin akan melakukan tanda tangan?') . '
							</p>
							<br />
							<div class="btn-group flex-center">
								<a href="' . current_page('update') . '" class="btn btn-warning btn-holo btn-sm inout">
									<i class="fa fa-check"></i>
									Lanjutkan
								</a>
								<a href="javascript:void(0)" class="btn btn-warning btn-holo btn-sm" data-dismiss="modal">
									<i class="fa fa-times"></i>
									Batal
								</a>
							</div>
						</div>
					'
				)
			);
		}
		else
		{
			$column									= $this->input->get('target');
			$checker								= $this->model->select($column)->get_where('ta__asistensi_kak_setuju', array('id_keg_sub' => $this->_primary), 1)->row($column);
			return make_json
			(
				array
				(
					'status'						=> 200,
					'meta'							=> array
					(
						'title'						=> 'Konfirmasi',
						'icon'						=> 'mdi mdi-check'
					),
					'modal'							=> true,
					'html'							=> '
						<div class="alert alert-warning text-center rounded-0">
							<h3>
								Konfirmasi
							</h3>
							<p>
								' . (1 == $checker ? 'Anda yakin akan menolak paraf?' : 'Anda yakin akan menyetujui paraf?') . '
							</p>
							<br />
							<div class="btn-group flex-center">
								<a href="' . current_page('update') . '" class="btn btn-warning btn-holo btn-sm inout">
									<i class="fa fa-check"></i>
									Lanjutkan
								</a>
								<a href="javascript:void(0)" class="btn btn-warning btn-holo btn-sm" data-dismiss="modal">
									<i class="fa fa-times"></i>
									Batal
								</a>
							</div>
						</div>
					'
				)
			);
		}
	}
	
	public function update()
	{
		$column										= $this->input->get('target');
		if(!in_array($column, array('perencanaan', 'keuangan', 'setda', 'ttd_1', 'ttd_2', 'ttd_3')) || $this->model->get_where('ta__kegiatan', array('id' => $this->_primary))->num_rows() <= 0)
		{
			return throw_exception(404, 'Tidak menemukan data yang dapat diverifikasi!');
		}
		$checker									= $this->model->get_where('ta__asistensi_kak_setuju', array('id_keg_sub' => $this->_primary), 1)->row();
		
		if('ttd' == $this->input->get('req'))
		{
			if($checker)
			{
				if(!$this->model->update('ta__asistensi_kak_setuju', array($column => (1 == $checker->$column ? 0 : 1)), array('id_keg_sub' => $this->_primary), 1))
				{
					return throw_exception(500, 'Gagal menandatangani, silakan mencoba kembali...');
				}
			}
			else
			{
				if(!$this->model->insert('ta__asistensi_kak_setuju', array($column => 1)))
				{
					return throw_exception(500, 'Gagal menandatangani, silakan mencoba kembali...');
				}
			}
		}
		else
		{
			if($checker)
			{
				if(!$this->model->update('ta__asistensi_kak_setuju', array($column => (1 == $checker->$column ? 0 : 1), 'waktu_verifikasi_' . $column => date('Y-m-d H:i:s'), 'operator_verifikasi_' . $column => $this->_id_operator, 'nama_operator_' . $column => $this->_nama_operator), array('id_keg_sub' => $this->_primary), 1))
				{
					return throw_exception(500, 'Gagal memverifikasi, silakan mencoba kembali...');
				}
			}
			else
			{
				if(!$this->model->insert('ta__asistensi_kak_setuju', array('id_keg_sub' => $this->_primary, $column => 1, 'waktu_verifikasi_' . $column => date('Y-m-d H:i:s'), 'operator_verifikasi_' . $column => $this->_id_operator, 'nama_operator_' . $column => $this->_nama_operator), 1))
				{
					return throw_exception(500, 'Gagal memverifikasi, silakan mencoba kembali...');
				}
			}
		}
		
		$check_approval								= $this->model->select('perencanaan, keuangan, setda')->get_where('ta__asistensi_kak_setuju', array('id_keg_sub' => $this->_primary), 1)->row();
		if(isset($check_approval->perencanaan) && 1 == $check_approval->perencanaan && isset($check_approval->keuangan) && 1 == $check_approval->keuangan && isset($check_approval->setda) && 1 == $check_approval->setda)
		{
			//$this->model->update('ta__kegiatan', array('lock_kegiatan' => 1), array('id' => $this->_primary), 1);
		}
		else
		{
			//$this->model->update('ta__kegiatan', array('lock_kegiatan' => 0), array('id' => $this->_primary), 1);
		}
		
		if('ttd' == $this->input->get('req'))
		{
			$ttd									= $this->model->select('ttd')->get_where('ref__tim_anggaran', array('id' => str_replace('ttd_', '', $this->input->get('target'))), 1)->row('ttd');
			if($ttd)
			{
				$ttd								= '<img src="' . get_image('anggaran', $ttd) . '" width="80" class="img-responsive" />';
			}
			make_json
			(
				array
				(
					'status'						=> (isset($checker->$column) && 1 == $checker->$column ? 'on' : 'off'),
					'button'						=> (isset($checker->$column) && 1 == $checker->$column ? 'btn-success' : 'btn-danger'),
					'icon'							=> (isset($checker->$column) && 1 == $checker->$column ? 'mdi-check' : 'mdi-window-close'),
					'label'							=> (isset($checker->$column) && 1 == $checker->$column ? 'Tanda tangan' : ''),
					'messages'						=> (isset($checker->$column) && 1 == $checker->$column ? null : $ttd)
				)
			);
		}
		else
		{
			make_json
			(
				array
				(
					'status'						=> (isset($checker->$column) && 1 == $checker->$column ? 'on' : 'off'),
					'button'						=> (isset($checker->$column) && 1 == $checker->$column ? 'btn-success' : 'btn-danger'),
					'icon'							=> (isset($checker->$column) && 1 == $checker->$column ? 'mdi-check' : 'mdi-window-close'),
					'label'							=> (isset($checker->$column) && 1 == $checker->$column ? 'Setuju' : 'Tolak'),
					'messages'						=> (isset($checker->$column) && 1 == $checker->$column ? null : 'Disetujui oleh <b>' . get_userdata('first_name') . ' ' . get_userdata('last_name') . '</b> pada ' . date_indo(date('Y-m-d H:i:s'), 3, '-'))
				)
			);
		}
	}
}