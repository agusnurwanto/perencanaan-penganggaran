<!DOCTYPE html>
<html>
	<head>
		<title>
			Lembar Asistensi
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		
		<style type="text/css">
			@import url('<?php echo base_url('themes/assets/fonts/Oxygen/Oxygen.css'); ?>');
			@page
			{
				sheet-size: 13in 8.5in;
				footer: html_footer
			}
			.print
			{
				display: none
			}
			@media print
			{
				.no-print
				{
					display: none
				}
				.print
				{
					display: block
				}
			}
			body
			{
				font-family: 'Oxygen';
				font-size: 13px;
				margin: 0 50px
			}
			label,
			h4
			{
				display: block
			}
			a,
			a:hover,
			a:focus,
			a:visited,
			a:link
			{
				text-decoration: none;
				color: #000
			}
			hr
			{
				border-top:1px solid #999999;
				border-bottom: 0;
				margin-bottom: 15px
			}
			.separator
			{
				border-top: 3px solid #000000;
				border-bottom:1px solid #000000;
				padding:1px;
				margin-bottom: 15px
			}
			.text-sm
			{
				font-size: 10px
			}
			.text-uppercase
			{
				text-transform: uppercase
			}
			.text-muted
			{
				color: #888888
			}
			.text-left
			{
				text-align: left
			}
			.text-right
			{
				text-align: right
			}
			.text-center
			{
				text-align: center
			}
			.text-justify
			{
				text-align: justify
			}
			table
			{
				width: 100%
			}
			th
			{
				text-align:center;
				font-size: 12px;
				white-space: nowrap
			}
			td
			{
				font-size: 12px;
				padding: 5px;
				vertical-align: top
			}
			.table
			{
				border-collapse: collapse
			}
			.bordered
			{
				border:1px solid #000
			}
			.no-border-left
			{
				border-left: 0
			}
			.no-border-top
			{
				border-top: 0
			}
			.no-border-right
			{
				border-right: 0
			}
			.no-border-bottom
			{
				border-bottom: 0
			}
			.no-padding
			{
				padding: 0;
				border: 0
			}
			h1
			{
				font-size: 24px
			}
			h2
			{
				font-size: 22px
			}
			h3
			{
				font-size: 20px
			}
			h4
			{
				font-size: 18px
			}
			h1, h2, h3, h4, h5
			{
				margin-top: 0;
				margin-bottom: 0
			}
		</style>
	</head>
	<body>
		<table align="center">
			<tr>
				<td>
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</td>
				<td align="center" width="100%">
					<h4>
						LEMBAR ASISTENSI RKA SKPD
					</h4>
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : "") ; ?>
					</h4>
					<h4>
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<tr>
				<td width="25%">
					UNIT KERJA
				</td>
				<td width="3%">
					:
				</td>
				<td width="72%">
					<?php echo $results['kegiatan']->kd_urusan; ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_bidang); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_unit); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_sub); ?> <?php echo $results['kegiatan']->nm_sub; ?>
				</td>
			</tr>
			<tr>
				<td>
					JUDUL KEGIATAN
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['kegiatan']->kd_urusan; ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_bidang); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_unit); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_sub); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_program); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_keg); ?> <?php echo $results['kegiatan']->kegiatan; ?>
				</td>
			</tr>
			<tr>
				<td>
					PAGU KEGIATAN
				</td>
				<td>
					:
				</td>
				<td>
					Rp. <?php echo number_format($results['kegiatan']->pagu); ?>
				</td>
			</tr>
			<tr>
				<td>
					SUMBER DANA
				</td>
				<td>
					:
				</td>
				<td>
					PENDAPATAN ASLI DAERAH
				</td>
			</tr>
		</table>
		<!--<br />
		<br />
		<div class="text-center" style="font-size:14px;margin-bottom:50px">
			RIWAYAT ASISTENSI
			<br />
			KEGIATAN <?php //echo $results['kegiatan']->kegiatan; ?>
		</div>-->
		<table class="table">
			<tr>
				<td width="25%">
					BAPPEDA
				</td>
				<td width="3%">
					:
				</td>
				<td width="72%">
					<?php echo (isset($results['approval']->perencanaan) && 1 == $results['approval']->perencanaan ? 'Diverifikasi Oleh <b>' . $results['approval']->nama_operator_perencanaan . '</b> pada ' . date_indo($results['approval']->waktu_verifikasi_perencanaan, 3, '-') : 'Belum Diverifikasi'); ?>
				</td>
			</tr>
			<tr>
				<td>
					BPKAD
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo (isset($results['approval']->keuangan) && 1 == $results['approval']->keuangan ? 'Diverifikasi Oleh <b>' . $results['approval']->nama_operator_keuangan . '</b> pada ' . date_indo($results['approval']->waktu_verifikasi_keuangan, 3, '-') : 'Belum Diverifikasi'); ?>
				</td>
			</tr>
			<tr>
				<td>
					BAGIAN PEMBANGUNAN
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo (isset($results['approval']->setda) && 1 == $results['approval']->setda ? 'Diverifikasi Oleh <b>' . $results['approval']->nama_operator_setda . '</b> pada ' . date_indo($results['approval']->waktu_verifikasi_setda, 3, '-') : 'Belum Diverifikasi'); ?>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" rowspan="2" width="25%">
						URAIAN
					</th>
					<th class="bordered" colspan="2" width="50%">
						CATATAN TIM ASISTENSI
					</th>
					<th class="bordered" rowspan="2" width="25%">
						CATATAN OPD
					</th>
				</tr>
				<tr>
					<th class="bordered">
						Komentar
					</th>
					<th class="bordered">
						Tim
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$tanggapan_pendukung_kak			= false;
					$tanggapan_pendukung_rkbu			= false;
					$tanggapan_pendukung_rab			= false;
					$tanggapan_pendukung_gambar			= false;
					$capaian_program					= false;
					$indikator							= false;
					$kesesuaian							= false;
					$belanja							= false;
					$belanja_sub						= false;
					$belanja_rinc						= false;
					if(isset($results['tanggapan_pendukung']->tanggapan_kak) && !empty($results['tanggapan_pendukung']->tanggapan_kak)) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Tanggapan KAK</b>
								</td>
							</tr>
						';
						$tanggapan_pendukung_kak		= true;
						echo '
							<tr>
								<td style="border:1px solid #000" colspan="4">
									' . (isset($results['tanggapan_pendukung']->tanggapan_kak) ? $results['tanggapan_pendukung']->tanggapan_kak : null) . '
								</td>
							</tr>
						';
					}
					
					if(isset($results['tanggapan_pendukung']->tanggapan_rkbu) && !empty($results['tanggapan_pendukung']->tanggapan_rkbu)) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Tanggapan RKBU</b>
								</td>
							</tr>
						';
						$tanggapan_pendukung_rkbu		= true;
						echo '
							<tr>
								<td style="border:1px solid #000" colspan="4">
									' . (isset($results['tanggapan_pendukung']->tanggapan_rkbu) ? $results['tanggapan_pendukung']->tanggapan_rkbu : null) . '
								</td>
							</tr>
						';
					}
					
					if(isset($results['tanggapan_pendukung']->tanggapan_rab) && !empty($results['tanggapan_pendukung']->tanggapan_rab)) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Tanggapan RAB</b>
								</td>
							</tr>
						';
						$tanggapan_pendukung_rab		= true;
						echo '
							<tr>
								<td style="border:1px solid #000" colspan="4">
									' . (isset($results['tanggapan_pendukung']->tanggapan_rab) ? $results['tanggapan_pendukung']->tanggapan_rab : null) . '
								</td>
							</tr>
						';
					}
					
					if(isset($results['tanggapan_pendukung']->tanggapan_gambar) && !empty($results['tanggapan_pendukung']->tanggapan_gambar)) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Tanggapan Gambar</b>
								</td>
							</tr>
						';
						$tanggapan_pendukung_rab		= true;
						echo '
							<tr>
								<td style="border:1px solid #000" colspan="4">
									' . (isset($results['tanggapan_pendukung']->tanggapan_gambar) ? $results['tanggapan_pendukung']->tanggapan_gambar : null) . '
								</td>
							</tr>
						';
					}
					
					if(isset($results['capaian_program']) && !empty($results['capaian_program'])) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Capaian Program</b>
								</td>
							</tr>
						';
					}
					foreach($results['capaian_program'] as $key => $val)
					{
						if($val->comments)
						{
							$capaian_program			= true;
							echo '
								<tr>
									<td style="border-left:1px solid #000">
										' . $val->tolak_ukur . '
									</td>
									<td style="border-left:1px solid #000">
										' . $val->comments . '<br /><div class="text-sm">' . date_indo($val->tanggal, 3, '-') . '</div>
									</td>
									<td class="text-sm" style="border-left:1px solid #000">
										' . $val->operator . '
									</td>
									<td style="border-left:1px solid #000;border-right:1px solid #000">
										' . $val->tanggapan . '<br /><div class="text-sm">' . ($val->tanggal_tanggapan != 0 ? date_indo($val->tanggal_tanggapan, 3, '-') : null) . '</div>
									</td>
								</tr>
							';
						}
					}
					if(!$capaian_program)
					{
						//echo '<tr><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td></tr>';
					}
					if(isset($results['indikator']) && !empty($results['indikator']))
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Indikator</b>
								</td>
							</tr>
						';
					}
					$tolak_ukur						= null;
					foreach($results['indikator'] as $key => $val)
					{
						if($val->comments)
						{
							$indikator				= true;
							echo '
								<tr>
									<td style="border-left:1px solid #000">
										' . ($tolak_ukur != $val->tolak_ukur ? $val->tolak_ukur : null) . '
									</td>
									<td style="border-left:1px solid #000">
										' . $val->comments . '<br /><div class="text-sm">' . date_indo($val->tanggal, 3, '-') . '</div>
									</td>
									<td class="text-sm" style="border-left:1px solid #000">
										' . $val->operator . '
									</td>
									<td style="border-left:1px solid #000;border-right:1px solid #000">
										' . $val->tanggapan . '<br /><div class="text-sm">' . ($val->tanggal_tanggapan != 0 ? date_indo($val->tanggal_tanggapan, 3, '-') : null) . '</div>
									</td>
								</tr>
							';
							$tolak_ukur				= $val->tolak_ukur;
						}
					}
					if(!$indikator)
					{
						//echo '<tr><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td></tr>';
					}
					
					if(!empty($results['kelompok_sasaran'])) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Kelompok Sasaran</b>
								</td>
							</tr>
						';
					}
					$kelompok_sasaran						= null;
					foreach($results['kelompok_sasaran'] as $key => $val)
					{
						if($val->comments)
						{
							$kelompok_sasaran			= true;
							echo '
								<tr>
									<td style="border-left:1px solid #000">
										' . ($tolak_ukur != $val->kelompok_sasaran ? $val->kelompok_sasaran : null) . '
									</td>
									<td style="border-left:1px solid #000">
										' . $val->comments . '<br /><div class="text-sm">' . date_indo($val->tanggal, 3, '-') . '</div>
									</td>
									<td class="text-sm" style="border-left:1px solid #000">
										' . $val->operator . '
									</td>
									<td style="border-left:1px solid #000;border-right:1px solid #000">
										' . $val->tanggapan . '<br /><div class="text-sm">' . ($val->tanggal_tanggapan != 0 ? date_indo($val->tanggal_tanggapan, 3, '-') : null) . '</div>
									</td>
								</tr>
							';
							$kelompok_sasaran			= $val->kelompok_sasaran;
						}
					}
					if(!$kelompok_sasaran)
					{
						//echo '<tr><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td></tr>';
					}
					
					if(!empty($results['kesesuaian'])) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Kesesuaian</b>
								</td>
							</tr>
						';
					}
					$kesesuaian						= null;
					foreach($results['kesesuaian'] as $key => $val)
					{
						if($val->comments)
						{
							$kesesuaian				= true;
							echo '
								<tr>
									<td style="border-left:1px solid #000">
										Kesesuaian
									</td>
									<td style="border-left:1px solid #000">
										' . $val->comments . '<br /><div class="text-sm">' . date_indo($val->tanggal, 3, '-') . '</div>
									</td>
									<td class="text-sm" style="border-left:1px solid #000">
										' . $val->operator . '
									</td>
									<td style="border-left:1px solid #000;border-right:1px solid #000">
										' . $val->tanggapan . '<br /><div class="text-sm">' . ($val->tanggal_tanggapan != 0 ? date_indo($val->tanggal_tanggapan, 3, '-') : null) . '</div>
									</td>
								</tr>
							';
							$kesesuaian				= $val->comments;
						}
					}
					if(!$kesesuaian)
					{
						//echo '<tr><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td></tr>';
					}
					
					if(!empty($results['belanja'])) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Rekening</b>
								</td>
							</tr>
						';
					}
					$uraian							= null;
					foreach($results['belanja'] as $key => $val)
					{
						if($val->comments)
						{
							$belanja				= true;
							echo '
								<tr>
									<td style="border-left:1px solid #000">
										' . ($uraian != $val->uraian ? $val->uraian : null) . '
									</td>
									<td style="border-left:1px solid #000">
										' . $val->comments . '<br /><div class="text-sm">' . date_indo($val->tanggal, 3, '-') . '</div>
									</td>
									<td class="text-sm" style="border-left:1px solid #000">
										' . $val->operator . '
									</td>
									<td style="border-left:1px solid #000;border-right:1px solid #000">
										' . $val->tanggapan . '<br /><div class="text-sm">' . ($val->tanggal_tanggapan != 0 ? date_indo($val->tanggal_tanggapan, 3, '-') : null) . '</div>
									</td>
								</tr>
							';
						}
						$uraian				= $val->uraian;
					}
					if(!$belanja)
					{
						//echo '<tr><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td></tr>';
					}
					if(isset($results['belanja_sub']) && !empty($results['belanja_sub'])) 
					{
						echo '<tr>
								<td class="bordered" colspan="4">
									<b>Sub Rekening</b>
								</td>
							</tr>
						';
					}
					$uraian							= null;
					foreach($results['belanja_sub'] as $key => $val)
					{
						if($val->comments)
						{
							$belanja_sub		= true;
							echo '
								<tr>
									<td style="border-left:1px solid #000">
										' . ($uraian != $val->uraian ? $val->uraian : null) . '
									</td>
									<td style="border-left:1px solid #000">
										' . $val->comments . '<br /><div class="text-sm">' . date_indo($val->tanggal, 3, '-') . '</div>
									</td>
									<td class="text-sm" style="border-left:1px solid #000">
										' . $val->operator . '
									</td>
									<td style="border-left:1px solid #000;border-right:1px solid #000">
										' . $val->tanggapan . '<br /><div class="text-sm">' . ($val->tanggal_tanggapan != 0 ? date_indo($val->tanggal_tanggapan, 3, '-') : null) . '</div>
									</td>
								</tr>
							';
						}
						$uraian				= $val->uraian;
					}
					if(!$belanja_sub)
					{
						//echo '<tr><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000">&nbsp;</td></tr>';
					}
					if(isset($results['belanja_rinc']) && !empty($results['belanja_rinc'])) 
					{
						echo '
							<tr>
								<td class="bordered" colspan="4">
									<b>Rincian</b>
								</td>
							</tr>
						';
					}
					$uraian							= null;
					foreach($results['belanja_rinc'] as $key => $val)
					{
						if($val->comments)
						{
							$belanja_rinc		= true;
							echo '
								<tr>
									<td style="border-left:1px solid #000">
										' . ($uraian != $val->uraian ? $val->uraian : null) . '
									</td>
									<td style="border-left:1px solid #000">
										' . $val->comments . '<br /><div class="text-sm">' . date_indo($val->tanggal, 3, '-') . '</div>
									</td>
									<td class="text-sm" style="border-left:1px solid #000">
										' . $val->operator . '
									</td>
									<td style="border-left:1px solid #000;border-right:1px solid #000">
										' . $val->tanggapan . '<br /><div class="text-sm">' . ($val->tanggal_tanggapan != 0 ? date_indo($val->tanggal_tanggapan, 3, '-') : null) . '</div>
									</td>
								</tr>
							';
						}
						$uraian				= $val->uraian;
					}
					echo '<tr><td style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000">&nbsp;</td><td style="border-left:1px solid #000;border-right:1px solid #000;border-bottom:1px solid #000">&nbsp;</td></tr>';
				?>
			</tbody>
		</table>
		<br />
		<br /><!--
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="text-center" width="50%">
					<!-- Mengetahui,
					<br />
					<b>
						<?php //echo $header['jabatan_kpa']; ?>
					</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php //echo $header['kpa']; ?>
						</b>
					</u>
					<br />
					NIP <?php //echo $header['nip_kpa']; ?> -->
				</td>
				<!--<td class="text-center" width="50%">
					<?php //echo (isset($nama_daerah) ? $nama_daerah : "") ; ?>, <?php //echo $tanggal_cetak; ?>,
					<br />
						<b>
							<?php //echo $header['jabatan_camat']; ?>
						</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php //echo $header['camat']; ?>
						</b>
					</u>
					<br />
					NIP <?php //echo $header['nip_camat']; ?>
				</td>
			</tr>
		</table>-->
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
						</i>
					</td>
					<td class="text-muted text-sm text-right">
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>