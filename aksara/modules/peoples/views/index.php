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
					$output							= null;
					foreach($results as $key => $val)
					{
						$output						.= '
							<div class="col-6 col-md-4">
								<div class="card border-0 shadow-sm mb-3">
									<a href="' . go_to($val->people_slug) . '" class="--xhr">
										<img src="' . get_image('peoples', $val->photo, 'thumb') . '" class="card-img-top" alt="' . $val->first_name . ' '  . $val->last_name . '" width="100%" />
									</a>
									<div class="card-body">
										<a href="' . go_to($val->people_slug) . '" class="--xhr">
											<h6 class="card-title text-center text-truncate">
												' . truncate($val->first_name, 22) . ' ' . truncate($val->last_name, 22) . '
											</h6>
										</a>
										<a href="' . go_to($val->people_slug) . '" class="--xhr">
											<h6 class="card-subtitle font-weight-light text-center mb-2 text-muted text-truncate">
												' . truncate($val->position, 22) . '
											</h6>
										</a>
									</div>
								</div>
							</div>
						';
					}
					
					echo '
						<div class="row form-group">
							' . $output . '
						</div>
					';
				}
			?>
		</div>
	</div>
</div>