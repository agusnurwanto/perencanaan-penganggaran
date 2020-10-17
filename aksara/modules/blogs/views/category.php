<?php
	$output											= null;
	$category										= null;
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
					if($label == 2) break;
					if($badge)
					{
						$tags						.= '
							<a href="' . go_to('tags', array('q' => $badge)) . '" class="--xhr">
								<span class="badge badge-secondary">
									' . trim($badge) . '
								</span>
							</a>
						';
					}
				}
			}
			
			$output									.= '
				<div class="row">
					<div class="col-2 col-md-1 pr-0">
						<a href="' . base_url('user/' . $val->username) . '" class="--xhr">
							<img src="' . get_image('users', $val->photo, 'thumb') . '" class="img-fluid rounded" />
						</a>
					</div>
					<div class="col-10 col-md-11">
						<a href="' . base_url('user/' . $val->username) . '" class="--xhr">
							<h6 class="mb-0">
								' . $val->first_name . ' ' . $val->last_name . '
							</h6>
						</a>
						<p>
							<span class="text-sm text-muted" data-toggle="tooltip" title="' . $val->updated_timestamp . '">
								' . time_ago($val->updated_timestamp) . '
							</span>
						</p>
					</div>
				</div>
				<div class="row mb-5">
					<div class="col-3 col-sm-2 col-md-2 offset-md-1 pr-0">
						<a href="' . base_url(array('blogs', $val->category_slug, $val->post_slug)) . '" class="--xhr">
							<img id="og-image" src="' . get_image('blogs', $val->featured_image, 'thumb') . '" class="img-fluid rounded" />
						</a>
					</div>
					<div class="col-9 col-sm-10 col-md-9">
						<a href="' . base_url(array('blogs', $val->category_slug, $val->post_slug)) . '" class="--xhr">
							<h6>
								' . $val->post_title . '
							</h6>
						</a>
						<p>
							<a href="' . base_url(array('blogs', $val->category_slug, $val->post_slug)) . '" class="--xhr text-muted">
								' . truncate($val->post_excerpt, 128) . '
							</a>
						</p>
						<p>
							' . ($tags ? $tags : null) . '
						</p>
					</div>
				</div>
			';
		}
	}
	if(is_array($categories) && sizeof($categories) > 0)
	{
		foreach($categories as $key => $val)
		{
			$category								.= '
				<li class="nav-item">
					<div class="row form-group">
						<div class="col-2">
							<img src="' . get_image('blogs', $val->category_image, 'icon') . '" class="img-fluid rounded" />
						</div>
						<div class="col">
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
<div class="jumbotron jumbotron-fluid bg-light">
	<div class="container">
		<div class="row">
			<div class="col-0 col-sm-2 col-md-1 d-none d-sm-block">
				<img src="<?php echo get_image('blogs', $val->category_image, 'icon'); ?>" class="img-fluid mt-2 rounded" />
			</div>
			<div class="col-12 col-sm-8 col-md-5 text-center text-sm-left">
				<h3 class="mb-0<?php echo (!$meta->description ? ' mt-3' : null); ?>">
					<?php echo $meta->title; ?>
				</h3>
				<p class="lead mb-0">
					<?php echo truncate($meta->description, 256); ?>
				</p>
			</div>
			<div class="col-12 col-md-5">
				<form action="<?php echo go_to('search', array('per_page' => null)); ?>" method="GET" class="form-horizontal relative mt-3 --xhr-form">
					<input type="text" name="q" class="form-control font-weight-bold pt-4 pr-4 pb-4 pl-4 border-0" value="<?php echo $this->input->get('q'); ?>" placeholder="<?php echo phrase('search_post'); ?>" autocomplete="off">
					<button type="submit" class="btn btn-lg float-right absolute top right">
						<i class="mdi mdi-magnify font-weight-bold"></i>
					</button>
				</form>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-md-7 offset-md-1 pb-5">
		
			<?php
				if($output)
				{
					echo $output;
					echo $this->template->pagination($pagination);
				}
				else
				{
					echo '
						<div class="text-muted">
							<i class="mdi mdi-information-outline"></i>
							&nbsp;
							' . phrase('no_post_found_under_this_category') . '
						</div>
					';
				}
			?>
		
		</div>
		<div class="col-md-3">
			<div class="sticky-top">
				<h5>
					<?php echo phrase('other_categories'); ?>
				</h5>
				<ul class="nav flex-column">
				
					<?php echo $category; ?>
					
				</ul>
			</div>
		</div>
	</div>
</div>