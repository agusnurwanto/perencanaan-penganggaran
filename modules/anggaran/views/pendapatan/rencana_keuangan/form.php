<style type="text/css">
	.table .form-control
	{
		border: 1px solid rgba(0,0,0,.1)!important;
		padding: 5px 12px;
		text-align: right
	}
</style>
<?php
	$field							= $results->form_data;
?>
<div class="container-fluid pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-<?php echo ('modal' == $this->input->post('prefer') ? 12 : 12); ?>">
				<div class="row">
					<div class="col-md-12">
						<div class="row">
							<label class="control-label col-md-4 col-xs-4 no-margin">
								<b>
									Anggaran
								</b>
							</label>
							<label class="control-label col-md-4 col-xs-6 no-margin">
								<b>
									<?php echo number_format($total); ?>
								</b>
							</label>
						</div>
						<div class="table-responsive">
							<table class="table table-bordered table-sm">
								<thead>
									<tr>
										<th class="text-center">
											BULAN
										</th>
										<th>
											RENCANA
										</th>
										<th class="text-center">
											BULAN
										</th>
										<th>
											<span class="float-right">
												<button type="button" class="btn btn-success btn-xs average" data-amount="<?php echo (int) $total; ?>">
													<i class="fa fa-sitemap"></i>
													Bagi Rata
												</button>
												<button type="button" class="btn btn-light btn-xs reset-input" data-value="0">
													<i class="fa fa-refresh"></i>
													Reset
												</button>
											</span>
											RENCANA
										</th>
									</tr>
								</thead>
								<tbody>
									<tr>
										<td class="text-right">
											<b>
												Januari
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->jan->content) ? $field->jan->content : null); ?>
											</div>
										</td>
										<td class="text-right">
											<b>
												Juli
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->jul->content) ? $field->jul->content : null); ?>
											</div>
										</td>
									</tr>
									<tr>
										<td class="text-right">
											<b>
												Februari
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->feb->content) ? $field->feb->content : null); ?>
											</div>
										</td>
										<td class="text-right">
											<b>
												Agustus
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->agt->content) ? $field->agt->content : null); ?>
											</div>
										</td>
									</tr>
									<tr>
										<td class="text-right">
											<b>
												Maret
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->mar->content) ? $field->mar->content : null); ?>
											</div>
										</td>
										<td class="text-right">
											<b>
												September
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->sep->content) ? $field->sep->content : null); ?>
											</div>
										</td>
									</tr>
									<tr>
										<td class="text-right">
											<b>
												April
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->apr->content) ? $field->apr->content : null); ?>
											</div>
										</td>
										<td class="text-right">
											<b>
												Oktober
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->okt->content) ? $field->okt->content : null); ?>
											</div>
										</td>
									</tr>
									<tr>
										<td class="text-right">
											<b>
												Mei
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->mei->content) ? $field->mei->content : null); ?>
											</div>
										</td>
										<td class="text-right">
											<b>
												Nopember
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->nop->content) ? $field->nop->content : null); ?>
											</div>
										</td>
									</tr>
									<tr>
										<td class="text-right">
											<b>
												Juni
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->jun->content) ? $field->jun->content : null); ?>
											</div>
										</td>
										<td class="text-right">
											<b>
												Desember
											</b>
										</td>
										<td align="right">
											<div class="input-group input-group-sm">
												<div class="input-group-prepend">
													<span class="input-group-text">
														Rp
													</span>
												</div>
												<?php echo (isset($field->des->content) ? $field->des->content : null); ?>
											</div>
										</td>
									</tr>
								</tbody>
								<tfoot>
									<td align="right" colspan="3">
										<b>
											TOTAL (Rp)
										</b>
									</td>
									<td class="text-right sum_input_total">
										<b>
											0
										</b>
									</td>
								</tfoot>
							</table>
						</div>
					</div>
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