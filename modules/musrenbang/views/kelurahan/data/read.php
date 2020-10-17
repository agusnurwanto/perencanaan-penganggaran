<?php echo ($modal ? '<div class="container-fluid"><div class="row"><div class="col-md-8 col-md-offset-2">' : null); ?>
	<div class="box no-border">
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
				<button type="button" class="btn btn-box-tool" <?php echo ($modal ? 'data-dismiss="modal"' : 'data-widget="remove"'); ?>>
					<i class="fa fa-times"></i>
				</button>
			</div>
			<h3 class="box-title">
				<i class="<?php echo $icon; ?>"></i>
				&nbsp;
				<?php echo $title; ?>
			</h3>
		</div>
		<div class="form-group">
			<div class="drawing-placeholder">
				<div id="maps" role="maps" data-initialize="marker" data-draggable="false" data-coordinate="<?php echo strip_tags(htmlspecialchars($view_data->map_coordinates)); ?>" data-applyto="map_coordinate" style="height:200px"></div>
			</div>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="form-group data-table">
						<div class="panel panel-default">
							<div class="panel-heading no-padding">
								<a data-toggle="collapse" data-parent="#data-table" href="#collapse_data-table">
									<div class="info-box bg-default no-margin">
										<span class="info-box-icon">
											<i class="fa fa-info-circle"></i>
										</span>
										<div class="info-box-content">
											<span class="info-box-number">
												Detail Musrenbang Usulan
											</span>
											<span class="info-box-text">
												Klik untuk melihat detail
											</span>
										</div>
									</div>
								</a>
							</div>
							<div id="collapse_data-table" class="panel-collapse collapse">
								<div class="panel-body">
									<div class="row">
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Kode
												</label>
												<label class="control-label big-label">
													<?php echo $view_data->kode; ?>
												</label>
											</div>
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Isu
												</label>
												<label class="control-label big-label">
													<?php echo $view_data->nama_isu; ?>
												</label>
											</div>
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Jenis Pekerjaan
												</label>
												<label class="control-label big-label">
													<?php echo $view_data->nama_pekerjaan; ?>
												</label>
											</div>
											<div class="form-group">
												<div class="alert alert-info">
													<?php echo $view_data->deskripsi; ?>
												</div>
											</div>
											<?php
												$variabel				= null;
												if($view_data->variabel_kelurahan)
												{
													foreach($view_data->variabel_kelurahan as $key => $val)
													{
														$variabel		.= '
															<div class="row">
																<div class="col-xs-2">
																	' . $val['kode_variabel'] . '
																</div>
																<div class="col-xs-4">
																	' . $val['nama_variabel'] . '
																</div>
																<div class="col-xs-4">
																	' . number_format($val['nilai']) . '
																</div>
																<div class="col-xs-2">
																	' . $val['satuan'] . '
																</div>
															</div>
														';
													}
												}
											?>
											<div class="form-group">
												<?php echo $variabel; ?>
											</div>
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Nilai Usulan
												</label>
												<label class="control-label big-label">
													Rp. <?php echo number_format($view_data->nilai_usulan, 2); ?>;
												</label>
											</div>
										</div>
										<div class="col-sm-6">
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Alamat
												</label>
												<label class="control-label big-label">
													<?php echo $view_data->map_address; ?>
												</label>
											</div>
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Kecamatan
												</label>
												<label class="control-label big-label">
													<?php echo $view_data->kecamatan; ?>
												</label>
											</div>
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Kelurahan
												</label>
												<label class="control-label big-label">
													<?php echo $view_data->nama_kelurahan; ?>
												</label>
											</div>
											<div class="form-group">
												<div class="row">
													<div class="col-xs-6">
														<label class="control-label big-label text-uppercase text-muted">
															RW
														</label>
														<label class="control-label big-label">
															<?php echo $view_data->rw; ?>
														</label>
													</div>
													<div class="col-xs-6">
														<label class="control-label big-label text-uppercase text-muted">
															RT
														</label>
														<label class="control-label big-label">
															<?php echo $view_data->rt; ?>
														</label>
													</div>
												</div>
											</div>
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Urgensi
												</label>
												<label class="control-label big-label">
													<?php echo $view_data->urgensi; ?>
												</label>
											</div>
											<?php
												$images					= null;
												if($view_data->images)
												{
													foreach($view_data->images as $file => $label)
													{
														$images			.= '
															<div class="col-xs-2 col-sm-3">
																<a href="' . get_image('kegiatan', $file) . '" target="_blank">
																	<img src="' . get_image('kegiatan', $file, 'thumb') . '" class="img-responsive" />
																</a>
															</div>
														';
													}
												}
												if($images)
												{
													echo '
														<div class="form-group">
															<label class="control-label big-label text-uppercase text-muted">
																Foto
															</label>
															<div class="row">
																' . $images . '
															</div>
														</div>
													';
												}
											?>
											<div class="form-group">
												<label class="control-label big-label text-uppercase text-muted">
													Status
												</label>
												<label class="control-label big-label">
													<?php
														if($view_data->flag == 0)
														{
															echo '<label class="label bg-primary">Usulan</label>';
														}
														elseif($view_data->flag == 1)
														{
															echo '<label class="label bg-teal">Diterima Kelurahan</label>';
														}
														elseif($view_data->flag == 2)
														{
															echo '<label class="label bg-yellow">Ditolak Kelurahan</label>';
														}
														elseif($view_data->flag == 3)
														{
															echo '<label class="label bg-green">Usulan Kelurahan</label>';
														}
														elseif($view_data->flag == 4)
														{
															echo '<label class="label bg-green">Diterima Kecamatan</label>';
														}
														elseif($view_data->flag == 5)
														{
															echo '<label class="label bg-green">Ditolak Kecamatan</label>';
														}
														elseif($view_data->flag == 6)
														{
															echo '<label class="label bg-red">Usulan Kecamatan</label>';
														}
														elseif($view_data->flag == 7)
														{
															echo '<label class="label bg-aqua">Diterima SKPD</label>';
														}
														elseif($view_data->flag == 8)
														{
															echo '<label class="label bg-maroon">Ditolak SKPD</label>';
														}
													?>
												</label>
											</div>
										</div>
									</div>
								</div>
							</div>
						</div>
					</div>
					<div class="row">
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Kode
								</label>
								<label class="control-label big-label">
									<?php echo $view_data->kode; ?>
								</label>
							</div>
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Isu
								</label>
								<label class="control-label big-label">
									<?php echo $view_data->nama_isu; ?>
								</label>
							</div>
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Jenis Pekerjaan
								</label>
								<label class="control-label big-label">
									<?php echo $view_data->nama_pekerjaan; ?>
								</label>
							</div>
							<div class="form-group">
								<div class="alert alert-info">
									<?php echo $view_data->deskripsi; ?>
								</div>
							</div>
							<?php
								$variabel				= null;
								if($view_data->variabel_kelurahan)
								{
									foreach($view_data->variabel_kelurahan as $key => $val)
									{
										$variabel		.= '
											<div class="row">
												<div class="col-xs-2">
													' . $val['kode_variabel'] . '
												</div>
												<div class="col-xs-4">
													' . $val['nama_variabel'] . '
												</div>
												<div class="col-xs-4">
													' . number_format($val['nilai']) . '
												</div>
												<div class="col-xs-2">
													' . $val['satuan'] . '
												</div>
											</div>
										';
									}
								}
							?>
							<div class="form-group">
								<?php echo $variabel; ?>
							</div>
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Nilai Kelurahan
								</label>
								<label class="control-label big-label">
									Rp. <?php echo number_format($view_data->nilai_kelurahan, 2); ?>;
								</label>
							</div>
						</div>
						<div class="col-sm-6">
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Alamat
								</label>
								<label class="control-label big-label">
									<?php echo $view_data->map_address; ?>
								</label>
							</div>
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Kecamatan
								</label>
								<label class="control-label big-label">
									<?php echo $view_data->kecamatan; ?>
								</label>
							</div>
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Kelurahan
								</label>
								<label class="control-label big-label">
									<?php echo $view_data->nama_kelurahan; ?>
								</label>
							</div>
							<div class="form-group">
								<div class="row">
									<div class="col-xs-6">
										<label class="control-label big-label text-uppercase text-muted">
											RW
										</label>
										<label class="control-label big-label">
											<?php echo $view_data->rw; ?>
										</label>
									</div>
									<div class="col-xs-6">
										<label class="control-label big-label text-uppercase text-muted">
											RT
										</label>
										<label class="control-label big-label">
											<?php echo $view_data->rt; ?>
										</label>
									</div>
								</div>
							</div>
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Urgensi
								</label>
								<label class="control-label big-label">
									<?php echo $view_data->urgensi; ?>
								</label>
							</div>
							<?php
								$images					= null;
								if($view_data->images)
								{
									foreach($view_data->images as $file => $label)
									{
										$images			.= '
											<div class="col-xs-2 col-sm-3">
												<a href="' . get_image('kegiatan', $file) . '" target="_blank">
													<img src="' . get_image('kegiatan', $file, 'thumb') . '" class="img-responsive" />
												</a>
											</div>
										';
									}
								}
								if($images)
								{
									echo '
										<div class="form-group">
											<label class="control-label big-label text-uppercase text-muted">
												Foto
											</label>
											<div class="row">
												' . $images . '
											</div>
										</div>
									';
								}
							?>
							<div class="form-group">
								<label class="control-label big-label text-uppercase text-muted">
									Status
								</label>
								<label class="control-label big-label">
									<?php
										if($view_data->flag == 0)
										{
											echo '<label class="label bg-primary">Usulan</label>';
										}
										elseif($view_data->flag == 1)
										{
											echo '<label class="label bg-teal">Diterima Kelurahan</label>';
										}
										elseif($view_data->flag == 2)
										{
											echo '<label class="label bg-yellow">Ditolak Kelurahan</label>';
										}
										elseif($view_data->flag == 3)
										{
											echo '<label class="label bg-green">Usulan Kelurahan</label>';
										}
										elseif($view_data->flag == 4)
										{
											echo '<label class="label bg-green">Diterima Kecamatan</label>';
										}
										elseif($view_data->flag == 5)
										{
											echo '<label class="label bg-green">Ditolak Kecamatan</label>';
										}
										elseif($view_data->flag == 6)
										{
											echo '<label class="label bg-red">Usulan Kecamatan</label>';
										}
										elseif($view_data->flag == 7)
										{
											echo '<label class="label bg-aqua">Diterima SKPD</label>';
										}
										elseif($view_data->flag == 8)
										{
											echo '<label class="label bg-maroon">Ditolak SKPD</label>';
										}
									?>
								</label>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="row">
				<div class="col-md-5 col-md-offset-6 callback_status">
					<div class="btn-group btn-group-justified">
						<?php
							if($modal)
							{
								echo '
									<a href="javascript:void(0)" class="btn btn-primary btn-holo" data-dismiss="modal">
										<i class="fa fa-times"></i>
										&nbsp;
										' . phrase('cancel') . '
									</a>
								';
							}
							else
							{
								echo '
									<a href="' . go_to(null, array('id' => null)) . '" class="btn btn-primary btn-holo ajaxLoad">
										<i class="fa fa-chevron-left"></i>
										&nbsp;
										' . phrase('back') . '
									</a>
								';
							}
						?>
						<a href="<?php echo go_to('update'); ?>" class="btn btn-primary btn-holo ajax">
							<i class="fa fa-bookmark-o"></i>
							&nbsp;
							<?php echo phrase('verify'); ?>
						</a>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo ($modal ? '</div></div></div>' : null); ?>