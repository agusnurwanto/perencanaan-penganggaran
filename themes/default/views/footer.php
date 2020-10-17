<div class="bg-white pt-5">
	<div class="container">
		<div class="row">
			<div class="col-md-3 offset-md-1 text-sm-center mb-5">
				<p>
					<a href="<?php echo base_url(); ?>">
						<img src="<?php echo get_image('settings', get_setting('app_icon'), 'thumb'); ?>" class="img-fluid mt-3 grayscale --xhr" width="200" />
					</a>
				</p>
			</div>
			<div class="col col-md-2">
				<ul class="nav flex-column row">
					<li class="nav-item">
						<b class="nav-link">
							<?php echo phrase('featured'); ?>
						</b>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('blogs'); ?>" class="nav-link --xhr">
							<?php echo phrase('blogs'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('galleries'); ?>" class="nav-link --xhr">
							<?php echo phrase('galleries'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('peoples'); ?>" class="nav-link --xhr">
							<?php echo phrase('peoples'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('announcements'); ?>" class="nav-link --xhr">
							<?php echo phrase('announcements'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('testimonials'); ?>" class="nav-link --xhr">
							<?php echo phrase('testimonials'); ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="col col-md-2">
				<ul class="nav flex-column row">
					<li class="nav-item">
						<b class="nav-link">
							<?php echo phrase('knowledge_center'); ?>
						</b>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('pages/guidelines/documentations'); ?>" class="nav-link --xhr">
							<?php echo phrase('documentations'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('pages/guidelines/features'); ?>" class="nav-link --xhr">
							<?php echo phrase('features'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('pages/guidelines/faqs'); ?>" class="nav-link --xhr">
							<?php echo phrase('faqs'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('pages/guidelines/terms-and-conditions'); ?>" class="nav-link --xhr">
							<?php echo phrase('terms_and_conditions'); ?>
						</a>
					</li>
					<li class="nav-item">
						<a href="<?php echo base_url('pages/guidelines/privacy-policy'); ?>" class="nav-link --xhr">
							<?php echo phrase('privacy_policy'); ?>
						</a>
					</li>
				</ul>
			</div>
			<div class="col-md-3">
				<hr class="row d-lg-none" />
				<ul class="nav flex-column row">
					<li class="nav-item">
						<b class="nav-link">
							<?php echo phrase('about_us'); ?>
						</b>
					</li>
					<?php if(get_setting('office_address')) { ?>
					<li class="nav-item">
						<a href="#" class="nav-link">
							<div class="row no-gutters">
								<div class="col-2 col-sm-1">
									<i class="mdi mdi-map-marker text-danger"></i>
								</div>
								<div class="col-10 col-sm-11">
									<?php echo get_setting('office_address'); ?>
								</div>
							</div>
						</a>
					</li>
					<?php } ?>
					<?php if(get_setting('office_email')) { ?>
					<li class="nav-item">
						<a href="mailto:<?php echo get_setting('office_email'); ?>" class="nav-link">
							<div class="row no-gutters">
								<div class="col-2 col-sm-1">
									<i class="mdi mdi-at text-danger"></i>
								</div>
								<div class="col-10 col-sm-11">
									<?php echo get_setting('office_email'); ?>
								</div>
							</div>
						</a>
					</li>
					<?php } ?>
					<?php if(get_setting('office_phone')) { ?>
					<li class="nav-item">
						<a href="tel:<?php echo get_setting('office_phone'); ?>" class="nav-link">
							<div class="row no-gutters">
								<div class="col-2 col-sm-1">
									<i class="mdi mdi-phone text-success"></i>
								</div>
								<div class="col-10 col-sm-11">
									<?php echo get_setting('office_phone'); ?>
								</div>
							</div>
						</a>
					</li>
					<?php } ?>
					<?php if(get_setting('office_fax')) { ?>
					<li class="nav-item">
						<a href="fax:<?php echo get_setting('office_fax'); ?>" class="nav-link">
							<div class="row no-gutters">
								<div class="col-2 col-sm-1">
									<i class="mdi mdi-fax text-warning"></i>
								</div>
								<div class="col-10 col-sm-11">
									<?php echo get_setting('office_fax'); ?>
								</div>
							</div>
						</a>
					</li>
					<?php } ?>
					<?php if(get_setting('whatsapp_number')) { ?>
					<li class="nav-item">
						<a href="https://api.whatsapp.com/send?phone=<?php echo str_replace('+', '', get_setting('whatsapp_number')); ?>&text=<?php echo phrase('hello') . '%20' . get_setting('app_name'); ?>..." class="nav-link">
							<div class="row no-gutters">
								<div class="col-2 col-sm-1 text-success">
									<i class="mdi mdi-whatsapp"></i>
								</div>
								<div class="col-10 col-sm-11">
									<?php echo get_setting('whatsapp_number'); ?>
								</div>
							</div>
						</a>
					</li>
					<?php } ?>
					<?php if(get_setting('facebook_fanpage')) { ?>
					<li class="nav-item">
						<a href="<?php echo get_setting('facebook_fanpage'); ?>" class="nav-link">
							<div class="row no-gutters">
								<div class="col-2 col-sm-1">
									<i class="mdi mdi-facebook text-primary"></i>
								</div>
								<div class="col-10 col-sm-11">
									@<?php echo basename(get_setting('facebook_fanpage')); ?>
								</div>
							</div>
						</a>
					</li>
					<?php } ?>
					<?php if(get_setting('twitter_username')) { ?>
					<li class="nav-item">
						<a href="https://www.twitter.com/<?php echo get_setting('twitter_username'); ?>" class="nav-link">
							<div class="row no-gutters">
								<div class="col-2 col-sm-1">
									<i class="mdi mdi-twitter text-info"></i>
								</div>
								<div class="col-10 col-sm-11">
									@<?php echo get_setting('twitter_username'); ?>
								</div>
							</div>
						</a>
					</li>
					<?php } ?>
					<?php if(get_setting('instagram_username')) { ?>
					<li class="nav-item">
						<a href="https://www.instagram.com/<?php echo get_setting('instagram_username'); ?>" class="nav-link">
							<div class="row no-gutters">
								<div class="col-2 col-sm-1">
									<i class="mdi mdi-instagram text-danger"></i>
								</div>
								<div class="col-10 col-sm-11">
									@<?php echo get_setting('instagram_username'); ?>
								</div>
							</div>
						</a>
					</li>
					<?php } ?>
				</ul>
			</div>
		</div>
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<p class="text-center text-muted font-weight-light">
					<small>
						<?php echo phrase('app_description'); ?>
					</small>
				</p>
			</div>
		</div>
		<div class="text-center">
			<small class="font-weight-bold">
				<?php echo phrase('copyright'); ?>
				&#169;
				<?php echo date('Y'); ?>
				&nbsp;
				-
				&nbsp;
				<?php echo get_setting('app_name'); ?>
			</small>
			<small>
				(<a href="<?php echo base_url('pages/about'); ?>" class="font-weight-bold --xhr">Aksara <?php echo get_setting('build_version'); ?></a>)
			</small>
		</div>
	</div>
</div>
