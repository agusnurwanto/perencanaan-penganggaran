<!DOCTYPE html>
<html>
	<head>
		<title>
			Rekapitulasi per SKPD
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
						REKAPITULASI PERENCANAAN PER SKPD
					</h4>
					<h4>
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
					</h4>
				</th>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border" width="5%" rowspan="2">
						KODE
					</th>
					<th class="border" width="21%" rowspan="2">
						SKPD
					</th>
					<th class="border" width="15%" colspan="3">
						JUMLAH PROGRAM
					</th>
					<th class="border" width="15%" colspan="3">
						JUMLAH SUB KEGIATAN
					</th>
					<th class="border" width="22%" colspan="3">
						PLAFON
					</th>
					<th class="border" width="22%" colspan="3">
						PRA RKA
					</th>
				</tr>
				<tr>
					<th class="border">
						BLPU
					</th>
					<th class="border">
						BLU
					</th>
					<th class="border">
						TOTAL
					</th>
					<th class="border">
						BLPU
					</th>
					<th class="border">
						BLU
					</th>
					<th class="border">
						TOTAL
					</th>
					<th class="border">
						BLPU
					</th>
					<th class="border">
						BLU
					</th>
					<th class="border">
						TOTAL
					</th>
					<th class="border">
						BLPU
					</th>
					<th class="border">
						BLU
					</th>
					<th class="border">
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
						$total_program_blpu					+= $val->jumlah_program_blpu;
						$total_program_blu					+= $val->jumlah_program_blu;
						$total_kegiatan_blpu				+= $val->jumlah_sub_kegiatan_skpd_blpu;
						$total_kegiatan_blu					+= $val->jumlah_sub_kegiatan_skpd_blu;
						$total_plafon_blpu					+= $val->plafon_anggaran_skpd_blpu;
						$total_plafon_blu					+= $val->plafon_anggaran_skpd_blu;
						$total_pra_rka_blpu					+= $val->pra_rka_blpu;
						$total_pra_rka_blu					+= $val->pra_rka_blu;
						echo '
							<tr>
								<td class="border" align="center">										
									' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '.' . sprintf('%02d', $val->kd_unit) . '
								</td>
								<td class="border" style="padding-left:5px">
									' . $val->nm_unit . '
								</td>
								<td class="border" align="right">
									' . number_format($val->jumlah_program_blpu) . '									
								</td>
								<td class="border" align="right">
									' . number_format($val->jumlah_program_blu) . '									
								</td>
								<td class="border" align="right">
									' . number_format($val->jumlah_program_blpu + $val->jumlah_program_blu) . '									
								</td>
								<td class="border" align="right">
									' . number_format($val->jumlah_sub_kegiatan_skpd_blpu) . '									
								</td>
								<td class="border" align="right">
									' . number_format($val->jumlah_sub_kegiatan_skpd_blu) . '									
								</td>
								<td class="border" align="right">
									' . number_format($val->jumlah_sub_kegiatan_skpd_blpu + $val->jumlah_sub_kegiatan_skpd_blu) . '									
								</td>
								<td class="border" align="right">
									' . number_format($val->plafon_anggaran_skpd_blpu) . '
								</td>
								<td class="border" align="right">
									' . number_format($val->plafon_anggaran_skpd_blu) . '
								</td>
								<td class="border" align="right">
									' . number_format($val->plafon_anggaran_skpd_blpu + $val->plafon_anggaran_skpd_blu) . '
								</td>
								<td class="border" align="right">
									' . number_format($val->pra_rka_blpu) . '
								</td>
								<td class="border" align="right">
									' . number_format($val->pra_rka_blu) . '
								</td>
								<td class="border" align="right">
									' . number_format($val->pra_rka_blpu + $val->pra_rka_blu) . '
								</td>
							</tr>
						';
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
						<?php echo number_format($total_program_blpu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_program_blu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_program_blpu + $total_program_blu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_kegiatan_blpu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_kegiatan_blu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_kegiatan_blpu + $total_kegiatan_blu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_plafon_blpu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_plafon_blu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_plafon_blpu + $total_plafon_blu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_pra_rka_blpu); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total_pra_rka_blu); ?>
					</b>
				</td>
				<td class="border" align="right">
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
							Rekapitulasi Rencana Kerja Awal Tahun <?php echo get_userdata('year'); ?>
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