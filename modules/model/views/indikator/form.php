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
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="row">
							<div class="col-md-7">
								<?php
									foreach($results as $key => $val)
									{
										echo '
											<div class="form-group">
												<label class="control-label big-label text-muted text-uppercase" for="jns_indikator_input">
													' . $val['jns_indikator']['label'] . '
												</label>
												' . $val['jns_indikator']['content'] . '
											</div>
											<div class="form-group">
												<label class="control-label big-label text-muted text-uppercase" for="kd_indikator_input">
													' . $val['kd_indikator']['label'] . '
												</label>
												' . $val['kd_indikator']['content'] . '
											</div>
											<div class="form-group">
												<label class="control-label big-label text-muted text-uppercase" for="tolak_ukur_input">
													' . $val['tolak_ukur']['label'] . '
												</label>
												' . $val['tolak_ukur']['content'] . '
											</div>
											<div class="row">
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label big-label text-muted text-uppercase" for="target_input">
															' . $val['target']['label'] . '
														</label>
														' . $val['target']['content'] . '
													</div>
												</div>
												<div class="col-sm-6">
													<div class="form-group">
														<label class="control-label big-label text-muted text-uppercase" for="satuan_input">
															' . $val['satuan']['label'] . '
														</label>
														' . $val['satuan']['content'] . '
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label big-label text-muted text-uppercase" for="penjelasan_input">
													' . $val['penjelasan']['label'] . '
												</label>
												' . $val['penjelasan']['content'] . '
											</div>
										';
									}
								?>
							</div>
							<div class="col-sm-5">
								<div class="form-group">
									<label class="control-label">
										<b>Variable yang tersedia</b>
									</label>
								</div>
								<div class="form-group">
									<?php
										if($variabel)
										{
											$row		= null;
											foreach($variabel as $key => $val)
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