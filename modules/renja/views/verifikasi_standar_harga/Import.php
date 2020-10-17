<div class="container-fluid pt-3 pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-6">
				<div class="alert alert-info">
					<p>
						Anda dapat menambahkan barang dengan cara mengimpor dari berkas excel sesuai dengan format yang diberikan.
					</p>
					<a href="<?php echo get_image('_import_tmp', 'TEMPLATE-IMPORT-PENERIMAAN-RINCI.xls'); ?>" class="btn btn-success btn-sm" target="_blank">
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
			</div>
		</div>
		<div class="opt-btn-overlap-fix"></div>
		<!-- fix the overlap -->
		<div class="row opt-btn">
			<div class="col-md-6">
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<a href="<?php echo current_page('../'); ?>" class="btn btn-light --xhr">
					<i class="mdi mdi-arrow-left"></i>
					Kembali
				</a>
				<button type="submit" class="btn btn-primary float-right">
					<i class="mdi mdi-check"></i>
					Import
					<em class="text-sm">(ctrl+s)</em>
				</button>
			</div>
		</div>
	</form>
</div>