<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo  phrase('submitting'); ?>" data-icon="check" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" enctype="multipart/form-data">
	<?php echo ($modal ? '<div class="row"><div class="col-sm-10 col-sm-offset-1">' : null); ?>
		<div class="box no-border animated fadeIn">
			<div class="box-header with-border">
				<div class="box-tools pull-right">
					<?php if(!$modal) { ?>
					<a href="<?php echo current_page(); ?>" class="btn btn-box-tool ajaxLoad show_process">
						<i class="fa fa-refresh"></i>
					</a>
					<?php } ?>
					<button type="button" class="btn btn-box-tool" data-widget="collapse">
						<i class="fa fa-minus"></i>
					</button>
					<button type="button" class="btn btn-box-tool" data-widget="maximize">
						<i class="fa fa-expand"></i>
					</button>
					<button type="button" class="btn btn-box-tool"<?php echo ($modal ? ' data-dismiss="modal"' : ' data-widget="remove"'); ?>>
						<i class="fa fa-times"></i>
					</button>
				</div>
				<h3 class="box-title">
					<i class="fa fa-edit"></i>
					&nbsp;
					<?php echo $title; ?>
				</h3>
			</div>
			<div class="box-body animated zoomIn">
				<?php if($description) { ?>
					<div class="row">
						<div class="alert alert-info no-border no-radius fade in" style="margin-top:-12px">
							<i class="fa fa-info-circle"></i>
							&nbsp;
							<?php echo $description; ?>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="row">
							<div class="col-md-7">
								<?php
									$action							= $this->router->fetch_method();
									foreach($results as $key => $val)
									{
										if('create' == $action)
										{
											$this->db->where('ta__model_belanja_rinc.id', $this->input->get('id'));
											$query					= $this->db
																	->select('ta__model_variabel.id, ta__model_variabel.nm_variabel')
																	->from('ta__model_belanja_rinc')
																	->join('ta__model_belanja', 'ta__model_belanja.id = ta__model_belanja_rinc.id_belanja')
																	->join('ta__model_variabel', 'ta__model_variabel.id_model = ta__model_belanja.id_model')
																	->get()->result_array();
										}
										else
										{
											$this->db->where('ta__model_belanja_rinc_sub.id', $this->input->get('id'));
											$query					= $this->db
															->select('ta__model_variabel.id, ta__model_variabel.nm_variabel')
															->from('ta__model_belanja_rinc_sub')
															->join('ta__model_belanja_rinc', 'ta__model_belanja_rinc.id = ta__model_belanja_rinc_sub.id_belanja_rinc')->join('ta__model_belanja', 'ta__model_belanja.id = ta__model_belanja_rinc.id_belanja')
															->join('ta__model_variabel', 'ta__model_variabel.id_model = ta__model_belanja.id_model')
															->get()->result_array();
										}
										$option						= null;
										if($query)
										{
											foreach($query as $abc => $def)
											{
												$option				.= '<option value="' . $def['id'] . '">' . $def['nm_variabel'] . '</option>';
											}
										}
										echo '
											<div class="form-group">
												<label class="control-label big-label text-muted text-uppercase" for="kd_belanja_rinc_sub_input">
													' . $val['kd_belanja_rinc_sub']['label'] . '
												</label>
												' . $val['kd_belanja_rinc_sub']['content'] . '
											</div>
											<div class="form-group">
												<label class="control-label big-label text-muted text-uppercase" for="uraian_input">
													' . $val['uraian']['label'] . '
												</label>
												' . $val['uraian']['content'] . '
											</div>
											<div class="form-group">
												<label class="control-label big-label text-muted text-uppercase" for="nilai_input">
													' . $val['nilai']['label'] . '
												</label>
												' . $val['nilai']['content'] . '
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label big-label text-muted text-uppercase" for="vol_1_input">
															' . $val['vol_1']['label'] . '
														</label>
														' . $val['vol_1']['content'] . '
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label big-label text-muted text-uppercase" for="satuan_1_input">
															' . $val['satuan_1']['label'] . '
														</label>
														' . $val['satuan_1']['content'] . '
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label big-label text-muted text-uppercase" for="vol_2_input">
															' . $val['vol_2']['label'] . '
														</label>
														' . $val['vol_2']['content'] . '
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label big-label text-muted text-uppercase" for="satuan_2_input">
															' . $val['satuan_2']['label'] . '
														</label>
														' . $val['satuan_2']['content'] . '
													</div>
												</div>
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label big-label text-muted text-uppercase" for="vol_3_input">
															' . $val['vol_3']['label'] . '
														</label>
														' . $val['vol_3']['content'] . '
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label big-label text-muted text-uppercase" for="satuan_3_input">
															' . $val['satuan_3']['label'] . '
														</label>
														' . $val['satuan_3']['content'] . '
													</div>
												</div>
											</div>
											<br />
											<div class="row form-group">
												<label class="control-label col-sm-12 big-label text-muted text-uppercase" for="satuan_123_input">
													' . $val['satuan_123']['label'] . '
												</label>
												<div class="col-sm-12">
													' . $val['satuan_123']['content'] . '
													<input type="hidden" name="satuan_123" class="satuan_total_hidden" value="" />
												</div>
											</div>
										';
									}
								?>
							</div>
							<div class="col-md-5">
								<div class="form-group">
									<label class="control-label">
										<b>Variable yang tersedia</b>
									</label>
								</div>
								<div class="form-group">
									<?php
										$query = $this->model->query
										('
											SELECT
											ta__model_variabel.kd_variabel,
											ta__model_variabel.nm_variabel,
											ta__model_variabel.id
											FROM
											ta__model_variabel
											INNER JOIN ta__model ON ta__model_variabel.id_model = ta__model.id
											INNER JOIN ta__model_belanja ON ta__model_belanja.id_model = ta__model.id
											INNER JOIN ta__model_belanja_rinc ON ta__model_belanja_rinc.id_belanja = ta__model_belanja.id
											WHERE
											ta__model_belanja_rinc.id = ' . $this->input->get('id_belanja_rinc') . '
											ORDER BY
											ta__model_variabel.kd_variabel ASC										
										')
										->result_array();
										if($query)
										{
											$row		= null;
											foreach($query as $key => $val)
											{
												$row	.= '
													<tr>
														<td align="center">
															' . $val['kd_variabel'] . '
														</td>
														<td>
															' . $val['nm_variabel'] . '
														</td>
														<td align="center">
														{' . $val['id'] . '}
														</td>
													</tr>
												';
											}
											echo '
												<table class="table table-bordered">
													<thead>
														<tr>
															<th align="center">
																No.
															</th>
															<th align="center">
																Variabel
															</th>
															<th align="center">
																Kode
															</th>
														</tr>
													</thead>
													<tbody>
														' . $row . '
													</tbody>
												</table>
											';
										}
										else
										{
											echo '<div class="alert alert-warning">Variabel belum tersedia</div>';
										}
									?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="row">
							<div class="col-md-5 col-md-offset-7 callback-status">
								<div class="btn-group btn-group-justified">
									<?php if($modal) { ?>
										<a href="javascript:void(0)" class="btn btn-primary btn-holo" data-dismiss="modal">
											<i class="fa fa-times"></i>
											&nbsp;
											<?php echo phrase('cancel'); ?>
											<small class="hidden-xs hidden-sm" style="font-size:10px;color:#cacaca">(ESC)</small>
										</a>
									<?php } else { ?>
										<a href="<?php echo go_to(); ?>" class="btn btn-primary btn-holo ajaxLoad">
											<i class="fa fa-chevron-left"></i>
											&nbsp;
											<?php echo phrase('back'); ?>
										</a>
									<?php } ?>
									<div class="btn-group">
										<input type="hidden" name="token" value="<?php echo $token; ?>" />
										<button type="submit" class="btn btn-primary btn-holo submitBtn">
											<i class="fa fa-check"></i>
											&nbsp;
											<?php echo phrase('submit'); ?>
											<small class="hidden-xs hidden-sm" style="font-size:10px;color:#cacaca">(CTRL+S)</small>
										</button>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	<?php echo ($modal ? '</div></div>' : null); ?>
</form>