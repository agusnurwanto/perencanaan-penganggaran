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
			<div class="col-sm-5 col-sm-offset-1">
				<div class="panel-group" id="left">
					<div class="panel panel-primary">
						<div class="panel-heading no-padding">
							<a data-toggle="collapse" data-parent="#left" href="#collapse_1">
								<div class="info-box bg-primary no-margin">
									<span class="info-box-icon">
										<i class="fa fa-sticky-note-o"></i>
									</span>
									<div class="info-box-content">
										<span class="info-box-number">
											Perbarui Nilai Variabel
										</span>
									</div>
								</div>
							</a>
						</div>
						<div id="collapse_1" class="panel-collapse collapse">
							<div class="panel-body">
								<div class="form-group alert alert-info">
									Dengan mengklik tombol di bawah ini Anda akan memperbarui Nilai Usulan, Kelurahan, Kecamatan dan SKPD berdasarkan nilai variabel yang telah diperbarui.
								</div>
								<a href="<?php echo go_to('update_nilai'); ?>" class="btn btn-primary btn-block ajaxLoad show_process">
									<i class="fa fa-refresh"></i>
									Eksekusi
								</a>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>