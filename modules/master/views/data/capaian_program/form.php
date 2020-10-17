<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo phrase('submitting'); ?>" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" data-icon="check" enctype="multipart/form-data">
	<?php echo ($modal ? '<div class="row"><div class="col-md-6 col-md-offset-3">' : null); ?>
	<div class="box no-border">
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
				<button type="button" class="btn btn-box-tool"<?php echo ($modal ? 'data-dismiss="modal"' : 'data-widget="remove"'); ?>>
					<i class="fa fa-times"></i>
				</button>
			</div>
			<h3 class="box-title">
				<i class="<?php echo $icon; ?>"></i>
				&nbsp;
				<?php echo $title; ?>
			</h3>
		</div>
		<div class="box-body">
			<?php if($description) { ?>
				<div class="row form-group">	
					<div class="alert alert-info alert-dismissable no-border no-radius animated fadeIn" style="margin-top:-12px">
						<button type="button" class="close pull-right" data-dismiss="alert" aria-hidden="true">
							<i class="fa fa-times"></i>
						</button>
						<i class="fa fa-info-circle"></i>
						&nbsp;
						<?php echo $description; ?>
					</div>
				</div>
			<?php } ?>
			<div class="row">
				<div class="<?php echo ($modal ? 'col-md-10 col-md-offset-1' : 'col-md-6 col-md-offset-1'); ?>">
					<?php
						$form_data									= $results['form_data'];
						if(isset($form_data['id_prog']))
						{
							echo '
								<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="id_prog_input">
										' . ($form_data['id_prog']['required'] ? '<span class="text-sm text-capitalize text-danger pull-right">' . phrase('required') . '</span>' : null) . '
										' . $form_data['id_prog']['label'] . '
									</label>
									' . $form_data['id_prog']['content'] . '
								</div>
							';
						}
						echo '
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kd_capaian_input">
									' . ($form_data['kd_capaian']['required'] ? '<span class="text-sm text-capitalize text-danger pull-right">' . phrase('required') . '</span>' : null) . '
									' . $form_data['kd_capaian']['label'] . '
								</label>
								' . $form_data['kd_capaian']['content'] . '
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="tolak_ukur_input">
									' . ($form_data['tolak_ukur']['required'] ? '<span class="text-sm text-capitalize text-danger pull-right">' . phrase('required') . '</span>' : null) . '
									' . $form_data['tolak_ukur']['label'] . '
								</label>
								' . $form_data['tolak_ukur']['content'] . '
							</div>
							<div class="row form-group">
								<div class="col-sm-6">
									<label class="control-label big-label text-muted text-uppercase" for="target_angka_input">
										' . ($form_data['target_angka']['required'] ? '<span class="text-sm text-capitalize text-danger pull-right">' . phrase('required') . '</span>' : null) . '
										' . $form_data['target_angka']['label'] . '
									</label>
									' . $form_data['target_angka']['content'] . '
								</div>
								<div class="col-sm-6">
									<label class="control-label big-label text-muted text-uppercase" for="target_uraian_input">
										' . ($form_data['target_uraian']['required'] ? '<span class="text-sm text-capitalize text-danger pull-right">' . phrase('required') . '</span>' : null) . '
										' . $form_data['target_uraian']['label'] . '
									</label>
									' . $form_data['target_uraian']['content'] . '
								</div>
							</div>
						';
					?>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="row">
				<div class="<?php echo ($modal ? 'col-md-10 col-md-offset-1' : 'col-md-6 col-md-offset-1'); ?> callback-status">
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
	<?php echo ($modal ? '</div></div>' : null); ?>
</form>