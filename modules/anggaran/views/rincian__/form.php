<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo phrase('submitting'); ?>" data-icon="check" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" enctype="multipart/form-data">
	<?php if($modal) { ?><div class="row"><div class="col-md-6 col-md-offset-3"><?php } ?>
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
				<div class="row form-group">
					<div class="<?php echo ($modal ? 'col-md-10 col-md-offset-1' : 'col-md-7 col-md-offset-1'); ?>">
						<div class="row form-group animated zoomIn">
							<div class="col-sm-2">
								<label class="control-label text-muted text-uppercase big-label" for="kd_belanja_rinc_input">
									<?php echo $results['form_data']['kd_belanja_rinc']['label']; ?>
								</label>
								<?php echo $results['form_data']['kd_belanja_rinc']['content']; ?>
							</div>
							<div class="col-sm-7">
								<label class="control-label text-muted text-uppercase big-label" for="uraian_input">
									<?php echo $results['form_data']['uraian']['label']; ?>
								</label>
								<?php echo $results['form_data']['uraian']['content']; ?>
							</div>
							<div class="col-sm-3">
								<label class="control-label text-muted text-uppercase big-label" for="nilai_input">
									<?php echo $results['form_data']['nilai']['label']; ?>
								</label>
								<input type="number" name="nilai" min="0" class="form-control sum_field" value="<?php echo $results['form_data']['nilai']['original']; ?>" data-original-value="<?php echo $results['form_data']['nilai']['original']; ?>" placeholder="<?php echo phrase('number_only'); ?>" id="nilai_input" maxlength="17">
							</div>
						</div>
						<div class="autocomplete-description">
							<?php
								if($results['form_data']['id_standar_harga']['original'])
								{
									$desc		= $this->model->select('deskripsi')->get_where('ref__standar_harga', array('id' => $results['form_data']['id_standar_harga']['original']), 1)->row('deskripsi');
									if($desc)
									{
										echo '<div class="alert alert-info">' . $desc . '</div>';
									}
								}
							?>
							<input type="hidden" name="id_standar_harga" class="id_standar_harga_input" value="<?php echo $results['form_data']['id_standar_harga']['original']; ?>" data-original-value="<?php echo $results['form_data']['id_standar_harga']['original']; ?>" />
						</div>
						<div class="row">
							<div class="col-xs-4">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="vol_1_input">
										<?php echo $results['form_data']['vol_1']['label']; ?>
									</label>
									<?php echo $results['form_data']['vol_1']['content']; ?>
								</div>
							</div>
							<div class="col-xs-8">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="satuan_1_input">
										<?php echo $results['form_data']['satuan_1']['label']; ?>
									</label>
									<?php echo $results['form_data']['satuan_1']['content']; ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-4">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="vol_2_input">
										<?php echo $results['form_data']['vol_2']['label']; ?>
									</label>
									<?php echo $results['form_data']['vol_2']['content']; ?>
								</div>
							</div>
							<div class="col-xs-8">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="satuan_2_input">
										<?php echo $results['form_data']['satuan_2']['label']; ?>
									</label>
									<?php echo $results['form_data']['satuan_2']['content']; ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-4">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="vol_3_input">
										<?php echo $results['form_data']['vol_3']['label']; ?>
									</label>
									<?php echo $results['form_data']['vol_3']['content']; ?>
								</div>
							</div>
							<div class="col-xs-8">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="satuan_3_input">
										<?php echo $results['form_data']['satuan_3']['label']; ?>
									</label>
									<?php echo $results['form_data']['satuan_3']['content']; ?>
								</div>
							</div>
						</div>
						<div class="row">
							<div class="col-xs-4">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="total_input">
										<?php echo $results['form_data']['total']['label']; ?>
									</label>
									<?php echo $results['form_data']['total']['content']; ?>
								</div>
							</div>
							<div class="col-xs-8">
								<div class="form-group animated zoomIn">
									<label class="control-label text-muted text-uppercase big-label" for="satuan_123_input">
										<?php echo $results['form_data']['satuan_123']['label']; ?>
									</label>
									<?php echo $results['form_data']['satuan_123']['content']; ?>
								</div>
							</div>
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