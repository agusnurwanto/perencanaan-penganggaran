<?php
	$field							= $results['form_data'];
?>
<?php echo ($modal ? '<div class="container-fluid"><div class="row"><div class="col-md-10 col-md-offset-1">' : null); ?>
	<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo phrase('submitting'); ?>" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" data-icon="check" enctype="multipart/form-data">
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
				<?php echo $field['map_coordinates']['content']; ?>
			</div>
			<div class="box-body">
				<div class="row">
					<div class="col-md-10 col-md-offset-1">
						<div class="row">
							<div class="col-md-6">
								<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="id_prog_input">
										<span class="text-sm text-capitalize text-danger pull-right"><?php echo phrase('required'); ?></span>
										Program
									</label>
									<?php echo $field['id_prog']['content']; ?>
								</div>
								<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="kode_input">
										<span class="text-sm text-capitalize text-danger pull-right"></span>
										Kode
									</label>
									<?php echo $field['kd_keg']['content']; ?>
								</div>
								<!--<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="jenis_pekerjaan_input">
										<span class="text-sm text-capitalize text-danger pull-right"><?php //echo phrase('required'); ?></span>
										Jenis Kegiatan
									</label>
									<?php //echo $field['jenis_kegiatan']['content']; ?>
								</div>
								<div class="form-group animated zoomIn jenis_pekerjaan-variable">
									<?php //echo $variabel; ?>
								</div>-->
								

								<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="kegiatan_input">
										<span class="text-sm text-capitalize text-danger pull-right"></span>
										Nama Kegiatan
									</label>
									<?php  echo $field['kegiatan_judul_baru']['content']; ?>
									
								</div>

								


								<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="kelompok_sasaran_input">
										<span class="text-sm text-capitalize text-danger pull-right"><?php echo phrase('required'); ?></span>
										Kelompok Sasaran
									</label>
									<?php echo $field['kelompok_sasaran']['content']; ?>
								</div>
							</div>
							<div class="col-md-6">
								<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="map_address_input">
										<span class="text-sm text-capitalize text-danger pull-right"><?php echo phrase('required'); ?></span>
										Alamat
									</label>
									<?php echo $field['map_address']['content']; ?>
								</div>
								<div class="form-group animated zoomIn address_details<?php echo (!$field['map_address']['content'] ? ' hidden' : null); ?>">
									<label class="control-label big-label text-muted text-uppercase" for="alamat_detail_input">
										Alamat Detail
									</label>
									<?php echo $field['alamat_detail']['content']; ?>
								</div>
								<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="id_kel_input">
										<span class="text-sm text-capitalize text-danger pull-right"><?php echo phrase('required'); ?></span>
										Kelurahan
									</label>
									<?php echo $field['id_kel']['content']; ?>
								</div>
								<div class="survey-holder">
								</div>
								<div class="form-group animated zoomIn">
									<?php echo $variabel; ?>
								</div>
								<div class="form-group animated zoomIn">
									<label class="control-label big-label text-muted text-uppercase" for="images_input">
										<span class="text-sm text-capitalize text-danger pull-right"><?php echo phrase('required'); ?></span>
										Foto 
									</label>
									<?php echo $field['images']['content']; ?>
								</div>
							</div>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-md-5 col-md-offset-6 callback-status">
						<input type="hidden" name="token" value="<?php echo $token; ?>" />
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
								<button type="submit" name="tolak" class="btn btn-danger tolak" value="1">
									<i class="fa fa-times-circle"></i>
									&nbsp;
									Tolak
								</button>
							</div>
							<div class="btn-group">
								<button type="submit" class="btn btn-success submitBtn" value="1" >
									<i class="fa fa-check"></i>
									&nbsp;
									Terima
							

								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
<?php echo ($modal ? '</div></div></div>' : null); ?>