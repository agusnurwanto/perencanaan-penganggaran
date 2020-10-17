<!DOCTYPE html>
<html>
	<head>
		<title>
			Anggaran Kas
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
						ALOKASI TRIWULAN
					</h4>
					<h4>
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<tr>
				<td width="180">
					Urusan Pemerintahan
				</td>
				<td width="1">
					:
				</td>
				<td width="100">
					<?php echo $results['kegiatan']->kd_urusan; ?>
				</td>
				<td>
					<?php echo $results['kegiatan']->nm_urusan; ?>
				</td>
			</tr>
			<tr>
				<td>
					Bidang Pemerintahan
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['kegiatan']->kd_urusan . '.' . sprintf('%02d', $results['kegiatan']->kd_bidang); ?>
				</td>
				<td>
					<?php echo $results['kegiatan']->nm_bidang; ?>
				</td>
			</tr>
			<tr>
				<td>
					Unit Organisasi
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['kegiatan']->kd_urusan . '.' . sprintf('%02d', $results['kegiatan']->kd_bidang) . '.' . sprintf('%02d', $results['kegiatan']->kd_unit); ?>
				</td>
				<td>
					<?php echo $results['kegiatan']->nm_unit; ?>
				</td>
			</tr>
			<tr>
				<td>
					Sub Unit Organisasi
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['kegiatan']->kd_urusan . '.' . sprintf('%02d', $results['kegiatan']->kd_bidang) . '.' . sprintf('%02d', $results['kegiatan']->kd_unit) . '.' . sprintf('%02d', $results['kegiatan']->kd_sub); ?>
				</td>
				<td>
					<?php echo $results['kegiatan']->nm_sub; ?>
				</td>
			</tr>
			<tr>
				<td>
					Program
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['kegiatan']->kd_urusan . '.' . sprintf('%02d', $results['kegiatan']->kd_bidang) . '.' . sprintf('%02d', $results['kegiatan']->kd_unit) . '.' . sprintf('%02d', $results['kegiatan']->kd_sub) . '.' . sprintf('%02d', $results['kegiatan']->kd_program); ?>
				</td>
				<td>
					<?php echo $results['kegiatan']->nm_program; ?>
				</td>
			</tr>
			<tr>
				<td>
					Kegiatan
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo $results['kegiatan']->kd_urusan . '.' . sprintf('%02d', $results['kegiatan']->kd_bidang) . '.' . sprintf('%02d', $results['kegiatan']->kd_unit) . '.' . sprintf('%02d', $results['kegiatan']->kd_sub) . '.' . sprintf('%02d', $results['kegiatan']->kd_program) . '.' . sprintf('%02d', $results['kegiatan']->kd_keg); ?>
				</td>
				<td>
					<?php echo $results['kegiatan']->kegiatan; ?>
				</td>
			</tr>
			<tr>
				<td>
					Pagu
				</td>
				<td>
					:
				</td>
				<td align="right">
					<?php echo number_format_indo($results['kegiatan']->pagu); ?>
				</td>
				<td>
					<?php echo spell_number($results['kegiatan']->pagu); ?> Rupiah
				</td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="8%">
						KODE
					</th>
					<th class="bordered" width="32%">
						URAIAN
					</th>
					<th class="bordered" width="10%">
						PAGU
					</th>
					<th class="bordered" width="10%">
						TRIWULAN I
					</th>
					<th class="bordered" width="10%">
						TRIWULAN II
					</th>
					<th class="bordered" width="10%">
						TRIWULAN III
					</th>
					<th class="bordered" width="10%">
						TRIWULAN IV
					</th>
					<th class="bordered" width="10%">
						SELISIH
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_rek_3						= 0;
					$id_rek_4						= 0;
					$total_rekening					= 0;
					$total_tw_1						= 0;
					$total_tw_2						= 0;
					$total_tw_3						= 0;
					$total_tw_4						= 0;
					$selisih_rek_3					= 0;
					$selisih_rek_4					= 0;
					$total_selisih					= 0;
					foreach($results['data'] as $key => $val)
					{
						if($val->id_rek_3 != $id_rek_3)
						{
							$selisih_rek_3				= $val->pagu_rek_3 - $val->rencana_rek_3_tw_1 - $val->rencana_rek_3_tw_2 - $val->rencana_rek_3_tw_3 - $val->rencana_rek_3_tw_4;
							echo '
								<tr>
									<td class="bordered">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '</b>
									</td>
									<td class="bordered">
										<b>' . $val->uraian_rek_3 . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->pagu_rek_3) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->rencana_rek_3_tw_1) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->rencana_rek_3_tw_2) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->rencana_rek_3_tw_3) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->rencana_rek_3_tw_4) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($selisih_rek_3) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_4 != $id_rek_4)
						{
							$selisih_rek_4				= $val->pagu_rek_4 - $val->rencana_rek_4_tw_1 - $val->rencana_rek_4_tw_2 - $val->rencana_rek_4_tw_3 - $val->rencana_rek_4_tw_4;
							echo '
								<tr>
									<td class="bordered">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . sprintf('%02d', $val->kd_rek_4) . '</b>
									</td>
									<td style="padding-left:8px" class="bordered">
										<b>' . $val->uraian_rek_4 . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->pagu_rek_4) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->rencana_rek_4_tw_1) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->rencana_rek_4_tw_2) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->rencana_rek_4_tw_3) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->rencana_rek_4_tw_4) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($selisih_rek_4) . '</b>
									</td>
								</tr>
							';
						}
						$selisih					= $val->pagu_rek_5 - $val->tw_1 - $val->tw_2 - $val->tw_3 - $val->tw_4;
						echo '	
								<td class="bordered">
									' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . sprintf('%02d', $val->kd_rek_4) . '.' . sprintf('%02d', $val->kd_rek_5) . '
								</td>
								<td style="padding-left:13px" class="bordered">
									' . $val->uraian_rek_5 . '
								</td>
								<td class="bordered" align="right">
									' . number_format_indo($val->pagu_rek_5) . '
								</td>
								<td class="bordered" align="right">
									' . number_format_indo($val->tw_1) . '
								</td>
								<td class="bordered" align="right">
									' . number_format_indo($val->tw_2) . '
								</td>
								<td class="bordered" align="right">
									' . number_format_indo($val->tw_3) . '
								</td>
								<td class="bordered" align="right">
									' . number_format_indo($val->tw_4) . '
								</td>
								<td class="bordered" align="right">
									' . number_format_indo($selisih) . '
								</td>
							</tr>
						';
						$id_rek_3						= $val->id_rek_3;
						$id_rek_4						= $val->id_rek_4;
						$total_rekening					+= $val->pagu_rek_5;
						$total_tw_1						+= $val->tw_1;
						$total_tw_2						+= $val->tw_2;
						$total_tw_3						+= $val->tw_3;
						$total_tw_4						+= $val->tw_4;
						$total_selisih					+= $selisih;
					}
				?>
			</tbody>
				<tr>
					<td colspan="2" class="bordered text-center">
						<b>JUMLAH</b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format_indo($total_rekening); ?></b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format_indo($total_tw_1); ?></b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format_indo($total_tw_2); ?></b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format_indo($total_tw_3); ?></b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format_indo($total_tw_4); ?></b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format_indo($total_selisih); ?></b>
					</td>
				</tr>
		</table>
		<br />
		<br />
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="text-center" width="50%">
					
					<br />
					<b><?php //echo $header['jabatan_kpa']; ?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php //echo $header['kpa']; ?></b></u>
					<br />
					<?php //echo $header['nip_kpa']; ?>
				</td>
				<td class="text-center" width="50%">
					<?php echo $nama_daerah; ?>, <?php echo ($results['tanggal_anggaran_kas'] ? date_indo($results['tanggal_anggaran_kas']) : null); ?>
					<br />
					<b><?php echo ($results['kegiatan']->jabatan_ppk_skpd ? $results['kegiatan']->jabatan_ppk_skpd : null); ?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo ($results['kegiatan']->nama_ppk_skpd ? $results['kegiatan']->nama_ppk_skpd : null); ?></b></u>
					<br />
					<?php echo ($results['kegiatan']->nip_ppk_skpd ? 'NIP. ' . $results['kegiatan']->nip_ppk_skpd : null); ?>
				</td>
			</tr>
		</table>
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							<?php echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') ?>
						</i>
					</td>
					<td class="text-muted text-sm text-right">
						<?php echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>