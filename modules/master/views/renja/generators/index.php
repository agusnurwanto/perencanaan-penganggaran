<div class="container-fluid pt-3 pb-3">
	<div class="row" id="accordion">
		<div class="col-md-5 col-md-offset-1">
			<div class="card bg-yellow mb-3">
				<a href="#collapse_left_1" class="card-header p-2 text-secondary" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-update mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Menyesuaikan Judul
							</h5>
							<p class="mb-0">
								dan Pagu sesuai dengan Master Jenis Pekerjaan
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_left_1" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Dengan mengklik tombol di bawah ini Anda akan Menyesuaikan Judul dan Pagu sesuai dengan Master Jenis Pekerjaan
						</div>
						<a href="<?php echo current_page('execute'); ?>" class="btn btn-primary btn-block --xhr show-progress">
							<i class="mdi mdi-sync"></i>
							Eksekusi
						</a>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="card bg-warning mb-3">
				<a href="#collapse_right_1" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-barcode mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Update Kode
							</h5>
							<p class="mb-0">
								dalam table kegiatan
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_right_1" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-danger">
							<b>
								Perhatian!
							</b>
							<p class="text-sm text-danger">
								Tindakan ini akan mengubah seluruh kode kegiatan dalam tabel kegiatan. Pastikan menekan tombol hanya apabila Anda ingin mengubah seluruh kode kegiatan...
							</p>
						</div>
						<a href="<?php echo current_page('kegiatan'); ?>" class="btn btn-primary --xhr show-progress">
							<i class="mdi mdi-sync"></i>
							Jalankan Query
						</a>
					</div>
				</div>
			</div>
			<div class="card bg-info mb-3">
				<a href="#collapse_right_2" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-qrcode-edit mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Update Kode
							</h5>
							<p class="mb-0">
								dalam table indikator
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_right_2" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-danger">
							<b>
								Perhatian!
							</b>
							<p class="text-sm text-danger">
								Tindakan ini akan mengubah seluruh kode indikator dalam tabel indikator. Pastikan menekan tombol hanya apabila Anda ingin mengubah seluruh kode indikator...
							</p>
						</div>
						<a href="<?php echo current_page('indikator'); ?>" class="btn btn-primary --xhr show-progress">
							<i class="mdi mdi-sync"></i>
							Jalankan Query
						</a>
					</div>
				</div>
			</div>
			<div class="card bg-success mb-3">
				<a href="#collapse_right_3" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-table-edit mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Update Kode
							</h5>
							<p class="mb-0">
								dalam table capaian program
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_right_3" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-danger">
							<b>
								Perhatian!
							</b>
							<p class="text-sm text-danger">
								Tindakan ini akan mengubah seluruh kode capaian program dalam tabel capaian program. Pastikan menekan tombol hanya apabila Anda ingin mengubah seluruh kode capaian program...
							</p>
						</div>
						<a href="<?php echo current_page('capaian_program'); ?>" class="btn btn-primary --xhr show-progress">
							<i class="mdi mdi-sync"></i>
							Jalankan Query
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>