<!DOCTYPE html>
<html>
	<head>
		<title>
			Rekapitulasi Renja per SKPD
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
				<td align="center" width="100%" colspan="13">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
					<?php
						if ($this->input->get('status') == 1)
						{
							echo "DRAFT";
						}
					?>
						REKAPITULASI RENCANA KERJA PER SKPD
					</h4>
					<h4>
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="5%" rowspan="2">
						KODE
					</th>
					<th class="bordered" width="21%" rowspan="2">
						SKPD
					</th>
					<th class="bordered" width="15%" colspan="3">
						JUMLAH PROGRAM
					</th>
					<th class="bordered" width="15%" colspan="3">
						JUMLAH KEGIATAN
					</th>
					<th class="bordered" width="22%" colspan="3">
						PLAFON
					</th>
					<th class="bordered" width="22%" colspan="3">
						PRA RKA
					</th>
				</tr>
				<tr>
					<th class="bordered">
						BLPU
					</th>
					<th class="bordered">
						BLU
					</th>
					<th class="bordered">
						TOTAL
					</th>
					<th class="bordered">
						BLPU
					</th>
					<th class="bordered">
						BLU
					</th>
					<th class="bordered">
						TOTAL
					</th>
					<th class="bordered">
						BLPU
					</th>
					<th class="bordered">
						BLU
					</th>
					<th class="bordered">
						TOTAL
					</th>
					<th class="bordered">
						BLPU
					</th>
					<th class="bordered">
						BLU
					</th>
					<th class="bordered">
						TOTAL
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total_program_blpu									= 0;
					$total_program_blu									= 0;
					$total_kegiatan_blpu								= 0;
					$total_kegiatan_blu									= 0;
					$total_plafon_blpu									= 0;
					$total_plafon_blu									= 0;
					$total_pra_rka_blpu									= 0;
					$total_pra_rka_blu									= 0;
					foreach($results['data'] as $key => $val)
					{
						$total_program_blpu					+= $val['jumlah_program_blpu'];
						$total_program_blu					+= $val['jumlah_program_blu'];
						$total_kegiatan_blpu				+= $val['jumlah_kegiatan_skpd_blpu'];
						$total_kegiatan_blu					+= $val['jumlah_kegiatan_skpd_blu'];
						$total_plafon_blpu					+= $val['plafon_anggaran_skpd_blpu'];
						$total_plafon_blu					+= $val['plafon_anggaran_skpd_blu'];
						$total_pra_rka_blpu					+= $val['pra_rka_blpu'];
						$total_pra_rka_blu					+= $val['pra_rka_blu'];
						echo '
							<tr>
								<td class="bordered" align="center">										
									' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '
								</td>
								<td class="bordered" style="padding-left:5px">
									' . $val['nm_unit'] . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['jumlah_program_blpu']) . '									
								</td>
								<td class="bordered" align="right">
									' . number_format($val['jumlah_program_blu']) . '									
								</td>
								<td class="bordered" align="right">
									' . number_format($val['jumlah_program_blpu'] + $val['jumlah_program_blu']) . '									
								</td>
								<td class="bordered" align="right">
									' . number_format($val['jumlah_kegiatan_skpd_blpu']) . '									
								</td>
								<td class="bordered" align="right">
									' . number_format($val['jumlah_kegiatan_skpd_blu']) . '									
								</td>
								<td class="bordered" align="right">
									' . number_format($val['jumlah_kegiatan_skpd_blpu'] + $val['jumlah_kegiatan_skpd_blu']) . '									
								</td>
								<td class="bordered" align="right">
									' . number_format($val['plafon_anggaran_skpd_blpu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['plafon_anggaran_skpd_blu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['plafon_anggaran_skpd_blpu'] + $val['plafon_anggaran_skpd_blu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['pra_rka_blpu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['pra_rka_blu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['pra_rka_blpu'] + $val['pra_rka_blu']) . '
								</td>
							</tr>
						';
					}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="bordered" align="center">
					<b>
						JUMLAH
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_program_blpu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_program_blu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_program_blpu + $total_program_blu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_kegiatan_blpu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_kegiatan_blu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_kegiatan_blpu + $total_kegiatan_blu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_plafon_blpu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_plafon_blu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_plafon_blpu + $total_plafon_blu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_pra_rka_blpu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_pra_rka_blu); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_pra_rka_blpu + $total_pra_rka_blu); ?>
					</b>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm" colspan="2">
						<i>
						<?php 
							if ($this->input->get('status') == 1)
							{
								echo "Draft";
							}
						?>
							Rekapitulasi Rencana Kerja Tahun <?php echo get_userdata('year'); ?>
							<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
						</i>
					</td>
					<td class="text-muted text-sm" align="right" colspan="4">
						<?php echo phrase('page') . ' {PAGENO} dari {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>