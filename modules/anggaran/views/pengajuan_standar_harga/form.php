<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo phrase('submitting'); ?>" data-icon="check" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" enctype="multipart/form-data">
	<?php if($modal) { ?><div class="row"><div class="col-md-4 col-md-offset-4"><?php } ?>
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
					<div class="<?php echo ($modal ? 'col-md-10 col-md-offset-1' : 'col-md-7 col-md-offset-1'); ?>">
						<div class="form-group">
							<div class="alert alert-info">
								<i class="fa fa-info-circle"></i>
								Anda dapat menggunakan standar harga (uraian) setelah pengajuan ini disetujui.
							</div>
						</div>
						<div class="form-group animated zoomIn">
							<label class="control-label text-muted text-uppercase big-label" for="deskripsi_input">
								<?php echo $results['form_data']['uraian']['label']; ?>
							</label>
							<?php echo $results['form_data']['uraian']['content']; ?>
						</div>
						<div class="row">
							<div class="col-sm-6">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="nilai_input">
										<?php echo $results['form_data']['nilai']['label']; ?>
									</label>
									<?php echo $results['form_data']['nilai']['content']; ?>
								</div>
							</div>
							<div class="col-sm-6">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="v_input">
										Satuan 1
									</label>
									<?php echo $results['form_data']['satuan_1']['content']; ?>
								</div>
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="v_input">
										Satuan 2
									</label>
									<?php echo $results['form_data']['satuan_2']['content']; ?>
								</div>
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="v_input">
										Satuan 3
									</label>
									<?php echo $results['form_data']['satuan_3']['content']; ?>
								</div>
							</div>
						</div>
						<div class="form-group animated zoomIn">
							<label class="control-label text-muted text-uppercase big-label" for="deskripsi_input">
								<?php echo $results['form_data']['deskripsi']['label']; ?>
							</label>
							<?php echo $results['form_data']['deskripsi']['content']; ?>
						</div>
						<div class="form-group animated zoomIn">
							<label class="control-label text-muted text-uppercase big-label" for="flag_input">
								<?php echo $results['form_data']['flag']['label']; ?>
							</label>
							<?php echo $results['form_data']['flag']['content']; ?>
						</div>
						<div class="form-group animated zoomIn">
							<label class="control-label text-muted text-uppercase big-label" for="flag_input">
								<?php echo $results['form_data']['images']['label']; ?>
							</label>
							<?php echo $results['form_data']['images']['content']; ?>
						</div>
						<div class="form-group animated zoomIn">
							<label class="control-label text-muted text-uppercase big-label" for="flag_input">
								<?php echo $results['form_data']['url']['label']; ?>
							</label>
							<?php echo $results['form_data']['url']['content']; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="<?php echo ($modal ? 'col-md-10 col-md-offset-1' : 'col-md-7 col-md-offset-1'); ?> callback-status">
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
								<?php echo phrase('cancel'); ?>
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
	<?php if($modal) { echo '</div></div>'; } ?>
</form>