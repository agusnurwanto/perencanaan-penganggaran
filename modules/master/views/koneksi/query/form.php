<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo phrase('submitting'); ?>" data-icon="check" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" enctype="multipart/form-data">
	<div class="row">
		<?php echo ($modal ? '<div class="col-sm-6 col-sm-offset-3">' : '<div class="col-sm-12">'); ?>
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
						<i class="<?php echo $icon; ?>"></i>
						&nbsp;
						<?php echo $title; ?>
					</h3>
				</div>
				<div class="box-body animated zoomIn">
					<div class="row">
						<div class="<?php echo ($modal ? 'col-sm-10 col-sm-offset-1' : 'col-sm-8 col-sm-offset-1'); ?>">
							<?php
								foreach($results as $key => $val)
								{
									echo '
										<div class="form-group">
											<label class="control-label big-label text-uppercase text-muted">
												' . $val['query_name']['label'] . '
											</label>
											' . $val['query_name']['content'] . '
										</div>
										<div class="form-group">
											<label class="control-label big-label text-uppercase text-muted">
												' . $val['query']['label'] . '
											</label>
											' . $val['query']['content'] . '
										</div>
										<div class="row">
											<div class="col-sm-6 form-group">
												<label class="control-label big-label text-uppercase text-muted">
													' . $val['json_file_location']['label'] . '
												</label>
												' . $val['json_file_location']['content'] . '
											</div>
											<div class="col-sm-6 form-group">
												<label class="control-label big-label text-uppercase text-muted">
													' . $val['json_file_name']['label'] . '
												</label>
												' . $val['json_file_name']['content'] . '
											</div>
										</div>
										<div class="row">
											<div class="col-sm-6 form-group">
												' . $val['status']['content'] . '
											</div>
											<div class="col-sm-6 form-group">
												<button type="button" class="btn btn-info btn-block query_checker show_process" data-href="' . go_to('run_query') . '">
													<i class="fa fa-refresh"></i>
													&nbsp;
													' . phrase('run_query') . '
												</button>
											</div>
										</div>
									';
								}
							?>
							<div class="row form-group">
								<div class="col-sm-12">
									<div class="query_checker_info"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="<?php echo ($modal ? 'col-sm-10 col-sm-offset-1' : 'col-sm-8 col-sm-offset-1'); ?> callback-status">
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
</form>