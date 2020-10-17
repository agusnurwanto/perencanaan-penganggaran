<div class="container-fluid pt-3 pb-3">
	<div class="row">
		<div class="col-md-5 offset-md-1">
			<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
				<div class="form-group">
					<label class="d-block text-muted">
						Jenis Data
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="penatausahaan" />
						Penatausahaan
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="anggaran" />
						Anggaran
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="standar-harga" />
						Standar Harga
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="renja" />
						Renja
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="program-opd" />
						Program OPD
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="musrenbang" />
						Musrenbang
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="rekening-anggaran" />
						Rekening Anggaran
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="unit-subunit" />
						Unit dan Sub Unit
					</label>
					<label class="d-block">
						<input type="radio" name="jenis" value="referensi-umum" />
						Referensi Umum
					</label>
				</div>
				<div class="--validation-callback mb-0"></div>
				<div class="row">
					<div class="col-md-6">
						<label class="d-block text-muted">
							Kode Akses
						</label>
						<input type="password" name="kode_akses" class="form-control" placeholder="Masukkan Kode Akses" />
						<input type="hidden" name="token" value="<?php echo $token; ?>" />
					</div>
					<div class="col-md-6">
						<label class="d-block text-muted">
							&nbsp;
						</label>
						<button type="submit" class="btn btn-primary btn-block">
							<i class="mdi mdi-check"></i>
							Kosongkan Data
						</button>
					</div>
				</div>
			</form>
		</div>
		<div class="col-md-5">
			<p>
				<b class="text-danger">
					PERHATIAN!
				</b>
			</p>
			<div class="affected-data"></div>
			<p>
				Tindakan pengosongan data ini akan mengosongkan seluruh table yang berkaitan dengan data yang diminta.
				<br />
				Lanjutkan hanya apabila Anda menyadari tentang resiko terkait tindakan yang Anda lakukan!
				<br />
				Isi Kode Akses dengan Password Anda dan Klik tombol "Kosongkan Data"
			</p>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		$('body').on('change', 'input[name=jenis]', function(e)
		{
			e.preventDefault();
			
			if('penatausahaan' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data Penatausahaan yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">SPP</li>' +
						'<li class="text-danger">SPM</li>' +
						'<li class="text-danger">SP2D</li>' +
					'</ol>'
				)
			}
			else if('anggaran' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data Anggaran yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">Verifikasi RKA</li>' +
						'<li class="text-danger">Rencana Kas</li>' +
						'<li class="text-danger">Anggaran Pendapatan</li>' +
						'<li class="text-danger">Anggaran Belanja</li>' +
						'<li class="text-danger">Anggaran Pembiayaan</li>' +
					'</ol>'
				)
			}
			else if('standar-harga' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">Standar Harga Tertinggi</li>' +
						'<li class="text-danger">Standar Biaya Umum</li>' +
					'</ol>'
				)
			}
			else if('renja' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">Verifikasi RKA</li>' +
						'<li class="text-danger">Rencana Kas</li>' +
						'<li class="text-danger">Anggaran Pendapatan</li>' +
						'<li class="text-danger">Anggaran Belanja</li>' +
						'<li class="text-danger">Anggaran Pembiayaan</li>' +
						'<li class="text-danger">Kerangka Acuan Kerja</li>' +
						'<li class="text-danger">Sub Kegiatan</li>' +
						'<li class="text-danger">Indikator Sub Kegiatan</li>' +
						'<li class="text-danger">Kegiatan</li>' +
						'<li class="text-danger">Indikator Kegiatan</li>' +
					'</ol>'
				)
			}
			else if('program-opd' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">Program</li>' +
						'<li class="text-danger">Capaian Program</li>' +
					'</ol>'
				)
			}
			else if('musrenbang' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">Musrenbang RW</li>' +
						'<li class="text-danger">Musrenbang Kelurahan</li>' +
						'<li class="text-danger">Musrenbang Kecamatan</li>' +
						'<li class="text-danger">Musrenbang SKPD</li>' +
					'</ol>'
				)
			}
			else if('rekening-anggaran' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">Rekening Anggaran</li>' +
					'</ol>'
				)
			}
			else if('unit-subunit' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">Unit</li>' +
						'<li class="text-danger">Sub Unit</li>' +
					'</ol>'
				)
			}
			else if('referensi-umum' == $(this).val())
			{
				$('.affected-data').html
				(
					'<b>' +
						'Data yang dihapus meliputi:' +
					'</b>' +
					'<br />' +
					'<ol>' +
						'<li class="text-danger">Referensi Urusan</li>' +
						'<li class="text-danger">Referensi Bidang</li>' +
						'<li class="text-danger">Referensi Program</li>' +
						'<li class="text-danger">Referensi Kegiatan</li>' +
						'<li class="text-danger">Referensi Sub Kegiatan</li>' +
					'</ol>'
				)
			}
		})
	})
</script>