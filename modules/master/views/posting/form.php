<div class="container-fluid pt-3 pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-10 offset-md-1">
				<?php echo $sub_kegiatan; ?>
				<div class="row">
					<div class="col-sm-3">
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="1" checked>
							1. Rancangan Awal Rencana Kerja PD
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="2">
							2. Rancangan Rencana Kerja PD
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="3">
							3. Rancangan Akhir Rencana Kerja PD
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="4">
							4. Rancangan Awal RKPD
						</label>
					</div>
					<div class="col-sm-3">
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="5">
							5. Rancangan Akhir RKPD
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="6">
							6. Rancangan KUA PPAS
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="7">
							7. KUA PPAS
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="8">
							8. RAPBD
						</label>
					</div>
					<div class="col-sm-3">
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="9">
							9. APBD
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="10">
							10. Perubahan Penjabaran 1
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="11">
							11. Perubahan Penjabaran 2
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="12">
							12. Perubahan Penjabaran 3
						</label>
					</div>
					<div class="col-sm-3">
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="13">
							13. Perubahan Penjabaran 4
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="14">
							14. Perubahan Penjabaran 5
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="15">
							15. Perubahan Penjabaran 6
						</label>
						<label class="d-block text-sm">
							<input type="radio" name="kode_perubahan" value="20">
							16. Perubahan APBD
						</label>
					</div>
				</div>
				<div class="--validation-callback mb-0"></div>
			</div>
		</div>
		<div class="row opt-btn">
			<div class="col-md-10 offset-md-1">
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<a href="<?php echo current_page('../'); ?>" class="btn btn-link --xhr">
					<i class="mdi mdi-arrow-left"></i>
					<?php echo phrase('back'); ?>
				</a>
				<button type="submit" class="btn btn-primary float-right">
					<i class="mdi mdi-check"></i>
					<?php echo phrase('posting'); ?>
					<em class="text-sm">(ctrl+s)</em>
				</button>
			</div>
		</div>
		<div class="row">
			<div class="col-md-10 offset-md-1">
				<div class="alert alert-info" style="margin-top:10px">
					Catatan : Ketika posting ke tahapan selanjutnya, agar di posting semua terlebih dahulu
				</div>
			</div>
		</div>
	</form>
</div>