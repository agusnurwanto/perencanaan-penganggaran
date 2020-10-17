<style type="text/css">
	#footer-wrapper
	{
		display: none
	}
</style>
<div class="full-height d-flex align-items-center justify-content-center">
	<div class="container-fluid pt-3 pb-3">
		<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
			<div class="pr-2 pl-2">
				<div class="form-group">
					<input type="text" name="username" class="form-control" id="username_input" placeholder="<?php echo phrase('type_username_or_email_here'); ?>" />
				</div>
				<div class="form-group">
					<input type="password" name="password" class="form-control" id="password_input" placeholder="<?php echo phrase('type_password_here'); ?>" autocomplete="new-password" />
				</div>
				<?php if(get_setting('login_annually')) { ?>
					<div class="form-group">
						<?php
							$year					= get_active_years(); /* from grobal helper */
							$option					= null;
							foreach($year as $key => $val)
							{
								$option				.= '<option value="' . $val->year . '"' . ($val->default ? ' selected' : null) . '>' . $val->year . '</option>';
							}
						?>
						<select name="year" class="form-control" placeholder="<?php echo phrase('choose_year'); ?>" id="year_input">
							<?php echo $option; ?>
						</select>
					</div>
				<?php } ?>
				
				<div class="--validation-callback mb-3"></div>
				
				<div class="form-group">
					<label class="d-none">
						<input type="checkbox" name="remember_session" value="1" checked /> 
						<?php echo phrase('remember_me'); ?>
					</label>
				</div>
				<div class="form-group">
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<button type="submit" class="btn btn-primary btn-block">
						<i class="mdi mdi-check"></i> 
						<?php echo phrase('sign_in'); ?>
					</button>
				</div>
			</div>
		</form>
		<?php if(get_setting('enable_frontend_registration')){ ?>
			<div class="pr-2 pl-2">
				<p class="text-center text-muted">
					<?php echo phrase('do_not_have_account_yet'); ?>
					&nbsp;
					<a href="<?php echo base_url('auth/register'); ?>" class="--xhr">
						<b>
							<?php echo phrase('register_an_account'); ?>
						</b>
					</a>
				</p>
			</div>
		<?php } ?>
		<?php if(get_setting('enable_frontend_registration')){ ?>
			<div class="pr-2 pl-2">
				<p class="text-center text-muted pt-2">
					<?php echo phrase('or_sign_in_with_your_social_account'); ?>
				</p>
				<div class="row">
					<?php if(get_setting('google_client_id') && get_setting('google_client_secret')) { ?>
					<div class="col-6">
						<p>
							<a href="<?php echo base_url('auth/google'); ?>" class="btn btn-outline-danger btn-block btn-sm">
								<i class="mdi mdi-google"></i>
								<?php echo phrase('google'); ?>
							</a>
						</p>
					</div>
					<?php } ?>
					<?php if(get_setting('facebook_app_id') && get_setting('facebook_app_secret')) { ?>
					<div class="col-6">
						<p>
							<a href="<?php echo base_url('auth/facebook'); ?>" class="btn btn-outline-primary btn-block btn-sm">
								<i class="mdi mdi-facebook"></i>
								<?php echo phrase('facebook'); ?>
							</a>
						</p>
					</div>
					<?php } ?>
				</div>
			</div>
		<?php } ?>
	</div>
</div>