<?php
	$list_sub_unit									= '<option value="0">Semua Sub Unit (Lambat)</option>';
	
	if($sub_unit)
	{
		foreach($sub_unit as $key => $val)
		{
			$list_sub_unit							.= '<option value="' . $val->id . '">' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '.' . $val->kd_urusan_2 . '.' . sprintf('%02d', $val->kd_bidang_2) . '.' . $val->kd_urusan_3 . '.' . sprintf('%02d', $val->kd_bidang_3) . '.' . sprintf('%02d', $val->kd_unit) . '.' . sprintf('%02d', $val->kd_sub) . ' - ' . $val->nm_sub;
		}
	}
?>
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
								Integrasi Referensi Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Integrasi Referensi Kegiatan meliputi Program, Kegiatan dan Sub Kegiatan.
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_referensi" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Referensi Kegiatan meliputi Program, Kegiatan dan Sub Kegiatan.
						</div>
						<form action="<?php echo current_page('referensi_kegiatan'); ?>" method="POST" class="--validate-form">
							
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
								Integrasi Unit Organisasi
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Integrasi Unit meliputi Unit dan Sub Unit.
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_unit" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Unit meliputi Unit dan Sub Unit.
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
								Integrasi Rekening
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Integrasi Rekening meliputi Akun, Kelompok dll
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_rekening" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Rekening meliputi Akun, Kelompok dll
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
								Integrasi Sumber Dana
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Integrasi Sumber Dana xxx
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_sumber_dana" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Sumber Dana xxx
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
								Integrasi Standar Harga
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Integrasi Standar Harga xxx
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_standar_harga" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Standar Harga xxx
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
								Integrasi Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Integrasi Kegiatan meliputi xxx
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_kegiatan" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Kegiatan meliputi xxx
						</div>
						<form action="<?php echo current_page('kegiatan'); ?>" method="POST" class="--validate-form">
							<div class="form-group">
								<label class="d-block text-muted">
									Sub Unit
								</label>
								<select name="sub_unit" class="form-control" placeholder="Silakan pilih Sub Unit">
									<?php echo $list_sub_unit; ?>
								</select>
							</div>
							
							<div class="--validation-callback mb-0"></div>
							
							<button type="submit" class="btn btn-primary btn-block">
								<i class="mdi mdi-check"></i>
								Eksekusi
							</button>
						</form>
					</div>
				</div>
			</div>
			<div class="card bg-danger mb-3">
				<a href="#collapse_detail_kegiatan" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Integrasi Detail Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Integrasi Detail Kegiatan
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_detail_kegiatan" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Integrasi Detail Kegiatan
						</div>
						<form action="<?php echo current_page('detail_kegiatan'); ?>" method="POST" class="--validate-form">
							<div class="form-group">
								<label class="d-block text-muted">
									Sub Unit
								</label>
								<select name="sub_unit" class="form-control" placeholder="Silakan pilih Sub Unit">
									<?php echo $list_sub_unit; ?>
								</select>
							</div>
							
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