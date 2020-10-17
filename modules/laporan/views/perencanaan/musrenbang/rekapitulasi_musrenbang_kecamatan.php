<!DOCTYPE html>
<html>
	<head>
		<title>
			Musrenbang Kecamatan
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
				<th class="border no-border-left" align="center" width="100%" colspan="14">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						REKAPITULASI MUSRENBANG KECAMATAN
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year'); ?>
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
						WILAYAH
					</th>
					<th class="border" colspan="4">
						USULAN KELURAHAN
					</th>
					<th class="border" colspan="3">
						DITERIMA KECAMATAN
					</th>
					<th class="border" colspan="2">
						DITOLAK KECAMATAN
					</th>
					<th class="border" colspan="4">
						USULAN KECAMATAN
					</th>
				</tr>
				<tr>
					<th class="border text-sm">
						MAKS
					</th>
					<th class="border text-sm">
						USULAN
					</th>
					<th class="border text-sm">
						SELISIH
					</th>
					<th class="border text-sm">
						TOTAL
					</th>
					<th class="border text-sm">
						MAKS
					</th>
					<th class="border text-sm">
						DITERIMA
					</th>
					<th class="border text-sm">
						TOTAL
					</th>
					<th class="border text-sm">
						JUMLAH
					</th>
					<th class="border text-sm">
						TOTAL
					</th>
					<th class="border text-sm">
						MAKS
					</th>
					<th class="border text-sm">
						USULAN
					</th>
					<th class="border text-sm">
						JUMLAH
					</th>
					<th class="border text-sm">
						TOTAL
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id										= 0;
					$maksimal_usulan_kelurahan				= 0;
					$total_maksimal_usulan_kelurahan		= 0;
					$maksimal_usulan_kelurahan_kecamatan	= 0;
					$nilai_usulan							= 0;
					$nilai_diterima							= 0;
					$nilai_ditolak							= 0;
					$usulan_kecamatan						= 0;
					$jumlah_usulan							= 0;
					$jumlah_diterima						= 0;
					$jumlah_ditolak							= 0;
					$nilai_usulan_kecamatan					= 0;
					$maksimal_usulan_kecamatan				= 30;
					$total_maksimal_usulan_kecamatan		= 0;
					$jumlah_kecamatan						= 0;
					foreach($results['data'] as $key => $val)
					{
						$maksimal_usulan_kelurahan			= ($val['jumlah_rw'] * 4 * 75 / 100) + 20;
						$maksimal_usulan_kelurahan_kecamatan = ($val['jumlah_rw_kecamatan'] * 4 * 75 / 100) + ($val['jumlah_kelurahan_sekecamatan'] * 20);
						$total_maksimal_usulan_kelurahan	+= $maksimal_usulan_kelurahan;
						$nilai_usulan						+= $val['kelurahan_nilai_usulan_kelurahan'];
						$nilai_diterima						+= $val['kelurahan_nilai_diterima_kecamatan'];
						$nilai_ditolak						+= $val['kelurahan_nilai_ditolak_kecamatan'];
						$nilai_usulan_kecamatan				+= $val['kelurahan_nilai_usulan_kecamatan'];
						$jumlah_usulan						+= $val['kelurahan_jumlah_usulan_kelurahan'];
						$jumlah_diterima					+= $val['kelurahan_jumlah_diterima_kecamatan'];
						$jumlah_ditolak						+= $val['kelurahan_jumlah_ditolak_kecamatan'];
						$jumlah_kecamatan					+= $val['kelurahan_jumlah_usulan_kecamatan'];
						if($val['kode_kecamatan'] != $id)
						{							
							$total_maksimal_usulan_kecamatan	+= $maksimal_usulan_kecamatan;
							echo '
								<tr>
									<td class="border">
										<b>
											' . sprintf('%02d', $val['kode_kecamatan']) . '
										</b>
									</td>
									<td class="border">
										<b>
											' . $val['kecamatan'] . ' (' . $val['jumlah_rw_kecamatan'] . ' RW)
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($maksimal_usulan_kelurahan_kecamatan) . '											
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['kecamatan_jumlah_usulan_kelurahan']) . '											
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($maksimal_usulan_kelurahan_kecamatan - $val['kecamatan_jumlah_usulan_kelurahan']) . '											
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['kecamatan_nilai_usulan_kelurahan']) . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format(round($val['kecamatan_jumlah_usulan_kelurahan']/2)) . '											
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['kecamatan_jumlah_diterima_kecamatan']) . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['kecamatan_nilai_diterima_kecamatan']) . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['kecamatan_jumlah_ditolak_kecamatan']) . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['kecamatan_nilai_ditolak_kecamatan']) . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($maksimal_usulan_kecamatan) . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['kecamatan_jumlah_usulan_kecamatan']) . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($maksimal_usulan_kecamatan - $val['kecamatan_jumlah_usulan_kecamatan']) . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											' . number_format($val['kecamatan_nilai_usulan_kecamatan']) . '
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
										' . sprintf('%02d', $val['kode_kecamatan']) . '.' . sprintf('%02d', $val['kode_kelurahan']) . '
									</td>
									<td class="border" style="padding-left:15px">
										' . $val['nama_kelurahan'] . ' (' . $val['jumlah_rw'] . ' RW)
									</td>
									<td class="border" align="right">
										' . number_format($maksimal_usulan_kelurahan) . '									
									</td>
									<td class="border" align="right">
										' . number_format($val['kelurahan_jumlah_usulan_kelurahan']) . '									
									</td>
									<td class="border" align="right">
										' . number_format($maksimal_usulan_kelurahan - $val['kelurahan_jumlah_usulan_kelurahan']) . '
									</td>
									<td class="border" align="right">
										' . number_format($val['kelurahan_nilai_usulan_kelurahan']) . '
									</td>
									<td class="border" align="right">
																			
									</td>
									<td class="border" align="right">
										' . number_format($val['kelurahan_jumlah_diterima_kecamatan']) . '										
									</td>
									<td class="border" align="right">
										' . number_format($val['kelurahan_nilai_diterima_kecamatan']) . '										
									</td>
									<td class="border" align="right">
										' . number_format($val['kelurahan_jumlah_ditolak_kecamatan']) . '										
									</td>
									<td class="border" align="right">
										' . number_format($val['kelurahan_nilai_ditolak_kecamatan']) . '										
									</td>
									<td class="border" align="right">
																				
									</td>
									<td class="border" align="right">
										' . number_format($val['kelurahan_jumlah_usulan_kecamatan']) . '										
									</td>
									<td class="border" align="right">
																			
									</td>
									<td class="border" align="right">
										' . number_format($val['kelurahan_nilai_usulan_kecamatan']) . '										
									</td>
								</tr>
							';
						}
						$id									= $val['kode_kecamatan'];
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
						<?php echo number_format($total_maksimal_usulan_kelurahan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($jumlah_usulan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_maksimal_usulan_kelurahan - $jumlah_usulan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($nilai_usulan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format(round($jumlah_usulan/2)); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($jumlah_diterima); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($nilai_diterima); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($jumlah_ditolak); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($nilai_ditolak); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_maksimal_usulan_kecamatan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($jumlah_kecamatan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_maksimal_usulan_kecamatan - $jumlah_kecamatan); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($nilai_usulan_kecamatan); ?>
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