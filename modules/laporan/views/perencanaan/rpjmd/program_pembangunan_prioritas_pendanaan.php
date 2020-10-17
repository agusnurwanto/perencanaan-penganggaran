<!DOCTYPE html>
<html>
	<head>
		<title>
			Program Pembangunan Prioritas dan Pendanaan
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
						<?php echo strtoupper('Indikasi Program Pembangunan Prioritas dan Pendanaan  Berdasarkan Urusan') ; ?>
					</h4>
					<h4>
						TAHUN <?php //echo get_userdata('year')?>
					</h4>
				</td>
				</th>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border" rowspan="3">
						KODE
					</th>
					<th class="border" rowspan="3">
						MISI / TUJUAN / SASARAN / INDIKATOR
					</th>
					<th class="border" rowspan="3">
						SATUAN
					</th>
					<th class="border" colspan="10">
						TARGET KINERJA PROGRAM DAN KERANGKA PENDANAAN
					</th>
				</tr>
				<tr>
					<?php
					for ($x = $results['visi']->tahun_awal; $x <= $results['visi']->tahun_akhir; $x++) {
					  echo '
						<th class="border" colspan="2">
							' . $x . '
						</th>
						';
					}
					?>
				</tr>
				<tr>
					<th class="border text-sm">
						Target
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						Target
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						Target
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						Target
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						Target
					</th>
					<th class="border text-sm">
						Rp
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_misi								= 0;
					$id_unit								= 0;
					$id_tujuan								= 0;
					foreach($results['data'] as $key => $val)
					{
						if( $val->id_misi != $id_misi)
						{
							echo '
								<tr>
									<td class="border">
										<b>' . $val->kode_misi . '</b>
									</td>
									<td class="border">
										<b>' . $val->misi . '</b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
								</tr>
							';
						}
						if( $val->id_tujuan != $id_tujuan)
						{
							echo '
								<tr>
									<td class="border">
										<b>' . $val->kode_misi . '.' . sprintf('%02d', $val->kode_tujuan) . '</b>
									</td>
									<td class="border" style="padding-left:10px">
										<b>' . $val->tujuan . '</b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
									<td class="border text-right">
										<b></b>
									</td>
								</tr>
							';
						}
							echo '
								<tr>
									<td class="border">										
										' . $val->kode_misi . '.' . sprintf('%02d', $val->kode_tujuan) . '
									</td>
									<td class="border" style="padding-left:15px">
										' . $val->sasaran . '
									</td>
									<td class="border text-left">
										' . $val->satuan . '
									</td>
									<td class="border text-right">
										' . number_format($val->tahun_1) . '
									</td>
									<td class="border text-right">
										
									</td>
									<td class="border text-right">
										' . number_format($val->tahun_2) . '
									</td>
									<td class="border text-right">
										
									</td>
									<td class="border text-right">
										' . number_format($val->tahun_3) . '
									</td>
									<td class="border text-right">
																			
									</td>
									<td class="border text-right">
										' . number_format($val->tahun_4) . '
									</td>
									<td class="border text-right">
																			
									</td>
									<td class="border text-right">
										' . number_format($val->tahun_5) . '
									</td>
									<td class="border text-right">
																			
									</td>
								</tr>
							';
						//}
						$id_misi								= $val->id_misi;
						$id_tujuan								= $val->id_tujuan;
					}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="border text-center">
					<b>JUMLAH</b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($jumlah_usulan); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($nilai_usulan); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($jumlah_diterima); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($nilai_diterima); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($jumlah_ditolak); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($nilai_ditolak); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($jumlah_kelurahan); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($usulan_kelurahan); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($usulan_kelurahan); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($usulan_kelurahan); ?></b>
				</td>
				<td class="border text-right">
					<b><?php //echo number_format($usulan_kelurahan); ?></b>
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
					<td class="text-muted text-sm text-right">
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>