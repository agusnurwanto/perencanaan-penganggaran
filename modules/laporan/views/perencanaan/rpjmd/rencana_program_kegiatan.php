<!DOCTYPE html>
<html>
	<head>
		<title>
			Rencana Program, Kegiatan, Indikator Kinerja, Kelompok Sasaran, dan Pendanaan Indikatif 
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
						<?php echo strtoupper('Rencana Program, Kegiatan, Indikator Kinerja, Kelompok Sasaran, dan Pendanaan Indikatif ') ; ?>
					</h4>
					<h4>
						NAMA SKPD <?php //echo get_userdata('year')?>
					</h4>
					<h4>
						TAHUN 2018 - 2023 <?php //echo get_userdata('year')?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" rowspan="3">
						KODE
					</th>
					<th class="bordered" rowspan="3">
						URAIAN
					</th>
					<th class="bordered" rowspan="3">
						SATUAN
					</th>
					<th class="bordered" rowspan="3">
						KONDISI AWAL
					</th>
					<th class="bordered" colspan="10">
						TARGET KINERJA PROGRAM DAN KERANGKA PENDANAAN
					</th>
					<th class="bordered" rowspan="3">
						KONDISI AKHIR
					</th>
				</tr>
				<tr>
					<th class="bordered" colspan="2">
						2019
					</th>
					<th class="bordered" colspan="2">
						2020
					</th>
					<th class="bordered" colspan="2">
						2021
					</th>
					<th class="bordered" colspan="2">
						2022
					</th>
					<th class="bordered" colspan="2">
						2023
					</th>
				</tr>
				<tr>
					<th class="bordered">
						TARGET
					</th>
					<th class="bordered">
						RP
					</th>
					<th class="bordered">
						TARGET
					</th>
					<th class="bordered">
						RP
					</th>
					<th class="bordered">
						TARGET
					</th>
					<th class="bordered">
						RP
					</th>
					<th class="bordered">
						TARGET
					</th>
					<th class="bordered">
						RP
					</th>
					<th class="bordered">
						TARGET
					</th>
					<th class="bordered">
						RP
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_misi								= 0;
					$id_unit								= 0;
					$id_tujuan								= 0;
					//$nilai_ditolak							= 0;
					//$usulan_kelurahan						= 0;
					//$jumlah_usulan							= 0;
					//$jumlah_diterima						= 0;
					//$jumlah_ditolak							= 0;
					//$jumlah_kelurahan						= 0;
					foreach($results['data'] as $key => $val)
					{
						/*$nilai_usulan						+= $val['nilai_usulan'];
						$nilai_diterima						+= $val['nilai_diterima'];
						$nilai_ditolak						+= $val['nilai_ditolak'];
						$usulan_kelurahan					+= $val['usulan_kelurahan'];
						$jumlah_usulan						+= $val['jumlah_usulan'];
						$jumlah_diterima					+= $val['jumlah_diterima'];
						$jumlah_ditolak						+= $val['jumlah_ditolak'];
						$jumlah_kelurahan					+= $val['jumlah_kelurahan'];*/
						if( $val['id_misi'] != $id_misi)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_misi'] . '
										</b>
									</td>
									<td class="bordered">
										<b>
											' . $val['misi'] . '
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
											
										</b>
									</td>
									<td class="bordered text-right">
										<b>
											
										</b>
									</td>
								</tr>
							';
						}
						if( $val['id_unit'] != $id_unit)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_misi'] . '.' . sprintf('%02d', $val['kode_unit']) . '
										</b>
									</td>
									<td class="bordered" style="padding-left:5px">
										<b>
											' . $val['nama_unit'] . '
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
											
										</b>
									</td>
									<td class="bordered text-right">
										<b>
											
										</b>
									</td>
								</tr>
							';
						}
						if( $val['id_tujuan'] != $id_tujuan)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_misi'] . '.' . sprintf('%02d', $val['kode_unit']) . '.' . sprintf('%02d', $val['kode_tujuan']) . '
										</b>
									</td>
									<td class="bordered" style="padding-left:10px">
										<b>
											' . $val['tujuan'] . '
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
											
										</b>
									</td>
									<td class="bordered text-right">
										<b>
											
										</b>
									</td>
								</tr>
							';
						}
						//if(isset($val['kode_kelurahan']))
						//{
							echo '
								<tr>
									<td class="bordered">										
										' . $val['kode_misi'] . '.' . sprintf('%02d', $val['kode_unit']) . '.' . sprintf('%02d', $val['kode_tujuan']) . '
									</td>
									<td class="bordered" style="padding-left:15px">
										' . $val['sasaran'] . '
									</td>
									<td class="bordered text-left">
										' . $val['satuan'] . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['kondisi_awal']) . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['tahun_1']) . '
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered text-right">
										' . number_format($val['tahun_2']) . '
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered text-right">
										' . number_format($val['tahun_3']) . '
									</td>
									<td class="bordered text-right">
																			
									</td>
									<td class="bordered text-right">
										' . number_format($val['tahun_4']) . '
									</td>
									<td class="bordered text-right">
																			
									</td>
									<td class="bordered text-right">
										' . number_format($val['tahun_5']) . '
									</td>
									<td class="bordered text-right">
																			
									</td>
									<td class="bordered text-right">
										' . number_format($val['kondisi_akhir']) . '
									</td>
								</tr>
							';
						//}
						$id_misi								= $val['id_misi'];
						$id_unit								= $val['id_unit'];
						$id_tujuan								= $val['id_tujuan'];
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
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($nilai_diterima); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($jumlah_ditolak); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($nilai_ditolak); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($jumlah_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($usulan_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($usulan_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($usulan_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($usulan_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($usulan_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($usulan_kelurahan); ?>
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