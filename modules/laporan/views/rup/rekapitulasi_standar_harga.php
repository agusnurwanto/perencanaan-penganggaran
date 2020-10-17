<!DOCTYPE html>
<html>
	<head>
		<title>
			Rekapitulasi Standar Harga
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
	<?php
		/*if($this->input->get('status') != null)
		{
			$status 				= $this->input->get('status');
			if($status == 1)
			{
				$status_text	= '<b>Usulan Kelurahan</b>';
			}
			elseif($status == 2)
			{
				$status_text	= '<b>Diterima Kecamatan</b>';
			}
			elseif($status == 3)
			{
				$status_text	= '<b>Ditolak Kecamatan</b>';
			}
			elseif($status == 4)
			{
				$status_text	= '<b>Usulan Kecamatan</b>';
			}
			elseif($status == 5)
			{
				$status_text	= '<b>Pilih Semua</b>';
			}
			elseif($status == 6)
			{
				$status_text	= '<b>Diterima Kecamatan dan Usulan Kecamatan</b>';
			}
			else
			{
				$status_text	= '<b>Anda Harus Pilih Status</b>';
			}
		}
		else
		{
			$status_text	= '<b>Anda Harus Pilih Status</b>';
		}*/
	?>
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
						REKAPITULASI STANDAR HARGA
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year'); ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="3%">
						NO
					</th>
					<th class="bordered" width="32%">
						URAIAN
					</th>
					<th class="bordered" width="8%">
						MASIH USULAN
					</th>
					<th class="bordered" width="8%">
						DISETUJUI
					</th>
					<th class="bordered" width="8%">
						DITOLAK
					</th>
					<th class="bordered" width="8%">
						TOTAL
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total_sht								= 0;
					$total_sbm								= 0;
					$total_usulan							= 0;
					$total_disetujui						= 0;
					$total_ditolak							= 0;
					$total_all								= 0;
					//foreach($results['data'] as $key => $val)
					//{
						$total_sht							= $results['data'][0]['jumlah_sht_usulan'] + $results['data'][0]['jumlah_sht_disetujui'] + $results['data'][0]['jumlah_sht_ditolak'] ;
						$total_sbm							= $results['data'][0]['jumlah_sbm_usulan'] + $results['data'][0]['jumlah_sbm_disetujui'] + $results['data'][0]['jumlah_sbm_ditolak'] ;
						$total_usulan						= $results['data'][0]['jumlah_sht_usulan'] + $results['data'][0]['jumlah_sbm_usulan'];
						$total_disetujui					= $results['data'][0]['jumlah_sht_disetujui'] + $results['data'][0]['jumlah_sbm_disetujui'];
						$total_ditolak						= $results['data'][0]['jumlah_sht_ditolak'] + $results['data'][0]['jumlah_sbm_ditolak'];
						$total_all							= $total_sht + $total_sbm;
						echo '
							<tr>
								<td class="bordered text-center">
									1
								</td>
								<td class="bordered">
									Standar Harga Tertinggi (SHT)
								</td>
								<td class="bordered text-right">
									' . number_format($results['data'][0]['jumlah_sht_usulan']) . '
								</td>
								<td class="bordered text-right">
									' . number_format($results['data'][0]['jumlah_sht_disetujui']) . '
								</td>
								<td class="bordered text-right">
									' . number_format($results['data'][0]['jumlah_sht_ditolak']) . '
								</td>
								<td class="bordered text-right">
									' . number_format($total_sht) . '
								</td>
							</tr>
							<tr>
								<td class="bordered text-center">
									2
								</td>
								<td class="bordered">
									Standar Biaya Masukan (SBM)
								</td>
								<td class="bordered text-right">
									' . number_format($results['data'][0]['jumlah_sbm_usulan']) . '
								</td>
								<td class="bordered text-right">
									' . number_format($results['data'][0]['jumlah_sbm_disetujui']) . '
								</td>
								<td class="bordered text-right">
									' . number_format($results['data'][0]['jumlah_sbm_ditolak']) . '
								</td>
								<td class="bordered text-right">
									' . number_format($total_sbm) . '
								</td>
							</tr>
							';
						//$num++;
					//}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="bordered text-center">
					<b>
						JUMLAH
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php echo number_format($total_usulan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php echo number_format($total_disetujui); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php echo number_format($total_ditolak); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php echo number_format($total_all); ?>
					</b>
				</td>
			</tr>
		</table>
		<br />
		<br />
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
				<td class="text-center" width="50%">
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo $tanggal_cetak; ?>,
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
		</table>
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