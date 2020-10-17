<?php
	$field							= $results->form_data;
?>
<div class="container-fluid pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		
		<div class="row">
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? 12 : 12); ?>">
				<div class="row">
					<div class="col-md-12">
						<div class="form-group">
							<label class="d-block text-muted" for="id_prog_input">
								Program
								<?php echo ($field->id_prog->required ? '<span class="text-sm text-capitalize text-danger">*</span>' : null); ?>
							</label>
							<?php echo $field->id_prog->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted" for="kode_input">
								Kode
								<?php echo ($field->kd_keg->required ? '<span class="text-sm text-capitalize text-danger">*</span>' : null); ?>
							</label>
							<?php echo $field->kd_keg->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted" for="id_kegiatan_input">
								Kegiatan
								<?php echo ($field->id_kegiatan->required ? '<span class="text-sm text-capitalize text-danger">*</span>' : null); ?>
							</label>
							<?php echo $field->id_kegiatan->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="files_input">
								Files
								<?php echo ($field->files->required ? '<span class="text-sm text-capitalize text-danger">*</span>' : null); ?>
							</label>
							<?php echo $field->files->content; ?>
						</div>
					</div>
				</div>
				
				<div class="--validation-callback mb-0"></div>
				
			</div>
		</div>
		
		<?php echo ('modal' == $this->input->post('prefer') ? '<hr class="row" />' : '<div class="opt-btn-overlap-fix"></div><!-- fix the overlap -->'); ?>
		<div class="row<?php echo ('modal' != $this->input->post('prefer') ? ' opt-btn' : null); ?>">
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? '12 text-right' : 12); ?>">
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				
				<?php if('modal' == $this->input->post('prefer')) { ?>
				<button type="button" class="btn btn-link" data-dismiss="modal">
					<?php echo phrase('close'); ?>
					<em class="text-sm">(esc)</em>
				</button>
				<?php } else { ?>
				<a href="<?php echo go_to(null, $results->query_string); ?>" class="btn btn-link --xhr">
					<i class="mdi mdi-arrow-left"></i>
					<?php echo phrase('back'); ?>
				</a>
				<?php } ?>
				
				<button type="submit" class="btn btn-primary float-right">
					<i class="mdi mdi-check"></i>
					<?php echo phrase('submit'); ?>
					<em class="text-sm">(ctrl+s)</em>
				</button>
			</div>
		</div>
	</form>
</div>