<!DOCTYPE html>
<html>
	<head>
		<title>
			Rencana Kerja Awal SKPD
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
				<td align="center" width="100%">
					<h4>
						<?php
						if($this->input->get('judul') !== null)
						{
							echo $this->input->get('judul');
						}
						else
						{
							echo 'KOMPILASI PROGRAM DAN PAGU INDIKATIF TIAP PERANGKAT DAERAH';
						}
						?>
						
					</h4>
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year'); ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" rowspan="2">
						KODE
					</th>
					<th class="bordered" rowspan="2">
						SKPD / PROGRAM
					</th>
					<th class="bordered" rowspan="2">
						KEGIATAN
					</th>
					<th class="bordered" colspan="2">
						LOKASI DETAIL
					</th>
					<th class="bordered" rowspan="2">
						PAGU
						<br />
						INDIKATIF
					</th>
					<th class="bordered" colspan="2">
						CAPAIAN PROGRAM
					</th>
					<th class="bordered" colspan="2">
						INDIKATOR KELUARAN
					</th>
					<th class="bordered" colspan="2">
						INDIKATOR HASIL
					</th>
					<th class="bordered" rowspan="2">
						SUMBER DANA
					</th>
				</tr>
				<tr>
					<th class="bordered">
						Kelurahan
					</th>
					<th class="bordered">
						Kecamatan
					</th>
					<th class="bordered">
						Tolak Ukur Kinerja
					</th>
					<th class="bordered">
						Target
					</th>
					<th class="bordered">
						Tolak Ukur Kinerja
					</th>
					<th class="bordered">
						Target
					</th>
					<th class="bordered">
						Tolak Ukur Kinerja
					</th>
					<th class="bordered">
						Target
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					function count_indicator($array = array(), $key = 0, $val = 0)
					{
						return count(array_filter($array, function($var) use ($val, $key)
						{
							return $val === $var[$key];
						}));
					}
					$id_urusan								= 0;
					$id_bidang								= 0;
					$id_ta__program							= 0;
					$nm_program								= null;
					$total_pagu_indikatif					= 0;
					$total_pra_rka							= 0;
					$id_program								= 0;
					$id_kegiatan							= 0;
					$id_unit								= 0;
					foreach($results['data'] as $key => $val)
					{
						if($val['id_unit'] != $id_unit)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '
										</b>
									</td>
									<td class="bordered">
										<b>
											' . $val['nm_unit'] . '
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
											' . number_format($val['pagu_unit']) . '											
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
									<td class="bordered text-right">
										<b>
																						
										</b>
									</td>
								</tr>
							';
						}
						$list_capaian_program						= array();
						$rowspana									= 0;
						if(sizeof($results['capaian_program']) > 0)
						{
							if($id_program != $val['id_ta__program'])
							{
								$count_1							= count_indicator($results['capaian_program'], 'id_ta__program', $val['id_ta__program']);
							}
							foreach($results['capaian_program'] as $key_a => $val_a)
							{
								if($val_a['id_ta__program'] != $val['id_ta__program']) continue;
								$list_capaian_program[]				= '<td class="bordered" style="padding-left:5px"><b>' . ($count_1 > 1 ? $val_a['kode'] . '. ' : '') . $val_a['tolak_ukur'] . '</b></td><td class="bordered"><b>' . (fmod($val_a['target'], 1) !== 0.00 ? number_format($val_a['target'], 2) : (number_format($val_a['target']) == 0 ? '' : number_format($val_a['target'])) ) . ' ' . $val_a['satuan_target'] . '</b></td>';
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
						if($val['id_program'] != $id_program)
						{
							echo '
								<tr>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_program']) . '</b>
									</td>
									<td class="bordered" style="padding-left:5px"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>' . $val['nm_program'] . '</b>
									</td>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
									<td class="bordered" align="right"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>' . number_format($val['pagu_program']) . '</b>
									</td>
									' . (isset($list_capaian_program[0]) ? $list_capaian_program[0] : '<td class="bordered"></td><td class="bordered"></td>') . '
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
								</tr>
								' . $new_capaian . '
							';
						}
						$indikator									= array();
						if(sizeof($results['indikator_kegiatan']) > 0)
						{
							foreach($results['indikator_kegiatan'] as $key_ => $val_)
							{
								if($val_['id_keg'] != $val['id_kegiatan']) continue;
								$k									= $val_['id_keg'];
								$j									= $val_['jns_indikator'];
								$indikator[$k][$j][]				= '<td class="bordered" style="padding-left:5px">' . $val_['kd_indikator'] . '. ' . $val_['tolak_ukur'] . '</td><td class="bordered">' . $val_['target'] . ' ' . $val_['satuan'] . '</td>';
							}
						}
						$tik										= (isset($indikator[$val['id_kegiatan']][2]) ? sizeof($indikator[$val['id_kegiatan']][2]) : 0);
						$tih										= (isset($indikator[$val['id_kegiatan']][3]) ? sizeof($indikator[$val['id_kegiatan']][3]) : 0);
						$rowspan									= max(array($tik, $tih));
						$new_indikator								= null;
						if(sizeof($indikator) > 1)
						{
							foreach($indikator as $_key => $_val)
							{
								if($_key < 1) continue;
								$new_indikator						.= '<tr><td class="bordered" colspan="8"></td>' . $_val . '</tr>';
							}
						}
						$id_unit									= $val['id_unit'];
						$id_program									= $val['id_program'];
						if(isset($val['id_kegiatan']))
						{
							echo '
								<tr>
									<td class="bordered">										
										' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_program']) . '.' . sprintf('%02d', $val['kd_keg']) . '
									</td>
									<td class="bordered" style="padding-left:5px">
										
									</td>
									<td class="bordered" style="padding-left:5px">
										' . $val['kegiatan'] . '
									</td>
									<td class="bordered" style="padding-left:5px">
										' . $val['kelurahan'] . '
									</td>
									<td class="bordered" style="padding-left:5px">
										' . $val['kecamatan'] . '
									</td>
									<td class="bordered text-right">
										' . number_format($val['pagu_kegiatan']) . '	
									</td>
									<td class="bordered" style="padding-left:5px">
										
									</td>
									<td class="bordered" style="padding-left:5px">
										
									</td>
									' . (isset($indikator[$val['id_kegiatan']][2][0]) ? $indikator[$val['id_kegiatan']][2][0] : '<td class="bordered"></td><td class="bordered"></td>') . '
									' . (isset($indikator[$val['id_kegiatan']][3][0]) ? $indikator[$val['id_kegiatan']][3][0] : '<td class="bordered"></td><td class="bordered"></td>') . '
									<td class="bordered" style="padding-left:5px">
										' . $val['nama_sumber_dana'] . '
									</td>
								</tr>
								' . $new_indikator . '
							';
						}
					}
				?>
			</tbody>
			<tr>
				<td colspan="2" class="bordered text-center">
					<b>
						JUMLAH
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($total_maksimal_usulan_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($total_maksimal_usulan_kelurahan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($jumlah_usulan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($total_maksimal_usulan_kelurahan - $jumlah_usulan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($total_maksimal_usulan_kelurahan - $jumlah_usulan); ?>
					</b>
				</td>
				<td class="bordered text-right">
					<b>
						<?php //echo number_format($total_maksimal_usulan_kelurahan - $jumlah_usulan); ?>
					</b>
				</td>
				<td class="bordered" colspan="5">
					
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