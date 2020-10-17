<!DOCTYPE html>
<html>
	<head>
		<title>
			Daftar Rencana Umum Pengadaan
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
						DAFTAR RENCANA UMUM PENGADAAN
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
					<?php echo $results['header']->kd_urusan; ?>
				</td>
				<td>
					<?php echo $results['header']->nm_urusan; ?>
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
					<?php echo $results['header']->kd_urusan . '.' . sprintf('%02d', $results['header']->kd_bidang); ?>
				</td>
				<td>
					<?php echo $results['header']->nm_bidang; ?>
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
					<?php echo $results['header']->kd_urusan . '.' . sprintf('%02d', $results['header']->kd_bidang) . '.' . sprintf('%02d', $results['header']->kd_unit); ?>
				</td>
				<td>
					<?php echo $results['header']->nm_unit; ?>
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
						PLAFON
					</th>
					<th class="bordered" width="10%">
						NILAI
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$kd_urusan						= 0;
					$kd_bidang						= 0;
					$kd_unit						= 0;
					$kd_sub							= 0;
					$kd_prog						= 0;
					$id_prog						= 0;
					$kd_keg							= 0;
					$total_plafon					= 0;
					$total_pekerjaan				= 0;
					foreach($results['rup'] as $key => $val)
					{
						if($val->kd_urusan != $kd_urusan || $val->kd_bidang != $kd_bidang || $val->kd_unit != $kd_unit || $val->kd_sub != $kd_sub || $val->kd_prog != $kd_prog || $val->id_prog != $id_prog || $val->kd_keg != $kd_keg)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>' . sprintf('%02d', $val->kd_prog) . '.' . sprintf('%02d', $val->kd_keg) . '</b>
									</td>
									<td style="padding-left:5px" class="bordered">
										<b>' . $val->kegiatan . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->pagu) . '</b>
									</td>
									<td class="bordered" align="right">
										<b>' . number_format_indo($val->nilai_pekerjaan_kegiatan) . '</b>
									</td>
								</tr>
							';
							$total_plafon					+= $val->pagu;
						}
						if(isset($val->no))
						{
							echo '	
								<tr>
									<td class="bordered">
										' . sprintf('%02d', $val->kd_prog) . '.' . sprintf('%02d', $val->kd_keg) . '.' . $val->no . '
									</td>
									<td style="padding-left:15px" class="bordered">
										' . $val->pekerjaan . '
									</td>
									<td class="bordered" align="right">
										
									</td>
									<td class="bordered" align="right">
										' . number_format_indo($val->nilai_pekerjaan) . '
									</td>
								</tr>
							';
							$total_pekerjaan				+= $val->nilai_pekerjaan;
						}
						$kd_urusan						= $val->kd_urusan;
						$kd_bidang						= $val->kd_bidang;
						$kd_unit						= $val->kd_unit;
						$kd_sub							= $val->kd_sub;
						$kd_prog						= $val->kd_prog;
						$id_prog						= $val->id_prog;
						$kd_keg							= $val->kd_keg;
					}
				?>
			</tbody>
				<tr>
					<td colspan="2" class="bordered text-center">
						<b>JUMLAH</b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format_indo($total_plafon); ?></b>
					</td>
					<td class="bordered" align="right">
						<b><?php echo number_format_indo($total_pekerjaan); ?></b>
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
					<?php //echo $nama_daerah; ?>, <?php //echo ($results['tanggal_anggaran_kas'] ? date_indo($results['tanggal_anggaran_kas']) : null); ?>
					<br />
					<b><?php //echo ($results['kegiatan']->jabatan_ppk_skpd ? $results['kegiatan']->jabatan_ppk_skpd : null); ?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php //echo ($results['kegiatan']->nama_ppk_skpd ? $results['kegiatan']->nama_ppk_skpd : null); ?></b></u>
					<br />
					<?php //echo ($results['kegiatan']->nip_ppk_skpd ? 'NIP. ' . $results['kegiatan']->nip_ppk_skpd : null); ?>
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