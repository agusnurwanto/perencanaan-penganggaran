<!DOCTYPE html>
<html>
	<head>
		<title>
			Rekapitulasi Rencana Kerja Pemerintah Daerah
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
						if ($this->input->get('status') == 1)
						{
							echo "DRAFT";
						}
					?>
						REKAPITULASI RENCANA KERJA PEMERINTAH DAERAH (RKPD)
					</h4>
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : "") ; ?>
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year'); ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<!--<table class="table">
			<tr>
				<td width="20%">
					<b>
						PERANGKAT DAERAH
					</b>
				</td>
				<td width="5%">
					<b>
						:
					</b>
				</td>
				<td width="75%">
					<b>
						<?php //echo ucwords(strtolower($results['data'][0]['nm_unit'])); ?>
					</b>
				</td>
			</tr>
		</table>-->
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" rowspan="2" width="8%">
						KODE
					</th>
					<th class="bordered" rowspan="2" width="15%">
						URUSAN / BIDANG
					</th>
					<th class="bordered" rowspan="2" width="15%">
						PROGRAM / KEGIATAN
					</th>
					<th class="bordered" colspan="2" width="17%">
						PROGRAM PENDUKUNG PPP
						<br />
						(PRIORITAS PEMBANGUNAN
						<br />
						PROVINSI)
					</th>
					<th class="bordered" rowspan="2" width="15%">
						KEGIATAN
					</th>
					<th class="bordered" colspan="2" width="15%">
						INDIKATOR KINERJA
					</th>
					<th class="bordered" rowspan="2" width="10%">
						PAGU
					</th>
					<th class="bordered" rowspan="2" width="5%">
						KET
					</th>
				</tr>
				<tr>
					<th class="bordered" width="5%">
						YA
					</th>
					<th class="bordered" width="5%">
						TIDAK
					</th>
					<th class="bordered" width="10%">
						Indikator
					</th>
					<th class="bordered" width="5%">
						Target
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$id_urusan								= 0;
					$id_bidang								= 0;
					$id_unit								= 0;
					$id_program								= 0;
					$total_pagu_indikatif					= 0;
					$total_pra_rka							= 0;
					foreach($results['data'] as $key => $val)
					{
						if($val['id_urusan'] != $id_urusan)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kd_urusan'] . '
										</b>
									</td>
									<td class="bordered">
										<b>
											' . $val['nm_urusan'] . '
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
											
										</b>
									</td>
									<td class="bordered">
										<b>
											
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											' . number_format($val['pagu_urusan']) . '
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											
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
											' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '
										</b>
									</td>
									<td class="bordered">
										<b>
											' . $val['nm_bidang'] . '
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
											
										</b>
									</td>
									<td class="bordered">
										<b>
											
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											' . number_format($val['pagu_bidang']) . '
										</b>
									</td>
									<td class="bordered text-right">
										<b>
											
										</b>
									</td>
								</tr>
							';
						}
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
											' . ucwords(strtolower($val['nm_unit'])) . '
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
																						
										</b>
									</td>
									<td class="bordered">
										<b>
											
										</b>
									</td>
									<td class="bordered">
										<b>
											
										</b>
									</td>
									<td class="bordered" align="right">
										<b>
											' . number_format($val['pagu_unit']) . '
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
							foreach($results['capaian_program'] as $key_a => $val_a)
							{
								if($val_a['id_prog'] != $val['id_prog']) continue;
								$list_capaian_program[]				= '<td class="bordered" style="padding-left:5px">' . $val_a['kode'] . '. ' . $val_a['tolak_ukur'] . '</td><td class="bordered">' . (fmod($val_a['target'], 1) !== 0.00 ? number_format($val_a['target'], 2) : number_format($val_a['target'], 0)) . ' ' . $val_a['satuan'] . '</td>';
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
										<b>
											' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_program']) . '
										</b>
									</td>
									<td class="bordered"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>
																						
										</b>
									</td>
									<td class="bordered" style="padding-left:5px"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>
											' . $val['nm_program'] . '
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
									' . (isset($list_capaian_program[0]) ? $list_capaian_program[0] : '<td class="bordered"></td><td class="bordered"></td>') . '
									<td class="bordered" align="right"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>
											' . number_format($val['pagu_program']) . '											
										</b>
									</td>
									<td class="bordered" align="right"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>
																					
										</b>
									</td>
								</tr>
								' . $new_capaian . '
							';
						}
						$list_indikator_kegiatan					= array();
						$rowspan									= 0;
						if(sizeof($results['indikator_kegiatan']) > 0)
						{
							foreach($results['indikator_kegiatan'] as $key_ => $val_)
							{
								if($val_['id_keg'] != $val['id_kegiatan']) continue;
								$list_indikator_kegiatan[]			= '<td class="bordered" style="padding-left:5px">' . $val_['kd_indikator'] . '. ' . $val_['tolak_ukur'] . '</td><td class="bordered">' . (fmod($val_['target'], 1) !== 0.00 ? number_format($val_['target'], 2) : number_format($val_['target'], 0)) . ' ' . $val_['satuan'] . '</td>';
								$rowspan++;
							}
						}
						$new_indikator								= null;
						if(sizeof($list_indikator_kegiatan) > 1)
						{
							foreach($list_indikator_kegiatan as $_key => $_val)
							{
								if($_key < 1) continue;
								$new_indikator						.= '<tr>' . $_val . '</tr>';
							}
						}
						$id_urusan									= $val['id_urusan'];
						$id_bidang									= $val['id_bidang'];
						$id_unit									= $val['id_unit'];
						$id_program									= $val['id_program'];
						if(isset($val['id_kegiatan']))
						{
							echo '
								<tr>
									<td class="bordered"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>										
										' . $val['kd_urusan'] . '.' . sprintf('%02d', $val['kd_bidang']) . '.' . sprintf('%02d', $val['kd_unit']) . '.' . sprintf('%02d', $val['kd_program']) . '.' . sprintf('%02d', $val['kd_keg']) . '
									</td>
									<td class="bordered" style="padding-left:8px"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										
									</td>
									<td class="bordered" style="padding-left:8px"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										
									</td>
									<td class="bordered" style="padding-left:8px"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										
									</td>
									<td class="bordered" style="padding-left:8px"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										
									</td>
									<td class="bordered" style="padding-left:8px"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . $val['kegiatan'] . '
									</td>
									' . (isset($list_indikator_kegiatan[0]) ? $list_indikator_kegiatan[0] : '<td class="bordered"></td><td class="bordered"></td>') . '
									<td class="bordered" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . number_format($val['pagu_kegiatan']) . '	
									</td>
									<td class="bordered" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
											
									</td>
								</tr>
								' . $new_indikator . '
							';							
							$total_pagu_indikatif					+= $val['pagu_kegiatan'];
							$total_pra_rka							+= $val['kegiatan_rka'];
						}
					}
				?>
			</tbody>
			<tr>
				<td colspan="6" class="bordered text-center">
					<b>
						JUMLAH
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format($total_pagu_indikatif); ?>
					</b>
				</td>
				<td class="bordered" align="right">
					<b>
						<?php echo number_format(0); ?>
					</b>
				</td>
				<td class="bordered">
					<b>
						
					</b>
				</td>
				<td class="bordered">
					<b>
						
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
						<?php 
							if ($this->input->get('status') == 1)
							{
								echo "Draft";
							}
						?>
							Kompilasi Program dan Pagu Indikatif Rencana Kerja Awal Tahun <?php echo get_userdata('year'); ?>
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