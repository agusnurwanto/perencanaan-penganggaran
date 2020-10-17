<!DOCTYPE html>
<html>
	<head>
		<title>
			Anggaran Kas Per Bulan
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
						REKAPITULASI ALOKASI ANGGARAN PER BULAN
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
					<th class="bordered" width="8%">
						KODE
					</th>
					<th class="bordered" width="32%">
						ORGANISASI
					</th>
					<th class="bordered" width="10%">
						Pagu
					</th>
					<th class="bordered" width="10%">
						Januari
					</th>
					<th class="bordered" width="10%">
						Februari
					</th>
					<th class="bordered" width="10%">
						Maret
					</th>
					<th class="bordered" width="10%">
						April
					</th>
					<th class="bordered" width="10%">
						Mei
					</th>
					<th class="bordered" width="10%">
						Juni
					</th>
					<th class="bordered" width="10%">
						Juli
					</th>
					<th class="bordered" width="10%">
						Agustus
					</th>
					<th class="bordered" width="10%">
						September
					</th>
					<th class="bordered" width="10%">
						Oktober
					</th>
					<th class="bordered" width="10%">
						Nopember
					</th>
					<th class="bordered" width="10%">
						Desember
					</th>
					<th class="bordered" width="10%">
						Selisih
					</th>
				</tr>
			</thead>
				<?php
					$total_pagu						= 0;
					$total_jan						= 0;
					$total_feb						= 0;
					$total_mar						= 0;
					$total_apr						= 0;
					$total_mei						= 0;
					$total_jun						= 0;
					$total_jul						= 0;
					$total_agt						= 0;
					$total_sep						= 0;
					$total_okt						= 0;
					$total_nop						= 0;
					$total_des						= 0;
					$selisih						= 0;
					$total_selisih					= 0;
					foreach($results['data'] as $key => $val)
					{
						$selisih					= $val->plafon - $val->jan - $val->feb - $val->mar - $val->apr - $val->mei - $val->jun - $val->jul - $val->agt - $val->sep - $val->okt - $val->nop - $val->des;
						echo '	
								<td class="bordered text-sm">
									' . $val->kd_urusan . '.' . $val->kd_bidang . '.' . $val->kd_unit . '.' . $val->kd_sub . '
								</td>
								<td class="bordered text-sm">
									' . $val->nm_sub . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->plafon) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->jan) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->feb) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->mar) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->apr) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->mei) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->jun) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->jul) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->agt) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->sep) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->okt) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->nop) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($val->des) . '
								</td>
								<td class="bordered text-sm" align="right">
									' . number_format_indo($selisih) . '
								</td>
							</tr>
						';
						$total_pagu						+= $val->plafon;
						$total_jan						+= $val->jan;
						$total_feb						+= $val->feb;
						$total_mar						+= $val->mar;
						$total_apr						+= $val->apr;
						$total_mei						+= $val->mei;
						$total_jun						+= $val->jun;
						$total_jul						+= $val->jul;
						$total_agt						+= $val->agt;
						$total_sep						+= $val->sep;
						$total_okt						+= $val->okt;
						$total_nop						+= $val->nop;
						$total_des						+= $val->des;
						$total_selisih					+= $selisih;
					}
				?>
				<tr>
					<td colspan="2" class="bordered text-center">
						<b>JUMLAH</b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_pagu); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_jan); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_feb); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_mar); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_apr); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_mei); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_jun); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_jul); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_agt); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_sep); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_okt); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_nop); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
						<b><?php echo number_format_indo($total_des); ?></b>
					</td>
					<td class="bordered text-sm" align="right">
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
					<b><?php echo ($results['header']->jabatan_sekretaris_daerah ? $results['header']->jabatan_sekretaris_daerah : null); ?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo ($results['header']->nama_sekretaris_daerah ? $results['header']->nama_sekretaris_daerah : null); ?></b></u>
					<br />
					<?php echo ($results['header']->nip_sekretaris_daerah ? 'NIP. ' . $results['header']->nip_sekretaris_daerah : null); ?>
				</td>
			</tr>
		</table>
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
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