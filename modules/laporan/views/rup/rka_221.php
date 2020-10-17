<?php 
	$title = null; 
	//foreach($results['header'] as $result);
	$title		= ucwords(strtolower($results['header'][0]['nama_unit'])) . ' - ' . $results['header'][0]['nama_kegiatan'];
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
				font-size: 14px
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
				font-size: 12px
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
				font-size: 14px;
				white-space: nowrap
			}
			td
			{
				font-size: 14px;
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
			h5
			{
				font-size: 14px
			}
			h1, h2, h3, h4, h5
			{
				margin-top: 0;
				margin-bottom: 0
			}
			.relative
			{
				position: relative
			}
			.absolute
			{
				position: absolute
			}
		</style>
	</head>
	<body>
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2" width="100" class="bordered">
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
						2.2.1
					</th>
				</tr>
				<tr>
					<th class="bordered" align="center">
						<h4>
							<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
						</h4>
						<h5>
							TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
						</h5>
					</th>
				</tr>
				<tr>
					<td colspan="3" class="bordered">
						<table>
							<tr>
								<td width="20%" class="text-sm">
									<b>Urusan Pemerintahan</b>
								</td>
								<td width="2%" class="text-sm">
									:
								</td>
								<td width="20%" class="text-sm">
									<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?>
								</td>
								<td width="58%" class="text-sm">
									<?php echo $results['header'][0]['nama_urusan']; ?> <?php //echo $results['header'][0]['nama_bidang']; ?>
								</td>
							</tr>
							<tr>
								<td class="text-sm">
									<b>Organisasi</b>
								</td>
								<td class="text-sm">
									:
								</td>
								<td class="text-sm">
									<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?> . <?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']) . '.' . sprintf('%02d', $results['header'][0]['kode_unit']); ?>
								</td>
								<td class="text-sm">
									<?php echo ucwords(strtolower($results['header'][0]['nama_unit'])); ?>
								</td>
							</tr>
							<tr>
								<td class="text-sm">
									<b>Program</b>
								</td>
								<td class="text-sm">
									:
								</td>
								<td class="text-sm">
									<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?> . <?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']) . '.' . sprintf('%02d', $results['header'][0]['kode_unit']) . ' . ' . sprintf('%02d', $results['header'][0]['kode_program']); ?>
								</td>
								<td class="text-sm">
									<?php echo $results['header'][0]['nama_program']; ?>
								</td>
							</tr>
							<tr>
								<td class="text-sm">
									<b>Kegiatan</b>
								</td>
								<td class="text-sm">
									:
								</td>
								<td class="text-sm">
									<?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']); ?> . <?php echo $results['header'][0]['kode_urusan'] . '.' . sprintf('%02d', $results['header'][0]['kode_bidang']) . '.' . sprintf('%02d', $results['header'][0]['kode_unit']) . ' . ' . sprintf('%02d', $results['header'][0]['kode_program']) . ' . ' . sprintf('%02d', $results['header'][0]['kode_kegiatan']); ?>
								</td>
								<td class="text-sm">
									<?php echo $results['header'][0]['nama_kegiatan']; ?>
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="bordered">
						<table>
							<tr>
								<td width="20%" class="text-sm">
									<b>Lokasi Kegiatan</b>
								</td>
								<td width="2%" class="text-sm">
									:
								</td>
								<td width="78%" class="text-sm">
									<?php //echo $results['header'][0]['map_address']; ?> 
									<?php 
										if($results['header'][0]['alamat_detail'] != "")
										{
											echo $results['header'][0]['alamat_detail'];
										}
									?>
								</td>
							</tr>
							<tr>
								<td class="text-sm">
									<b>Sumber Dana</b>
								</td>
								<td class="text-sm">
									:
								</td>
								<td class="text-sm">
									<?php echo (isset($results['sumber_dana']->nama_sumber_dana) ? $results['sumber_dana']->nama_sumber_dana : NULL); ?> 
								</td>
							</tr>
						</table>
					</td>
				</tr>
				<tr>
					<td colspan="3" class="bordered">
						<table>
							<!--<tr>
								<td width="20%" class="text-sm">
									<b>Jumlah Tahun n - 1</b>
								</td>
								<td width="2%" class="text-sm">
									:
								</td>
								<td width="3%" class="text-sm">
									Rp
								</td>
								<td class="text-right text-sm" width="17%">
									0.00
								</td>
								<td width="58%" class="text-sm">
									<i>( <?php //echo 'Nol'; ?> Rupiah )</i>
								</td>
							</tr>-->
							<tr>
								<td width="20%" class="text-sm">
									<b>Jumlah Tahun n</b>
								</td>
								<td width="2%" class="text-sm">
									:
								</td>
								<td width="3%" class="text-sm">
									Rp
								</td>
								<td width="17%" class="text-right text-sm">
									<?php echo (isset($results['belanja'][0]['subtotal_rek_1']) ? number_format($results['belanja'][0]['subtotal_rek_1']) : 0); ?>
								</td>
								<td width="58%" class="text-sm">
									<i>(<?php echo (isset($results['belanja'][0]['subtotal_rek_1']) && $results['belanja'][0]['subtotal_rek_1'] > 0 ? spell_number($results['belanja'][0]['subtotal_rek_1']) : 'Nol'); ?> Rupiah )</i>
								</td>
							</tr>
							<tr>
								<td class="text-sm">
									<b>Jumlah Tahun n + 1</b>
								</td>
								<td class="text-sm">
									:
								</td>
								<td>
									Rp
								</td>
								<td class="text-right text-sm">
									<?php echo number_format($results['header'][0]['pagu_1']); ?> 
								</td>
								<td class="text-sm">
									<i>(<?php echo (isset($results['header'][0]['pagu_1']) && $results['header'][0]['pagu_1'] > 0 ? spell_number($results['header'][0]['pagu_1']) : 'Nol'); ?> Rupiah )</i>
								</td>
							</tr>
						</table>
					</td>
				</tr>
			</thead>
		</table>
		<table class="table">
			<tr>
				<th colspan="3" class="bordered no-border-top">
					INDIKATOR & TOLOK UKUR KINERJA BELANJA LANGSUNG
				</th>
			</tr>
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
			<?php
				$cek_capaian				= (isset($results['header'][0]['capaian_program']) ? $results['header'][0]['capaian_program'] : 0);
				$capaian_program			= 0 ;
				foreach($results['capaian_program'] as $key => $val)
				{
					$checked				= false;
					if($cek_capaian == $val['id'])
					{
						$checked			= true;
					}
			?>
						<tr>
							<td class="bordered text-sm">
							<?php
								if ($capaian_program == 0)
								{
							?>
									<b>CAPAIAN PROGRAM</b>
							<?php
								}
							?>
							</td>
							<td class="bordered text-sm">
								<?php echo ($checked ? '<b>&#8730;</b> ' . $val['tolak_ukur'] : '<div style="margin-left: 14px">' . $val['tolak_ukur'] . '</div>'); ?>
							</td>
							<td class="bordered text-sm">
								<?php //echo fmod($val['target'], 1);exit(); ?>
								<?php //echo (fmod($val['target'], 1) !== 0.00 ? number_format($val['target'], 2) : ($val['target'] == 0.00 ? number_format($val['target'], 0) : '')); ?> 
								<?php echo (fmod($val['target'], 1) !== 0.00 ? number_format($val['target'], 2) : (number_format($val['target']) == 0 ? '' : number_format($val['target'])) ); ?> 
								<?php echo $val['satuan_target']; ?>
								<?php //echo (fmod($val['target'], 1) !== 0.00 ? number_format($val['target'], 2) : ''); ?> <?php //echo $val['satuan_target']; ?>
							</td>
						</tr>
			<?php
					$capaian_program += 1;
				}
			?>
			<?php
				$masukan			= null;
				$keluaran			= null;
				$hasil				= null;
				$kd_indikator		= 0;
				//print_r($results['indikator']);exit;
				$masukan	.= '<td class="bordered text-sm" width="53%">
									Jumlah Dana
								</td>
								<td class="bordered text-sm" width="25%">
									Rp ' . number_format($results['header'][0]['pagu']) . '
								</td>
								';
				foreach($results['indikator'] as $key => $val)
				{
				//print_r($results['indikator']);exit;
					if($val['jns_indikator'] == 1)
					{
						$masukan	.= ($masukan ? '<td class="bordered text-sm"></td>' : '') . '<td class="bordered text-sm">' . $val['tolak_ukur'] . '</td><td class="bordered text-sm">' . $val['satuan'] . ' ' . number_format($val['target']) . '</td></tr><tr>';
					}									
					elseif($val['jns_indikator'] == 2)
					{
						if($results['header'][0]['pilihan'] == 0 or $results['header'][0]['pilihan'] == 1)
						{
							$keluaran	.= ($keluaran ? '<td class="bordered text-sm"></td>' : '') . '<td class="bordered text-sm">' . $val['tolak_ukur'] . '</td><td class="bordered text-sm">' . (fmod($val['target'], 1) !== 0.00 ? number_format($val['target'], 2) : number_format($val['target'], 0)) . ' ' . $val['satuan'] . '</td></tr><tr>';
						}
					}
					elseif($val['jns_indikator'] == 3)
					{
						if($results['header'][0]['pilihan'] == 0 or $results['header'][0]['pilihan'] == 1)
						{
							$hasil		.= ($hasil ? '<td class="bordered text-sm"></td>' : '') . '<td class="bordered text-sm">' . $val['tolak_ukur'] . '</td><td class="bordered text-sm">' . (fmod($val['target'], 1) !== 0.00 ? number_format($val['target'], 2) : number_format($val['target'], 0)) . ' ' . $val['satuan'] . '</td></tr><tr>';
						}
					}									
					$kd_indikator	= $val['kd_indikator'];
				}
				//print_r($keluaran);exit;
			?>
			<tr>
				<td class="bordered text-sm" width="22%">
					<b>MASUKAN</b>
				</td>
				<?php echo ($masukan ? $masukan : '<td colspan="2" class="bordered text-sm"></td>'); ?>
				<!--<td class="bordered">
					Jumlah Dana
				</td>								
				<td class="bordered">
					Rp. <?php //echo (isset($results['belanja'][0]['subtotal_rek_1']) ? number_format($results['belanja'][0]['subtotal_rek_1']) : 0); ?>									
				</td>-->
			</tr>
			<tr>
				<td class="bordered text-sm">
					<b>KELUARAN</b>
				</td>
				<?php echo ($keluaran ? $keluaran : '<td colspan="2" class="bordered text-sm"></td>'); ?>
			</tr>
			<tr>
				<td class="bordered text-sm">
					<b>HASIL</b>
				</td>
				<?php echo ($hasil ? $hasil : '<td colspan="2" class="bordered text-sm"></td>'); ?>
			</tr>
			<tr>
				<td class="bordered no-border-bottom text-sm" colspan="3">
					<b>Kelompok Sasaran Kegiatan : <?php echo $results['header'][0]['kelompok_sasaran']; ?></b>
				</td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th colspan="6" class="bordered text-sm">
						RINCIAN ANGGARAN BELANJA LANGSUNG MENURUT KEGIATAN SATUAN KERJA PERANGKAT DAERAH
					</th>
				</tr>
				<tr>
					<th rowspan="2" class="bordered text-sm" width="11%">
						KODE
						<br />
						REKENING
					</th>
					<th rowspan="2" class="bordered text-sm" width="46%">
						URAIAN
					</th>
					<th colspan="3" class="bordered text-sm" width="30%">
						RINCIAN PERHITUNGAN
					</th>
					<th rowspan="2" class="bordered text-sm" width="13%">
						JUMLAH
						<br />
						(Rp)
					</th>
				</tr>
				<tr>
					<th class="bordered text-sm">
						Volume
					</th>
					<th class="bordered text-sm">
						Satuan
					</th>
					<th class="bordered text-sm">
						Harga Satuan
					</th>
				</tr>
				<tr bgcolor="gray">
					<th class="bordered text-sm">
						1
					</th>
					<th class="bordered text-sm">
						2
					</th>
					<th class="bordered text-sm">
						3
					</th>
					<th class="bordered text-sm">
						4
					</th>
					<th class="bordered text-sm">
						5
					</th>
					<th class="bordered text-sm">
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
					$id_belanja_sub					= 0;
					$id_belanja_rinc				= 0;
					foreach($results['belanja'] as $key => $val)
					{
						if($val['id_rek_1'] != $id_rek_1)
						{
							echo '
								<tr>
									<td class="bordered text-sm">
										<b>' . $val['kd_rek_1'] . '</b>
									</td>
									<td class="bordered text-sm">
										<b>' . $val['nm_rek_1'] . '</b>
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-center text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										<b>' . number_format($val['subtotal_rek_1']) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_rek_2'] != $id_rek_2)
						{
							echo '
								<tr>
									<td class="bordered text-sm">
										<b>' . $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '</b>
									</td>
									<td style="padding-left:5px" class="bordered text-sm">
										<b>' . $val['nm_rek_2'] . '</b>
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-center text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										<b>' . number_format($val['subtotal_rek_2']) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_rek_3'] != $id_rek_3)
						{
							echo '
								<tr>
									<td class="bordered text-sm">
										<b>' . $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '.' . $val['kd_rek_3'] . '</b>
									</td>
									<td style="padding-left:8px" class="bordered text-sm">
										<b>' . $val['nm_rek_3'] . '</b>
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-center text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										<b>' . number_format($val['subtotal_rek_3']) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_rek_4'] != $id_rek_4)
						{
							echo '
								<tr>
									<td class="bordered text-sm">
										<b>' . $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '.' . $val['kd_rek_3'] . '.' . sprintf('%02d', $val['kd_rek_4']) . '</b>
									</td>
									<td style="padding-left:11px" class="bordered text-sm">
										<b>' . $val['nm_rek_4'] . '</b>
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-center text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										<b>' . number_format($val['subtotal_rek_4']) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_rek_5'] != $id_rek_5)
						{
							echo '
								<tr>
									<td class="bordered text-sm">
										<b>' . $val['kd_rek_1'] . '.' . $val['kd_rek_2'] . '.' . $val['kd_rek_3'] . '.' . sprintf('%02d', $val['kd_rek_4']) . '.' . sprintf('%02d', $val['kd_rek_5']) . '</b>
									</td>
									<td style="padding-left:14px" class="bordered text-sm">
										<b>' . $val['nm_rek_5'] . '</b>
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-center text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										<b>' . number_format($val['subtotal_rek_5']) . '</b>
									</td>
								</tr>
							';
						}
						if($val['id_belanja_sub'] != $id_belanja_sub)
						{
							echo '
								<tr>
									<td class="bordered text-sm">
										
									</td>
									<td style="padding-left:17px" class="bordered text-sm">
										' . $val['nama_sub'] . '
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										
									</td>
									<td class="bordered text-right text-sm">
										' . number_format($val['subtotal_rinc']) . '
									</td>
								</tr>
							';
						}
						if($val['id_belanja_rinc'] != $id_belanja_rinc)
						{
							echo '
								<tr>
									<td class="bordered text-sm">
										
									</td>
									<td style="padding-left:20px" class="bordered text-sm">
										- ' . $val['nama_rinc'] . ' (' . (0 != $val['vol_1'] ? (0 != $val['vol_2'] || 0 != $val['vol_3'] ? number_format($val['vol_1']) . ' ' . $val['satuan_1'] . ' x ' : number_format($val['vol_1']) . ' ' . $val['satuan_1']) : null) . (0 != $val['vol_2'] ? (0 != $val['vol_3'] ? number_format($val['vol_2']) . ' ' . $val['satuan_2'] . ' x ' : number_format($val['vol_2']) . ' ' . $val['satuan_2']) : null) . (0 != $val['vol_3'] ? number_format($val['vol_3']) . ' ' . $val['satuan_3'] : null) . ')
									</td>
									<td class="bordered text-center text-sm">
										' . number_format($val['vol_123'])  . '
									</td>
									<td class="bordered text-center text-sm">
										' . $val['satuan_123'] . '
									</td>
									<td class="bordered text-right text-sm">
										' . number_format($val['nilai']) . '
									</td>
									<td class="bordered text-right text-sm">
										' . number_format($val['total']) . '
									</td>
								</tr>
							';
						}
						$id_rek_1					= $val['id_rek_1'];
						$id_rek_2					= $val['id_rek_2'];
						$id_rek_3					= $val['id_rek_3'];
						$id_rek_4					= $val['id_rek_4'];
						$id_rek_5					= $val['id_rek_5'];
						$id_belanja_sub				= $val['id_belanja_sub'];
						$id_belanja_rinc			= $val['id_belanja_rinc'];
					}
				?>
			</tbody>
		</table>
		<table class="table" width="100%" style="page-break-inside:avoid">
			<tbody>
				<tr>
					<td colspan="3" class="bordered">
						Keterangan : <?php echo ($results['header'][0]['pilihan'] == 1 ? ' Model ' . $results['header'][0]['nm_model'] : NULL); ?>
						<br />
						<img src="<?php echo get_image('___qrcode', sha1(current_page() . SALT) . '.png'); ?>" alt="..." width="100" />
					</td>
					<td colspan="2" class="text-center bordered">
						<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo ($results['tanggal']->tanggal_rka ? date_indo($results['tanggal']->tanggal_rka) : date('d') . '' . phrase(date('F')) . '' . date('Y') ); ?>
						<br />
						<b><?php 
							echo strtoupper($results['header'][0]['nama_jabatan']);	
							?></b>
						<br />
						<br />
						<br />
						<br />
						<br />
						<u><b><?php
									echo $results['header'][0]['nama_pejabat'];
								?></b></u>
						<br />
						<?php
							echo 'NIP. '. $results['header'][0]['nip_pejabat'];
						?>
					</td>
				</tr>
				<tr>
					<th colspan="5" class="bordered">
						<b>TIM ANGGARAN PEMERINTAH DAERAH</b>
					</th>
				</tr>
				<tr>
					<th class="bordered text-sm" width="3%">
						NO
					</th>
					<th class="bordered text-sm" width="28%">
						NAMA
					</th>
					<th class="bordered text-sm" width="22%">
						NIP
					</th>
					<th class="bordered text-sm" width="30%">
						JABATAN
					</th>
					<th class="bordered text-sm" width="20%">
						TANDA TANGAN
					</th>
				</tr>
				<?php
				foreach($results['tim_anggaran'] as $key => $val)
				{
					$id							= 'ttd_' . $val['id'];
					$ttd						= (isset($results['approval']->$id) && 1 == $results['approval']->$id && $val['ttd'] ? '<img src="' . get_image('anggaran', $val['ttd']) . '" height="70" class="ttd absolute" style="' . (1 == $key ? 'top:0;right:0' : (2 == $key ? 'top:-20px;left:20px' : 'top:-20px')) . '" />' : null);
					echo '
						<tr>
							<td class="bordered text-center text-sm">
								' . $val['kode'] .'
							</td>
							<td class="bordered text-sm">
								' . $val['nama_tim'] .'
							</td>
							<td class="bordered text-sm">
								' . $val['nip_tim'] .'
							</td>
							<td class="bordered text-sm">
								' . $val['jabatan_tim'] .'
							</td>
							<td class="bordered relative">
								' . $ttd . '
							</td>
						</tr>
						';
				}			
				?>
			</tbody>
		</table>
		<htmlpagefooter name="footer">
			<table class="table print">
				<tfoot>
					<tr>
						<td class="bordered text-center text-sm" colspan="4">
							<b>PARAF TIM ASISTENSI</b>
						</td>
					</tr>
					<tr>
						<td class="bordered text-sm" width="33%">
							<b>1. BAPPEDA </b>
							<?php echo (isset($results['approval']->perencanaan) && 1 == $results['approval']->perencanaan ? '(Disetujui Oleh :<br /><b>' . $results['approval']->nama_operator_perencanaan . '</b>)' : ''); ?>
							<?php //echo (isset($results['approval']->perencanaan) && 1 == $results['approval']->perencanaan ? '(Disetujui Oleh <b>' . $results['approval']->nama_operator_perencanaan . '</b> pada ' . date_indo($results['approval']->waktu_verifikasi_perencanaan, 3, '-') . ')' : ''); ?>
						</td>
						<td class="bordered text-sm" width="33%">
							<b>2. BPKAD </b>
							<?php echo (isset($results['approval']->keuangan) && 1 == $results['approval']->keuangan ? '(Disetujui Oleh :<br /><b>' . $results['approval']->nama_operator_keuangan . '</b>)' : ''); ?>
							<?php //echo (isset($results['approval']->keuangan) && 1 == $results['approval']->keuangan ? '(Disetujui Oleh <b>' . $results['approval']->nama_operator_keuangan . '</b> pada ' . date_indo($results['approval']->waktu_verifikasi_keuangan, 3, '-') . ')' : ''); ?>
						</td>
						<td class="bordered text-sm" width="34%" colspan="2">
							<b>3. Bagian Pembangunan Setda </b>
							<?php echo (isset($results['approval']->setda) && 1 == $results['approval']->setda ? '(Disetujui Oleh :<br /><b>' . $results['approval']->nama_operator_setda . '</b>)' : ''); ?>
							<?php //echo (isset($results['approval']->setda) && 1 == $results['approval']->setda ? '(Disetujui Oleh <b>' . $results['approval']->nama_operator_setda . '</b> pada ' . date_indo($results['approval']->waktu_verifikasi_setda, 3, '-') . ')' : ''); ?>
						</td>
					</tr>
					<tr>
						<td class="bordered text-sm no-border-right" colspan="3">
							<i>
								RKA 2.2.1 - <?php echo (isset($results['header'][0]['nama_kegiatan']) ? $results['header'][0]['nama_kegiatan'] : '-'); ?>
								<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
							</i>
						</td>
						<td class="bordered text-sm text-right no-border-left">
							<?php echo phrase('page') . ' {PAGENO} dari {nb}'; ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</htmlpagefooter>
	</body>
</html>