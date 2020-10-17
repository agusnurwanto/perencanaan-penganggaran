<!DOCTYPE html>
<html>
	<head>
		<title>
			Rencana Kerja SKPD
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
		</style>
	</head>
	<body>
		<table class="table" align="center">
			<tr>
				<th width="100" class="border no-border-right">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." width="80" />
				</th>
				<th class="border no-border-left" align="center" width="100%" colspan="9">
					<h4>
						KOMPILASI PROGRAM DAN PAGU INDIKATIF TIAP PERANGKAT DAERAH
					</h4>
					<h4>
						RENCANA KERJA <?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : "") ; ?>
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year'); ?>
					</h4>
				</th>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border text-sm-2" rowspan="2" width="8%">
						KODE
					</th>
					<th class="border text-sm-2" rowspan="2" width="18%">
						URUSAN / BIDANG / PROGRAM / KEGIATAN / SUB KEGIATAN
					</th>
					<th class="border text-sm-2" colspan="2" width="12%">
						LOKASI DETAIL
					</th>
					<th class="border text-sm-2" colspan="2" width="24%">
						KINERJA
					</th>
					<th class="border text-sm-2" rowspan="2" width="9%">
						PAGU
						<br>
						INDIKATIF
					</th>
					<th class="border text-sm-2" rowspan="2" width="9%">
						PAGU
						<br>
						PRA-RKA
					</th>
					<th class="border text-sm-2" colspan="2" width="13%">
						SELISIH
					</th>
					<th class="border text-sm" rowspan="2" width="7%">
						PERANGKAT<br />DAERAH
					</th>
				</tr>
				<tr>
					<th class="border text-sm" width="6%">
						Kelurahan
					</th>
					<th class="border text-sm" width="6%">
						Kecamatan
					</th>
					<th class="border text-sm" width="19%">
						Indikator
					</th>
					<th class="border text-sm" width="5%">
						Target
					</th>
					<th class="border text-sm" width="8%">
						Rp
					</th>
					<th class="border text-sm" width="5%">
						%
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
					$id_kegiatan_sub						= 0;
					foreach($results['data'] as $key => $val)
					{
							// Urusan
						if($val->id_urusan != $id_urusan)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_urusan . '</b>
									</td>
									<td class="border text-sm" colspan="3">
										<b>' . $val->nm_urusan . '</b>
									</td>
									<td class="border text-sm" align="right">
									</td>
									<td class="border text-sm" align="right">
									</td>
									<td class="border text-sm" align="right">
										<b>' . number_format_indo($val->pagu_urusan) . '</b>
									</td>
									<td class="border text-sm" align="right">
										<b>' . number_format_indo($val->urusan_rka) . '</b>
									</td>
									<td class="border text-sm" align="right">
										<b>' . number_format_indo($val->pagu_urusan - $val->urusan_rka) . '</b>
									</td>
									<td class="border text-sm" align="right">
										<b>' . ($val->pagu_urusan > 0 ? number_format_indo($val->urusan_rka / $val->pagu_urusan * 100, 2) : 0) . '</b>
									</td>
									<td class="border text-sm" align="right">
										<b>																						
										</b>
									</td>
								</tr>
							';
						}
							// Bidang
						if($val->id_bidang != $id_bidang)
						{
							echo '
								<tr>
									<td class="border text-sm">
										<b>' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '</b>
									</td>
									<td class="border text-sm" colspan="3">
										<b>' . $val->nm_bidang . '</b>
									</td>
									<td class="border text-sm">
									</td>
									<td class="border text-sm">
									</td>
									<td class="border text-sm" align="right">
										<b>' . number_format_indo($val->pagu_bidang) . '</b>
									</td>
									<td class="border text-sm" align="right">
										<b>' . number_format_indo($val->bidang_rka) . '</b>
									</td>
									<td class="border text-sm" align="right">
										<b>' . number_format_indo($val->pagu_bidang - $val->bidang_rka) . '</b>
									</td>
									<td class="border text-sm" align="right">
										<b>' . ($val->pagu_bidang > 0 ? number_format_indo($val->bidang_rka / $val->pagu_bidang * 100, 2) : 0) . '</b>
									</td>
									<td class="border text-sm">
										<b>																						
										</b>
									</td>
								</tr>
							';
						}
							// Program
						$list_capaian_program						= array();
						$rowspana									= 0;
						if(sizeof($results['capaian_program']) > 0)
						{
							if($id_program != $val->id_ta__program)
							{
								$count_1							= count_indicator($results['capaian_program'], 'id_ta__program', $val->id_ta__program);
							}
							foreach($results['capaian_program'] as $key_a => $val_a)
							{
								if($val_a['id_ta__program'] != $val->id_ta__program) continue;
								$list_capaian_program[]				= '<td class="border text-sm" style="padding-left:5px">' . ($count_1 > 1 ? $val_a['kode'] . '. ' : '') . $val_a['tolak_ukur'] . '</td><td class="border text-sm">' . (fmod($val_a['target'], 1) !== 0.00 ? number_format_indo($val_a['target'], 2) : (number_format_indo($val_a['target']) == 0 ? '' : number_format_indo($val_a['target'])) ) . ' ' . $val_a['satuan_target'] . '</td>';
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
						if($val->id_ta__program != $id_ta__program && $val->nm_program != $nm_program )
						{
							echo '
								<tr>
									<td class="border text-sm"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '.' . sprintf('%02d', $val->kd_program) . '</b>
									</td>
									<td class="border text-sm" style="padding-left:5px"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . ' colspan="3">
										<b>' . $val->nm_program . '</b>
									</td>
									' . (isset($list_capaian_program[0]) ? $list_capaian_program[0] : '<td class="border text-sm"></td><td class="border text-sm"></td>') . '
									<td class="border text-sm" align="right"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>' . number_format_indo($val->pagu_program) . '</b>
									</td>
									<td class="border text-sm" align="right"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>' . number_format_indo($val->program_rka) . '</b>
									</td>
									<td class="border text-sm" align="right"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>' . number_format_indo($val->pagu_program - $val->program_rka) . '</b>
									</td>
									<td class="border text-sm" align="right"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>' . ($val->pagu_program > 0 ? number_format_indo($val->program_rka / $val->pagu_program * 100, 2) : 0) . '</b>
									</td>
									<td class="border text-sm"' . ($rowspana > 0 ? ' rowspan="' . $rowspana . '"' : '') . '>
										<b>																						
										</b>
									</td>
								</tr>
								' . $new_capaian . '
							';
						}
							// Kegiatan
						$list_indikator_kegiatan					= array();
						$rowspan									= 0;
						if(sizeof($results['indikator_kegiatan']) > 0)
						{
							if($id_kegiatan != $val->id_kegiatan)
							{
								$count_2								= count_indicator($results['indikator_kegiatan'], 'id_keg', $val->id_kegiatan);
							}
							foreach($results['indikator_kegiatan'] as $key_ => $val_)
							{
								if($val_['id_keg'] != $val->id_kegiatan) continue;
								$list_indikator_kegiatan[]			= '<td class="border text-sm" style="padding-left:5px">' . ($count_2 > 1 ? $val_['kd_indikator'] . '. ' : '') . $val_['tolak_ukur'] . '</td><td class="border text-sm">' . (fmod($val_['target'], 1) !== 0.00 ? number_format_indo($val_['target'], 2) : number_format_indo($val_['target'], 0)) . ' ' . $val_['satuan'] . '</td>';
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
						if($val->id_kegiatan != $id_kegiatan)
						{
							echo '
								<tr>
									<td class="border text-sm"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>										
										' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '.' . sprintf('%02d', $val->kd_program) . '.' . sprintf('%02d', $val->kd_keg) . '
									</td>
									<td class="border text-sm" style="padding-left:10px"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . ' colspan="3">
										<b>' . $val->kegiatan . '</b>
									</td>
									' . (isset($list_indikator_kegiatan[0]) ? $list_indikator_kegiatan[0] : '<td class="border text-sm"></td><td class="border text-sm"></td>') . '
									<td class="border text-sm" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										<b>' . number_format_indo($val->pagu_kegiatan) . '</b>
									</td>
									<td class="border text-sm" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										<b>' . number_format_indo($val->kegiatan_rka) . '</b>
									</td>
									<td class="border text-sm" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										<b>' . number_format_indo($val->pagu_kegiatan - $val->kegiatan_rka) . '</b>
									</td>
									<td class="border text-sm" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										<b>' . ($val->pagu_kegiatan > 0 ? number_format_indo($val->kegiatan_rka / $val->pagu_kegiatan * 100, 2) : 0) . '</b>
									</td>
									<td class="border text-sm" align="center" ' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										
									</td>
								</tr>
								' . $new_indikator . '
							';
						}
						
							// Sub Kegiatan
						$list_indikator_kegiatan_sub				= array();
						$rowspan									= 0;
						if(sizeof($results['indikator_sub_kegiatan']) > 0)
						{
							if($id_kegiatan_sub != $val->id_kegiatan_sub)
							{
								$count_2							= count_indicator($results['indikator_sub_kegiatan'], 'id_keg_sub', $val->id_kegiatan_sub);
							}
							foreach($results['indikator_sub_kegiatan'] as $key_ => $val_)
							{
								if($val_['id_keg_sub'] != $val->id_kegiatan_sub) continue;
								$list_indikator_kegiatan_sub[]		= '<td class="border text-sm" style="padding-left:5px">' . ($count_2 > 1 ? $val_['kd_indikator'] . '. ' : '') . $val_['tolak_ukur'] . '</td><td class="border text-sm">' . (fmod($val_['target'], 1) !== 0.00 ? number_format_indo($val_['target'], 2) : number_format_indo($val_['target'], 0)) . ' ' . $val_['satuan'] . '</td>';
								$rowspan++;
							}
						}
						$new_indikator_sub							= null;
						if(sizeof($list_indikator_kegiatan_sub) > 1)
						{
							foreach($list_indikator_kegiatan_sub as $_key => $_val)
							{
								if($_key < 1) continue;
								$new_indikator_sub					.= '<tr>' . $_val . '</tr>';
							}
						}
						$id_urusan									= $val->id_urusan;
						$id_bidang									= $val->id_bidang;
						$id_ta__program								= $val->id_ta__program;
						$nm_program									= $val->nm_program;
						if(isset($val->id_kegiatan_sub))
						{
							echo '
								<tr>
									<td class="border text-sm"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>										
										' . $val->kd_urusan . '.' . sprintf('%02d', $val->kd_bidang) . '.' . sprintf('%02d', $val->kd_program) . '.' . sprintf('%02d', $val->kd_keg) . '.' . sprintf('%02d', $val->kd_keg_sub) . '
									</td>
									<td class="border text-sm" style="padding-left:15px"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . $val->kegiatan_sub . '
									</td>
									<td class="border text-sm" align="center" ' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . $val->kelurahan . '
									</td>
									<td class="border text-sm" align="center" ' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . $val->kecamatan . '
									</td>
									' . (isset($list_indikator_kegiatan_sub[0]) ? $list_indikator_kegiatan_sub[0] : '<td class="border text-sm"></td><td class="border text-sm"></td>') . '
									<td class="border text-sm" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . number_format_indo($val->pagu_sub_kegiatan) . '	
									</td>
									<td class="border text-sm" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . number_format_indo($val->sub_kegiatan_rka) . '	
									</td>
									<td class="border text-sm" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . number_format_indo($val->pagu_sub_kegiatan - $val->sub_kegiatan_rka) . '	
									</td>
									<td class="border text-sm" align="right"' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . ($val->pagu_sub_kegiatan > 0 ? number_format_indo($val->sub_kegiatan_rka / $val->pagu_sub_kegiatan * 100, 2) : 0) . '
									</td>
									<td class="border text-sm" align="center" ' . ($rowspan > 0 ? ' rowspan="' . $rowspan . '"' : '') . '>
										' . $val->nm_unit . '
									</td>
								</tr>
								' . $new_indikator_sub . '
							';							
							$total_pagu_indikatif					+= $val->pagu_sub_kegiatan;
							$total_pra_rka							+= $val->sub_kegiatan_rka;
							$id_program								= $val->id_ta__program;
							$id_kegiatan							= $val->id_kegiatan;
							$id_kegiatan_sub						= $val->id_kegiatan_sub;
						}
					}
				?>
			</tbody>
			<tr>
				<td colspan="6" class="border text-sm" align="center">
					<b>JUMLAH</b>
				</td>
				<td class="border text-sm" align="right">
					<b><?php echo number_format_indo($total_pagu_indikatif); ?></b>
				</td>
				<td class="border text-sm" align="right">
					<b><?php echo number_format_indo($total_pra_rka); ?></b>
				</td>
				<td class="border text-sm" align="right">
					<b><?php echo number_format_indo($total_pagu_indikatif - $total_pra_rka); ?></b>
				</td>
				<td class="border text-sm" align="right">
					<b><?php  echo ($total_pagu_indikatif > 0 ? number_format_indo($total_pra_rka / $total_pagu_indikatif * 100, 2) : 0); ?> %</b>
				</td>
				<td class="border text-sm" align="right">
					
				</td>
			</tr>
		</table>
		<table class="table" width="100%" style="page-break-inside:avoid">
			<tbody>
				<tr>
					<td colspan="3" class="border">
						Keterangan : <?php //echo ($results['header']->pilihan == 1 ? ' Model ' . $results['header']->nm_model : NULL); ?> <!--
						<br />
						<img src="<?php // echo get_image('___qrcode', sha1(current_page() . SALT) . '.png'); ?>" alt="..." width="100" />-->
					</td>
					<td colspan="2" class="text-center border">
						<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo ($tanggal_cetak ? $tanggal_cetak : date('d') . '' . phrase(date('F')) . '' . date('Y') ); ?>
						<br />
						<b><?php 
							echo strtoupper($results['header']->nama_jabatan);	
							?></b>
						<br />
						<br />
						<br />
						<br />
						<br />
						<u><b><?php
									echo $results['header']->nama_pejabat;
								?></b></u>
						<br />
						<?php
							echo 'NIP. '. $results['header']->nip_pejabat;
						?>
					</td>
				</tr>
			</tbody>
		</table>
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="border no-border-right text-muted text-sm" colspan="2">
						<i>
						<?php 
							if ($this->input->get('status') == 1)
							{
								echo "Draft";
							}
						?>
							Kompilasi Program dan Pagu Indikatif Rencana Kerja Tahun <?php echo get_userdata('year'); ?>
						</i>
					</td>
					<td class="border no-border-left text-muted text-sm" align="right" colspan="2">
						<?php echo phrase('page') . ' {PAGENO} dari {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>