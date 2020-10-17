<!DOCTYPE html>
<html>
	<head>
		<title>
			Kemajuan Kegiatan
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
						LAPORAN KEMAJUAN KEGIATAN
						<br />
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
						<br />
						<?php echo strtoupper($triwulan); ?>
						<br />
						<?php echo ($results['header']->nm_unit ? strtoupper($results['header']->nm_unit) : NULL) ?> 
					</h4>
				</td>
			</tr>
		</table>
		
		<div class="divider"></div>
		
		<table class="table">
			<thead>
				<tr>
					<th rowspan="3" class="border text-sm">
						Kode
						<br />
						Rekening
					</th>
					<th rowspan="3" class="border text-sm">
						Program/Kegiatan
					</th>
					<th rowspan="3" class="border text-sm">
						Pagu
						<br />
						(Rp)
					</th>
					<th colspan="2" class="border text-sm">
						Target s.d <?php echo ucfirst($triwulan); ?>
					</th>
					<th colspan="3" class="border text-sm">
						Realisasi s.d <?php echo ucfirst($triwulan); ?>
					</th>
					<th rowspan="3" class="border text-sm">
						Permasalahan
					</th>
					<th rowspan="3" class="border text-sm">
						Rencana Tindak Lanjut
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
					$total_murni				= 0;
					$total_perubahan			= 0;
					$total_rencana_fisik		= 0;
					$total_rencana_keuangan		= 0;
					$total_realisasi_fisik		= 0;
					$total_realisasi_keuangan	= 0;
					$jumlah_kegiatan			= 0;
					$id_prog					= 0;
					foreach($results['data'] as $key => $val)
					{
						if($id_prog != $val->id_prog)
						{
							echo '
								<tr>
									<td class="border text-sm">										
										<b>' . $val->kode_urusan . '.' . sprintf('%02d', $val->kode_bidang) . '.' . sprintf('%02d', $val->kode_unit) . '.' . sprintf('%02d', $val->kode_sub) . '.' . sprintf('%02d', $val->kode_prog) . '</b>
									</td>
									<td class="border text-sm" style="padding-left:5px">
										<b>' . $val->nm_program . '</b>
									</td>
									<td class="border text-sm" align="right">
										<b>' . number_format_indo($val->pagu_program) . '</b>
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
								</tr>
							';
						}
						echo '
							<tr>
								<td class="border text-sm">										
									' . $val->kode_urusan . '.' . sprintf('%02d', $val->kode_bidang) . '.' . sprintf('%02d', $val->kode_unit) . '.' . sprintf('%02d', $val->kode_sub) . '.' . sprintf('%02d', $val->kode_prog) . '.' . sprintf('%02d', $val->kode_keg) . '
								</td>
								<td class="border text-sm" style="padding-left:15px">
									' . $val->kegiatan . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->pagu) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_rencana_fisik, 2) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_rencana_uang) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_realisasi_fisik, 2) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_realisasi_uang) . '
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($val->nilai_realisasi_uang / ($val->pagu == 0 ? 1 : $val->pagu) *100 , 2) . '
								</td>
								<td class="border text-sm" align="right">
									
								</td>
								<td class="border text-sm" align="right">
									
								</td>
							</tr>
						';
						$id_prog					= $val->id_prog;
						$total_murni				+= $val->pagu;
						//$total_perubahan			+= $val->pagu_perubahan;
						$total_rencana_fisik		+= $val->nilai_rencana_fisik;
						$total_rencana_keuangan		+= $val->nilai_rencana_uang;
						$total_realisasi_fisik		+= $val->nilai_realisasi_fisik;
						$total_realisasi_keuangan	+= $val->nilai_realisasi_uang;
						$jumlah_kegiatan++;
					}
				?>
				<tr>
					<td colspan="2" class="border text-sm text-center">
						<b>TOTAL
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_murni); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo(($total_rencana_fisik / ($jumlah_kegiatan > 0 ? $jumlah_kegiatan : 1)), 2); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_rencana_keuangan); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php 
							$jumlah_total_realisasi_fisik	= $total_realisasi_fisik / ($jumlah_kegiatan > 0 ? $jumlah_kegiatan : 1);
							echo number_format_indo($jumlah_total_realisasi_fisik, 2); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo($total_realisasi_keuangan); ?></b>
					</td>
					<td class="border text-right text-sm">
						<b><?php echo number_format_indo(($total_realisasi_keuangan / ($total_murni == 0 ? 1 : $total_murni) * 100), 2); ?></b>
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