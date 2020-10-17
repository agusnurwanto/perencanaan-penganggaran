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
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="kode_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								No
							</label>
							<?php echo $field->kd_keg->content; ?>
						</div>
						<?php if(isset($field->id_reses)) { ?>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="id_reses_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Dewan
							</label>
							<?php echo $field->id_reses->content; ?>
						</div>
						<?php } ?>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="isu_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Isu
							</label>
							<?php echo $isu; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="jenis_kegiatan_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Kelompok Kegiatan
							</label>
							<?php echo $jenis_kegiatan; ?>
						</div>
					<!--	<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="kegiatan_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Nama Kegiatan
							</label>
							<?php echo $field->kegiatan->content; ?>
						</div>-->
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="kegiatan_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Nama Kegiatan
							</label>
							<?php echo $field->kegiatan_judul_baru->content; ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="map_address_input">
								<span class="text-sm text-capitalize text-danger float-right"></span>
								Alamat
							</label>
							<?php echo $field->map_address->content; ?>
						</div>
						<div class="form-group address_details hidden">
							<label class="d-block text-muted text-uppercase" for="alamat_detail_input">
								Alamat Tambahan (RT/RW/Gang)
							</label>
							<?php echo $field->alamat_detail->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="id_kel_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Kelurahan
							</label>
							<?php echo $field->id_kel->content; ?>
						</div>
						<!--<div class="survey-holder">
						</div>-->
						<div class="form-group jenis_pekerjaan-variable">
							<?php echo $variabel; ?>
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
					<?php echo phrase('submit'); ?>
					<em class="text-sm">(ctrl+s)</em>
				</button>
			</div>
		</div>
	</form>
</div>