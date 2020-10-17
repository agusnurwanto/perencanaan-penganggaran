<div class="container-fluid pt-3 pb-3">
	<div class="row">
		<div class="col-md-6">
			<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
				<div class="form-group">
					<label class="d-block text-muted" for="hostname_input">
						<?php echo $results->form_data->hostname->label; ?>
						<?php echo ($results->form_data->hostname->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->hostname->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="logout_url_input">
						<?php echo $results->form_data->logout_url->label; ?>
						<?php echo ($results->form_data->logout_url->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->logout_url->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="username_input">
						<?php echo $results->form_data->username->label; ?>
						<?php echo ($results->form_data->username->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->username->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="password_input">
						<?php echo $results->form_data->password->label; ?>
						<?php echo ($results->form_data->password->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->password->content; ?>
				</div>
				<div class="--validation-callback mb-0"></div>
				<div class="opt-btn">
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<a href="<?php echo current_page('../'); ?>" class="btn btn-link --xhr">
						<i class="mdi mdi-arrow-left"></i>
						<?php echo phrase('back'); ?>
					</a>
					<button type="submit" class="btn btn-primary float-right">
						<i class="mdi mdi-check"></i>
						<?php echo phrase('submit'); ?>
						<em class="text-sm">(ctrl+s)</em>
					</button>
				</div>
			</form>
		</div>
	</div>
</div>