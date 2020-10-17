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
						<a href="<?php echo go_to('akun'); ?>" class="ajaxLoad">
							<div class="info-box bg-aqua">
								<span class="info-box-icon">
									<i class="fa fa-id-card-o"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('akun'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_rekening_akun'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('kelompok'); ?>" class="ajaxLoad">
							<div class="info-box bg-yellow">
								<span class="info-box-icon">
									<i class="fa fa-cubes"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('kelompok'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_rekening_kelompok'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-12">
						<a href="<?php echo go_to('jenis_belanja'); ?>" class="ajaxLoad">
							<div class="info-box bg-green">
								<span class="info-box-icon">
									<i class="fa fa-shopping-cart"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('jenis_belanja'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_jenis_belanja'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('objek_belanja'); ?>" class="ajaxLoad">
							<div class="info-box bg-red">
								<span class="info-box-icon">
									<i class="fa fa-cart-plus"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('objek_belanja'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_objek_belanja'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
					<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('rincian_objek'); ?>" class="ajaxLoad">
							<div class="info-box bg-teal">
								<span class="info-box-icon">
									<i class="fa fa-check-circle-o"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('rincian_objek'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_rincian_objek'); ?>
									</span>
								</div>
							</div>
						</a>
					</div>
										<div class="col-md-6 col-md-6 col-xs-12">
						<a href="<?php echo go_to('sub_rincian_objek'); ?>" class="ajaxLoad">
							<div class="info-box bg-teal">
								<span class="info-box-icon">
									<i class="fa fa-check-circle-o"></i>
								</span>
								<div class="info-box-content">
									<span class="info-box-text">
										<?php echo phrase('sub_rincian_objek'); ?>
									</span>
									<span class="info-box-number">
										<?php echo phrase('kelola_sub_rincian_objek'); ?>
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