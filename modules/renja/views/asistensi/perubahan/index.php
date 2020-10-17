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
</style>
<div class="alert alert-info no-border">
	<i class="fa fa-info-circle"></i>
	Klik pada text yang ditandai untuk memberi atau mengubah komentar
</div>
<form action="<?php echo current_page(); ?>" class="table-responsive submit-data" style="padding:12px;margin-bottom:50px">
	<table class="table table-bordered">
		<thead>
			<tr>
				<th rowspan="2" width="100" class="bordered">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
				</th>
				<th class="bordered text-center">
					RENCANA KERJA DAN ANGGARAN PERUBAHAN
					<br />
					SATUAN KERJA PERANGKAT DAERAH
				</th>
				<th colspan="7" width="140" class="bordered text-center text-sm">
					NOMOR RKAP SKPD
				</th>
				<th rowspan="2" width="100" class="bordered text-center">
					Formulir
					<br />
					RKAP SKPD
					<br />
					2.2.1
				</th>
			</tr>
			<tr>
				<th class="bordered text-center no-margin-top no-margin-bottom">
					<h5>
						<?php echo (isset($results['daerah']->nama_pemda) ? strtoupper($results['daerah']->nama_pemda) : '-'); ?>
					</h5>
					<h5>
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
					</h5>
				</th>
				<th class="bordered" align="center" width="40">
					<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?>
				</th>
				<th class="bordered" align="center" width="40">
					<?php echo sprintf('%02d', $results['header'][0]['kode_unit']); ?>
				</th>
				<th class="bordered" align="center" width="40">
					<?php echo sprintf('%02d', $results['header'][0]['kode_sub']); ?>
				</th>
				<th class="bordered" align="center" width="40">
					<?php echo sprintf('%02d', $results['header'][0]['kode_program']); ?>
				</th>
				<th class="bordered" align="center" width="40">
					<?php echo sprintf('%02d', $results['header'][0]['kode_kegiatan']); ?>
				</th>
				<th class="bordered" align="center" width="40">
					5
				</th>
				<th class="bordered" align="center" width="40">
					2
				</th>
			</tr>
		</thead>
	<table>
	<table class="table table-bordered">
		<tr>
			<td width="220">
				<b>Urusan Pemerintahan</b>
			</td>
			<td width="10">
				:
			</td>
			<td width="220">
				<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?>
			</td>
			<td>
				<?php echo $results['header'][0]['nama_urusan']; ?> <?php //echo $results['header'][0]['nama_bidang']; ?>
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
				<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?> . <?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']) . '.' . sprintf('%02d', $results['header'][0]['kode_unit']); ?>
			</td>
			<td>
				<?php echo ucwords(strtolower($results['header'][0]['nama_unit'])); ?>
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
				<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?> . <?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']) . '.' . sprintf('%02d', $results['header'][0]['kode_unit']) . ' . ' . sprintf('%02d', $results['header'][0]['kode_program']); ?>
			</td>
			<td>
				<?php echo $results['header'][0]['nama_program']; ?>
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
				<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?> . <?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']) . '.' . sprintf('%02d', $results['header'][0]['kode_unit']) . ' . ' . sprintf('%02d', $results['header'][0]['kode_program']) . ' . ' . sprintf('%02d', $results['header'][0]['kode_kegiatan']); ?>
			</td>
			<td>
				<?php echo $results['header'][0]['nama_kegiatan']; ?>
			</td>
		</tr>
		<?php 
			if ($results['header'][0]['pilihan'] == 1)
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
							<b>' . $results['header'][0]['nm_model'] . '</b>
						</td>
					</tr>
				';
			}
		?>
	</table>
	<table class="table table-bordered">
		<tr>
			<td width="220">
				<b>Lokasi Kegiatan</b>
			</td>
			<td width="10">
				:
			</td>
			<td>
				<?php echo $results['header'][0]['map_address']; ?> 
				<?php 
					if($results['header'][0]['alamat_detail'] != "")
					{
						echo '-' . $results['header'][0]['alamat_detail'];
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
				<?php echo (isset($results['sumber_dana']->nama_sumber_dana) ? $results['sumber_dana']->nama_sumber_dana : NULL); ?> 
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<tr>
			<td>
				<b>
					Jumlah Tahun n
				</b>
			</td>
			<td>
				:
			</td>
			<td style="border-right:0">
				Rp
			</td>
			<td class="text-right" style="border-left:0">
				<?php echo (isset($results['belanja'][0]['subtotal_rek_1_setelah']) ? number_format($results['belanja'][0]['subtotal_rek_1_setelah']) : 0); ?>
			</td>
			<td>
				<i>(<?php echo (isset($results['belanja'][0]['subtotal_rek_1_setelah']) && $results['belanja'][0]['subtotal_rek_1_setelah'] > 0 ? spell_number($results['belanja'][0]['subtotal_rek_1_setelah']) : 'nol rupiah'); ?>)</i>
			</td>
		</tr>
		<tr>
			<td colspan="5">
				<table>
					<tr>
						<td class="text-sm">
							Latar Belakang Perubahan / dianggarkan dalam Perubahan APBD 
						</td>
						<td class="text-sm">
							:
						</td>
						<td class="text-sm">
							<?php echo ($results['header'][0]['latar_belakang_perubahan'] ? $results['header'][0]['latar_belakang_perubahan'] : ''); ?>
						</td>
					</tr>
				</table>
			</td>
		</tr>
	</table>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="text-center" colspan="5">
					INDIKATOR & TOLOK UKUR KINERJA BELANJA LANGSUNG
				</th>
			</tr>
			<tr>
				<th class="text-center" rowspan="2">
					INDIKATOR
				</th>
				<th class="text-center" colspan="2">
					TOLOK UKUR KINERJA
				</th>
				<th class="text-center" colspan="2">
					TARGET KINERJA
				</th>
			</tr>
			<tr>
				<th class="bordered text-center text-sm" width="32%">
					SEBELUM
				</th>
				<th class="bordered text-center text-sm" width="31%">
					SETELAH
				</th>
				<th class="bordered text-center text-sm" width="15%">
					SEBELUM
				</th>
				<th class="bordered text-center text-sm" width="15%">
					SETELAH
				</th>
			</tr>
			<?php
				$cek_capaian				= (isset($results['header'][0]['capaian_program']) ? $results['header'][0]['capaian_program'] : array());
				$capaian_program			= 0 ;
				$checkbox					= 0;
				foreach($results['capaian_program'] as $key => $val)
				{
					$checked				= false;
					if($cek_capaian == $val['id'])
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
								<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="<?php echo $controller->get_thread(1, $val['id'], null, 'tooltip'); ?>" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
									<?php echo ($checked ? '<b>&#8730;</b> ' . $val['tolak_ukur'] : '<div' . ($checkbox > 0 ? ' style="margin-left: 14px"' : '') . '>' . $val['tolak_ukur'] . '</div>'); ?>
								</a>
								<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
									<?php echo $controller->get_thread(1, $val['id']); ?>
									<li class="input-capaian-program">
										<textarea name="capaian_program[<?php echo $val['id']; ?>][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
										<div class="btn-group pull-right" style="margin-top:12px">
											<button type="submit" class="btn btn-success btn-sm">
												<i class="fa fa-check"></i>
												Simpan
											</button>
											<button type="button" class="btn btn-danger btn-sm">
												<i class="fa fa-times"></i>
												Batal
											</button>
										</div>
									</li>
								</ul>
							</td>
							<td class="text-right">
								<?php echo (fmod($val['target'], 1) !== 0.00 ? number_format($val['target'], 2) : (number_format($val['target']) == 0 ? '' : number_format($val['target'])) ); ?>
								<?php //echo (fmod($val['target'], 1) !== 0.00 ? number_format($val['target'], 2) : number_format($val['target'], 0)); ?> 
								<?php echo $val['satuan']; ?>
							</td>
						</tr>
			<?php
					$capaian_program += 1;
				}
			?>
			<?php
				$masukan			= '
					<td class="relative text-sm">
						Jumlah Dana
					</td>
					<td class="relative text-sm">
						<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(2, 0, 1, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
							Jumlah Dana
						</a>
						<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
							' . $controller->get_thread(2, 0, 1) . '
							<li class="input-indikator-masukan">
								<textarea name="indikator[masukan][0][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
								<div class="btn-group pull-right" style="margin-top:12px">
									<button type="submit" class="btn btn-success btn-sm">
										<i class="fa fa-check"></i>
										Simpan
									</button>
									<button type="button" class="btn btn-danger btn-sm">
										<i class="fa fa-times"></i>
										Batal
									</button>
								</div>
							</li>
						</ul>
					</td>
					<td class="text-right text-sm">
						Rp ' . number_format(isset($results['belanja'][0]['subtotal_rek_1_sebelum']) ? $results['belanja'][0]['subtotal_rek_1_sebelum'] : 0) . '
					</td>
					<td class="text-right text-sm">
						Rp ' . number_format(isset($results['belanja'][0]['subtotal_rek_1_setelah']) ? $results['belanja'][0]['subtotal_rek_1_setelah'] : 0) . '
					</td>
				';
				$keluaran			= null;
				$hasil				= null;
				foreach($results['indikator'] as $key => $val)
				{
					if($val['jns_indikator'] == 1)
					{
						$masukan	.= ($masukan ? '
							<td></td>' : '') . '
							<td class="relative text-sm">
								<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(2, $val['id'], 1, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
									' . $val['tolak_ukur'] . '
								</a>
								<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
									' . $controller->get_thread(2, $val['id'], 1) . '
									<li class="input-indikator-masukan">
										<textarea name="indikator[masukan][' . $val['id'] . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
										<div class="btn-group pull-right" style="margin-top:12px">
											<button type="submit" class="btn btn-success btn-sm">
												<i class="fa fa-check"></i>
												Simpan
											</button>
											<button type="button" class="btn btn-danger btn-sm">
												<i class="fa fa-times"></i>
												Batal
											</button>
										</div>
									</li>
								</ul>
							</td>
							<td class="text-right text-sm">
								' . (fmod($val['target'], 1) !== 0.00 ? number_format($val['target'], 2) : number_format($val['target'], 0)) . ' ' . $val['satuan'] . '
							</td>
							</tr><tr>';
					}									
					elseif($val['jns_indikator'] == 2)
					{
						if($results['header'][0]['pilihan'] == 0 or $results['header'][0]['pilihan'] == 1)
						{
							$keluaran	.= ($keluaran ? '<td></td>' : '') . '
							<td class="relative text-sm">
								' . $val['tolak_ukur_sebelum'] . '
							</td>
							<td class="relative text-sm">
								<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(2, $val['id'], 2, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
									' . $val['tolak_ukur_setelah'] . '
								</a>
								<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
									' . $controller->get_thread(2, $val['id'], 2) . '
									<li class="input-indikator-keluaran">
										<textarea name="indikator[keluaran][' . $val['id'] . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
										<div class="btn-group pull-right" style="margin-top:12px">
											<button type="submit" class="btn btn-success btn-sm">
												<i class="fa fa-check"></i>
												Simpan
											</button>
											<button type="button" class="btn btn-danger btn-sm">
												<i class="fa fa-times"></i>
												Batal
											</button>
										</div>
									</li>
								</ul>
							</td>
							<td class="text-right text-sm">
								' . (fmod($val['target_sebelum'], 1) !== 0.00 ? number_format($val['target_sebelum'], 2) : number_format($val['target_sebelum'], 0)) . ' ' . $val['satuan_sebelum'] . '
							</td>
							<td class="text-right text-sm">
								' . (fmod($val['target_setelah'], 1) !== 0.00 ? number_format($val['target_setelah'], 2) : number_format($val['target_setelah'], 0)) . ' ' . $val['satuan_setelah'] . '
							</td>
							</tr><tr>';
						}
					}
					elseif($val['jns_indikator'] == 3)
					{
						if($results['header'][0]['pilihan'] == 0 or $results['header'][0]['pilihan'] == 1)
						{
							$hasil		.= ($hasil ? '
								<td></td>' : '') . '
								<td class="relative text-sm">
									' . $val['tolak_ukur_sebelum'] . '
								</td>
								<td class="relative text-sm">
									<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(2, $val['id'], 3, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
										' . $val['tolak_ukur_setelah'] . '
									</a>
									<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
										' . $controller->get_thread(2, $val['id'], 3) . '
										<li class="input-indikator-hasil">
											<textarea name="indikator[hasil][' . $val['id'] . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
											<div class="btn-group pull-right" style="margin-top:12px">
												<button type="submit" class="btn btn-success btn-sm">
													<i class="fa fa-check"></i>
													Simpan
												</button>
												<button type="button" class="btn btn-danger btn-sm">
													<i class="fa fa-times"></i>
													Batal
												</button>
											</div>
										</li>
									</ul>
								</td>
								<td class="text-right text-sm">
									' . (fmod($val['target_sebelum'], 1) !== 0.00 ? number_format($val['target_sebelum'], 2) : number_format($val['target_sebelum'], 0)) . ' ' . $val['satuan_sebelum'] . '
								</td>
								<td class="text-right text-sm">
									' . (fmod($val['target_setelah'], 1) !== 0.00 ? number_format($val['target_setelah'], 2) : number_format($val['target_setelah'], 0)) . ' ' . $val['satuan_setelah'] . '
								</td>
								</tr><tr>';
						}
					}
				}
				//print_r($keluaran);exit;
			?>
			<tr>
				<td class="text-sm">
					MASUKAN
				</td>
				<?php echo ($masukan ? $masukan : '<td colspan="2"></td>'); ?>
				<!--<td>
					Jumlah Dana
				</td>								
				<td>
					Rp. <?php //echo (isset($results['belanja'][0]['subtotal_rek_1']) ? number_format($results['belanja'][0]['subtotal_rek_1']) : 0); ?>									
				</td>-->
			</tr>
			<tr>
				<td class="text-sm">
					KELUARAN
				</td>
				<?php echo ($keluaran ? $keluaran : '<td colspan="2"></td>'); ?>
			</tr>
			<tr>
				<td class="text-sm">
					HASIL
				</td>
				<?php echo ($hasil ? $hasil : '<td colspan="2"></td>'); ?>
			</tr>
			<tr>
				<td colspan="5" class="relative text-sm">
					<b>Kelompok Sasaran Kegiatan :</b>
					<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="<?php echo $controller->get_thread(7, 0, 0, 'tooltip'); ?>" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
						<b>
							<?php echo $results['header'][0]['kelompok_sasaran']; ?>
						</b>
					</a>
					<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
						<?php echo $controller->get_thread(7, 0); ?>
						<li class="input-indikator-masukan">
							<textarea name="kelompok_sasaran[0][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
							<div class="btn-group pull-right" style="margin-top:12px">
								<button type="submit" class="btn btn-success btn-sm">
									<i class="fa fa-check"></i>
									Simpan
								</button>
								<button type="button" class="btn btn-danger btn-sm">
									<i class="fa fa-times"></i>
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
		<a href="javascript:void(0)" class="btn btn-info dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="<?php echo $controller->get_thread(6, 0, null, 'tooltip'); ?>" data-html="true">
			<i class="fa fa-edit"></i>
			Kesesuaian
		</a>
		<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
			<?php echo $controller->get_thread(6); ?>
			<li class="input-kesesuaian">
				<textarea name="kesesuaian[0][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
				<div class="btn-group pull-right" style="margin-top:12px">
					<button type="submit" class="btn btn-success btn-sm">
						<i class="fa fa-check"></i>
						Simpan
					</button>
					<button type="button" class="btn btn-danger btn-sm">
						<i class="fa fa-times"></i>
						Batal
					</button>
				</div>
			</li>
		</ul>
	</div>
	<table class="table table-bordered">
		<thead>
			<tr>
				<th class="text-center text-sm" colspan="12">
					RINCIAN ANGGARAN BELANJA LANGSUNG MENURUT PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH
				</th>
			</tr>
			<tr>
				<th class="text-center text-sm" rowspan="3" width="10%">
					KODE
					<br />
					REKENING
				</th>
				<th  class="text-center text-sm"rowspan="3" width="25%">
					URAIAN
				</th>
				<th colspan="4" class="bordered text-center text-sm" width="25%">
					SEBELUM PERUBAHAN
				</th>
				<th colspan="4" class="bordered text-center text-sm" width="25%">
					SETELAH PERUBAHAN
				</th>
				<th colspan="2" rowspan="2" class="bordered text-center text-sm" width="15%">
					Bertambah
					<br />
					(Berkurang)
				</th>
			</tr>
			<tr>
				<th colspan="3" class="bordered text-center text-sm">
					RINCIAN PERHITUNGAN
				</th>
				<th rowspan="2" class="bordered text-center text-sm">
					JUMLAH
					<br />
					(Rp)
				</th>
				<th colspan="3" class="bordered text-center text-sm">
					RINCIAN PERHITUNGAN
				</th>
				<th rowspan="2" class="bordered text-center text-sm">
					JUMLAH
					<br />
					(Rp)
				</th>
			</tr>
			<tr>
				<th class="bordered text-center text-sm">
					Volume
				</th>
				<th class="bordered text-center text-sm">
					Satuan
				</th>
				<th class="bordered text-center text-sm">
					Harga Satuan
				</th>
				<th class="bordered text-center text-sm">
					Volume
				</th>
				<th class="bordered text-center text-sm">
					Satuan
				</th>
				<th class="bordered text-center text-sm">
					Harga Satuan
				</th>
				<th class="bordered text-center text-sm">
					(Rp)
				</th>
				<th class="bordered text-center text-sm">
					%
				</th>
			</tr>
			<tr bgcolor="gray">
				<th class="bordered text-center text-sm">
					1
				</th>
				<th class="bordered text-center text-sm">
					2
				</th>
				<th class="bordered text-center text-sm">
					3
				</th>
				<th class="bordered text-center text-sm">
					4
				</th>
				<th class="bordered text-center text-sm">
					5
				</th>
				<th class="bordered text-center text-sm">
					6
				</th>
				<th class="bordered text-center text-sm">
					7
				</th>
				<th class="bordered text-center text-sm">
					8
				</th>
				<th class="bordered text-center text-sm">
					9
				</th>
				<th class="bordered text-center text-sm">
					10
				</th>
				<th class="bordered text-center text-sm">
					11
				</th>
				<th class="bordered text-center text-sm">
					12
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
				$id_belanja_rinc				= 0;
				$nm_belanja_sub					= null;
				$nm_belanja_rinc				= null;
				foreach($results['belanja'] as $key => $val)
				{
					if($val['id_rek_1'] != $id_rek_1)
					{
						$plus_minus_rek_1		= $val['subtotal_rek_1_setelah'] - $val['subtotal_rek_1_sebelum'];
						$persen_rek_1			= $plus_minus_rek_1 / ($val['subtotal_rek_1_sebelum'] == 0 ? 1 : $val['subtotal_rek_1_sebelum']) * 100;
						if($plus_minus_rek_1 < 0)
						{
							$plus_minus_rek_1 = '(' . number_format_indo($plus_minus_rek_1 * -1) . ')';
						}
						else
						{
							$plus_minus_rek_1 = number_format_indo($plus_minus_rek_1);
						}
						if($persen_rek_1 < 0)
						{
							$persen_rek_1 = '(' . number_format_indo(($persen_rek_1 * -1), 2) . ')';
						}
						elseif($val['subtotal_rek_1_sebelum'] == 0)
						{
							$persen_rek_1 = number_format_indo(100, 2);
						}
						else
						{
							$persen_rek_1 = number_format_indo($persen_rek_1, 2);
						}
						echo '
							<tr>
								<td class="bordered text-sm">
									<b>' . $val['kd_rek_1'] . '</b>
								</td>
								<td class="bordered text-sm">
									<b>' . $val['nm_rek_1'] . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_1_sebelum']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_1_setelah']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $plus_minus_rek_1 . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $persen_rek_1 . '</b>
								</td>
							</tr>
						';
					}
					if($val['id_rek_2'] != $id_rek_2)
					{
						$plus_minus_rek_2		= $val['subtotal_rek_2_setelah'] - $val['subtotal_rek_2_sebelum'];
						$persen_rek_2			= $plus_minus_rek_2 / ($val['subtotal_rek_2_sebelum'] == 0 ? 1 : $val['subtotal_rek_2_sebelum']) * 100;
						if($plus_minus_rek_2 < 0)
						{
							$plus_minus_rek_2 = '(' . number_format_indo($plus_minus_rek_2 * -1) . ')';
						}
						else
						{
							$plus_minus_rek_2 = number_format_indo($plus_minus_rek_2);
						}
						if($persen_rek_2 < 0)
						{
							$persen_rek_2 = '(' . number_format_indo(($persen_rek_2 * -1), 2) . ')';
						}
						elseif($val['subtotal_rek_2_sebelum'] == 0)
						{
							$persen_rek_2 = number_format_indo(100, 2);
						}
						else
						{
							$persen_rek_2 = number_format_indo($persen_rek_2, 2);
						}
						echo '
							<tr>
								<td class="bordered text-sm">
									<b>' . $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '</b>
								</td>
								<td style="padding-left:5px" class="bordered text-sm">
									<b>' . $val['nm_rek_2'] . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_2_sebelum']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_2_setelah']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $plus_minus_rek_2 . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $persen_rek_2 . '</b>
								</td>
							</tr>
						';
					}
					if($val['id_rek_3'] != $id_rek_3)
					{
						$plus_minus_rek_3		= $val['subtotal_rek_3_setelah'] - $val['subtotal_rek_3_sebelum'];
						$persen_rek_3			= $plus_minus_rek_3 / ($val['subtotal_rek_3_sebelum'] == 0 ? 1 : $val['subtotal_rek_3_sebelum']) * 100;
						if($plus_minus_rek_3 < 0)
						{
							$plus_minus_rek_3 = '(' . number_format_indo($plus_minus_rek_3 * -1) . ')';
						}
						else
						{
							$plus_minus_rek_3 = number_format_indo($plus_minus_rek_3);
						}
						if($persen_rek_3 < 0)
						{
							$persen_rek_3 = '(' . number_format_indo(($persen_rek_3 * -1), 2) . ')';
						}
						elseif($val['subtotal_rek_3_sebelum'] == 0)
						{
							$persen_rek_3 = number_format_indo(100, 2);
						}
						else
						{
							$persen_rek_3 = number_format_indo($persen_rek_3, 2);
						}
						echo '
							<tr>
								<td class="bordered text-sm">
									<b>' . $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '.' . $val['kd_rek_3'] . '</b>
								</td>
								<td style="padding-left:8px" class="bordered text-sm">
									<b>' . $val['nm_rek_3'] . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_3_sebelum']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_3_setelah']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $plus_minus_rek_3 . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $persen_rek_3 . '</b>
								</td>
							</tr>
						';
					}
					if($val['id_rek_4'] != $id_rek_4)
					{
						$plus_minus_rek_4		= $val['subtotal_rek_4_setelah'] - $val['subtotal_rek_4_sebelum'];
						$persen_rek_4			= $plus_minus_rek_4 / ($val['subtotal_rek_4_sebelum'] == 0 ? 1 : $val['subtotal_rek_4_sebelum']) * 100;
						if($plus_minus_rek_4 < 0)
						{
							$plus_minus_rek_4 = '(' . number_format_indo($plus_minus_rek_4 * -1) . ')';
						}
						else
						{
							$plus_minus_rek_4 = number_format_indo($plus_minus_rek_4);
						}
						if($persen_rek_4 < 0)
						{
							$persen_rek_4 = '(' . number_format_indo(($persen_rek_4 * -1), 2) . ')';
						}
						elseif($val['subtotal_rek_4_sebelum'] == 0)
						{
							$persen_rek_4 = number_format_indo(100, 2);
						}
						else
						{
							$persen_rek_4 = number_format_indo($persen_rek_4, 2);
						}
						echo '
							<tr>
								<td class="bordered text-sm">
									<b>' . $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '.' . $val['kd_rek_3'] . '.' . sprintf('%02d', $val['kd_rek_4']) . '</b>
								</td>
								<td style="padding-left:11px" class="bordered text-sm">
									<b>' . $val['nm_rek_4'] . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_4_sebelum']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_4_setelah']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $plus_minus_rek_4 . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $persen_rek_4 . '</b>
								</td>
							</tr>
						';
					}
					if($val['id_rek_5'] != $id_rek_5)
					{
						$plus_minus_rek_5		= $val['subtotal_rek_5_setelah'] - $val['subtotal_rek_5_sebelum'];
						$persen_rek_5			= $plus_minus_rek_5 / ($val['subtotal_rek_5_sebelum'] == 0 ? 1 : $val['subtotal_rek_5_sebelum']) * 100;
						if($plus_minus_rek_5 < 0)
						{
							$plus_minus_rek_5 = '(' . number_format_indo($plus_minus_rek_5 * -1) . ')';
						}
						else
						{
							$plus_minus_rek_5 = number_format_indo($plus_minus_rek_5);
						}
						if($persen_rek_5 < 0)
						{
							$persen_rek_5 = '(' . number_format_indo(($persen_rek_5 * -1), 2) . ')';
						}
						elseif($val['subtotal_rek_5_sebelum'] == 0)
						{
							$persen_rek_5 = number_format_indo(100, 2);
						}
						else
						{
							$persen_rek_5 = number_format_indo($persen_rek_5, 2);
						}
						echo '
							<tr>
								<td class="text-sm">
									<b data-toggle="tooltip" title="' . htmlspecialchars($val['keterangan']) . '">' . $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '.' . $val['kd_rek_3'] . '.' . sprintf('%02d', $val['kd_rek_4']) . '.' . sprintf('%02d', $val['kd_rek_5']) . ' <i class="fa fa-question-circle text-success"></i></b>
								</td>
								<td class="relative text-sm" style="padding-left:20px">
									<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(3, $val['id_rek_5'], null, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
										<b>' . $val['nm_rek_5'] . '</b>
									</a>
									<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
										' . $controller->get_thread(3, $val['id_rek_5']) . '
										<li class="input-belanja">
											<textarea name="belanja[' . $val['id_rek_5'] . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
											<div class="btn-group pull-right" style="margin-top:12px">
												<button type="submit" class="btn btn-success btn-sm">
													<i class="fa fa-check"></i>
													Simpan
												</button>
												<button type="button" class="btn btn-danger btn-sm">
													<i class="fa fa-times"></i>
													Batal
												</button>
											</div>
										</li>
									</ul>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_5_sebelum']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-center text-sm">
								</td>
								<td class="bordered text-right text-sm">
								</td>
								<td class="bordered text-right text-sm">
									<b>' . number_format_indo($val['subtotal_rek_5_setelah']) . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $plus_minus_rek_5 . '</b>
								</td>
								<td class="bordered text-right text-sm">
									<b>' . $persen_rek_5 . '</b>
								</td>
							</tr>
						';
					}
					if($val['id_belanja_sub'] != $id_belanja_sub)
					{
						$plus_minus_belanja_sub	= $val['subtotal_sub_setelah'] - $val['subtotal_sub_sebelum'];
						$persen_belanja_sub		= $plus_minus_belanja_sub / ($val['subtotal_sub_sebelum'] == 0 ? 1 : $val['subtotal_sub_sebelum']) * 100;
						if($plus_minus_belanja_sub < 0)
						{
							$plus_minus_belanja_sub = '(' . number_format_indo($plus_minus_belanja_sub * -1) . ')';
						}
						else
						{
							$plus_minus_belanja_sub = number_format_indo($plus_minus_belanja_sub);
						}
						if($persen_belanja_sub < 0)
						{
							$persen_belanja_sub = '(' . number_format_indo(($persen_belanja_sub * -1), 2) . ')';
						}
						elseif($val['subtotal_sub_sebelum'] == 0)
						{
							$persen_belanja_sub = number_format_indo(100, 2);
						}
						else
						{
							$persen_belanja_sub = number_format_indo($persen_belanja_sub, 2);
						}
						echo '
							<tr>
								<td>
									
								</td>
								<td class="relative text-sm" style="padding-left:25px">
									<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(4, $val['id_belanja_sub'], null, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
										' . $val['nm_belanja_sub'] . '
									</a>
									<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
										' . $controller->get_thread(4, $val['id_belanja_sub']) . '
										<li class="input-belanja-sub">
											<textarea name="belanja_sub[' . $val['id_belanja_sub'] . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
											<div class="btn-group pull-right" style="margin-top:12px">
												<button type="submit" class="btn btn-success btn-sm">
													<i class="fa fa-check"></i>
													Simpan
												</button>
												<button type="button" class="btn btn-danger btn-sm">
													<i class="fa fa-times"></i>
													Batal
												</button>
											</div>
										</li>
									</ul>
								</td>
									<td class="bordered text-right text-sm">
									</td>
									<td class="bordered text-sm">
									</td>
									<td class="bordered text-right text-sm">
									</td>
									<td class="bordered text-right text-sm">
										' . number_format_indo($val['subtotal_sub_sebelum']) . '
									</td>
									<td class="bordered text-right text-sm">
									</td>
									<td class="bordered text-sm">
									</td>
									<td class="bordered text-right text-sm">
									</td>
									<td class="bordered text-right text-sm">
										' . number_format_indo($val['subtotal_sub_setelah']) . '
									</td>
									<td class="bordered text-right text-sm">
										' . $plus_minus_belanja_sub . '
									</td>
									<td class="bordered text-right text-sm">
										' . $persen_belanja_sub . '
									</td>
							</tr>
						';
					}
					if(($val['id_belanja_rinc'] != $id_belanja_rinc) || ($val['nm_belanja_rinc'] != $nm_belanja_rinc))
					{
						$plus_minus				= $val['total_setelah'] - $val['total_sebelum'];
						$persen					= $plus_minus / ($val['total_sebelum'] == 0 ? 1 : $val['total_sebelum']) * 100;
						if($plus_minus < 0)
						{
							$plus_minus = '(' . number_format_indo($plus_minus * -1) . ')';
						}
						else
						{
							$plus_minus = number_format_indo($plus_minus);
						}
						if($persen < 0)
						{
							$persen = '(' . number_format_indo(($persen * -1), 2) . ')';
						}
						elseif($val['total_sebelum'] == 0)
						{
							$persen = number_format_indo(100, 2);
						}
						else
						{
							$persen = number_format_indo($persen, 2);
						}
						echo '
							<tr>
								<td>
									
								</td>
								<td class="relative text-sm" style="padding-left:30px">
									<a href="javascript:void(0)" class="dropdown-toggle toggle-tooltip prepare-to-trigger" data-toggle="dropdown" title="' . $controller->get_thread(5, $val['id_belanja_rinc'], null, 'tooltip') . '" data-html="true" style="background:#afa;border-bottom:1px dashed #f00;display:inline-block">
										- ' . $val['nm_belanja_rinc'] . ' (' . (0 != $val['vol_1_setelah'] ? (0 != $val['vol_2_setelah'] || 0 != $val['vol_3_setelah'] ? number_format($val['vol_1_setelah']) . ' ' . $val['satuan_1_setelah'] . ' x ' : number_format($val['vol_1_setelah']) . ' ' . $val['satuan_1_setelah']) : null) . (0 != $val['vol_2_setelah'] ? (0 != $val['vol_3_setelah'] ? number_format($val['vol_2_setelah']) . ' ' . $val['satuan_2_setelah'] . ' x ' : number_format($val['vol_2_setelah']) . ' ' . $val['satuan_2_setelah']) : null) . (0 != $val['vol_3_setelah'] ? number_format($val['vol_3_setelah']) . ' ' . $val['satuan_3_setelah'] : null) . ')
									</a>
									<ul class="dropdown-menu no-radius" style="width:320px;padding:12px;margin:0 0;top:0;left:auto">
										' . $controller->get_thread(5, $val['id_belanja_rinc']) . '
										<li class="input-belanja-rinc">
											<textarea name="belanja_rinc[' . $val['id_belanja_rinc'] . '][comments]" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>
											<div class="btn-group pull-right" style="margin-top:12px">
												<button type="submit" class="btn btn-success btn-sm">
													<i class="fa fa-check"></i>
													Simpan
												</button>
												<button type="button" class="btn btn-danger btn-sm">
													<i class="fa fa-times"></i>
													Batal
												</button>
											</div>
										</li>
									</ul>
								</td>
									<td class="bordered text-center text-sm">
										' . number_format_indo($val['vol_123_sebelum'])  . '
									</td>
									<td class="bordered text-center text-sm">
										' . $val['satuan_123_sebelum'] . '
									</td>
									<td class="bordered text-right text-sm">
										' . number_format_indo($val['nilai_sebelum']) . '
									</td>
									<td class="bordered text-right text-sm">
										' . number_format_indo($val['total_sebelum']) . '
									</td>
									<td class="bordered text-center text-sm">
										' . number_format_indo($val['vol_123_setelah'])  . '
									</td>
									<td class="bordered text-center text-sm">
										' . $val['satuan_123_setelah'] . '
									</td>
									<td class="bordered text-right text-sm">
										' . number_format_indo($val['nilai_setelah']) . '
									</td>
									<td class="bordered text-right text-sm">
										' . number_format_indo($val['total_setelah']) . '
									</td>
									<td class="bordered text-right text-sm">
										' . $plus_minus . '
									</td>
									<td class="bordered text-right text-sm">
										' . $persen . '
									</td>
							</tr>
						';
					}
					$id_rek_1					= $val['id_rek_1'];
					$id_rek_2					= $val['id_rek_2'];
					$id_rek_3					= $val['id_rek_3'];
					$id_rek_4					= $val['id_rek_4'];
					$id_rek_5					= $val['id_rek_5'];
					$id_belanja_sub				= $val['id_belanja_sub'];
					$id_belanja_rinc			= $val['id_belanja_rinc'];
					$nm_belanja_sub				= $val['nm_belanja_sub'];
					$nm_belanja_rinc			= $val['nm_belanja_rinc'];
				}
			?>
		</tbody>
	</table>
	<table class="table table-bordered" style="page-break-inside:avoid">
		<tr>
			<td colspan="3">
				Keterangan:
				<br />
			</td>
			<td colspan="3" class="text-center">
				<?php  echo $results['daerah']->nama_daerah ; ?>, <?php echo ($results['tanggal']->tanggal_rka_perubahan ? date_indo($results['tanggal']->tanggal_rka_perubahan) : date('d') . '' . phrase(date('F')) . '' . date('Y') ); ?>
				<br />
				<b><?php echo strtoupper($results['header'][0]['nama_jabatan']); ?></b>
				<br />
				<br />
				<br />
				<br />
				<br />
				<u><b><?php echo $results['header'][0]['nama_pejabat']; ?></b></u>
				<br />
				<?php echo 'NIP. '. $results['header'][0]['nip_pejabat']; ?>
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
			foreach($results['tim_anggaran'] as $key => $val)
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
							' . (1 == get_userdata('group_id') || (15 == get_userdata('group_id') && get_userdata('sub_unit') == $val->id) ? '<a href="' . current_page('../verifikasi', array('req' => 'ttd', 'target' => 'ttd_' . $val->id)) . '" class="btn btn-toggle btn-sm ' . (isset($results['verified']->$id) && 1 == $results['verified']->$id ? 'active' : 'inactive') . ' ajax">
								<span class="handle"></span>
							</a>' : null) . '
						</td>
						<td>
							<p class="verifikator text-center">
								' . (isset($results['verified']->$id) && 1 == $results['verified']->$id ? $ttd : null) . '
							</p>
						</td>
					</tr>
				';
			}
		?>
	</table>
	<table class="table table-bordered">
		<tfoot>
			<tr>
				<td class="text-center text-sm" colspan="3">
					<b>PARAF TIM ASISTENSI</b>
				</td>
			</tr>
			<tr>
				<td class="text-sm text-center">
					<b>1. BAPPEDA 
						<a href="<?php echo current_page('../verifikasi', array('target' => 'perencanaan')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($results['verified']->perencanaan) && 1 == $results['verified']->perencanaan ? 'active' : 'inactive'); ?> ajax">
							<span class="handle"></span>
						</a></b>
					<p class="verifikator text-center">
						<?php echo (isset($results['verified']->perencanaan) && 1 == $results['verified']->perencanaan ? 'Diverifikasi oleh <b>' . $results['verified']->nama_operator_perencanaan . '</b> pada ' . date_indo($results['verified']->waktu_verifikasi_perencanaan, 3, '-') : null); ?>
					</p>
				</td>
				<td class="text-sm text-center">
					<b>2. BPKAD
						<a href="<?php echo current_page('../verifikasi', array('target' => 'keuangan')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($results['verified']->keuangan) && 1 == $results['verified']->keuangan ? 'active' : 'inactive'); ?> ajax">
							<span class="handle"></span>
						</a></b>
					<p class="verifikator text-center">
						<?php echo (isset($results['verified']->keuangan) && 1 == $results['verified']->keuangan ? 'Diverifikasi oleh <b>' . $results['verified']->nama_operator_keuangan . '</b> pada ' . date_indo($results['verified']->waktu_verifikasi_keuangan, 3, '-') : null); ?>
					</p>
				</td>
				<td class="text-sm text-center">
					<b>3. Bagian Pembangunan Setda
						<a href="<?php echo current_page('../verifikasi', array('target' => 'setda')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($results['verified']->setda) && 1 == $results['verified']->setda ? 'active' : 'inactive'); ?> ajax">
							<span class="handle"></span>
						</a></b>
					<p class="verifikator text-center">
						<?php echo (isset($results['verified']->setda) && 1 == $results['verified']->setda ? 'Diverifikasi oleh <b>' . $results['verified']->nama_operator_setda . '</b> pada ' . date_indo($results['verified']->waktu_verifikasi_setda, 3, '-'): null); ?>
					</p>
				</td>
			</tr>
		</tfoot>
	</table>
</form>
<div class="btn-group btn-float" style="margin-bottom:24px">
	<a href="<?php echo current_page('../../kegiatan/kak', array('id_keg' => $this->input->get('id_keg'))); ?>" class="btn btn-warning ajax">
		<i class="fa fa-car"></i>
		KAK
	</a>
	<a href="<?php echo current_page('../../kegiatan/pendukung', array('id_sub' => null, 'id_keg' => $this->input->get('id_keg'))); ?>" class="btn btn-danger ajax">
		<i class="fa fa-book"></i>
		Pendukung
	</a>
	<a href="<?php echo current_page('../../../laporan/anggaran/lembar_asistensi', array('id_sub' => null, 'id_keg' => null, 'sub_unit' => $this->input->get('id_sub'), 'kegiatan' => $this->input->get('id_keg'), 'method' => 'print')); ?>" class="btn btn-info" target="_blank">
		<i class="fa fa-print"></i>
		Asistensi
	</a>
	<a href="<?php echo current_page('../../../laporan/anggaran/rka_221', array('id_sub' => null, 'id_keg' => null, 'kegiatan' => $this->input->get('id_keg'), 'method' => 'print')); ?>" class="btn btn-primary" target="_blank">
		<i class="fa fa-print"></i>
		RKA
	</a>
</div>