<?php if($modal) echo '<div class="container-fluid"><div class="row"><div class="col-sm-10 col-sm-offset-1">'; ?>
	<form action="<?php echo current_page(); ?>" method="POST" class="submitForm" data-save="<?php echo phrase('submit'); ?>" data-saving="<?php echo  phrase('submitting'); ?>" data-icon="check" data-alert="<?php echo phrase('unable_to_submit_your_data'); ?>" enctype="multipart/form-data">
		<div class="box no-border animated fadeIn">
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
					<button type="button" class="btn btn-box-tool"<?php echo ($modal ? ' data-dismiss="modal"' : ' data-widget="remove"'); ?>>
						<i class="fa fa-times"></i>
					</button>
				</div>
				<h3 class="box-title">
					<i class="<?php echo $icon; ?>"></i>
					&nbsp;
					<?php echo $title; ?>
				</h3>
			</div>
			<div class="box-body animated zoomIn">
				<?php if(null != $description) { ?>
					<div class="row">
						<div class="alert alert-info no-border no-radius fade in" style="margin-top:-12px">
							<i class="fa fa-info-circle"></i>
							&nbsp;
							<?php echo $description; ?>
						</div>
					</div>
				<?php } ?>
				<div class="row">
					<br />
					<div class="<?php echo ($modal ? 'col-sm-12' : 'col-sm-12'); ?>">
						<?php
							$flag								= null;
							$transaksi							= null;
							/*$transaksi_exists					= array();
							if($form_data['transaksi']['existing'])
							{
								foreach($form_data['transaksi']['existing'] as $key => $val)
								{
									$transaksi_exists[]			= $val['id_transaksi'];
								}
							}*/
							//print_r($form_data['transaksi']['data']);exit;
							if($form_data['transaksi']['data'])
							{
								foreach($form_data['transaksi']['data'] as $key => $val)
								{
									if('create' == $this->_method )
									{
										if ($val['flag'] = 0)
										{
											$flag				= '<label class="label bg-navy">Usulan RW</label>';
										}
										elseif ($val['flag'] = 1)
										{
											$flag				= '<label class="label bg-green">Diterima Kelurahan</label>';
										}
										elseif ($val['flag'] = 2)
										{
											$flag				= '<label class="label bg-yellow">Ditolak Kelurahan</label>';
										}
										elseif ($val['flag'] = 3)
										{
											$flag				= '<label class="label bg-aqua">Usulan Kelurahan</label>';
										}
										elseif ($val['flag'] = 4)
										{
											$flag				= '<label class="label bg-blue">Diterima Kecamatan</label>';
										}
										elseif ($val['flag'] = 5)
										{
											$flag				= '<label class="label bg-purple">Ditolak Kecamatan</label>';
										}
										elseif ($val['flag'] = 6)
										{
											$flag				= '<label class="label bg-primary">Usulan Kecamatan</label>';
										}
										elseif ($val['flag'] = 7)
										{
											$flag				= '<label class="label bg-teal">Diterima SKPD</label>';
										}
										elseif ($val['flag'] = 8)
										{
											$flag				= '<label class="label bg-maroon">Ditolak SKPD</label>';
										}
										$transaksi				.= '
											<tr>
												<td>
													<input type="checkbox" name="id_transaksi[]" value="' . $val['id'] . '" id="x' . $val['id'] . '" />
												</td>
												<td>
													<label for="x' . $val['id'] . '">
														' . $val['rw'] . '
													</label>
												</td>
												<td>
													<label for="x' . $val['id'] . '">
														' . $val['rt'] . '
													</label>
												</td>
												<td>
													<label for="x' . $val['id'] . '">
														' . $val['nama_pekerjaan'] . '
													</label>
												</td>
												<td>
													<label for="x' . $val['id'] . '">
														' . $val['map_address'] . '
													</label>
												</td>
												<td class="text-center">
													<label for="x' . $val['id'] . '">
														' . $flag . '
													</label>
												</td>
												<td align="right">
													<label for="x' . $val['id'] . '">
														' . number_format($val['nilai_usulan']) . '
													</label>
												</td>
											</tr>
										';
									}
								}
							}
						?>
						<div class="table-responsive">
							<table class="table table-bordered table-hover check_all_group">
								<thead>
									<tr>
										<th colspan="7">
											<i class="fa fa-retweet"></i>
											&nbsp;
											BELANJA
										</th>
									</tr>
									<tr>
										<th>
											<input type="checkbox" data-toggle="tooltip" title="<?php echo phrase('check_all'); ?>" role="check_all" />
										</th>
										<th class="text-uppercase">
											<?php echo phrase('RW'); ?>
										</th>
										<th class="text-uppercase">
											<?php echo phrase('RT'); ?>
										</th>
										<th class="text-uppercase">
											<?php echo phrase('jenis_pekerjaan'); ?>
										</th>
										<th class="text-uppercase">
											<?php echo phrase('alamat'); ?>
										</th>
										<th class="text-uppercase text-right">
											<?php echo phrase('status'); ?>
										</th>
										<th class="text-uppercase text-right">
											<?php echo phrase('nilai'); ?>
										</th>
									</tr>
								</thead>
								<tbody>
									<?php echo $transaksi; ?>
								</tbody>
							</table>
						</div>
					</div>
				</div>
			</div>
			<div class="box-footer">
				<div class="row">
					<div class="col-sm-6 col-sm-offset-6 callback-status">
						<div class="btn-group btn-group-justified">
							<?php if($modal) { ?>
							<a href="javascript:void(0)" class="btn btn-primary btn-holo" data-dismiss="modal">
								<i class="fa fa-times"></i>
								&nbsp;
								<?php echo phrase('cancel'); ?>
								<small class="hidden-xs hidden-sm" style="font-size:10px;color:#cacaca">(ESC)</small>
							</a>
							<?php } else { ?>
							<a href="<?php echo go_to(); ?>" class="btn btn-primary btn-holo ajaxLoad">
								<i class="fa fa-chevron-left"></i>
								&nbsp;
								<?php echo phrase('cancel'); ?>
							</a>
							<?php } ?>
							<div class="btn-group">
								<input type="hidden" name="token" value="<?php echo generate_token(); ?>" />
								<button type="submit" class="btn btn-primary btn-holo submitBtn">
									<i class="fa fa-check"></i>
									&nbsp;
									<?php echo phrase('submit'); ?>
									<small class="hidden-xs hidden-sm" style="font-size:10px;color:#cacaca">(CTRL+S)</small>
								</button>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</form>
<?php if($modal) echo '</div></div></div>'; ?>