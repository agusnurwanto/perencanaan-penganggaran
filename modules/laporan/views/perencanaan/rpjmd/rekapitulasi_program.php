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
		<table class="table">
			<thead>
				<tr>
					<th class="border" rowspan="2" width="7%">
						KODE
					</th>
					<th class="border" rowspan="2" width="30%">
						PERANGKAT DAERAH / PROGRAM
					</th>
					<th class="border" rowspan="2" width="33%">
						INDIKATOR PROGRAM
					</th>
					<th class="border" colspan="5" width="30%">
						TARGET TAHUN
					</th>
				</tr>
				<tr>
					<?php
					for ($x = $results['visi']->tahun_awal; $x <= $results['visi']->tahun_akhir; $x++) {
					  echo '
						<th class="border">
							' . $x . '
						</th>
						';
					}
					?>
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
					<th class="border text-sm">
						(5)
					</th>
					<th class="border text-sm">
						(6)
					</th>
					<th class="border text-sm">
						(7)
					</th>
					<th class="border text-sm">
						(8)
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_urusan								= 0;
					$id_bidang								= 0;
					$id_unit								= 0;
					$id_prog								= 0;
					$total_program							= 0;
					foreach($results['data'] as $key => $val)
					{
						if( $val->id_urusan != $id_urusan)
						{
							echo '
								<tr>
									<td class="border">
										<b>' . $val->kode_urusan . '</b>
									</td>
									<td class="border" style="padding-left:5px">
										<b>' . ucwords(strtolower($val->nama_urusan)) . '</b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
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
									<td class="border" style="padding-left:5px">
										<b>' . ucwords(strtolower($val->nama_bidang)) . '</b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
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
									<td class="border" style="padding-left:5px">
										<b>' . ucwords(strtolower($val->nama_unit)) . '</b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
									<td class="border">
										<b></b>
									</td>
								</tr>
							';
						}
						$list_capaian_program						= array();
						$rowspana									= 0;
						if(sizeof($results['capaian_program']) > 0)
						{
							foreach($results['capaian_program'] as $key_a => $val_a)
							{
								if($val_a->id_prog != $val->id_prog) continue;
								$list_capaian_program[]				= '
									<td class="border" style="padding-left:5px">' . $val_a->kode . '. ' . $val_a->tolak_ukur . '</td>
									<td class="border" align="center">' . (fmod($val_a->tahun_1_target, 1) !== 0.00 ? number_format($val_a->tahun_1_target, 2) : number_format($val_a->tahun_1_target, 0)) . ' ' . $val_a->tahun_1_satuan . '</td>
									<td class="border" align="center">' . (fmod($val_a->tahun_2_target, 1) !== 0.00 ? number_format($val_a->tahun_2_target, 2) : number_format($val_a->tahun_2_target, 0)) . ' ' . $val_a->tahun_1_satuan . '</td>
									<td class="border" align="center">' . (fmod($val_a->tahun_3_target, 1) !== 0.00 ? number_format($val_a->tahun_3_target, 2) : number_format($val_a->tahun_3_target, 0)) . ' ' . $val_a->tahun_1_satuan . '</td>
									<td class="border" align="center">' . (fmod($val_a->tahun_4_target, 1) !== 0.00 ? number_format($val_a->tahun_4_target, 2) : number_format($val_a->tahun_4_target, 0)) . ' ' . $val_a->tahun_1_satuan . '</td>
									<td class="border" align="center">' . (fmod($val_a->tahun_5_target, 1) !== 0.00 ? number_format($val_a->tahun_5_target, 2) : number_format($val_a->tahun_5_target, 0)) . ' ' . $val_a->tahun_1_satuan . '</td>
									';
								$rowspana++;
							}
						}
						$new_capaian								= null;
						if(sizeof($list_capaian_program) > 1)
						{
							foreach($list_capaian_program as $_keya => $_vala)
							{
								if($_keya < 1) continue;
								$new_capaian						.= '<tr>' . $_vala . '</tr>';
							}
						}
						if( $val->id_prog != $id_prog)
						{
							echo '
								<tr>
									<td class="border"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										' . $val->kode_urusan . '.' . sprintf('%02d', $val->kode_bidang) . '.' . sprintf('%02d', $val->kode_unit) . '.' . sprintf('%02d', $val->kode_program) . '
									</td>
									<td class="border" style="padding-left:5px"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										' . $val->nama_program . '
									</td>
									' . (isset($list_capaian_program[0]) ? $list_capaian_program[0] : '<td class="border"></td><td class="border"></td>') . '
								</tr>
								' . $new_capaian . '
							';
						}
						$id_urusan								= $val->id_urusan;
						$id_bidang								= $val->id_bidang;
						$id_unit								= $val->id_unit;
						$id_prog								= $val->id_prog;
					}
				?>
			</tbody>
		</table>
		<br />
		<br />
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							Kompilasi Program dan Pagu Indikatif Tiap Perangkat Daerah
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