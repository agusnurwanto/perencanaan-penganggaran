<?php 
	$title = null; 
	//foreach($results['header'] as $result);
	$title		= ucwords(strtolower($header['nama_unit']));
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			<?php echo 'RKA - ' . $title; ?>
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
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2" width="80" class="bordered">
						<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
					</th>
					<th class="bordered text-center">
						RENCANA KERJA DAN ANGGARAN
						<br />
						SATUAN KERJA PERANGKAT DAERAH
					</th>
					<th rowspan="2" width="80" class="bordered text-center">
						Formulir
						<br />
						RKA SKPD
						<br />
						2.2
					</th>
				</tr>
				<tr>
					<th class="text-center bordered">
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
						<br />
						TAHUN ANGGARAN : <?php echo get_userdata('year'); ?>
					</th>
				</tr>
				<tr>
					<td colspan="3" class="bordered">
						<table>
							<tr>
								<td width="200">
									Urusan Pemerintahan
								</td>
								<td width="20">
									:
								</td>
								<td>
									<?php echo $header['kode_urusan'] . '.' . sprintf('%02d', $header['kode_bidang']); ?>
								</td>
								<td>
									<?php echo $header['nama_urusan']; ?>
								</td>
							</tr>
							<tr>
								<td>
									Organisasi
								</td>
								<td>
									:
								</td>
								<td>
									<?php echo $header['kode_urusan'] . '.' . sprintf('%02d', $header['kode_bidang']); ?> . <?php echo $header['kode_urusan'] . '.' . sprintf('%02d', $header['kode_bidang']) . '.' . sprintf('%02d', $header['kode_unit']); ?>
								</td>
								<td>
									<?php echo ucwords(strtolower($header['nama_unit'])); ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</thead>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2" class="bordered" width="10%">
						KODE
					</th>
					<th rowspan="2" class="bordered" width="30%">
						URAIAN
					</th>
					<th rowspan="2" class="bordered" width="10%">
						LOKASI KEGIATAN
					</th>
					<th colspan="4" class="bordered" width="50%">
						JUMLAH
					</th>
				</tr>
				<tr>
					<th class="bordered">
						Pegawai
					</th>
					<th class="bordered">
						Barang & Jasa
					</th>
					<th class="bordered">
						Modal
					</th>
					<th class="bordered">
						Jumlah
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
					<th class="bordered">
						7=4+5+6
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_urusan						= 0;
					$id_bidang						= 0;
					$id_program						= 0;
					$id_kegiatan					= 0;
					$total_pegawai					= 0;
					$total_barang_jasa				= 0;
					$total_modal					= 0;
					$jumlah_anggaran				= 0;
					$total_anggaran					= 0;
					/*$id_rek_5						= 0;
					$id_belanja_sub					= 0;
					$id_belanja_rinc				= 0;*/
					foreach($results['data'] as $key => $val)
					{
						/*if($val['id_urusan'] != $id_urusan)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_urusan'] . '
										</b>
									</td>
									<td class="bordered">
										<b>
											' . $val['nama_urusan'] . '
										</b>
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered text-center">
										
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered text-right">
										<b>
											' . number_format($val['pagu']) . '
										</b>
									</td>
								</tr>
							';
						}
						if($val['id_bidang'] != $id_bidang)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_urusan'] . '.' . $val['kode_bidang'] . '
										</b>
									</td>
									<td style="padding-left:10px" class="bordered">
										<b>
											' . $val['nama_bidang'] . '
										</b>
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered text-center">
										
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered text-right">
										<b>
											' . number_format($val['pagu']) . '
										</b>
									</td>
								</tr>
							';
						}*/
						if($val['id_program'] != $id_program)
						{
							$jumlah_anggaran_program		= $val['pegawai_program'] + $val['barang_jasa_program'] + $val['modal_program'];
							echo '
								<tr>
									<td class="bordered">
										<b>' . $val['kode_urusan'] . '.' . $val['kode_bidang'] . '.' . sprintf('%02d', $val['kode_program']) . '</b>
									</td>
									<td style="padding-left:5px" class="bordered">
										<b>' . $val['nama_program'] . '</b>
									</td>
									<td class="bordered text-right">
										
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['pegawai_program']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['barang_jasa_program']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($val['modal_program']) . '</b>
									</td>
									<td class="bordered text-right">
										<b>' . number_format($jumlah_anggaran_program) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_kegiatan'] != $id_kegiatan)
						{
							$total_pegawai					+= $val['pegawai'];		
							$total_barang_jasa				+= $val['barang_jasa'];		
							$total_modal					+= $val['modal'];
							$jumlah_anggaran				= $val['pegawai'] + $val['barang_jasa'] + $val['modal'];
							$total_anggaran					+= $jumlah_anggaran;
							echo '
								<tr>
									<td class="bordered">
										' . $val['kode_urusan'] . '.' . $val['kode_bidang'] . '.' . sprintf('%02d', $val['kode_program']) . '.' . sprintf('%02d', $val['kode_kegiatan']) . '
									</td>
									<td style="padding-left:10px" class="bordered">
										' . $val['nama_kegiatan'] . '
									</td>
									<td class="bordered">
										' . $val['map_address'] . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['pegawai']) . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['barang_jasa']) . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['modal']) . '
									</td>
									<td class="bordered text-right">
										' . number_format($jumlah_anggaran) . '
									</td>
								</tr>
							';
						}
						$id_urusan					= $val['id_urusan'];
						$id_bidang					= $val['id_bidang'];
						$id_program					= $val['id_program'];
						$id_kegiatan				= $val['id_kegiatan'];
					}
				?>
			</tbody>
			<tr>
				<td colspan="3" class="bordered text-center">
					<b>JUMLAH</b>
				</td>
				<td class="bordered text-right">
					<b><?php echo number_format($total_pegawai); ?></b>
				</td>
				<td class="bordered text-right">
					<b><?php echo number_format($total_barang_jasa); ?></b>
				</td>
				<td class="bordered text-right">
					<b><?php echo number_format($total_modal); ?></b>
				</td>
				<td class="bordered text-right">
					<b><?php echo number_format($total_anggaran); ?></b>
				</td>
			</tr>
			<tr>
				<td colspan="2" class="bordered">
					Keterangan:
					<br />
				</td>
				<td colspan="5" class="text-center bordered">
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo date('d'); ?> <?php echo phrase(date('F')); ?> <?php echo date('Y'); ?>
					<br />
					<b><?php echo strtoupper($header['nama_jabatan']);?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo strtoupper($header['nama_pejabat']);?></b></u>
					<br />
					<?php echo 'NIP. '. $header['nip_pejabat'];?>
				</td>
			</tr>
		</table>
		<htmlpagefooter name="footer">
			<table class="print">
				<tfoot>
					<tr>
						<td class="text-sm">
							<i>
								<?php //echo $result['kd_urusan'] . '.' . sprintf('%02d', $result['kd_bidang']); ?> <?php //echo $result['kd_urusan'] . '.' . sprintf('%02d', $result['kd_bidang']) . '.' . sprintf('%02d', $result['kd_unit']); ?>. <?php //echo $model['kd_model'] . '. ' . $model['nm_model']; ?>
							</i>
						</td>
						<td class="text-muted text-sm text-right">
							
						</td>
					</tr>
				</tfoot>
			</table>
		</htmlpagefooter>
	</body>
</html>