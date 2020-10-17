<!DOCTYPE html>
<html>
	<head>
		<title>
			Evaluasi Hasil RKPD
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		<style type="text/css">
			@page
			{
				footer: html_footer /* !!! apply only when the htmlpagefooter is sets !!! */
			}
			body
			{
				font-family: sans-serif
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
			.divider
			{
				display: block;
				border-top: 3px solid #000;
				border-bottom: 1px solid #000;
				padding: 2px;
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
				font-weight: bold
			}
			td
			{
				vertical-align: top
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
				padding: 0;
				border: 0
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
	<?php
		if($this->input->get('triwulan') == 1)
		{
			$triwulan		= "triwulan I";
			$triwulan_data	= 1;
		}
		elseif($this->input->get('triwulan') == 2)
		{
			$triwulan 		= "triwulan II";
			$triwulan_data	= 2;
		}
		elseif($this->input->get('triwulan') == 3)
		{
			$triwulan 		= "triwulan III";
			$triwulan_data	= 3;
		}
		else
		{
			$triwulan 		= "triwulan IV";
			$triwulan_data	= 4;
		}
	?>
	<body>
		<table>
			<tr>
				<td width="80">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
				</td>
				<td align="center">
					<h4>
						<?php
							if($this->input->get('unit') == 'all')
							{
								echo 'EVALUASI HASIL RKPD TAHUNAN';
							}
							else
							{
								echo 'EVALUASI TERHADAP HASIL RENJA PERANGKAT DAERAH';
							}
						?>
						<br />
						<?php echo (isset($results['header']->nm_unit) ? strtoupper($results['header']->nm_unit) : NULL) ?> 
						<br />
						<?php echo strtoupper($nama_pemda); ?>
						<br />
						TAHUN ANGGARAN <?php echo get_userdata('year'); ?> <?php echo strtoupper($triwulan); ?>
					</h4>
				</td>
			</tr>
		</table>
		
		<div class="divider"></div>
		
		<table class="table">
			<thead>
				<tr>
					<th rowspan="2" class="border text-sm">
						No
					</th>
					<th rowspan="2" class="border text-sm">
						<?php
							if($this->input->get('unit') == 'all')
							{
								echo 'Sasaran RKPD';
							}
							else
							{
								echo 'Sasaran';
							}
						?>
					</th>
					<th rowspan="2" class="border text-sm">
						Urusan / Program / Kegiatan
					</th>
					<th rowspan="2" class="border text-sm">
						Indikator Kinerja Program (outcome)/
						<br />
						Kegiatan (output)
					</th>
					<th rowspan="2" colspan="2" class="border text-sm">
						<?php
							if($this->input->get('unit') == 'all')
							{
								echo 'Target RPJMD pada Tahun ' . get_userdata('year') . ' (Akhir Periode RPJMD)';
							}
							else
							{
								echo 'Target Renstra Perangkat Daerah pada Tahun 2023 (Akhir Periode Renstra Perangkat Daerah)';
							}
						?>
					</th>
					<th rowspan="2" colspan="2" class="border text-sm">
						<?php
							if($this->input->get('unit') == 'all')
							{
								echo 'Realisasi Capaian Kinerja RPJMD sampai dengan RKPD Tahun ' . (get_userdata('year') - 1);
							}
							else
							{
								echo 'Realisasi Capaian Kinerja Renstra Perangkat Daerah s.d Renja Perangkat Darah Tahun Lalu ' . (get_userdata('year') - 1);
							}
						?>
					</th>
					<th rowspan="2" colspan="2" class="border text-sm">
						<?php
							if($this->input->get('unit') == 'all')
							{
								echo 'Target Kinerja dan Anggaran Berjalan Tahun ' . get_userdata('year') . ' yang dievaluasi';
							}
							else
							{
								echo 'Target Kinerja dan Anggaran Renja Perangkat Daerah Tahun Berjalan Tahun ' . get_userdata('year') . ' yang di evaluasi';
							}
						?>
					</th>
					<th colspan="8" class="border text-sm">
						Realisasi Kinerja s.d Triwulan
					</th>
					<th rowspan="2" colspan="2" class="border text-sm">
						Realisasi Capaian Kinerja dan Anggaran Renja SKPD yang dievaluasi (<?php echo get_userdata('year'); ?>)
					</th>
					<th rowspan="2" colspan="2" class="border text-sm">
						Tingkat Capaian Kinerja dan Realisasi Anggaran Renja Tahun (<?php echo get_userdata('year'); ?>) (%)
					</th>
					<th rowspan="2" colspan="2" class="border text-sm">
						<?php
							if($this->input->get('unit') == 'all')
							{
								echo 'Realisasi Kinerja dan Anggaran RPJMD s/d Tahun ' . get_userdata('year') . ' (Akhir Tahun Pelaksanaan RKPD Tahun ' . get_userdata('year') . ')';
							}
							else
							{
								echo 'Realisasi Kinerja dan Anggaran Renstra SKPD s/d Tahun ' . get_userdata('year') . ' (Akhir Tahun Pelaksanaan Renja SKPD Tahun ' . get_userdata('year') . ')';
							}
						?>
					</th>
					<th rowspan="2" colspan="2" class="border text-sm">
						<?php
							if($this->input->get('unit') == 'all')
							{
								echo 'Tingkat Capaian Kinerja dan Realisasi Anggaran RPJMD s/d tahun ' . get_userdata('year') . ' (%)';
							}
							else
							{
								echo 'Tingkat Capaian Kinerja dan Realisasi Anggaran Renstra SKPD s/d tahun ' . get_userdata('year') . ' (%)';
							}
						?>
					</th>
					<th rowspan="2" class="border text-sm">
						SKPD Penanggungjawab
					</th>
					<th rowspan="2" class="border text-sm">
						Keterangan
					</th>
				</tr>
				<tr>
					<th colspan="2" class="border text-sm">
						I
					</th>
					<th colspan="2" class="border text-sm">
						II
					</th>
					<th colspan="2" class="border text-sm">
						III
					</th>
					<th colspan="2" class="border text-sm">
						IV
					</th>
				</tr>
				<tr>
					<th rowspan="2" class="border text-sm">
						1
					</th>
					<th rowspan="2" class="border text-sm">
						2
					</th>
					<th rowspan="2" class="border text-sm">
						3
					</th>
					<th rowspan="2" class="border text-sm">
						4
					</th>
					<th colspan="2" class="border text-sm">
						5
					</th>
					<th colspan="2" class="border text-sm">
						6
					</th>
					<th colspan="2" class="border text-sm">
						7
					</th>
					<th colspan="2" class="border text-sm">
						8
					</th>
					<th colspan="2" class="border text-sm">
						9
					</th>
					<th colspan="2" class="border text-sm">
						10
					</th>
					<th colspan="2" class="border text-sm">
						11
					</th>
					<th colspan="2" class="border text-sm">
						12=8+9+10+11
					</th>
					<th colspan="2" class="border text-sm">
						13=12/7x100%
					</th>
					<th colspan="2" class="border text-sm">
						14=6+12
					</th>
					<th colspan="2" class="border text-sm">
						15=14/5x100%
					</th>
					<th rowspan="2" class="border text-sm">
						16
					</th>
					<th rowspan="2" class="border text-sm">
						17
					</th>
				</tr>
				<tr>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
					<th class="border text-sm">
						K
					</th>
					<th class="border text-sm">
						Rp
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					$num									= 1;
					$num_program							= 1;
					$kegiatan								= null;
					$nm_program								= null;
					$id_prog								= 0;
					$id_bidang								= 0;
					
					$rck_1									= 0;
					$rck_1_rp								= 0;
					$rck_2									= 0;
					$rck_2_rp								= 0;
					$rck_3									= 0;
					$rck_3_rp								= 0;
					$rck_4									= 0;
					$rck_4_rp								= 0;
					
					/**
					 * Group array by kode kegiatan
					 */
					$arr_group								= array();
					foreach($results['data'] as $key => $val)
					{
						$arr_group[$val->kode_keg]			= $key;
					}
					// end group array
					
					foreach($results['data'] as $key => $val)
					{
						if($id_bidang != $val->id_bidang)
						{
							echo '
								<tr>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm">										
										<b>' . $val->nm_bidang . '</b>
									</td>
									<td class="border text-sm">
										
									</td>
									<td class="border text-sm" align="right" colspan="24">
										
									</td>
								</tr>
							';
						}
						if($id_prog != $val->id_prog)
						{
							echo '
								<tr>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm">										
										<b>' . ($nm_program == $val->nm_program ? null : $val->nm_program) . '</b>
									</td>
									<td class="border text-sm">
										<b>' . //$val->tolak_ukur_program . 
										'</b>
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="right">
										
									</td>
									<td class="border text-sm" align="center">
										<b>' . //$val->tahun_1_target . ' ' . $val->tahun_1_satuan . 
										'</b>
									</td>
									<td class="border text-sm" align="right" colspan="20">
										
									</td>
								</tr>
							';
						}
						if($triwulan_data == 1)
						{
							$realisasi_capaian_kinerja_keluaran	=	($val->realisasi_indikator_triwulan_1) / 100 * $val->target; /* 12 */
							$realisasi_capaian_kinerja_rp		=	($kegiatan == $val->kegiatan ? null : $val->nilai_realisasi_uang_tw_1); /* 12 */
							$tingkat_capaian_kinerja_keluaran	=	$realisasi_capaian_kinerja_keluaran / ($val->target == 0 ? 1 : $val->target) * 100; /* 13 */
							$tingkat_capaian_kinerja_rp			=	($kegiatan == $val->kegiatan ? null : $realisasi_capaian_kinerja_rp / ($val->pagu ? $val->pagu : 1) * 100); /* 13 */
							$realisasi_kinerja_keluaran			= 	$realisasi_capaian_kinerja_keluaran; /* 14 */
							$realisasi_kinerja_rp				= 	$realisasi_capaian_kinerja_rp; /* 14 */
						}
						elseif($triwulan_data == 2)
						{
							$realisasi_capaian_kinerja_keluaran	=	($val->realisasi_indikator_triwulan_1 + $val->realisasi_indikator_triwulan_2) / 100 * $val->target; /* 12 */
							$realisasi_capaian_kinerja_rp		=	($kegiatan == $val->kegiatan ? null : ($val->nilai_realisasi_uang_tw_1 + $val->nilai_realisasi_uang_tw_2)); /* 12 */
							$tingkat_capaian_kinerja_keluaran	=	$realisasi_capaian_kinerja_keluaran / ($val->target == 0 ? 1 : $val->target) * 100; /* 13 */
							$tingkat_capaian_kinerja_rp			=	($kegiatan == $val->kegiatan ? null : $realisasi_capaian_kinerja_rp / ($val->pagu ? $val->pagu : 1) * 100); /* 13 */
							$realisasi_kinerja_keluaran			= 	$realisasi_capaian_kinerja_keluaran; /* 14 */
							$realisasi_kinerja_rp				= 	$realisasi_capaian_kinerja_rp; /* 14 */
						}
						elseif($triwulan_data == 3)
						{
							$realisasi_capaian_kinerja_keluaran	=	($val->realisasi_indikator_triwulan_1 + $val->realisasi_indikator_triwulan_2 + $val->realisasi_indikator_triwulan_3) / 100 * $val->target; /* 12 */
							$realisasi_capaian_kinerja_rp	=	($kegiatan == $val->kegiatan ? null : ($val->nilai_realisasi_uang_tw_1 + $val->nilai_realisasi_uang_tw_2 + $val->nilai_realisasi_uang_tw_3)); /* 12 */
							$tingkat_capaian_kinerja_keluaran	=	$realisasi_capaian_kinerja_keluaran / ($val->target == 0 ? 1 : $val->target) * 100; /* 13 */
							$tingkat_capaian_kinerja_rp			=	($kegiatan == $val->kegiatan ? null : $realisasi_capaian_kinerja_rp / ($val->pagu ? $val->pagu : 1) * 100); /* 13 */
							$realisasi_kinerja_keluaran			= 	$realisasi_capaian_kinerja_keluaran; /* 14 */
							$realisasi_kinerja_rp				= 	$realisasi_capaian_kinerja_rp; /* 14 */
						}
						elseif($triwulan_data > 3)
						{
							$realisasi_capaian_kinerja_keluaran	=	($val->realisasi_indikator_triwulan_1 + $val->realisasi_indikator_triwulan_2 + $val->realisasi_indikator_triwulan_3 + $val->realisasi_indikator_triwulan_4) / 100 * $val->target; /* 12 */
							$realisasi_capaian_kinerja_rp	=	($kegiatan == $val->kegiatan ? null : ($val->nilai_realisasi_uang_tw_1 + $val->nilai_realisasi_uang_tw_2 + $val->nilai_realisasi_uang_tw_3 + $val->nilai_realisasi_uang_tw_4)); /* 12 */
							$tingkat_capaian_kinerja_keluaran	=	$realisasi_capaian_kinerja_keluaran / ($val->target == 0 ? 1 : $val->target) * 100; /* 13 */
							$tingkat_capaian_kinerja_rp			=	($kegiatan == $val->kegiatan ? null : $realisasi_capaian_kinerja_rp / ($val->pagu ? $val->pagu : 1) * 100); /* 13 */
							$realisasi_kinerja_keluaran			= 	$realisasi_capaian_kinerja_keluaran; /* 14 */
							$realisasi_kinerja_rp				= 	$realisasi_capaian_kinerja_rp; /* 14 */
						}
						
						echo '
							<tr>
								<td class="border text-sm text-center">										
									' . ($kegiatan == $val->kegiatan ? null : $num) . ' <!-- 1 -->
								</td>
								<td class="border text-sm" align="right">
									<!-- 2 -->
								</td>
								<td class="border text-sm" style="padding-left:5px">
									' . ($kegiatan == $val->kegiatan ? null : $val->kegiatan) . ' <!-- 3 -->
								</td>
								<td class="border text-sm">
									' . ($val->jns_indikator == 2 ?  $val->tolak_ukur : '<b>' . $val->tolak_ukur . '</b>' ) . '  <!-- 4 -->
								</td>
								<td class="border text-sm" align="right">
									<!-- 5 -->
								</td>
								<td class="border text-sm" align="right">
									<!-- 5 -->
								</td>
								<td class="border text-sm" align="right">
									<!-- 6 -->
								</td>
								<td class="border text-sm" align="right">
									<!-- 6 -->
								</td>
								<td class="border text-sm">
									' . $val->target . ' ' . $val->satuan . ' <!-- 7 -->
								</td>
								<td class="border text-sm" align="right">
									' . ($kegiatan == $val->kegiatan ? null : number_format_indo($val->pagu)) . ' <!-- 7 -->
								</td>
								<td class="border text-sm" align="center">
									' . ($val->target * $val->realisasi_indikator_triwulan_1 / 100) . ' <!-- 8 -->
								</td>
								<td class="border text-sm" align="right">
									' . ($kegiatan == $val->kegiatan ? null : number_format_indo($val->nilai_realisasi_uang_tw_1)) . ' <!-- 8 -->
								</td>
								<td class="border text-sm" align="center">
									' . ($triwulan_data > 1 ? ($val->target * $val->realisasi_indikator_triwulan_2 / 100) : 0) . ' <!-- 9 -->
								</td>
								<td class="border text-sm" align="right">
									' . ($triwulan_data > 1 ? ($kegiatan == $val->kegiatan ? null : number_format_indo($val->nilai_realisasi_uang_tw_2)) : 0) . ' <!-- 9 -->
								</td>
								<td class="border text-sm" align="center">
									' . ($triwulan_data > 2 ? ($val->target * $val->realisasi_indikator_triwulan_3 / 100) : 0) . ' <!-- 10 -->
								</td>
								<td class="border text-sm" align="right">
									' . ($triwulan_data > 2 ? ($kegiatan == $val->kegiatan ? null : number_format_indo($val->nilai_realisasi_uang_tw_3)) : 0) . ' <!-- 10 -->
								</td>
								<td class="border text-sm" align="center">
									' . ($triwulan_data > 3 ? ($val->target * $val->realisasi_indikator_triwulan_4 / 100) : 0) . ' <!-- 11 -->
								</td>
								<td class="border text-sm" align="right">
									' . ($triwulan_data > 3 ? ($kegiatan == $val->kegiatan ? null : number_format_indo($val->nilai_realisasi_uang_tw_4)) : 0) . ' <!-- 11 -->
								</td>
								<td class="border text-sm" align="center">
									' . number_format_indo($realisasi_capaian_kinerja_keluaran, 2) . '  <!-- 12 -->
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($realisasi_capaian_kinerja_rp, 2) . ' <!-- 12 -->
								</td>
								<td class="border text-sm" align="center">
									' . number_format_indo($tingkat_capaian_kinerja_keluaran, 2) . ' <!-- 13 -->
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($tingkat_capaian_kinerja_rp, 2) . ' <!-- 13 -->
								</td>
								<td class="border text-sm" align="center">
									' . number_format_indo($realisasi_kinerja_keluaran, 2) . ' <!-- 14 -->
								</td>
								<td class="border text-sm" align="right">
									' . number_format_indo($realisasi_kinerja_rp, 2) . ' <!-- 14 -->
								</td>
								<td class="border text-sm" align="center">
									' . ($val->realisasi_indikator_triwulan_1 + $val->realisasi_indikator_triwulan_2 + $val->realisasi_indikator_triwulan_3 + $val->realisasi_indikator_triwulan_4) . ' <!-- 15 -->
								</td>
								<td class="border text-sm" align="right">
									 <!-- 15 -->
								</td>
								<td class="border text-sm">
									' . ($kegiatan == $val->kegiatan ? null : ucwords(strtolower($val->nm_unit))) . ' <!-- 16 -->
								</td>
								<td class="border text-sm" align="right">
									<!-- 17 -->
								</td>
							</tr>
						';
						
						if(isset($arr_group[$val->kode_keg]))
						{
							/**
							 * FORMULA
							 */
							$rck_1							+= $val->realisasi_indikator_triwulan_1;
							$rck_1_rp						+= $val->nilai_realisasi_uang_tw_1;
							$rck_2							+= $val->realisasi_indikator_triwulan_2;
							$rck_2_rp						+= $val->nilai_realisasi_uang_tw_2;
							$rck_3							+= $val->realisasi_indikator_triwulan_3;
							$rck_3_rp						+= $val->nilai_realisasi_uang_tw_3;
							$rck_4							+= $val->realisasi_indikator_triwulan_4;
							$rck_4_rp						+= $val->nilai_realisasi_uang_tw_4;
							
							if($arr_group[$val->kode_keg] == $key)
							{
								echo '
									<tr>
										<td colspan="10" class="border text-sm text-right">
											Rata-rata capaian kinerja (%)
										</td>
										<td class="border text-sm text-right">
											' . number_format($rck_1, 2) . '
										</td>
										<td class="border text-sm text-right">
											' . number_format($rck_1_rp) . '
										</td>
										<td class="border text-sm text-right">
											' . ($triwulan_data > 1 ? number_format($rck_2, 2) : 0) . '
										</td>
										<td class="border text-sm text-right">
											' . ($triwulan_data > 1 ? number_format($rck_2_rp) : 0) . '
										</td>
										<td class="border text-sm text-right">
											' . ($triwulan_data > 2 ? number_format($rck_3, 2) : 0) . '
										</td>
										<td class="border text-sm text-right">
											' . ($triwulan_data > 2 ? number_format($rck_3_rp) : 0) . '
										</td>
										<td class="border text-sm text-right">
											' . ($triwulan_data > 3 ? number_format($rck_4, 2) : 0) . '
										</td>
										<td class="border text-sm text-right">
											' . ($triwulan_data > 3 ? number_format($rck_4_rp) : 0) . '
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td colspan="8" class="border text-sm text-right">
										</td>
									</tr>
									<tr>
										<td colspan="10" class="border text-sm text-right">
											Predikat kinerja
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td class="border text-sm text-right">
										</td>
										<td colspan="8" class="border text-sm text-right">
										</td>
									</tr>
								';
							}
						}
						else
						{
							$rck_1							= 0;
							$rck_1_rp						= 0;
							$rck_2							= 0;
							$rck_2_rp						= 0;
							$rck_3							= 0;
							$rck_3_rp						= 0;
							$rck_4							= 0;
							$rck_4_rp						= 0;
						}
						
						($kegiatan == $val->kegiatan ? null : $num++);
						($nm_program == $val->nm_program ? null : $num_program++);
						$kegiatan							= $val->kegiatan;
						$id_prog 							= $val->id_prog;
						$id_bidang 							= $val->id_bidang;
					}
				?>
			</tbody>
		</table>
		<table class="table">
			<tr>
				<td class="bordered" align="center">
					Disusun
					<br />
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo date('d'); ?> <?php echo phrase(date('F')); ?> <?php echo date('Y'); ?>
					<br />
					<br />
					<b><?php echo (isset($results['header']->nama_jabatan) ? $results['header']->nama_jabatan : NULL);?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo (isset($results['header']->nama_pejabat) ? $results['header']->nama_pejabat : NULL);?></b></u>
					<br />
					<?php echo (isset($results['header']->nip_pejabat) ? 'NIP. '. $results['header']->nip_pejabat : NULL);?>
				</td>
				<td class="bordered" align="center">
					Dievaluasi
					<br />
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo date('d'); ?> <?php echo phrase(date('F')); ?> <?php echo date('Y'); ?>
					<br />
					<br />
					<b><?php echo ($results['bappeda']->jabatan_kepala_perencanaan ? $results['bappeda']->jabatan_kepala_perencanaan : NULL);?></b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo ($results['bappeda']->nama_kepala_perencanaan ? $results['bappeda']->nama_kepala_perencanaan : NULL);?></b></u>
					<br />
					<?php echo ($results['bappeda']->nip_kepala_perencanaan ? 'NIP. '. $results['bappeda']->nip_kepala_perencanaan : NULL);?>
				</td>
			</tr>
		</table>
		<htmlpagefooter name="footer">
			<table class="table print">
				<tfoot>
					<tr>
						<td class="text-sm text-muted">
							<i>
								<?php echo phrase('document_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' ' . date('F d Y, H:i:s'); ?>
							</i>
						</td>
						<td class="text-sm text-muted text-right print">
							<?php echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
						</td>
					</tr>
				</tfoot>
			</table>
		</htmlpagefooter>
	</body>
</html>