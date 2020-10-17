<!DOCTYPE html>
<html>
	<head>
		<title>
			Musrenbang Kelurahan
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
	<?php
		if($this->input->get('status') != null)
		{
			$status 				= $this->input->get('status');
			if($status == 0)
			{
				$status_text	= '<b>Usulan</b>';
			}
			elseif($status == 1)
			{
				$status_text	= '<b>Diterima</b>';
			}
			elseif($status == 2)
			{
				$status_text	= '<b>Ditolak</b>';
			}
		} 
	?>
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
						HASIL MUSRENBANG KELURAHAN
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<tr>
				<td width="180">
					Kecamatan
				</td>
				<td width="1">
					:
				</td>
				<td width="100">
					<?php echo sprintf('%02d', $header['kode_kecamatan']); ?>
				</td>
				<td>
					<?php echo $header['nama_kecamatan']; ?>
				</td>
			</tr>
			<tr>
				<td>
					Kelurahan
				</td>
				<td>
					:
				</td>
				<td>
					<?php echo sprintf('%02d', $header['kode_kecamatan']) . '.' . sprintf('%02d', $header['kode_kelurahan']); ?>
				</td>
				<td>
					<?php echo $header['nama_kelurahan']; ?>
				</td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered">
						NO
					</th>
					<th class="bordered">
						RW
					</th>
					<th class="bordered">
						RT
					</th>
					<th class="bordered">
						PEKERJAAN
					</th>
					<th class="bordered">
						ALAMAT
					</th>
					<th class="bordered">
						NILAI (Rp)
					</th>
					<th class="bordered">
						URGENSI
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$num									= 1;
					$nilai									= 0;
					$total									= 0;
					//$total_periode_lalu					= 0;
					//$total_periode_ini					= 0;
					//$total_sd_periode_ini					= 0;
					foreach($results['data'] as $key => $val)
					{
						$status 							= $this->input->get('status');
						if($status == 1)
						{
							$nilai							= $val['nilai_kelurahan'];
							$total							+= $val['nilai_kelurahan'];
						}
						elseif($status == 2)
						{
							$nilai							= $val['nilai_kelurahan'];
							$total							+= $val['nilai_kelurahan'];
						}
						else
						{
							$nilai							= $val['nilai_usulan'];
							$total							+= $val['nilai_usulan'];
						}
						//$total_periode_lalu				+= $val['periode_lalu'];
						//$total_periode_ini				+= $val['periode_ini'];
						//$total_sd_periode_ini				+= $val['periode_lalu'] + $val['periode_ini'];
						/*$target								= json_decode($val['variabel_usulan'], true);
						$variabel							= $this->model->get_where('ref__musrenbang_variabel', array('id_musrenbang_jenis_pekerjaan' => $val['jenis_pekerjaan']))->result_array();
						$variabel_usulan					= null;
						if($target)
						{
							foreach($target as $key_x => $val_x)
							{
								if($variabel)
								{
									foreach($variabel as $key_y => $val_y)
									{
										if($key_y == $key_x)
										{
											$variabel_usulan	.= (isset($key_y[$key_x]) ? $variabel[$key_y] : null) . ' ' . (isset($variabel[$key_y]) ? $variabel[$key_y] : null);
										}
									}
								}
							}
						}*/
						echo '
							<tr>
								<td class="bordered text-center">
									' . $num . '
								</td>
								<td class="bordered text-center">
									' . $val['rw'] . '
								</td>
								<td class="bordered text-center">
									' . $val['rt'] . '
								</td>
								<td class="bordered">
									' . $val['nama_pekerjaan'] . '
								</td>
								<td class="bordered">
									' . $val['map_address'] . '
								</td>
								<td class="bordered text-right">
									' . number_format($nilai) . '
								</td>
								<td class="bordered">
									' . $val['urgensi'] . '
								</td>
							</tr>
						';
						$num++;
					}
				?>
			</tbody>
			<tr>
				<td colspan="5" class="bordered text-center">
					<b>
						JUMLAH
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php echo number_format($total); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($total_periode_ini, 2); ?>
					</b>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="text-center" width="50%">
					<!-- Mengetahui,
					<br />
					<b>
						<?php //echo $header['jabatan_kpa']; ?>
					</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php //echo $header['kpa']; ?>
						</b>
					</u>
					<br />
					NIP <?php //echo $header['nip_kpa']; ?> -->
				</td>
				<td class="text-center" width="50%">
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo $tanggal_cetak; ?>,
					<br />
						<b>
							<?php echo $header['jabatan_lurah']; ?>
						</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php echo $header['nama_lurah']; ?>
						</b>
					</u>
					<br />
					NIP <?php echo $header['nip_lurah']; ?>
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