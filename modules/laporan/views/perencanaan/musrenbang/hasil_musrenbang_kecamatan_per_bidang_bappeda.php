<!DOCTYPE html>
<html>
	<head>
		<title>
			Musrenbang Kecamatan Per Bidang Bappeda
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
	<?php
		if($this->input->get('status') != null)
		{
			$status 				= $this->input->get('status');
			if($status == 1)
			{
				$status_text	= '<b>Usulan Kelurahan</b>';
			}
			elseif($status == 2)
			{
				$status_text	= '<b>Diterima Kecamatan</b>';
			}
			elseif($status == 3)
			{
				$status_text	= '<b>Ditolak Kecamatan</b>';
			}
			elseif($status == 4)
			{
				$status_text	= '<b>Usulan Kecamatan</b>';
			}
			elseif($status == 5)
			{
				$status_text	= '<b>Pilih Semua</b>';
			}
			elseif($status == 6)
			{
				$status_text	= '<b>Diterima Kecamatan dan Usulan Kecamatan</b>';
			}
			else
			{
				$status_text	= '<b>Anda Harus Pilih Status</b>';
			}
		}
		else
		{
			$status_text	= '<b>Anda Harus Pilih Status</b>';
		}
	?>
	<body>
		<table class="table" align="center">
			<tr>
				<th width="100" class="border no-border-right">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</th>
				<th class="border no-border-left" align="center" width="100%" colspan="5">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : "") ; ?>
					</h4>
					<h4>
						HASIL MUSRENBANG KECAMATAN PER BIDANG BAPPEDA
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
			<tr>
				<td class="border no-border-right no-border-bottom no-border-top" colspan="2">
					Status
				</td>
				<td>
					:
				</td>
				<td colspan="2" class="border no-border-left no-border-bottom no-border-top">
					<?php echo $status_text; ?>
				</td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border">
						KODE
					</th>
					<th class="border">
						PROGRAM / PEKERJAAN
					</th>
					<th class="border">
						RT / RW
					</th>
					<th class="border">
						ALAMAT
					</th>
					<th class="border">
						NILAI
					</th>
					<th class="border">
						URGENSI
					</th>
				<?php 
					if($status == 5)
					{
				?>
					<th class="border">
						STATUS
					</th>
				<?php
					}
				?>
				</tr>
			</thead>
			<tbody>
				<?php
					$num									= 1;
					$kode									= 0;
					$nilai									= 0;
					$total									= 0;
					foreach($results['data'] as $key => $val)
					{
						if($this->input->get('status') != null)
						{
							$status 					= $this->input->get('status');
							if($status == 1) // Usulan Kelurahan
							{
								$nilai					= $val['nilai_usulan'];
								$total					+= $val['nilai_usulan'];
							}
							elseif($status == 3) // Ditolak Kecamatan
							{
								$nilai					= $val['nilai_kelurahan'];
								$total					+= $val['nilai_kelurahan'];
							}
							elseif($status == 2 || $status == 4 || $status == 6) // Diterima Kecamatan dan Usulan Kecamatan
							{
								$nilai					= $val['nilai_kelurahan'];
								$total					+= $val['nilai_kelurahan'];
							}
							elseif($status == 5) // Pilih Semua
							{
								if($val['flag'] == 0)
								{
									$nilai				= $val['nilai_usulan'];
									$total				+= $val['nilai_kelurahan'];
								}
								else
								{
									$nilai_kelurahan	= $val['nilai_kelurahan'];
									$total				+= $val['nilai_kelurahan'];
								}
							}
						}
						if($val['kode'] != $kode)
						{
							$num						= 1;
							echo '
								<tr>
									<td class="border" align="center">
										<b>
											' . $val['kode'] . '
										</b>
									</td>
									<td class="border">
										<b>
											' . $val['nama_bidang'] . '
										</b>
									</td>
									<td class="border" align="right">
										
									</td>
									<td class="border" align="center">
										
									</td>
									<td class="border" align="right">
										<b>
											
										</b>										
									</td>
									<td class="border" align="right">
									</td>
									';
								if($status == 5)
								{
							echo'	<td class="border">
									</td>
									';
								}
							echo '
								</tr>
							';
						}
						echo '
							<tr>
								<td class="border" align="center">
									' . $val['kode'] . '.' . sprintf('%02d', $num) . '
								</td>
								<td class="border">
									' . $val['nama_pekerjaan'] . '
								</td>
								<td class="border" align="center">
									' . $val['nama_kelurahan'] . ' ' . $val['rt'] . ' / ' . $val['rw'] . '
								</td>
								<td class="border">
									' . $val['map_address'] . '
								</td>
								<td class="border" align="right">
									' . number_format($nilai) . '
								</td>
								<td class="border">
									' . $val['urgensi'] . '
								</td>
								';
							if($status == 5)
							{
						echo'	<td class="border">
								';
								if($val['flag'] == 0)
								{
									echo "Usulan RW";
								}
								elseif($val['flag'] == 1)
								{
									echo "Diterima Kelurahan";
								}
								elseif($val['flag'] == 2)
								{
									echo "Ditolak Kelurahan";
								}
								elseif($val['flag'] == 3)
								{
									echo "Usulan Kelurahan";
								}
								elseif($val['flag'] >= 3 && $val['pengusul'] == 1)
								{
									echo "Diterima Kelurahan";
								}
								else
								{
									echo "Usulan Kelurahan";
								}
						echo '		
								</td>
								';
							}
						echo '
							</tr>
						';
						$num++;
						$kode									= $val['kode'];
					}
				?>
			</tbody>
			<tr>
				<td colspan="4" class="border" align="center">
					<b>
						JUMLAH
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php echo number_format($total); ?>
					</b>
				</td>
				<td class="border" align="right">
					<b>
						<?php //echo number_format($total_periode_ini, 2); ?>
					</b>
				</td>
				<?php	
					if($status == 5)
					{
						echo '
					<td class="border" align="right">
						<b>
							
						</b>
					</td>
					';
					}
				?>
			</tr>
		</table>
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="border no-border-right" width="60%">
				</td>
				<td class="border no-border-left" width="40%">
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo $tanggal_cetak; ?>
					<br />
						<b>
							<?php echo (isset($results['header']->jabatan_camat) ? $results['header']->jabatan_camat : null); ?>
						</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php echo (isset($results['header']->camat) ? $results['header']->camat : null); ?>
						</b>
					</u>
					<br />
					NIP <?php echo (isset($results['header']->nip_camat) ? $results['header']->nip_camat : null); ?>
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
					<td class="text-muted text-sm" align="right">
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>