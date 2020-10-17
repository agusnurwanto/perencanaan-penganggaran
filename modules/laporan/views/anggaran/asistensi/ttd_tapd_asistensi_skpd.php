<!DOCTYPE html>
<html>
	<head>
		<title>
			Rekapitulasi TTD TAPD per SKPD
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
		<table align="center">
			<tr>
				<td>
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</td>
				<td align="center" width="100%" colspan="13">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						EVALUASI TTD TAPD RKA BELANJA LANGSUNG PER SKPD
					</h4>
					<?php 
						echo 
						(isset($results['bidang_bappeda']->nama_bidang) && $results['bidang_bappeda']->nama_bidang != 'all' ?
						'<h4>
							' . strtoupper($results['bidang_bappeda']->nama_bidang) . '
						</h4>
						'
						: NULL)
					?>
					<h4>
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="5%" rowspan="2">
						KODE
					</th>
					<th class="bordered" width="30%" rowspan="2">
						SKPD
					</th>
					<th class="bordered" width="10%" colspan="3">
						KEGIATAN
					</th>
					<th class="bordered" width="10%" colspan="3">
						BAPPEDA
					</th>
					<th class="bordered" width="10%" colspan="3">
						BPKAD
					</th>
					<th class="bordered" width="10%" colspan="3">
						PEMBANGUNAN
					</th>
					<th class="bordered" width="10%" colspan="3">
						SELESAI
					</th>
					<th class="bordered" width="10%" colspan="3">
						BELUM SELESAI
					</th>
					<th class="bordered" width="5%" rowspan="2">
						%
					</th>
				</tr>
				<tr>
					<th class="bordered text-sm">
						BLPU
					</th>
					<th class="bordered text-sm">
						BL UR
					</th>
					<th class="bordered text-sm">
						TOTAL
					</th>
					<th class="bordered text-sm">
						BLPU
					</th>
					<th class="bordered text-sm">
						BL UR
					</th>
					<th class="bordered text-sm">
						TOTAL
					</th>
					<th class="bordered text-sm">
						BLPU
					</th>
					<th class="bordered text-sm">
						BL UR
					</th>
					<th class="bordered text-sm">
						TOTAL
					</th>
					<th class="bordered text-sm">
						BLPU
					</th>
					<th class="bordered text-sm">
						BL UR
					</th>
					<th class="bordered text-sm">
						TOTAL
					</th>
					<th class="bordered text-sm">
						BLPU
					</th>
					<th class="bordered text-sm">
						BL UR
					</th>
					<th class="bordered text-sm">
						TOTAL
					</th>
					<th class="bordered text-sm">
						BLPU
					</th>
					<th class="bordered text-sm">
						BL UR
					</th>
					<th class="bordered text-sm">
						TOTAL
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					/*$plafon_bl									= 0;
					$total_plafon_blpu							= 0;
					$total_plafon_blu							= 0;
					$total_plafon_bl							= 0;*/
					
					$jumlah_rka_bl								= 0;
					$total_jumlah_blpu							= 0;
					$total_jumlah_blu							= 0;
					$total_jumlah_bl							= 0;
					
					$perencanaan_bl								= 0;
					$total_perencanaan_blpu						= 0;
					$total_perencanaan_blu						= 0;
					$total_perencanaan_bl						= 0;
					
					$keuangan_bl								= 0;
					$total_keuangan_blpu						= 0;
					$total_keuangan_blu							= 0;
					$total_keuangan_bl							= 0;
					
					$setda_bl									= 0;
					$total_setda_blpu							= 0;
					$total_setda_blu							= 0;
					$total_setda_bl								= 0;
					
					$selesai_bl									= 0;
					$total_selesai_blpu							= 0;
					$total_selesai_blu							= 0;
					$total_selesai_bl							= 0;
					
					$belum_selesai_blpu							= 0;
					$belum_selesai_blu							= 0;
					$belum_selesai_bl							= 0;
					
					$total_belum_selesai_blpu					= 0;
					$total_belum_selesai_blu					= 0;
					$total_belum_selesai_bl						= 0;
					
					$persen										= 0;
					//$total_persen								= 0;
					foreach($results['data'] as $key => $val)
					{
						$jumlah_rka_bl							= $val['jumlah_kegiatan_skpd_blpu'] + $val['jumlah_kegiatan_skpd_blu'];
						$perencanaan_bl							= $val['perencanaan_blpu'] + $val['perencanaan_blu'];
						$keuangan_bl							= $val['keuangan_blpu'] + $val['keuangan_blu'];
						$setda_bl								= $val['setda_blpu'] + $val['setda_blu'];
						$selesai_bl								= $val['selesai_blpu'] + $val['selesai_blu'];
						
						$belum_selesai_blpu						= $val['jumlah_kegiatan_skpd_blpu'] - 
																$val['selesai_blpu'];
						$belum_selesai_blu						= $val['jumlah_kegiatan_skpd_blu'] - 
																$val['selesai_blu'];
						$belum_selesai_bl						= $jumlah_rka_bl - $selesai_bl;
						
						$persen									= $selesai_bl / ($jumlah_rka_bl > 0 ? $jumlah_rka_bl : 1);
						echo '
							<tr>
								<td class="bordered" align="center">										
									' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '
								</td>
								<td class="bordered" style="padding-left:5px">
									' . $val['nm_unit'] . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['jumlah_kegiatan_skpd_blpu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['jumlah_kegiatan_skpd_blu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($jumlah_rka_bl) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['perencanaan_blpu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['perencanaan_blu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($perencanaan_bl) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['keuangan_blpu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['keuangan_blu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($keuangan_bl) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['setda_blpu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['setda_blu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($setda_bl) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['selesai_blpu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($val['selesai_blu']) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($selesai_bl) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($belum_selesai_blpu) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($belum_selesai_blu) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($belum_selesai_bl) . '
								</td>
								<td class="bordered" align="right">
									' . number_format($persen * 100, 2) . '
								</td>
							</tr>
						';
					//	$total_plafon_blpu					+= $val['plafon_anggaran_skpd_blpu'];
					//	$total_plafon_blu					+= $val['plafon_anggaran_skpd_blu'];
					//	$total_plafon_bl					+= $plafon_bl;
						
						$total_jumlah_blpu					+= $val['jumlah_kegiatan_skpd_blpu'];
						$total_jumlah_blu					+= $val['jumlah_kegiatan_skpd_blu'];
						$total_jumlah_bl					+= $jumlah_rka_bl;
						
						$total_perencanaan_blpu				+= $val['perencanaan_blpu'];
						$total_perencanaan_blu				+= $val['perencanaan_blu'];
						$total_perencanaan_bl				+= $perencanaan_bl;
						
						$total_keuangan_blpu				+= $val['keuangan_blpu'];
						$total_keuangan_blu					+= $val['keuangan_blu'];
						$total_keuangan_bl					+= $keuangan_bl;
						
						$total_setda_blpu					+= $val['setda_blpu'];
						$total_setda_blu					+= $val['setda_blu'];
						$total_setda_bl						+= $setda_bl;
						
						$total_selesai_blpu					+= $val['selesai_blpu'];
						$total_selesai_blu					+= $val['selesai_blu'];
						$total_selesai_bl					+= $selesai_bl;
						
						$total_belum_selesai_blpu			+= $belum_selesai_blpu;
						$total_belum_selesai_blu			+= $belum_selesai_blu;
						$total_belum_selesai_bl				+= $belum_selesai_bl;
					}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="bordered" align="center">
					<b>JUMLAH</b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_jumlah_blpu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_jumlah_blu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_jumlah_bl); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_perencanaan_blpu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_perencanaan_blu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_perencanaan_bl); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_keuangan_blpu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_keuangan_blu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_keuangan_bl); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_setda_blpu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_setda_blu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_setda_bl); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_selesai_blpu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_selesai_blu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_selesai_bl); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_belum_selesai_blpu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_belum_selesai_blu); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_belum_selesai_bl); ?></b>
				</td>
				<td class="bordered" align="right">
					<b><?php echo number_format($total_selesai_bl / ($total_jumlah_bl > 0 ? $total_jumlah_bl : 1) * 100, 2); ?></b>
				</td>
			</tr>
		</table>
		<br />
		<br />
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm" colspan="2">
						<i>
							Evaluasi Asistensi RKA Belanja Langsung <?php echo get_userdata('year'); ?>
							<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
						</i>
					</td>
					<td class="text-muted text-sm" align="right" colspan="4">
						<?php echo phrase('page') . ' {PAGENO} dari {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>