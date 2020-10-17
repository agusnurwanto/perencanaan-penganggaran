<?php
	$field							= $results['field_data'];
	//print_r($field);exit;
?>
<?php echo ($modal ? '<div class="container-fluid"><div class="row"><div class="col-md-10 col-md-offset-1">' : null); ?>
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
		<div class="form-group animated zoomIn">
			<?php echo (isset($field['map_coordinates']['content']) ? $field['map_coordinates']['content'] : null); ?>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<?php if($riwayat_skpd) { ?>
						<div class="panel panel-default">
							<div class="panel-heading no-padding">
								<a data-toggle="collapse" data-parent="#data-table" href="#collapse3">
									<div class="info-box bg-default no-margin">
										<span class="info-box-icon">
											<i class="fa fa-clock-o"></i>
										</span>
										<div class="info-box-content">
											<span class="info-box-number">
												Riwayat Perubahan SKPD
											</span>
											<span class="info-box-text">
												Klik untuk melihat detail
											</span>
										</div>
									</div>
								</a>
							</div>
							<div id="collapse3" class="panel-collapse collapse">
								<div class="panel-body">
									<?php echo $riwayat_skpd; ?>
								</div>
							</div>
						</div>
					<?php } ?>
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Visi : 
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Misi : 
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Tujuan :
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Sasaran : 
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Urusan :
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Bidang :
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Unit :
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Sub Unit :
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									<?php echo $field['kd_urusan']['label']; ?> : <?php echo $field['kd_urusan']['content']; ?>
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kegiatan_input">
									<?php echo $field['nm_program']['label']; ?>
								</label>
								<h4>
									<?php echo $field['nm_program']['content']; ?>
								</h4>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kegiatan_input">
									<?php echo $field['kegiatan']['label']; ?>
								</label>
								<h4>
									<?php echo $field['kegiatan']['content']; ?>
								</h4>
							</div>
							<?php if($capaian_program) { ?>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="jenis_pekerjaan_input">
									Capaian Program
								</label>
								<?php echo $capaian_program; ?>
							</div>
							<?php } ?>
							<?php if(isset($field['kelompok_sasaran']['label'])) { ?>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kelompok_sasaran_input">
									<?php echo $field['kelompok_sasaran']['label']; ?>
								</label>
								<h4>
									<?php echo $field['kelompok_sasaran']['content']; ?>
								</h4>
							</div>
							<?php } ?>
							<?php if(isset($field['waktu_pelaksanaan']['label'])) { ?>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="waktu_pelaksanaan_input">
									<?php echo $field['waktu_pelaksanaan']['label']; ?>
								</label>
								<h4>
									<?php echo $field['waktu_pelaksanaan']['content']; ?>
								</h4>
							</div>
							<?php } ?>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="waktu_pelaksanaan_input">
									Pagu
								</label>
								<h4>
									<?php echo (isset($field['pagu']) ? $field['pagu']['content'] : number_format(0)); ?>
								</h4>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="map_address_input">
									<?php echo (isset($field['map_address']['label']) ? $field['map_address']['label'] : null); ?>
								</label>
								<h4>
									<?php echo (isset($field['map_address']['content']) ? $field['map_address']['content'] : null); ?>
								</h4>
							</div>
							<?php if(isset($field['alamat_detail'])) { ?>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="alamat_detail_input">
									<?php echo $field['alamat_detail']['label']; ?>
								</label>
								<h4>
									<?php echo $field['alamat_detail']['content']; ?>
								</h4>
							</div>
							<?php } ?>
							<div class="survey-holder">
							</div>
							<?php if(isset($field['pilihan']['label'])) { ?>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="pilihan_input">
									<?php echo $field['pilihan']['label']; ?>
								</label>
								<h4>
									<?php echo ($field['pilihan']['content'] ? 'Menggunakan Model' : 'Input RKA'); ?>
								</h4>
							</div>
							<?php } ?>
							<?php if(isset($field['pilihan']['content']) && $field['pilihan']['content'] == 1 && isset($model['model_isu']) && $model) { ?>
								<div class="load_model">
									<div class="form-group animated zoomIn">
										<label class="control-label big-label text-muted text-uppercase" for="model_isu_input">
											Isu Model
										</label>
										<h4>
											<?php echo (isset($model['model_isu']) ? $model['model_isu'] : null); ?>
										</h4>
									</div>
									<div class="form-group animated zoomIn">
										<label class="control-label big-label text-muted text-uppercase" for="model_input">
											Model
										</label>
										<h4>
											<?php echo (isset($model['model']) ? $model['model'] : null); ?>
										</h4>
									</div>
									<div class="form-group animated zoomIn model-variable">
										<label class="control-label big-label text-muted text-uppercase" for="model_input">
											Variabel
										</label>
										<?php echo (isset($model['variabel']) ? $model['variabel'] : null); ?>
									</div>
								</div>
							<?php } ?>
							<div class="form-group animated zoomIn">
								<div class="row">
									<?php if(isset($field['images']['label'])) { ?>
									<div class="col-sm-6">
										<label class="control-label big-label text-muted text-uppercase" for="images_input">
											Foto 
										</label>
										<?php echo $field['images']['content']; ?>
									</div>
									<?php } ?>
									<div class="col-sm-6">
										<label class="control-label big-label text-muted text-uppercase" for="images_input">
											Dibuat pada
										</label>
										<?php echo $field['created']['content']; ?>
										<label class="control-label big-label text-muted text-uppercase" for="images_input">
											Diperbarui pada 
										</label>
										<?php echo $field['updated']['content']; ?>
									</div>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="row">
				<div class="col-md-5 col-md-offset-6 callback-status">
					<div class="btn-group btn-group-justified">
						<?php
							if($modal)
							{
								echo '
									<a href="javascript:void(0)" class="btn btn-primary btn-holo" data-dismiss="modal">
										<i class="fa fa-times"></i>
										&nbsp;
										' . phrase('cancel') . '
										<small class="hidden-xs hidden-sm" style="font-size:10px;color:#cacaca">(ESC)</small>
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
						<div class="btn-group">
							<a href="<?php echo go_to(null, array('id' => null)); ?>" class="btn btn-primary btn-holo ajaxLoad">
								<i class="fa fa-chevron-left"></i>
								&nbsp;
								<?php echo phrase('back'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo ($modal ? '</div></div></div>' : null); ?>