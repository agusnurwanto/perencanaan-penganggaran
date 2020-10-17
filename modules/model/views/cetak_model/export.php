<html>
	<head>
		<title>
			<?php //echo 'RKA - ' . $title; ?>
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		<style type="text/css">
			@import url('<?php echo base_url('themes/assets/fonts/Oxygen/Oxygen.css'); ?>');
			@page
			{
				sheet-size: 8.5in 13in;
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
				border-top:1px solid #999999;
				border-bottom: 0;
				margin-bottom: 15px
			}
			.separator
			{
				border-top: 3px solid #000000;
				border-bottom:1px solid #000000;
				padding:1px;
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
				border:1px solid #000
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
		<table>
			<thead>
				<tr>
					<th colspan="3">
						<h3>
							VARIABEL YANG DIGUNAKAN DALAM MODEL
						</h3>
					</th>
				</tr>
			</thead>
			<br />
			<br />
			<tbody>
				<tr>
					<td>
						KODE MODEL
					</td>
					<td>
						:
					</td>
					<td>
						EPRO-<?php echo sprintf('%03d', $results->header->kd_model); ?>
					</td>
				</tr>
				<tr>
					<td>
						NAMA MODEL
					</td>
					<td>
						:
					</td>
					<td>
						<?php echo $results->header->nm_model; ?>
					</td>
				</tr>
				<tr>
					<td>
						DESKRIPSI
					</td>
					<td>
						:
					</td>
					<td>
						<?php echo $results->header->desc; ?>
					</td>
				</tr>
			</tbody>
		</table>
		<br />
		<br />
		<table class="table">
			<thead>
				<tr>
					<th class="bordered">
						NO
					</th>
					<th class="bordered">
						NAMA VARIABEL
					</th>
					<th class="bordered">
						SATUAN
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					foreach($results->variabel as $key => $val)
					{
						echo '
							<tr>
								<td class="bordered text-center">
									' . $val->kd_variabel . '
								</td>
								<td class="bordered">
									' . $val->nm_variabel . '
								</td>
								<td class="bordered">
									' . $val->satuan . '
								</td>
							</tr>
						';
					}
				?>
			</tbody>
		</table>
		<pagebreak />
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2" width="80" class="bordered">
						<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
					</th>
					<th class="bordered text-center">
						MODEL <?php echo strtoupper($results->header->nm_model); ?>
						<br />
						RENCANA KERJA DAN ANGGARAN
						<br />
						SATUAN KERJA PERANGKAT DAERAH
					</th>
					<th rowspan="2" class="bordered text-center" width="80">
						EPRO-<?php echo sprintf('%03d', $results->header->kd_model); ?>
					</th>
				</tr>
				<tr>
					<th class="bordered text-center">
						PEMERINTAH KOTA BEKASI
						<br />
						Tahun Anggaran : <?php echo get_userdata('year'); ?>
					</th>
				</tr>
				<tr>
					<th colspan="3" class="bordered">
						INDIKATOR DAN TOLOK UKUR KINERJA BELANJA LANGSUNG
					</th>
				</tr>
			</thead>
		</table>
		<table class="table">
			<tr>
				<th class="bordered">
					INDIKATOR
				</th>
				<th class="bordered">
					TOLOK UKUR KINERJA
				</th>
				<th class="bordered">
					TARGET KINERJA
				</th>
			</tr>
			<tr>
				<td class="bordered">
					<b>
						CAPAIAN PROGRAM
					</b>
				</td>
				<td class="bordered">
					
				</td>
				<td class="bordered">
					
				</td>
			</tr>
			<?php
				$masukan			= null;
				$keluaran			= null;
				$hasil				= null;
				$kd_indikator		= 0;
				foreach($results->indikator as $key => $val)
				{
					$target					= $val->target;
					foreach($results->variabel as $var => $abc)
					{
						$target				= str_replace('{' . $abc->id . '}', '{' . $abc->nm_variabel . '}', $target);
					}
					if($val->jns_indikator == 1 && $kd_indikator != $val->kd_indikator)
					{
						$masukan	.= ($masukan ? '<td class="bordered"></td>' : '') . '<td class="bordered">' . $val->tolak_ukur . '</td><td class="bordered">' . $target . ' ' . $val->satuan . '</td></tr><tr>';
					}
					elseif($val->jns_indikator == 2 && $kd_indikator != $val->kd_indikator)
					{
						$keluaran	.= ($keluaran ? '<td class="bordered"></td>' : '') . '<td class="bordered">' . $val->tolak_ukur . '</td><td class="bordered">' . $target . ' ' . $val->satuan . '</td></tr><tr>';
					}
					elseif($val->jns_indikator == 3 && $kd_indikator != $val->kd_indikator)
					{
						$hasil		.= ($hasil ? '<td class="bordered"></td>' : '') . '<td class="bordered">' . $val->tolak_ukur . '</td><td class="bordered">' . $target . ' ' . $val->satuan . '</td></tr><tr>';
					}
					$kd_indikator	= $val->kd_indikator;
				}
			?>
			<tr>
				<td class="bordered">
					<b>
						MASUKAN
					</b>
				</td>
				<?php echo ($masukan ? $masukan : '<td colspan="2"></td>'); ?>
			</tr>
			<tr>
				<td class="bordered">
					<b>
						KELUARAN
					</b>
				</td>
				<?php echo ($keluaran ? $keluaran : '<td colspan="2"></td>'); ?>
			</tr>
			<tr>
				<td class="bordered">
					<b>
						HASIL
					</b>
				</td>
				<?php echo ($hasil ? $hasil : '<td colspan="2"></td>'); ?>
			</tr>
		</table>
		<br />
		<table class="table">
			<thead>
				<tr>
					<th colspan="6" class="bordered text-left">
						Kelompok Sasaran dan Kegiatan
					</th>
				</tr>
				<tr>
					<th colspan="6" class="bordered">
						RINCIAN ANGGARAN BELANJA LANGSUNG MENURUT PROGRAM DAN PER KEGIATAN SATUAN KERJA PERANGKAT DAERAH
					</th>
				</tr>
				<tr>
					<th rowspan="2" class="bordered" width="13%">
						KODE REKENING
					</th>
					<th rowspan="2" class="bordered" width="37%">
						URAIAN
					</th>
					<th colspan="3" class="bordered" width="40%">
						RINCIAN PERHITUNGAN
					</th>
					<th rowspan="2" class="bordered" width="10%">
						JUMLAH
						<br />
						(Rp)
					</th>
				</tr>
				<tr>
					<th class="bordered">
						Volume
					</th>
					<th class="bordered">
						Satuan
					</th>
					<th class="bordered">
						Harga Satuan
					</th>
				</tr>
				<tr bgcolor="gray">
					<th class="bordered">
						1
					</th>
					<th class="bordered">
						2
					</th>
					<th class="bordered">
						3
					</th>
					<th class="bordered">
						4
					</th>
					<th class="bordered">
						5
					</th>
					<th class="bordered">
						6
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_rek_1						= 0;
					$id_rek_2						= 0;
					$id_rek_3						= 0;
					$id_rek_4						= 0;
					$id_rek_5						= 0;
					$id_rek_6						= 0;
					$id_belanja_sub					= 0;
					$id_belanja_rinci				= 0;
					foreach($results->belanja as $key => $val)
					{
						if($val->id_rek_1 != $id_rek_1)
						{
							echo '
								<tr>
									<td align="left" class="bordered">
										<b>' . $val->kd_rek_1 . '</b>
									</td>
									<td class="bordered">
										<b>' . $val->nm_rek_1 . '</b>
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="center" class="bordered">
										
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="right" class="bordered">
									</td>
								</tr>
							';
						}
						if($val->id_rek_2 != $id_rek_2)
						{
							echo '
								<tr>
									<td align="left" class="bordered">
										<b>' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '</b>
									</td>
									<td style="padding-left:3px" class="bordered">
										<b>
											' . $val->nm_rek_2 . '
										</b>
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="center" class="bordered">
										
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="right" class="bordered">
									</td>
								</tr>
							';
						}
						if($val->id_rek_3 != $id_rek_3)
						{
							echo '
								<tr>
									<td align="left" class="bordered">
										<b>
											' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '
										</b>
									</td>
									<td style="padding-left:6px" class="bordered">
										<b>
											' . $val->nm_rek_3 . '
										</b>
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="center" class="bordered">
										
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="right" class="bordered">
									</td>
								</tr>
							';
						}
						if($val->id_rek_4 != $id_rek_4)
						{
							echo '
								<tr>
									<td align="left" class="bordered">
										<b>
											' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . sprintf('%02d', $val->kd_rek_4) . '
										</b>
									</td>
									<td style="padding-left:9px" class="bordered">
										<b>
											' . $val->nm_rek_4 . '
										</b>
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="center" class="bordered">
										
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="right" class="bordered">
									</td>
								</tr>
							';
						}
						if($val->id_rek_5 != $id_rek_5)
						{
							echo '
								<tr>
									<td align="left" class="bordered">
										<b>
											' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . sprintf('%02d', $val->kd_rek_4) . '.' . sprintf('%02d', $val->kd_rek_5) . '
										</b>
									</td>
									<td style="padding-left:12px" class="bordered">
										<b>
											' . $val->nm_rek_5 . '
										</b>
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="center" class="bordered">
										
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="right" class="bordered">
									</td>
								</tr>
							';
						}
						if($val->id_rek_6 != $id_rek_6)
						{
							echo '
								<tr>
									<td align="left" class="bordered">
										<b>
											' . $val->kd_rek_1 . '.' . $val->kd_rek_2 . '.' . $val->kd_rek_3 . '.' . sprintf('%02d', $val->kd_rek_4) . '.' . sprintf('%02d', $val->kd_rek_5) . '.' . sprintf('%02d', $val->kd_rek_6) . '
										</b>
									</td>
									<td style="padding-left:15px" class="bordered">
										<b>
											' . $val->nm_rek_6 . '
										</b>
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="center" class="bordered">
										
									</td>
									<td align="right" class="bordered">
										
									</td>
									<td align="right" class="bordered">
									</td>
								</tr>
							';
						}
						if($val->id_belanja_sub != $id_belanja_sub)
						{
							echo '
								<tr>
									<td class="bordered">
										
									</td>
									<td style="padding-left:15px" class="bordered">
										' . $val->nm_belanja_sub . '
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered">
										
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered text-right">
									</td>
								</tr>
							';
						}
						if($val->id_belanja_rinci != $id_belanja_rinci)
						{
							$vol_1					= $val->vol_1;
							$vol_2					= $val->vol_2;
							$vol_3					= $val->vol_3;
							$nilai					= $val->nilai;
							foreach($results->variabel as $var => $abc)
							{
								$vol_1				= str_replace('{' . $abc->id . '}', '{' . $abc->nm_variabel . '}', $vol_1);
								$vol_2				= str_replace('{' . $abc->id . '}', '{' . $abc->nm_variabel . '}', $vol_2);
								$vol_3				= str_replace('{' . $abc->id . '}', '{' . $abc->nm_variabel . '}', $vol_3);
								$nilai				= str_replace('{' . $abc->id . '}', '{' . $abc->nm_variabel . '}', $nilai);
							}
							echo '
								<tr>
									<td class="bordered">
										
									</td>
									<td style="padding-left:18px" class="bordered">
										- ' . $val->nm_belanja_rinci . ' (' . $vol_1 . ' ' . $val->satuan_1 . ' ' . ($vol_2 || $vol_3 ? ' x ' : null) . ' ' . $vol_2 . ' ' . $val->satuan_2 . ' ' . ($vol_3 ? ' x ' : null) . ' ' . $vol_3 . ' ' . $val->satuan_3 . ')
									</td>
									<td align="center" class="bordered">
									</td>
									<td align="center" class="bordered">
										' . $val->satuan_123 . '
									</td>
									<td align="right" class="bordered">
										' . $nilai . '
									</td>
									<td align="right" class="bordered">
									</td>
								</tr>
							';
						}
						$id_rek_1					= $val->id_rek_1;
						$id_rek_2					= $val->id_rek_2;
						$id_rek_3					= $val->id_rek_3;
						$id_rek_4					= $val->id_rek_4;
						$id_rek_5					= $val->id_rek_5;
						$id_rek_6					= $val->id_rek_6;
						$id_belanja_sub				= $val->id_belanja_sub;
						$id_belanja_rinci			= $val->id_belanja_rinci;
					}
				?>
			</tbody>
		</table>
		<htmlpagefooter name="footer">
			<table class="print">
				<tfoot>
					<tr>
						<td class="text-sm">
							<i>
								MODEL: <?php echo $title; ?>
							</i>
						</td>
						<td class="text-muted text-sm text-right">
							Halaman {PAGENO} dari {nb}
						</td>
					</tr>
				</tfoot>
			</table>
		</htmlpagefooter>
	</body>
</html>