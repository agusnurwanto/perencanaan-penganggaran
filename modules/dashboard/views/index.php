<style type="text/css">
	.nav-tabs .nav-link.active
	{
		color: #fff;
		background: #007bff!important;
		border-bottom: 1px solid #007bff!important
	}
</style>

<div class="container-fluid">
	<div style="margin-right:-15px; margin-left:-15px">
		<nav style="margin-top:-1px; margin-right:-1px; margin-left:-1px">
			<div class="nav nav-tabs" id="nav-tab" role="tablist">
				<a class="nav-item nav-link rounded-0 active" data-toggle="tab" href="#tab-1" role="tab">
					Perencanaan
				</a>
				<a class="nav-item nav-link rounded-0" data-toggle="tab" href="#tab-2" role="tab">
					Anggaran
				</a>
				<a class="nav-item nav-link rounded-0" data-toggle="tab" href="#tab-3" role="tab">
					Realisasi
				</a>
			</div>
		</nav>
		<div class="tab-content" id="nav-tabContent1" style="margin-top:-1px">
			<div class="tab-pane show active border-top border-bottom p-3" id="tab-1" role="tabpanel">
				<div class="row border-bottom pt-3">
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/blogs'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Pendapatan'; ?>">
							<div class="card border-0 bg-primary text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-newspaper mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Pendapatan
											<br />
											<?php echo number_format_indo($card->pendapatan, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/pages'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Belanja'; ?>">
							<div class="card border-0 bg-info text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-file-multiple mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Belanja
											<br />
											<?php echo number_format_indo($card->belanja, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 40%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/galleries'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Penerimaan Pembiayaan'; ?>">
							<div class="card border-0 bg-danger text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-folder-multiple-image mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Penerimaan Pembiayaan
											<br />
											<?php echo number_format_indo($card->pembiayaan_penerimaan, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/peoples'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Pengeluaran Pembiayaan'; ?>">
							<div class="card border-0 bg-dark text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-account-group-outline mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Pengeluaran Pembiayaan
											<br />
											<?php echo number_format_indo($card->pembiayaan_pengeluaran, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="90">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="border rounded p-1 mb-5">
							<div id="chart-1" class="rounded" style="width:100%; height:300px"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-1 mb-5">
							<div class="text-center">
								<div class="btn-group btn-group-xs buttons">
									<button type="button" id="2000" class="btn btn-light">
										2000
									</button>
									<button type="button" id="2004" class="btn btn-light">
										2004
									</button>
									<button type="button" id="2008" class="btn btn-light">
										2008
									</button>
									<button type="button" id="2012" class="btn btn-light">
										2012
									</button>
									<button type="button" id="2016" class="btn btn-light active">
										2016
									</button>
								</div>
							</div>
							<div id="chart-2" class="rounded" style="width:100%; height:300px"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane border-top border-bottom p-3" id="tab-2" role="tabpanel">
				<div class="row border-bottom pt-3">
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/blogs'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Pendapatan'; ?>">
							<div class="card border-0 bg-primary text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-newspaper mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Pendapatan
											<br />
											<?php echo number_format_indo($card->pendapatan, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/pages'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Belanja'; ?>">
							<div class="card border-0 bg-info text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-file-multiple mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Belanja
											<br />
											<?php echo number_format_indo($card->belanja, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 40%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/galleries'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Penerimaan Pembiayaan'; ?>">
							<div class="card border-0 bg-danger text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-folder-multiple-image mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Penerimaan Pembiayaan
											<br />
											<?php echo number_format_indo($card->pembiayaan_penerimaan, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/peoples'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Pengeluaran Pembiayaan'; ?>">
							<div class="card border-0 bg-dark text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-account-group-outline mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Pengeluaran Pembiayaan
											<br />
											<?php echo number_format_indo($card->pembiayaan_pengeluaran, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="90">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="border rounded p-1 mb-5">
							<div id="chart-3" class="rounded" style="width:100%; height:300px"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-1 mb-5">
							<div id="chart-4" class="rounded" style="width:100%; height:300px"></div>
						</div>
					</div>
				</div>
			</div>
			<div class="tab-pane border-top border-bottom p-3" id="tab-3" role="tabpanel">
				<div class="row border-bottom pt-3">
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/blogs'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Pendapatan'; ?>">
							<div class="card border-0 bg-primary text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-newspaper mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Pendapatan
											<br />
											<?php echo number_format_indo($card->pendapatan, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 10%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/pages'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Belanja'; ?>">
							<div class="card border-0 bg-info text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-file-multiple mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Belanja
											<br />
											<?php echo number_format_indo($card->belanja, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 40%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/galleries'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Penerimaan Pembiayaan'; ?>">
							<div class="card border-0 bg-danger text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-folder-multiple-image mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Penerimaan Pembiayaan
											<br />
											<?php echo number_format_indo($card->pembiayaan_penerimaan, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="100">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
					<div class="col-6 col-lg-3 mb-3">
						<a href="<?php //echo base_url('cms/peoples'); ?>" class="d-block --xhr" data-toggle="tooltip" title="<?php echo 'Anggaran Pengeluaran Pembiayaan'; ?>">
							<div class="card border-0 bg-dark text-center text-sm-left" style="overflow:hidden">
								<div class="row">
									<div class="col-sm-4">
										<div class="p-3 text-center" style="background:rgba(0, 0, 0, .1)">
											<i class="mdi mdi-account-group-outline mdi-2x text-light"></i>
										</div>
									</div>
									<div class="col-sm-8 p-0">
										<h5 class="m-0 text-truncate text-light">
											Pengeluaran Pembiayaan
											<br />
											<?php echo number_format_indo($card->pembiayaan_pengeluaran, 2); ?>
										</h5>
										<div class="progress mt-2" style="height: 6px">
											<div class="progress-bar bg-warning progress-bar-striped progress-bar-animated" role="progressbar" style="width: 80%" aria-valuenow="10" aria-valuemin="0" aria-valuemax="90">
											</div>
										</div>
									</div>
								</div>
							</div>
						</a>
					</div>
				</div>
				<div class="row">
					<div class="col-md-6">
						<div class="border rounded p-1 mb-5">
							<div id="chart-5" class="rounded" style="width:100%; height:300px"></div>
						</div>
					</div>
					<div class="col-md-6">
						<div class="border rounded p-1 mb-5">
							<div class="row no-gutters">
								<div class="col-6">
									<div id="chart-speed" style="width:100%; height:300px"></div>
								</div>
								<div class="col-6">
									<div id="chart-rpm" style="width:100%; height:300px"></div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
	<div class="row">
		<div class="col-lg-8 bg-white border-right" style="margin-right:-1px">
			<div class="sticky-top pt-3" style="top:86px; z-index:0">
				<div class="border rounded p-1 mb-5">
					<div id="visitor-chart" class="rounded" style="width:100%; height:300px"></div>
				</div>
				<div class="row">
					<div class="col-md-6 mb-3">
						<div class="card">
							<div class="card-header bg-white border-0">
								<h5 class="card-title mb-0">
									<?php echo phrase('most_used_browser'); ?>
								</h5>
							</div>
							<div class="card-body p-3">
								<?php
									$num	= 0;
									foreach($visitors->browsers as $key => $val)
									{
										echo '
											' . ($num ? '<hr class="mt-2 mb-2" />' : null) . '
											<div class="row no-gutters">
												<div class="col-3 col-sm-2">
													<i class="mdi mdi-' . ($key == 'chrome' ? 'google-chrome text-success' : ($key == 'firefox' ? 'firefox text-warning' : ($key == 'safari' ? 'apple-safari text-primary' : ($key == 'edge' ? 'edge text-primary' : ($key == 'opera' ? 'opera text-danger' : ($key == 'explorer' ? 'internet-explorer text-info' : 'web text-muted')))))) . ' mdi-3x"></i>
												</div>
												<div class="col-9 col-sm-10 pl-3">
													<b>
														' . ($key == 'chrome' ? phrase('google_chrome') : ($key == 'firefox' ? phrase('mozilla_firefox') : ($key == 'safari' ? phrase('safari') : ($key == 'edge' ? phrase('microsoft_edge') : ($key == 'opera' ? phrase('opera') : ($key == 'explorer' ? phrase('internet_explorer') : phrase('other'))))))) . '
													</b>
													<p class="mb-0 text-sm text-muted">
														' . number_format($val) . ' ' . phrase('usage_in_a_week') . '
													</p>
												</div>
											</div>
										';
										$num++;
									}
								?>
							</div>
						</div>
					</div>
					<div class="col-md-6 mb-3">
						<div class="card">
							<div class="card-header bg-white border-0">
								<h5 class="card-title mb-0">
									<?php echo phrase('recent_signed_users'); ?>
								</h5>
							</div>
							<div class="card-body p-3">
								<?php
									foreach($recent_signed as $key => $val)
									{
										echo '
											' . ($key ? '<hr class="mt-2 mb-2" />' : null) . '
											<div class="row no-gutters">
												<div class="col-3 col-sm-2">
													<img src="' . get_image('users', $val->photo, 'icon') . '" class="img-fluid rounded" />
												</div>
												<div class="col-9 col-sm-10 pl-3">
													<b>
														' . $val->first_name . ' ' . $val->last_name . '
													</b>
													<p class="mb-0 text-sm text-muted">
														' . $val->group_name . '
													</p>
												</div>
											</div>
										';
									}
								?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="col-lg-4 bg-white border-left" style="margin-left:-1px">
			<div class="sticky-top pt-3" style="top:86px; z-index:0">
				<div class="form-group border-bottom">
					<label class="d-block text-muted">
						<?php echo strtoupper(phrase('aksara_version')); ?>
					</label>
					<p>
						<?php echo get_setting('aksara_version'); ?>
					</p>
				</div>
				<div class="form-group border-bottom">
					<label class="d-block text-muted">
						<?php echo strtoupper(phrase('build_version')); ?>
					</label>
					<p>
						<?php echo get_setting('build_version'); ?>
					</p>
				</div>
				<!--<div class="form-group border-bottom">
					<label class="d-block text-muted">
						<?php //echo strtoupper(phrase('installation_path')); ?>
					</label>
					<p>
						<?php //echo (isset($directory->path) ? $directory->path : null); ?>
					</p>
				</div>-->
				<div class="form-group border-bottom">
					<label class="d-block text-muted">
						<?php echo strtoupper(phrase('uploaded_file')); ?>
					</label>
					<p class="uploaded-file">
						0
					</p>
				</div>
				<div class="form-group border-bottom">
					<label class="d-block text-muted">
						<?php echo strtoupper(phrase('system_language')); ?>
					</label>
					<p>
						<?php echo (isset($system_language) ? $system_language : null); ?>
					</p>
				</div>
				<div class="form-group">
					<label class="d-block text-muted">
						<?php echo strtoupper(phrase('membership')); ?>
					</label>
					<p>
						<?php echo (get_setting('enable_frontend_registration') ? '<span class="badge badge-success">' . phrase('enabled') . '</span>' : '<span class="badge badge-danger">' . phrase('disabled') . '</span>'); ?>
					</p>
				</div>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		if(typeof interval !== 'undefined')
		{
			clearInterval(interval)
		}
		
		var interval;
		
		require.js('<?php echo asset_url('highcharts/highcharts.min.js'); ?>', function()
		{
			var categories					= [
				'0-4', '5-9', '10-14', '15-19',
				'20-24', '25-29', '30-34', '35-39', '40-44',
				'45-49', '50-54', '55-59', '60-64', '65-69',
				'70-74', '75-79', '80-84', '85-89', '90-94',
				'95-99', '100 + '
			];
			
			Highcharts.chart('chart-1', {
				chart: {
					type: 'bar'
				},
				title: {
					text: 'Rekapitulasi Anggaran per SKPD'
				},
				subtitle: {
					text: 'Sumber: <a href="https://siencang.bekasikota.go.id/2021/dashboard/">Berdasarkan Hasil isian Anggaran pada Hari ini</a>'
				},
				accessibility: {
					point: {
						valueDescriptionFormat: '{index}. Age {xDescription}, {value}%.'
					}
				},
				xAxis: [{
					categories: categories,
					reversed: false,
					labels: {
						step: 1
					},
					accessibility: {
						description: 'Age (male)'
					}
				},
				{
					opposite: true,
					reversed: false,
					categories: categories,
					linkedTo: 0,
					labels: {
						step: 1
					},
					accessibility: {
						description: 'Age (female)'
					}
				}],
				yAxis: {
					title: {
						text: null
					},
					labels: {
						formatter: function () {
							return Math.abs(this.value) + '%';
						}
					},
					accessibility: {
						description: 'Percentage population',
						rangeDescription: 'Range: 0 to 5%'
					}
				},

				plotOptions: {
					series: {
						stacking: 'normal'
					}
				},

				tooltip: {
					formatter: function () {
						return '<b>' + this.series.name + ', age ' + this.point.category + '</b><br/>' +
							'Population: ' + Highcharts.numberFormat(Math.abs(this.point.y), 1) + '%';
					}
				},

				series: [{
					name: 'Male',
					data: [
						-2.2, -2.1, -2.2, -2.4,
						-2.7, -3.0, -3.3, -3.2,
						-2.9, -3.5, -4.4, -4.1,
						-3.4, -2.7, -2.3, -2.2,
						-1.6, -0.6, -0.3, -0.0,
						-0.0
					]
				}, {
					name: 'Female',
					data: [
						2.1, 2.0, 2.1, 2.3, 2.6,
						2.9, 3.2, 3.1, 2.9, 3.4,
						4.3, 4.0, 3.5, 2.9, 2.5,
						2.7, 2.2, 1.1, 0.6, 0.2,
						0.0
					]
				}]
			});
			
			var dataPrev = {
				2016: [
					['South Korea', 0],
					['Japan', 0],
					['Australia', 0],
					['Germany', 11],
					['Russia', 24],
					['China', 38],
					['Great Britain', 29],
					['United States', 46]
				],
				2012: [
					['South Korea', 13],
					['Japan', 0],
					['Australia', 0],
					['Germany', 0],
					['Russia', 22],
					['China', 51],
					['Great Britain', 19],
					['United States', 36]
				],
				2008: [
					['South Korea', 0],
					['Japan', 0],
					['Australia', 0],
					['Germany', 13],
					['Russia', 27],
					['China', 32],
					['Great Britain', 9],
					['United States', 37]
				],
				2004: [
					['South Korea', 0],
					['Japan', 5],
					['Australia', 16],
					['Germany', 0],
					['Russia', 32],
					['China', 28],
					['Great Britain', 0],
					['United States', 36]
				],
				2000: [
					['South Korea', 0],
					['Japan', 0],
					['Australia', 9],
					['Germany', 20],
					['Russia', 26],
					['China', 16],
					['Great Britain', 0],
					['United States', 44]
				]
			};

			var data = {
				2016: [
					['South Korea', 0],
					['Japan', 0],
					['Australia', 0],
					['Germany', 17],
					['Russia', 19],
					['China', 26],
					['Great Britain', 27],
					['United States', 46]
				],
				2012: [
					['South Korea', 13],
					['Japan', 0],
					['Australia', 0],
					['Germany', 0],
					['Russia', 24],
					['China', 38],
					['Great Britain', 29],
					['United States', 46]
				],
				2008: [
					['South Korea', 0],
					['Japan', 0],
					['Australia', 0],
					['Germany', 16],
					['Russia', 22],
					['China', 51],
					['Great Britain', 19],
					['United States', 36]
				],
				2004: [
					['South Korea', 0],
					['Japan', 16],
					['Australia', 17],
					['Germany', 0],
					['Russia', 27],
					['China', 32],
					['Great Britain', 0],
					['United States', 37]
				],
				2000: [
					['South Korea', 0],
					['Japan', 0],
					['Australia', 16],
					['Germany', 13],
					['Russia', 32],
					['China', 28],
					['Great Britain', 0],
					['United States', 36]
				]
			};

			var countries = [{
				name: 'South Korea',
				flag: 197582,
				color: 'rgb(201, 36, 39)'
			}, {
				name: 'Japan',
				flag: 197604,
				color: 'rgb(201, 36, 39)'
			}, {
				name: 'Australia',
				flag: 197507,
				color: 'rgb(0, 82, 180)'
			}, {
				name: 'Germany',
				flag: 197571,
				color: 'rgb(0, 0, 0)'
			}, {
				name: 'Russia',
				flag: 197408,
				color: 'rgb(240, 240, 240)'
			}, {
				name: 'China',
				flag: 197375,
				color: 'rgb(255, 217, 68)'
			}, {
				name: 'Great Britain',
				flag: 197374,
				color: 'rgb(0, 82, 180)'
			}, {
				name: 'United States',
				flag: 197484,
				color: 'rgb(215, 0, 38)'
			}];


			function getData(data) {
				return data.map(function (country, i) {
					return {
						name: country[0],
						y: country[1],
						color: countries[i].color
					};
				});
			}

			var chart = Highcharts.chart('chart-2', {
				chart: {
					type: 'column'
				},
				title: {
					text: 'Summer Olympics 2016 - Top 5 countries by Gold medals'
				},
				subtitle: {
					text: 'Comparing to results from Summer Olympics 2012 - Source: <ahref="https://en.wikipedia.org/wiki/2016_Summer_Olympics_medal_table">Wikipedia</a>'
				},
				plotOptions: {
					series: {
						grouping: false,
						borderWidth: 0
					}
				},
				legend: {
					enabled: false
				},
				tooltip: {
					shared: true,
					headerFormat: '<span style="font-size: 15px">{point.point.name}</span><br/>',
					pointFormat: '<span style="color:{point.color}">\u25CF</span> {series.name}: <b>{point.y} medals</b><br/>'
				},
				xAxis: {
					type: 'category',
					max: 4,
					labels: {
						useHTML: true,
						animate: true,
						formatter: function () {
							var value = this.value,
								output;

							countries.forEach(function (country) {
								if (country.name === value) {
									output = country.flag;
								}
							});

							return '<span><img src="https://image.flaticon.com/icons/svg/197/' + output + '.svg" style="width: 40px; height: 40px;"/><br></span>';
						}
					}
				},
				yAxis: [{
					title: {
						text: 'Gold medals'
					},
					showFirstLabel: false
				}],
				series: [{
					color: 'rgb(158, 159, 163)',
					pointPlacement: -0.2,
					linkedTo: 'main',
					data: dataPrev[2016].slice(),
					name: '2012'
				}, {
					name: '2016',
					id: 'main',
					dataSorting: {
						enabled: true,
						matchByName: true
					},
					dataLabels: [{
						enabled: true,
						inside: true,
						style: {
							fontSize: '16px'
						}
					}],
					data: getData(data[2016]).slice()
				}],
				exporting: {
					allowHTML: true
				}
			});

			var years = [2016, 2012, 2008, 2004, 2000];

			years.forEach(function (year) {
				var btn = document.getElementById(year);

				btn.addEventListener('click', function () {

					document.querySelectorAll('.buttons button.active').forEach(function (active) {
						active.className = 'btn btn-light';
					});
					btn.className = 'btn btn-light active';

					chart.update({
						title: {
							text: 'Summer Olympics ' + year + ' - Top 5 countries by Gold medals'
						},
						subtitle: {
							text: 'Comparing to results from Summer Olympics ' + (year - 4) + ' - Source: <ahref="https://en.wikipedia.org/wiki/' + (year) + '_Summer_Olympics_medal_table">Wikipedia</a>'
						},
						series: [{
							name: year - 4,
							data: dataPrev[year].slice()
						}, {
							name: year,
							data: getData(data[year]).slice()
						}]
					}, true, false, {
						duration: 800
					});
				});
			}),
			
			Highcharts.chart('chart-3', {
				chart: {
					type: 'spline',
					scrollablePlotArea: {
						minWidth: 600,
						scrollPositionX: 1
					}
				},
				title: {
					text: 'Wind speed during two days',
					align: 'left'
				},
				subtitle: {
					text: '13th & 14th of February, 2018 at two locations in Vik i Sogn, Norway',
					align: 'left'
				},
				xAxis: {
					type: 'datetime',
					labels: {
						overflow: 'justify'
					}
				},
				yAxis: {
					title: {
						text: 'Wind speed (m/s)'
					},
					minorGridLineWidth: 0,
					gridLineWidth: 0,
					alternateGridColor: null,
					plotBands: [{
						from: 0.3,
						to: 1.5,
						color: 'rgba(68, 170, 213, 0.1)',
						label: {
							text: 'Light air',
							style: {
								color: '#606060'
							}
						}
					},
					{
						from: 1.5,
						to: 3.3,
						color: 'rgba(0, 0, 0, 0)',
						label: {
							text: 'Light breeze',
							style: {
								color: '#606060'
							}
						}
					},
					{
						from: 3.3,
						to: 5.5,
						color: 'rgba(68, 170, 213, 0.1)',
						label: {
							text: 'Gentle breeze',
							style: {
								color: '#606060'
							}
						}
					},
					{
						from: 5.5,
						to: 8,
						color: 'rgba(0, 0, 0, 0)',
						label: {
							text: 'Moderate breeze',
							style: {
								color: '#606060'
							}
						}
					},
					{
						from: 8,
						to: 11,
						color: 'rgba(68, 170, 213, 0.1)',
						label: {
							text: 'Fresh breeze',
							style: {
								color: '#606060'
							}
						}
					},
					{
						from: 11,
						to: 14,
						color: 'rgba(0, 0, 0, 0)',
						label: {
							text: 'Strong breeze',
							style: {
								color: '#606060'
							}
						}
					},
					{
						from: 14,
						to: 15,
						color: 'rgba(68, 170, 213, 0.1)',
						label: {
							text: 'High wind',
							style: {
								color: '#606060'
							}
						}
					}]
				},
				tooltip: {
					valueSuffix: ' m/s'
				},
				plotOptions: {
					spline: {
						lineWidth: 4,
						states: {
							hover: {
								lineWidth: 5
							}
						},
						marker: {
							enabled: false
						},
						pointInterval: 3600000,
						pointStart: Date.UTC(2018, 1, 13, 0, 0, 0)
					}
				},
				series: [{
					name: 'Hestavollane',
					data: [
						3.7, 3.3, 3.9, 5.1, 3.5, 3.8, 4.0, 5.0, 6.1, 3.7, 3.3, 6.4,
						6.9, 6.0, 6.8, 4.4, 4.0, 3.8, 5.0, 4.9, 9.2, 9.6, 9.5, 6.3,
						9.5, 10.8, 14.0, 11.5, 10.0, 10.2, 10.3, 9.4, 8.9, 10.6, 10.5, 11.1,
						10.4, 10.7, 11.3, 10.2, 9.6, 10.2, 11.1, 10.8, 13.0, 12.5, 12.5, 11.3,
						10.1
					]

				}, {
					name: 'Vik',
					data: [
						0.2, 0.1, 0.1, 0.1, 0.3, 0.2, 0.3, 0.1, 0.7, 0.3, 0.2, 0.2,
						0.3, 0.1, 0.3, 0.4, 0.3, 0.2, 0.3, 0.2, 0.4, 0.0, 0.9, 0.3,
						0.7, 1.1, 1.8, 1.2, 1.4, 1.2, 0.9, 0.8, 0.9, 0.2, 0.4, 1.2,
						0.3, 2.3, 1.0, 0.7, 1.0, 0.8, 2.0, 1.2, 1.4, 3.7, 2.1, 2.0,
						1.5
					]
				}],
				navigation: {
					menuItemStyle: {
						fontSize: '10px'
					}
				}
			});
			
			var elevationData = [
				[0.0, 225],
				[0.1, 226],
				[0.2, 228],
				[0.3, 228],
				[0.4, 229],
				[0.5, 229],
				[0.6, 230],
				[0.7, 234],
				[0.8, 235],
				[0.9, 236],
				[1.0, 235],
				[1.1, 232],
				[1.2, 228],
				[1.3, 223],
				[1.4, 218],
				[1.5, 214],
				[1.6, 207],
				[1.7, 202],
				[1.8, 198],
				[1.9, 196],
				[2.0, 197],
				[2.1, 200],
				[2.2, 205],
				[2.3, 206],
				[2.4, 210],
				[2.5, 210],
				[2.6, 210],
				[2.7, 209],
				[2.8, 208],
				[2.9, 207],
				[3.0, 209],
				[3.1, 208],
				[3.2, 207],
				[3.3, 207],
				[3.4, 206],
				[3.5, 206],
				[3.6, 205],
				[3.7, 201],
				[3.8, 195],
				[3.9, 191],
				[4.0, 191],
				[4.1, 195],
				[4.2, 199],
				[4.3, 203],
				[4.4, 208],
				[4.5, 208],
				[4.6, 208],
				[4.7, 208],
				[4.8, 209],
				[4.9, 207],
				[5.0, 207],
				[5.1, 208],
				[5.2, 209],
				[5.3, 208],
				[5.4, 210],
				[5.5, 209],
				[5.6, 209],
				[5.7, 206],
				[5.8, 207],
				[5.9, 209],
				[6.0, 211],
				[6.1, 206],
				[6.2, 201],
				[6.3, 199],
				[6.4, 200],
				[6.5, 202],
				[6.6, 203],
				[6.7, 202],
				[6.8, 204],
				[6.9, 206],
				[7.0, 208],
				[7.1, 205],
				[7.2, 202],
				[7.3, 198],
				[7.4, 198],
				[7.5, 198],
				[7.6, 198],
				[7.7, 198],
				[7.8, 199],
				[7.9, 197],
				[8.0, 194],
				[8.1, 194],
				[8.2, 195],
				[8.3, 195],
				[8.4, 196],
				[8.5, 192],
				[8.6, 200],
				[8.7, 197],
				[8.8, 194],
				[8.9, 194],
				[9.0, 193],
				[9.1, 192],
				[9.2, 192],
				[9.3, 193],
				[9.4, 191],
				[9.5, 191],
				[9.6, 193],
				[9.7, 193],
				[9.8, 194],
				[9.9, 192],
				[10.0, 192],
				[10.1, 192],
				[10.2, 192],
				[10.3, 192],
				[10.4, 193],
				[10.5, 193],
				[10.6, 193],
				[10.7, 193],
				[10.8, 194],
				[10.9, 194],
				[11.0, 194],
				[11.1, 195],
				[11.2, 194],
				[11.3, 194],
				[11.4, 194],
				[11.5, 194],
				[11.6, 193],
				[11.7, 194],
				[11.8, 194],
				[11.9, 194],
				[12.0, 195],
				[12.1, 195],
				[12.2, 195],
				[12.3, 197],
				[12.4, 197],
				[12.5, 197],
				[12.6, 198],
				[12.7, 201],
				[12.8, 202],
				[12.9, 203],
				[13.0, 205],
				[13.1, 205],
				[13.2, 204],
				[13.3, 210],
				[13.4, 213],
				[13.5, 212],
				[13.6, 213],
				[13.7, 213],
				[13.8, 213],
				[13.9, 214],
				[14.0, 214],
				[14.1, 212],
				[14.2, 209],
				[14.3, 207],
				[14.4, 207],
				[14.5, 208],
				[14.6, 211],
				[14.7, 215],
				[14.8, 219],
				[14.9, 219],
				[15.0, 221],
				[15.1, 224],
				[15.2, 224],
				[15.3, 225],
				[15.4, 225],
				[15.5, 225],
				[15.6, 225],
				[15.7, 225],
				[15.8, 225],
				[15.9, 226],
				[16.0, 226],
				[16.1, 227],
				[16.2, 227],
				[16.3, 230],
				[16.4, 231],
				[16.5, 231],
				[16.6, 232],
				[16.7, 230],
				[16.8, 229],
				[16.9, 228],
				[17.0, 226],
				[17.1, 226],
				[17.2, 224],
				[17.3, 223],
				[17.4, 224],
				[17.5, 223],
				[17.6, 221],
				[17.7, 220],
				[17.8, 218],
				[17.9, 217],
				[18.0, 216],
				[18.1, 216],
				[18.2, 215],
				[18.3, 214],
				[18.4, 213],
				[18.5, 212],
				[18.6, 212],
				[18.7, 213],
				[18.8, 214],
				[18.9, 215],
				[19.0, 215],
				[19.1, 216],
				[19.2, 217],
				[19.3, 216],
				[19.4, 216],
				[19.5, 217],
				[19.6, 219],
				[19.7, 218],
				[19.8, 218],
				[19.9, 220],
				[20.0, 224],
				[20.1, 224],
				[20.2, 225],
				[20.3, 224],
				[20.4, 225],
				[20.5, 228],
				[20.6, 228],
				[20.7, 227],
				[20.8, 228],
				[20.9, 228],
				[21.0, 228],
				[21.1, 229],
				[21.2, 225],
				[21.3, 223],
				[21.4, 222],
				[21.5, 222],
				[21.6, 223],
				[21.7, 224],
				[21.8, 224],
				[21.9, 226],
				[22.0, 228],
				[22.1, 233],
				[22.2, 237],
				[22.3, 237],
				[22.4, 238],
				[22.5, 236],
				[22.6, 232],
				[22.7, 232],
				[22.8, 228],
				[22.9, 227],
				[23.0, 227],
				[23.1, 227],
				[23.2, 227],
				[23.3, 226],
				[23.4, 226],
				[23.5, 226],
				[23.6, 226],
				[23.7, 226],
				[23.8, 226],
				[23.9, 226],
				[24.0, 228],
				[24.1, 228],
				[24.2, 229],
				[24.3, 230],
				[24.4, 230],
				[24.5, 233],
				[24.6, 235],
				[24.7, 234],
				[24.8, 234],
				[24.9, 233],
				[25.0, 233],
				[25.1, 232],
				[25.2, 232],
				[25.3, 232],
				[25.4, 232],
				[25.5, 234],
				[25.6, 234],
				[25.7, 234],
				[25.8, 234],
				[25.9, 237],
				[26.0, 238],
				[26.1, 238],
				[26.2, 238],
				[26.3, 240],
				[26.4, 240],
				[26.5, 244],
				[26.6, 241],
				[26.7, 241],
				[26.8, 245],
				[26.9, 254],
				[27.0, 262],
				[27.1, 255],
				[27.2, 255],
				[27.3, 251],
				[27.4, 252],
				[27.5, 253],
				[27.6, 253],
				[27.7, 251],
				[27.8, 251],
				[27.9, 254],
				[28.0, 255],
				[28.1, 257],
				[28.2, 259],
				[28.3, 259],
				[28.4, 259],
				[28.5, 260],
				[28.6, 258],
				[28.7, 258],
				[28.8, 262],
				[28.9, 260],
				[29.0, 262],
				[29.1, 266],
				[29.2, 268],
				[29.3, 270],
				[29.4, 278],
				[29.5, 276],
				[29.6, 278],
				[29.7, 282],
				[29.8, 281],
				[29.9, 284],
				[30.0, 287],
				[30.1, 292],
				[30.2, 303],
				[30.3, 309],
				[30.4, 317],
				[30.5, 324],
				[30.6, 334],
				[30.7, 334],
				[30.8, 319],
				[30.9, 321],
				[31.0, 333],
				[31.1, 337],
				[31.2, 345],
				[31.3, 352],
				[31.4, 386],
				[31.5, 398],
				[31.6, 406],
				[31.7, 416],
				[31.8, 423],
				[31.9, 425],
				[32.0, 425],
				[32.1, 424],
				[32.2, 423],
				[32.3, 421],
				[32.4, 421],
				[32.5, 422],
				[32.6, 421],
				[32.7, 421],
				[32.8, 421],
				[32.9, 421],
				[33.0, 421],
				[33.1, 423],
				[33.2, 435],
				[33.3, 450],
				[33.4, 451],
				[33.5, 452],
				[33.6, 452],
				[33.7, 452],
				[33.8, 452],
				[33.9, 455],
				[34.0, 459],
				[34.1, 465],
				[34.2, 469],
				[34.3, 473],
				[34.4, 476],
				[34.5, 480],
				[34.6, 483],
				[34.7, 487],
				[34.8, 490],
				[34.9, 494],
				[35.0, 497],
				[35.1, 502],
				[35.2, 504],
				[35.3, 507],
				[35.4, 511],
				[35.5, 514],
				[35.6, 518],
				[35.7, 521],
				[35.8, 524],
				[35.9, 527],
				[36.0, 528],
				[36.1, 525],
				[36.2, 526],
				[36.3, 528],
				[36.4, 528],
				[36.5, 528],
				[36.6, 529],
				[36.7, 528],
				[36.8, 526],
				[36.9, 525],
				[37.0, 524],
				[37.1, 524],
				[37.2, 524],
				[37.3, 525],
				[37.4, 528],
				[37.5, 528],
				[37.6, 532],
				[37.7, 534],
				[37.8, 538],
				[37.9, 540],
				[38.0, 544],
				[38.1, 546],
				[38.2, 554],
				[38.3, 555],
				[38.4, 559],
				[38.5, 566],
				[38.6, 568],
				[38.7, 571],
				[38.8, 571],
				[38.9, 570],
				[39.0, 569],
				[39.1, 567],
				[39.2, 567],
				[39.3, 566],
				[39.4, 566],
				[39.5, 566],
				[39.6, 566],
				[39.7, 566],
				[39.8, 566],
				[39.9, 565],
				[40.0, 563],
				[40.1, 562],
				[40.2, 563],
				[40.3, 563],
				[40.4, 562],
				[40.5, 562],
				[40.6, 562],
				[40.7, 561],
				[40.8, 563],
				[40.9, 561],
				[41.0, 559],
				[41.1, 559],
				[41.2, 559],
				[41.3, 558],
				[41.4, 559],
				[41.5, 560],
				[41.6, 560],
				[41.7, 560],
				[41.8, 559],
				[41.9, 557],
				[42.0, 556],
				[42.1, 555],
				[42.2, 555],
				[42.3, 556],
				[42.4, 557],
				[42.5, 557],
				[42.6, 557],
				[42.7, 557],
				[42.8, 557],
				[42.9, 557],
				[43.0, 557],
				[43.1, 557],
				[43.2, 558],
				[43.3, 559],
				[43.4, 560],
				[43.5, 563],
				[43.6, 566],
				[43.7, 570],
				[43.8, 572],
				[43.9, 575],
				[44.0, 573],
				[44.1, 576],
				[44.2, 577],
				[44.3, 579],
				[44.4, 581],
				[44.5, 584],
				[44.6, 591],
				[44.7, 593],
				[44.8, 594],
				[44.9, 596],
				[45.0, 599],
				[45.1, 601],
				[45.2, 601],
				[45.3, 604],
				[45.4, 607],
				[45.5, 607],
				[45.6, 607],
				[45.7, 607],
				[45.8, 605],
				[45.9, 607],
				[46.0, 609],
				[46.1, 612],
				[46.2, 613],
				[46.3, 614],
				[46.4, 614],
				[46.5, 614],
				[46.6, 616],
				[46.7, 616],
				[46.8, 615],
				[46.9, 615],
				[47.0, 618],
				[47.1, 617],
				[47.2, 615],
				[47.3, 614],
				[47.4, 613],
				[47.5, 613],
				[47.6, 613],
				[47.7, 613],
				[47.8, 612],
				[47.9, 612],
				[48.0, 609],
				[48.1, 610],
				[48.2, 603],
				[48.3, 598],
				[48.4, 598],
				[48.5, 596],
				[48.6, 595],
				[48.7, 593],
				[48.8, 590],
				[48.9, 587],
				[49.0, 583],
				[49.1, 580],
				[49.2, 576],
				[49.3, 569],
				[49.4, 568],
				[49.5, 566],
				[49.6, 560],
				[49.7, 559],
				[49.8, 558],
				[49.9, 562],
				[50.0, 564],
				[50.1, 563],
				[50.2, 563],
				[50.3, 567],
				[50.4, 574],
				[50.5, 577],
				[50.6, 580],
				[50.7, 581],
				[50.8, 579],
				[50.9, 579],
				[51.0, 578],
				[51.1, 574],
				[51.2, 569],
				[51.3, 564],
				[51.4, 558],
				[51.5, 554],
				[51.6, 550],
				[51.7, 543],
				[51.8, 539],
				[51.9, 536],
				[52.0, 532],
				[52.1, 530],
				[52.2, 529],
				[52.3, 528],
				[52.4, 528],
				[52.5, 528],
				[52.6, 528],
				[52.7, 527],
				[52.8, 527],
				[52.9, 528],
				[53.0, 529],
				[53.1, 528],
				[53.2, 526],
				[53.3, 526],
				[53.4, 524],
				[53.5, 519],
				[53.6, 517],
				[53.7, 517],
				[53.8, 522],
				[53.9, 521],
				[54.0, 520],
				[54.1, 518],
				[54.2, 513],
				[54.3, 518],
				[54.4, 520],
				[54.5, 523],
				[54.6, 526],
				[54.7, 522],
				[54.8, 513],
				[54.9, 512],
				[55.0, 513],
				[55.1, 513],
				[55.2, 518],
				[55.3, 522],
				[55.4, 526],
				[55.5, 526],
				[55.6, 525],
				[55.7, 522],
				[55.8, 520],
				[55.9, 519],
				[56.0, 518],
				[56.1, 518],
				[56.2, 518],
				[56.3, 517],
				[56.4, 516],
				[56.5, 517],
				[56.6, 517],
				[56.7, 517],
				[56.8, 521],
				[56.9, 522],
				[57.0, 518],
				[57.1, 517],
				[57.2, 514],
				[57.3, 515],
				[57.4, 513],
				[57.5, 511],
				[57.6, 511],
				[57.7, 511],
				[57.8, 510],
				[57.9, 510],
				[58.0, 509],
				[58.1, 509],
				[58.2, 509],
				[58.3, 509],
				[58.4, 509],
				[58.5, 509],
				[58.6, 509],
				[58.7, 509],
				[58.8, 509],
				[58.9, 510],
				[59.0, 510],
				[59.1, 521],
				[59.2, 524],
				[59.3, 528],
				[59.4, 533],
				[59.5, 539],
				[59.6, 545],
				[59.7, 551],
				[59.8, 562],
				[59.9, 572],
				[60.0, 579],
				[60.1, 585],
				[60.2, 593],
				[60.3, 596],
				[60.4, 605],
				[60.5, 617],
				[60.6, 620],
				[60.7, 627],
				[60.8, 628],
				[60.9, 627],
				[61.0, 627],
				[61.1, 628],
				[61.2, 629],
				[61.3, 632],
				[61.4, 634],
				[61.5, 638],
				[61.6, 637],
				[61.7, 638],
				[61.8, 639],
				[61.9, 640],
				[62.0, 640],
				[62.1, 639],
				[62.2, 639],
				[62.3, 637],
				[62.4, 637],
				[62.5, 636],
				[62.6, 637],
				[62.7, 636],
				[62.8, 637],
				[62.9, 635],
				[63.0, 629],
				[63.1, 626],
				[63.2, 626],
				[63.3, 623],
				[63.4, 621],
				[63.5, 621],
				[63.6, 621],
				[63.7, 622],
				[63.8, 625],
				[63.9, 626],
				[64.0, 629],
				[64.1, 631],
				[64.2, 633],
				[64.3, 631],
				[64.4, 632],
				[64.5, 634],
				[64.6, 637],
				[64.7, 637],
				[64.8, 637],
				[64.9, 637],
				[65.0, 638],
				[65.1, 641],
				[65.2, 644],
				[65.3, 646],
				[65.4, 649],
				[65.5, 648],
				[65.6, 647],
				[65.7, 647],
				[65.8, 649],
				[65.9, 650],
				[66.0, 651],
				[66.1, 654],
				[66.2, 652],
				[66.3, 651],
				[66.4, 650],
				[66.5, 650],
				[66.6, 649],
				[66.7, 648],
				[66.8, 648],
				[66.9, 648],
				[67.0, 649],
				[67.1, 650],
				[67.2, 647],
				[67.3, 642],
				[67.4, 641],
				[67.5, 638],
				[67.6, 636],
				[67.7, 635],
				[67.8, 633],
				[67.9, 636],
				[68.0, 638],
				[68.1, 639],
				[68.2, 641],
				[68.3, 643],
				[68.4, 644],
				[68.5, 645],
				[68.6, 649],
				[68.7, 651],
				[68.8, 648],
				[68.9, 645],
				[69.0, 640],
				[69.1, 637],
				[69.2, 637],
				[69.3, 637],
				[69.4, 635],
				[69.5, 630],
				[69.6, 628],
				[69.7, 625],
				[69.8, 622],
				[69.9, 620],
				[70.0, 618],
				[70.1, 613],
				[70.2, 612],
				[70.3, 608],
				[70.4, 603],
				[70.5, 599],
				[70.6, 597],
				[70.7, 591],
				[70.8, 590],
				[70.9, 587],
				[71.0, 584],
				[71.1, 584],
				[71.2, 582],
				[71.3, 574],
				[71.4, 572],
				[71.5, 570],
				[71.6, 572],
				[71.7, 573],
				[71.8, 575],
				[71.9, 578],
				[72.0, 590],
				[72.1, 595],
				[72.2, 595],
				[72.3, 579],
				[72.4, 581],
				[72.5, 583],
				[72.6, 583],
				[72.7, 583],
				[72.8, 583],
				[72.9, 580],
				[73.0, 579],
				[73.1, 584],
				[73.2, 587],
				[73.3, 594],
				[73.4, 597],
				[73.5, 597],
				[73.6, 596],
				[73.7, 593],
				[73.8, 591],
				[73.9, 596],
				[74.0, 596],
				[74.1, 598],
				[74.2, 598],
				[74.3, 595],
				[74.4, 592],
				[74.5, 592],
				[74.6, 592],
				[74.7, 594],
				[74.8, 597],
				[74.9, 600],
				[75.0, 601],
				[75.1, 605],
				[75.2, 604],
				[75.3, 604],
				[75.4, 607],
				[75.5, 607],
				[75.6, 607],
				[75.7, 604],
				[75.8, 605],
				[75.9, 608],
				[76.0, 616],
				[76.1, 618],
				[76.2, 629],
				[76.3, 633],
				[76.4, 634],
				[76.5, 637],
				[76.6, 644],
				[76.7, 650],
				[76.8, 653],
				[76.9, 653],
				[77.0, 657],
				[77.1, 664],
				[77.2, 668],
				[77.3, 668],
				[77.4, 668],
				[77.5, 672],
				[77.6, 674],
				[77.7, 679],
				[77.8, 681],
				[77.9, 689],
				[78.0, 692],
				[78.1, 704],
				[78.2, 708],
				[78.3, 714],
				[78.4, 716],
				[78.5, 719],
				[78.6, 722],
				[78.7, 729],
				[78.8, 733],
				[78.9, 735],
				[79.0, 736],
				[79.1, 737],
				[79.2, 737],
				[79.3, 737],
				[79.4, 737],
				[79.5, 736],
				[79.6, 736],
				[79.7, 736],
				[79.8, 737],
				[79.9, 737],
				[80.0, 737],
				[80.1, 737],
				[80.2, 738],
				[80.3, 739],
				[80.4, 739],
				[80.5, 739],
				[80.6, 741],
				[80.7, 741],
				[80.8, 741],
				[80.9, 741],
				[81.0, 743],
				[81.1, 744],
				[81.2, 744],
				[81.3, 744],
				[81.4, 744],
				[81.5, 746],
				[81.6, 748],
				[81.7, 757],
				[81.8, 753],
				[81.9, 752],
				[82.0, 751],
				[82.1, 748],
				[82.2, 746],
				[82.3, 756],
				[82.4, 755],
				[82.5, 748],
				[82.6, 745],
				[82.7, 749],
				[82.8, 752],
				[82.9, 753],
				[83.0, 753],
				[83.1, 755],
				[83.2, 764],
				[83.3, 766],
				[83.4, 771],
				[83.5, 774],
				[83.6, 775],
				[83.7, 777],
				[83.8, 778],
				[83.9, 778],
				[84.0, 780],
				[84.1, 780],
				[84.2, 782],
				[84.3, 783],
				[84.4, 786],
				[84.5, 791],
				[84.6, 792],
				[84.7, 787],
				[84.8, 782],
				[84.9, 780],
				[85.0, 777],
				[85.1, 777],
				[85.2, 777],
				[85.3, 777],
				[85.4, 770],
				[85.5, 768],
				[85.6, 764],
				[85.7, 763],
				[85.8, 758],
				[85.9, 757],
				[86.0, 760],
				[86.1, 759],
				[86.2, 756],
				[86.3, 751],
				[86.4, 748],
				[86.5, 748],
				[86.6, 747],
				[86.7, 746],
				[86.8, 745],
				[86.9, 746],
				[87.0, 746],
				[87.1, 747],
				[87.2, 742],
				[87.3, 738],
				[87.4, 733],
				[87.5, 730],
				[87.6, 730],
				[87.7, 727],
				[87.8, 725],
				[87.9, 722],
				[88.0, 719],
				[88.1, 718],
				[88.2, 716],
				[88.3, 712],
				[88.4, 710],
				[88.5, 708],
				[88.6, 707],
				[88.7, 705],
				[88.8, 699],
				[88.9, 695],
				[89.0, 686],
				[89.1, 674],
				[89.2, 671],
				[89.3, 670],
				[89.4, 670],
				[89.5, 669],
				[89.6, 668],
				[89.7, 669],
				[89.8, 670],
				[89.9, 672],
				[90.0, 679],
				[90.1, 681],
				[90.2, 684],
				[90.3, 689],
				[90.4, 693],
				[90.5, 697],
				[90.6, 701],
				[90.7, 705],
				[90.8, 708],
				[90.9, 709],
				[91.0, 712],
				[91.1, 714],
				[91.2, 716],
				[91.3, 706],
				[91.4, 697],
				[91.5, 689],
				[91.6, 681],
				[91.7, 677],
				[91.8, 668],
				[91.9, 663],
				[92.0, 661],
				[92.1, 653],
				[92.2, 652],
				[92.3, 650],
				[92.4, 647],
				[92.5, 646],
				[92.6, 645],
				[92.7, 642],
				[92.8, 640],
				[92.9, 640],
				[93.0, 651],
				[93.1, 648],
				[93.2, 657],
				[93.3, 660],
				[93.4, 660],
				[93.5, 662],
				[93.6, 672],
				[93.7, 675],
				[93.8, 681],
				[93.9, 685],
				[94.0, 694],
				[94.1, 700],
				[94.2, 707],
				[94.3, 716],
				[94.4, 720],
				[94.5, 723],
				[94.6, 727],
				[94.7, 727],
				[94.8, 728],
				[94.9, 727],
				[95.0, 727],
				[95.1, 727],
				[95.2, 730],
				[95.3, 735],
				[95.4, 744],
				[95.5, 749],
				[95.6, 753],
				[95.7, 759],
				[95.8, 762],
				[95.9, 767],
				[96.0, 782],
				[96.1, 781],
				[96.2, 783],
				[96.3, 785],
				[96.4, 789],
				[96.5, 796],
				[96.6, 807],
				[96.7, 813],
				[96.8, 819],
				[96.9, 822],
				[97.0, 824],
				[97.1, 826],
				[97.2, 830],
				[97.3, 832],
				[97.4, 836],
				[97.5, 838],
				[97.6, 842],
				[97.7, 847],
				[97.8, 848],
				[97.9, 854],
				[98.0, 855],
				[98.1, 858],
				[98.2, 863],
				[98.3, 870],
				[98.4, 875],
				[98.5, 883],
				[98.6, 889],
				[98.7, 896],
				[98.8, 904],
				[98.9, 910],
				[99.0, 916],
				[99.1, 922],
				[99.2, 927],
				[99.3, 931],
				[99.4, 938],
				[99.5, 941],
				[99.6, 949],
				[99.7, 954],
				[99.8, 962],
				[99.9, 967],
				[100.0, 976],
				[100.1, 983],
				[100.2, 986],
				[100.3, 992],
				[100.4, 994],
				[100.5, 999],
				[100.6, 1004],
				[100.7, 1006],
				[100.8, 1007],
				[100.9, 1009],
				[101.0, 1012],
				[101.1, 1016],
				[101.2, 1019],
				[101.3, 1021],
				[101.4, 1025],
				[101.5, 1025],
				[101.6, 1020],
				[101.7, 1017],
				[101.8, 1009],
				[101.9, 1003],
				[102.0, 1000],
				[102.1, 994],
				[102.2, 989],
				[102.3, 986],
				[102.4, 979],
				[102.5, 974],
				[102.6, 972],
				[102.7, 964],
				[102.8, 961],
				[102.9, 957],
				[103.0, 952],
				[103.1, 946],
				[103.2, 944],
				[103.3, 940],
				[103.4, 936],
				[103.5, 935],
				[103.6, 934],
				[103.7, 934],
				[103.8, 934],
				[103.9, 934],
				[104.0, 933],
				[104.1, 929],
				[104.2, 922],
				[104.3, 914],
				[104.4, 906],
				[104.5, 910],
				[104.6, 906],
				[104.7, 903],
				[104.8, 895],
				[104.9, 893],
				[105.0, 891],
				[105.1, 889],
				[105.2, 889],
				[105.3, 893],
				[105.4, 899],
				[105.5, 904],
				[105.6, 906],
				[105.7, 897],
				[105.8, 883],
				[105.9, 895],
				[106.0, 898],
				[106.1, 893],
				[106.2, 895],
				[106.3, 907],
				[106.4, 916],
				[106.5, 915],
				[106.6, 920],
				[106.7, 919],
				[106.8, 917],
				[106.9, 911],
				[107.0, 904],
				[107.1, 891],
				[107.2, 894],
				[107.3, 902],
				[107.4, 900],
				[107.5, 900],
				[107.6, 898],
				[107.7, 897],
				[107.8, 897],
				[107.9, 897],
				[108.0, 909],
				[108.1, 910],
				[108.2, 906],
				[108.3, 920],
				[108.4, 901],
				[108.5, 900],
				[108.6, 895],
				[108.7, 892],
				[108.8, 887],
				[108.9, 889],
				[109.0, 904],
				[109.1, 910],
				[109.2, 910],
				[109.3, 907],
				[109.4, 906],
				[109.5, 898],
				[109.6, 908],
				[109.7, 911],
				[109.8, 920],
				[109.9, 928],
				[110.0, 939],
				[110.1, 939],
				[110.2, 935],
				[110.3, 932],
				[110.4, 926],
				[110.5, 924],
				[110.6, 919],
				[110.7, 913],
				[110.8, 909],
				[110.9, 906],
				[111.0, 901],
				[111.1, 899],
				[111.2, 899],
				[111.3, 899],
				[111.4, 898],
				[111.5, 896],
				[111.6, 895],
				[111.7, 889],
				[111.8, 887],
				[111.9, 886],
				[112.0, 881],
				[112.1, 875],
				[112.2, 872],
				[112.3, 867],
				[112.4, 856],
				[112.5, 850],
				[112.6, 842],
				[112.7, 839],
				[112.8, 836],
				[112.9, 827],
				[113.0, 822],
				[113.1, 817],
				[113.2, 809],
				[113.3, 805],
				[113.4, 802],
				[113.5, 796],
				[113.6, 793],
				[113.7, 790],
				[113.8, 786],
				[113.9, 778],
				[114.0, 770],
				[114.1, 759],
				[114.2, 754],
				[114.3, 744],
				[114.4, 744],
				[114.5, 746],
				[114.6, 741],
				[114.7, 761],
				[114.8, 759],
				[114.9, 737],
				[115.0, 722],
				[115.1, 719],
				[115.2, 720],
				[115.3, 721],
				[115.4, 722],
				[115.5, 718],
				[115.6, 713],
				[115.7, 709],
				[115.8, 706],
				[115.9, 707],
				[116.0, 699],
				[116.1, 689],
				[116.2, 685],
				[116.3, 683],
				[116.4, 669],
				[116.5, 665],
				[116.6, 661],
				[116.7, 657],
				[116.8, 653],
				[116.9, 653],
				[117.0, 647],
				[117.1, 640],
				[117.2, 638],
				[117.3, 633],
				[117.4, 628],
				[117.5, 624],
				[117.6, 618],
				[117.7, 613],
				[117.8, 607],
				[117.9, 602],
				[118.0, 598],
				[118.1, 595],
				[118.2, 595],
				[118.3, 594],
				[118.4, 602],
				[118.5, 598],
				[118.6, 598],
				[118.7, 601],
				[118.8, 605],
				[118.9, 608],
				[119.0, 612],
				[119.1, 614],
				[119.2, 611],
				[119.3, 608],
				[119.4, 611],
				[119.5, 612],
				[119.6, 614],
				[119.7, 615],
				[119.8, 613],
				[119.9, 611],
				[120.0, 602],
				[120.1, 593],
				[120.2, 588],
				[120.3, 588],
				[120.4, 586],
				[120.5, 583],
				[120.6, 579],
				[120.7, 579],
				[120.8, 578],
				[120.9, 576],
				[121.0, 575],
				[121.1, 579],
				[121.2, 574],
				[121.3, 570],
				[121.4, 565],
				[121.5, 562],
				[121.6, 560],
				[121.7, 559],
				[121.8, 556],
				[121.9, 554],
				[122.0, 546],
				[122.1, 542],
				[122.2, 536],
				[122.3, 531],
				[122.4, 529],
				[122.5, 529],
				[122.6, 518],
				[122.7, 515],
				[122.8, 515],
				[122.9, 515],
				[123.0, 514],
				[123.1, 513],
				[123.2, 506],
				[123.3, 498],
				[123.4, 496],
				[123.5, 494],
				[123.6, 483],
				[123.7, 479],
				[123.8, 476],
				[123.9, 470],
				[124.0, 466],
				[124.1, 460],
				[124.2, 457],
				[124.3, 451],
				[124.4, 445],
				[124.5, 443],
				[124.6, 435],
				[124.7, 432],
				[124.8, 426],
				[124.9, 421],
				[125.0, 418],
				[125.1, 414],
				[125.2, 408],
				[125.3, 405],
				[125.4, 403],
				[125.5, 394],
				[125.6, 386],
				[125.7, 379],
				[125.8, 361],
				[125.9, 358],
				[126.0, 366],
				[126.1, 372],
				[126.2, 372],
				[126.3, 374],
				[126.4, 379],
				[126.5, 382],
				[126.6, 385],
				[126.7, 388],
				[126.8, 390],
				[126.9, 393],
				[127.0, 394],
				[127.1, 393],
				[127.2, 391],
				[127.3, 387],
				[127.4, 382],
				[127.5, 378],
				[127.6, 374],
				[127.7, 370],
				[127.8, 367],
				[127.9, 366],
				[128.0, 364],
				[128.1, 364],
				[128.2, 362],
				[128.3, 362],
				[128.4, 360],
				[128.5, 357],
				[128.6, 354],
				[128.7, 351],
				[128.8, 350],
				[128.9, 351],
				[129.0, 350],
				[129.1, 350],
				[129.2, 351],
				[129.3, 352],
				[129.4, 352],
				[129.5, 352],
				[129.6, 351],
				[129.7, 352],
				[129.8, 352],
				[129.9, 353],
				[130.0, 348],
				[130.1, 346],
				[130.2, 344],
				[130.3, 343],
				[130.4, 343],
				[130.5, 342],
				[130.6, 342],
				[130.7, 345],
				[130.8, 349],
				[130.9, 341],
				[131.0, 345],
				[131.1, 348],
				[131.2, 364],
				[131.3, 374],
				[131.4, 388],
				[131.5, 379],
				[131.6, 380],
				[131.7, 387],
				[131.8, 394],
				[131.9, 404],
				[132.0, 411],
				[132.1, 416],
				[132.2, 426],
				[132.3, 428],
				[132.4, 430],
				[132.5, 438],
				[132.6, 447],
				[132.7, 450],
				[132.8, 454],
				[132.9, 460],
				[133.0, 468],
				[133.1, 469],
				[133.2, 474],
				[133.3, 478],
				[133.4, 485],
				[133.5, 488],
				[133.6, 494],
				[133.7, 497],
				[133.8, 502],
				[133.9, 510],
				[134.0, 513],
				[134.1, 516],
				[134.2, 523],
				[134.3, 527],
				[134.4, 531],
				[134.5, 540],
				[134.6, 544],
				[134.7, 549],
				[134.8, 554],
				[134.9, 557],
				[135.0, 564],
				[135.1, 566],
				[135.2, 571],
				[135.3, 577],
				[135.4, 581],
				[135.5, 584],
				[135.6, 591],
				[135.7, 596],
				[135.8, 600],
				[135.9, 608],
				[136.0, 610],
				[136.1, 616],
				[136.2, 621],
				[136.3, 627],
				[136.4, 632],
				[136.5, 644],
				[136.6, 649],
				[136.7, 656],
				[136.8, 660],
				[136.9, 663],
				[137.0, 668],
				[137.1, 672],
				[137.2, 674],
				[137.3, 677],
				[137.4, 680],
				[137.5, 683],
				[137.6, 689],
				[137.7, 691],
				[137.8, 697],
				[137.9, 699],
				[138.0, 702],
				[138.1, 707],
				[138.2, 712],
				[138.3, 716],
				[138.4, 720],
				[138.5, 728],
				[138.6, 731],
				[138.7, 735],
				[138.8, 740],
				[138.9, 742],
				[139.0, 746],
				[139.1, 750],
				[139.2, 752],
				[139.3, 760],
				[139.4, 760],
				[139.5, 761],
				[139.6, 757],
				[139.7, 756],
				[139.8, 755],
				[139.9, 754],
				[140.0, 755],
				[140.1, 756],
				[140.2, 753],
				[140.3, 746],
				[140.4, 743],
				[140.5, 734],
				[140.6, 740],
				[140.7, 746],
				[140.8, 748],
				[140.9, 747],
				[141.0, 743],
				[141.1, 742],
				[141.2, 742],
				[141.3, 740],
				[141.4, 739],
				[141.5, 741],
				[141.6, 757],
				[141.7, 756],
				[141.8, 751],
				[141.9, 747],
				[142.0, 743],
				[142.1, 738],
				[142.2, 739],
				[142.3, 742],
				[142.4, 749],
				[142.5, 750],
				[142.6, 756],
				[142.7, 760],
				[142.8, 762],
				[142.9, 765],
				[143.0, 771],
				[143.1, 775],
				[143.2, 786],
				[143.3, 791],
				[143.4, 797],
				[143.5, 801],
				[143.6, 801],
				[143.7, 793],
				[143.8, 786],
				[143.9, 782],
				[144.0, 780],
				[144.1, 778],
				[144.2, 776],
				[144.3, 765],
				[144.4, 757],
				[144.5, 753],
				[144.6, 750],
				[144.7, 748],
				[144.8, 745],
				[144.9, 738],
				[145.0, 735],
				[145.1, 732],
				[145.2, 726],
				[145.3, 724],
				[145.4, 720],
				[145.5, 712],
				[145.6, 710],
				[145.7, 705],
				[145.8, 697],
				[145.9, 691],
				[146.0, 686],
				[146.1, 681],
				[146.2, 679],
				[146.3, 680],
				[146.4, 678],
				[146.5, 673],
				[146.6, 665],
				[146.7, 657],
				[146.8, 655],
				[146.9, 647],
				[147.0, 640],
				[147.1, 634],
				[147.2, 621],
				[147.3, 621],
				[147.4, 613],
				[147.5, 608],
				[147.6, 598],
				[147.7, 594],
				[147.8, 588],
				[147.9, 578],
				[148.0, 565],
				[148.1, 559],
				[148.2, 558],
				[148.3, 556],
				[148.4, 556],
				[148.5, 555],
				[148.6, 556],
				[148.7, 557],
				[148.8, 557],
				[148.9, 565],
				[149.0, 570],
				[149.1, 575],
				[149.2, 583],
				[149.3, 591],
				[149.4, 599],
				[149.5, 603],
				[149.6, 609],
				[149.7, 613],
				[149.8, 618],
				[149.9, 624],
				[150.0, 630],
				[150.1, 635],
				[150.2, 643],
				[150.3, 652],
				[150.4, 658],
				[150.5, 669],
				[150.6, 680],
				[150.7, 684],
				[150.8, 692],
				[150.9, 696],
				[151.0, 698],
				[151.1, 698],
				[151.2, 696],
				[151.3, 695],
				[151.4, 696],
				[151.5, 699],
				[151.6, 701],
				[151.7, 706],
				[151.8, 707],
				[151.9, 707],
				[152.0, 703],
				[152.1, 702],
				[152.2, 700],
				[152.3, 700],
				[152.4, 705],
				[152.5, 705],
				[152.6, 715],
				[152.7, 718],
				[152.8, 721],
				[152.9, 723],
				[153.0, 725],
				[153.1, 724],
				[153.2, 722],
				[153.3, 720],
				[153.4, 716],
				[153.5, 710],
				[153.6, 700],
				[153.7, 696],
				[153.8, 691],
				[153.9, 682],
				[154.0, 676],
				[154.1, 670],
				[154.2, 664],
				[154.3, 658],
				[154.4, 648],
				[154.5, 643],
				[154.6, 645],
				[154.7, 645],
				[154.8, 646],
				[154.9, 630],
				[155.0, 625],
				[155.1, 620],
				[155.2, 614],
				[155.3, 605],
				[155.4, 600],
				[155.5, 593],
				[155.6, 587],
				[155.7, 581],
				[155.8, 576],
				[155.9, 569],
				[156.0, 566],
				[156.1, 559],
				[156.2, 557],
				[156.3, 551],
				[156.4, 548],
				[156.5, 544],
				[156.6, 542],
				[156.7, 540],
				[156.8, 537],
				[156.9, 540],
				[157.0, 542],
				[157.1, 541],
				[157.2, 540],
				[157.3, 538],
				[157.4, 536],
				[157.5, 532],
				[157.6, 523],
				[157.7, 519],
				[157.8, 515],
				[157.9, 509],
				[158.0, 503],
				[158.1, 499],
				[158.2, 491],
				[158.3, 485],
				[158.4, 478],
				[158.5, 477],
				[158.6, 474],
				[158.7, 471],
				[158.8, 469],
				[158.9, 464],
				[159.0, 462],
				[159.1, 456],
				[159.2, 454],
				[159.3, 445],
				[159.4, 424],
				[159.5, 427],
				[159.6, 425],
				[159.7, 422],
				[159.8, 419],
				[159.9, 418],
				[160.0, 417],
				[160.1, 423],
				[160.2, 436],
				[160.3, 434],
				[160.4, 426],
				[160.5, 401],
				[160.6, 417],
				[160.7, 418],
				[160.8, 419],
				[160.9, 419],
				[161.0, 412],
				[161.1, 417],
				[161.2, 425],
				[161.3, 430],
				[161.4, 432],
				[161.5, 417],
				[161.6, 423],
				[161.7, 425],
				[161.8, 409],
				[161.9, 399],
				[162.0, 427],
				[162.1, 425],
				[162.2, 414],
				[162.3, 421],
				[162.4, 424],
				[162.5, 426],
				[162.6, 417],
				[162.7, 405],
				[162.8, 411],
				[162.9, 403],
				[163.0, 405],
				[163.1, 410],
				[163.2, 412],
				[163.3, 413],
				[163.4, 415],
				[163.5, 406],
				[163.6, 407],
				[163.7, 408],
				[163.8, 412],
				[163.9, 417],
				[164.0, 426],
				[164.1, 431],
				[164.2, 482],
				[164.3, 478],
				[164.4, 471],
				[164.5, 463],
				[164.6, 457],
				[164.7, 452],
				[164.8, 456],
				[164.9, 463],
				[165.0, 471],
				[165.1, 470],
				[165.2, 471],
				[165.3, 474],
				[165.4, 494],
				[165.5, 506],
				[165.6, 515],
				[165.7, 520],
				[165.8, 534],
				[165.9, 520],
				[166.0, 536],
				[166.1, 529],
				[166.2, 524],
				[166.3, 515],
				[166.4, 520],
				[166.5, 526],
				[166.6, 531],
				[166.7, 551],
				[166.8, 553],
				[166.9, 555],
				[167.0, 559],
				[167.1, 562],
				[167.2, 564],
				[167.3, 567],
				[167.4, 571],
				[167.5, 574],
				[167.6, 576],
				[167.7, 596],
				[167.8, 607],
				[167.9, 623],
				[168.0, 645],
				[168.1, 667],
				[168.2, 685],
				[168.3, 691],
				[168.4, 709],
				[168.5, 702],
				[168.6, 692],
				[168.7, 692],
				[168.8, 696],
				[168.9, 732],
				[169.0, 746],
				[169.1, 758],
				[169.2, 761],
				[169.3, 763],
				[169.4, 765],
				[169.5, 768],
				[169.6, 783],
				[169.7, 816],
				[169.8, 815],
				[169.9, 817],
				[170.0, 821],
				[170.1, 825],
				[170.2, 827],
				[170.3, 828],
				[170.4, 830],
				[170.5, 829],
				[170.6, 827],
				[170.7, 827],
				[170.8, 828],
				[170.9, 837],
				[171.0, 845],
				[171.1, 850],
				[171.2, 856],
				[171.3, 864],
				[171.4, 867],
				[171.5, 876],
				[171.6, 880],
				[171.7, 888],
				[171.8, 900],
				[171.9, 905],
				[172.0, 910],
				[172.1, 922],
				[172.2, 925],
				[172.3, 931],
				[172.4, 939],
				[172.5, 957],
				[172.6, 966],
				[172.7, 980],
				[172.8, 991],
				[172.9, 998],
				[173.0, 1008],
				[173.1, 1021],
				[173.2, 1031],
				[173.3, 1045],
				[173.4, 1059],
				[173.5, 1065],
				[173.6, 1078],
				[173.7, 1094],
				[173.8, 1092],
				[173.9, 1096],
				[174.0, 1098],
				[174.1, 1101],
				[174.2, 1104],
				[174.3, 1107],
				[174.4, 1110],
				[174.5, 1113],
				[174.6, 1114],
				[174.7, 1116],
				[174.8, 1119],
				[174.9, 1122],
				[175.0, 1125],
				[175.1, 1128],
				[175.2, 1130],
				[175.3, 1134],
				[175.4, 1137],
				[175.5, 1142],
				[175.6, 1148],
				[175.7, 1155],
				[175.8, 1162],
				[175.9, 1174],
				[176.0, 1183],
				[176.1, 1191],
				[176.2, 1196],
				[176.3, 1198],
				[176.4, 1200],
				[176.5, 1189],
				[176.6, 1183],
				[176.7, 1180],
				[176.8, 1178],
				[176.9, 1173],
				[177.0, 1171],
				[177.1, 1167],
				[177.2, 1165],
				[177.3, 1165],
				[177.4, 1165],
				[177.5, 1165],
				[177.6, 1164],
				[177.7, 1164],
				[177.8, 1164],
				[177.9, 1166],
				[178.0, 1167],
				[178.1, 1170],
				[178.2, 1177],
				[178.3, 1174],
				[178.4, 1166],
				[178.5, 1166],
				[178.6, 1164],
				[178.7, 1162],
				[178.8, 1160],
				[178.9, 1155],
				[179.0, 1152],
				[179.1, 1151],
				[179.2, 1151],
				[179.3, 1146],
				[179.4, 1144],
				[179.5, 1147],
				[179.6, 1151],
				[179.7, 1154],
				[179.8, 1153],
				[179.9, 1154],
				[180.0, 1153],
				[180.1, 1150],
				[180.2, 1147],
				[180.3, 1146],
				[180.4, 1144],
				[180.5, 1142],
				[180.6, 1140],
				[180.7, 1135],
				[180.8, 1125],
				[180.9, 1121],
				[181.0, 1113],
				[181.1, 1107],
				[181.2, 1098],
				[181.3, 1094],
				[181.4, 1087],
				[181.5, 1084],
				[181.6, 1082],
				[181.7, 1078],
				[181.8, 1073],
				[181.9, 1068],
				[182.0, 1063],
				[182.1, 1061],
				[182.2, 1060],
				[182.3, 1061],
				[182.4, 1065],
				[182.5, 1069],
				[182.6, 1072],
				[182.7, 1076],
				[182.8, 1084],
				[182.9, 1098],
				[183.0, 1110],
				[183.1, 1116],
				[183.2, 1113],
				[183.3, 1116],
				[183.4, 1122],
				[183.5, 1125],
				[183.6, 1133],
				[183.7, 1138],
				[183.8, 1146],
				[183.9, 1140],
				[184.0, 1135],
				[184.1, 1134],
				[184.2, 1131],
				[184.3, 1129],
				[184.4, 1137],
				[184.5, 1139],
				[184.6, 1144],
				[184.7, 1149],
				[184.8, 1155],
				[184.9, 1163],
				[185.0, 1164],
				[185.1, 1173],
				[185.2, 1170],
				[185.3, 1165],
				[185.4, 1157],
				[185.5, 1153],
				[185.6, 1150],
				[185.7, 1142],
				[185.8, 1141],
				[185.9, 1142],
				[186.0, 1142],
				[186.1, 1142],
				[186.2, 1142],
				[186.3, 1145],
				[186.4, 1151],
				[186.5, 1154],
				[186.6, 1155],
				[186.7, 1152],
				[186.8, 1154],
				[186.9, 1154],
				[187.0, 1155],
				[187.1, 1158],
				[187.2, 1159],
				[187.3, 1158],
				[187.4, 1158],
				[187.5, 1158],
				[187.6, 1161],
				[187.7, 1167],
				[187.8, 1170]
			];
			
			Highcharts.chart('chart-4', {

				chart: {
					type: 'area',
					zoomType: 'x',
					panning: true,
					panKey: 'shift',
					scrollablePlotArea: {
						minWidth: 600
					}
				},

				caption: {
					text: 'This chart uses the Highcharts Annotations feature to place labels at various points of interest. The labels are responsive and will be hidden to avoid overlap on small screens.'
				},

				title: {
					text: '2017 Tour de France Stage 8: Dole - Station des Rousses'
				},

				accessibility: {
					description: 'Image description: An annotated line graph illustrates the 8th stage of the 2017 Tour de France cycling race from the start point in Dole to the finish line at Station des Rousses. Altitude is plotted on the Y-axis at increments of 500m and distance is plotted on the X-axis in increments of 25 kilometers. The line graph is interactive, and the user can trace the altitude level at every 100-meter point along the stage. The graph is shaded below the data line to visualize the mountainous altitudes encountered on the 187.5-kilometre stage. The three largest climbs are highlighted at Col de la Joux, Côte de Viry and the final 11.7-kilometer, 6.4% gradient climb to Montée de la Combe de Laisia Les Molunes which peaks at 1200 meters above sea level. The stage passes through the villages of Arbois, Montrond, Bonlieu, Chassal and Saint-Claude along the route.'
				},

				credits: {
					enabled: false
				},

				annotations: [{
					labelOptions: {
						backgroundColor: 'rgba(255,255,255,0.5)',
						verticalAlign: 'top',
						y: 15
					},
					labels: [{
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 27.98,
							y: 255
						},
						text: 'Arbois'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 45.5,
							y: 611
						},
						text: 'Montrond'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 63,
							y: 651
						},
						text: 'Mont-sur-Monnet'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 84,
							y: 789
						},
						x: -10,
						text: 'Bonlieu'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 129.5,
							y: 382
						},
						text: 'Chassal'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 159,
							y: 443
						},
						text: 'Saint-Claude'
					}]
				}, {
					labels: [{
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 101.44,
							y: 1026
						},
						x: -30,
						text: 'Col de la Joux'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 138.5,
							y: 748
						},
						text: 'Côte de Viry'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 176.4,
							y: 1202
						},
						text: 'Montée de la Combe<br>de Laisia Les Molunes'
					}]
				}, {
					labelOptions: {
						shape: 'connector',
						align: 'right',
						justify: false,
						crop: true,
						style: {
							fontSize: '0.8em',
							textOutline: '1px white'
						}
					},
					labels: [{
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 96.2,
							y: 783
						},
						text: '6.1 km climb<br>4.6% on avg.'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 134.5,
							y: 540
						},
						text: '7.6 km climb<br>5.2% on avg.'
					}, {
						point: {
							xAxis: 0,
							yAxis: 0,
							x: 172.2,
							y: 925
						},
						text: '11.7 km climb<br>6.4% on avg.'
					}]
				}],

				xAxis: {
					labels: {
						format: '{value} km'
					},
					minRange: 5,
					title: {
						text: 'Distance'
					},
					accessibility: {
						rangeDescription: 'Range: 0 to 187.8km.'
					}
				},

				yAxis: {
					startOnTick: true,
					endOnTick: false,
					maxPadding: 0.35,
					title: {
						text: null
					},
					labels: {
						format: '{value} m'
					}
				},

				tooltip: {
					headerFormat: 'Distance: {point.x:.1f} km<br>',
					pointFormat: '{point.y} m a. s. l.',
					shared: true
				},

				legend: {
					enabled: false
				},

				series: [{
					accessibility: {
						keyboardNavigation: {
							enabled: false
						}
					},
					data: elevationData,
					lineColor: Highcharts.getOptions().colors[1],
					color: Highcharts.getOptions().colors[2],
					fillOpacity: 0.5,
					name: 'Elevation',
					marker: {
						enabled: false
					},
					threshold: null
				}]

			});
			
			Highcharts.chart('chart-5', {
				chart: {
					type: 'pie',
					options3d: {
						enabled: true,
						alpha: 45
					}
				},
				title: {
					text: 'Contents of Highsoft\'s weekly fruit delivery'
				},
				subtitle: {
					text: '3D donut in Highcharts'
				},
				plotOptions: {
					pie: {
						innerSize: 100,
						depth: 45
					}
				},
				series: [{
					name: 'Delivered amount',
					data: [
						['Bananas', 8],
						['Kiwi', 3],
						['Mixed nuts', 1],
						['Oranges', 6],
						['Apples', 8],
						['Pears', 4],
						['Clementines', 4],
						['Reddish (bag)', 1],
						['Grapes (bunch)', 1]
					]
				}]
			});
			
			var gaugeOptions = {
				chart: {
					type: 'gauge'
				},

				title: null,

				pane: {
					center: ['50%', '85%'],
					size: '140%',
					startAngle: -90,
					endAngle: 90,
					background: {
						backgroundColor:
							Highcharts.defaultOptions.legend.backgroundColor || '#EEE',
						innerRadius: '60%',
						outerRadius: '100%',
						shape: 'arc'
					}
				},

				exporting: {
					enabled: false
				},

				tooltip: {
					enabled: false
				},
				
				yAxis: {
					stops: [
						[0.1, '#55BF3B'],
						[0.5, '#DDDF0D'],
						[0.9, '#DF5353']
					],
					lineWidth: 0,
					tickWidth: 0,
					minorTickInterval: null,
					tickAmount: 2,
					title: {
						y: -70
					},
					labels: {
						y: 16
					}
				},

				plotOptions: {
					solidgauge: {
						dataLabels: {
							y: 5,
							borderWidth: 0,
							useHTML: true
						}
					}
				}
			};
			
			var chartSpeed = Highcharts.chart('chart-speed', Highcharts.merge(gaugeOptions, {
				yAxis: {
					min: 0,
					max: 200,
					title: {
						text: 'Speed'
					}
				},

				credits: {
					enabled: false
				},

				series: [{
					name: 'Speed',
					data: [80],
					dataLabels: {
						format:
							'<div style="text-align:center">' +
							'<span style="font-size:25px">{y}</span><br/>' +
							'<span style="font-size:12px;opacity:0.4">km/h</span>' +
							'</div>'
					},
					tooltip: {
						valueSuffix: ' km/h'
					}
				}]

			}));
			
			var chartRpm = Highcharts.chart('chart-rpm', Highcharts.merge(gaugeOptions, {
				yAxis: {
					min: 0,
					max: 5,
					title: {
						text: 'RPM'
					}
				},

				series: [{
					name: 'RPM',
					data: [1],
					dataLabels: {
						format:
							'<div style="text-align:center">' +
							'<span style="font-size:25px">{y:.1f}</span><br/>' +
							'<span style="font-size:12px;opacity:0.4">' +
							'* 1000 / min' +
							'</span>' +
							'</div>'
					},
					tooltip: {
						valueSuffix: ' revolutions/min'
					}
				}]

			}));
			
			setInterval(function () {
				var point,
					newVal,
					inc;

				if (chartSpeed) {
					point = chartSpeed.series[0].points[0];
					inc = Math.round((Math.random() - 0.5) * 100);
					newVal = point.y + inc;

					if (newVal < 0 || newVal > 200) {
						newVal = point.y - inc;
					}

					point.update(newVal);
				}
				
				if (chartRpm) {
					point = chartRpm.series[0].points[0];
					inc = Math.random() - 0.5;
					newVal = point.y + inc;

					if (newVal < 0 || newVal > 5) {
						newVal = point.y - inc;
					}

					point.update(newVal);
				}
			}, 2000);

			Highcharts.chart('visitor-chart',
			{
				chart:
				{
					type: 'areaspline'
				},
				title:
				{
					text: '<b><?php echo phrase('visitor_graph'); ?></b>'
				},
				legend:
				{
					layout: 'vertical',
					align: 'left',
					verticalAlign: 'top',
					x: 150,
					y: 100,
					floating: true,
					borderWidth: 1,
					borderRadius: 5
				},
				xAxis:
				{
					categories: <?php echo json_encode($visitors->categories); ?>,
					plotBands:
					[{
						from: 4.5,
						to: 6.5,
						color: 'rgba(68, 170, 213, .2)'
					}]
				},
				yAxis:
				{
					title:
					{
						text: '<?php echo phrase('visitor_total'); ?>'
					},
					allowDecimals: false
				},
				tooltip:
				{
					shared: true,
					valueSuffix: ' <?php echo phrase('users'); ?>'
				},
				credits:
				{
					enabled: false
				},
				plotOptions:
				{
					areaspline:
					{
						fillOpacity: .5
					}
				},
				series:
				[{
					name: '<?php echo phrase('front_end'); ?>',
					data: <?php echo json_encode($visitors->frontend); ?>
				},
				{
					name: '<?php echo phrase('back_end'); ?>',
					data: <?php echo json_encode($visitors->backend); ?>
				}]
			})
		}),
		
		$.ajax
		({
			url: '<?php echo current_page(); ?>',
			method: 'POST',
			data:
			{
				request: 'count_upload'
			},
			beforeSend: function()
			{
				var size		= 0;
				interval		= setInterval(function()
				{
					$('.uploaded-file').text(size);
					size++;
				}, 50)
			}
		})
		.done(function(response)
		{
			$('.uploaded-file').text(response.size),
			clearInterval(interval)
		})
	})
</script>