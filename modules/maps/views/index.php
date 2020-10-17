<style type="text/css">
	.nav.navbar-nav:not(.navbar-right)
	{
		background: rgba(0,0,0,.2);
		border-radius: 9px
	}
	.filter-wrapper
	{
		width: 460px;
		position: absolute;
		top: 0;
		padding: 12px;
		background: rgba(255,255,255,1);
		border-radius: 0 0 0 3px;
		z-index: 9999
	}
	.filter-wrapper.expanded
	{
		overflow-y: auto;
		top: 0;
		right: 0;
		bottom: 0;
		transition: .1s all ease-in-out
	}
	.filter-wrapper.collapsed
	{
		right: -460px;
		transition: .1s all ease-in-out
	}
	.filter-container
	{
		position: relative;
		background: #fff
	}
	.filter-container .open-filter
	{
		position: absolute;
		left: -62px;
		top: 0;
		background: rgba(255,255,255,1);
		padding: 5px 12px;
		border-radius: 3px 0 0 3px;
		cursor: pointer
	}
	.filter-container .form-control.bordered~.select2,
	.filter-container .form-control~.select2
	{
		border-radius: 2px
	}
	@media(max-width: 920px)
	{
		.filter-wrapper
		{
			width: 100%
		}
		.filter-wrapper.collapsed
		{
			right: -100%
		}
	}
</style>
<form action="<?php echo current_page('json', array('column' => 'all')); ?>" method="POST" class="ajaxMap">
	<div class="filter-wrapper collapsed">
		<div class="filter-container">
			<span class="ec open-filter">
				<i class="fa fa-cog fa-spin fa-2x"></i>
			</span>
			<div class="box no-border">
				<div class="box-header">
					<h4>
						<div class="btn-group pull-right">
							<button type="reset" class="btn btn-default btn-xs">
								<i class="fa fa-refresh"></i>
								Reset
							</button>
							<button type="reset" class="ec btn btn-default btn-xs">
								<i class="fa fa-times"></i>
								Close
							</button>
						</div>
						<i class="fa fa-filter"></i>
						Filter Data
					</h4>
				</div>
				<div class="box-body" role="scroller" data-offset="0">
					<div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true">
					<?php
						$controller							=& get_instance();
						foreach($results as $key => $val)
						{
							echo '
								<div class="panel no-radius" style="background:transparent">
									<div class="panel-heading" role="tab" id="heading_' . $key . '" style="padding-right:0;padding-left:0">
										<h4 class="panel-title">
											<a role="button" data-toggle="collapse" data-parent="#accordion" href="#collapse_' . $key . '" aria-expanded="true" aria-controls="collapse_' . $key . '">
												<span class="label bg-red pull-right" style="margin-top:5px">' . $val->total . '</span>
												<b>
													Kecamatan ' . $val->kecamatan . '
												</b>
											</a>
										</h4>
									</div>
									<div id="collapse_' . $key . '" class="panel-collapse collapse" role="tabpanel" aria-labelledby="heading_' . $key . '">
										<div class="panel-body" style="padding-right:0;padding-left:0">
											' . $controller->_get_thread($val->id_kec) . '
										</div>
									</div>
								</div>
							';
						}
					?>
					</div>
				</div>
				<div class="box-footer">
					<div class="row check-all-parent">
						<div class="col-xs-6">
							<div class="form-group">
								<input type="checkbox" name="flag[]" role="check-all" checker-parent=".check-all-parent" value="999" id="flag_999" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_999">
									Semua Jenis
								</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children" value="0" id="flag_0" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_0">
									Usulan RW
								</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children"  value="1" id="flag_1" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_1">
									Diterima Kelurahan
								</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children"  value="2" id="flag_2" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_2">
									Ditolak Kelurahan
								</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children"  value="3" id="flag_3" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_3">
									Usulan Kelurahan
								</label>
							</div>
						</div>
						<div class="col-xs-6">
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children"  value="4" id="flag_4" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_4">
									Diterima Kecamatan
								</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children"  value="5" id="flag_5" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_5">
									Ditolak Kecamatan
								</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children"  value="6" id="flag_6" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_6">
									Usulan Kecamatan
								</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children"  value="7" id="flag_7" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_7">
									Diterima SKPD
								</label>
							</div>
							<div class="form-group">
								<input type="checkbox" name="flag[]" class="check-all-children"  value="8" id="flag_8" />
								<label style="display:block;margin-top:-22px;margin-left:30px" for="flag_8">
									Ditolak SKPD
								</label>
							</div>
						</div>
					</div>
					<button type="submit" class="btn btn-primary btn-sm btn-block">
						<i class="fa fa-check"></i>
						Filter
					</button>
				</div>
			</div>
		</div>
	</div>
	<div class="drawing-placeholder preloader" id="carousel-placeholder">
		<div id="map_coordinates" class="full-height" role="maps" data-coordinate="<?php echo htmlspecialchars(get_setting('office_map')); ?>" data-initialize="marker" data-draggable="false"></div>
	</div>
</form>
<script type="text/javascript">
$(document).ready(function()
{
	$('.ec').on('click', function(e)
	{
		e.preventDefault();
		if($(this).closest('.filter-wrapper').hasClass('expanded'))
		{
			$(this).closest('.filter-wrapper').removeClass('expanded').addClass('collapsed')
		}
		else
		{
			$(this).closest('.filter-wrapper').removeClass('collapsed').addClass('expanded')
		}
	})
})
</script>