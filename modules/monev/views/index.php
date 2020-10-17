<div class="container-fluid">
	<br />
	<div class="row">
		<label class="col-xs-3 col-md-2 control-label text-sm text-muted text-uppercase">
			<b>SKPD</b>
		</label>
		<label class="col-xs-4 col-md-2 text-sm">
			<?php echo (isset($header->kd_urusan) ? $header->kd_urusan : 0) . '.' . (isset($header->kd_bidang) ? sprintf('%02d', $header->kd_bidang) : 0) . '.' . (isset($header->kd_unit) ?  sprintf('%02d', $header->kd_unit) : 0) . '.' . (isset($header->kd_sub) ?  sprintf('%02d', $header->kd_sub) : 0); ?>
		</label>
		<label class="col-xs-5 col-md-7 text-sm">
			<b><?php echo (isset($header->nm_sub) ? $header->nm_sub : 0); ?></b>
		</label>
	</div>
	<div class="row">
		<label class="col-xs-3 col-md-2 control-label text-sm text-muted text-uppercase">
			<b>Program</b>
		</label>
		<label class="col-xs-4 col-md-2 text-sm">
			<?php echo (isset($header->kd_program) ?  sprintf('%02d', $header->kd_program) : 0); ?>
		</label>
		<label class="col-xs-5 col-md-7 text-sm">
			<b><?php echo (isset($header->nm_program) ? $header->nm_program : 0); ?></b>
		</label>
	</div>
	<div class="row">
		<label class="col-xs-3 col-md-2 control-label text-sm text-muted text-uppercase">
			<b>Kegiatan</b>
		</label>
		<label class="col-xs-4 col-md-2 text-sm">
			<?php echo (isset($header->kd_program) ?  sprintf('%02d', $header->kd_program) : 0) . '.' . (isset($header->kd_keg) ?  sprintf('%02d', $header->kd_keg) : 0); ?>
		</label>
		<label class="col-xs-5 col-md-7 text-sm">
			<b><?php echo (isset($header->kegiatan) ? $header->kegiatan : 0); ?></b>
		</label>
	</div>
	<div class="row">
		<label class="col-xs-3 col-md-2 control-label text-sm text-muted text-uppercase">
			<b>Pagu</b>
		</label>
		<label class="col-xs-4 col-md-2 text-sm">
			<?php echo (isset($header->pagu) ?  'Rp ' . number_format_indo($header->pagu) : 0); ?>
		</label>
		<label class="col-xs-5 col-md-7 text-sm">
			<b><?php echo (isset($header->pagu) ? spell_number($header->pagu) . ' Rupiah' : 0); ?></b>
		</label>
	</div>

	<?php
		$keluaran						= null;
		$hasil							= null;
		if($indikator)
		{
			foreach($indikator as $key => $val)
			{
				if(2 == $val->jns_indikator)
				{
					$keluaran			.= '
						<div class="col-md-6">
							<div class="card bordered">
								<div class="card-body text-sm" style="padding:12px">
									<div class="row form-group">
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Tolak Ukur
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											<b>
												' . $val->kd_indikator . '. ' . $val->tolak_ukur . '
											</b>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Target
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											' . $val->target . '
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Satuan
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											' . $val->satuan . '
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan I
											</b>
										</div>
										<div class="col-xs-8 col-md-3">
											<div class="input-group">
												<input type="text" name="keluaran[' . $val->id . '][1]" value="' . (isset($monev_realisasi[$val->id]['tw_1']) ? $monev_realisasi[$val->id]['tw_1'] : 0) . '" class="form-control input-sm bordered" placeholder="0.00"' . (isset($lock->triwulan_1) && 1 == $lock->triwulan_1 ? ' data-toggle="tooltip" title="Triwulan 1 telah dikunci" disabled' : null) . ' />
												<span class="input-group-addon">
													%
												</span>
											</div>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan II
											</b>
										</div>
										<div class="col-xs-8 col-md-3">
											<div class="input-group">
												<input type="text" name="keluaran[' . $val->id . '][2]" value="' . (isset($monev_realisasi[$val->id]['tw_2']) ? $monev_realisasi[$val->id]['tw_2'] : 0) . '" class="form-control input-sm bordered" placeholder="0.00"' . (isset($lock->triwulan_2) && 1 == $lock->triwulan_2 ? ' data-toggle="tooltip" title="Triwulan 2 telah dikunci" disabled' : null) . ' />
												<span class="input-group-addon">
													%
												</span>
											</div>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan III
											</b>
										</div>
										<div class="col-xs-8 col-md-3">
											<div class="input-group">
												<input type="text" name="keluaran[' . $val->id . '][3]" value="' . (isset($monev_realisasi[$val->id]['tw_3']) ? $monev_realisasi[$val->id]['tw_3'] : 0) . '" class="form-control input-sm bordered" placeholder="0.00"' . (isset($lock->triwulan_3) && 1 == $lock->triwulan_3 ? ' data-toggle="tooltip" title="Triwulan 3 telah dikunci" disabled' : null) . ' />
												<span class="input-group-addon">
													%
												</span>
											</div>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan IV
											</b>
										</div>
										<div class="col-xs-8 col-md-3">
											<div class="input-group">
												<input type="text" name="keluaran[' . $val->id . '][4]" value="' . (isset($monev_realisasi[$val->id]['tw_4']) ? $monev_realisasi[$val->id]['tw_4'] : 0) . '" class="form-control input-sm bordered" placeholder="0.00"' . (isset($lock->triwulan_4) && 1 == $lock->triwulan_4 ? ' data-toggle="tooltip" title="Triwulan 4 telah dikunci" disabled' : null) . ' />
												<span class="input-group-addon">
													%
												</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-8 col-xs-offset-4 col-md-9 col-md-offset-3">
											<button type="submit" class="btn btn-info btn-sm">
												<i class="fa fa-check"></i>
												Simpan
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					';
				}
				elseif(3 == $val->jns_indikator)
				{
					$hasil				.= '
						<div class="col-md-6">
							<div class="card bordered">
								<div class="card-body text-sm" style="padding:12px">
									<div class="row form-group">
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Tolak Ukur
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											<b>
												' . $val->kd_indikator . '. ' . $val->tolak_ukur . '
											</b>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Target
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											' . $val->target . '
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Satuan
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											' . $val->satuan . '
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan I
											</b>
										</div>
										<div class="col-xs-8 col-md-3">
											<div class="input-group">
												<input type="text" name="hasil[' . $val->id . '][1]" value="' . (isset($monev_realisasi[$val->id]['tw_1']) ? $monev_realisasi[$val->id]['tw_1'] : 0) . '" class="form-control input-sm bordered" placeholder="0.00"' . (isset($lock->triwulan_1) && 1 == $lock->triwulan_1 ? ' data-toggle="tooltip" title="Triwulan 1 telah dikunci" disabled' : null) . ' />
												<span class="input-group-addon">
													%
												</span>
											</div>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan II
											</b>
										</div>
										<div class="col-xs-8 col-md-3">
											<div class="input-group">
												<input type="text" name="hasil[' . $val->id . '][2]" value="' . (isset($monev_realisasi[$val->id]['tw_2']) ? $monev_realisasi[$val->id]['tw_2'] : 0) . '" class="form-control input-sm bordered" placeholder="0.00"' . (isset($lock->triwulan_2) && 1 == $lock->triwulan_2 ? ' data-toggle="tooltip" title="Triwulan 2 telah dikunci" disabled' : null) . ' />
												<span class="input-group-addon">
													%
												</span>
											</div>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan III
											</b>
										</div>
										<div class="col-xs-8 col-md-3">
											<div class="input-group">
												<input type="text" name="hasil[' . $val->id . '][3]" value="' . (isset($monev_realisasi[$val->id]['tw_3']) ? $monev_realisasi[$val->id]['tw_3'] : 0) . '" class="form-control input-sm bordered" placeholder="0.00"' . (isset($lock->triwulan_3) && 1 == $lock->triwulan_3 ? ' data-toggle="tooltip" title="Triwulan 3 telah dikunci" disabled' : null) . ' />
												<span class="input-group-addon">
													%
												</span>
											</div>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan IV
											</b>
										</div>
										<div class="col-xs-8 col-md-3">
											<div class="input-group">
												<input type="text" name="hasil[' . $val->id . '][4]" value="' . (isset($monev_realisasi[$val->id]['tw_4']) ? $monev_realisasi[$val->id]['tw_4'] : 0) . '" class="form-control input-sm bordered" placeholder="0.00"' . (isset($lock->triwulan_4) && 1 == $lock->triwulan_4 ? ' data-toggle="tooltip" title="Triwulan 4 telah dikunci" disabled' : null) . ' />
												<span class="input-group-addon">
													%
												</span>
											</div>
										</div>
									</div>
									<div class="row">
										<div class="col-xs-8 col-xs-offset-4 col-md-9 col-md-offset-3">
											<button type="submit" class="btn btn-info btn-sm">
												<i class="fa fa-check"></i>
												Simpan
											</button>
										</div>
									</div>
								</div>
							</div>
						</div>
					';
				}
			}
		}
	?>
	<hr />
	<!-- INDIKATOR -->
	<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo phrase('submitting'); ?>" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" data-icon="check" enctype="multipart/form-data">
		<input type="hidden" name="token" value="<?php echo $token; ?>" />
		<h4 class="text-center">
			KELUARAN
		</h4>
		<div class="row">
			<?php echo $keluaran; ?>
		</div>
		<hr />
		<h4 class="text-center">
			HASIL
		</h4>
		<div class="row">
			<?php echo $hasil; ?>
		</div>
	</form>
	
	<div class="row">
		<div class="col-md-6">
			<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo phrase('submitting'); ?>" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" data-icon="check" enctype="multipart/form-data">
				<div class="form-group">
					<textarea name="keterangan" class="form-control" placeholder="Silakan masukkan keterangan" rows="1"><?php echo $keterangan; ?></textarea>
				</div>
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<button type="submit" class="btn btn-info btn-sm">
					<i class="fa fa-check"></i>
					Simpan
				</button>
			</form>
		</div>
	</div>
	<hr />
	<div class="row">
		<div class="col-md-6">
			<!-- REALISASI -->
			<div class="text-center">
				<div id="rencana" style="height:300px"></div>
			</div>
			<h4 class="text-center">
				RENCANA KAS
			</h4>
			<?php
				$data_rencana				= array();
				if($rencana)
				{
					foreach($rencana as $key => $val)
					{
						$data_rencana[]		= array
						(
							'name'			=> $val->Nm_Rek_5,
							'data'			=> array
							(
								(float) $val->tw_1,
								(float) ($val->tw_1 + $val->tw_2),
								(float) ($val->tw_1 + $val->tw_2 + $val->tw_3),
								(float) ($val->tw_1 + $val->tw_2 + $val->tw_3 + $val->tw_4)
							)
						);
						echo '
							<div class="card bordered">
								<div class="card-body text-sm" style="padding:12px">
									<div class="row">
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Rekening
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											<b>
												' . $val->Kd_Rek_1 . '.' . $val->Kd_Rek_2 . '.' . $val->Kd_Rek_3 . '.' . $val->Kd_Rek_4 . '.' . $val->Kd_Rek_5 . ' - ' . $val->Nm_Rek_5 . '
											</b>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan I
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											Rp.
											<span class="pull-right">
												' . number_format($val->tw_1) . '
											</span>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan II
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											Rp.
											<span class="pull-right">
												' . number_format($val->tw_2) . '
											</span>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan III
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											Rp.
											<span class="pull-right">
												' . number_format($val->tw_3) . '
											</span>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Triwulan IV
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											Rp.
											<span class="pull-right">
												' . number_format($val->tw_4) . '
											</span>
										</div>
									</div>
								</div>
							</div>
						';
					}
				}
				else
				{
					echo '
						<div class="alert alert-warning">
							Belum ada data Rencana Kas yang dapat ditampilkan
						</div>
					';
				}
			?>
		</div>
		<div class="col-md-6">
			<div class="text-center">
				<div id="realisasi" style="height:300px"></div>
			</div>
			<h4 class="text-center">
				REALISASI
			</h4>
			<?php
				$data_realisasi									= array();
				if($realisasi)
				{
					foreach($realisasi as $key => $val)
					{
						$val->Anggaran							= ($val->Anggaran && $val->Anggaran != '.0000' ? $val->Anggaran : 0);
						$val->UP								= ($val->UP && $val->UP != '.0000' ? $val->UP : 0);
						$val->LS								= ($val->LS && $val->LS != '.0000' ? $val->LS : 0);
						$val->Sisa								= ($val->Sisa && $val->Sisa != '.0000' ? $val->Sisa : 0);
						
						$data_realisasi['categories'][]			= $val->Nm_Rek_5;
						$data_realisasi['data']['realisasi'][]	= (float) ($val->UP + $val->LS);
						$data_realisasi['data']['sisa'][]		= (float) $val->Sisa;
						echo '
							<div class="card bordered">
								<div class="card-body text-sm" style="padding:12px">
									<div class="row">
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Rekening
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											<b>
												' . str_replace(' ', '', $val->Kd_Rek_5_Gab) . ' - ' . $val->Nm_Rek_5 . '
											</b>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Realisasi
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											Rp.
											<span class="pull-right">
												' . number_format($val->UP + $val->LS) . '
											</span>
										</div>
										<div class="col-xs-4 col-md-3">
											<b class="text-muted">
												Sisa Anggaran
											</b>
										</div>
										<div class="col-xs-8 col-md-9">
											Rp.
											<span class="pull-right">
												' . number_format($val->Sisa) . '
											</span>
										</div>
									</div>
								</div>
							</div>
						';
					}
				}
				else
				{
					echo '
						<div class="alert alert-warning">
							Belum ada data Realisasi yang dapat ditampilkan
						</div>
					';
				}
			?>
			<h4 class="text-center">
				REALISASI FISIK
			</h4>
			<?php
				if($realisasi_fisik)
				{
					echo '
						<div class="card bordered">
							<div class="card-body text-sm" style="padding:12px">
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Januari
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Jan . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Jan . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Februari
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Feb . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Feb . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Maret
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Mar . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Mar . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											April
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Apr . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Apr . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Mei
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Mei . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Mei . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Juni
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Jun . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Jun . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Juli
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Jul . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Jul . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Agustus
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Agt . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Agt . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											September
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Sep . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Sep . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Oktober
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Okt . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Okt . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Nopember
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Nop . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Nop . '
										</p>
									</div>
								</div>
								<hr />
								<div class="row form-group">
									<div class="col-xs-2">
										<b class="text-muted">
											Desember
										</b>
									</div>
									<div class="col-xs-2">
										<b>
											' . $realisasi_fisik->Des . '%
										</b>
									</div>
									<div class="col-xs-8">
										<b class="text-muted">
											Hambatan
										</b>
										<p>
											' . $realisasi_fisik->Hambatan_Des . '
										</p>
									</div>
								</div>
							</div>
						</div>
					';
				}
				else
				{
					echo '
						<div class="alert alert-warning">
							Belum ada data Realisasi Fisik yang dapat ditampilkan
						</div>
					';
				}
			?>
		</div>
	</div>
			
	<!-- SPD -->
	<h4 class="text-center">
		SPD
	</h4>
	
	<table class="table table-sm table-bordered">
		<thead>
			<tr>
				<th class="text-center text-sm">
					TANGGAL
				</th>
				<th class="text-center text-sm">
					NO. BUKTI
				</th>
				<th class="text-center text-sm">
					SPD
				</th>
				<th class="text-center text-sm">
					PENGESAHAN SPJ
				</th>
				<th class="text-center text-sm">
					SP2D LS
				</th>
				<th class="text-center text-sm">
					SPJ YANG
					<br />
					BELUM DISAHKAN
				</th>
				<th class="text-center text-sm">
					SPP YANG BELUM
					<br />
					DI-SP2D-KAN
				</th>
				<th class="text-center text-sm">
					SISA SPD
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
				if($spd)
				{
					$nm_rek_5				= null;
					foreach($spd as $key => $val)
					{
						if($nm_rek_5 != $val->Nm_Rek_5)
						{
							$sisa					= 0;
							echo '
								<tr>
									<td class="text-sm" colspan="2">
										<b>' . $val->Kd_Rek_5_Gab . ' ' . $val->Nm_Rek_5 . '</b>
									</td>
									<td class="text-right text-sm">
										
									</td>
									<td class="text-right text-sm">
										
									</td>
									<td class="text-right text-sm">
										
									</td>
									<td class="text-right text-sm">
										
									</td>
									<td class="text-right text-sm">
										
									</td>
									<td class="text-right text-sm">
										
									</td>
								</tr>
							';
						}
						$sisa					+= $val->Sisa;
						echo '
							<tr>
								<td class="text-sm">
									' . date_indo($val->Tgl_Bukti) . '
								</td>
								<td class="text-sm">
									' . $val->No_Bukti . '
								</td>
								<td class="text-right text-sm">
									' . number_format($val->SPD) . '
								</td>
								<td class="text-right text-sm">
									' . number_format($val->SPJ_Sah) . '
								</td>
								<td class="text-right text-sm">
									' . number_format($val->LS) . '
								</td>
								<td class="text-right text-sm">
									' . number_format($val->SPJ) . '
								</td>
								<td class="text-right text-sm">
									' . number_format($val->SPP) . '
								</td>
								<td class="text-right text-sm">
									' . number_format($sisa) . '
								</td>
							</tr>
						';
						$nm_rek_5 			= $val->Nm_Rek_5;
					}
				}
				else
				{
					echo '
						<div class="alert alert-warning">
							Belum ada data SPD yang dapat ditampilkan
						</div>
					';
				}
			?>
		</tbody>
	</table>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		Highcharts.setOptions
		({
			lang:
			{
				numericSymbols: ['rb', 'jt', 'M', 'T']
			}
		}),
		Highcharts.chart('rencana',
		{
			chart:
			{
				type: 'areaspline'
			},
			title:
			{
				text: 'Grafik Rencana'
			},
			legend: false,
			xAxis:
			{
				categories:
				[
					'TW 1',
					'TW 2',
					'TW 3',
					'TW 4'
				]
			},
			yAxis:
			{
				title:
				{
					text: 'Rupiah'
				}
			},
			tooltip:
			{
				shared: true,
				valueSuffix: ' rp'
			},
			credits:
			{
				enabled: false
			},
			plotOptions:
			{
				areaspline:
				{
					fillOpacity: 0.2,
					pointPlacement: 'on'
				}
			},
			series: <?php echo json_encode($data_rencana); ?>
		}),
		Highcharts.chart('realisasi',
		{
			chart:
			{
				type: 'column'
			},
			title:
			{
				text: 'Grafik Realisasi'
			},
			legend: false,
			xAxis:
			{
				categories: <?php echo json_encode($data_realisasi['categories']); ?>
			},
			yAxis:
			{
				min: 0,
				title:
				{
					text: 'Rupiah'
				}
			},
			tooltip:
			{
				shared: true,
				valueSuffix: ' rp'
			},
			plotOptions:
			{
				areaspline:
				{
					fillOpacity: 0.2,
					pointPlacement: 'on'
				}
			},
			series:
			[
				{
					name: 'Realisasi',
					data: <?php echo json_encode($data_realisasi['data']['realisasi']); ?>
				},
				{
					name: 'Sisa',
					data: <?php echo json_encode($data_realisasi['data']['sisa']); ?>
				}
			]
		})
	})
</script>