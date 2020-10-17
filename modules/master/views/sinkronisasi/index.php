<div class="container-fluid pt-3 pb-3">
	<div class="row" id="accordion">
		<div class="col-md-5 offset-md-1">
			<div class="card bg-info mb-3">
				<a href="#collapse_1" class="card-header p-2 text-light" data-toggle="collapse">
					<div class="row">
						<div class="col-2">
							<i class="mdi mdi-check mdi-3x"></i>
						</div>
						<div class="col-10">
							<h5 class="mb-0 text-truncate">
								SIPD Kemendagri
							</h5>
							<p class="mb-0 text-truncate">
								<i class="mdi mdi-arrow-right float-right"></i>
								Sinkronisasi data Pemda ke SIPD Kemendagri
							</p>
						</div>
					</div>
				</a>
				<div id="collapse_1" class="collapse" data-parent="#accordion">
					<div class="card-body bg-white p-3">
						<div class="alert alert-info">
							Anda akan melakukan sinkronisasi data Pemda ke SIPD Kemendagri
						</div>
						<form action="<?php echo current_page('sipd_kemendagri'); ?>" method="POST" class="--validate-form">
							
							<?php echo $unit; ?>
							
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<label class="d-block text-muted text-uppercase">
											Tahun
										</label>
										<?php
											$option			= null;
											foreach($tahun as $key => $val)
											{
												$option		.= '<option value="' . $val->tahun . '"' . ($val->tahun == get_userdata('year') ? ' selected' : ($val->default == 1 ? ' selected' : null)) . '>' . $val->tahun . '</option>';
											}
										?>
										<select name="tahun" class="form-control form-control-sm" placeholder="Silakan pilih tahun">
											<?php echo $option; ?>
										</select>
									</div>
								</div>
							</div>
							<div class="--validation-callback mb-0"></div>
							<div class="row">
								<div class="col-sm-4">
									<div class="form-group">
										<input type="hidden" name="token" value="<?php echo $token; ?>" />
										<button type="submit" class="btn btn-primary btn-block">
											<i class="mdi mdi-check"></i>
											Kirim
										</button>
									</div>
								</div>
							</div>
						</form>
					</div>
				</div>
			</div>
		</div>
		<div class="col-md-5">
		</div>
	</div>
</div>