<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Reset_approval extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('id_keg');
		$this->set_theme('backend');
		$this->set_permission();
		$this->permission->must_ajax();
	}
	
	public function index()
	{
		if(1 == $this->input->get('continue'))
		{
			return $this->_reset();
		}
		else
		{
			return make_json
			(
				array
				(
					'status'						=> 200,
					'modal'							=> true,
					'html'							=> '
						<div class="col-sm-4 col-sm-offset-4">
							<div class="alert alert-warning text-center">
								<h3>
									Konfirmasi
								</h3>
								<p>
									Anda yakin akan menghapus paraf asistensi dan tanda tangan TAPD pada kegiatan ini?
								</p>
								<br />
								<div class="btn-group flex-center">
									<a href="' . current_page(null, array('continue' => 1)) . '" class="btn btn-warning btn-holo btn-sm inout">
										<i class="fa fa-check"></i>
										Lanjutkan
									</a>
									<a href="javascript:void(0)" class="btn btn-warning btn-holo btn-sm" data-dismiss="modal">
										<i class="fa fa-times"></i>
										Batal
									</a>
								</div>
							</div>
						</div>
					'
				)
			);
		}
	}
	
	private function _reset()
	{
		/**
		 * Do update
		 */
		$query										= $this->model->update
		(
			'ta__asistensi_setuju',
			array
			(
				'perencanaan'						=> 0,
				'keuangan'							=> 0,
				'setda'								=> 0,
				'ttd_1'								=> '',
				'ttd_2'								=> '',
				'ttd_3'								=> ''
			),
			array
			(
				'id_keg'							=> $this->_primary
			)
		);
		
		if($query)
		{
			return generateMessages(200, 'Paraf asistensi dan tanda tangan TAPD pada kegiatan yang dipilih berhasil dihapus...', current_page('../'));
		}
		else
		{
			return generateMessages(404, 'Tidak dapat menghapus paraf asistensi dan tanda tangan TAPD. Silakan coba lagi...', current_page('../'));
		}
	}
}