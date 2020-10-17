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
			<button type="button" class="btn btn-box-tool" data-widget="remove">
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
			<div class="col-md-8 col-md-offset-1">
				<div class="row">
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('urusan'); ?>" class="ajaxLoad">
							<div class="info-box bg-aqua">
								<span class="info-box-icon">
									<i class="fa fa-check-square-o"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('data_urusan'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_data_urusan'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('bidang'); ?>" class="ajaxLoad">
							<div class="info-box bg-yellow">
								<span class="info-box-icon">
									<i class="fa fa-sitemap"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('bidang'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_bidang'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('units'); ?>" class="ajaxLoad">
							<div class="info-box bg-red">
								<span class="info-box-icon">
									<i class="fa fa-institution"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('data_unit'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_data_unit'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('sub_units'); ?>" class="ajaxLoad">
							<div class="info-box bg-teal">
								<span class="info-box-icon">
									<i class="fa fa-home"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('data_sub_unit'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_data_sub_unit'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row form-group">
					<div class="col-md-12">
						<a href="<?php echo go_to('program'); ?>" class="ajaxLoad">
							<div class="info-box bg-success">
								<span class="info-box-icon">
									<i class="fa fa-list-ol"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('program'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_program'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<div class="btn-group">
							<a href="<?php echo go_to('../'); ?>" class="btn btn-default ajaxLoad">
								<i class="fa fa-chevron-left"></i>
							</a>
							<a href="<?php echo go_to('../'); ?>" class="btn btn-default ajaxLoad">
								<?php echo phrase('back_to'); ?> Master
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>