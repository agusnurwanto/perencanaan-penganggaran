<!DOCTYPE html>
<html>
	<head>
		<title>
			Rekapitulasi Rekening
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		
		<style type="text/css">
			@import url('<?php echo base_url('themes/assets/fonts/Oxygen/Oxygen.css'); ?>');
			@page
			{
				sheet-size: 8.5in 13in;
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
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						REKAPITULASI REKENING
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
				<td width="180">
					Urusan Pemerintahan
				</td>
				<td width="1">
					:
				</td>
				<td width="100">
					<?php echo $results['unit']->kd_urusan; ?>
				</td>
				<td>
					<?php echo $results['unit']->nm_urusan; ?>
				</td>
			</tr>
			<tr>
				<td>
					Bidang Pemerintahan
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['unit']->kd_urusan . '.' . sprintf('%02d', $results['unit']->kd_bidang); ?>
				</td>
				<td>
					<?php echo $results['unit']->nm_bidang; ?>
				</td>
			</tr>
			<tr>
				<td>
					Unit Organisasi
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['unit']->kd_urusan . '.' . sprintf('%02d', $results['unit']->kd_bidang) . '.' . sprintf('%02d', $results['unit']->kd_unit); ?>
				</td>
				<td>
					<?php echo $results['unit']->nm_unit; ?>
				</td>
			</tr>
			<!--<tr>
				<td>
					Sub Unit Organisasi
				</td>
				<td>
					:
				</td>
				<td>
					<?php //echo $results['kegiatan']->kd_urusan . '.' . sprintf('%02d', $results['kegiatan']->kd_bidang) . '.' . sprintf('%02d', $results['kegiatan']->kd_unit) . '.' . sprintf('%02d', $results['kegiatan']->kd_sub); ?>
				</td>
				<td>
					<?php //echo $results['kegiatan']->nm_sub; ?>
				</td>
			</tr>
			<tr>
				<td>
					Program
				</td>
				<td>
					:
				</td>
				<td>
					<?php //echo $results['kegiatan']->kd_urusan . '.' . sprintf('%02d', $results['kegiatan']->kd_bidang) . '.' . sprintf('%02d', $results['kegiatan']->kd_unit) . '.' . sprintf('%02d', $results['kegiatan']->kd_sub) . '.' . sprintf('%02d', $results['kegiatan']->kd_program); ?>
				</td>
				<td>
					<?php //echo $results['kegiatan']->nm_program; ?>
				</td>
			</tr>
			<tr>
				<td>
					Kegiatan
				</td>
				<td>
					:
				</td>
				<td>
					<?php //echo $results['kegiatan']->kd_urusan . '.' . sprintf('%02d', $results['kegiatan']->kd_bidang) . '.' . sprintf('%02d', $results['kegiatan']->kd_unit) . '.' . sprintf('%02d', $results['kegiatan']->kd_sub) . '.' . sprintf('%02d', $results['kegiatan']->kd_program) . '.' . sprintf('%02d', $results['kegiatan']->kd_keg); ?>
				</td>
				<td>
					<?php //echo $results['kegiatan']->kegiatan; ?>
				</td>
			</tr>-->
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="8%">
						KODE
					</th>
					<th class="bordered" width="32%">
						URAIAN
					</th>
					<th class="bordered" width="10%">
						NILAI
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$jumlah_total_rek_5				= 0;
					$total_tw_1						= 0;
					$total_tw_2						= 0;
					$total_tw_3						= 0;
					$total_tw_4						= 0;
					$selisih						= 0;
					foreach($results['data'] as $key => $val)
					{
						//$selisih					= $val->rekening - $val->tw_1 - $val->tw_2 - $val->tw_3 - $val->tw_4;
						echo '	
								<td class="bordered">
									' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . sprintf('%02d', $val->kd_rek_4) . '.' . sprintf('%02d', $val->kd_rek_5) . '
								</td>
								<td class="bordered">
									' . $val->uraian_rek_5 . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val->total_rek_5) . '
								</td>
							</tr>
						';
						$jumlah_total_rek_5				+= $val->total_rek_5;
					}
				?>
			</tbody>
				<tr>
					<td colspan="2" class="bordered text-center">
						<b>JUMLAH</b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format($jumlah_total_rek_5); ?></b>
					</td>
				</tr>
		</table>
		<br />
		<br />
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="text-center" width="50%">
					Mengetahui,
					<br />
					<b><?php //echo $header['jabatan_kpa']; ?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php //echo $header['kpa']; ?></b></u>
					<br />
					NIP <?php //echo $header['nip_kpa']; ?>
				</td>
				<td class="text-center" width="50%">
					<?php echo $nama_daerah; ?>, <?php echo $tanggal_cetak; ?>
					<br />
					<b><?php //echo $header['jabatan_bendahara']; ?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php //echo $header['bendahara']; ?></b></u>
					<br />
					NIP <?php //echo $header['nip_bendahara']; ?>
				</td>
			</tr>
		</table>
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							<?php echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
						</i>
					</td>
					<td class="text-muted text-sm text-right">
						<?php echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>