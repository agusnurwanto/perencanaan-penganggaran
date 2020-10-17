<div class="container-fluid pt-3 pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-3">
				<div class="form-group">
					<label class="d-block text-muted" for="jabatan_kepala_daerah_input">
						<?php echo $results->form_data->jabatan_kepala_daerah->label; ?>
						<?php echo ($results->form_data->jabatan_kepala_daerah->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->jabatan_kepala_daerah->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="nama_kepala_daerah_input">
						<?php echo $results->form_data->nama_kepala_daerah->label; ?>
						<?php echo ($results->form_data->nama_kepala_daerah->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->nama_kepala_daerah->content; ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="d-block text-muted" for="jabatan_sekretaris_daerah_input">
						<?php echo $results->form_data->jabatan_sekretaris_daerah->label; ?>
						<?php echo ($results->form_data->jabatan_sekretaris_daerah->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->jabatan_sekretaris_daerah->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="nama_sekretaris_daerah_input">
						<?php echo $results->form_data->nama_sekretaris_daerah->label; ?>
						<?php echo ($results->form_data->nama_sekretaris_daerah->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->nama_sekretaris_daerah->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="nip_sekretaris_daerah_input">
						<?php echo $results->form_data->nip_sekretaris_daerah->label; ?>
						<?php echo ($results->form_data->nip_sekretaris_daerah->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->nip_sekretaris_daerah->content; ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="d-block text-muted" for="jabatan_kepala_perencanaan_input">
						<?php echo $results->form_data->jabatan_kepala_perencanaan->label; ?>
						<?php echo ($results->form_data->jabatan_kepala_perencanaan->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->jabatan_kepala_perencanaan->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="nama_kepala_perencanaan_input">
						<?php echo $results->form_data->nama_kepala_perencanaan->label; ?>
						<?php echo ($results->form_data->nama_kepala_perencanaan->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->nama_kepala_perencanaan->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="nip_kepala_perencanaan_input">
						<?php echo $results->form_data->nip_kepala_perencanaan->label; ?>
						<?php echo ($results->form_data->nip_kepala_perencanaan->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->nip_kepala_perencanaan->content; ?>
				</div>
			</div>
			<div class="col-md-3">
				<div class="form-group">
					<label class="d-block text-muted" for="jabatan_kepala_keuangan_input">
						<?php echo $results->form_data->jabatan_kepala_keuangan->label; ?>
						<?php echo ($results->form_data->jabatan_kepala_keuangan->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->jabatan_kepala_keuangan->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="nama_kepala_keuangan_input">
						<?php echo $results->form_data->nama_kepala_keuangan->label; ?>
						<?php echo ($results->form_data->nama_kepala_keuangan->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->nama_kepala_keuangan->content; ?>
				</div>
				<div class="form-group">
					<label class="d-block text-muted" for="nip_kepala_keuangan_input">
						<?php echo $results->form_data->nip_kepala_keuangan->label; ?>
						<?php echo ($results->form_data->nip_kepala_keuangan->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->nip_kepala_keuangan->content; ?>
				</div>
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="col-md-6">
				<h4>
					Pengaturan Integrasi SIPD
				</h4>
				<div class="form-group">
					<label class="d-block text-muted" for="server_api_sipd_input">
						<?php echo $results->form_data->server_api_sipd->label; ?>
						<?php echo ($results->form_data->server_api_sipd->required ? '<b class="text-danger">*</b>' : null); ?>
					</label>
					<?php echo $results->form_data->server_api_sipd->content; ?>
				</div>
				<div class="row">
					<div class="col-md-4">
						<div class="form-group">
							<label class="d-block text-muted" for="kode_pemda_input">
								<?php echo $results->form_data->kode_pemda->label; ?>
								<?php echo ($results->form_data->kode_pemda->required ? '<b class="text-danger">*</b>' : null); ?>
							</label>
							<?php echo $results->form_data->kode_pemda->content; ?>
						</div>
					</div>
					<div class="col-md-8">
						<div class="form-group">
							<label class="d-block text-muted" for="token_api_sipd_input">
								<?php echo $results->form_data->token_api_sipd->label; ?>
								<?php echo ($results->form_data->token_api_sipd->required ? '<b class="text-danger">*</b>' : null); ?>
							</label>
							<?php echo $results->form_data->token_api_sipd->content; ?>
						</div>
					</div>
				</div>
			</div>
			<div class="col-md-6">
				<h4>
					Pengaturan Validasi
				</h4>
				<div class="row">
					<div class="col-md-6">
						<div class="form-group">
							<label class="d-block text-muted" for="anggaran_kunci_plafon_input">
								<?php echo $results->form_data->anggaran_kunci_plafon->label; ?>
								<?php echo ($results->form_data->anggaran_kunci_plafon->required ? '<b class="text-danger">*</b>' : null); ?>
							</label>
							<?php echo $results->form_data->anggaran_kunci_plafon->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted" for="anggaran_standar_harga_input">
								<?php echo $results->form_data->anggaran_standar_harga->label; ?>
								<?php echo ($results->form_data->anggaran_standar_harga->required ? '<b class="text-danger">*</b>' : null); ?>
							</label>
							<?php echo $results->form_data->anggaran_standar_harga->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted" for="anggaran_kunci_satuan_input">
								<?php echo $results->form_data->anggaran_kunci_satuan->label; ?>
								<?php echo ($results->form_data->anggaran_kunci_satuan->required ? '<b class="text-danger">*</b>' : null); ?>
							</label>
							<?php echo $results->form_data->anggaran_kunci_satuan->content; ?>
						</div>
					</div>
					<div class="col-md-6">
						<div class="form-group">
							<label class="d-block text-muted" for="anggaran_kunci_standar_ke_rekening_input">
								<?php echo $results->form_data->anggaran_kunci_standar_ke_rekening->label; ?>
								<?php echo ($results->form_data->anggaran_kunci_standar_ke_rekening->required ? '<b class="text-danger">*</b>' : null); ?>
							</label>
							<?php echo $results->form_data->anggaran_kunci_standar_ke_rekening->content; ?>
						</div>
						<div class="form-group">
							<label class="d-block text-muted" for="bank_indikator_input">
								<?php echo $results->form_data->bank_indikator->label; ?>
								<?php echo ($results->form_data->bank_indikator->required ? '<b class="text-danger">*</b>' : null); ?>
							</label>
							<?php echo $results->form_data->bank_indikator->content; ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="--validation-callback mb-0"></div>
		<div class="opt-btn">
			<input type="hidden" name="token" value="<?php echo $token; ?>" />
			<a href="<?php echo current_page('../'); ?>" class="btn btn-link --xhr">
				<i class="mdi mdi-arrow-left"></i>
				<?php echo phrase('back'); ?>
			</a>
			<button type="submit" class="btn btn-primary float-right">
				<i class="mdi mdi-check"></i>
				<?php echo phrase('submit'); ?>
				<em class="text-sm">(ctrl+s)</em>
			</button>
		</div>
	</form>
</div>