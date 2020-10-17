<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Standar_harga extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_limit								= 25;
		$this->_offset								= (is_numeric($this->input->get('per_page')) ? $this->input->get('per_page') - 1 : 0) * $this->_limit;
		$this->_total								= 0;
			// Grup User SuperAdmin, Admin Perencanaan, Admin Keuangan, Verifikatur SSH
		if(!in_array(get_userdata('group_id'), array(1, 2, 3, 15)))
		{
			return throw_exception(403, phrase('you_are_not_allowed_to_accessing_the_page'), base_url('dashboard'));
		}
		$this->set_theme('backend');
		$this->set_upload_path('verifikasi_standar_harga');
		$this->set_permission();
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
		elseif('accept' == $this->input->post('method'))
		{
			return $this->_accept();
		}
		elseif('decline' == $this->input->post('method'))
		{
			return $this->_decline();
		}
		
		$this->set_title('Master Standar Harga')
		->set_icon('mdi mdi-nfc-tap')
		->unset_column('id, id_rek_6, spesifikasi, id_sub, dilihat, url, deskripsi, alasan, approved_time, approved_by, approved_by_user, operator, tahun')
		->unset_field('approved_time, approved_by, operator, id_rek_6')
		->unset_view('id, dilihat, approved_by_user, tahun')
		->unset_truncate('uraian')
		->unset_action('export, print, pdf')
		->column_order('kd_standar_harga_1, kode, uraian, nilai, satuan_1, satuan_2, satuan_3, deskripsi, alasan, nm_sub, images, flag')
		->field_order('id_standar_harga_7, uraian, nilai, satuan_1, satuan_2, satuan_3, deskripsi, flag, approve, alasan, url')
		->view_order('kd_standar_harga_1, kode, nilai, satuan_1, satuan_2, satuan_3, deskripsi, flag, approve, alasan, url, nm_sub, approved_time')
		->set_alias
			(
				array
				(
					'id_rek_6'						=> 'Rekening',
					'id_sub'						=> 'Sub Unit',
					'nm_sub'						=> 'Sub Unit',
					'images'						=> 'Gambar'
				)
			)
		
		->set_field
		(
			array
			(
				'uraian'							=> 'textarea',
				'deskripsi'							=> 'textarea',
				'nilai'								=> 'price_format',
				'url'								=> 'textarea',
				'images'							=> 'files'
			)
		)
		->set_field('nilai', 'price_format', 4)
		->set_field
		(
			'flag',
			'radio',
			array
			(
				0									=> '<label class="badge badge-primary">SHT</label>',
				1									=> '<label class="badge badge-success">SBM</label>'
			)
		)
		->set_field
		(
			'approve',
			'radio',
			array
			(
				0									=> '<label class="badge badge-warning">Belum Disetujui</label>',
				1									=> '<label class="badge badge-success">Disetujui</label>',
				2									=> '<label class="badge badge-danger">Ditolak</label>'
			)
		)
		->add_class
		(
			array
			(
				'uraian'							=> 'autofocus'
			)
		)
		->merge_content('{satuan_1} {satuan_2} {satuan_3}', 'Satuan')
		//->merge_content('{kode}. {uraian}', 'Uraian')
		->set_field('uraian', 'hyperlink', 'renja/verifikasi_standar_harga/rekening', array('standar_harga' => 'id'))
		//->merge_content('{kd_rek_1}.{kd_rek_2}.{kd_rek_3}.{kd_rek_4}.{kd_rek_5}.{kd_rek_6} {nm_rek_6}', 'Rekening')
		->merge_content('{kd_standar_harga_1}.{kd_standar_harga_2}.{kd_standar_harga_3}.{kd_standar_harga_4}.{kd_standar_harga_5}.{kd_standar_harga_6}.{kd_standar_harga_7} {nm_standar_harga_7}', 'BMD')
		/*->set_relation
		(
			'id_rek_6',
			'ref__rek_6.id',
			'{ref__rek_1.kd_rek_1}.{ref__rek_2.kd_rek_2}.{ref__rek_3.kd_rek_3}.{ref__rek_4.kd_rek_4}.{ref__rek_5.kd_rek_5}.{ref__rek_6.kd_rek_6} {ref__rek_6.uraian}',
			NULL,
			array
			(
				array
				(
					'ref__rek_5',
					'ref__rek_5.id = ref__rek_6.id_ref_rek_5'
				),
				array
				(
					'ref__rek_4',
					'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
				),
				array
				(
					'ref__rek_3',
					'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
				),
				array
				(
					'ref__rek_2',
					'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
				),
				array
				(
					'ref__rek_1',
					'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
				)
			),
			array
			(
				'ref__rek_1.kd_rek_1'			=> 'ASC',
				'ref__rek_2.kd_rek_2'			=> 'ASC',
				'ref__rek_3.kd_rek_3'			=> 'ASC',
				'ref__rek_4.kd_rek_4'			=> 'ASC',
				'ref__rek_5.kd_rek_5'			=> 'ASC'
			)
		)*/
		->set_relation
		(
			'id_standar_harga_7',
			'ref__standar_harga_7.id',
			'{ref__standar_harga_1.kd_standar_harga_1}.{ref__standar_harga_2.kd_standar_harga_2}.{ref__standar_harga_3.kd_standar_harga_3}.{ref__standar_harga_4.kd_standar_harga_4}.{ref__standar_harga_5.kd_standar_harga_5}.{ref__standar_harga_6.kd_standar_harga_6}.{ref__standar_harga_7.kd_standar_harga_7} {ref__standar_harga_7.uraian AS nm_standar_harga_7}',
			array
			(
				'ref__standar_harga_7.tahun'		=> get_userdata('year')
			),
			array
			(
				array
				(
					'ref__standar_harga_6',
					'ref__standar_harga_6.id = ref__standar_harga_7.id_standar_harga_6'
				),
				array
				(
					'ref__standar_harga_5',
					'ref__standar_harga_5.id = ref__standar_harga_6.id_standar_harga_5'
				),
				array
				(
					'ref__standar_harga_4',
					'ref__standar_harga_4.id = ref__standar_harga_5.id_standar_harga_4'
				),
				array
				(
					'ref__standar_harga_3',
					'ref__standar_harga_3.id = ref__standar_harga_4.id_standar_harga_3'
				),
				array
				(
					'ref__standar_harga_2',
					'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2'
				),
				array
				(
					'ref__standar_harga_1',
					'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1'
				)
			),
			array
			(
				'ref__standar_harga_1.kd_standar_harga_1'	=> 'ASC',
				'ref__standar_harga_2.kd_standar_harga_2'	=> 'ASC',
				'ref__standar_harga_3.kd_standar_harga_3'	=> 'ASC',
				'ref__standar_harga_4.kd_standar_harga_4'	=> 'ASC',
				'ref__standar_harga_5.kd_standar_harga_5'	=> 'ASC',
				'ref__standar_harga_6.kd_standar_harga_6'	=> 'ASC',
				'ref__standar_harga_7.kd_standar_harga_7'	=> 'ASC'
			),
			null,
			50
		)
		->set_relation
		(
			'id_sub',
			'ref__sub.id',
			'{ref__sub.nm_sub}'
		)
		->set_validation
		(
			array
			(
				'id_rek_6'							=> 'required',
				'uraian'							=> 'required|trim',
				'nilai'								=> 'required|trim',
				'satuan_1'							=> 'required|trim'
			)
		)
		->set_default
		(
			array
			(
				'id_sub'							=> 0,
				'tahun'								=> get_userdata('year')
			)
		)
		->order_by
		(
			array
			(
				'id'								=> 'ASC'//,
				//'uraian'							=> 'ASC'				
			)
		)
		->field_prepend
		(
			array
			(
				'nilai'								=> 'Rp'
			)
		)
		->field_position
		(
			array
			(
				'nm_sub'							=> 2,
				'satuan_1'							=> 2,
				'satuan_2'							=> 2,
				'satuan_3'							=> 2,
				'deskripsi'							=> 2,
				'url'								=> 3,
				'approve'							=> 3,
				'approved_time'						=> 3,
				'images'							=> 3,
				'alasan'							=> 3
			)
		)
		->order_by
		(
			array
			(
				'kd_standar_harga_1'				=> 'ASC',
				'kd_standar_harga_2'				=> 'ASC',
				'kd_standar_harga_3'				=> 'ASC',
				'kd_standar_harga_4'				=> 'ASC',
				'kd_standar_harga_5'				=> 'ASC',
				'kd_standar_harga_6'				=> 'ASC',
				'kd_standar_harga_7'				=> 'ASC'			
			)
		)
		->render('ref__standar_harga');
	}
	
	public function after_update()
	{
		$prepare									= array
		(
			'approved_time'							=> date('Y-m-d H:i:s'),
			'approved_by'							=> get_userdata('user_id')
		);
		$this->model->update('ref__standar_harga', $prepare, array('id' => $this->input->get('id')), 1);
		
		if('notification' == $this->input->get('referrer'))
		{
			return generateMessages(200, 'Data standar harga berhasil diperbarui...');
		}
	}
	
	private function _accept()
	{
		return make_json
		(
			array
			(
				'action'							=> 'accept',
				'html'								=> 'aa'
			)
		);
	}
	
	private function _decline()
	{
		return make_json
		(
			array
			(
				'action'							=> 'decline',
				'html'								=> 'aa'
			)
		);
	}
	
	private function _notification()
	{
		if($this->input->post('action') && $this->input->post('item'))
		{
			$query									= $this->model->update
			(
				'ref__standar_harga',
				array
				(
					'approve'						=> ('accept' == $this->input->post('action') ? 1 : 2),
					'approved_time'					=> date('Y-m-d H:i:s'),
					'approved_by'					=> get_userdata('user_id'),
					'dilihat'						=> 1,
					'alasan'						=> $this->input->post('reason')
				),
				array
				(
					'id'							=> $this->input->post('item')
				),
				1
			);
			
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
		
		$this->_total								= $this->model->select('count(id) as total')->get_where('ref__standar_harga', array('approve' => 0))->row('total');
		
		if($this->input->post('counting'))
		{
			return make_json
			(
				array
				(
					'count'							=> $this->_total
				)
			);
		}
		
		$query										= $this->model->select
		('
			ref__standar_harga.*,
			ref__sub.nm_sub
		')
		->join
		(
			'ref__sub',
			'ref__sub.id = ref__standar_harga.id_sub',
			'left'
		)
		->order_by('ref__standar_harga.id', 'DESC')
		->limit($this->_limit, $this->_offset)
		->get_where
		(
			'ref__standar_harga',
			array
			(
				'ref__standar_harga.approve'		=> 0
			)
		)
		->result();
		
		$output										= null;
		
		if($query)
		{
			foreach($query as $key => $val)
			{
				$files								= null;
				if($val->images)
				{
					$json_files						= json_encode($val->images);
					if($json_files && is_object($json_files))
					{
						foreach($json_files as $label => $source)
						{
							$files					.= '<p class="text-sm"><a href="' . base_url('uploads/verifikasi_standar_harga/' . $source) . '" target="_blank">' . $label . '</a></p>';
						}
					}
				}
				
				$hyperlink							= null;
				
				if($val->url)
				{
					$url							= array_map('trim', explode(',', $val->url));
					
					if($url && is_array($url))
					{
						foreach($url as $label => $source)
						{
							if(strpos($source, 'http://') !== false || strpos($source, 'https://') !== false)
							{
								$hyperlink			.= '<p class="text-sm"><a href="' . $source . '" target="_blank"><i class="fa fa-external-link"></i> Link ' . (sizeof($url) > 1 ? ($label + 1) : null) . '</a></p>';
							}
						}
					}
				}
				
				$output								.= 
				'
					<div class="form-group">
						' . ($key ? '<hr />' : '') . '
						<span class="badge float-right ' . ($val->flag ? 'badge-danger' : 'badge-primary') . '">
							' . ($val->flag ? 'SBB' : 'SHT') . '
						</span>
						<div class="row">
							<div class="col-sm-5" style="word-break: break-all;">
								<b>
									' . $val->uraian . '
								</b>
								<br />
								<small>
									' . $val->deskripsi . ' - ' . $val->nm_sub . '
								</small>
								<br />
								<small>
									' . number_format($val->nilai) . ' ' . ($val->satuan_1 ? ' / ' . $val->satuan_1 : null) . ($val->satuan_2 ? ' x ' . $val->satuan_2 : null) . ($val->satuan_3 ? ' x ' . $val->satuan_3 : null) . '
								</small>
								<br />
								<a href="' . current_page() . '" class="btn btn-success btn-xs notification-action" data-action="accept" data-item="' . $val->id . '">
									<i class="mdi mdi-check"></i>
									Terima
								</a>
								<a href="' . current_page() . '" class="btn btn-danger btn-xs notification-action" data-action="decline" data-item="' . $val->id . '">
									<i class="mdi mdi-window-close"></i>
									Tolak
								</a>
								<a href="' . current_page('update', array('id' => $val->id, 'referrer' => 'notification')) . '" class="btn btn-warning btn-xs --xhr">
									<i class="mdi mdi-square-edit-outline"></i>
									Edit
								</a>
							</div>
							<div class="col-sm-2" style="word-break: break-all;">
								<p class="text-sm"><b>' . phrase('file') . '</b></p>
								' . ($files ? $files : '-') . '
								' . ($hyperlink ? $hyperlink : null) . '
							</div>
							<div class="col-sm-5">
								<textarea name="alasan" class="form-control bordered reason" placeholder="Alasan diterima / ditolak">' . $val->alasan . '</textarea>
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
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="alert alert-info">
							<i class="fa fa-info-circle"></i>
							Terdapat ' . number_format($this->_total) . ' daftar pengajuan standar harga yang menunggu verifikasi.
						</div>
						<br />
						' . $output . '
						' . $this->_pagination() . '
					</div>
				</div>
			';
		}
		else
		{
			$output									= 
			'
				<div class="container-fluid">
					<div class="alert alert-info rounded-0 border-bottom" style="margin-left:-15px; margin-right:-15px">
						<i class="fa fa-info-circle"></i>
						Terdapat ' . number_format($this->_total) . ' daftar pengajuan standar harga yang menunggu verifikasi.
					</div>
					<br />
					' . $output . '
				</div>
				' . $this->_pagination() . '
			';
		}
		
		make_json
		(
			array
			(
				'meta'								=> array
				(
					'icon'							=> 'mdi mdi-bell-ring-outline',
					'title'							=> 'Pengajuan SSH',
					'modal_size'					=> 'modal-lg'
				),
				'count'								=> number_format($this->_total),
				'html'								=> $output
			)
		);
	}
	
	private function _pagination()
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
			'base_url'								=> current_page(null, array('per_page' => null)),
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
			<div class="pt-3 pr-3 pl-3">
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