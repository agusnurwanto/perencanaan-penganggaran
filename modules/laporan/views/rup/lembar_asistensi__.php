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
				font-size: 13px
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
				border-top: 1px solid #999999;
				border-bottom: 0;
				margin-bottom: 15px
			}
			.separator
			{
				border-top: 3px solid #000000;
				border-bottom: 1px solid #000000;
				padding: 1px;
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
				border: 1px solid #000
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
				<td width="20%">
					UNIT KERJA
				</td>
				<td width="3%">
					:
				</td>
				<td width="77%">
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
					<?php echo $results['kegiatan']->kegiatan; ?>
				</td>
			</tr>
			<tr>
				<td>
					KODE KEGIATAN
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['kegiatan']->kd_urusan; ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_bidang); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_unit); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_sub); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_program); ?>.<?php echo sprintf("%02d", $results['kegiatan']->kd_keg); ?>
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
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="5%">
						NO
					</th>
					<th class="bordered" width="80%">
						CATATAN
					</th>
					<th class="bordered" width="15%">
						NAMA DAN PARAF
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="bordered" align="center">
						1
					</td>
					<td class="bordered">
						SEKRETARIAT
						<br /><br /><br /><br /><br /><br /><br /><br />
					</td>
					<td class="bordered">
						
					</td>
				</tr>
				<tr>
					<td class="bordered" align="center">
						2
					</td>
					<td class="bordered">
						BAPPEDA : <?php echo $results['kegiatan']->nama_bidang; ?>
						<br />
						<b>Capaian Program</b><br/>
					<?php	
						foreach($results['capaian_program'] as $key => $val)
						{
							echo $val['tolak_ukur'] . ' - ' . $val['comments'] . ' - ' . $val['operator'] . ' <br /> ';
						}
					?>
						<br /><b>Indikator</b><br/>
					<?php	
						foreach($results['indikator']['masukan'] as $key => $val)
						{
							if($val['comments'] != "")
							{
								echo $val['nm_indikator'] . ' - ' . $val['comments'];
							}
						}
					?>
					</td>
					<td class="bordered">
						
					</td>
				</tr>
				<tr>
					<td class="bordered" align="center">
						3
					</td>
					<td class="bordered">
						BPKAD
						<br />
						<b>Rekening</b><br/>
					<?php
						foreach($results['belanja'] as $key => $val)
						{
							if($val['comments'] != "")
							{
								echo $val['uraian'] . ' - ' . $val['comments'] . ' - ' . $val['operator'] . ' <br /> ';
							}
						}
					?>
						<br /><b>Sub Rekening</b><br/>
					<?php
						foreach($results['belanja_sub'] as $key => $val)
						{
							if($val['comments'] != "")
							{
								echo $val['uraian'] . ' - ' . $val['comments'] . ' - ' . $val['operator'] . ' <br /> ';
							}
						}
					?>
						<br /><b>Rincian</b><br/>
					<?php
						foreach($results['belanja_rinc'] as $key => $val)
						{
							if($val['comments'] != "")
							{
								echo $val['uraian'] . ' - ' . $val['comments'] . ' - ' . $val['operator'] . ' <br /> ';
							}
						}
					?>
					</td>
					<td class="bordered">
						
					</td>
				</tr>
				<tr>
					<td class="bordered" align="center">
						4
					</td>
					<td class="bordered">
						BAGIAN PEMBANGUNAN SEKRETARIAT DAERAH
						<br /><br /><br /><br /><br /><br /><br /><br />
					</td>
					<td class="bordered">
						
					</td>
				</tr>
				<?php
					/*$num									= 1;
					foreach($results['data'] as $key => $val)
					{
						echo '
							<tr>
								<td class="bordered text-center">
									' . $num . '
								</td>
								<td class="bordered">
									' . $val['uraian'] . '
								</td>
								<td class="bordered text-center">
									' . $val['satuan_1'] . '
								</td>
								<td class="bordered text-center">
									' . $val['satuan_2'] . '
								</td>
								<td class="bordered">
									' . $val['satuan_3'] . '
								</td>
								<td class="bordered text-right">
									' . number_format($val['nilai']) . '
								</td>
								<td class="bordered">
									' . $val['deskripsi'] . '
								</td>
							</tr>
							';
						$num++;
					}*/
				?>
			</tbody>
			<!--
				<tr>
					<td colspan="6" class="bordered text-center">
						<b>
							JUMLAH
						</b>
					</td>
					<td class="bordered text-right">
						<b>
							<?php //echo number_format($total); ?>
						</b>
					</td>
				</tr>
			-->
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