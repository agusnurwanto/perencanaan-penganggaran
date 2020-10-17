<?php
	$field							= $results['field_data'];
?>
<?php echo ($modal ? '<div class="container-fluid"><div class="row"><div class="col-md-10 col-md-offset-1">' : null); ?>
	<div class="box no-border">
		<div class="box-header with-border">
			<div class="box-tools pull-right">
				<?php if(!$modal) { ?>
				<a href="<?php echo current_page(); ?>" class="btn btn-box-tool ajaxLoad show_process">
					<i class="fa fa-refresh"></i>
				</a>
				<?php } ?>
				<button type="button" class="btn btn-box-tool" data-widget="collapse">
					<i class="fa fa-minus"></i>
				</button>
				<button type="button" class="btn btn-box-tool" data-widget="maximize">
					<i class="fa fa-expand"></i>
				</button>
				<button type="button" class="btn btn-box-tool" <?php echo ($modal ? 'data-dismiss="modal"' : 'data-widget="remove"'); ?>>
					<i class="fa fa-times"></i>
				</button>
			</div>
			<h3 class="box-title">
				<i class="<?php echo $icon; ?>"></i>
				&nbsp;
				<?php echo $title; ?>
			</h3>
		</div>
		<div class="form-group animated zoomIn">
			<?php echo $field['map_coordinates']['content']; ?>
		</div>
		<div class="box-body">
			<div class="row">
				<div class="col-md-10 col-md-offset-1">
					<div class="row">
						<div class="col-md-6">
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kode_input">
									Kode
								</label>
								<label class="control-label big-label">
									<?php echo $field['kd_urusan']['content']; ?>
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="id_reses_input">
									Dewan
								</label>
								<label class="control-label big-label">
									<?php echo (isset($view_data->kode_reses) ? $view_data->kode_reses : null) . '. ' . (isset($view_data->nama_dewan) ? $view_data->nama_dewan : null); ?>
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="isu_input">
									Isu
								</label>
								<label class="control-label big-label">
									<?php echo (isset($view_data->kode_isu) ? $view_data->kode_isu : null) . '. ' . (isset($view_data->nama_isu) ? $view_data->nama_isu : null); ?>
								</label>
							</div>
							<?php /*
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="jenis_pekerjaan_input">
									Jenis Kegiatan
								</label>
								<label class="control-label big-label">
									<?php echo $field['jns_kegiatan']['content']; ?>
								</label>
							</div>
							*/ ?>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kegiatan_input">
									Nama Kegiatan
								</label>
								<label class="control-label big-label">
									<?php echo (isset($field['kegiatan']['content']) ? $field['kegiatan']['content'] : ''); ?>
								</label>
							</div>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="kegiatan_input">
									Nilai Usulan
								</label>
								<label class="control-label big-label">
									<?php echo (isset($field['nilai_usulan']['content']) ? $field['nilai_usulan']['content'] : ''); ?>
								</label>
							</div>
						</div>
						<div class="col-md-6">
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="map_address_input">
									Alamat
								</label>
								<label class="control-label big-label">
									<?php echo $field['map_address']['content']; ?>
								</label>
							</div>
							<?php if(isset($view_data->alamat_detail)) { ?>
							<div class="form-group animated zoomIn address_details">
								<label class="control-label big-label text-muted text-uppercase" for="alamat_detail_input">
									Alamat Tambahan (RT/RW/Gang)
								</label>
								<label class="control-label big-label">
									<?php echo $view_data->alamat_detail; ?>
								</label>
							</div>
							<?php } ?>
							<div class="form-group animated zoomIn">
								<?php
									if(isset($view_data->survey) && is_array($view_data->survey) && sizeof($view_data->survey) > 0)
									{
										$output							= null;
										foreach($view_data->survey as $key => $val)
										{
											$output						.= '
												<label>
													' . $val->kode . '. 
													' . $val->pertanyaan . '
													<b class="text-' . (1 == $val->value ? 'success' : 'danger') . '">
														' . (1 == $val->value ? phrase('true') : phrase('false')) . '
													</b>
												</label>
											';
										}
										$output							= '
											<div class="panel panel-default">
												<div class="panel-heading no-padding">
													<a data-toggle="collapse" data-parent="#data-table" href="#collapse_data-table">
														<div class="info-box bg-default no-margin">
															<span class="info-box-icon">
																<i class="fa fa-info-circle"></i>
															</span>
															<div class="info-box-content">
																<span class="info-box-number">
																	Hasil Survey
																</span>
																<span class="info-box-text">
																	Klik untuk melihat detail
																</span>
															</div>
														</div>
													</a>
												</div>
												<div id="collapse_data-table" class="panel-collapse collapse">
													<div class="panel-body">
														' . $output . '
													</div>
												</div>
											</div>
										';
										echo $output;
									}
								?>
							</div>
							<div class="form-group animated zoomIn">
								<div class="alert alert-info">
									<?php echo $view_data->deskripsi; ?>
								</div>
								<?php
									if(isset($view_data->variabel_usulan) && is_array($view_data->variabel_usulan) && sizeof($view_data->variabel_usulan) > 0)
									{
										$rows							= null;
										foreach($view_data->variabel_usulan as $key => $val)
										{
											$rows						.= '
												<tr>
													<td>
														' . $val->kode_variabel . '
													</td>
													<td>
														' . $val->nama_variabel . '
													</td>
													<td>
														' . $val->value . ' ' . $val->satuan . '
													</td>
												</tr>
											';
										}
										echo '
											<table class="table">
												<thead>
													<tr>
														<th>
															Kode
														</td>
														<th>
															Variabel
														</th>
														<th>
															Value
														</th>
													</tr>
												</thead>
												<tbody>
													' . $rows . '
												</tbody>
											</table>
										';
									}
								?>
							</div>
							<?php if(isset($field['images']['content'])) { ?>
							<div class="form-group animated zoomIn">
								<label class="control-label big-label text-muted text-uppercase" for="images_input">
									Foto 
								</label>
								<?php echo $field['images']['content']; ?>
							</div>
							<?php } ?>
						</div>
					</div>
				</div>
			</div>
		</div>
		<div class="box-footer">
			<div class="row">
				<div class="col-md-5 col-md-offset-6 callback-status">
					<div class="btn-group btn-group-justified">
						<?php
							if($modal)
							{
								echo '
									<a href="javascript:void(0)" class="btn btn-primary btn-holo" data-dismiss="modal">
										<i class="fa fa-times"></i>
										&nbsp;
										' . phrase('cancel') . '
										<small class="hidden-xs hidden-sm" style="font-size:10px;color:#cacaca">(ESC)</small>
									</a>
								';
							}
							else
							{
								echo '
									<a href="' . go_to(null, array('id' => null)) . '" class="btn btn-primary btn-holo ajaxLoad">
										<i class="fa fa-chevron-left"></i>
										&nbsp;
										' . phrase('back') . '
									</a>
								';
							}
						?>
						<div class="btn-group">
							<a href="<?php echo go_to(null, array('id' => null)); ?>" class="btn btn-primary btn-holo ajaxLoad">
								<i class="fa fa-chevron-left"></i>
								&nbsp;
								<?php echo phrase('back'); ?>
							</a>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
<?php echo ($modal ? '</div></div></div>' : null); ?>