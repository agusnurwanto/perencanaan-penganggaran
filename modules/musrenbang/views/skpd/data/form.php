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
								Kode
							</label>
							<?php echo $field->kode->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="isu_input">
								Isu
							</label>
							<?php echo $isu; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="isu_input">
								Jenis Pekerjaan
							</label>
							<?php echo $jenis_pekerjaan; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="nama_kegiatan_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Kegiatan
							</label>
							<?php echo $field->nama_kegiatan->content; ?>
						</div>
						<div class="form-group jenis_pekerjaan-variable">
							<?php echo $variabel; ?>
						</div>
						<!--<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="alasan_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php //echo phrase('required'); ?></span>
								Prioritas
							</label>
							<?php //echo $field->prioritas_skpd->content; ?>
						</div>-->
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="map_address">
								Alamat
							</label>
							<?php echo $field->map_address->content; ?>
						</div>
						<div class="form-group">
							<?php echo $survey; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="urgensi_input">
								Urgensi
							</label>
							<?php echo $field->urgensi->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="urgensi_input">
								Jenis Usulan
							</label>
							<?php echo $field->jenis_usulan->content;  ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="alasan_input">
								<span class="text-sm text-capitalize text-danger float-right"><?php echo phrase('required'); ?></span>
								Alasan
							</label>
							<?php echo $field->alasan_skpd->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="images_input">
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