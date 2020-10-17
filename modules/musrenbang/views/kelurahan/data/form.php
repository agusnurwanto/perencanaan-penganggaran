<?php
	$field							= $results->form_data;
?>
<div class="container-fluid pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		
		<div class="row">
			<?php echo $field->map_coordinates->content; ?>
		</div>
		
		<div class="row">
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? 12 : 10); ?>">
				<div class="row">
					<div class="col-md-6">
						<div class="row">
							<div class="col-3">
								<div class="form-group">
									<label class="d-block text-muted text-uppercase" for="kode_input">
										<span class="text-sm text-capitalize text-danger float-right">*</span>
										No
									</label>
									<?php echo $field->kode->content; ?>
								</div>
							</div>
							<div class="col-9">
								<div class="form-group">
									<label class="d-block text-muted text-uppercase" for="id_rt_input">
										<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
										RT
									</label>
									<?php echo $field->id_rt->content; ?>
								</div>
							</div>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="isu_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Isu/OPD
							</label>
							<?php echo $isu; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="jenis_pekerjaan_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Kelompok Kegiatan 
							</label>
							<?php echo $jenis_pekerjaan; ?>
						</div>
						<div class="form-group jenis_pekerjaan-variable">
							<?php echo $variabel; ?>
						</div>

					</div>
					
					<div class="col-md-6">
						<div class="form-group" placeholder="tst">
							<label class="d-block text-muted text-uppercase" for="map_address_input" >
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Kelurahan dan Kecamatan
							</label>
							
							<?php echo $field->map_address->content; ?>
						</div>
					<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="alamat_detail_input">
								<?php echo ($field->alamat_detail->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Alamat Detail
							</label>
							<?php echo $field->alamat_detail->content; ?>
						</div>

						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="nama_kegiatan_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Nama Kegiatan
							</label>
							<?php echo $field->nama_kegiatan->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="id_prioritas_pembangunan_input">
								<span class="text-sm text-capitalize text-danger float-right"></span>
								Prioritas Pembangunan
							</label>
							<?php echo $field->id_prioritas_pembangunan->content; ?>
						</div>
						<!--<div class="survey-holder">
						</div>-->
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="urgensi_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Urgensi
							</label>
							<?php echo $field->urgensi->content; ?>
						</div>
						<!--<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="kelurahan_input">
								<?php //echo ($field->id_unit->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Nama OPD
							</label>
							<?php //echo $field->id_unit->content; ?>
						</div>-->
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="jenis_usulan_input">
								<?php echo ($field->jenis_usulan->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Jenis Usulan
							</label>
							<?php echo $field->jenis_usulan->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="images_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Foto 
							</label>
							<?php echo $field->images->content; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		
		<?php echo ('modal' == $this->input->post('prefer') ? '<hr class="row" />' : '<div class="opt-btn-overlap-fix"></div><!-- fix the overlap -->'); ?>
		<div class="row<?php echo ('modal' != $this->input->post('prefer') ? ' opt-btn' : null); ?>">
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? '12 text-right' : 10); ?>">
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				
				<?php if('modal' == $this->input->post('prefer')) { ?>
				<button type="button" class="btn btn-link" data-dismiss="modal">
					<?php echo phrase('close'); ?>
					<em class="text-sm">(esc)</em>
				</button>
				<?php } else { ?>
				<a href="<?php echo go_to(null, $results->query_string); ?>" class="btn btn-link --xhr">
					<i class="mdi mdi-arrow-left"></i>
					<?php echo phrase('back'); ?>
				</a>
				<?php } ?>
				
				<button type="submit" class="btn btn-primary float-right">
					<i class="mdi mdi-check"></i>
					Terima
					<em class="text-sm">(ctrl+s)</em>
				</button>
				<button type="submit" name="tolak" class="btn btn-danger tolak float-right" value="1">
					<i class="mdi mdi-window-close"></i>
					Tolak
				</button>
			</div>
		</div>
	</form>
</div>