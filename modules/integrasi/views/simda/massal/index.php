<div class="container-fluid pt-3 pb-3">
	<div class="row" id="accordion">
		<div class="col-md-5 offset-md-1">
			<div class="card bg-danger mb-3">
				<a href="#collapse_1" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Sinkronisasi Referensi Program
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Sinkronisasi table Referensi Program
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_1" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Dengan mengklik tombol di bawah ini Anda akan menjalankan Sinkronisasi Referensi Program
						</div>
						<form action="<?php echo current_page('../sinkronisasi_referensi_program'); ?>" method="POST" class="--validate-form">
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-refresh"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card bg-primary mb-3">
				<a href="#collapse_2" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Sinkronisasi Transaksi Program
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Sinkronisasi table Transaksi Program
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_2" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Dengan mengklik tombol di bawah ini Anda akan menjalankan Sinkronisasi Transaksi Program
						</div>
						<form action="<?php echo current_page('../sinkronisasi_program'); ?>" method="POST" class="--validate-form">
							
							<?php echo $sub_unit; ?>
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-refresh"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card bg-warning mb-3">
				<a href="#collapse_3" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Sinkronisasi Referensi Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Sinkronisasi table Referensi Kegiatan
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_3" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Dengan mengklik tombol di bawah ini Anda akan menjalankan Sinkronisasi Referensi Kegiatan
						</div>
						<form action="<?php echo current_page('../sinkronisasi_referensi_kegiatan'); ?>" method="POST" class="--validate-form">
							
							<?php echo $kode_perubahan; ?>
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-refresh"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card bg-success mb-3">
				<a href="#collapse_4" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Sinkronisasi Transaksi Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Sinkronisasi table Transaksi Kegiatan
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_4" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Dengan mengklik tombol di bawah ini Anda akan menjalankan Sinkronisasi Transaksi Kegiatan
						</div>
						<form action="<?php echo current_page('../sinkronisasi_kegiatan'); ?>" method="POST" class="--validate-form">
							
							<?php echo $kode_perubahan; ?>
							
							<?php echo $sub_unit; ?>
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-refresh"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form">
				
				<?php echo $kode_perubahan; ?>
				
				<?php echo $kegiatan; ?>
				
				<div class="--validation-callback mb-0"></div>
				
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<button type="submit" class="btn btn-primary btn-block">
					<i class="mdi mdi-send"></i>
					Kirim ke SIMDA
				</button>
			</form>
		</div>
	</div>
</div>