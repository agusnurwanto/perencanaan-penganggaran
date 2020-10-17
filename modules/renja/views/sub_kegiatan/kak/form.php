<?php
	$field							= $results->form_data;
	$dasar_hukum					= json_decode($field->dasar_hukum->original);
	$gambaran_umum					= json_decode($field->gambaran_umum->original);
	$penerima_manfaat				= $field->penerima_manfaat->original;
	$metode_pelaksanaan				= json_decode($field->metode_pelaksanaan->original, true);
	$tahapan_pelaksanaan			= json_decode($field->tahapan_pelaksanaan->original, true);
	$waktu_pelaksanaan				= $field->waktu_pelaksanaan->original;
	$biaya							= json_decode($field->biaya->original, true);
?>
<div class="container-fluid pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? 12 : 12); ?>">
				<div class="form-group">
					<label class="control-label big-label text-muted text-uppercase">
						Dasar Hukum
					</label>
					<p class="kak-input relative d-none">
						<button type="button" class="btn btn-danger btn-xs btn-holo absolute float-right kak-remove"><i class="fa fa-times"></i> Hapus</button>
						<textarea name="dasar_hukum[]" class="form-control" placeholder="Silakan ketik item Dasar Hukum" disabled></textarea>
					</p>
					<?php
						if($dasar_hukum)
						{
							foreach($dasar_hukum as $key => $val)
							{
								echo '
									<p class="kak-input relative">
										<button type="button" class="btn btn-danger btn-xs btn-holo absolute float-right kak-remove"><i class="fa fa-times"></i> Hapus</button>
										<textarea name="dasar_hukum[]" class="form-control" placeholder="Silakan ketik item Dasar Hukum">' . $val . '</textarea>
									</p>
								';
							}
						}
					?>
					<button type="button" class="btn btn-info btn-xs tambah-kak-input">
						<i class="fa fa-plus"></i>
						Tambah
					</button>
					<br />
					<br />
				</div>
				<div class="form-group">
					<label class="control-label big-label text-muted text-uppercase">
						Gambaran Umum
					</label>
					<ol type="a">
						<li>
							<label class="control-label big-label" for="latar_belakang_kegiatan">
								Latar Belakang Kegiatan
							</label>
							<textarea name="gambaran_umum[a]" class="form-control" id="latar_belakang_kegiatan" placeholder="Silakan ketik latar belakang kegiatan"><?php echo (isset($gambaran_umum->a) ? $gambaran_umum->a : null); ?></textarea>
						</li>
						<li>
							<label class="control-label big-label" for="maksud_dan_tujuan">
								Maksud dan Tujuan Kegiatan
							</label>
							<p class="kak-input relative d-none">
								<button type="button" class="btn btn-danger btn-xs btn-holo absolute float-right kak-remove"><i class="fa fa-times"></i> Hapus</button>
								<textarea name="gambaran_umum[b][]" class="form-control" id="maksud_dan_tujuan" placeholder="Silakan ketik item Maksud dan Tujuan Kegiatan" disabled></textarea>
							</p>
							<?php
								if(isset($gambaran_umum->b))
								{
									foreach($gambaran_umum->b as $key => $val)
									{
										echo '
											<p class="kak-input relative">
												<button type="button" class="btn btn-danger btn-xs btn-holo absolute float-right kak-remove"><i class="fa fa-times"></i> Hapus</button>
												<textarea name="gambaran_umum[b][]" class="form-control" id="maksud_dan_tujuan" placeholder="Silakan ketik item Dasar Hukum">' . $val . '</textarea>
											</p>
										';
									}
								}
							?>
							<button type="button" class="btn btn-info btn-xs tambah-kak-input">
								<i class="fa fa-plus"></i>
								Tambah
							</button>
							<br />
							<br />
						</li>
						<li>
							<label class="control-label big-label" for="output">
								Gambaran umum mengenai keluaran (output) kegiatan
							</label>
							<textarea name="gambaran_umum[c]" class="form-control" id="output" placeholder="Silakan ketik gambaran umum mengenai keluaran (output) kegiatan"><?php echo (isset($gambaran_umum->c) ? $gambaran_umum->c : null); ?></textarea>
						</li>
						<li>
							<label class="control-label big-label" for="volume">
								Volume / target yang akan dilaksanakan
							</label>
							<textarea name="gambaran_umum[d]" class="form-control" id="volume" placeholder="Silakan ketik volume / target yang akan dilaksanakan"><?php echo (isset($gambaran_umum->d) ? $gambaran_umum->d : null); ?></textarea>
						</li>
						<li>
							<label class="control-label big-label" for="lokasi_pelaksanaan">
								Lokasi pelaksanaan kegiatan / lokasi output yang dihasilkan / lokasi penerima manfaat atas output yang dihasilkan (bila kegiatan berbentuk infrastruktur, sertakan peta lokasi dan informasi lainnya yang dianggap perlu)
							</label>
							<textarea name="gambaran_umum[e]" class="form-control" id="lokasi_pelaksanaan" placeholder="Silakan ketik"><?php echo (isset($gambaran_umum->e) ? $gambaran_umum->e : null); ?></textarea>
						</li>
					</ol>
				</div>
				<br />
				<div class="form-group">
					<label class="control-label big-label text-muted text-uppercase" for="penerima_manfaat">
						Penerima Manfaat
					</label>
					<textarea name="penerima_manfaat" class="form-control" id="penerima_manfaat" placeholder="Silakan ketik penerima manfaat"><?php echo $penerima_manfaat; ?></textarea>
				</div>
				<br />
				<div class="form-group">
					<label class="control-label big-label text-muted text-uppercase">
						Strategi Pencapaian Keluaran (Output)
					</label>
					<ol>
						<li>
							<b>
								Metode Pelaksanaan
							</b>
							<ol type="a">
								<li>
									Metode Pengadaan Barang / Jasa
									<table class="table">
										<tr>
											<td>
												Kontraktual
											</td>
											<td width="100">
												<input type="checkbox" name="metode_pelaksanaan[a][1]" value="1"<?php echo (isset($metode_pelaksanaan['a'][1]) && 1 == $metode_pelaksanaan['a'][1] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Swakelola
											</td>
											<td>
												<input type="checkbox" name="metode_pelaksanaan[a][2]" value="1"<?php echo (isset($metode_pelaksanaan['a'][2]) && 1 == $metode_pelaksanaan['a'][2] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Paket Pekerjaan
											</td>
											<td>
												<input type="checkbox" name="metode_pelaksanaan[a][4]" value="1"<?php echo (isset($metode_pelaksanaan['a'][4]) && 1 == $metode_pelaksanaan['a'][4] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Lainnya
												<p class="other-input no-margin<?php echo (!isset($metode_pelaksanaan['a'][3]) || !$metode_pelaksanaan['a'][3] ? ' d-none' : null); ?>">
													<input type="text" name="metode_pelaksanaan[a][3]" class="form-control" value="<?php echo (isset($metode_pelaksanaan['a'][3]) ? $metode_pelaksanaan['a'][3] : null); ?>" placeholder="Metode pangadaan barang / jasa yang Anda maksud"<?php echo (!isset($metode_pelaksanaan['a'][3]) || !$metode_pelaksanaan['a'][3] ? ' disabled' : null); ?> />
												</p>
											</td>
											<td>
												<input type="checkbox" class="other-checked" value="1"<?php echo (isset($metode_pelaksanaan['a'][3]) && $metode_pelaksanaan['a'][3] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<i class="text-muted">
													Centang jenis metode pengadaan yang akan dilakukan
												</i>
											</td>
										</tr>
									</table>
								</li>
								<li>
									Metode pelaksanaan kegiatan
									<table class="table">
										<tr>
											<td>
												Pembangunan Fisik
											</td>
											<td width="100">
												<input type="checkbox" name="metode_pelaksanaan[b][1]" value="1"<?php echo (isset($metode_pelaksanaan['b'][1]) && 1 == $metode_pelaksanaan['b'][1] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Sosialisasi / Bimtek / Workshop
											</td>
											<td>
												<input type="checkbox" name="metode_pelaksanaan[b][2]" value="1"<?php echo (isset($metode_pelaksanaan['b'][2]) && 1 == $metode_pelaksanaan['b'][2] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Pengujian Laboratorium
											</td>
											<td>
												<input type="checkbox" name="metode_pelaksanaan[b][3]" value="1"<?php echo (isset($metode_pelaksanaan['b'][3]) && 1 == $metode_pelaksanaan['b'][3] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Kajian / Analisa / Perencanaan
											</td>
											<td>
												<input type="checkbox" name="metode_pelaksanaan[b][4]" value="1"<?php echo (isset($metode_pelaksanaan['b'][4]) && 1 == $metode_pelaksanaan['b'][4] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Jaringan IT
											</td>
											<td>
												<input type="checkbox" name="metode_pelaksanaan[b][5]" value="1"<?php echo (isset($metode_pelaksanaan['b'][5]) && 1 == $metode_pelaksanaan['b'][5] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Pengawasan / Penertiban
											</td>
											<td>
												<input type="checkbox" name="metode_pelaksanaan[b][6]" value="1"<?php echo (isset($metode_pelaksanaan['b'][6]) && 1 == $metode_pelaksanaan['b'][6] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Pelayanan Kesehatan / Pendidikan / Kebersihan
											</td>
											<td>
												<input type="checkbox" name="metode_pelaksanaan[b][7]" value="1"<?php echo (isset($metode_pelaksanaan['b'][7]) && 1 == $metode_pelaksanaan['b'][7] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td>
												Lainnya
												<p class="other-input no-margin<?php echo (!isset($metode_pelaksanaan['b'][8]) || !$metode_pelaksanaan['b'][8] ? ' d-none' : null); ?>">
													<input type="text" name="metode_pelaksanaan[b][8]" class="form-control" value="<?php echo (isset($metode_pelaksanaan['b'][8]) ? $metode_pelaksanaan['b'][8] : null); ?>" placeholder="Metode pangadaan barang / jasa yang Anda maksud"<?php echo (!isset($metode_pelaksanaan['b'][8]) || !$metode_pelaksanaan['b'][8] ? ' disabled' : null); ?> />
												</p>
											</td>
											<td>
												<input type="checkbox" class="other-checked" value="1"<?php echo (isset($metode_pelaksanaan['b'][8]) && $metode_pelaksanaan['b'][8] ? ' checked' : null); ?> />
											</td>
										</tr>
										<tr>
											<td colspan="2">
												<i class="text-muted">
													Centang jenis metode pelaksanaan yang akan dilakukan
												</i>
											</td>
										</tr>
									</table>
								</li>
								<li>
									Rencana jenis kebutuhan belanja
									<table class="table table-bordered">
										<tr>
											<th class="text-center">
												Jenis Belanja
											</th>
											<th class="text-center" width="50">
												Isi
											</th>
											<th class="text-center" width="100">
												Perkiraan / Unit / Volume / Orang
											</th>
											<th class="text-center" width="120">
												Satuan
											</th>
											<th class="text-center" width="100">
												Opsi
											</th>
										</tr>
										<tr class="kebutuhan-belanja-input d-none">
											<td>
												<ol>
													<li>
														<input type="text" class="form-control input-sm bordered" id="label" placeholder="Nama Belanja" />
													</li>
												</ol>
											</td>
											<td class="text-center">
												<input type="checkbox" id="isi" value="1" />
											</td>
											<td>
												<input type="number" class="form-control input-sm bordered" id="volume" placeholder="volume" />
											</td>
											<td>
												<input type="text" class="form-control input-sm bordered" id="satuan" placeholder="satuan" />
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-danger btn-block btn-xs btn-holo kebutuhan-belanja-remove">
													<i class="fa fa-times"></i>
													Hapus
												</button>
											</td>
										</tr>
										<tr>
											<th colspan="4">
												1. Pegawai
											</th>
											<th class="text-center">
												<button type="button" class="btn btn-info btn-block btn-xs tambah-kebutuhan-belanja" data-number="1" data-initial="pegawai" data-insert-before="#belanja-barang-jasa">
													<i class="fa fa-plus"></i>
													Tambah
												</button>
											</th>
										</tr>
										<?php
											$alpha						= range('a', 'z');
											$initial					= 1;
											if(isset($metode_pelaksanaan['c'][$initial]))
											{
												$num					= 1;
												foreach($metode_pelaksanaan['c'][$initial] as $key => $val)
												{
													if(!is_numeric($key))
													{
														$key			= array_search($key, $alpha);
													}
													echo '
														<tr class="kebutuhan-belanja-input pegawai">
															<td>
																<ol start="' . $num . '">
																	<li>
																		<input type="text" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][label]" value="' . (isset($val['label']) ? $val['label'] : null) . '" class="form-control input-sm bordered" id="label" placeholder="Nama Belanja" />
																	</li>
																</ol>
															</td>
															<td class="text-center">
																<input type="checkbox" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][isi]" id="isi" value="1"' . (isset($val['isi']) && 1 == $val['isi'] ? ' checked' : null) . ' />
															</td>
															<td>
																<input type="number" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][volume]" value="' . (isset($val['volume']) ? $val['volume'] : null) . '" class="form-control input-sm bordered" id="volume" placeholder="volume" />
															</td>
															<td>
																<input type="text" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][satuan]" value="' . (isset($val['satuan']) ? $val['satuan'] : null) . '" class="form-control input-sm bordered" id="satuan" placeholder="satuan" />
															</td>
															<td class="text-center">
																<button type="button" class="btn btn-danger btn-block btn-xs btn-holo kebutuhan-belanja-remove">
																	<i class="fa fa-times"></i>
																	Hapus
																</button>
															</td>
														</tr>
													';
													$num++;
												}
											}
										?>
										<tr id="belanja-barang-jasa">
											<th colspan="4">
												2. Belanja Barang dan Jasa
											</th>
											<th class="text-center">
												<button type="button" class="btn btn-info btn-block btn-xs tambah-kebutuhan-belanja" data-number="2" data-initial="barang-jasa" data-insert-before="#belanja-jasa-lainnya">
													<i class="fa fa-plus"></i>
													Tambah
												</button>
											</th>
										</tr>
										<?php
											$initial					= 2;
											if(isset($metode_pelaksanaan['c'][$initial]))
											{
												$num					= 1;
												foreach($metode_pelaksanaan['c'][$initial] as $key => $val)
												{
													if(!is_numeric($key))
													{
														$key			= array_search($key, $alpha);
													}
													echo '
														<tr class="kebutuhan-belanja-input barang-jasa">
															<td>
																<ol start="' . $num . '">
																	<li>
																		<input type="text" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][label]" value="' . (isset($val['label']) ? $val['label'] : null) . '" class="form-control input-sm bordered" id="label" placeholder="Nama Belanja" />
																	</li>
																</ol>
															</td>
															<td class="text-center">
																<input type="checkbox" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][isi]" id="isi" value="1"' . (isset($val['isi']) && 1 == $val['isi'] ? ' checked' : null) . ' />
															</td>
															<td>
																<input type="number" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][volume]" value="' . (isset($val['volume']) ? $val['volume'] : null) . '" class="form-control input-sm bordered" id="volume" placeholder="volume" />
															</td>
															<td>
																<input type="text" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][satuan]" value="' . (isset($val['satuan']) ? $val['satuan'] : null) . '" class="form-control input-sm bordered" id="satuan" placeholder="satuan" />
															</td>
															<td class="text-center">
																<button type="button" class="btn btn-danger btn-block btn-xs btn-holo kebutuhan-belanja-remove">
																	<i class="fa fa-times"></i>
																	Hapus
																</button>
															</td>
														</tr>
													';
													$num++;
												}
											}
										?>
										<tr id="belanja-jasa-lainnya">
											<th colspan="4">
												3. Belanja Jasa Lainnya
											</th>
											<th class="text-center">
												<button type="button" class="btn btn-info btn-block btn-xs tambah-kebutuhan-belanja" data-number="3" data-initial="jasa-lain" data-insert-before="#belanja-modal">
													<i class="fa fa-plus"></i>
													Tambah
												</button>
											</th>
										</tr>
										<?php
											$initial					= 3;
											if(isset($metode_pelaksanaan['c'][$initial]))
											{
												$num					= 1;
												foreach($metode_pelaksanaan['c'][$initial] as $key => $val)
												{
													if(!is_numeric($key))
													{
														$key			= array_search($key, $alpha);
													}
													echo '
														<tr class="kebutuhan-belanja-input jasa-lain">
															<td>
																<ol start="' . $num . '">
																	<li>
																		<input type="text" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][label]" value="' . (isset($val['label']) ? $val['label'] : null) . '" class="form-control input-sm bordered" id="label" placeholder="Nama Belanja" />
																	</li>
																</ol>
															</td>
															<td class="text-center">
																<input type="checkbox" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][isi]" id="isi" value="1"' . (isset($val['isi']) && 1 == $val['isi'] ? ' checked' : null) . ' />
															</td>
															<td>
																<input type="number" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][volume]" value="' . (isset($val['volume']) ? $val['volume'] : null) . '" class="form-control input-sm bordered" id="volume" placeholder="volume" />
															</td>
															<td>
																<input type="text" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][satuan]" value="' . (isset($val['satuan']) ? $val['satuan'] : null) . '" class="form-control input-sm bordered" id="satuan" placeholder="satuan" />
															</td>
															<td class="text-center">
																<button type="button" class="btn btn-danger btn-block btn-xs btn-holo kebutuhan-belanja-remove">
																	<i class="fa fa-times"></i>
																	Hapus
																</button>
															</td>
														</tr>
													';
													$num++;
												}
											}
										?>
										<tr id="belanja-modal">
											<th colspan="4">
												4. Belanja Modal
											</th>
											<th class="text-center">
												<button type="button" class="btn btn-info btn-block btn-xs tambah-kebutuhan-belanja" data-number="4" data-initial="belanja-modal" data-insert-before="#belanja-footer">
													<i class="fa fa-plus"></i>
													Tambah
												</button>
											</th>
										</tr>
										<?php
											$initial					= 4;
											if(isset($metode_pelaksanaan['c'][$initial]))
											{
												$num					= 1;
												foreach($metode_pelaksanaan['c'][$initial] as $key => $val)
												{
													if(!is_numeric($key))
													{
														$key			= array_search($key, $alpha);
													}
													echo '
														<tr class="kebutuhan-belanja-input belanja-modal">
															<td>
																<ol start="' . $num . '">
																	<li>
																		<input type="text" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][label]" value="' . (isset($val['label']) ? $val['label'] : null) . '" class="form-control input-sm bordered" id="label" placeholder="Nama Belanja" />
																	</li>
																</ol>
															</td>
															<td class="text-center">
																<input type="checkbox" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][isi]" id="isi" value="1"' . (isset($val['isi']) && 1 == $val['isi'] ? ' checked' : null) . ' />
															</td>
															<td>
																<input type="number" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][volume]" value="' . (isset($val['volume']) ? $val['volume'] : null) . '" class="form-control input-sm bordered" id="volume" placeholder="volume" />
															</td>
															<td>
																<input type="text" name="metode_pelaksanaan[c][' . $initial . '][' . $key . '][satuan]" value="' . (isset($val['satuan']) ? $val['satuan'] : null) . '" class="form-control input-sm bordered" id="satuan" placeholder="satuan" />
															</td>
															<td class="text-center">
																<button type="button" class="btn btn-danger btn-block btn-xs btn-holo kebutuhan-belanja-remove">
																	<i class="fa fa-times"></i>
																	Hapus
																</button>
															</td>
														</tr>
													';
													$num++;
												}
											}
										?>
										<tr id="belanja-footer">
											<td colspan="5">
												<i class="text-muted">
													Centang jenis kebutuhan yang akan dilakukan
												</i>
											</td>
										</tr>
									</table>
								</li>
							</ol>
						</li>
						<li>
							<b>
								Tahapan dan Waktu Pelaksanaan
							</b>
							<ol type="a">
								<li>
									Tahapan
									<table class="table table-bordered">
										<tr>
											<th rowspan="2" class="text-center">
												TAHAPAN
											</th>
											<th colspan="12" class="text-center">
												BULAN ke-
											</th>
											<th colspan="3" class="text-center">
												&nbsp;
											</th>
										</tr>
										<tr>
											<th class="text-center">
												1
											</th>
											<th class="text-center">
												2
											</th>
											<th class="text-center">
												3
											</th>
											<th class="text-center">
												4
											</th>
											<th class="text-center">
												5
											</th>
											<th class="text-center">
												6
											</th>
											<th class="text-center">
												7
											</th>
											<th class="text-center">
												8
											</th>
											<th class="text-center">
												9
											</th>
											<th class="text-center">
												10
											</th>
											<th class="text-center">
												11
											</th>
											<th class="text-center">
												12
											</th>
											<th class="text-center">
												Bobot (%)
											</th>
											<th class="text-center">
												Keluaran
											</th>
											<th class="text-center">
												Opsi
											</th>
										</tr>
										<tr>
											<th colspan="16">
												Persiapan
											</th>
										</tr>
										<tr class="metode-persiapan-input d-none">
											<td>
												<textarea name="tahapan_pelaksanaan[persiapan][label][]" class="form-control bordered" placeholder="Ketik persiapan" rows="1" disabled></textarea>
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][1][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][2][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][3][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][4][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][5][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][6][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][7][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][8][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][9][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][10][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][11][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[persiapan][12][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="number" class="form-control bordered sum_input text-right" data-id="total_1" name="tahapan_pelaksanaan[persiapan][bobot][]" value="0.00" min="0" max="100" disabled />
											</td>
											<td class="text-center">
												<input type="text" class="form-control bordered" name="tahapan_pelaksanaan[persiapan][keluaran][]" value="" maxlength="255" disabled />
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-danger btn-xs btn-holo metode-persiapan-remove" data-toggle="tooltip" title="Hapus">x</button>
											</td>
										</tr>
										<?php
											$total_bobot			= 0;
											if(isset($tahapan_pelaksanaan['persiapan']['label']))
											{
												foreach($tahapan_pelaksanaan['persiapan']['label'] as $key => $val)
												{
													$total_bobot	+= (isset($tahapan_pelaksanaan['persiapan']['bobot'][$key]) && is_numeric($tahapan_pelaksanaan['persiapan']['bobot'][$key]) ? $tahapan_pelaksanaan['persiapan']['bobot'][$key] : 0);
													
													echo '
														<tr class="metode-persiapan-input">
															<td>
																<textarea name="tahapan_pelaksanaan[persiapan][label][' . $key . ']" class="form-control bordered" placeholder="Ketik persiapan" rows="1">' . $val . '</textarea>
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][1][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][1][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][1][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][2][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][2][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][2][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][3][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][3][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][3][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][4][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][4][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][4][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][5][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][5][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][5][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][6][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][6][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][6][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][7][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][7][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][7][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][8][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][8][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][8][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][9][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][9][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][9][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][10][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][10][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][10][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][11][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][11][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][11][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[persiapan][12][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['persiapan'][12][$key]) && 1 == $tahapan_pelaksanaan['persiapan'][12][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="number" class="form-control bordered sum_input text-right" data-id="total_1" name="tahapan_pelaksanaan[persiapan][bobot][' . $key . ']" value="' . (isset($tahapan_pelaksanaan['persiapan']['bobot'][$key]) ? $tahapan_pelaksanaan['persiapan']['bobot'][$key] : null) . '" min="0" max="100" />
															</td>
															<td class="text-center">
																<input type="text" class="form-control bordered" name="tahapan_pelaksanaan[persiapan][keluaran][' . $key . ']" value="' . (isset($tahapan_pelaksanaan['persiapan']['keluaran'][$key]) ? $tahapan_pelaksanaan['persiapan']['keluaran'][$key] : null) . '" maxlength="255" />
															</td>
															<td class="text-center">
																<button type="button" class="btn btn-danger btn-xs btn-holo metode-persiapan-remove" data-toggle="tooltip" title="Hapus">x</button>
															</td>
														</tr>
													';
												}
											}
										?>
										<tr>
											<td>
												<button type="button" class="btn btn-info btn-xs tambah-metode-persiapan">
													<i class="fa fa-plus"></i>
													Tambah
												</button>
											</td>
											<td colspan="12" class="text-right">
												Total
											</td>
											<td class="text-right">
												<span class="sum_input_total text-right" id="total_1">
													<?php echo number_format($total_bobot, 2); ?>
												</span>
											</td>
											<td colspan="2">
												&nbsp;
											</td>
										</tr>
										<tr>
											<th colspan="16">
												Pelaksanaan
											</th>
										</tr>
										<tr class="metode-pelaksanaan-input d-none">
											<td>
												<textarea name="tahapan_pelaksanaan[pelaksanaan][label][]" class="form-control bordered" placeholder="Ketik pelaksanaan" rows="1" disabled></textarea>
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][1][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][2][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][3][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][4][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][5][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][6][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][7][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][8][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][9][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][10][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][11][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][12][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="number" class="form-control bordered sum_input text-right" data-id="total_2" name="tahapan_pelaksanaan[pelaksanaan][bobot][]" value="0.00" min="0" max="100" disabled />
											</td>
											<td class="text-center">
												<input type="text" class="form-control bordered" name="tahapan_pelaksanaan[pelaksanaan][keluaran][]" value="" maxlength="255" disabled />
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-danger btn-xs btn-holo metode-pelaksanaan-remove" data-toggle="tooltip" title="Hapus">x</button>
											</td>
										</tr>
										<?php
											$total_bobot			= 0;
											if(isset($tahapan_pelaksanaan['pelaksanaan']['label']))
											{
												foreach($tahapan_pelaksanaan['pelaksanaan']['label'] as $key => $val)
												{
													$total_bobot	+= (isset($tahapan_pelaksanaan['pelaksanaan']['bobot'][$key]) && is_numeric($tahapan_pelaksanaan['pelaksanaan']['bobot'][$key]) ? $tahapan_pelaksanaan['pelaksanaan']['bobot'][$key] : 0);
													echo '
														<tr class="metode-pelaksanaan-input">
															<td>
																<textarea name="tahapan_pelaksanaan[pelaksanaan][label][' . $key . ']" class="form-control bordered" placeholder="Ketik pelaksanaan" rows="1">' . $val . '</textarea>
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][1][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][1][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][1][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][2][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][2][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][2][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][3][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][3][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][3][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][4][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][4][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][4][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][5][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][5][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][5][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][6][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][6][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][6][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][7][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][7][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][7][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][8][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][8][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][8][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][9][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][9][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][9][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][10][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][10][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][10][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][11][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][11][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][11][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaksanaan][12][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaksanaan'][12][$key]) && 1 == $tahapan_pelaksanaan['pelaksanaan'][12][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="number" class="form-control bordered sum_input text-right" data-id="total_2" name="tahapan_pelaksanaan[pelaksanaan][bobot][' . $key . ']" value="' . (isset($tahapan_pelaksanaan['pelaksanaan']['bobot'][$key]) ? $tahapan_pelaksanaan['pelaksanaan']['bobot'][$key] : null) . '" min="0" max="100" />
															</td>
															<td class="text-center">
																<input type="text" class="form-control bordered" name="tahapan_pelaksanaan[pelaksanaan][keluaran][' . $key . ']" value="' . (isset($tahapan_pelaksanaan['pelaksanaan']['keluaran'][$key]) ? $tahapan_pelaksanaan['pelaksanaan']['keluaran'][$key] : null) . '" maxlength="255" />
															</td>
															<td class="text-center">
																<button type="button" class="btn btn-danger btn-xs btn-holo metode-pelaksanaan-remove" data-toggle="tooltip" title="Hapus">x</button>
															</td>
														</tr>
													';
												}
											}
										?>
										<tr>
											<td>
												<button type="button" class="btn btn-info btn-xs tambah-metode-pelaksanaan">
													<i class="fa fa-plus"></i>
													Tambah
												</button>
											</td>
											<td colspan="12" class="text-right">
												Total
											</td>
											<td class="text-right">
												<span class="sum_input_total text-right" id="total_2">
													<?php echo number_format($total_bobot, 2); ?>
												</span>
											</td>
											<td colspan="2">
												&nbsp;
											</td>
										</tr>
										<tr>
											<th colspan="16">
												Pelaporan
											</th>
										</tr>
										<tr class="metode-pelaporan-input d-none">
											<td>
												<textarea name="tahapan_pelaksanaan[pelaporan][label][]" class="form-control bordered" placeholder="Ketik pelaporan" rows="1" disabled></textarea>
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][1][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][2][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][3][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][4][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][5][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][6][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][7][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][8][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][9][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][10][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][11][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][12][]" value="1" disabled />
											</td>
											<td class="text-center">
												<input type="number" class="form-control bordered sum_input text-right" data-id="total_3" name="tahapan_pelaksanaan[pelaporan][bobot][]" value="0.00" min="0" max="100" disabled />
											</td>
											<td class="text-center">
												<input type="text" class="form-control bordered" name="tahapan_pelaksanaan[pelaporan][keluaran][]" value="" maxlength="255" disabled />
											</td>
											<td class="text-center">
												<button type="button" class="btn btn-danger btn-xs btn-holo metode-pelaporan-remove" data-toggle="tooltip" title="Hapus">x</button>
											</td>
										</tr>
										<?php
											$total_bobot			= 0;
											if(isset($tahapan_pelaksanaan['pelaporan']['label']))
											{
												foreach($tahapan_pelaksanaan['pelaporan']['label'] as $key => $val)
												{
													$total_bobot	+= (isset($tahapan_pelaksanaan['pelaporan']['bobot'][$key]) && is_numeric($tahapan_pelaksanaan['pelaporan']['bobot'][$key]) ? $tahapan_pelaksanaan['pelaporan']['bobot'][$key] : 0);
													echo '
														<tr class="metode-pelaporan-input">
															<td>
																<textarea name="tahapan_pelaksanaan[pelaporan][label][' . $key . ']" class="form-control bordered" placeholder="Ketik pelaporan" rows="1">' . $val . '</textarea>
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][1][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][1][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][1][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][2][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][2][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][2][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][3][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][3][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][3][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][4][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][4][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][4][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][5][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][5][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][5][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][6][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][6][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][6][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][7][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][7][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][7][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][8][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][8][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][8][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][9][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][9][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][9][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][10][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][10][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][10][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][11][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][11][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][11][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="checkbox" name="tahapan_pelaksanaan[pelaporan][12][' . $key . ']" value="1"' . (isset($tahapan_pelaksanaan['pelaporan'][12][$key]) && 1 == $tahapan_pelaksanaan['pelaporan'][12][$key] ? ' checked' : null) . ' />
															</td>
															<td class="text-center">
																<input type="number" class="form-control bordered sum_input text-right" data-id="total_3" name="tahapan_pelaksanaan[pelaporan][bobot][' . $key . ']" value="' . (isset($tahapan_pelaksanaan['pelaporan']['bobot'][$key]) ? $tahapan_pelaksanaan['pelaporan']['bobot'][$key] : null) . '" min="0" max="100" />
															</td>
															<td class="text-center">
																<input type="text" class="form-control bordered" name="tahapan_pelaksanaan[pelaporan][keluaran][' . $key . ']" value="' . (isset($tahapan_pelaksanaan['pelaporan']['keluaran'][$key]) ? $tahapan_pelaksanaan['pelaporan']['keluaran'][$key] : null) . '" maxlength="255" />
															</td>
															<td class="text-center">
																<button type="button" class="btn btn-danger btn-xs btn-holo metode-pelaporan-remove" data-toggle="tooltip" title="Hapus">x</button>
															</td>
														</tr>
													';
												}
											}
										?>
										<tr>
											<td>
												<button type="button" class="btn btn-info btn-xs tambah-metode-pelaporan">
													<i class="fa fa-plus"></i>
													Tambah
												</button>
											</td>
											<td colspan="12" class="text-right">
												Total
											</td>
											<td class="text-right">
												<span class="sum_input_total" id="total_3">
													<?php echo number_format($total_bobot, 2); ?>
												</span>
											</td>
											<td colspan="2">
												&nbsp;
											</td>
										</tr>
									</table>
								</li>
								<li>
									Waktu Pelaksanaan
									<p>
										<input type="text" name="waktu_pelaksanaan" class="form-control" value="<?php echo $waktu_pelaksanaan; ?>" placeholder="Silakan ketik waktu pelaksanaan" />
									</p>
								</li>
							</ol>
						</li>
					</ol>
					<br />
					<label class="control-label big-label text-muted text-uppercase">
						Perkiraan Anggaran Biaya dan Sumber Dana
					</label>
					<ol type="a">
						<li>
							Perkiraan Anggaran
							<div class="form-group">
								<div class="input-group">
									<div class="input-group-prepend">
										<span class="input-group-text">
											Rp.
										</span>
									</div>
									<input type="number" name="biaya[a]" class="form-control bordered" value="<?php echo (isset($biaya['a']) ? $biaya['a'] : 0); ?>" placeholder="Silakan ketik biaya" />
								</div>
							</div>
							<br />
						</li>
						<li>
							Sumber Dana
							<table class="table">
								<tr>
									<td>
										PAD / DDL
									</td>
									<td width="100">
										<input type="checkbox" name="biaya[b][1]" value="1"<?php echo (isset($biaya['b'][1]) && 1 == $biaya['b'][1] ? ' checked' : null); ?> />
									</td>
								</tr>
								<tr>
									<td>
										Dana Alokasi Khusus
									</td>
									<td>
										<input type="checkbox" name="biaya[b][2]" value="1"<?php echo (isset($biaya['b'][2]) && 1 == $biaya['b'][2] ? ' checked' : null); ?> />
									</td>
								</tr>
								<tr>
									<td>
										Dana Alokasi Umum
									</td>
									<td>
										<input type="checkbox" name="biaya[b][3]" value="1"<?php echo (isset($biaya['b'][3]) && 1 == $biaya['b'][3] ? ' checked' : null); ?> />
									</td>
								</tr>
								<tr>
									<td>
										Dana Insentif Daerah
									</td>
									<td>
										<input type="checkbox" name="biaya[b][4]" value="1"<?php echo (isset($biaya['b'][4]) && 1 == $biaya['b'][4] ? ' checked' : null); ?> />
									</td>
								</tr>
								<tr>
									<td>
										Bantuan Provinsi DKI Jakarta
									</td>
									<td>
										<input type="checkbox" name="biaya[b][5]" value="1"<?php echo (isset($biaya['b'][5]) && 1 == $biaya['b'][5] ? ' checked' : null); ?> />
									</td>
								</tr>
								<tr>
									<td>
										Bantuan Provinsi Jawa Barat
									</td>
									<td>
										<input type="checkbox" name="biaya[b][6]" value="1"<?php echo (isset($biaya['b'][6]) && 1 == $biaya['b'][6] ? ' checked' : null); ?> />
									</td>
								</tr>
								<tr>
									<td>
										DANA JKN
									</td>
									<td>
										<input type="checkbox" name="biaya[b][7]" value="1"<?php echo (isset($biaya['b'][7]) && 1 == $biaya['b'][7] ? ' checked' : null); ?> />
									</td>
								</tr>

								<tr>
									<td colspan="2">
										<i class="text-muted">
											Centang jenis sumber dana yang akan dilakukan
										</i>
									</td>
								</tr>
							</table>
						</li>
					</ol>
				</div>
				
				<div class="--validation-callback mb-0"></div>
				
			</div>
		</div>
		
		<?php echo ('modal' == $this->input->post('prefer') ? '<hr class="row" />' : '<div class="opt-btn-overlap-fix"></div><!-- fix the overlap -->'); ?>
		<div class="row<?php echo ('modal' != $this->input->post('prefer') ? ' opt-btn' : null); ?>">
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? '12 text-right' : 12); ?>">
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				
				<?php if('modal' == $this->input->post('prefer')) { ?>
				<button type="button" class="btn btn-link" data-dismiss="modal">
					<?php echo phrase('close'); ?>
					<em class="text-sm">(esc)</em>
				</button>
				<?php } else { ?>
				<a href="<?php echo go_to(null, $results->query_string); ?>" class="btn btn-link --xhr">
					<i class="mdi mdi-arrow-left"></i>
					<?php echo phrase('back'); ?>
				</a>
				<?php } ?>
				
				<button type="submit" class="btn btn-primary float-right">
					<i class="mdi mdi-check"></i>
					<?php echo phrase('submit'); ?>
					<em class="text-sm">(ctrl+s)</em>
				</button>
			</div>
		</div>
	</form>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		$('body').off('change.other-checked'),
		$('body').on('change.other-checked', '.other-checked', function(e)
		{
			e.preventDefault();
			if($(this).is(':checked'))
			{
				$(this).closest('tr').find('.other-input').removeClass('d-none'),
				$(this).closest('tr').find('.form-control').attr('disabled', false).focus()
			}
			else
			{
				$(this).closest('tr').find('.other-input').addClass('d-none'),
				$(this).closest('tr').find('.form-control').attr('disabled', true)
			}
		})
	})
</script>