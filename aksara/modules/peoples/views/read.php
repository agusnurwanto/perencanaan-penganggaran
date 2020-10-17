<div class="jumbotron jumbotron-fluid bg-light">
	<div class="container">
		<br />
		<br />
		<br />
		<br />
	</div>
</div>
<div class="container">
	<div class="row">
		<?php
			foreach($results as $key => $val)
			{
				echo '
					<div class="col-md-8 offset-md-2">
						<div class="text-center" style="margin-top:-150px">
							<a href="' . get_image('peoples', $val->photo) . '" target="_blank">
								<img src="' . get_image('peoples', $val->photo, 'thumb') . '" class="img-fluid rounded-pill mb-5" style="border:5px solid #fff" alt="' . $val->first_name . ' ' . $val->last_name . '" />
							</a>
						</div>
						<div class="form-group">
							<label class="text-muted d-block">
								' . phrase('full_name') . '
							</label>
							<h5>
								' . $val->first_name . ' ' . $val->last_name . '
							</h5>
						</div>
						<div class="form-group">
							<label class="text-muted d-block">
								' . phrase('position') . '
							</label>
							<h5>
								' . $val->position . '
							</h5>
						</div>
						<div class="row form-group">
							<div class="col-sm-6">
								<label class="text-muted d-block">
									' . phrase('email') . '
								</label>
								<h5>
									' . $val->email . '
								</h5>
							</div>
							<div class="col-sm-6">
								<label class="text-muted d-block">
									' . phrase('mobile') . '
								</label>
								<h5>
									' . $val->mobile . '
								</h5>
							</div>
						</div>
						<div class="form-group">
							<label class="text-muted d-block">
								' . phrase('biography') . '
							</label>
							<blockquote class="blockquote article">
								<i class="mdi mdi-format-quote-close mdi-3x text-muted"></i>' . $val->biography . '
							</blockquote>
						</div>
						<div class="form-group">
							<label class="text-muted d-block">
								' . phrase('social_account') . '
							</label>
							<div class="row">
								' . ($val->facebook ? '
									<div class="col-sm-6 col-md-4">
										<a href="' . $val->facebook . '" class="btn btn-outline-primary btn-block mb-3" target="_blank">
											<i class="mdi mdi-facebook"></i>
											&nbsp;
											Facebook
										</a>
									</div>
								' : '') . '
								' . ($val->twitter ? '
									<div class="col-sm-6 col-md-4">
										<a href="' . $val->twitter . '" class="btn btn-outline-info btn-block mb-3" target="_blank">
											<i class="mdi mdi-twitter"></i>
											&nbsp;
											Twitter
										</a>
									</div>
								' : '') . '
							</div>
						</div>
					</div>
				';
			}
		?>
	</div>
</div>
<div class="container mt-5">
	<div class="row mb-5">
		<div class="col-lg-8 offset-lg-2">
			<?php
				if(sizeof($similar) > 0)
				{
					$output							= null;
					foreach($similar as $key => $val)
					{
						$output						.= '
							<div class="col-6 col-md-4' . ($key > 2 ? ' d-md-none' : null) . '">
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
						<h5>
							<i class="mdi mdi-dots-horizontal"></i>
							' . phrase('meet_another_team') . '
						</h5>
						<div class="row">
							' . $output . '
						</div>
					';
				}
			?>
		</div>
	</div>
</div>