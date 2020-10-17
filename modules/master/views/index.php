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
			<?php //echo $title; ?>
		</h3>
	</div>
	<div class="box-body animated zoomIn">
		<div class="row">
			<div class="col-md-8 col-md-offset-1">
				<div class="row">
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('data'); ?>" class="ajaxLoad">
							<div class="info-box bg-aqua">
								<span class="info-box-icon">
									<i class="fa fa-file-text-o"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('master_data'); ?>
									</span>
									<span class="info-box-number">
										<i class="fa fa-arrow-right pull-right"></i>
										<?php echo phrase('kelola_master_data'); ?>
										(<?php echo phrase('sub'); ?>)
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('rekening'); ?>" class="ajaxLoad">
							<div class="info-box bg-yellow">
								<span class="info-box-icon">
									<i class="fa fa-address-card-o"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('master_rekening'); ?>
									</span>
									<span class="info-box-number">
										<i class="fa fa-arrow-right pull-right"></i>
										<?php echo phrase('kelola_master_rekening'); ?>
										(<?php echo phrase('sub'); ?>)
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('tahun'); ?>" class="ajaxLoad">
							<div class="info-box bg-maroon">
								<span class="info-box-icon">
									<i class="fa fa-calendar"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('master_tahun'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_tahun_aktif'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('settings'); ?>" class="ajaxLoad">
							<div class="info-box bg-black">
								<span class="info-box-icon">
									<i class="fa fa-toggle-on"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('pengaturan_tambahan'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_pengaturan_tambahan'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('koneksi'); ?>" class="ajaxLoad">
							<div class="info-box bg-primary">
								<span class="info-box-icon">
									<i class="fa fa-check-square-o"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('master_koneksi'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('untuk_keperluan_sinkronisasi'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('sinkronisasi'); ?>" class="ajaxLoad">
							<div class="info-box bg-green">
								<span class="info-box-icon">
									<i class="fa fa-refresh fa-spin"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('sinkronisasi_dan_cronjob'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_sinkronisasi'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>