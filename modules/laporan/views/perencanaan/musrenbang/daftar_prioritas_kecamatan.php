<!DOCTYPE html>
<html>
	<head>
		<title>
			Daftar Kegiatan Prioritas Kecamatan
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
		<table class="table" align="center">
			<tr>
				<th width="100" class="border no-border-right">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</th>
				<th class="border no-border-left" align="center" width="100%" colspan="6">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : "") ; ?>
					</h4>
					<h4>
						DAFTAR URUTAN KEGIATAN PRIORITAS KECAMATAN MENURUT PERANGKAT DAERAH
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year')?>
					</h4>
				</th>
			</tr>
		</table>
		<table class="table">
			<tr>
				<td class="border no-border-right no-border-bottom no-border-top no-margin" width="15%" colspan="2">
					Kecamatan
				</td>
				<td class="no-margin" width="3%" align="center">
					:
				</td>
				<td class="no-margin" width="10%">
					<?php echo sprintf('%02d', $results['header']->kode_kecamatan); ?>
				</td>
				<td class="border no-border-left no-border-bottom no-border-top no-margin" width="72%">
					<?php echo (isset($results['header']->nama_kecamatan) ? strtoupper($results['header']->nama_kecamatan) : null); ?>
				</td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border">
						No
					</th>
					<th class="border">
						Prioritas
						<br />
						Daerah
					</th>
					<th class="border">
						Sasaran Daerah
					</th>
					<th class="border">
						Program
					</th>
					<th class="border">
						Kegiatan Prioritas
					</th>
					<th class="border">
						Sasaran
						<br />
						Kegiatan
					</th>
					<th class="border">
						Lokasi
					</th>
					<th class="border">
						Volume
					</th>
					<th class="border">
						Pagu
					</th>
					<th class="border">
						Perangkat Daerah
						<br />
						Penanggung Jawab
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$num									= 1;
					$volume									= 0;
					$total									= 0;
					//$total_sd_periode_ini					= 0;
					$controller								=& get_instance();
					foreach($results['data'] as $key => $val)
					{
						/* extract the variable */
						$volume								= json_decode($val['variabel_kecamatan'], true/* true mean the result would be format as array instead of object */);
						
						/* get the variable through controller, check the get_volume() function */
						$volume								= $controller->get_volume($volume);
						
						echo '
							<tr>
								<td class="border" align="center">
									' . $num . '
								</td>
								<td class="border">
									' . $val['prioritas'] . '
								</td>
								<td class="border">
									' . $val['sasaran'] . '
								</td>
								<td class="border">
									' . $val['nm_program'] . '
								</td>
								<td class="border">
									' . $val['nama_kegiatan'] . '
								</td>
								<td class="border">
									' . $val['sasaran_kegiatan'] . '
								</td>
								<td class="border">
									' . $val['lokasi'] . '
								</td>
								<td class="border" align="right">
									' . $volume . '
								</td>
								<td class="border" align="right">
									' . number_format_indo($val['nilai_kecamatan']) . '
								</td>
								<td class="border">
									' . ucwords(strtolower($val['nm_unit'])) . '
								</td>
							</tr>
								';
						$num++;
						$total							+= $val['nilai_kecamatan'];
					}
				?>
			</tbody>
			<tr>
				<td colspan="8" class="border" align="center">
					<b>JUMLAH</b>
				</td>
				<td class="border" align="right">
					<b><?php echo number_format($total); ?></b>
				</td>
				<td class="border" align="right">
					<b>
						<?php //echo number_format($total_periode_ini, 2); ?>
					</b>
				</td>
			</tr>
		</table>
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="border no-border-right" width="60%">
				</td>
				<td class="border no-border-left" width="40%">
					<?php echo (isset($nama_daerah) ? $nama_daerah : "") ; ?>, <?php echo $tanggal_cetak; ?>
					<br />
						<b><?php echo (isset($results['header']->jabatan_camat) ? $results['header']->jabatan_camat : null); ?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo (isset($results['header']->camat) ? $results['header']->camat : null); ?></b></u>
					<br />
					NIP. <?php echo (isset($results['header']->nip_camat) ? $results['header']->nip_camat : null); ?>
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
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>