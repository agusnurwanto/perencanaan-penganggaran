<?php
	$controller						=& get_instance();
	
	$dasar_hukum					= (isset($kak->dasar_hukum) ? json_decode($kak->dasar_hukum) : array());
	$gambaran_umum					= (isset($kak->gambaran_umum) ? json_decode($kak->gambaran_umum) : array());
	$penerima_manfaat				= (isset($kak->penerima_manfaat) ? $kak->penerima_manfaat : null);
	$metode_pelaksanaan				= (isset($kak->metode_pelaksanaan) ? json_decode($kak->metode_pelaksanaan, true) : array());
	$tahapan_pelaksanaan			= (isset($kak->tahapan_pelaksanaan) ? json_decode($kak->tahapan_pelaksanaan, true) : array());
	$waktu_pelaksanaan				= (isset($kak->waktu_pelaksanaan) ? $kak->waktu_pelaksanaan : null);
	$biaya							= (isset($kak->biaya) ? json_decode($kak->biaya, true) : array());
?>
<style type="text/css">
	.border
	{
		border: 1px solid rgba(0,0,0,.1)
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
	.highlighted
	{
		background: #9f9;
		border-bottom: 1px dotted #f00;
		padding-left: 8px;
		padding-right: 8px
	}
</style>
<div class="container-fluid pt-3 pb-3">
	<div class="row">
		<div class="col-md-10 offset-md-1">
			<h3 class="text-center">
				ASISTENSI KERANGKA ACUAN KERJA
			</h3>
			<table class="table table-sm">
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
						<?php echo (isset($header->nm_sub) ? $header->kegiatan : '-'); ?>
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
								if ($val->jns_indikator == 2) {
								$keluaran .= '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tolak_ukur', $key)) . '" name="tolak_ukur[' . $key . ']">' . $val->kd_indikator . '. ' . $val->tolak_ukur . ' : ' . $val->target . ' ' . $val->satuan . '</a><br>';
							
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
							$keluaran = null;
							$hasil = null;
							
							foreach($indikator as $key => $val)
							{
								if ($val->jns_indikator == 3) {
								$hasil.= '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tolak_ukur', $key)) . '" name="tolak_ukur[' . $key . ']">' . $val->tolak_ukur . ' : ' . $val->target . ' ' . $val->satuan . '</a><br>';


							}
							}
								echo  $hasil;
								
						 ?>
					</td>

				</tr>
				<tr>
					<td>
						Sub Kegiatan
					</td>
					<td width="1">
						:
					</td>
					<td>
						<?php echo (isset($header->kegiatan_sub) ? $header->kegiatan_sub : '-'); ?>
					</td>

				</tr>
			</table>
			<hr />
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
										$list				.= '<li align="justify"><a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('dasar_hukum', $key)) . '" name="dasar_hukum[' . $key . ']">' . $val . '</a></li>';
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
										
										<?php echo ($gambaran_umum ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('gambaran_umum','a')) . '" name=gambaran_umum[a][0]">' . $gambaran_umum->a . '</a>' : '<i class="text-muted">Latar Belakang belum diisi</i>'); ?>
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
												$list			.= '<li align="justify"><a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('gambaran_umum', 'b', $key)) . '" name="gambaran_umum[b][' . $key . ']">' . $val . '</a></li>';
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
										<?php echo (isset($gambaran_umum->c) && $gambaran_umum->c ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('gambaran_umum', 'c')) . '" name="gambaran_umum[c]">' . $gambaran_umum->c . '</a>' : '<i class="text-muted">Keluaran (output) kegiatan belum diisi</i>'); ?>
									</p>
								</li>
								<li align="justify">
									Lokasi pelaksanaan kegiatan / lokasi output yang dihasilkan / lokasi penerima manfaat atas output yang dihasilkan (bila kegiatan berbentuk infrastruktur, sertakan peta lokasi dan informasi lainnya yang dianggap perlu)
									<p>
										<?php echo (isset($gambaran_umum->e) && $gambaran_umum->e ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('gambaran_umum', 'e')) . '" name="gambaran_umum[e]">' . $gambaran_umum->e . '</a>' : '<i class="text-muted">Lokasi kegiatan belum diisi</i>'); ?>
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
						<?php echo ($penerima_manfaat ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('penerima_manfaat')) . '" name="penerima_manfaat">' . $penerima_manfaat . '</a>' : '<i class="text-muted">Penerima maanfaat belum diisi</i>'); ?>
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
									<table class="table table-sm" style="width:400px">
										<tr>
											<td class="border">
												Kontraktual
											</td>
											<td class="border text-center" width="100">
												<?php echo (isset($metode_pelaksanaan['a'][1]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'a', 1)) . '" name="metode_pelaksanaan[a][1]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Swakelola
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['a'][2]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'a', 2)) . '" name="metode_pelaksanaan[a][2]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Lainnya
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['a'][3]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'a', 3)) . '" name="metode_pelaksanaan[a][3]">&#10004;</a>' : '&nbsp;'); ?>
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
									<table class="table table-sm" style="width:400px">
										<tr>
											<td class="border">
												Pembangunan Fisik
											</td>
											<td class="border text-center" width="100">
												<?php echo (isset($metode_pelaksanaan['b'][1]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'b', 1)) . '" name="metode_pelaksanaan[b][1]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Sosialisasi / Bimtek / Workshop
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['b'][2]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'b', 2)) . '" name="metode_pelaksanaan[b][2]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Pengujian Laboratorium
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['b'][3]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'b', 3)) . '" name="metode_pelaksanaan[b][3]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Kajian / Analisa / Perencanaan
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['b'][4]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'b', 4)) . '" name="metode_pelaksanaan[b][4]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Jaringan IT
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['b'][5]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'b', 5)) . '" name="metode_pelaksanaan[b][5]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Pengawasan / Penertiban
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['b'][6]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'b', 6)) . '" name="metode_pelaksanaan[b][6]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Pelayanan Kesehatan / Pendidikan / Kebersihan
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['b'][7]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'b', 7)) . '" name="metode_pelaksanaan[b][7]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
										<tr>
											<td class="border">
												Lainnya
											</td>
											<td class="border text-center">
												<?php echo (isset($metode_pelaksanaan['b'][8]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'b', 8)) . '" name="metode_pelaksanaan[b][8]">&#10004;</a>' : '&nbsp;'); ?>
											</td>
										</tr>
									</table>
									<p>
										<i>
											Ket: metode pelaksanaan bertanda &#10004; adalah yang digunakan
										</i>
									</p>
								</li>
								<pagebreak />
								<li>
									<p>
										Rencana Jenis Kebutuhan Belanja
									</p>
									<table class="table table-sm">
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
																		' . (isset($val['label']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'label')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][label]">' . $val['label'] . '</a>' : null) . '
																	</li>
																</ol>
															</td>
															<td class="border text-center">
																' . (isset($val['isi']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'isi')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][isi]">&#10004;</a>' : '&nbsp;') . '</a>
															</td>
															<td class="border text-center">
																' . (isset($val['volume']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'volume')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][volume]">' . $val['volume'] . '</a>' : null) . '
															</td>
															<td class="border">
																' . (isset($val['satuan']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'satuan')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][satuan]">' . $val['satuan'] . '</a>' : null) . '
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
																		' . (isset($val['label']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'label')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][label]">' . $val['label'] . '</a>' : null) . '
																	</li>
																</ol>
															</td>
															<td class="border text-center">
																' . (isset($val['isi']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'isi')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][isi]">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td class="border text-center">
																' . (isset($val['volume']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'volume')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][volume]">' . $val['volume'] . '</a>' : null) . '
															</td>
															<td class="border">
																' . (isset($val['satuan']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'satuan')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][satuan]">' . $val['satuan'] . '</a>' : null) . '
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
																		' . (isset($val['label']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'label')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][label]">' . $val['label'] . '</a>' : null) . '
																	</li>
																</ol>
															</td>
															<td class="border text-center">
																' . (isset($val['isi']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'isi')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][isi]">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td class="border text-center">
																' . (isset($val['volume']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'volume')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][volume]">' . $val['volume'] . '</a>' : null) . '
															</td>
															<td class="border">
																' . (isset($val['satuan']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'satuan')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][satuan]">' . $val['satuan'] . '</a>' : null) . '
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
																		' . (isset($val['label']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'label')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][label]">' . $val['label'] . ' </a>' : null) . '
																	</li>
																</ol>
															</td>
															<td class="border text-center">
																' . (isset($val['isi']) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'isi')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][isi]">&#10004;</a>' : '&nbsp;') . '</a>
															</td>
															<td class="border text-center">
																' . (isset($val['volume']) && $val['volume'] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'volume')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][volume]">' . $val['volume'] . '</a>' : null) . '
															</td>
															<td class="border">
																' . (isset($val['satuan']) && $val['satuan'] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('metode_pelaksanaan', 'c', $initial, $key, 'satuan')) . '" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][satuan]">' . $val['satuan'] . '</a>' : null) . '
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
									<table class="table table-sm">
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
																		<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 'label', $key)) . '" name="tahapan_pelaksanaan[persiapan][label][' . $key . ']">' . $val . '</a>
																	</li>
																</ol>
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][1][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][1][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 1, $key)) . '" name="tahapan_pelaksanaan[persiapan][1][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][2][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][2][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 2, $key)) . '" name="tahapan_pelaksanaan[persiapan][2][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][3][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][3][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 3, $key)) . '" name="tahapan_pelaksanaan[persiapan][3][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][4][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][4][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 4, $key)) . '" name="tahapan_pelaksanaan[persiapan][4][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][5][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][5][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 5, $key)) . '" name="tahapan_pelaksanaan[persiapan][5][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][6][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][6][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 6, $key)) . '" name="tahapan_pelaksanaan[persiapan][6][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][7][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][7][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 7, $key)) . '" name="tahapan_pelaksanaan[persiapan][7][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][8][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][8][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 8, $key)) . '" name="tahapan_pelaksanaan[persiapan][8][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][9][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][9][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 9, $key)) . '" name="tahapan_pelaksanaan[persiapan][9][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][10][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][10][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 10, $key)) . '" name="tahapan_pelaksanaan[persiapan][10][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][11][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][11][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 11, $key)) . '" name="tahapan_pelaksanaan[persiapan][11][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['persiapan'][12][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['persiapan'][12][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 12, $key)) . '" name="tahapan_pelaksanaan[persiapan][12][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="60" class="text-right v-middle border">
																<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'persiapan', 'bobot', $key)) . '" name="tahapan_pelaksanaan[persiapan][bobot][' . $key . ']">' . (isset($tahapan_pelaksanaan['persiapan']['bobot'][$key]) ? number_format($tahapan_pelaksanaan['persiapan']['bobot'][$key], 2) : 0) . '</a>
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
																		<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 'label', $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][label][' . $key . ']">' . $val . '</a>
																	</li>
																</ol>
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][1][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][1][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 1, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][1][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][2][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][2][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 2, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][2][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][3][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][3][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 3, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][3][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][4][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][4][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 4, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][4][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][5][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][5][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 5, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][5][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][6][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][6][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 6, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][6][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][7][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][7][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 7, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][7][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][8][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][8][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 8, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][8][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][9][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][9][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 9, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][9][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][10][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][10][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 10, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][10][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][11][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][11][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 11, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][11][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaksanaan'][12][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaksanaan'][12][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 12, $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][12][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="60" class="text-right v-middle border">
																<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaksanaan', 'bobot', $key)) . '" name="tahapan_pelaksanaan[pelaksanaan][bobot][' . $key . ']">' . (isset($tahapan_pelaksanaan['pelaksanaan']['bobot'][$key]) ? number_format($tahapan_pelaksanaan['pelaksanaan']['bobot'][$key], 2) : 0) . '</a>
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
																		<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 'label', $key)) . '" name="tahapan_pelaksanaan[pelaporan][label][' . $key . ']">' . $val . '</a>
																	</li>
																</ol>
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][1][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][1][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 1, $key)) . '" name="tahapan_pelaksanaan[pelaporan][1][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][2][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][2][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 2, $key)) . '" name="tahapan_pelaksanaan[pelaporan][2][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][3][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][3][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 3, $key)) . '" name="tahapan_pelaksanaan[pelaporan][3][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][4][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][4][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 4, $key)) . '" name="tahapan_pelaksanaan[pelaporan][4][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][5][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][5][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 5, $key)) . '" name="tahapan_pelaksanaan[pelaporan][5][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][6][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][6][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 6, $key)) . '" name="tahapan_pelaksanaan[pelaporan][6][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][7][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][7][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 7, $key)) . '" name="tahapan_pelaksanaan[pelaporan][7][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][8][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][8][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 8, $key)) . '" name="tahapan_pelaksanaan[pelaporan][8][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][9][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][9][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 9, $key)) . '" name="tahapan_pelaksanaan[pelaporan][9][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][10][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][10][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 10, $key)) . '" name="tahapan_pelaksanaan[pelaporan][10][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][11][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][11][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 11, $key)) . '" name="tahapan_pelaksanaan[pelaporan][11][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="20" class="text-center v-middle border"' . (isset($tahapan_pelaksanaan['pelaporan'][12][$key]) ? ' bgcolor="ffffff"' : null) . '>
																' . (isset($tahapan_pelaksanaan['pelaporan'][12][$key]) ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 12, $key)) . '" name="tahapan_pelaksanaan[pelaporan][12][' . $key . ']">&#10004;</a>' : '&nbsp;') . '
															</td>
															<td width="60" class="text-right v-middle border">
																<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('tahapan_pelaksanaan', 'pelaporan', 'bobot', $key)) . '" name="tahapan_pelaksanaan[pelaporan][bobot][' . $key . ']">' . (isset($tahapan_pelaksanaan['pelaporan']['bobot'][$key]) ? number_format($tahapan_pelaksanaan['pelaporan']['bobot'][$key], 2) : 0) . '</a>
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
										<?php echo (isset($kak->waktu_pelaksanaan) && $kak->waktu_pelaksanaan ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('waktu_pelaksanaan')) . '" name="waktu_pelaksanaan">' . $kak->waktu_pelaksanaan . '</a>' : '<i class="text-muted">Waktu pelaksanaan belum diisi</i>'); ?>
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
								<a href="javascript:void(0)" class="highlighted" data-thread="<?php echo htmlspecialchars($controller->_get_comment('biaya', 'a')); ?>" name="biaya[a]">Rp. <?php echo number_format((isset($biaya['a']) ? $biaya['a'] : 0)); ?></a>
								<br />
								Terbilang: <?php echo spell_number((isset($biaya['a']) ? $biaya['a'] : 0)); ?>
							</p>
						</li>
						<li>
							Sumber Dana
							<br />
							<table class="table table-sm" style="width:400px">
								<tr>
									<td class="border">
										PAD / DDL
									</td>
									<td class="border text-center" width="100">
										<?php echo (isset($biaya['b'][1]) && 1 == $biaya['b'][1] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('biaya', 'b', 1)) . '" name="biaya[b][1]">&#10004;</a>' : '&nbsp;'); ?>
									</td>
								</tr>
								<tr>
									<td class="border">
										Dana Alokasi Khusus
									</td>
									<td class="border text-center">
										<?php echo (isset($biaya['b'][2]) && 1 == $biaya['b'][2] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('biaya', 'b', 2)) . '" name="biaya[b][2]">&#10004;</a>' : '&nbsp;'); ?>
									</td>
								</tr>
								<tr>
									<td class="border">
										Dana Alokasi Umum
									</td>
									<td class="border text-center">
										<?php echo (isset($biaya['b'][3]) && 1 == $biaya['b'][3] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('biaya', 'b', 3)) . '" name="biaya[b][3]">&#10004;</a>' : '&nbsp;'); ?>
									</td>
								</tr>
								<tr>
									<td class="border">
										Dana Insentif Daerah
									</td>
									<td class="border text-center">
										<?php echo (isset($biaya['b'][4]) && 1 == $biaya['b'][4] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('biaya', 'b', 4)) . '" name="biaya[b][4]">&#10004;</a>' : '&nbsp;'); ?>
									</td>
								</tr>
								<tr>
									<td class="border">
										Bantuan Provinsi DKI Jakarta
									</td>
									<td class="border text-center">
										<?php echo (isset($biaya['b'][5]) && 1 == $biaya['b'][5] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('biaya', 'b', 5)) . '" name="biaya[b][5]">&#10004;</a>' : '&nbsp;'); ?>
									</td>
								</tr>
								<tr>
									<td class="border">
										Bantuan Provinsi Jawa Barat
									</td>
									<td class="border text-center">
										<?php echo (isset($biaya['b'][6]) && 1 == $biaya['b'][6] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('biaya', 'b', 6)) . '" name="biaya[b][6]">&#10004;</a>' : '&nbsp;'); ?>
									</td>
								</tr>
								<tr>
									<td class="border">
										DANA JKN
									</td>
									<td class="border text-center">
										<?php echo (isset($biaya['b'][7]) && 1 == $biaya['b'][7] ? '<a href="javascript:void(0)" class="highlighted" data-thread="' . htmlspecialchars($controller->_get_comment('biaya', 'b', 7)) . '" name="biaya[b][7]">&#10004;</a>' : '&nbsp;'); ?>
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
			<br />
			<br />
			<table class="table table-sm table-bordered">
				<tfoot>
					<tr>
						<td class="text-center text-sm"">
							<b>PARAF TIM ASISTENSI</b>
						</td>
					</tr>
					<tr>
						<td class="text-sm text-center">
							<b>1. BAPPEDA 
								<a href="<?php echo current_page('../verifikasi_kak', array('target' => 'perencanaan')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($verified->perencanaan) && 1 == $verified->perencanaan ? 'active' : 'inactive'); ?> --modal">
									<span class="handle"></span>
								</a></b>
							<p class="verifikator text-center">
								<?php echo (isset($verified->perencanaan) && 1 == $verified->perencanaan ? 'Disetujui oleh <b>' . $verified->nama_operator_perencanaan . '</b> pada ' . date_indo($verified->waktu_verifikasi_perencanaan, 3, '-') : null); ?>
							</p>
						</td>
						<?php /*
						<td class="text-sm text-center">
							<b>2. BPKAD
								<a href="<?php echo current_page('../verifikasi_kak', array('target' => 'keuangan')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($verified->keuangan) && 1 == $verified->keuangan ? 'active' : 'inactive'); ?> ajax">
									<span class="handle"></span>
								</a></b>
							<p class="verifikator text-center">
								<?php echo (isset($verified->keuangan) && 1 == $verified->keuangan ? 'Disetujui oleh <b>' . $verified->nama_operator_keuangan . '</b> pada ' . date_indo($verified->waktu_verifikasi_keuangan, 3, '-') : null); ?>
							</p>
						</td>
						<td class="text-sm text-center">
							<b>3. Bagian Pembangunan Setda
								<a href="<?php echo current_page('../verifikasi_kak', array('target' => 'setda')); ?>" class="btn btn-toggle btn-sm <?php echo (isset($verified->setda) && 1 == $verified->setda ? 'active' : 'inactive'); ?> ajax">
									<span class="handle"></span>
								</a></b>
							<p class="verifikator text-center">
								<?php echo (isset($verified->setda) && 1 == $verified->setda ? 'Disetujui oleh <b>' . $verified->nama_operator_setda . '</b> pada ' . date_indo($verified->waktu_verifikasi_setda, 3, '-'): null); ?>
							</p>
						</td>
						*/ ?>
					</tr>
				</tfoot>
			</table>
		</div>
	</div>
