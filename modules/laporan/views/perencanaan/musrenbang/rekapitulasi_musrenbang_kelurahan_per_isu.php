<!DOCTYPE html>
<html>
	<head>
		<title>
			Musrenbang Kelurahan per Isu
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		
		<style type="text/css">
			@page
			{
				footer: html_footer; /* !!! apply only when the htmlpagefooter is sets !!! */
				sheet-size: 13in 8.5in;
				margin: 50, 40, 40, 40
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
				font-family: Tahoma
			}
			.divider
			{
				display: block;
				border-top: 3px solid #000;
				border-bottom: 1px solid #000;
				padding: 1px;
				margin-bottom: 15px
			}
			.text-sm-2
			{
				font-size: 10px
			}
			.text-sm
			{
				font-size: 8px
			}
			.text-uppercase
			{
				text-transform: uppercase
			}
			.text-muted
			{
				color: #888
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
			table
			{
				width: 100%
			}
			th
			{
				font-weight: bold;
				font-size: 12px;
				padding: 6px;
			}
			td
			{
				vertical-align: top;
				font-size: 10px;
				padding: 5px;
			}
			.v-middle
			{
				vertical-align: middle
			}
			.table
			{
				border-collapse: collapse
			}
			.border
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
				padding: 0
			}
			.no-margin
			{
				margin: 0
			}
			h1
			{
				font-size: 18px
			}
			p
			{
				margin: 0
			}
			.dotted-bottom
			{
				border-bottom: 1px dotted #000
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
		<table class="table" align="center">
			<tr>
				<th width="100" class="border no-border-right">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</th>
				<th class="border no-border-left" align="center" width="100%" colspan="9">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						REKAPITULASI MUSRENBANG KELURAHAN BERDASARKAN ISU
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year')?>
					</h4>
				</th>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border" rowspan="2">
						KODE
					</th>
					<th class="border" rowspan="2">
						URAIAN
					</th>
					<th class="border" colspan="2">
						USULAN RW
					</th>
					<th class="border" colspan="2">
						DITERIMA KELURAHAN
					</th>
					<th class="border" colspan="2">
						DITOLAK KELURAHAN
					</th>
					<th class="border" colspan="2">
						USULAN KELURAHAN
					</th>
				</tr>
				<tr>
					<th class="border">
						JUMLAH
					</th>
					<th class="border">
						TOTAL
					</th>
					<th class="border">
						JUMLAH
					</th>
					<th class="border">
						TOTAL
					</th>
					<th class="border">
						JUMLAH
					</th>
					<th class="border">
						TOTAL
					</th>
					<th class="border">
						JUMLAH
					</th>
					<th class="border">
						TOTAL
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_isu									= 0;
					$jumlah_usulan							= 0;
					$nilai_usulan							= 0;
					$jumlah_diterima_kelurahan				= 0;
					$nilai_diterima_kelurahan				= 0;
					$jumlah_ditolak_kelurahan				= 0;
					$nilai_ditolak_kelurahan				= 0;
					$jumlah_usulan_kelurahan				= 0;
					$nilai_usulan_kelurahan					= 0;
					foreach($results['data'] as $key => $val)
					{
						$jumlah_usulan						+= $val['jumlah_usulan'];
						$nilai_usulan						+= $val['nilai_usulan'];
						$jumlah_diterima_kelurahan			+= $val['jumlah_diterima_kelurahan'];
						$nilai_diterima_kelurahan			+= $val['nilai_diterima_kelurahan'];
						$jumlah_ditolak_kelurahan			+= $val['jumlah_ditolak_kelurahan'];
						$nilai_ditolak_kelurahan			+= $val['nilai_ditolak_kelurahan'];
						$jumlah_usulan_kelurahan			+= $val['jumlah_usulan_kelurahan'];
						$nilai_usulan_kelurahan				+= $val['nilai_usulan_kelurahan'];
						if($val['id_isu'] != $id_isu)
						{							
							$num									= 1;
							echo '
								<tr>
									<td class="border">
										<b>
											' . sprintf('%02d', $val['kode_isu']) . '
										</b>
									</td>
									<td class="border">
										<b>
											' . $val['nama_isu'] . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['jumlah_usulan_isu']) . '											
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['nilai_usulan_isu']) . '											
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['jumlah_diterima_kelurahan_isu']) . '											
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['nilai_diterima_kelurahan_isu']) . '	
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['jumlah_ditolak_kelurahan_isu']) . '	
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['nilai_ditolak_kelurahan_isu']) . '	
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['jumlah_usulan_kelurahan_isu']) . '	
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['nilai_usulan_kelurahan_isu']) . '	
										</b>
									</td>
								</tr>
							';
						}
						if(isset($val['kode_kelurahan']))
						{
							echo '
								<tr>
									<td class="border">										
										' . sprintf('%02d', $val['kode_isu']) . '.' . sprintf('%02d', $num) . '
									</td>
									<td class="border" style="padding-left:15px">
										' . $val['nama_kelurahan'] . ' 
									</td>
									<td class="border" align="right">
										' . number_format($val['jumlah_usulan']) . '								
									</td>
									<td class="border" align="right">
										' . number_format($val['nilai_usulan']) . '									
									</td>
									<td class="border" align="right">
										' . number_format($val['jumlah_diterima_kelurahan']) . '
									</td>
									<td class="border" align="right">
										' . number_format($val['nilai_diterima_kelurahan']) . '
									</td>
									<td class="border" align="right">
										' . number_format($val['jumlah_ditolak_kelurahan']) . '										
									</td>
									<td class="border" align="right">
										' . number_format($val['nilai_ditolak_kelurahan']) . '										
									</td>
									<td class="border" align="right">
										' . number_format($val['jumlah_usulan_kelurahan']) . '										
									</td>
									<td class="border" align="right">
										' . number_format($val['nilai_usulan_kelurahan']) . '										
									</td>
								</tr>
							';
						}
						$id_isu									= $val['id_isu'];
						$num++;
					}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="border" align="center">
					<b>
						JUMLAH
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($jumlah_usulan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($nilai_usulan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($jumlah_diterima_kelurahan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($nilai_diterima_kelurahan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($jumlah_ditolak_kelurahan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($nilai_ditolak_kelurahan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($jumlah_usulan_kelurahan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($nilai_usulan_kelurahan); ?>
					</b>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
						</i>
					</td>
					<td class="text-muted text-sm" align="right">
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>