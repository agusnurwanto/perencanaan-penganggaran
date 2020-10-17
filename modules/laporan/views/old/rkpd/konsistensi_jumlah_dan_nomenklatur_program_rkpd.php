<!DOCTYPE html>
<html>
	<head>
		<title>
			Konsistensi Jumlah dan Nomenklatur Program RKPD
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
						KONSISTENSI JUMLAH DAN NOMENKLATUR PROGRAM RENCANA KERJA PEMERINTAH DAERAH (RKPD)
					</h4>
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : "") ; ?>
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year')?> DAN RPJMD
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="5%">
						KODE
					</th>
					<th class="bordered" width="20%">
						URUSAN / BIDANG
					</th>
					<th class="bordered" width="25%">
						PERANGKAT DAERAH
						<br />
						PENANGGUNG JAWAB
					</th>
					<th class="bordered" width="10%">
						JUMLAH
						<br />
						PROGRAM RPJMD
					</th>
					<th class="bordered" width="10%">
						JUMLAH PROGRAM
						<br />
						RKPD TAHUN <?php echo get_userdata('year')?>
					</th>
					<th class="bordered" width="10%">
						JUMLAH NAMA
						<br />
						PROGRAM KONSISTEN
					</th>
					<th class="bordered" width="10%">
						JUMLAH NAMA
						<br />
						PROGRAM INKONSISTEN
					</th>
					<th class="bordered" width="10%">
						PENJELASAN
						<br />
						INKONSISTEN
					</th>
				</tr>
				<tr>
					<th class="bordered">
						(1)
					</th>
					<th class="bordered">
						(2)
					</th>
					<th class="bordered">
						(3)
					</th>
					<th class="bordered">
						(4)
					</th>
					<th class="bordered">
						(5)
					</th>
					<th class="bordered">
						(6)
					</th>
					<th class="bordered">
						(7)
					</th>
					<th class="bordered">
						(8)
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_urusan								= 0;
					$id_bidang								= 0;
					$id_unit								= 0;
					foreach($results['data'] as $key => $val)
					{
						if( $val['id_urusan'] != $id_urusan)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_urusan'] . '
										</b>
									</td>
									<td class="bordered" style="padding-left:5px">
										<b>
											' . ucwords(strtolower($val['nama_urusan'])) . '
										</b>
									</td>
									<td class="bordered">
										<b>
										
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											' . number_format($val['jumlah_program_per_urusan']) . '
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											' . number_format($val['jumlah_kegiatan_per_urusan']) . '
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											
										</b>
									</td>
									<td class="bordered">
										<b>
										
										</b>
									</td>
									<td class="bordered">
										<b>
										
										</b>
									</td>
								</tr>
							';
						}
						if( $val['id_bidang'] != $id_bidang)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '
										</b>
									</td>
									<td class="bordered" style="padding-left:5px">
										<b>
											' . ucwords(strtolower($val['nama_bidang'])) . '
										</b>
									</td>
									<td class="bordered">
										<b>
										
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											' . number_format($val['jumlah_program_per_bidang']) . '
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											' . number_format($val['jumlah_kegiatan_per_bidang']) . '
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											
										</b>
									</td>
									<td class="bordered">
									</td>
									<td class="bordered">
										
									</td>
								</tr>
							';
						}
						if( $val['id_unit'] != $id_unit)
						{
							echo '
								<tr>
									<td class="bordered">
										' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '.' . sprintf('%02d', $val['kode_unit']) . '
									</td>
									<td class="bordered" style="padding-left:5px">
										
									</td>
									<td class="bordered">
										' . ucwords(strtolower($val['nama_unit'])) . '
									</td>
									<td class="bordered" align="right">
										' . number_format($val['jumlah_kegiatan_per_bidang']) . '
									</td>
									<td class="bordered" align="right">
										
									</td>
									<td class="bordered">
									</td>
									<td class="bordered">
										
									</td>
									<td class="bordered">
										
									</td>
								</tr>
							';
						}
						$id_urusan								= $val['id_urusan'];
						$id_bidang								= $val['id_bidang'];
						$id_unit								= $val['id_unit'];
					}
				?>
			</tbody>
			<!--
				<tr>
					<td colspan="2" class="bordered text-center">
						<b>
							JUMLAH
						</b>
					</td>
					<td class="bordered text-right">
						<b>
							<?php //echo number_format($jumlah_usulan); ?>
						</b>
					</td>
					<td class="bordered text-right">
						<b>
							<?php //echo number_format($nilai_usulan); ?>
						</b>
					</td>
					<td class="bordered text-right">
						<b>
							<?php //echo number_format($jumlah_diterima); ?>
						</b>
					</td>
				</tr>
			-->
		</table>
		<br />
		<br />
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							Kompilasi Program dan Pagu Indikatif Tiap Perangkat Daerah
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