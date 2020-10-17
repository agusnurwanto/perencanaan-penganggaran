<div class="box no-border animated fadeIn">
	<div class="box-header with-border">
		<div class="box-tools pull-right">
			<?php
				if($description)
				{
					echo '
						<button type="button" class="btn btn-box-tool" data-toggle="collapse" data-target="#description-collapse">
							<i class="fa fa-info-circle"></i>
						</button>
					';
				}
			?>
			<a href="<?php echo current_page(); ?>" class="btn btn-box-tool ajaxLoad show_process">
				<i class="fa fa-refresh"></i>
			</a>
			<button type="button" class="btn btn-box-tool" data-widget="maximize">
				<i class="fa fa-expand"></i>
			</button>
			<button type="button" class="btn btn-box-tool" data-widget="remove">
				<i class="fa fa-times"></i>
			</button>
		</div>
		<h3 class="box-title">
			<i class="<?php echo $icon; ?>"></i>
			&nbsp;
			<?php echo $title; ?>
		</h3>
		<div class="row">
			<hr />
		</div>
		<?php
			if($description)
			{
				echo '
					<div class="description-collapse collapse in" id="description-collapse">
						<div class="row">
							<div class="col-md-12">
								' . $description . '
							</div>
						</div>
					</div>
				';
			}
		?>
	</div>
	<div class="box-body">
		<div class="row form-group">
			<div class="col-sm-4">
				<?php echo $chart_1; ?>
			</div>
			<div class="col-sm-4">
				<?php echo $chart_2; ?>
			</div>
			<div class="col-sm-4">
				<?php echo $chart_3; ?>
			</div>
		</div>
		<div class="row form-group">
			<div class="col-sm-4">
				<?php echo $chart_4; ?>
			</div>
			<div class="col-sm-4">
				<?php echo $chart_5; ?>
			</div>
			<div class="col-sm-4">
				<?php echo $chart_6; ?>
			</div>
		</div>
	</div>
</div>