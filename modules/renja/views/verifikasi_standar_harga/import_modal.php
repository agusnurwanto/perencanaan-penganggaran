<div class="container-fluid pt-3 pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		<div class="alert alert-info">
			<p>
						Anda dapat menambahkan Standar Harga dengan cara mengimpor dari berkas excel sesuai dengan format yang diberikan.
			</p>
			<a href="<?php echo get_image('_import_tmp', 'contoh_form_upload_ssh.xlsx'); ?>" class="btn btn-success btn-sm" target="_blank">
				<i class="mdi mdi-table"></i>
				Download Template
			</a>
		</div>
		<div class="form-group">
			<label class="text-muted d-block" for="file_input">
				Silakan Pilih Berkas
			</label>
			<input type="file" name="file" role="uploader" id="file_input" />
		</div>
		<div class="--validation-callback mb-0"></div>
		<hr class="row" />
		<div class="row">
			<div class="col-md-12">
				<div class="text-right">
					<input type="hidden" name="token" value="<?php echo $token; ?>" />
					<button type="button" class="btn btn-link" data-dismiss="modal">
						Tutup
						<em class="text-sm">(esc)</em>
					</button>
					<button type="submit" class="btn btn-primary float-right">
						<i class="mdi mdi-check"></i>
						Import
						<em class="text-sm">(ctrl+s)</em>
					</button>
				</div>
			</div>
		</div>
	</form>
</div>