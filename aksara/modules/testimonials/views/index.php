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
					$items				= null;
					foreach($results as $key => $val)
					{
						$items			.= '
							<div class="row mb-3">
								<div class="col-3 col-md-2">
									<img src="' . get_image('users', 'placeholder.png', 'icon') . '" class="rounded">
								</div>
								<div class="col-9 col-md-10">
									<h4 class="article font-weight-bold">
										' . $val->testimonial_title . '
									</h4>
									<p class="lead article">
										' . $val->testimonial_content . '
									</p>
									<p class="blockquote-footer">
										<b>' . $val->first_name . ' ' . $val->last_name . '</b>, ' . $val->timestamp . '
									</p>
								</div>
							</div>
						';
					}
					
					echo $items;
					echo $this->template->pagination($pagination);
				}
				else
				{
					echo '
						<div class="text-muted">
							<i class="fa fa-info"></i>
							&nbsp;
							' . phrase('no_testimonial_is_available') . '
						</div>
					';
				}
			?>
		</div>
	</div>
</div>