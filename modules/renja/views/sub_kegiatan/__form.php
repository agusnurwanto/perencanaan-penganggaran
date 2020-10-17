<?php
	$field							= $results->form_data;
?>
<div class="container-fluid pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		
		<div class="row form-group">
			<?php echo $field->map_coordinates->content; ?>
		</div>
		
		<div class="row">
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? 12 : 12); ?>">
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="id_prog_input">
								<?php echo ($field->id_prog->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Program
							</label>
							<?php echo $field->id_prog->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="kode_input">
								<?php echo ($field->kd_keg->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Kode
							</label>
							<?php echo $field->kd_keg->content; ?>
						</div>
						<!--<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="jenis_pekerjaan_input">
								<?php //echo ($field->jenis_kegiatan_renja->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Jenis Kegiatan
							</label>
							<?php //echo $field->jenis_kegiatan_renja->content; ?>
						</div>-->
						<div class="form-group jenis_pekerjaan-variable">
							<?php //echo $variabel; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="kegiatan_input">
								<?php echo ($field->kegiatan->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Nama Kegiatan
							</label>
							<?php echo $field->kegiatan->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="kelompok_sasaran_input">
								<?php echo ($field->kelompok_sasaran->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Kelompok Sasaran
							</label>
							<?php echo $field->kelompok_sasaran->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="waktu_pelaksanaan_input">
								<?php echo ($field->waktu_pelaksanaan->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Waktu Pelaksanaan
							</label>
							<?php echo $field->waktu_pelaksanaan->content; ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="pagu_input">
								<?php echo ($field->pagu->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Pagu Anggaran
							</label>
							<?php
								if($field->pagu->prepend || $field->pagu->append)
								{
									echo '<div class="input-group">';
								}
								
								if($field->pagu->prepend)
								{
									echo '
										<div class="input-group-prepend">
											<span class="input-group-text">
												' . $field->pagu->prepend . '
											</span>
										</div>
									';
								}
								
								echo $field->pagu->content;
								
								if($field->pagu->append)
								{
									echo '
										<div class="input-group-prepend">
											<span class="input-group-text">
												' . $field->pagu->append . '
											</span>
										</div>
									';
								}
								
								if($field->pagu->prepend || $field->pagu->append)
								{
									echo '</div>';
								}
							?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="pagu_1_input">
								<?php echo ($field->pagu_1->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Pagu N + 1
							</label>
							<?php
								if($field->pagu_1->prepend || $field->pagu_1->append)
								{
									echo '<div class="input-group">';
								}
								
								if($field->pagu_1->prepend)
								{
									echo '
										<div class="input-group-prepend">
											<span class="input-group-text">
												' . $field->pagu_1->prepend . '
											</span>
										</div>
									';
								}
								
								echo $field->pagu_1->content;
								
								if($field->pagu_1->append)
								{
									echo '
										<div class="input-group-prepend">
											<span class="input-group-text">
												' . $field->pagu_1->append . '
											</span>
										</div>
									';
								}
								
								if($field->pagu_1->prepend || $field->pagu_1->append)
								{
									echo '</div>';
								}
							?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="map_address_input">
								<?php echo ($field->map_address->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Alamat
							</label>
							<?php echo $field->map_address->content; ?>
						</div>
						<!--<div class="form-group address_details<?php //echo (!$field->map_address->original ? ' hidden' : null); ?>">
							<label class="d-block text-muted text-uppercase" for="alamat_detail_input">
								<?php //echo ($field->alamat_detail->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Alamat Detail
							</label>
							<?php //echo $field->alamat_detail->content; ?>
						</div>-->
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="alamat_detail_input">
								<?php echo ($field->alamat_detail->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Alamat Detail
							</label>
							<?php echo $field->alamat_detail->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="id_kel_input">
								<?php echo ($field->id_kel->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Kelurahan
							</label>
							<?php echo $field->id_kel->content; ?>
						</div>
					</div>
					<div class="col-md-4">
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="id_sumber_dana_input">
								<?php echo ($field->id_sumber_dana->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Sumber Dana
							</label>
							<?php echo $field->id_sumber_dana->content; ?>
						</div>
						<div class="survey-holder">
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="pilihan_input">
								<?php echo ($field->pilihan->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Pilihan
							</label>
							<label class="control-label big-label text-muted">
								<input type="radio" name="pilihan" class="pilih-model" value="1"<?php echo ('create' == $this->_method || 1 == $field->pilihan->original ? ' checked' : null); ?> />
								Model
							</label>
							<label class="control-label big-label text-muted">
								<input type="radio" name="pilihan" class="pilih-model" value="0"<?php echo ('create' == $this->_method || 0 == $field->pilihan->original ? ' checked' : null); ?> />
								RKA
							</label>
						</div>
						<div class="load_model">
							<div class="form-group">
								<label class="d-block text-muted text-uppercase" for="model_isu_input">
									<?php echo (isset($field->isu) && $field->isu->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
									Isu Model
								</label>
								<?php echo $model_isu; ?>
							</div>
							<div class="form-group">
								<label class="d-block text-muted text-uppercase" for="model_input">
									<?php echo ($field->id_model->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
									Model
								</label>
								<?php echo $model; ?>
							</div>
							<div class="form-group model-variable">
								<?php echo $model_variabel; ?>
							</div>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="jenis_usulan_input">
								<?php echo ($field->jenis_usulan->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Jenis Usulan
							</label>
							<?php echo $field->jenis_usulan->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="jenis_anggaran_input">
								<?php echo ($field->jenis_anggaran->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
								Jenis Anggaran
							</label>
							<?php echo $field->jenis_anggaran->content; ?>
						</div>
						<div class="latar-belakang-perubahan d-none">
							<div class="form-group">
								<label class="d-block text-muted text-uppercase" for="latar_belakang_perubahan_input">
									<?php echo ($field->latar_belakang_perubahan->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
									Latar Belakang Perubahan
								</label>
								<?php echo $field->latar_belakang_perubahan->content; ?>
							</div>
						</div>
						<div class="form-group">
							<label class="d-block text-muted text-uppercase" for="images_input">
								<?php echo ($field->images->required ? '<span class="text-sm text-capitalize text-danger float-right">' . phrase('required') . '</span>' : null); ?>
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
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? '12 text-right' : 12); ?>">
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