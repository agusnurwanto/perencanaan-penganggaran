<!DOCTYPE html>
<html>
	<head>
		<title>
			Target Realisasi Urusan
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
	<?php
		if($this->input->get('triwulan') == 1)
		{
			$triwulan = "triwulan I";
		}
		elseif($this->input->get('triwulan') == 2)
		{
			$triwulan = "triwulan II";
		}
		elseif($this->input->get('triwulan') == 3)
		{
			$triwulan = "triwulan III";
		}
		else
		{
			$triwulan = "triwulan IV";
		}
	?>
	<body>
		<table>
			<tr>
				<td width="80">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
				</td>
				<td align="center">
					<h4>
						LAPORAN TARGET REALISASI URUSAN
						<br />
						<?php echo strtoupper($nama_pemda); ?>
						<br />
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?> <?php echo strtoupper($triwulan); ?>
					</h4>
				</td>
			</tr>
		</table>
		
		<div class="divider"></div>
		
		<table class="table">
			<thead>
				<tr>
					<th class="border text-sm">
						Kode
					</th>
					<th class="border text-sm">
						Urusan
					</th>
					<th class="border text-sm">
						Anggaran
					</th>
					<th class="border text-sm">
						Rencana
					</th>
					<th class="border text-sm">
						Realisasi Anggaran
					</th>
					<th class="border text-sm">
						Persentase Anggaran
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$total_pagu					= 0;
					$total_rencana				= 0;
					$total_realisasi			= 0;
					foreach($results['data'] as $key => $val)
					{
						echo '
							<tr>
								<td class="border text-sm">										
									' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '
								</td>
								<td class="border text-sm" style="padding-left:5px">
									' . $val->nm_bidang . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->pagu) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_rencana_uang) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_realisasi_uang) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_realisasi_uang / ($val->pagu == 0 ? 1 : $val->pagu) * 100, 2) . '
								</td>
							</tr>
						';
						$total_pagu				+= $val->pagu;
						$total_rencana			+= $val->nilai_rencana_uang;
						$total_realisasi		+= $val->nilai_realisasi_uang;
					}
				?>
				<tr>
					<td class="border text-center text-sm" colspan="2">
						<b>TOTAL</b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_pagu); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_rencana); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_realisasi); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_realisasi / ($total_pagu == 0 ? 1 : $total_pagu) * 100, 2); ?></b>
					</td>
				</tr
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