<!DOCTYPE html>
<html>
	<head>
		<title>
			Rekapitulasi Kemajuan Kegiatan
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		<style type="text/css">
			@page
			{
				footer: html_footer /* !!! apply only when the htmlpagefooter is sets !!! */
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
			.divider
			{
				display: block;
				border-top: 3px solid #000;
				border-bottom: 1px solid #000;
				padding: 2px;
				margin-bottom: 15px
			}
			.text-sm
			{
				font-size: 12px
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
				font-weight: bold
			}
			td
			{
				vertical-align: top
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
				padding: 0;
				border: 0
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
		<table>
			<tr>
				<td width="80">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
				</td>
				<td align="center">
					<h4>
						REKAPITULASI KEMAJUAN KEGIATAN BELANJA LANGSUNG
						<br />
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
						<br />
						Periode <?php echo date_indo($this->input->get('periode_awal')) . ' s/d ' . date_indo($this->input->get('periode_akhir')); ?>
					</h4>
				</td>
			</tr>
		</table>
		
		<div class="divider"></div>
		
		<table class="table">
			<thead>
				<tr>
					<th rowspan="3" class="border text-sm">
						KODE						
					</th>
					<th rowspan="3" class="border text-sm">
						SKPD
					</th>
					<th rowspan="3" class="border text-sm">
						Anggaran
						<br />
						(Rp)
					</th>
					<th colspan="2" class="border text-sm">
						Target <?php //echo ucfirst($triwulan); ?>
					</th>
					<th colspan="3" class="border text-sm">
						Realisasi <?php //echo ucfirst($triwulan); ?>
					</th>
					<th rowspan="3" class="border text-sm">
						Permasalahan
					</th>
					<th rowspan="3" class="border text-sm">
						Rencana
						<br />
						Tindak Lanjut
					</th>
				</tr>
				<tr>
					<th rowspan="2" class="border text-sm">
						Fisik
						<br />
						(%)
					</th>
					<th rowspan="2" class="border text-sm">
						Keuangan
						<br />
						(Rp)
					</th>
					<th rowspan="2" class="border text-sm">
						Fisik
						<br />
						(%)
					</th>
					<th colspan="2" class="border text-sm">
						Keuangan
					</th>
				</tr>
				<tr>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						%
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total_anggaran				= 0;
					$total_rencana_fisik		= 0;
					$total_rencana_keuangan		= 0;
					$total_realisasi_fisik		= 0;
					$total_realisasi_keuangan	= 0;
					foreach($results['data'] as $key => $val)
					{
						echo '
							<tr>
								<td class="border text-sm">										
									' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '.' . sprintf('%02d', $val->kd_unit) . '
								</td>
								<td class="border text-sm" style="padding-left:15px">
									' . $val->nm_unit . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->anggaran) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_rencana_fisik, 2) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->target_keuangan) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_realisasi_fisik, 2) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_realisasi_uang) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_realisasi_uang / $val->anggaran * 100, 2) . '
								</td>
								<td class="border text-sm" align="right">
									
								</td>
								<td class="border text-sm" align="right">
									
								</td>
							</tr>
						';
						$total_anggaran				+= $val->anggaran;
						$total_rencana_fisik		+= $val->nilai_rencana_fisik;
						$total_rencana_keuangan		+= $val->target_keuangan;
						$total_realisasi_fisik		+= $val->nilai_realisasi_fisik;
						$total_realisasi_keuangan	+= $val->nilai_realisasi_uang;
						/*$total_perubahan			+= $val->pagu_perubahan;
						$jumlah_kegiatan++;*/
					}
				?>
				<tr>
					<td colspan="2" class="border text-sm text-center">
						<b>TOTAL</b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_anggaran); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php //echo number_format_indo(($total_rencana_fisik / ($jumlah_kegiatan > 0 ? $jumlah_kegiatan : 1)), 2); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_rencana_keuangan); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php 
							//$jumlah_total_realisasi_fisik	= $total_realisasi_fisik / ($jumlah_kegiatan > 0 ? $jumlah_kegiatan : 1);
							//echo number_format_indo($jumlah_total_realisasi_fisik, 2); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_realisasi_keuangan); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo(($total_realisasi_keuangan / $total_anggaran * 100), 2); ?></b>
					</td>
					<td colspan="2" class="border text-sm">
						&nbsp;
					</td>
				</tr>
			</tbody>
		</table>
		<htmlpagefooter name="footer">
			<table class="table">
				<tfoot>
					<tr>
						<td class="text-sm text-muted">
							<i>
								<?php echo phrase('document_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' ' . date('F d Y, H:i:s'); ?>
							</i>
						</td>
						<td class="text-sm text-muted text-right print">
							<?php echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</htmlpagefooter>
	</body>
</html>