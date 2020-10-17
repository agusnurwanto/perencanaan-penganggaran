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
				<p class="lead mb-0">
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
							<blockquote class="blockquote">
								<a href="' . go_to($val->announcement_slug) . '" class="--xhr">
									<p class="mb-0">
										' . $val->title . '
									</p>
									<footer class="blockquote-footer">
										' . phrase('valid_until') . ' ' . $val->end_date . '
									</footer>
								</a>
							</blockquote>
						';
					}
					echo $this->template->pagination($pagination);
				}
				else
				{
					echo '
						<div class="text-muted">
							<i class="mdi mdi-information"></i>
							&nbsp;
							' . phrase('no_announcement_is_available') . '
						</div>
					';
				}
			?>
		</div>
	</div>
</div>