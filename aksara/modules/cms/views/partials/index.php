<div class="container-fluid pt-3 pb-3">
	<div class="row">
		<div class="col-md-5">
			<a href="<?php echo base_url('cms/partials/announcements'); ?>" class="card text-white bg-secondary mb-3 --xhr">
				<div class="card-body">
					<div class="row">
						<div class="col-3">
							<i class="mdi mdi-bullhorn-outline mdi-3x"></i>
						</div>
						<div class="col">
							<h5 class="card-title mb-0">
								<?php echo phrase('announcements'); ?>
							</h5>
							<p class="card-text">
								<?php echo phrase('manage_announcements'); ?>
							</p>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-md-5">
			<a href="<?php echo base_url('cms/partials/carousels'); ?>" class="card text-white bg-secondary mb-3 --xhr">
				<div class="card-body">
					<div class="row">
						<div class="col-3">
							<i class="mdi mdi-view-carousel mdi-3x"></i>
						</div>
						<div class="col">
							<h5 class="card-title mb-0">
								<?php echo phrase('carousels'); ?>
							</h5>
							<p class="card-text">
								<?php echo phrase('manage_carousel_slideshow'); ?>
							</p>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
	<div class="row">
		<div class="col-md-5">
			<a href="<?php echo base_url('cms/partials/faqs'); ?>" class="card text-white bg-secondary mb-3 --xhr">
				<div class="card-body">
					<div class="row">
						<div class="col-3">
							<i class="mdi mdi-help-circle-outline mdi-3x"></i>
						</div>
						<div class="col">
							<h5 class="card-title mb-0">
								<?php echo phrase('faqs'); ?>
							</h5>
							<p class="card-text">
								<?php echo phrase('manage_faqs'); ?>
							</p>
						</div>
					</div>
				</div>
			</a>
		</div>
		<div class="col-md-5">
			<a href="<?php echo base_url('cms/partials/media'); ?>" class="card text-white bg-secondary mb-3 --xhr">
				<div class="card-body">
					<div class="row">
						<div class="col-3">
							<i class="mdi mdi-folder-multiple-image mdi-3x"></i>
						</div>
						<div class="col">
							<h5 class="card-title mb-0">
								<?php echo phrase('media'); ?>
							</h5>
							<p class="card-text">
								<?php echo phrase('manage_uploaded_media'); ?>
							</p>
						</div>
					</div>
				</div>
			</a>
		</div>
	</div>
</div>