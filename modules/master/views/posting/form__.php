<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo phrase('submitting'); ?>" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" data-icon="check" enctype="multipart/form-data">
	<div class="box no-border">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
				<a href="<?php echo current_page(); ?>" class="btn btn-box-tool ajaxLoad show_process">
					<i class="fa fa-refresh"></i>
				</a>
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
		<div class="box-body">
			<div class="row">
				<div class="col-md-9 col-md-offset-1">
					<?php echo $kegiatan; ?>
				</div>
			</div>
			<div class="row">
				<div class="col-md-9 col-md-offset-1">
					<div class="row">
						<div class="col-sm-4">
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="1" checked>
								1. Rancangan Awal Rencana Kerja PD
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="2">
								2. Rancangan Rencana Kerja PD
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="3">
								3. Rancangan Akhir Rencana Kerja PD
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="4">
								4. Rancangan Awal RKPD
							</label>
						</div>
						<div class="col-sm-4">
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="5">
								5. Rancangan Akhir RKPD
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="6">
								6. Rancangan KUA PPAS
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="7">
								7. KUA PPAS
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="8">
								8. RAPBD
							</label>
						</div>
						<div class="col-sm-4">
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="9">
								9. APBD
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="10">
								10. Perubahan Penjabaran 1
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="11">
								11. Perubahan Penjabaran 2
							</label>
							<label class="big-label text-sm">
								<input type="radio" name="kode_perubahan" value="20">
								12. Perubahan APBD
							</label>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="row">
				<div class="col-md-5 col-md-offset-1 callback-status">
					<div class="btn-group btn-group-justified">
						<a href="<?php echo go_to(null, array('id' => null)); ?>" class="btn btn-primary btn-holo ajaxLoad">
							<i class="fa fa-chevron-left"></i>
							&nbsp;
							<?php echo phrase('back'); ?>
						</a>
						<div class="btn-group">
							<input type="hidden" name="token" value="<?php echo $token; ?>" />
							<button type="submit" class="btn btn-primary btn-holo submitBtn">
								<i class="fa fa-check"></i>
								&nbsp;
								Posting
								<small class="hidden-xs hidden-sm" style="font-size:10px;color:#cacaca">(CTRL+S)</small>
							</button>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</form>