<!DOCTYPE html>
<html>
	<head>
		<title>
			<?php echo $title; ?>
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		
		<style type="text/css">
			@page
			{
				footer: html_footer; /* !!! apply only when the htmlpagefooter is sets !!! */
				sheet-size: 8.5in 13in;
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
		<table class="table">
			<thead>
				<tr>
					<th class="no-padding" width="45%">
					</th>
					<th class="no-padding" align="right" width="15%">
						Lampiran III : &nbsp;
					</th>
					<th class="no-padding text-left" colspan="2">
						<?php
							if(empty($results['jenis_anggaran']) || $results['jenis_anggaran']->kode == 8)
							{
								echo 'Rancangan Peraturan Kepala Daerah';
							}
							else
							{
								echo 'Peraturan Kepala Daerah';
							}
						?>
					</th>
				</tr>
				<tr>
					<th class="no-padding" colspan="2">
					</th>
					<th class="no-padding text-left" width="10%">
						Nomor
					</th>
					<th class="no-padding text-left">
						: <?php echo (isset($results['jenis_anggaran']->nomor_perkada) ? $results['jenis_anggaran']->nomor_perkada : NULL); ?>
					</th>
				</tr>
				<tr>
					<th class="no-padding" colspan="2">
					</th>
					<th class="no-padding text-left">
						Tanggal
					</th>
					<th class="no-padding text-left">
						: <?php echo (isset($results['jenis_anggaran']->tanggal_perkada) && $results['jenis_anggaran']->tanggal_perkada != '0000-00-00' ? date_indo($results['jenis_anggaran']->tanggal_perkada) : NULL); ?>
					</th>
				</tr>
				<tr>
					<th class="no-padding" colspan="2">
						&nbsp;
					</th>
					<th class="no-padding text-left">
						&nbsp;
					</th>
					<th class="no-padding text-left">
						&nbsp;
					</th>
				</tr>
			</thead>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th width="100" class="border no-border-right">
						<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
					</th>
					<th class="border no-border-left" align="center">
						<h4>
							<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
						</h4>
						<h4>
							DAFTAR NAMA CALON PENERIMA, ALAMAT DAN BESARAN
							<br />
							ALOKASI HIBAH BERUPA UANG YANG DITERIMA SERTA SKPD PEMBERI HIBAH
						</h4>
						<h4>
							TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
						</h4>
					</th>
				</tr>
			</thead>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border text-sm-2" width="5%">
						NO
					</th>
					<th class="border text-sm-2" width="35%">
						NAMA PENERIMA
					</th>
					<th class="border text-sm-2" width="40%">
						ALAMAT PENERIMA
					</th>
					<th class="border text-sm-2" width="20%">
						JUMLAH (Rp)
					</th>
				</tr>
				<tr bgcolor="gray">
					<th class="border text-sm">
						1
					</th>
					<th class="border text-sm">
						2
					</th>
					<th class="border text-sm">
						3
					</th>
					<th class="border text-sm">
						4
					</th>
				</tr>
			</thead>
				<?php
					/*$id_rek_1						= 0;
					$id_rek_2						= 0;
					$id_rek_3						= 0;
					
					foreach($results['data'] as $key => $val)
					{
						if($val->id_rek_1 != $id_rek_1)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . '</b>
									</td>
									<td class="border text-sm">
										<b>' . $val->uraian_rek_1 . '</b>
									</td>
									<td class="border text-sm">
										<b>' . $val->uraian_rek_1 . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_2 != $id_rek_2)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '</b>
									</td>
									<td style="padding-left:5px" class="border text-sm">
										<b>' . $val->uraian_rek_2 . '</b>
									</td>
									<td style="padding-left:5px" class="border text-sm">
										<b>' . $val->uraian_rek_2 . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_3 != $id_rek_3)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . sprintf('%02d', $val->kd_rek_3) . '</b>
									</td>
									<td style="padding-left:7px" class="border text-sm">
										<b>' . $val->uraian_rek_3 . '</b>
									</td>
									<td style="padding-left:7px" class="border text-sm">
										<b>' . $val->uraian_rek_3 . '</b>
									</td>
								</tr>
							';
						}
						$id_rek_1					= $val->id_rek_1;
						$id_rek_2					= $val->id_rek_2;
						$id_rek_3					= $val->id_rek_3;
					}*/
				?>
			</tbody>
		</table>
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="border no-border-right text-center" width="50%">
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
				</td>
				<td class="border no-border-left text-center" width="50%">
					<b><?php echo ($results['header']->jabatan_kepala_daerah ? $results['header']->jabatan_kepala_daerah : null); ?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo ($results['header']->nama_kepala_daerah ? $results['header']->nama_kepala_daerah : null); ?></b></u>
				</td>
			</tr>
		</table>
		<htmlpagefooter name="footer">
			<table class="table print">
				<tfoot>
					<tr>
						<td class="border text-sm no-border-right" colspan="3">
							<i>
								Lampiran I - 
								<?php echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
							</i>
						</td>
						<td class="border text-sm text-right no-border-left">
							<?php echo phrase('page') . ' {PAGENO} dari {nb}'; ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</htmlpagefooter>
	</body>
</html>