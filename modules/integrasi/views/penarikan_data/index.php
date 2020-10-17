<div class="alert alert-warning border-0 rounded-0">
	<b class="text-danger">PERHATIAN!</b>
	<br />
	Sebelum Anda melakukan penarikan data pada tiap-tiap menu di bawah ini, pastikan Anda telah melakukan integrasi massal pada menu <a href="<?php echo current_page('../sipd/massal'); ?>" class="--xhr"><b>Integrasi Massal SIPD</b></a> sehingga data SIPD yang akan dipindahkan merupakan data SIPD yang terbaru.
</div>
<div class="container-fluid pt-3 pb-3">
	<div class="row" id="accordion">
		<div class="col-md-5 offset-md-1">
			<div class="card bg-primary mb-3">
				<a href="#collapse_referensi" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Data Referensi Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Penarikan data Referensi Kegiatan meliputi Program, Kegiatan dan Sub Kegiatan.
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_referensi" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Penarikan data Referensi Kegiatan meliputi Program, Kegiatan dan Sub Kegiatan.
						</div>
						<form action="<?php echo current_page('referensi'); ?>" method="POST" class="--validate-form">
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-check"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card bg-info mb-3">
				<a href="#collapse_unit" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Data Unit Organisasi
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Penarikan Unit Organisasi meliputi Unit, Sub Unit dan Program.
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_unit" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Unit meliputi Unit, Sub Unit dan Program.
						</div>
						<form action="<?php echo current_page('unit'); ?>" method="POST" class="--validate-form">
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-check"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card bg-warning mb-3">
				<a href="#collapse_rekening" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Data Rekening
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Penarikan Rekening apa aja.
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_rekening" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Rekening apa aja.
						</div>
						<form action="<?php echo current_page('rekening'); ?>" method="POST" class="--validate-form">
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-check"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
			<div class="card bg-dark mb-3">
				<a href="#collapse_sumber_dana" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Data Sumber Dana
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Penarikan Sumber Dana meliputi xxx
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_sumber_dana" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Penarikan Sumber Dana meliputi xxx
						</div>
						<form action="<?php echo current_page('sumber_dana'); ?>" method="POST" class="--validate-form">
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-check"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card bg-secondary mb-3">
				<a href="#collapse_standar_harga" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Data Standar Harga
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Penarikan Standar Harga meliputi xxx
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_standar_harga" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Penarikan Standar Harga meliputi xxx
						</div>
						<form action="<?php echo current_page('standar_harga'); ?>" method="POST" class="--validate-form">
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-check"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card bg-success mb-3">
				<a href="#collapse_kegiatan" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Data Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Penarikan Kegiatan meliputi Program, Kegiatan dan Sub Kegiatan.
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_kegiatan" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Kegiatan meliputi Program, Kegiatan dan Sub Kegiatan.
						</div>
						<form action="<?php echo current_page('kegiatan'); ?>" method="POST" class="--validate-form">
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-check"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>