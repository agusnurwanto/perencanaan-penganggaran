<?php
	$field_data								= (isset($results[0]) ? $results[0] : array());
	if(!$field_data)
	{
		redirect();
	}
?>
<div class="container-fluid">
	<div class="row">
		<div class="col-sm-8 col-sm-offset-2">
			<div class="box no-border">
				<div class="box-header with-border">
					<div class="box-tools pull-right">
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
				<div class="box-body">
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
						</div>
					</div>
				</div>
				<div class="box-footer">
					<div class="row">
						<div class="col-md-10 col-md-offset-1">
							<div class="btn-group pull-right">
								<?php if($modal) { ?>
									<button type="button" class="btn btn-primary btn-holo" data-dismiss="modal">
										<i class="fa fa-close"></i>
										&nbsp;
										<?php echo phrase('close'); ?>
									</a>
								<?php } else { ?>
									<a href="<?php echo go_to(); ?>" class="btn btn-primary btn-holo ajaxLoad">
										<i class="fa fa-chevron-left"></i>
										&nbsp;
										<?php echo phrase('back'); ?>
									</a>
								<?php } ?>
							</div>
						</div>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>