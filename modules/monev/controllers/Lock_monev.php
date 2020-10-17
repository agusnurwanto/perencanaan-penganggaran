<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Lock_monev extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend');
		$this->set_permission();
		$this->permission->must_ajax();
		$this->_primary							= $this->input->get('id_keg');
		$this->set_method('update');
		if(!in_array(get_userdata('group_id'), array(1)))
		{
			generateMessages(403, 'Anda tidak diizinkan untuk mengakses halaman yang diminta...', 'dashboard');
		}
		
		if($this->input->post('token') && $this->input->post('token') == sha1(current_page()))
		{
			return $this->_submit();
		}
	}
	
	public function index()
	{
		if($this->input->get('id_sub') && $this->input->get('global'))
		{
			$lock								= $this->model->select
			('
				SUM(ta__monev_locked.triwulan_1) AS triwulan_1,
				SUM(ta__monev_locked.triwulan_2) AS triwulan_2,
				SUM(ta__monev_locked.triwulan_3) AS triwulan_3,
				SUM(ta__monev_locked.triwulan_4) AS triwulan_4
			')
			->join
			(
				'ta__kegiatan',
				'ta__kegiatan.id = ta__monev_locked.id_keg'
			)
			->join
			(
				'ta__program',
				'ta__program.id = ta__kegiatan.id_prog'
			)
			->get_where
			(
				'ta__monev_locked',
				array
				(
					'ta__program.id_sub'		=> $this->input->get('id_sub')
				),
				1
			)
			->row();
		}
		else
		{
			$lock								= $this->model->get_where('ta__monev_locked', array('id_keg' => $this->input->get('id_keg')), 1)->row();
		}
		
		$html									= '
			<div class="container-fluid">
				<div class="row">
					<div class="col-md-4 col-md-offset-4">
						<div class="box no-border">
							<div class="box-header with-border">
								<div class="box-tools pull-right">
									' . (!$this->input->post('modal') ? '
									<a href="' . current_page() . '" class="btn btn-box-tool ajaxLoad show_process">
										<i class="fa fa-refresh"></i>
									</a>
									' : null) . '
									<button type="button" class="btn btn-box-tool" data-widget="collapse">
										<i class="fa fa-minus"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-widget="maximize">
										<i class="fa fa-expand"></i>
									</button>
									<button type="button" class="btn btn-box-tool" data-dismiss="modal">
										<i class="fa fa-times"></i>
									</button>
								</div>
								<h3 class="box-title">
									<i class="fa fa-lock-alt"></i>
									&nbsp;
									Lock eMonev
								</h3>
							</div>
							<div class="box-body">
								<div class="row">
									<div class="col-md-10 col-md-offset-1">
										<form action="' . current_page() . '" method="POST" class="submitForm" data-save="Lock eMonev" data-saving="Mengunci..." data-alert="Tidak dapat mengunci kegiatan yang dipilih. Silakan mencoba lagi..." data-icon="lock">
											<div class="form-group">
												' . ($this->input->get('id_sub') && $this->input->get('global') ? '<label class="control-label big-label text-danger">Penguncian ini akan berlaku untuk semua kegiatan. Untuk mengunci per kegiatan, gunakan tombol yang tersedia di tiap kegiatan!</label><hr />' : null) . '
												<label class="control-label big-label">
													Silakan centang pada triwulan yang akan dikunci.
												</label>
											</div>
											<hr />
											<div class="form-group check-all-group">
												<label class="control-label big-label">
													<input type="checkbox" role="check-all" checker-parent=".check-all-group" />
													&nbsp;
													Semua Triwulan
												</label>
												<label class="control-label big-label"' . ($this->input->get('id_sub') && $this->input->get('global') && isset($lock->triwulan_1) && 1 == $lock->triwulan_1 > 0 ? ' data-toggle="tooltip" title="Beberapa kegiatan telah dikunci"' : null) . '>
													<input type="checkbox" name="triwulan_1" class="check-all-children" value="1"' . (isset($lock->triwulan_1) && 1 == $lock->triwulan_1 > 0 ? ' checked' : null) . ' />
													&nbsp;
													Triwulan I
												</label>
												<label class="control-label big-label"' . ($this->input->get('id_sub') && $this->input->get('global') && isset($lock->triwulan_2) && 1 == $lock->triwulan_2 > 0 ? ' data-toggle="tooltip" title="Beberapa kegiatan telah dikunci"' : null) . '>
													<input type="checkbox" name="triwulan_2" class="check-all-children" value="1"' . (isset($lock->triwulan_2) && 1 == $lock->triwulan_2 > 0 ? ' checked' : null) . ' />
													&nbsp;
													Triwulan II
												</label>
												<label class="control-label big-label"' . ($this->input->get('id_sub') && $this->input->get('global') && isset($lock->triwulan_3) && 1 == $lock->triwulan_3 > 0 ? ' data-toggle="tooltip" title="Beberapa kegiatan telah dikunci"' : null) . '>
													<input type="checkbox" name="triwulan_3" class="check-all-children" value="1"' . (isset($lock->triwulan_3) && 1 == $lock->triwulan_3 > 0 ? ' checked' : null) . ' />
													&nbsp;
													Triwulan III
												</label>
												<label class="control-label big-label"' . ($this->input->get('id_sub') && $this->input->get('global') && isset($lock->triwulan_4) && 1 == $lock->triwulan_4 > 0 ? ' data-toggle="tooltip" title="Beberapa kegiatan telah dikunci"' : null) . '>
													<input type="checkbox" name="triwulan_4" class="check-all-children" value="1"' . (isset($lock->triwulan_4) && 1 == $lock->triwulan_4 > 0 ? ' checked' : null) . ' />
													&nbsp;
													Triwulan IV
												</label>
											</div>
											<div class="form-group">
												<input type="hidden" name="token" value="' . sha1(current_page()) . '" />
												<button type="submit" class="btn btn-primary btn-holo">
													<i class="fa fa-lock"></i>
													Lock eMonev
												</button>
											</div>
										</form>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		';
		return make_json
		(
			array
			(
				'status'						=> 201,
				'html'							=> $html
			)
		);
	}
	
	private function _submit()
	{
		if($this->input->get('id_sub') && $this->input->get('global'))
		{
			$query								= $this->model->select
			('
				ta__kegiatan.id
			')
			->join
			(
				'ta__program',
				'ta__program.id = ta__kegiatan.id_prog'
			)
			->get_where
			(
				'ta__kegiatan',
				array
				(
					'ta__program.id_sub'		=> $this->input->get('id_sub')
				)
			)
			->result();
			
			if($query)
			{
				foreach($query as $key => $val)
				{
					if($this->model->get_where('ta__monev_locked', array('id_keg' => $val->id), 1)->row())
					{
						$this->model->update
						(
							'ta__monev_locked',
							array
							(
								'triwulan_1'	=> (1 == $this->input->post('triwulan_1') ? 1 : 0),
								'triwulan_2'	=> (1 == $this->input->post('triwulan_2') ? 1 : 0),
								'triwulan_3'	=> (1 == $this->input->post('triwulan_3') ? 1 : 0),
								'triwulan_4'	=> (1 == $this->input->post('triwulan_4') ? 1 : 0)
							),
							array
							(
								'id_keg'		=> $val->id
							),
							1
						);
					}
					else
					{
						$this->model->insert
						(
							'ta__monev_locked',
							array
							(
								'id_keg'		=> $val->id,
								'triwulan_1'	=> (1 == $this->input->post('triwulan_1') ? 1 : 0),
								'triwulan_2'	=> (1 == $this->input->post('triwulan_2') ? 1 : 0),
								'triwulan_3'	=> (1 == $this->input->post('triwulan_3') ? 1 : 0),
								'triwulan_4'	=> (1 == $this->input->post('triwulan_4') ? 1 : 0)
							)
						);
					}
				}
			}
		}
		else
		{
			if($this->model->get_where('ta__monev_locked', array('id_keg' => $this->input->get('id_keg')), 1)->row())
			{
				$this->model->update
				(
					'ta__monev_locked',
					array
					(
						'triwulan_1'			=> (1 == $this->input->post('triwulan_1') ? 1 : 0),
						'triwulan_2'			=> (1 == $this->input->post('triwulan_2') ? 1 : 0),
						'triwulan_3'			=> (1 == $this->input->post('triwulan_3') ? 1 : 0),
						'triwulan_4'			=> (1 == $this->input->post('triwulan_4') ? 1 : 0)
					),
					array
					(
						'id_keg'				=> $this->input->get('id_keg')
					),
					1
				);
			}
			else
			{
				$this->model->insert
				(
					'ta__monev_locked',
					array
					(
						'id_keg'				=> $this->input->get('id_keg'),
						'triwulan_1'			=> (1 == $this->input->post('triwulan_1') ? 1 : 0),
						'triwulan_2'			=> (1 == $this->input->post('triwulan_2') ? 1 : 0),
						'triwulan_3'			=> (1 == $this->input->post('triwulan_3') ? 1 : 0),
						'triwulan_4'			=> (1 == $this->input->post('triwulan_4') ? 1 : 0)
					)
				);
			}
		}
		return generateMessages(301, 'Sukses');
	}
}