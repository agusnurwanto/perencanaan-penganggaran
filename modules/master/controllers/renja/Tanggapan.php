<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Tanggapan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		if(!$this->input->get('per_page') && is_numeric($this->input->get('page')) && $this->input->get('page') > 0)
		{
			$_GET['per_page']						= $this->input->get('page');
		}
		$this->_limit								= 25;
		$this->_offset								= (is_numeric($this->input->get('per_page')) ? $this->input->get('per_page') - 1 : 0) * $this->_limit;
		$this->_total								= 0;
		$this->_id_unit								= get_userdata('sub_unit');
		
		$this->set_theme('backend');
		$this->set_upload_path('verifikasi_standar_harga');
		$this->set_permission();
		
		if(!$this->permission->allow('master', 'renja', 'tanggapan', $this->_method))
		{
			generateMessages(403, phrase('you_do_not_have_sufficient_privileges_to_access_the_requested_page'), 'dashboard');
		}
	}
	
	public function index()
	{
		if('notification' == $this->input->post('method') || 'notification' == $this->input->get('method'))
		{
			return $this->_notification();
		}
		elseif('modal' == $this->input->post('prefer'))
		{
			return $this->_notification();
		}
		elseif('filter' == $this->input->post('method'))
		{
			return $this->_notification($this->input->post('sub_unit'));
		}
		elseif($this->input->post('asistensi'))
		{
			$this->load->library('form_validation');
			$this->load->helper('security');
			
			$this->form_validation->set_rules('asistensi', 'ID Asistensi', 'required|numeric');
			$this->form_validation->set_rules('tanggapan', 'Tanggapan', 'required|xss_clean');
			if($this->form_validation->run() === false)
			{
				return throw_exception(400, array(validation_errors('<p><i class="fa fa-ban"></i> &nbsp; ', '</p>')));
			}
			else
			{
				if($this->model->update('ta__asistensi', array('tanggapan' => $this->input->post('tanggapan'), 'tanggal_tanggapan' => date('Y-m-d H:i:s')), array('id' => $this->input->post('asistensi'))))
				{
					return make_json
					(
						array
						(
							'status'				=> 200,
							'timestamp'				=> date_indo(date('Y-m-d H:i:s'), 3, '-'),
							'message'				=> 'Sukses menanggapi komentar'
						)
					);
				}
				else
				{
					return make_json
					(
						array
						(
							'status'				=> 500,
							'timestamp'				=> date_indo(date('Y-m-d H:i:s'), 3, '-'),
							'message'				=> 'Gagal menanggapi komentar'
						)
					);
				}
			}
		}
		$this->set_title('Tanggapan Asistensi')
		->unset_column('id, id_keg_sub, jenis, id_jenis, jenis_indikator, id_operator, operator, dibaca, komentar_dibaca, tanggapan_dibaca, tanggal_tanggapan')
		->unset_view('id, id_keg_sub, jenis, id_jenis, jenis_indikator, id_operator, operator, dibaca')
		->unset_action('create, update, delete')
		->set_icon('fa fa-comments-o')
		->render('ta__asistensi');
	}
	
	private function _notification($sub_unit = 0)
	{
		if($this->input->post('action') && $this->input->post('item'))
		{
			$query									= $this->model->update('ta__asistensi', array('approve' => ('accept' == $this->input->post('action') ? 1 : 0), 'dilihat' => 1, 'alasan' => $this->input->post('reason')), array('id' => $this->input->post('item')), 1);
			if($query)
			{
				return make_json
				(
					array
					(
						'status'					=> 200
					)
				);
			}
			else
			{
				return make_json
				(
					array
					(
						'status'					=> 500
					)
				);
			}
		}
		
		$this->model
		->join
		(
			'ta__kegiatan_sub',
			'ta__kegiatan_sub.id = ta__asistensi.id_keg_sub'
		)
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id = ta__kegiatan_sub.id_keg'
		)
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->join
		(
			'ref__program',
			'ref__program.id = ta__program.id_prog'
		);
		
		if($sub_unit > 0)
		{
			$this->model->where('ta__program.id_sub', $sub_unit);
		}
		if(in_array($this->session->userdata('group_id'), array(5)) && $this->_id_unit)
		{
			$this->model->join('ref__sub', 'ref__sub.id = ta__program.id_sub')
			->where('ref__sub.id_unit', $this->_id_unit);
		}
		if($this->input->get('id_keg'))
		{
			$this->model->where('ta__asistensi.id_keg_sub', $this->input->get('id_keg'));
		}
		if($this->input->post('counting'))
		{
			$this->model->where('ta__asistensi.tanggapan', '');
		}

		$query										= $this->model
		->select('count(ta__asistensi.id) as total')
		->group_by('id_keg_sub')
		->where('ta__asistensi.comments !=', '')
		->count_all_results('ta__asistensi');
		
		$this->_total								= $query;
		
		if(!$this->input->post('counting'))
		{
			$this->model
			->select
			('
				ta__kegiatan_sub.id,
				ta__kegiatan_sub.kegiatan_sub,
				ta__kegiatan_sub.files,
				ta__kegiatan_sub.kd_keg_sub,
				ta__program.kd_id_prog,
				ta__program.id_sub,
				ref__program.kd_program,
				ref__unit.nm_unit
			')
			->join
			(
				'ta__kegiatan_sub',
				'ta__kegiatan_sub.id = ta__asistensi.id_keg_sub'
			)
			->join
			(
				'ta__kegiatan',
				'ta__kegiatan.id = ta__kegiatan_sub.id_keg'
			)
			->join
			(
				'ta__program',
				'ta__program.id = ta__kegiatan.id_prog'
			)
			->join
			(
				'ref__program',
				'ref__program.id = ta__program.id_prog'
			)
			->join
			(
				'ref__sub',
				'ref__sub.id = ta__program.id_sub'
			)
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
				'ref__urusan',
				'ref__urusan.id = ref__bidang.id_urusan'
			);
			if($sub_unit > 0)
			{
				$this->model->where('ta__program.id_sub', $sub_unit);
			}
			if(in_array($this->session->userdata('group_id'), array(5)) && $this->_id_unit)
			{
				$this->model->where('ref__sub.id_unit', $this->_id_unit);
			}
			if($this->input->get('id_keg'))
			{
				$this->model->where('ta__asistensi.id_keg_sub', $this->input->get('id_keg'));
			}
			if($this->input->post('counting'))
			{
				$this->model->where('ta__asistensi.tanggapan', '');
			}
			
			$query										= $this->model
			->select('ta__kegiatan_sub.id, ta__kegiatan_sub.id_keg, ta__kegiatan_sub.kegiatan_sub, ta__kegiatan_sub.kd_keg_sub, kd_id_prog, id_sub, kd_program, nm_unit')
			->limit($this->_limit, $this->_offset)
			//->join('ta__kegiatan_sub', 'ta__kegiatan_sub.id = ta__asistensi.id_keg_sub')
			->order_by('kd_urusan, kd_bidang, kd_unit, kd_sub, kd_program, kd_id_prog, kd_keg')
			->group_by('id_keg_sub')
			->get_where
			(
				'ta__asistensi',
				array
				(
					'ta__asistensi.comments !='			=> ''
				)
			)
			->result();
			//print_r($query);exit;
			
			$output										= null;
			if($query)
			{
				foreach($query as $key => $val)
				{
					$counts								= $this->model
					->select
					('
						count(ta__asistensi.tanggapan) as tanggapan
					')
					->join
					(
						'ta__kegiatan_sub',
						'ta__kegiatan_sub.id = ta__asistensi.id_keg_sub'
					)
					->join
					(
						'ta__kegiatan',
						'ta__kegiatan.id = ta__kegiatan_sub.id_keg'
					)
					->join
					(
						'ta__program',
						'ta__program.id = ta__kegiatan.id_prog'
					)
					->get_where
					(
						'ta__asistensi',
						array
						(
							'ta__program.id_sub'		=> $val->id_sub,
							'ta__asistensi.id_keg_sub'	=> $val->id,
							'ta__asistensi.tanggapan'	=> ''
						)
					)
					->row('tanggapan');
					$output								.= 
					'
						<div class="panel panel-default">
							<div class="panel-heading" role="tab" id="heading_' . $key . '">
								<div class="btn-group-vertical float-right">
									<a href="' . go_to('../../../laporan/anggaran/rka/rka_sub_kegiatan', array('sub_unit' => $val->id_sub, 'kegiatan' => $val->id_keg, 'sub_kegiatan' => $val->id, 'method' => 'embed')) . '" class="btn btn-success btn-xs" target="_blank" data-toggle="tooltip" title="RKA Sub Kegiatan">
										<i class="mdi mdi-printer"></i>
										RKA
									</a>
									<a href="' . go_to('../../../laporan/anggaran/asistensi/asistensi_kegiatan', array('sub_unit' => $val->id_sub, 'sub_kegiatan' => $val->id, 'method' => 'embed')) . '" class="btn btn-warning btn-xs" target="_blank" data-toggle="tooltip" title="Cetak Lembar Asistensi">
										<i class="mdi mdi-printer"></i>
										Asistensi
									</a>
								</div>
								<a href="#accordion_' . $val->id . '" data-toggle="collapse" data-parent="#accordion-list">
									<b>
										' . $val->kd_program . '.' . $val->kd_keg_sub . '. ' . $val->kegiatan_sub . '
									</b>
									<p class="text-sm text-muted">
										' . $val->nm_unit . ($counts ? ' <span class="badge badge-danger">' . $counts . ' belum ditanggapi</span>' : null) . '
									</p>
								</a>
							</div>
							<div class="panel-collapse collapse' . ($this->input->get('id_keg') == $val->id ? ' in' : null) . '" id="accordion_' . $val->id . '" role="tabpanel" aria-labelledby="heading_' . $val->id . '">
								<div class="panel-body">
									' . $this->_get_thread($val->id_sub, $val->id) . '
								</div>
							</div>
						</div>
					';
				}
			}
			
			if($this->input->post('pagination'))
			{
				$output									=
				'
					<div>
						' . (!$this->input->get('id_keg') ? '
						<div class="alert alert-info row rounded-0 border-0">
							<i class="mdi mdi-information-outline"></i>
							Terdapat ' . number_format($this->_total) . ' kegiatan yang telah direspon.
						</div>
						' : null) . '
						' . (in_array(get_userdata('group_id'), array(1, 12)) ? $this->_filter() : null) . '
						' . ($output ? '
						<div class="panel-group" id="accordion-list" role="tablist" aria-multiselectable="true">
							' . $output . '
						</div>
						' : '
							<p class="text-center text-muted">
								Belum tersedia respon atau tanggapan untuk kegiatan yang dipilih...
							</p>
						') . '
						' . (!$this->input->get('id_keg') ? $this->_pagination($sub_unit) : null). '
					</div>
				';
			}
			else
			{
				$output									= 
				'
					<div class="container-fluid">
						<div class="box-body">
							' . (!$this->input->get('id_keg') ? '
							<div class="row alert alert-info rounded-0 border-0">
								<i class="mdi mdi-information-outline"></i>
								Terdapat ' . number_format($this->_total) . ' kegiatan yang telah direspon.
							</div>
							' . $this->_filter() . '
							' : null) . '
							' . ($output ? '
							<div class="panel-group" id="accordion-list" role="tablist" aria-multiselectable="true">
								' . $output . '
							</div>
							' : '
								<p class="text-center text-muted">
									Belum tersedia respon atau tanggapan untuk kegiatan yang dipilih...
								</p>
							') . '
							' . (!$this->input->get('id_keg') ? $this->_pagination($sub_unit) : null) . '
						</div>
					</div>
				';
			}
		}
		
		make_json
		(
			array
			(
				'meta'								=> array
				(
					'title'							=> 'Asistensi',
					'icon'							=> 'mdi mdi-comment-multiple-outline'
				),
				'count'								=> number_format($this->_total),
				'html'								=> (!$this->input->post('counting') ? $output : null)
			)
		);
	}
	
	private function _get_thread($id_sub = 0, $id_keg = 0)
	{
		$query										= $this->model
		->select
		('
			ta__asistensi.*,
			app__users.username,
			app__users.first_name,
			app__users.last_name
		')
		->join
		(
			'ta__kegiatan_sub',
			'ta__kegiatan_sub.id = ta__asistensi.id_keg_sub'
		)
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id = ta__kegiatan_sub.id_keg'
		)
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->join
		(
			'app__users',
			'app__users.user_id = ta__asistensi.id_operator'
		)
		//->group_by('jenis, id_jenis, jenis_indikator') ? group untuk apa ya?
		->order_by('ta__asistensi.tanggal', 'DESC')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__program.id_sub'				=> $id_sub,
				'ta__asistensi.id_keg_sub'			=> $id_keg,
				'ta__asistensi.comments !='			=> ''
			)
		)
		->result();
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= '
					<div class="form-group text-sm">
						<a href="javascript:void(0)" class="">
							<i class="text-sm text-muted float-right">
								' . $val->tanggal . '
							</i>
							<b class="text-success">' . $val->first_name . ' ' . $val->last_name . '</b> menanggapi <b class="text-info">' . $val->uraian . '</b>
						</a>
						<br />
						' . $val->comments . '
						<form action="' . current_page() . '" method="POST" class="notification-form">
							<input type="hidden" name="asistensi" value="' . $val->id . '" />
							<div class="input-group input-group-sm">
								<input type="text" name="tanggapan" value="' . $val->tanggapan . '" class="form-control input-sm bordered' . (!$val->tanggapan ? ' bg-warning' : null) . '" placeholder="Ketik tanggapan" autocomplete="off" />
								<div class="input-group-append already-responded">
									<span class="input-group-text status-tanggapan ' . (!$val->tanggapan ? ' d-none' : null) . '">
										<i class="mdi mdi-check text-success"></i>
									</span>
									<span class="input-group-text timestamp-tanggapan ' . (!$val->tanggapan ? ' d-none' : null) . '">
										' . ('0000-00-00 00:00:00' != $val->tanggal_tanggapan ? date_indo($val->tanggal_tanggapan, 3, '-') : null) . '
									</span>
									<button type="submit" class="btn btn-sm btn-primary">
										<i class="mdi mdi-send"></i>
									</button>
								</div>
							</div>
						</form>
					</div>
				';
			}
		}
		return $output;
	}
	
	private function _filter()
	{
		if(in_array($this->session->userdata('group_id'), array(5)) && $this->_id_unit)
		{
			$this->model->where('ref__unit.id', $this->_id_unit);
		}
		$query										= $this->model
		->select
		('
			ref__sub.*,
			ref__unit.kd_unit,
			ref__bidang.kd_bidang,
			ref__urusan.kd_urusan
		')
		->join('ref__unit', 'ref__unit.id = ref__sub.id_unit')
		->join('ref__bidang', 'ref__bidang.id = ref__unit.id_bidang')
		->join('ref__urusan', 'ref__urusan.id = ref__bidang.id_urusan')
		->order_by('kd_urusan, kd_bidang, kd_unit, kd_sub')
		->get_where
		(
			'ref__sub',
			array
			(
				'ref__sub.tahun'					=> get_userdata('year')
			)
		)
		->result();
		$options									= '<option value="">Semua Sub Unit</option>';
		if($query)
		{
			foreach($query as $key => $val)
			{
				$options							.= '<option value="' . $val->id . '"' . ($val->id == $this->input->post('sub_unit') ? ' selected' : null) . '>' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '.' . $val->kd_sub . '. ' . $val->nm_sub . '</option>';
			}
		}
		return '
			<div class="form-group">
				<select name="filter" class="form-control input-sm bordered filter-tanggapan" placeholder="Filter berdasar sub unit" data-href="' . current_page(null, array('per_page' => null)) . '" style="width:100%">
					' . $options . '
				</select>
			</div>
		';
	}
	
	private function _pagination($sub_unit = 0)
	{
		$form										= '
			<form action="' . current_page() . '" class="form-inline ajaxForm in-modal"  method="GET">
				<div class="input-group">
					<input type="number" name="per_page" min="0" class="form-control input-sm bordered" value="' . (is_numeric($this->input->get('per_page')) ? $this->input->get('per_page') : 1) . '" style="width:60px;border-left:0" />
					<span class="input-group-btn">
						<button type="submit" class="btn btn-default btn-sm">
							' . phrase('go') . '
						</button>
					</span>
				</div>
			</form>
		';
		$configs									= array
		(
			'base_url'								=> current_page(null, array('per_page' => null, 'sub_unit' => $sub_unit)),
			'total_rows'	 						=> $this->_total,
			'per_page'		 						=> $this->_limit,
			'use_page_numbers'		 				=> true,
			'num_links'								=> 0,
			'page_query_string'						=> true,
			'full_tag_open'		 					=> '<ul class="pagination pagination-sm">',
			'full_tag_close'	 					=> '<li class="page-item">' . $form . '</li></ul>',
			'num_tag_open'							=> '<li class="page-item">',
			'num_tag_close'							=> '</li>',
			'cur_tag_open'							=> '<li class="page-item active"><a href="javascript:void(0)" class="page-link disabled">',
			'cur_tag_close'		 					=> '<span class="sr-only"></span></a></li>',
			'next_tag_open'		 					=> '<li>',
			'next_tagl_close'						=> '</li>',
			'prev_tag_open'							=> '<li>',
			'prev_tagl_close'	 					=> '</li>',
			'first_tag_open'	 					=> '<li>',
			'first_tagl_close'						=> '</li>',
			'last_tag_open'							=> '<li>',
			'last_tagl_close'						=> '</li>',
			'last_link'								=> '&gt;&gt;',
			'first_link'							=> '&lt;&lt;'
		);
		
		$this->load->library('pagination');
		$this->pagination->initialize($configs);
		
		$results									= $this->pagination->create_links();
		if($results)
		{
			$output									= $results;
		}
		else
		{
			$output									= '
				<ul class="pagination pagination-sm">
					<li class="page-item disabled">
						<a href="javascript:void(0)" class="page-link" tabindex="0">
							&lt;
						</a>
					</li>
					<li class="page-item active">
						<a href="javascript:void(0)" class="page-link" tabindex="0">
							1
						</a>
					</li>
					<li class="page-item disabled">
						<a href="javascript:void(0)" class="page-link" tabindex="0">
							&gt;
						</a>
					</li>
				</ul>
			';
		}
		
		$output										= '
			<div class="pt-3">
				<div class="row">
					<div class="col-sm-8">
						<label class="text-muted text-sm d-block">
							<i class="mdi mdi-information-outline"></i>
							&nbsp;
							' . phrase('showing') . ' ' . ($this->_offset ? $this->_offset : ($this->_total > 0 ? 1 : 0)) . ' - ' . (($this->_offset + $this->_limit) < $this->_total ? ($this->_offset + $this->_limit) : $this->_total) . ' ' . phrase('from') . ' ' . $this->_total . ' ' . phrase('entries_found') . '
						</label>
						<nav>
							' . $output . '
						</nav>
					</div>
					<div class="col-sm-4">
						<label class="text-muted text-sm d-block">
							&nbsp;
						</label>
						<button type="button" class="btn btn-outline-primary btn-sm float-right" data-dismiss="modal">
							<i class="mdi mdi-window-close"></i>
							' . phrase('close') . '
						</button>
					</div>
				</div>
			</div>
		';
		
		return $output;
	}
}