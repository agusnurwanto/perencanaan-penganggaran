<?php
	//$results							= $resultss['field_data'];
	//$results							= json_decode(json_encode($results));
?>
<div class="container-fluid pt-3 pb-3">
	<div class="row">
		<div class="col-md-4 text-center">
			<p>
				<b>
					BAPPEDA
				</b>
			</p>
			<p>
				<a href="#" class="btn btn-toggle btn-sm <?php echo (isset($results->perencanaan) && 1 == $results->perencanaan ? 'active' : 'inactive'); ?>">
					<span class="handle"></span>
				</a>
			</p>
			<p class="verifikator text-center text-sm">
				<?php echo (isset($results->perencanaan) && 1 == $results->perencanaan ? 'Disetujui oleh <b>' . $results->nama_operator_perencanaan . '</b> pada ' . date_indo($results->waktu_verifikasi_perencanaan, 3, '-') : null); ?>
			</p>
		</div>
		<div class="col-md-4 text-center">
			<p>
				<b>
					BPKAD
				</b>
			</p>
			<p>
				<a href="#" class="btn btn-toggle btn-sm <?php echo (isset($results->keuangan) && 1 == $results->keuangan ? 'active' : 'inactive'); ?>">
					<span class="handle"></span>
				</a>
			</p>
			<p class="verifikator text-center text-sm">
				<?php echo (isset($results->keuangan) && 1 == $results->keuangan ? 'Disetujui oleh <b>' . $results->nama_operator_keuangan . '</b> pada ' . date_indo($results->waktu_verifikasi_keuangan, 3, '-') : null); ?>
			</p>
		</div>
		<div class="col-md-4 text-center">
			<p>
				<b>
					SETDA
				</b>
			</p>
			<p>
				<a href="#" class="btn btn-toggle btn-sm <?php echo (isset($results->setda) && 1 == $results->setda ? 'active' : 'inactive'); ?>">
					<span class="handle"></span>
				</a>
			</p>
			<p class="verifikator text-center text-sm">
				<?php echo (isset($results->setda) && 1 == $results->setda ? 'Disetujui oleh <b>' . $results->nama_operator_setda . '</b> pada ' . date_indo($results->waktu_verifikasi_setda, 3, '-'): null); ?>
			</p>
		</div>
	</div>
</div>
