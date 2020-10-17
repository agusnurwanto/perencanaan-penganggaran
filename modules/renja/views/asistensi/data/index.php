<?php
	$controller									=& get_instance();
?>
<style type="text/css">
	@media (min-width: 992px)
	{
		.submit-data
		{
			margin-bottom: 32px
		}
		.btn-float
		{
			position: fixed;
			right: 12px;
			bottom: 12px
		}
	}
	.sticky
	{
		position: fixed;
		top: 80px;
		width: 100%;
		background: #fff;
		z-index: 1080
	}
	.sticky + .content
	{
		padding-top: 90px
	}
</style>
<div class="alert alert-info rounded-0 border-0">
	<i class="fa fa-info-circle"></i>
	Klik pada text yang ditandai untuk memberi atau mengubah komentar
</div>
<form action="<?php echo current_page(); ?>" class="table-responsive submit-data" style="padding:12px;margin-bottom:50px">
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<th class="text-center" rowspan="2" width="100">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
				</th>
				<th class="text-center">
					RENCANA KERJA DAN ANGGARAN
					<br />
					SATUAN KERJA PERANGKAT DAERAH
				</th>
				<th rowspan="2" width="150" class="text-center">
						Formulir
						<br />
						RKA RINCIAN
						<br />
						SUB KEGIATAN
						<br />
						BELANJA SKPD
				</th>
			</tr>
			<tr>
				<th class="text-center">
					<?php echo (isset($results->daerah->nama_pemda) ? strtoupper($results->daerah->nama_pemda) : '-'); ?>
					<br />
					TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
				</th>
			</tr>
		</thead>
	</table>
	<table class="table table-bordered table-sm">
		<tr>
			<td width="220">
				<b>Urusan Pemerintahan</b>
			</td>
			<td width="10">
				:
			</td>
			<td width="220">
				<?php echo $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang); ?>
			</td>
			<td>
				<?php echo $results->header->nm_urusan; ?>
			</td>
		</tr>
		<tr>
			<td>
				<b>Organisasi</b>
			</td>
			<td>
				:
			</td>
			<td>
				<?php echo $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang) . ' . ' . $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang) . '.' . sprintf('%02d', $results->header->kd_unit) ; ?>
			</td>
			<td>
				<?php echo ucwords(strtolower($results->header->nm_unit)); ?>
			</td>
		</tr>
		<tr>
			<td>
				<b>Program</b>
			</td>
			<td>
				:
			</td>
			<td>
				<?php echo $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang) . ' . ' . $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang) . '.' . sprintf('%02d', $results->header->kd_unit) . '.' . sprintf('%02d', $results->header->kd_program) ; ?>
			</td>
			<td>
				<?php echo $results->header->nm_program; ?>
			</td>
		</tr>
		<tr>
			<td>
				<b>Kegiatan</b>
			</td>
			<td>
				:
			</td>
			<td>
				<?php echo $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang) . ' . ' . $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang) . '.' . sprintf('%02d', $results->header->kd_unit) . '.' . sprintf('%02d', $results->header->kd_program) . '.' . sprintf('%02d', $results->header->kd_keg) ; ?>
			</td>
			<td>
				<?php echo $results->header->kegiatan; ?>
			</td>
		</tr>
		<tr id="sticky-top">
			<td width="220">
				<b>Sub Kegiatan</b>
			</td>
			<td width="10">
				:
			</td>
			<td width="220">
				<?php echo $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang) . ' . ' . $results->header->kd_urusan . '.' . sprintf('%02d', $results->header->kd_bidang) . '.' . sprintf('%02d', $results->header->kd_unit) . '.' . sprintf('%02d', $results->header->kd_program) . '.' . sprintf('%02d', $results->header->kd_keg) . '.' . sprintf('%02d', $results->header->kd_keg_sub) ; ?>
			</td>
			<td>
				<?php echo $results->header->kegiatan_sub; ?>
			</td>
		</tr>
		<?php 
			if ($results->header->pilihan == 1)
			{
				echo '
					<tr>
						<td class="text-red">
							<b>Model</b>
						</td>
						<td class="text-red">
							:
						</td>
						<td colspan="2" class="text-red">
							<b>' . $results->header->nm_model . '</b>
						</td>
					</tr>
				';
			}
		?>
	</table>
	<table class="table table-bordered table-sm">
		<tr>
			<td width="220">
				<b>Lokasi Kegiatan</b>
			</td>
			<td width="10">
				:
			</td>
			<td>
				<?php echo $results->header->map_address; ?> 
				<?php 
					if($results->header->alamat_detail != "")
					{
						echo '-' . $results->header->alamat_detail;
					}
				?>
			</td>
		</tr>
		<tr>
			<td>
				<b>Sumber Dana</b>
			</td>
			<td>
				:
			</td>
			<td>
				<?php echo (isset($results->sumber_dana->nama_sumber_dana) ? $results->sumber_dana->nama_sumber_dana : NULL); ?> 
			</td>
		</tr>
	</table>
	<table class="table table-bordered table-sm">
		<!--<tr>
			<td width="220">
				<b>
					Jumlah Tahun n - 1
				</b>
			</td>
			<td width="10">
				:
			</td>
			<td width="10" style="border-right:0">
				Rp
			</td>
			<td class="text-right" width="190" style="border-left:0">
				0.00
			</td>
			<td width="0">
			</td>
		</tr>-->
		<tr>
			<td width="220">
				<b>
					Jumlah Tahun n
				</b>
			</td>
			<td width="10">
				:
			</td>
			<td width="10" style="border-right:0">
				Rp
			</td>
			<td class="text-right" width="190" style="border-left:0">
				<?php echo (isset($results->belanja[0]->subtotal_rek_1) ? number_format($results->belanja[0]->subtotal_rek_1) : 0); ?>
			</td>
			<td>
				<i>(<?php echo (isset($results->belanja[0]->subtotal_rek_1) && $results->belanja[0]->subtotal_rek_1 > 0 ? spell_number($results->belanja[0]->subtotal_rek_1) : 'Nol'); ?> Rupiah)</i>
			</td>
		</tr>
		<!--<tr>
			<td>
				<b>
					Jumlah Tahun n + 1
				</b>
			</td>
			<td>
				:
			</td>
			<td style="border-right:0">
				Rp
			</td>
			<td class="text-right" style="border-left:0">
				0.00
			</td>
			<td>
			</td>
		</tr>-->
	</table>
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<th class="text-center" colspan="3">
					INDIKATOR & TOLOK UKUR KINERJA BELANJA LANGSUNG
				</th>
			</tr>
			<tr>
				<th class="text-center">
					INDIKATOR
				</th>
				<th class="text-center">
					TOLOK UKUR KINERJA
				</th>
				<th class="text-center">
					TARGET KINERJA
				</th>
			</tr>
			<?php
				$cek_capaian				= (isset($results->header->capaian_program) ? $results->header->capaian_program : array());
				$capaian_program			= 0 ;
				$checkbox					= 0;
				foreach($results->capaian_program as $key => $val)
				{
					$checked				= false;
					if($cek_capaian == $val->id)
					{
						$checked			= true;
						$checkbox			+= 1;
					}
			?>
						<tr>
							<td>
							<?php
								if ($capaian_program == 0)
								{
							?>
									<b>CAPAIAN PROGRAM</b>
							<?php
								}
							?>
							</td>
							<td class="relative">
								<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="<?php echo $controller->get_thread(1, $val->id, null, 'tooltip'); ?>" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
									<?php echo ($checked ? '<b>&#8730;</b> ' . $val->tolak_ukur : '<div' . ($checkbox > 0 ? ' style="margin-left: 14px"' : '') . '>' . $val->tolak_ukur . '</div>'); ?>
								</a>
								<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
									<?php echo $controller->get_thread(1, $val->id); ?>
									<li class="input-capaian-program">
										<textarea name="capaian_program[<?php echo $val->id; ?>][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
										<div class="btn-group float-right" style="margin-top:12px">
											<button type="submit" class="btn btn-success btn-sm">
												<i class="mdi mdi-check"></i>
												Simpan
											</button>
											<button type="button" class="btn btn-danger btn-sm">
												<i class="mdi mdi-window-close"></i>
												Batal
											</button>
										</div>
									</li>
								</ul>
							</td>
							<td class="text-right">
								<?php echo (fmod($val->target, 1) !== 0.00 ? number_format($val->target, 2) : (number_format($val->target) == 0 ? '' : number_format($val->target)) ); ?>
								<?php //echo (fmod($val->target, 1) !== 0.00 ? number_format($val->target, 2) : number_format($val->target, 0)); ?> 
								<?php echo $val->satuan; ?>
							</td>
						</tr>
			<?php
					$capaian_program += 1;
				}
			?>
			<?php
				$masukan			= '
					<td class="relative">
						<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(2, 0, 1, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
							Jumlah Dana
						</a>
						<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
							' . $controller->get_thread(2, 0, 1) . '
							<li class="input-indikator-masukan">
								<textarea name="indikator[masukan][0][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
								<div class="btn-group float-right" style="margin-top:12px">
									<button type="submit" class="btn btn-success btn-sm">
										<i class="mdi mdi-check"></i>
										Simpan
									</button>
									<button type="button" class="btn btn-danger btn-sm">
										<i class="mdi mdi-window-close"></i>
										Batal
									</button>
								</div>
							</li>
						</ul>
					</td>
					<td class="text-right">
						Rp ' . number_format($results->header->pagu) . '
					</td>
				';
				$keluaran			= null;
				$hasil				= null;
				foreach($results->indikator as $key => $val)
				{
					if($val->jns_indikator == 1)
					{
						$masukan	.= ($masukan ? '<td></td>' : '') . '
						<td class="relative">
							<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(2, $val->id, 1, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
								' . $val->tolak_ukur . '
							</a>
							<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
								' . $controller->get_thread(2, $val->id, 1) . '
								<li class="input-indikator-masukan">
									<textarea name="indikator[masukan][' . $val->id . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
									<div class="btn-group float-right" style="margin-top:12px">
										<button type="submit" class="btn btn-success btn-sm">
											<i class="mdi mdi-check"></i>
											Simpan
										</button>
										<button type="button" class="btn btn-danger btn-sm">
											<i class="mdi mdi-window-close"></i>
											Batal
										</button>
									</div>
								</li>
							</ul>
						</td>
						<td class="text-right">
							' . (fmod($val->target, 1) !== 0.00 ? number_format($val->target, 2) : number_format($val->target, 0)) . ' ' . $val->satuan . '
						</td></tr><tr>';
					}									
					elseif($val->jns_indikator == 2)
					{
						if($results->header->pilihan == 0 or $results->header->pilihan == 1)
						{
							$keluaran	.= ($keluaran ? '<td></td>' : '') . '
							<td class="relative">
								<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(2, $val->id, 2, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
									' . $val->tolak_ukur . '
								</a>
								<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
									' . $controller->get_thread(2, $val->id, 2) . '
									<li class="input-indikator-keluaran">
										<textarea name="indikator[keluaran][' . $val->id . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
										<div class="btn-group float-right" style="margin-top:12px">
											<button type="submit" class="btn btn-success btn-sm">
												<i class="mdi mdi-check"></i>
												Simpan
											</button>
											<button type="button" class="btn btn-danger btn-sm">
												<i class="mdi mdi-window-close"></i>
												Batal
											</button>
										</div>
									</li>
								</ul>
							</td>
							<td class="text-right">
								' . (fmod($val->target, 1) !== 0.00 ? number_format($val->target, 2) : number_format($val->target, 0)) . ' ' . $val->satuan . '
							</td></tr><tr>';
						}
					}
					elseif($val->jns_indikator == 3)
					{
						if($results->header->pilihan == 0 or $results->header->pilihan == 1)
						{
							$hasil		.= ($hasil ? '<td></td>' : '') . '
							<td class="relative">
								<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(2, $val->id, 3, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
									' . $val->tolak_ukur . '
								</a>
								<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
									' . $controller->get_thread(2, $val->id, 3) . '
									<li class="input-indikator-hasil">
										<textarea name="indikator[hasil][' . $val->id . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
										<div class="btn-group float-right" style="margin-top:12px">
											<button type="submit" class="btn btn-success btn-sm">
												<i class="mdi mdi-check"></i>
												Simpan
											</button>
											<button type="button" class="btn btn-danger btn-sm">
												<i class="mdi mdi-window-close"></i>
												Batal
											</button>
										</div>
									</li>
								</ul>
							</td>
							<td class="text-right">
								' . (fmod($val->target, 1) !== 0.00 ? number_format($val->target, 2) : number_format($val->target, 0)) . ' ' . $val->satuan . '
							</td></tr><tr>';
						}
					}
				}
				//print_r($keluaran);exit;
			?>
			<tr>
				<td>
					<b>MASUKAN</b>
				</td>
				<?php echo ($masukan ? $masukan : '<td colspan="2"></td>'); ?>
				<!--<td>
					Jumlah Dana
				</td>								
				<td>
					Rp. <?php //echo (isset($results->belanja->subtotal_rek_1']) ? number_format($results->belanja->subtotal_rek_1']) : 0); ?>									
				</td>-->
			</tr>
			<tr>
				<td>
					<b>KELUARAN</b>
				</td>
				<?php echo ($keluaran ? $keluaran : '<td colspan="2"></td>'); ?>
			</tr>
			<tr>
				<td>
					<b>HASIL</b>
				</td>
				<?php echo ($hasil ? $hasil : '<td colspan="2"></td>'); ?>
			</tr>
			<tr>
				<td colspan="3" class="relative">
					<b>
						Kelompok Sasaran Kegiatan :
					</b>
					<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="<?php echo $controller->get_thread(7, 0, 0, 'tooltip'); ?>" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
						<b>
							<?php echo $results->header->kelompok_sasaran; ?>
						</b>
					</a>
					<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
						<?php echo $controller->get_thread(7, 0); ?>
						<li class="input-indikator-masukan">
							<textarea name="kelompok_sasaran[0][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
							<div class="btn-group float-right" style="margin-top:12px">
								<button type="submit" class="btn btn-success btn-sm">
									<i class="mdi mdi-check"></i>
									Simpan
								</button>
								<button type="button" class="btn btn-danger btn-sm">
									<i class="mdi mdi-window-close"></i>
									Batal
								</button>
							</div>
						</li>
					</ul>
				</td>
			</tr>
		</thead>
	</table>
	<div class="form-group relative">
		<a href="#" class="btn btn-info dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="<?php echo $controller->get_thread(6, 0, null, 'tooltip'); ?>" data-html="true">
			<i class="mdi mdi-square-edit-outline"></i>
			Kesesuaian
		</a>
		<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
			<?php echo $controller->get_thread(6); ?>
			<li class="input-kesesuaian">
				<textarea name="kesesuaian[0][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
				<div class="btn-group float-right" style="margin-top:12px">
					<button type="submit" class="btn btn-success btn-sm">
						<i class="mdi mdi-check"></i>
						Simpan
					</button>
					<button type="button" class="btn btn-danger btn-sm">
						<i class="mdi mdi-window-close"></i>
						Batal
					</button>
				</div>
			</li>
		</ul>
	</div>
	<table class="table table-bordered table-sm">
		<thead>
			<tr>
				<th class="text-center" colspan="6">
					RINCIAN ANGGARAN BELANJA LANGSUNG MENURUT PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH
				</th>
			</tr>
			<tr>
				<th class="text-center" rowspan="2" width="13%">
					KODE REKENING
				</th>
				<th  class="text-center"rowspan="2" width="44%">
					URAIAN
				</th>
				<th class="text-center" colspan="3" width="30%">
					RINCIAN PERHITUNGAN
				</th>
				<th class="text-center" rowspan="2" width="13%">
					JUMLAH
					<br />
					(Rp)
				</th>
			</tr>
			<tr>
				<th class="text-center">
					Volume
				</th>
				<th class="text-center">
					Satuan
				</th>
				<th class="text-center">
					Harga Satuan
				</th>
			</tr>
			<tr bgcolor="gray">
				<th class="text-center">
					1
				</th>
				<th class="text-center">
					2
				</th>
				<th class="text-center">
					3
				</th>
				<th class="text-center">
					4
				</th>
				<th class="text-center">
					5
				</th>
				<th class="text-center">
					6
				</th>
			</tr>
		</thead>
		<tbody>
			<?php
				$id_rek_1						= 0;
				$id_rek_2						= 0;
				$id_rek_3						= 0;
				$id_rek_4						= 0;
				$id_rek_5						= 0;
				$id_belanja_sub					= 0;
				$id_belanja_rinci				= 0;
				foreach($results->belanja as $key => $val)
				{
					if($val->id_rek_1 != $id_rek_1)
					{
						echo '
							<tr>
								<td>
									<b>' . $val->kd_rek_1 . '</b>
								</td>
								<td>
									<b>' . $val->nm_rek_1 . '</b>
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-center">
									
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-right">
									<b>' . number_format($val->subtotal_rek_1) . '</b>
								</td>
							</tr>
						';
					}
					if($val->id_rek_2 != $id_rek_2)
					{
						echo '
							<tr>
								<td>
									<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '</b>
								</td>
								<td style="padding-left:5px">
									<b>' . $val->nm_rek_2 . '</b>
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-center">
									
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-right">
									<b>' . number_format($val->subtotal_rek_2) . '</b>
								</td>
							</tr>
						';
					}
					if($val->id_rek_3 != $id_rek_3)
					{
						echo '
							<tr>
								<td>
									<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '</b>
								</td>
								<td style="padding-left:10px">
									<b>' . $val->nm_rek_3 . '</b>
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-center">
									
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-right">
									<b>' . number_format($val->subtotal_rek_3) . '</b>
								</td>
							</tr>
						';
					}
					if($val->id_rek_4 != $id_rek_4)
					{
						echo '
							<tr>
								<td>
									<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . sprintf('%02d', $val->kd_rek_4) . '</b>
								</td>
								<td style="padding-left:15px">
									<b>' . $val->nm_rek_4 . '</b>
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-center">
									
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-right">
									' . number_format($val->subtotal_rek_4) . '
								</td>
							</tr>
						';
					}
					if($val->id_rek_5 != $id_rek_5)
					{
						echo '
							<tr>
								<td>
									<b data-toggle="tooltip" title="' . htmlspecialchars($val->keterangan) . '">' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . sprintf('%02d', $val->kd_rek_4) . '.' . sprintf('%02d', $val->kd_rek_5) . ' <i class="fa fa-question-circle text-success"></i></b>
								</td>
								<td class="relative" style="padding-left:20px">
									<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(3, $val->id_rek_5, null, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
										<b>' . $val->nm_rek_5 . '</b>
									</a>
									<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
										' . $controller->get_thread(3, $val->id_rek_5) . '
										<li class="input-belanja">
											<textarea name="belanja[' . $val->id_rek_5 . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
											<div class="btn-group float-right" style="margin-top:12px">
												<button type="submit" class="btn btn-success btn-sm">
													<i class="mdi mdi-check"></i>
													Simpan
												</button>
												<button type="button" class="btn btn-danger btn-sm">
													<i class="mdi mdi-window-close"></i>
													Batal
												</button>
											</div>
										</li>
									</ul>
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-center">
									
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-right">
									' . number_format($val->subtotal_rek_5) . '
								</td>
							</tr>
						';
					}
					if($val->id_belanja_sub != $id_belanja_sub)
					{
						echo '
							<tr>
								<td>
									
								</td>
								<td class="relative" style="padding-left:25px">
									<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(4, $val->id_belanja_sub, null, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
										' . $val->nama_sub_rincian . '
									</a>
									<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
										' . $controller->get_thread(4, $val->id_belanja_sub) . '
										<li class="input-belanja-sub">
											<textarea name="belanja_sub[' . $val->id_belanja_sub . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
											<div class="btn-group float-right" style="margin-top:12px">
												<button type="submit" class="btn btn-success btn-sm">
													<i class="mdi mdi-check"></i>
													Simpan
												</button>
												<button type="button" class="btn btn-danger btn-sm">
													<i class="mdi mdi-window-close"></i>
													Batal
												</button>
											</div>
										</li>
									</ul>
								</td>
								<td class="text-right">
									
								</td>
								<td>
									
								</td>
								<td class="text-right">
									
								</td>
								<td class="text-right">
									' . number_format($val->subtotal_rinci) . '
								</td>
							</tr>
						';
					}
					if($val->id_belanja_rinci != $id_belanja_rinci)
					{
						echo '
							<tr>
								<td>
									
								</td>
								<td class="relative" style="padding-left:30px">
									<a href="#" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(5, $val->id_belanja_rinci, null, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
										- ' . $val->nama_rincian . ' (' . (0 != $val->vol_1 ? (0 != $val->vol_2 || 0 != $val->vol_3 ? number_format($val->vol_1) . ' ' . $val->satuan_1 . ' x ' : number_format($val->vol_1) . ' ' . $val->satuan_1) : null) . (0 != $val->vol_2 ? (0 != $val->vol_3 ? number_format($val->vol_2) . ' ' . $val->satuan_2 . ' x ' : number_format($val->vol_2) . ' ' . $val->satuan_2) : null) . (0 != $val->vol_3 ? number_format($val->vol_3) . ' ' . $val->satuan_3 : null) . ')
									</a>
									<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
										' . $controller->get_thread(5, $val->id_belanja_rinci) . '
										<li class="input-belanja-rinc">
											<textarea name="belanja_rinc[' . $val->id_belanja_rinci . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
											<div class="btn-group float-right" style="margin-top:12px">
												<button type="submit" class="btn btn-success btn-sm">
													<i class="mdi mdi-check"></i>
													Simpan
												</button>
												<button type="button" class="btn btn-danger btn-sm">
													<i class="mdi mdi-window-close"></i>
													Batal
												</button>
											</div>
										</li>
									</ul>
								</td>
								<td class="text-center">
									' . number_format($val->vol_123)  . '
								</td>
								<td class="text-center">
									' . $val->satuan_123 . '
								</td>
								<td class="text-right">
									' . number_format($val->nilai) . '
								</td>
								<td class="text-right">
									' . number_format($val->total) . '
								</td>
							</tr>
						';
					}
					$id_rek_1					= $val->id_rek_1;
					$id_rek_2					= $val->id_rek_2;
					$id_rek_3					= $val->id_rek_3;
					$id_rek_4					= $val->id_rek_4;
					$id_rek_5					= $val->id_rek_5;
					$id_belanja_sub				= $val->id_belanja_sub;
					$id_belanja_rinci			= $val->id_belanja_rinci;
				}
			?>
		</tbody>
	</table>
	<table class="table table-bordered table-sm" style="page-break-inside:avoid">
		<tr>
			<td colspan="3">
				Keterangan:
				<br />
			</td>
			<td colspan="3" class="text-center">
				<?php echo $results->daerah->nama_daerah; ?>, 
				<?php echo ($results->tanggal->tanggal_rka ? date_indo($results->tanggal->tanggal_rka) : date('d') . '' . phrase(date('F')) . '' . date('Y') ); ?>
				<br />
				<b><?php
						echo strtoupper($results->header->nama_jabatan);
					?></b>
				<br />
				<br />
				<br />
				<br />
				<br />
				<u><b>
						<?php
							echo $results->header->nama_pejabat;
						?>
					</b></u>
				<br />
				<?php
					echo 'NIP. '. $results->header->nip_pejabat;
				?>
			</td>
		</tr>
		<tr>
			<th class="text-center" colspan="6">
				<b>TIM ANGGARAN PEMERINTAH DAERAH</b>
			</th>
		</tr>
		<tr>
			<th class="text-center">
				NO.
			</th>
			<th class="text-center">
				NAMA
			</th>
			<th class="text-center">
				NIP
			</th>
			<th class="text-center">
				JABATAN
			</th>
			<th class="text-center">
				ACTION
			</th>
			<th class="text-center">
				TANDA TANGAN
			</th>
		</tr>
		<?php
			$CI							=& get_instance();
			foreach($results->tim_anggaran as $key => $val)
			{
				$id						= 'ttd_' . $val->id;
				$ttd					= $CI->get_ttd($val->id);
				echo '
					<tr>
						<td class="text-center">
							' . $val->kode . '
						</td>
						<td>
							' . $val->nama_tim . '
						</td>
						<td>
							' . $val->nip_tim . '
						</td>
						<td>
							' . $val->jabatan_tim . '
						</td>
						<td>
							' . (1 == get_userdata('group_id') || (15 == get_userdata('group_id') && get_userdata('sub_unit') == $val->id) ? '<a href="' . current_page('../verifikasi', array('req' => 'ttd', 'target' => 'ttd_' . $val->id)) . '" class="btn btn-toggle btn-sm ' . (isset($results->verified->$id) && 1 == $results->verified->$id ? 'active' : 'inactive') . ' --modal">
								<span class="handle"></span>
							</a>' : null) . '
						</td>
						<td>
							<p class="verifikatur-ttd text-center">
								' . (isset($results->verified->$id) && 1 == $results->verified->$id ? $ttd : null) . '
							</p>
						</td>
					</tr>
				';
			}
		?>
	</table>
	<table class="table table-bordered table-sm">
		<tfoot>
			<tr>
				<td class="text-center text-sm" colspan="3">
					<b>PARAF TIM ASISTENSI</b>
				</td>
			</tr>
			<tr>
				<td class="text-sm text-center">
					<b>1. BAPPEDA</b>
					<?php if(in_array(get_userdata('group_id'), array(1, 12))) { ?> <a href="<?php echo current_page('../verifikasi', array('target' => 'perencanaan')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($results->verified->perencanaan) && 1 == $results->verified->perencanaan ? 'active' : 'inactive'); ?> --modal">
						<span class="handle"></span>
					</a> <?php } ?>
					<p class="verifikatur text-center">
						<?php echo (isset($results->verified->perencanaan) && 1 == $results->verified->perencanaan ? 'Diverifikasi oleh <b>' . $results->verified->nama_operator_perencanaan . '</b> pada ' . date_indo($results->verified->waktu_verifikasi_perencanaan, 3, '-') : null); ?>
					</p>
				</td>
				<td class="text-sm text-center">
					<b>2. BPKAD</b>
					<?php if(in_array(get_userdata('group_id'), array(1, 12))) { ?> <a href="<?php echo current_page('../verifikasi', array('target' => 'keuangan')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($results->verified->keuangan) && 1 == $results->verified->keuangan ? 'active' : 'inactive'); ?> --modal">
						<span class="handle"></span>
					</a> <?php } ?>
					<p class="verifikatur text-center">
						<?php echo (isset($results->verified->keuangan) && 1 == $results->verified->keuangan ? 'Diverifikasi oleh <b>' . $results->verified->nama_operator_keuangan . '</b> pada ' . date_indo($results->verified->waktu_verifikasi_keuangan, 3, '-') : null); ?>
					</p>
				</td>
				<td class="text-sm text-center">
					<b>3. Bagian Pembangunan Setda</b>
					<?php if(in_array(get_userdata('group_id'), array(1, 12))) { ?> <a href="<?php echo current_page('../verifikasi', array('target' => 'setda')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($results->verified->setda) && 1 == $results->verified->setda ? 'active' : 'inactive'); ?> --modal">
						<span class="handle"></span>
					</a> <?php } ?>
					<p class="verifikatur text-center">
						<?php echo (isset($results->verified->setda) && 1 == $results->verified->setda ? 'Diverifikasi oleh <b>' . $results->verified->nama_operator_setda . '</b> pada ' . date_indo($results->verified->waktu_verifikasi_setda, 3, '-'): null); ?>
					</p>
				</td>
			</tr>
		</tfoot>
	</table>
	<input type="hidden" name="token" value="<?php echo $token; ?>" />
</form>
<div class="btn-group btn-float" style="margin-bottom:24px">
	<a href="<?php echo current_page('../../kegiatan/kak', array('id_keg' => $this->input->get('id_keg'))); ?>" class="btn btn-warning --xhr">
		<i class="mdi mdi-car"></i>
		KAK
	</a>
	<a href="<?php echo current_page('../../kegiatan/pendukung', array('id_sub' => null, 'id_keg' => $this->input->get('id_keg'))); ?>" class="btn btn-danger --xhr">
		<i class="mdi mdi-book"></i>
		Pendukung
	</a>
	<a href="<?php echo current_page('../../../laporan/anggaran/lembar_asistensi', array('id_sub' => null, 'id_keg' => null, 'sub_unit' => $this->input->get('id_sub'), 'kegiatan' => $this->input->get('id_keg'), 'method' => 'embed')); ?>" class="btn btn-info" target="_blank">
		<i class="mdi mdi-printer"></i>
		Asistensi
	</a>
	<a href="<?php echo current_page('../../../laporan/anggaran/rka/rka_sub_kegiatan', array('sub_kegiatan' => $this->input->get('sub_kegiatan'), 'method' => 'embed')); ?>" class="btn btn-primary" target="_blank">
		<i class="mdi mdi-printer"></i>
		RKA
	</a>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		var navbar = document.getElementById("sticky-top"),
			sticky = navbar.offsetTop + 190;
			
		$(window).on('scroll', function(e)
		{
			if (window.pageYOffset >= sticky)
			{
				navbar.classList.add("sticky")
			}
			else
			{
				navbar.classList.remove("sticky")
			}
		})
	})
</script>