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
				<a href="<?php echo current_page('program'); ?>" class="p-2 text-light --xhr">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Pencocokan Referensi Program
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Pencocokan Referensi Program
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="card bg-info mb-3">
				<a href="<?php echo current_page('unit'); ?>" class="p-2 text-light --xhr">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Pencocokan Unit
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Pencocokan Unit
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="card bg-teal mb-3">
				<a href="<?php echo current_page('sub_unit'); ?>" class="p-2 text-light --xhr">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Pencocokan Sub Unit
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Pencocokan Sub Unit
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="card bg-warning mb-3">
				<a href="<?php echo current_page('rekening'); ?>" class="p-2 text-light --xhr">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Pencocokan Rekening
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Pencocokan Rekening
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="card bg-danger mb-3">
				<a href="<?php echo current_page('referensi_kegiatan'); ?>" class="p-2 text-light --xhr">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Pencocokan Referensi Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Pencocokan Referensi Kegiatan
							</p>
						</div>
					</div>
				</a>
			</div>
			<div class="card bg-maroon mb-3">
				<a href="<?php echo current_page('referensi_kegiatan_sub'); ?>" class="p-2 text-light --xhr">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-refresh mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								Pencocokan Referensi Sub Kegiatan
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Pencocokan Referensi Sub Kegiatan
							</p>
						</div>
					</div>
				</a>
			</div>
		</div>
		<div class="col-md-5">
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
						<form action="<?php echo current_page('kegiatan'); ?>" method="GET" class="--xhr-form">
							<!--
							<div class="form-group">
								<label class="d-block text-muted">
									Sub Unit
								</label>
								<select name="sub_unit" class="form-control" placeholder="Silakan pilih Sub Unit">
									<?php echo $list_sub_unit; ?>
								</select>
							</div>
							-->
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