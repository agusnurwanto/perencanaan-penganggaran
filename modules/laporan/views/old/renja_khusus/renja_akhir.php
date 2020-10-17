<!DOCTYPE html>
<html>
	<head>
		<title>
			Rencana Kerja Awal SKPD
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
						KOMPILASI PROGRAM DAN PAGU INDIKATIF TIAP PERANGKAT DAERAH
					</h4>
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
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
					<th class="bordered" rowspan="2">
						KODE
					</th>
					<th class="bordered" rowspan="2">
						SKPD / PROGRAM / KEGIATAN
					</th>
					<th class="bordered" colspan="2">
						KINERJA
					</th>
					<th class="bordered" rowspan="2">
						PAGU INDIKATIF
					</th>
				</tr>
				<tr>
					<th class="bordered">
						Indikator
					</th>
					<th class="bordered">
						Target
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_unit								= 0;
					$id_program								= 0;
					/*$maksimal_usulan_kelurahan				= 0;
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
					$jumlah_kecamatan						= 0;*/
					foreach($results['data'] as $key => $val)
					{
						/*$maksimal_usulan_kelurahan			= ($val['jumlah_rw'] * 4 * 75 / 100) + 20;
						$maksimal_usulan_kelurahan_kecamatan = ($val['jumlah_rw_kecamatan'] * 4 * 75 / 100) + ($val['jumlah_kelurahan_sekecamatan'] * 20);
						$total_maksimal_usulan_kelurahan	+= $maksimal_usulan_kelurahan;
						$nilai_usulan						+= $val['kelurahan_nilai_usulan_kelurahan'];
						$nilai_diterima						+= $val['kelurahan_nilai_diterima_kecamatan'];
						$nilai_ditolak						+= $val['kelurahan_nilai_ditolak_kecamatan'];
						$nilai_usulan_kecamatan				+= $val['kelurahan_nilai_usulan_kecamatan'];
						$jumlah_usulan						+= $val['kelurahan_jumlah_usulan_kelurahan'];
						$jumlah_diterima					+= $val['kelurahan_jumlah_diterima_kecamatan'];
						$jumlah_ditolak						+= $val['kelurahan_jumlah_ditolak_kecamatan'];
						$jumlah_kecamatan					+= $val['kelurahan_jumlah_usulan_kecamatan'];*/
						if($val['id_unit'] != $id_unit)
						{
							//$total_maksimal_usulan_kecamatan	+= $maksimal_usulan_kecamatan;
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '
										</b>
									</td>
									<td class="bordered">
										<b>
											' . $val['nm_unit'] . '
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
											' . number_format($val['pagu_unit']) . '											
										</b>
									</td>
								</tr>
							';
						}
						if($val['id_program'] != $id_program)
						{
							//$total_maksimal_usulan_kecamatan	+= $maksimal_usulan_kecamatan;
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_program']) . '
										</b>
									</td>
									<td class="bordered" style="padding-left:10px">
										<b>
											' . $val['nm_program'] . '
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
											' . number_format($val['pagu_program']) . '											
										</b>
									</td>
								</tr>
							';
						}
						if(isset($val['id_kegiatan']))
						{
							echo '
								<tr>
									<td class="bordered">										
										' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_program']) . '.' . sprintf('%02d', $val['kd_keg']) . '
									</td>
									<td class="bordered" style="padding-left:15px">
										' . $val['kegiatan'] . '
									</td>
									<td class="bordered" style="padding-left:15px">
										' . $val['tolak_ukur'] . '
									</td>
									<td class="bordered" style="padding-left:15px">
										' . $val['target'] . ' ' . $val['satuan'] . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['pagu_kegiatan']) . '	
									</td>
								</tr>
							';
						}
						$id_unit									= $val['id_unit'];
						$id_program									= $val['id_program'];
					}
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
						<?php //echo number_format($total_maksimal_usulan_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($jumlah_usulan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($total_maksimal_usulan_kelurahan - $jumlah_usulan); ?>
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
					<td class="text-muted text-sm text-right">
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>