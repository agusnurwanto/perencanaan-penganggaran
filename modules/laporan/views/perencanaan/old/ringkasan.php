<!DOCTYPE html>
<html>
	<head>
		<title>
			Ringkasan
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
					<h5>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : ""); ?>
					</h5>
					<h5>
						RINGKASAN RANCANGAN ANGGARAN PENDAPATAN DAN BELANJA DAERAH
					</h5>
					<h5>
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
					</h5>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="10%">
						KODE
					</th>
					<th class="bordered" width="60%">
						URAIAN
					</th>
					<th class="bordered" width="10%">
						JUMLAH
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$jumlah									= 0;
					$kd_rek_1								= 0;
					$kd_rek_2								= 0;
					$kd_rek_3								= 0;
					foreach($results['data'] as $key => $val)
					{
						//$jumlah								= $val['rka'] + $val['model'] ;
						if($val->kd_rek_1 != $kd_rek_1)
						{
								if($val->kd_rek_1 == 4)
									{
							echo '
								<tr>
									<td class="bordered">
										<b>' . $val->kd_rek_1 . '</b>
									</td>
									<td class="bordered">
										<b>' . $val->uraian_rek_rek_1 . '</b>
									</td>

									<td class="bordered text-right">
										<b>' . number_format(5826941089195) . '</b>
									</td>
								</tr>
								';
						}
						else
						{

							echo '
								<tr>
									<td class="bordered">
										<b>' . $val->kd_rek_1 . '</b>
									</td>
									<td class="bordered">
										<b>' . $val->uraian_rek_rek_1 . '</b>
									</td>

									<td class="bordered text-right">
										<b>' . number_format($val->total_rek_1) . '</b>
									</td>
								</tr>
								';
						}


						}






						if($val->kd_rek_2 != $kd_rek_2)
						{
							
if($val->kd_rek_1 == 4 and $val->kd_rek_2 == 1)
{

							echo '
								<tr>
									<td class="bordered">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '</b>
									</td>
									<td class="bordered" style="padding-left:10px">
										<b>' . $val->uraian_rek_rek_2 . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format(3017100020330) . '</b>
									</td>
								</tr>
								';
						}

						else if($val->kd_rek_1 == 4 and $val->kd_rek_2 == 2)
							{

							echo '
								<tr>
									<td class="bordered">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '</b>
									</td>
									<td class="bordered" style="padding-left:10px">
										<b>' . $val->uraian_rek_rek_2 . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format( 1662911894000 ) . '</b>
									</td>
								</tr>
								';
							}


							else if($val->kd_rek_1 == 4 and $val->kd_rek_2 == 3)
							{

							echo '
								<tr>
									<td class="bordered">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '</b>
									</td>
									<td class="bordered" style="padding-left:10px">
										<b>' . $val->uraian_rek_rek_2 . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format(  1146929174865) . '</b>
									</td>
								</tr>
								';
							}

						else
						{
echo '
								<tr>
									<td class="bordered">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '</b>
									</td>
									<td class="bordered" style="padding-left:10px">
										<b>' . $val->uraian_rek_rek_2 . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val->total_rek_2) . '</b>
									</td>
								</tr>
								';

						}

						}
						if($val->kd_rek_3 != $kd_rek_3)
						{
							echo '
								<tr>
									<td class="bordered">
										' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '
									</td>
									<td class="bordered" style="padding-left:20px">
										' . $val->uraian_rek_rek_3 . '
									</td>
									<td class="bordered text-right">
										' . number_format($val->total_rek_3) . '
									</td>
								</tr>
								';
						}
						//$num++;
						$kd_rek_1					= $val->kd_rek_1;
						$kd_rek_2					= $val->kd_rek_2;
						$kd_rek_3					= $val->kd_rek_3;
					}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="bordered text-center">
					<b>JUMLAH</b>
				</td>
				<td class="bordered text-right">
					<b><?php //echo number_format($total_rka); ?></b>
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
					<?php echo (isset($nama_daerah) ? $nama_daerah : ""); ?>, <?php echo $tanggal_cetak; ?>
					<br />
						<b><?php echo ($results['header']->jabatan_kepala_daerah ? strtoupper($results['header']->jabatan_kepala_daerah) : '-'); ?>
						</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo ($results['header']->nama_kepala_daerah ? strtoupper($results['header']->nama_kepala_daerah) : '-'); ?></b></u>
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
						<?php echo phrase('page') . ' {PAGENO} dari {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>