</div>
<script type="text/javascript">
	$(document).ready(function()
	{
		$('body').off('click.highlighted touch.highlighted'),
		$('body').on('click.highlighted touch.highlighted', '.highlighted', function(e)
		{
			var offset_left					= $(this).offset().left,
				offset_right				= ($(window).width() - ($(this).offset().left + $(this).outerWidth())),
				outer_width					= $(this).outerWidth(true),
				thread						= '';
			
			if($(this).attr('data-thread'))
			{
				try
				{
					var comments			= $.parseJSON($(this).attr('data-thread'));
					
					$.each(comments, function(key, val)
					{
						thread				+= '<div class="text-sm text-left" style="border-bottom:1px solid #ddd"><b>' + val.operator + '</b> (' + val.tanggal + ')<br /><p>' + val.comments + '</p></div>';
					})
				}
				catch (err)
				{
					
				}
			}
			
			e.preventDefault(),
			$('.comment-form').remove(),
			$(
				'<form action="" method="POST" class="comment-form" style="position:absolute; width:320px; background:#fff; border:1px solid #ddd; padding:12px;' + (offset_left > offset_right ? 'margin-left:-' + (320 - outer_width) + 'px' : '') + '">' +
					thread +
					'<textarea name="' + $(this).attr('name') + '" class="form-control bordered" placeholder="Silakan masukkan keterangan..."></textarea>' +
					'<input type="hidden" name="do" value="comment" />' +
					'<div class="btn-group pull-right" style="margin-top:12px">' +
						'<button type="submit" class="btn btn-success btn-sm">' +
							'<i class="fa fa-check"></i>' +
							'Simpan' +
						'</button>' +
						'<button type="button" class="btn btn-danger btn-sm close-form">' +
							'<i class="fa fa-times"></i>' +
							'Batal' +
						'</button>' +
					'</div>' +
				'</form>'
			)
			.insertAfter($(this))
		}),
		
		$('body').off('submit.comment-form'),
		$('body').on('submit.comment-form', '.comment-form', function(e)
		{
			var formdata					= new FormData(this),
				thread						= JSON.parse($(this).prev('a.highlighted').attr('data-thread'));
			e.preventDefault(),
			$.ajax
			({
				url: '<?php echo current_page(); ?>',
				method: 'POST',
				data: formdata,
				processData: false,
				contentType: false,
				context: this
			})
			.done(function(response)
			{
				if(200 == response.status)
				{
					thread					= $.merge(thread, response.thread);
					
					$(this).prev('a.highlighted').attr('data-thread', JSON.stringify(thread)),
					$(this).remove()
				}
				else
				{
				}
			})
		}),
		
		$('body').off('click.close-form touch.close-form'),
		$('body').on('click.close-form touch.close-form', '.close-form', function(e)
		{
			$(this).closest('form').remove()
		})
	})
</script>