<?php
	$output											= null;
	$similar_categories								= null;
	$similar_items									= null;
	if($results)
	{
		foreach($results as $key => $val)
		{
			$item_tags								= explode(',', $val->post_tags);
			$tags									= null;
			if(sizeof($item_tags) > 0)
			{
				foreach($item_tags as $label => $badge)
				{
					if(!$badge) continue;
					$tags							.= '
						<a href="' . go_to('tags', array('q' => trim($badge))) . '" class="--xhr">
							<span class="badge badge-secondary">
								' . trim($badge) . '
							</span>
						</a>
						&nbsp;
					';
				}
			}
			$output									.= '
				<div class="mt-5 mb-5">
					' . str_replace('MsoNormalTable', 'table table-bordered', preg_replace('/(width|height)="\d*"\s/', '', preg_replace('/<p([> ])>/', '<p class="text-justify lead article">', preg_replace('/style[^>]*/', '', $val->post_content)))) . '
				</div>
				<p class="mb-5">
					' . $tags . '
				</p>
				<div class="fb-comments-container mb-5">
					<div class="fb-comments" data-href="' . current_page() . '" data-numposts="5" data-width="100%"></div>
				</div>
			';
		}
	}
	if($similar)
	{
		foreach($similar as $key => $val)
		{
			$similar_items					.= '
				<li class="nav-item">
					<div class="row form-group mb-3">
						<div class="col-2 col-sm-1 col-md-2 pr-0">
							<img src="' . get_image('blogs', $val->featured_image, 'icon') . '" class="img-fluid rounded" />
						</div>
						<div class="col col-sm-11 col-md-10">
							<a href="' . go_to(array($val->category_slug, $val->post_slug)) . '" class="--xhr">
								' . $val->post_title . '
							</a>
						</div>
					</div>
				</li>
			';
		}
	}
	if($categories)
	{
		foreach($categories as $key => $val)
		{
			$similar_categories				.= '
				<li class="nav-item">
					<div class="row form-group mb-4">
						<div class="col-2 col-sm-1 col-md-2 pr-0">
							<img src="' . get_image('blogs', $val->category_image, 'icon') . '" class="img-fluid rounded" />
						</div>
						<div class="col col-sm-11 col-md-10">
							<a href="' . go_to($val->category_slug) . '" class="--xhr">
								<span class="badge text-light bg-primary float-right">
									' . $val->total_data . '
								</span>
								<h6>
									' . $val->category_title . '
								</h6>
							</a>
						</div>
					</div>
				</li>
			';
		}
	}
?>
<div class="jumbotron jumbotron-fluid leading relative" style="background:url(<?php echo get_image('blogs', $results[0]->featured_image); ?>) center center no-repeat; background-size:cover">
	<div class="clip gradient-top"></div>
	<div class="container pt-5">
		<div class="row">
			<div class="col-0 col-sm-2 col-md-1 d-none d-sm-block">
				<i class="<?php echo $meta->icon; ?> mdi-4x text-muted"></i>
			</div>
			<div class="col-12 col-sm-10 col-md-11 text-center text-sm-left">
				<h3 class="mb-0<?php echo (!$meta->description ? ' mt-3' : null); ?> text-light">
					<?php echo $meta->title; ?>
				</h3>
				<p class="lead mb-0 d-none d-sm-block text-light">
					<?php echo truncate($meta->description, 256); ?>
				</p>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-7 offset-md-1">
		
			<?php
				if($output)
				{
					/* show author */
					echo '
						<div class="row">
							<div class="col-sm-6 col-md-8 mb-3">
								<div class="row no-gutters">
									<div class="col-2 col-sm-1">
										<a href="' . base_url('user/' . $results[0]->username) . '" class="--xhr">
											<img src="' . get_image('users', $results[0]->photo, 'thumb') . '" class="img-fluid rounded-circle" />
										</a>
									</div>
									<div class="col-10 col-sm-11 pl-3">
										<a href="' . base_url('user/' . $results[0]->username) . '" class="--xhr">
											<h6 class="mb-0">
												' . $results[0]->first_name . ' ' . $results[0]->last_name . '
											</h6>
										</a>
										<p>
											<span class="text-sm text-muted" data-toggle="tooltip" title="' . $results[0]->updated_timestamp . '">
												' . time_ago($results[0]->updated_timestamp) . '
											</span>
										</p>
									</div>
								</div>
							</div>
							<div class="col-sm-6 col-md-4 mb-3">
								<div class="btn-group d-flex">
									<a href="//www.facebook.com/sharer/sharer.php?u=' . current_page() . '" class="btn btn-primary" data-toggle="tooltip" title="' . phrase('share_to_facebook') . '" target="_blank">
										<i class="mdi mdi-facebook"></i>
									</a>
									<a href="//www.twitter.com/share?url=' . current_page() . '" class="btn btn-info" data-toggle="tooltip" title="' . phrase('share_to_twitter') . '" target="_blank">
										<i class="mdi mdi-twitter"></i>
									</a>
									<a href="//wa.me/?text=' . current_page() . '" class="btn btn-success" data-toggle="tooltip" title="' . phrase('send_to_whatsapp') . '" target="_blank">
										<i class="mdi mdi-whatsapp"></i>
									</a>
								</div>
							</div>
						</div>
					';
					
					/* show featured image */
					if($results[0]->featured_image)
					{
						echo '<a href="' . get_image('blogs', $results[0]->featured_image) . '" target="_blank"><img id="og-image" src="' . get_image('blogs', $results[0]->featured_image) . '" class="img-fluid rounded" width="100%" /></a>';
					}
					
					/* show post content */
					echo preg_replace('/<img src="(.*?)"/i', '<img id="og-image" src="$1" class="img-fluid rounded"', $output);
				}
				else
				{
					echo '
						<div class="alert alert-warning">
							<i class="mdi mdi-alert-outline"></i>
							' . phrase('the_post_you_requested_does_not_exists') . '
						</div>
					';
				}
			?>
		</div>
		<div class="col-md-3">
			<div class="sticky-top">
				<?php if($similar_items) { ?>
					<h5 class="mb-3">
						<?php echo phrase('another_posts'); ?>
					</h5>
					<ul class="nav flex-column mb-5">
						<?php echo $similar_items; ?>
					</ul>
				<?php } ?>
				<h5 class="mb-3">
					<?php echo phrase('categories'); ?>
				</h5>
				<ul class="nav flex-column">
					<?php echo $similar_categories; ?>
				</ul>
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		FB.XFBML.parse()
	})
</script>