<?php
	$form_data						= $results->form_data;
?>
<div class="container-fluid pt-3 pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-10 col-md-offset-1">
				<div class="row">
					<div class="col-md-7">
						<?php
							echo '
								<div class="form-group">
									<label class="control-label big-label text-muted text-uppercase" for="kd_belanja_rinci_input">
										' . $form_data->kd_belanja_rinci->label . '
									</label>
									' . $form_data->kd_belanja_rinci->content . '
								</div>
								<div class="form-group">
									<label class="control-label big-label text-muted text-uppercase" for="uraian_input">
										' . $form_data->uraian->label . '
									</label>
									' . $form_data->uraian->content . '
								</div>
								<div class="form-group">
									<label class="control-label big-label text-muted text-uppercase" for="nilai_input">
										' . $form_data->nilai->label . '
									</label>
									' . $form_data->nilai->content . '
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label big-label text-muted text-uppercase" for="vol_1_input">
												' . $form_data->vol_1->label . '
											</label>
											' . $form_data->vol_1->content . '
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label big-label text-muted text-uppercase" for="satuan_1_input">
												' . $form_data->satuan_1->label . '
											</label>
											' . $form_data->satuan_1->content . '
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label big-label text-muted text-uppercase" for="vol_2_input">
												' . $form_data->vol_2->label . '
											</label>
											' . $form_data->vol_2->content . '
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label big-label text-muted text-uppercase" for="satuan_2_input">
												' . $form_data->satuan_2->label . '
											</label>
											' . $form_data->satuan_2->content . '
										</div>
									</div>
								</div>
								<div class="row">
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label big-label text-muted text-uppercase" for="vol_3_input">
												' . $form_data->vol_3->label . '
											</label>
											' . $form_data->vol_3->content . '
										</div>
									</div>
									<div class="col-sm-6">
										<div class="form-group">
											<label class="control-label big-label text-muted text-uppercase" for="satuan_3_input">
												' . $form_data->satuan_3->label . '
											</label>
											' . $form_data->satuan_3->content . '
										</div>
									</div>
								</div>
								<br />
								<div class="row form-group">
									<label class="control-label col-sm-12 big-label text-muted text-uppercase" for="satuan_123_input">
										' . $form_data->satuan_123->label . '
									</label>
									<div class="col-sm-12">
										' . $form_data->satuan_123->content . '
										<input type="hidden" name="satuan_123" class="satuan_total_hidden" value="" />
									</div>
								</div>
							';
						?>
					</div>
					<div class="col-md-5">
						<div class="form-group">
							<label class="control-label">
								<b>Variable yang tersedia</b>
							</label>
						</div>
						<div class="form-group">
							<?php
								if($variable)
								{
									$row			= null;
									
									foreach($variable as $key => $val)
									{
										$row		.= '
											<tr>
												<td align="center">
													' . $val->kd_variabel . '
												</td>
												<td>
													' . $val->nm_variabel . '
												</td>
												<td align="center">
												{' . $val->id . '}
												</td>
											</tr>
										';
									}
									echo '
										<table class="table table-bordered">
											<thead>
												<tr>
													<th align="center">
														No.
													</th>
													<th align="center">
														Variabel
													</th>
													<th align="center">
														Kode
													</th>
												</tr>
											</thead>
											<tbody>
												' . $row . '
											</tbody>
										</table>
									';
								}
								else
								{
									echo '<div class="alert alert-warning">Variabel belum tersedia</div>';
								}
							?>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
</div>