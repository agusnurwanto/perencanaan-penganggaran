<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Pemberitahuan extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->_limit								= 25;
		$this->_offset								= (is_numeric($this->input->get('per_page')) ? $this->input->get('per_page') - 1 : 0) * $this->_limit;
		$this->_total								= 0;
		
		$this->set_theme('backend');
		$this->set_permission();
		
		if(!$this->permission->allow('master', 'renja', 'pemberitahuan', $this->_method))
		{
			generateMessages(403, phrase('you_do_not_have_sufficient_privileges_to_access_the_requested_page'), 'dashboard');
		}
	}
	
	public function index()
	{
		if('notification' == $this->input->post('method'))
		{
			return $this->_notification();
		}
		
		// render
	}
	
	private function _notification()
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
		$this->_total								= $this->model->group_by('id_keg')->get_where('ta__asistensi', array('ta__asistensi.tanggapan' => 0))->num_rows();
		
		$query										= $this->model
		->select
		('
			ta__kegiatan.id,
			ta__kegiatan.kegiatan,
			ta__kegiatan.map_address,
			ta__kegiatan.images,
			ta__kegiatan.kd_keg,
			ta__program.kd_id_prog,
			ref__program.kd_program
		')
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id = ta__asistensi.id_keg'
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
		->limit($this->_limit, $this->_offset)
		->order_by('id', 'DESC')
		->group_by('id_keg')
		->get_where
		(
			'ta__asistensi',
			array
			(
				'ta__asistensi.tanggapan'			=> 0
			)
		)
		->result();
		
		$output										= null;
		if($query)
		{
			foreach($query as $key => $val)
			{
				$output								.= 
				'
					//
				';
			}
		}
		
		if($this->input->post('pagination'))
		{
			$output									=
			'
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="panel-group" id="accordion-list" role="tablist" aria-multiselectable="true">
							' . $output . '
						</div>
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
					<div class="row">
						<div class="col-md-6 col-md-offset-3">
							<div class="box no-border">
								<div class="box-header with-border">
									<div class="box-tools pull-right">
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
										<i class="fa fa-asterisk"></i>
										&nbsp;
										Pemberitahuan
									</h3>
								</div>
								<div class="box-body">
									<div class="row">
										<div class="col-md-10 col-md-offset-1">
											<div class="panel-group" id="accordion-list" role="tablist" aria-multiselectable="true">
												' . $output . '
											</div>
											' . $this->_pagination() . '
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			';
		}
		make_json
		(
			array
			(
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
			'full_tag_open'		 					=> '<ul class="pagination pagination-sm no-margin pagination-modal">',
			'full_tag_close'	 					=> '<li>' . $form . '</li></ul>',
			'num_tag_open'							=> '<li>',
			'num_tag_close'							=> '</li>',
			'cur_tag_open'							=> '<li class="active"><a href="javascript:void(0)" class="disabled">',
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
	  
		$this->pagination->initialize($configs);
		
		$results									= $this->pagination->create_links();
		if($results)
		{
			$output									= $results;
		}
		else
		{
			$output									= '
				<ul class="pagination pagination-sm no-margin">
					<li class="paginate_button previous disabled">
						<a href="javascript:void(0)" tabindex="0">
							&lt;
						</a>
					</li>
					<li class="paginate_button active">
						<a href="javascript:void(0)" tabindex="0">
							1
						</a>
					</li>
					<li class="paginate_button next disabled">
						<a href="javascript:void(0)" tabindex="0">
							&gt;
						</a>
					</li>
				</ul>
			';
		}
		$output										= '
			<div class="row">
				<div class="col-sm-12 text-center">
					<label class="text-muted text-sm">
						<i class="fa fa-info-circle"></i>
						&nbsp;
						' . phrase('showing') . ' ' . ($this->_offset ? $this->_offset : ($this->_total > 0 ? 1 : 0)) . ' - ' . (($this->_offset + $this->_limit) < $this->_total ? ($this->_offset + $this->_limit) : $this->_total) . ' ' . phrase('from') . ' ' . $this->_total . ' ' . phrase('entries_found') . '
					</label>
				</div>
				<div class="col-sm-12 text-center">
					<div class="btn-group btn-group-justified">
						' . $output . '
					</div>
				</div>
			</div>
		';
		return $output;
	}
}