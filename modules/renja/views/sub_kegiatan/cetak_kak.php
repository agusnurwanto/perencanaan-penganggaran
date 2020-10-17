<?php
	$dasar_hukum					= (isset($kak->dasar_hukum) ? json_decode($kak->dasar_hukum) : array());
	$gambaran_umum					= (isset($kak->gambaran_umum) ? json_decode($kak->gambaran_umum) : array());
	$penerima_manfaat				= (isset($kak->penerima_manfaat) ? $kak->penerima_manfaat : null);
	$metode_pelaksanaan				= (isset($kak->metode_pelaksanaan) ? json_decode($kak->metode_pelaksanaan, true) : array());
	$tahapan_pelaksanaan			= (isset($kak->tahapan_pelaksanaan) ? json_decode($kak->tahapan_pelaksanaan, true) : array());
	$waktu_pelaksanaan				= (isset($kak->waktu_pelaksanaan) ? $kak->waktu_pelaksanaan : null);
	$biaya							= (isset($kak->biaya) ? json_decode($kak->biaya, true) : array());
?>
<!DOCTYPE html>
<html>
	<head>
		<title>
			Kerangka Acuan Kerja
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		<style type="text/css">
			@page
			{
				footer: html_footer /* !!! apply only when the htmlpagefooter is sets !!! */
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
			.v-middle
			{
				vertical-align: middle!important
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
			ol li
			{
				margin-bottom: 20px
			}
		</style>
	</head>
	<body>
		<h1 class="text-center">
			KERANGKA ACUAN KERJA
		</h1>
		<br />
		<br />
		<br />
		<table class="table">
			<tr>
				<td width="200">
					Perangkat Daerah
				</td>
				<td width="20">
					:
				</td>
				<td>
					<?php echo (isset($header->nm_sub) ? $header->nm_sub : '-'); ?>
				</td>
			</tr>
			<tr>
				<td>
					Nama Program
				</td>
				<td width="1">
					:
				</td>
				<td>
					<?php echo (isset($header->nm_program) ? $header->nm_program : '-'); ?>
				</td>
			</tr>
			<tr>
				<td>
					IK Program (Outcome)
				</td>
				<td width="1">
					:
				</td>
				<td>
					<?php 
						$num_capaian_progam		= 1;
						foreach($capaian_program as $key => $val)
						{
							echo $num_capaian_progam . '. ' . $val->tolak_ukur . ' : ' . $val->tahun_2_target . ' ' . $val->tahun_2_satuan . '<br />';
							$num_capaian_progam			= $num_capaian_progam + 1;
						}
					 ?>
				</td>
			</tr>
			<tr>
				<td>
					Nama Kegiatan
				</td>
				<td width="1">
					:
				</td>
				<td>
					<?php echo (isset($header->kegiatan) ? $header->kegiatan : '-'); ?>
				</td>
			</tr>
			
			<tr>
				<td>
				Indikator Keluaran
				</td>
				<td width="1">
				:
				</td>
				<td>
				<?php 
								
				$keluaran = null;
				$hasil = null;
				foreach($indikator as $key => $val)
					{
					if (( $val->jns_indikator)==2) {
					$keluaran .=   $val->kd_indikator . '. ' . $val->tolak_ukur . ' : ' . $val->target . ' ' . $val->satuan . '<br>' ;
									
					}

					}
					
					echo  $keluaran ;
									
					?>
					</td>
						</tr>
						<tr>
				<td>
				Indikator Hasil
				</td>
				<td width="1">
				:
				</td>
				<td>
				<?php 
								
				
				foreach($indikator as $key => $val)
					{
					if (( $val->jns_indikator)==3) {
					$hasil .=  $val->tolak_ukur . ' : ' . $val->target . ' ' .  $val->satuan . '<br>' ;
									
					}

					}
					
					echo  $hasil ;
									
					?>
					</td>
						</tr>
			
	</table>
		<br />
		<br />
		<br />
		<ol type="A">
			<li>
				<p>
					<b>
						LATAR BELAKANG
					</b>
				</p>
				<ol>
					<li>
						<p>
							Dasar Hukum
						</p>
						<?php
							if($dasar_hukum)
							{
								$list					= null;
								foreach($dasar_hukum as $key => $val)
								{
									$list				.= '<li align="justify">' . $val . '</li>';
								}
								echo '<ol type="a">' . $list . '</ol>';
							}
							else
							{
								echo '<p><i class="text-muted">Dasar hukum belum diisi</i></p>';
							}
						?>
					</li>
					<li>
						<p>
							Gambaran Umum
						</p>
						<ol type="a">
							<li align="justify">
								Latar Belakang Kegiatan
								<p>
									<?php echo (isset($gambaran_umum->a) ? $gambaran_umum->a : '<i class="text-muted">Latar belakang kegiatan belum diisi</i>'); ?>
								</p>
							</li>
							<li>
								Maksud dan Tujuan Kegiatan
								<?php
									if(isset($gambaran_umum->b))
									{
										$list					= null;
										foreach($gambaran_umum->b as $key => $val)
										{
											$list			.= '<li align="justify">' . $val . '</li>';
										}
										echo '<ul>' . $list . '</ul>';
									}
									else
									{
										echo '<p><i class="text-muted">Gambaran umum belum diisi</i></p>';
									}
								?>
							</li>
							<li align="justify">
								Gambaran umum mengenai keluaran (output) kegiatan
								<p>
									<?php echo (isset($gambaran_umum->c) ? $gambaran_umum->c : '<i class="text-muted">Keluaran (output) kegiatan belum diisi</i>'); ?>
								</p>
							</li>
							<li align="justify">
								Lokasi pelaksanaan kegiatan / lokasi output yang dihasilkan / lokasi penerima manfaat atas output yang dihasilkan (bila kegiatan berbentuk infrastruktur, sertakan peta lokasi dan informasi lainnya yang dianggap perlu)
								<p>
									<?php echo (isset($gambaran_umum->e) ? $gambaran_umum->e : '<i class="text-muted">Lokasi kegiatan belum diisi</i>'); ?>
								</p>
							</li>
						</ol>
					</li>
				</ol>
			</li>
			<li>
				<p>
					<b>
						PENERIMA MANFAAT
					</b>
				</p>
				<p>
					<?php echo ($penerima_manfaat ? $penerima_manfaat : '<i class="text-muted">Penerima maanfaat belum diisi</i>'); ?>
				</p>
			</li>
			<li>
				<p>
					<b>
						STRATEGI PENCAPAIAN KELUARAN (OUTPUT)
					</b>
				</p>
				<ol>
					<li>
						<p>
							Metode Pelaksanaan
						</p>
						<ol type="a">
							<li>
								<p>
									Metode Pengadaan Barang / Jasa
								</p>
								<table class="table" style="width:400px">
									<tr>
										<td class="border">
											Kontraktual
										</td>
										<td class="border text-center" width="100">
											<?php echo (isset($metode_pelaksanaan['a'][1]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											Swakelola
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['a'][2]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											Paket Pekerjaan
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['a'][4]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											<?php echo (isset($metode_pelaksanaan['a'][3]) ? $metode_pelaksanaan['a'][3] : 'Lainnya'); ?>
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['a'][3]) ? '&#10004;' : null); ?>
										</td>
									</tr>
								</table>
								<i>
									Ket: metode pengadaan bertanda &#10004; adalah yang digunakan
								</i>
							</li>
							<li>
								<p>
									Metode Pelaksanaan Kegiatan
								</p>
								<table class="table" style="width:400px">
									<tr>
										<td class="border">
											Pembangunan Fisik
										</td>
										<td class="border text-center" width="100">
											<?php echo (isset($metode_pelaksanaan['b'][1]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											Sosialisasi / Bimtek / Workshop
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['b'][2]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											Pengujian Laboratorium
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['b'][3]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											Kajian / Analisa / Perencanaan
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['b'][4]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											Jaringan IT
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['b'][5]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											Pengawasan / Penertiban
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['b'][6]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											Pelayanan Kesehatan / Pendidikan / Kebersihan
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['b'][7]) ? '&#10004;' : null); ?>
										</td>
									</tr>
									<tr>
										<td class="border">
											<?php echo (isset($metode_pelaksanaan['b'][8]) ? $metode_pelaksanaan['b'][8] : 'Lainnya'); ?>
										</td>
										<td class="border text-center">
											<?php echo (isset($metode_pelaksanaan['b'][8]) ? '&#10004;' : null); ?>
										</td>
									</tr>
								</table>
								<p>
									<i>
										Ket: metode pelaksanaan bertanda &#10004; adalah yang digunakan
									</i>
								</p>
							</li>
							<li>
								<p>
									Rencana Jenis Kebutuhan Belanja
								</p>
								<table class="table">
									<tr>
										<th class="border text-center" width="60%">
											Jenis Belanja
										</th>
										<th class="border text-center">
											Isi (*)
										</th>
										<th class="border text-center">
											Perkiraan
											<br />
											Unit / Volume / Orang
										</th>
										<th class="border text-center">
											Satuan
										</th>
									</tr>
									<tr>
										<td class="border">
											<b>
												1. Pegawai
											</b>
										</td>
										<td class="border">
											&nbsp;
										</td>
										<td class="border">
											&nbsp;
										</td>
										<td class="border">
											&nbsp;
										</td>
									</tr>
									<?php
										$alpha						= range('a', 'z');
										$initial					= 1;
										if(isset($metode_pelaksanaan['c'][$initial]))
										{
											$num					= 1;
											foreach($metode_pelaksanaan['c'][$initial] as $key => $val)
											{
												echo '
													<tr class="kebutuhan-belanja-input pegawai">
														<td class="border">
															<ol start="' . $num . '">
																<li style="margin-bottom:0;padding-bottom:0">
																	' . (isset($val['label']) ? $val['label'] : null) . '
																</li>
															</ol>
														</td>
														<td class="border text-center">
															' . (isset($val['isi']) ? '&#10004;' : null) . '
														</td>
														<td class="border text-center">
															' . (isset($val['volume']) ? $val['volume'] : null) . '
														</td>
														<td class="border">
															' . (isset($val['satuan']) ? $val['satuan'] : null) . '
														</td>
													</tr>
												';
												$num++;
											}
										}
									?>
									<tr>
										<td class="border">
											<b>
												2. Belanja Barang
											</b>
										</td>
										<td class="border">
											&nbsp;
										</td>
										<td class="border">
											&nbsp;
										</td>
										<td class="border">
											&nbsp;
										</td>
									</tr>
									<?php
										$initial					= 2;
										if(isset($metode_pelaksanaan['c'][$initial]))
										{
											$num					= 1;
											foreach($metode_pelaksanaan['c'][$initial] as $key => $val)
											{
												echo '
													<tr class="kebutuhan-belanja-input pegawai">
														<td class="border">
															<ol start="' . $num . '">
																<li style="margin-bottom:0;padding-bottom:0">
																	' . (isset($val['label']) ? $val['label'] : null) . '
																</li>
															</ol>
														</td>
														<td class="border text-center">
															' . (isset($val['isi']) ? '&#10004;' : null) . '
														</td>
														<td class="border text-center">
															' . (isset($val['volume']) ? $val['volume'] : null) . '
														</td>
														<td class="border">
															' . (isset($val['satuan']) ? $val['satuan'] : null) . '
														</td>
													</tr>
												';
												$num++;
											}
										}
									?>
									<tr>
										<td class="border">
											<b>
												3. Belanja Jasa Lainnya
											</b>
										</td>
										<td class="border">
											&nbsp;
										</td>
										<td class="border">
											&nbsp;
										</td>
										<td class="border">
											&nbsp;
										</td>
									</tr>
									<?php
										$initial					= 3;
										if(isset($metode_pelaksanaan['c'][$initial]))
										{
											$num					= 1;
											foreach($metode_pelaksanaan['c'][$initial] as $key => $val)
											{
												echo '
													<tr class="kebutuhan-belanja-input pegawai">
														<td class="border">
															<ol start="' . $num . '">
																<li style="margin-bottom:0;padding-bottom:0">
																	' . (isset($val['label']) ? $val['label'] : null) . '
																</li>
															</ol>
														</td>
														<td class="border text-center">
															' . (isset($val['isi']) ? '&#10004;' : null) . '
														</td>
														<td class="border text-center">
															' . (isset($val['volume']) ? $val['volume'] : null) . '
														</td>
														<td class="border">
															' . (isset($val['satuan']) ? $val['satuan'] : null) . '
														</td>
													</tr>
												';
												$num++;
											}
										}
									?>
									<tr>
										<td class="border">
											<b>
												4. Belanja Modal
											</b>
										</td>
										<td class="border">
											&nbsp;
										</td>
										<td class="border">
											&nbsp;
										</td>
										<td class="border">
											&nbsp;
										</td>
									</tr>
									<?php
										$initial					= 4;
										if(isset($metode_pelaksanaan['c'][$initial]))
										{
											$num					= 1;
											foreach($metode_pelaksanaan['c'][$initial] as $key => $val)
											{
												echo '
													<tr class="kebutuhan-belanja-input pegawai">
														<td class="border">
															<ol start="' . $num . '">
																<li style="margin-bottom:0;padding-bottom:0">
																	' . (isset($val['label']) ? $val['label'] : null) . '
																</li>
															</ol>
														</td>
														<td class="border text-center">
															' . (isset($val['isi']) ? '&#10004;' : null) . '
														</td>
														<td class="border text-center">
															' . (isset($val['volume']) ? $val['volume'] : null) . '
														</td>
														<td class="border">
															' . (isset($val['satuan']) ? $val['satuan'] : null) . '
														</td>
													</tr>
												';
												$num++;
											}
										}
									?>
								</table>
								<p>
									<i>
										Ket: isi bertanda &#10004; adalah jenis kebutuhan belanja yang akan dilaksanakan
									</i>
								</p>
							</li>
						</ol>
					</li>
					<li>
						<p>
							Tahapan dan Waktu Pelaksanaan
						</p>
						<ol type="a">
							<li>
								<p>
									Tahapan
								</p>
								<table class="table">
									<tr>
										<th rowspan="2" class="border text-center">
											TAHAPAN
										</th>
										<th colspan="14" class="border text-center">
											BULAN KE-
										</th>
									</tr>
									<tr>
										<th class="border text-center">
											1
										</th>
										<th class="border text-center">
											2
										</th>
										<th class="border text-center">
											3
										</th>
										<th class="border text-center">
											4
										</th>
										<th class="border text-center">
											5
										</th>
										<th class="border text-center">
											6
										</th>
										<th class="border text-center">
											7
										</th>
										<th class="border text-center">
											8
										</th>
										<th class="border text-center">
											9
										</th>
										<th class="border text-center">
											10
										</th>
										<th class="border text-center">
											11
										</th>
										<th class="border text-center">
											12
										</th>
										<th class="border text-center">
											Bobot (%)
										</th>
										<th class="border text-center">
											Keluaran
										</th>
									</tr>
									<tr>
										<td colspan="15" class="border">
											<b>
												Persiapan
											</b>
										</td>
									</tr>
									<?php
										if(isset($tahapan_pelaksanaan['persiapan']))
										{
											foreach($tahapan_pelaksanaan['persiapan']['label'] as $key => $val)
											{
												echo '
													<tr>
														<td class="border">
															<ol start="' . ($key + 1) . '">
																<li style="margin:0">
																	' . $val . '
																</li>
															</ol>
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][1][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][1][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][2][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][2][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][3][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][3][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][4][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][4][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][5][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][5][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][6][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][6][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][7][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][7][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][8][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][8][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][9][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][9][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][10][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][10][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][11][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][11][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][12][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['persiapan'][12][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="60" class="text-right v-middle border">
															' . (isset($tahapan_pelaksanaan['persiapan']['bobot'][$key]) && is_numeric($tahapan_pelaksanaan['persiapan']['bobot'][$key]) ? number_format($tahapan_pelaksanaan['persiapan']['bobot'][$key], 2) : 0) . '
														</td>
														<td width="60" class="v-middle border">
															' . (isset($tahapan_pelaksanaan['persiapan']['keluaran'][$key]) ? $tahapan_pelaksanaan['persiapan']['keluaran'][$key] : '') . '
														</td>
													</tr>
												';
											}
										}
									?>
									<tr>
										<td colspan="15" class="border">
											<b>
												Pelaksanaan
											</b>
										</td>
									</tr>
									<?php
										if(isset($tahapan_pelaksanaan['pelaksanaan']))
										{
											foreach($tahapan_pelaksanaan['pelaksanaan']['label'] as $key => $val)
											{
												echo '
													<tr>
														<td class="border">
															<ol start="' . ($key + 1) . '">
																<li style="margin:0">
																	' . $val . '
																</li>
															</ol>
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][1][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][1][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][2][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][2][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][3][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][3][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][4][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][4][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][5][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][5][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][6][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][6][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][7][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][7][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][8][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][8][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][9][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][9][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][10][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][10][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][11][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][11][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][12][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaksanaan'][12][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="60" class="text-right v-middle border">
															' . (isset($tahapan_pelaksanaan['pelaksanaan']['bobot'][$key]) && is_numeric($tahapan_pelaksanaan['pelaksanaan']['bobot'][$key]) ? number_format($tahapan_pelaksanaan['pelaksanaan']['bobot'][$key], 2) : 0) . '
														</td>
														<td width="60" class="v-middle border">
															' . (isset($tahapan_pelaksanaan['pelaksanaan']['keluaran'][$key]) ? $tahapan_pelaksanaan['pelaksanaan']['keluaran'][$key] : '') . '
														</td>
													</tr>
												';
											}
										}
									?>
									<tr>
										<td colspan="15" class="border">
											<b>
												Pelaporan
											</b>
										</td>
									</tr>
									<?php
										if(isset($tahapan_pelaksanaan['pelaporan']))
										{
											foreach($tahapan_pelaksanaan['pelaporan']['label'] as $key => $val)
											{
												echo '
													<tr>
														<td class="border">
															<ol start="' . ($key + 1) . '">
																<li style="margin:0">
																	' . $val . '
																</li>
															</ol>
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][1][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][1][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][2][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][2][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][3][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][3][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][4][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][4][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][5][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][5][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][6][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][6][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][7][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][7][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][8][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][8][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][9][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][9][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][10][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][10][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][11][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][11][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][12][$key]) ? ' bgcolor="#aabbcc"' : null) . '>
															' . (isset($tahapan_pelaksanaan['pelaporan'][12][$key]) ? '&#10004;' : null) . '
														</td>
														<td width="60" class="text-right v-middle border">
															' . (isset($tahapan_pelaksanaan['pelaporan']['bobot'][$key]) && is_numeric($tahapan_pelaksanaan['pelaporan']['bobot'][$key]) ? number_format($tahapan_pelaksanaan['pelaporan']['bobot'][$key], 2) : 0) . '
														</td>
														<td width="60" class="v-middle border">
															' . (isset($tahapan_pelaksanaan['pelaporan']['keluaran'][$key]) ? $tahapan_pelaksanaan['pelaporan']['keluaran'][$key] : '') . '
														</td>
													</tr>
												';
											}
										}
									?>
								</table>
							</li>
							<li>
								<p>
									Waktu Pelaksanaan
								</p>
								<p>
									<?php echo (isset($kak->waktu_pelaksanaan) && $kak->waktu_pelaksanaan ? $kak->waktu_pelaksanaan : '<i class="text-muted">Waktu pelaksanaan belum diisi</i>'); ?>
								</p>
							</li>
						</ol>
					</li>
				</ol>
			</li>
			<li>
				<b>
					PERKIRAAN ANGGARAN BIAYA DAN SUMBER DANA
				</b>
				<ol type="a">
					<li>
						Perkiraan Anggaran
						<p>
							Rp. <?php echo number_format((isset($biaya['a']) ? $biaya['a'] : 0)); ?>
							<br />
							Terbilang: <?php echo spell_number((isset($biaya['a']) ? $biaya['a'] : 0)); ?>
						</p>
					</li>
					<li>
						Sumber Dana
						<br />
						<table class="table" style="width:400px">
							<tr>
								<td class="border">
									PAD / DDL
								</td>
								<td class="border text-center" width="100">
									<?php echo (isset($biaya['b'][1]) && 1 == $biaya['b'][1] ? '&#10004;' : null); ?>
								</td>
							</tr>
							<tr>
								<td class="border">
									Dana Alokasi Khusus
								</td>
								<td class="border text-center">
									<?php echo (isset($biaya['b'][2]) && 1 == $biaya['b'][2] ? '&#10004;' : null); ?>
								</td>
							</tr>
							<tr>
								<td class="border">
									Dana Alokasi Umum
								</td>
								<td class="border text-center">
									<?php echo (isset($biaya['b'][3]) && 1 == $biaya['b'][3] ? '&#10004;' : null); ?>
								</td>
							</tr>
							<tr>
								<td class="border">
									Dana Insentif Daerah
								</td>
								<td class="border text-center">
									<?php echo (isset($biaya['b'][4]) && 1 == $biaya['b'][4] ? '&#10004;' : null); ?>
								</td>
							</tr>
							<tr>
								<td class="border">
									Bantuan Provinsi DKI Jakarta
								</td>
								<td class="border text-center">
									<?php echo (isset($biaya['b'][5]) && 1 == $biaya['b'][5] ? '&#10004;' : null); ?>
								</td>
							</tr>
							<tr>
								<td class="border">
									Bantuan Provinsi Jawa Barat
								</td>
								<td class="border text-center">
									<?php echo (isset($biaya['b'][6]) && 1 == $biaya['b'][6] ? '&#10004;' : null); ?>
								</td>
							</tr>
							<tr>
								<td class="border">
									DANA JKN
								</td>
								<td class="border text-center">
									<?php echo (isset($biaya['b'][7]) && 1 == $biaya['b'][7] ? '&#10004;' : null); ?>
								</td>
							</tr>

						</table>
						<p>
							<i>
								Ket: jenis sumber dana bertanda &#10004; adalah yang digunakan
							</i>
						</p>
					</li>
				</ol>
			</li>
		</ol>
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="text-center" width="50%">
				</td>
				<td class="text-center" width="50%">
					<?php //echo $nama_daerah; ?>, <?php //echo $tanggal_cetak; ?>
					<br />
					<b>
						<?php echo (isset($header->nama_jabatan) ? $header->nama_jabatan : '-'); ?>
					</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u><b><?php echo (isset($header->nama_pejabat) ? $header->nama_pejabat : '-'); ?></b></u>
					<br />
					NIP <?php echo (isset($header->nip_pejabat) ? $header->nip_pejabat : '-'); ?>
				</td>
			</tr>
		</table>
	</body>
</html>