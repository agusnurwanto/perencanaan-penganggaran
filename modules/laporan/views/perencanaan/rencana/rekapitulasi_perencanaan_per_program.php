<!DOCTYPE html>
<html>
	<head>
		<title>
			Kompilasi Program dan Pagu Indikatif Tiap Perangkat Daerah
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
		<table class="table" align="center">
			<tr>
				<th width="100" class="border no-border-right">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</th>
				<th class="border no-border-left" align="center" width="100%" colspan="9">
					<h4>
						KOMPILASI PROGRAM DAN PAGU INDIKATIF TIAP PERANGKAT DAERAH
					</h4>
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : "") ; ?>
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year')?>
					</h4>
				</th>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="border" width="12%">
						KODE
					</th>
					<th class="border" width="57%">
						URUSAN / BIDANG / PROGRAM
					</th>
					<th class="border" width="13%">
						JML SUB
						<br />
						KEGIATAN
					</th>
					<th class="border" width="18%">
						PAGU INDIKATIF
						<br />
						TAHUN <?php echo get_userdata('year')?>
					</th>
				</tr>
				<tr>
					<th class="border text-sm">
						(1)
					</th>
					<th class="border text-sm">
						(2)
					</th>
					<th class="border text-sm">
						(3)
					</th>
					<th class="border text-sm">
						(4)
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_urusan								= 0;
					$id_bidang								= 0;
					$id_unit								= 0;
					$id_prog								= 0;
					$jumlah_program							= 0;
					$total_program							= 0;
					foreach($results['data'] as $key => $val)
					{
						if($val->id_urusan != $id_urusan)
						{
							echo '
								<tr>
									<td class="border">
										<b>' . $val->kode_urusan . '</b>
									</td>
									<td class="border" style="padding-left:5px">
										<b>' . ucwords(strtolower($val->nama_urusan)) . '</b>
									</td>
									<td class="border" align="right">
										<b>' . number_format($val->jumlah_urusan) . '</b>
									</td>
									<td class="border" align="right">
										<b>' . number_format($val->pagu_urusan) . '</b>
									</td>
								</tr>
							';
						}
						if( $val->id_bidang != $id_bidang)
						{
							echo '
								<tr>
									<td class="border">
										<b>' . $val->kode_urusan . '.' . sprintf('%02d', $val->kode_bidang) . '</b>
									</td>
									<td class="border" style="padding-left:7px">
										<b>' . ucwords(strtolower($val->nama_bidang)) . '</b>
									</td>
									<td class="border" align="right">
										<b>' . number_format($val->jumlah_bidang) . '</b>
									</td>
									<td class="border" align="right">
										<b>' . number_format($val->pagu_bidang) . '</b>
									</td>
								</tr>
							';
						}
						if( $val->id_unit != $id_unit)
						{
							echo '
								<tr>
									<td class="border">
										<b>' . $val->kode_urusan . '.' . sprintf('%02d', $val->kode_bidang) . '.' . sprintf('%02d', $val->kode_unit) . '</b>
									</td>
									<td class="border" style="padding-left:9px">
										<b>' . ucwords(strtolower($val->nama_unit)) . '</b>
									</td>
									<td class="border" align="right">
										<b>' . number_format($val->jumlah_unit) . '</b>
									</td>
									<td class="border" align="right">
										<b>' . number_format($val->pagu_unit) . '</b>
									</td>
								</tr>
							';
						}
						if( $val->id_prog != $id_prog)
						{
							echo '
								<tr>
									<td class="border">
										' . $val->kode_urusan . '.' . sprintf('%02d', $val->kode_bidang) . '.' . sprintf('%02d', $val->kode_unit) . '.' . sprintf('%02d', $val->kode_program) . '
									</td>
									<td class="border" style="padding-left:11px">
										' . $val->nama_program . '
									</td>
									<td class="border" align="right">
										' . number_format($val->jumlah_program) . '
									</td>
									<td class="border" align="right">
										' . number_format($val->pagu_program) . '
									</td>
								</tr>
							';
						}
						$id_urusan								= $val->id_urusan;
						$id_bidang								= $val->id_bidang;
						$id_unit								= $val->id_unit;
						$id_prog								= $val->id_prog;
						$jumlah_program							+= $val->jumlah_program;
						$total_program							+= $val->pagu_program;
					}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="border text-center">
					<b>
						JUMLAH
					</b>
				</td>
				<td class="border text-right">
					<b>
						<?php echo number_format($jumlah_program); ?>
					</b>
				</td>
				<td class="border text-right">
					<b>
						<?php echo number_format($total_program); ?>
					</b>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							Kompilasi Program dan Pagu Indikatif Tiap Perangkat Daerah Tabel 5.1
							<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
						</i>
					</td>
					<td class="text-muted text-sm text-right">
						<?php echo phrase('page') . ' {PAGENO} dari {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>