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
						Lampiran I : &nbsp;
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
						<h5>
							<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
						</h5>
						<h5>
							RINGKASAN PENJABARAN APBD YANG DIKLASIFIKASI MENURUT KELOMPOK, JENIS, OBJEK,
							<br />
							RINCIAN OBJEK, SUB RINCIAN OBJEK PENDAPATAN, BELANJA, DAN PEMBIAYAAN
						</h5>
						<h5>
							TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
						</h5>
					</th>
				</tr>
			</thead>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border text-sm-2" width="18%">
						KODE
					</th>
					<th class="border text-sm-2" width="62%">
						URAIAN
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
					$jumlah_pendapatan				= 0;
					
					foreach($results['pendapatan'] as $key => $val)
					{
						if($val->id_rek_1 != $id_rek_1)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . '</b>
									</td>
									<td class="border text-sm">
										<b>' . $val->nm_rek_1 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_1, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_2 != $id_rek_2)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . '</b>
									</td>
									<td style="padding-left:15px" class="border text-sm">
										<b>' . $val->nm_rek_2 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_2, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_3 != $id_rek_3)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . '</b>
									</td>
									<td style="padding-left:20px" class="border text-sm">
										<b>' . $val->nm_rek_3 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_3, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_4 != $id_rek_4)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . '</b>
									</td>
									<td style="padding-left:25px" class="border text-sm">
										<b>' . $val->nm_rek_4 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_4, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_5 != $id_rek_5)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . ' . ' . sprintf('%02d', $val->kd_rek_5) . '</b>
									</td>
									<td style="padding-left:30px" class="border text-sm">
										<b>' . $val->nm_rek_5 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_5, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_6 != $id_rek_6)
						{
							echo '
								<tr>
									<td class="border text-sm">
										' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . ' . ' . sprintf('%02d', $val->kd_rek_5) . ' . ' . sprintf('%02d', $val->kd_rek_6) . '
									</td>
									<td style="padding-left:40px" class="border text-sm">
										' . $val->nm_rek_6 . '
									</td>
									<td class="border text-sm text-right">
										' . number_format_indo($val->jumlah_rek_6, 2) . '
									</td>
								</tr>
							';
						}
						if($val->kd_rek_1 == 4)
						{
							$jumlah_pendapatan		+= $val->jumlah_rek_6;
						}
						$id_rek_1					= $val->id_rek_1;
						$id_rek_2					= $val->id_rek_2;
						$id_rek_3					= $val->id_rek_3;
						$id_rek_4					= $val->id_rek_4;
						$id_rek_5					= $val->id_rek_5;
						$id_rek_6					= $val->id_rek_6;
					}
				?>
					<!--<tr>
						<td class="border text-sm">
							
						</td>
						<td style="padding-right:10px" class="border text-right text-sm">
							<b>JUMLAH PENDAPATAN</b>
						</td>
						<td class="border text-sm text-right">
							<b><?php //echo number_format_indo($jumlah_pendapatan, 2);?></b>
						</td>
					</tr>-->
					<tr>
						<td colspan="3" class="border text-sm">
							&nbsp;
						</td>
					</tr>
					
				<?php
					$id_rek_1						= 0;
					$id_rek_2						= 0;
					$id_rek_3						= 0;
					$id_rek_4						= 0;
					$id_rek_5						= 0;
					$id_rek_6						= 0;
					$jumlah_belanja					= 0;
					foreach($results['belanja'] as $key => $val)
					{
						if($val->id_rek_1 != $id_rek_1)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . '</b>
									</td>
									<td class="border text-sm">
										<b>' . $val->nm_rek_1 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_1, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_2 != $id_rek_2)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . '</b>
									</td>
									<td style="padding-left:15px" class="border text-sm">
										<b>' . $val->nm_rek_2 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_2, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_3 != $id_rek_3)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . '</b>
									</td>
									<td style="padding-left:20px" class="border text-sm">
										<b>' . $val->nm_rek_3 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_3, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_4 != $id_rek_4)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . '</b>
									</td>
									<td style="padding-left:25px" class="border text-sm">
										<b>' . $val->nm_rek_4 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_4, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_5 != $id_rek_5)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . ' . ' . sprintf('%02d', $val->kd_rek_5) . '</b>
									</td>
									<td style="padding-left:30px" class="border text-sm">
										<b>' . $val->nm_rek_5 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_5, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_6 != $id_rek_6)
						{
							echo '
								<tr>
									<td class="border text-sm">
										' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . ' . ' . sprintf('%02d', $val->kd_rek_5) . ' . ' . sprintf('%02d', $val->kd_rek_6) . '
									</td>
									<td style="padding-left:40px" class="border text-sm">
										' . $val->nm_rek_6 . '
									</td>
									<td class="border text-sm text-right">
										' . number_format_indo($val->jumlah_rek_6, 2) . '
									</td>
								</tr>
							';
						}
						if($val->kd_rek_1 == 5)
						{
							$jumlah_belanja		+= $val->jumlah_rek_6;
						}
						$id_rek_1					= $val->id_rek_1;
						$id_rek_2					= $val->id_rek_2;
						$id_rek_3					= $val->id_rek_3;
						$id_rek_4					= $val->id_rek_4;
						$id_rek_5					= $val->id_rek_5;
						$id_rek_6					= $val->id_rek_6;
					}
				?>
					<!--<tr>
						<td class="border text-sm">
							
						</td>
						<td style="padding-right:10px" class="border text-right text-sm">
							<b>JUMLAH BELANJA</b>
						</td>
						<td class="border text-sm text-right">
							<b><?php //echo number_format_indo($jumlah_belanja, 2);?></b>
						</td>
					</tr>-->
					<tr>
						<td class="border text-sm">
							
						</td>
						<td style="padding-right:10px" class="border text-right text-sm">
							<b>SURPLUS / (DEFISIT)</b>
						</td>
						<td class="border text-sm text-right">
							<b>
							<?php 
								$surplus_defisit		= $jumlah_pendapatan - $jumlah_belanja;
								if ($surplus_defisit >= 0)
								{
									echo number_format_indo($surplus_defisit, 2);
								}
								else
								{
									$surplus_defisit		= $surplus_defisit * -1 ;
									echo '(' . number_format_indo($surplus_defisit, 2) . ')' ;											
								}
							?>
							</b>
						</td>
					</tr>
					<tr>
						<td colspan="3" class="border text-sm">
							&nbsp;
						</td>
					</tr>
				<?php
					$id_rek_1						= 0;
					$id_rek_2						= 0;
					$id_rek_3						= 0;
					$id_rek_4						= 0;
					$id_rek_5						= 0;
					$id_rek_6						= 0;
					$jumlah_pembiayaan_penerimaan	= 0;
					foreach($results['pembiayaan_penerimaan'] as $key => $val)
					{
						if($val->id_rek_1 != $id_rek_1)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . '</b>
									</td>
									<td class="border text-sm">
										<b>' . $val->nm_rek_1 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_1, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_2 != $id_rek_2)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . '</b>
									</td>
									<td style="padding-left:15px" class="border text-sm">
										<b>' . $val->nm_rek_2 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_2, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_3 != $id_rek_3)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . '</b>
									</td>
									<td style="padding-left:20px" class="border text-sm">
										<b>' . $val->nm_rek_3 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_3, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_4 != $id_rek_4)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . '</b>
									</td>
									<td style="padding-left:25px" class="border text-sm">
										<b>' . $val->nm_rek_4 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_4, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_5 != $id_rek_5)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . ' . ' . sprintf('%02d', $val->kd_rek_5) . '</b>
									</td>
									<td style="padding-left:30px" class="border text-sm">
										<b>' . $val->nm_rek_5 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_5, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_6 != $id_rek_6)
						{
							echo '
								<tr>
									<td class="border text-sm">
										' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . ' . ' . sprintf('%02d', $val->kd_rek_5) . ' . ' . sprintf('%02d', $val->kd_rek_6) . '
									</td>
									<td style="padding-left:40px" class="border text-sm">
										' . $val->nm_rek_6 . '
									</td>
									<td class="border text-sm text-right">
										' . number_format_indo($val->jumlah_rek_6, 2) . '
									</td>
								</tr>
							';
						}
						if($val->kd_rek_1 == 6)
						{
							$jumlah_pembiayaan_penerimaan		+= $val->jumlah_rek_6;
						}
						$id_rek_1					= $val->id_rek_1;
						$id_rek_2					= $val->id_rek_2;
						$id_rek_3					= $val->id_rek_3;
						$id_rek_4					= $val->id_rek_4;
						$id_rek_5					= $val->id_rek_5;
						$id_rek_6					= $val->id_rek_6;
					}
				?>
					<!--<tr>
						<td class="border text-sm">
							
						</td>
						<td style="padding-right:10px" class="border text-right text-sm">
							<b>JUMLAH PENERIMAAN PEMBIAYAAN</b>
						</td>
						<td class="border text-sm text-right">
							<b><?php //echo number_format_indo($jumlah_pembiayaan_penerimaan, 2);?></b>
						</td>
					</tr>-->
					<tr>
						<td colspan="3" class="border text-sm">
							&nbsp;
						</td>
					</tr>
				<?php
					$id_rek_1						= 0;
					$id_rek_2						= 0;
					$id_rek_3						= 0;
					$id_rek_4						= 0;
					$id_rek_5						= 0;
					$id_rek_6						= 0;
					$jumlah_pembiayaan_pengeluaran	= 0;
					foreach($results['pembiayaan_pengeluaran'] as $key => $val)
					{
						if($val->id_rek_1 != $id_rek_1)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . '</b>
									</td>
									<td class="border text-sm">
										<b>' . $val->nm_rek_1 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_1, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_2 != $id_rek_2)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . '</b>
									</td>
									<td style="padding-left:15px" class="border text-sm">
										<b>' . $val->nm_rek_2 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_2, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_3 != $id_rek_3)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . '</b>
									</td>
									<td style="padding-left:20px" class="border text-sm">
										<b>' . $val->nm_rek_3 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_3, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_4 != $id_rek_4)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . '</b>
									</td>
									<td style="padding-left:25px" class="border text-sm">
										<b>' . $val->nm_rek_4 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_4, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_5 != $id_rek_5)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . ' . ' . sprintf('%02d', $val->kd_rek_5) . '</b>
									</td>
									<td style="padding-left:30px" class="border text-sm">
										<b>' . $val->nm_rek_5 . '</b>
									</td>
									<td class="border text-sm text-right">
										<b>' . number_format_indo($val->jumlah_rek_5, 2) . '</b>
									</td>
								</tr>
							';
						}
						if($val->id_rek_6 != $id_rek_6)
						{
							echo '
								<tr>
									<td class="border text-sm">
										' . $val->kd_rek_1 . ' . ' . $val->kd_rek_2 . ' . ' . sprintf('%02d', $val->kd_rek_3) . ' . ' . sprintf('%02d', $val->kd_rek_4) . ' . ' . sprintf('%02d', $val->kd_rek_5) . ' . ' . sprintf('%02d', $val->kd_rek_6) . '
									</td>
									<td style="padding-left:40px" class="border text-sm">
										' . $val->nm_rek_6 . '
									</td>
									<td class="border text-sm text-right">
										' . number_format_indo($val->jumlah_rek_6, 2) . '
									</td>
								</tr>
							';
						}
						if($val->kd_rek_1 == 6)
						{
							$jumlah_pembiayaan_pengeluaran		+= $val->jumlah_rek_6;
						}
						$id_rek_1					= $val->id_rek_1;
						$id_rek_2					= $val->id_rek_2;
						$id_rek_3					= $val->id_rek_3;
						$id_rek_4					= $val->id_rek_4;
						$id_rek_5					= $val->id_rek_5;
						$id_rek_6					= $val->id_rek_6;
					}
				?>
					<!--<tr>
						<td class="border text-sm">
							
						</td>
						<td style="padding-right:10px" class="border text-right text-sm">
							<b>JUMLAH PENGELUARAN PEMBIAYAAN</b>
						</td>
						<td class="border text-sm text-right">
							<b><?php //echo number_format_indo($jumlah_pembiayaan_pengeluaran, 2);?></b>
						</td>
					</tr>-->
					<tr>
						<td colspan="3" class="border text-sm">
							&nbsp;
						</td>
					</tr>
					<tr>
						<td class="border text-sm">
							
						</td>
						<td style="padding-right:10px" class="border text-right text-sm">
							<b>PEMBIAYAAN NETTO</b>
						</td>
						<td class="border text-sm text-right">
							<b>
								<?php 
								$pembiayaan_netto		= $jumlah_pembiayaan_penerimaan - $jumlah_pembiayaan_pengeluaran;
								if ($pembiayaan_netto >= 0)
								{
									echo number_format_indo($pembiayaan_netto, 2);
								}
								else
								{
									$pembiayaan_netto		= $pembiayaan_netto * -1 ;
									echo '(' . number_format_indo($pembiayaan_netto, 2) . ')' ;											
								}
								?>
							</b>
						</td>
					</tr>
					<tr>
						<td class="border text-sm">
							
						</td>
						<td style="padding-right:10px" class="border text-right text-sm">
							<b>SISA LEBIH PEMBIAYAAN ANGGARAN TAHUN BERKENAAN</b>
						</td>
						<td class="border text-sm text-right">
							<b>
								<?php 
								$sisa_tahun_berkenaan		= $surplus_defisit - $pembiayaan_netto;
								if ($sisa_tahun_berkenaan >= 0)
								{
									echo number_format_indo($sisa_tahun_berkenaan, 2);
								}
								else
								{
									$sisa_tahun_berkenaan		= $sisa_tahun_berkenaan * -1 ;
									echo '(' . number_format_indo($sisa_tahun_berkenaan, 2) . ')' ;											
								}
								?>
							</b>
						</td>
					</tr>
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