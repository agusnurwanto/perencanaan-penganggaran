<!DOCTYPE html>
<html>
	<head>
		<title>
			Perbandingan Plafon dengan Anggaran per Kegiatan
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
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						PERBANDINGAN PAGU PLAFON DENGAN ANGGARAN MENURUT KEGIATAN
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
					<th class="bordered">
						KODE
					</th>
					<th class="bordered">
						URUSAN PEMERINTAHAN DAERAH, ORGANISASI,
						<br />
						PROGRAM DAN KEGIATAN
					</th>
					<th class="bordered">
						PLAFON KEGIATAN
					</th>
					<th class="bordered">
						ANGGARAN
					</th>
					<th class="bordered">
						SELISIH
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_urusan						= 0;
					$id_bidang						= 0;
					$id_program						= 0;
					$id_kegiatan					= 0;
					$jumlah_anggaran				= 0;
					$total_plafon					= 0;
					$total_anggaran					= 0;
					$total_selisih					= 0;
					foreach($results['data'] as $key => $val)
					{						
						if($val['id_urusan'] != $id_urusan)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>' . $val['kode_urusan'] . '</b>
									</td>
									<td class="bordered">
										<b>' . $val['nama_urusan'] . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['jumlah_urusan']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['total_urusan']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['jumlah_urusan'] - $val['total_urusan']) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_bidang'] != $id_bidang)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>' . $val['kode_urusan'] . '.' . $val['kode_bidang'] . '</b>
									</td>
									<td style="padding-left:10px" class="bordered">
										<b>' . $val['nama_bidang'] . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['jumlah_bidang']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['total_bidang']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['jumlah_bidang'] - $val['total_bidang']) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_program'] != $id_program)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>' . $val['kode_urusan'] . '.' . $val['kode_bidang'] . '.' . $val['kode_program'] . '</b>
									</td>
									<td style="padding-left:15px" class="bordered">
										<b>' . $val['nama_program'] . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['jumlah_program']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['total_program']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['jumlah_program'] - $val['total_program']) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_kegiatan'] != $id_kegiatan)
						{
							$selisih					= $val['pagu'] - $val['jumlah_anggaran'];
							echo '
								<tr>
									<td class="bordered">
										' . $val['kode_urusan'] . '.' . $val['kode_bidang'] . '.' . $val['kode_program'] . '.' . $val['kode_kegiatan'] . '
									</td>
									<td style="padding-left:20px" class="bordered">
										' . $val['nama_kegiatan'] . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['pagu']) . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['jumlah_anggaran']) . '
									</td>
									<td class="bordered text-right">
										' . number_format($selisih) . '
									</td>
								</tr>
							';
						}
						$id_urusan					= $val['id_urusan'];
						$id_bidang					= $val['id_bidang'];
						$id_program					= $val['id_program'];
						$id_kegiatan				= $val['id_kegiatan'];
						$total_plafon				+= $val['pagu'];
						$total_anggaran				+= $val['jumlah_anggaran'];
						$total_selisih				+= $selisih;
					}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="bordered text-center">
					<b>JUMLAH</b>
				</td>
				<td class="bordered text-right">
					<b><?php echo number_format($total_plafon); ?></b>
				</td>
				<td class="bordered text-right">
					<b><?php echo number_format($total_anggaran); ?></b>
				</td>
				<td class="bordered text-right">
					<b><?php echo number_format($total_selisih); ?></b>
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