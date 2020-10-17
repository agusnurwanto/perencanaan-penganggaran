<div class="jumbotron jumbotron-fluid bg-light">
	<div class="container">
		<div class="row">
			<div class="col-0 col-sm-2 col-md-1 offset-md-1 d-none d-sm-block">
				<i class="<?php echo $meta->icon; ?> mdi-4x text-muted"></i>
			</div>
			<div class="col-12 col-sm-10 col-md-9 text-center text-sm-left">
				<h3 class="mb-0<?php echo (!$meta->description ? ' mt-3' : null); ?>">
					<?php echo $meta->title; ?>
				</h3>
				<p class="lead">
					<?php echo truncate($meta->description, 256); ?>
				</p>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<?php
				if($results)
				{
					foreach($results as $key => $val)
					{
						echo '
							<p>
								' . $val->content . '
							</p>
							<p class="text-muted mb-5">
								<em>
									' . phrase('this_announcement_is_effective_until') . ' <b>' . $val->end_date . '</b>
								</em>
							</p>
							<a href="' . current_page('../') . '" class="btn btn-outline-primary rounded-pill --xhr">
								<i class="mdi mdi-arrow-left"></i>
								&nbsp;
								' . phrase('back') . '
							</a>
						';
					}
				}
				else
				{
					echo '
						<h1 class="text-muted text-center">
							404
						</h1>
						<p class="text-muted text-center">
							' . phrase('the_data_you_requested_does_not_exists_or_it_has_been_removed') . '
						</p>
					';
				}
			?>
		</div>
	</div>
</div>