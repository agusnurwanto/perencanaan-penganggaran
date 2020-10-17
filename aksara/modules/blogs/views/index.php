<?php
	$headline_news								= null;
	$indicators									= null;
	if(sizeof($headline) > 0)
	{
		foreach($headline as $key => $val)
		{
			$headline_news						.= '
				<div class="carousel-item rounded bg-dark' . (!$key ? ' active' : null) . '" style="background:url(' . get_image('blogs', $val->featured_image) . ') center center no-repeat;background-size:cover;height:320px">
					<div class="carousel-caption">
						<a href="' . base_url('blogs/' . $val->category_slug . '/' . $val->post_slug) . '" class="--xhr">
							<h5 class="text-light text-shadow">
								' . $val->post_title . '
							</h5>
							<p class="text-light text-shadow">
								' . truncate($val->post_excerpt, 160) . '
							</p>
						</a>
					</div>
				</div>
			';
			$indicators							.= '<li data-target="#carouselExampleControls" data-slide-to="' . $key . '" class="' . (!$key ? ' active' : null) . '"></li>';
		}
		$headline_news							= '
			<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators">
					' . $indicators . '
				</ol>
				<div class="carousel-inner">
					' . $headline_news . '
				</div>
				<a class="carousel-control-prev" href="#carouselExampleControls" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">' . phrase('previous') . '</span>
				</a>
				<a class="carousel-control-next" href="#carouselExampleControls" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">' . phrase('next') . '</span>
				</a>
			</div>
		';
	}
	
	$output										= null;
	if($results)
	{
		foreach($results as $key => $val)
		{
			$_ci								=& get_instance();
			$news								= null;
			$items								= $_ci->get_articles($val->category_id);
			if($items)
			{
				foreach($items as $_key => $_val)
				{
					$news						.= '
						<div class="row form-group">
							<div class="col-3 col-md-2 pr-0">
								<a href="' . go_to(array($_val->category_slug, $_val->post_slug)) . '" class="--xhr">
									<img id="og-image" src="' . get_image('blogs', $_val->featured_image, 'icon') . '" class="img-fluid rounded w-100" alt="' . $_val->post_title . '" />
								</a>
							</div>
							<div class="col-9 col-md-10">
								<a href="' . go_to(array($_val->category_slug, $_val->post_slug)) . '" class="--xhr">
									<b>
										' . truncate($_val->post_title, 160) . '
									</b>
									<p class="text-muted">
										' . truncate($_val->post_excerpt, 160) . '
									</p>
								</a>
							</div>
						</div>
					';
				}
			}
			$output								.= '
				<div class="col-sm-6 col-md-5' . (($key + 1) % 2 != 0 ? ' offset-md-1' : null) . ' mb-5">
					<div class="row form-group">
						<div class="col-3 pr-0">
							<a href="' . go_to($val->category_slug) . '" class="--xhr">
								<img src="' . get_image('blogs', $val->category_image, 'thumb') . '" class="img-fluid rounded w-100" alt="..." />
							</a>
						</div>
						<div class="col-9">
							<a href="' . go_to($val->category_slug) . '" class="--xhr">
								<h4>
									<span class="badge badge-primary float-right">
										' . $val->total_data . '
									</span>
									' . $val->category_title . '
								</h4>
							</a>
							<p>
								<a href="' . go_to($val->category_slug) . '" class="--xhr text-muted">
									' . truncate($val->category_description, 160) . '
								</a>
							</p>
						</div>
					</div>
					
					' . $news . '
					
				</div>
				' . (($key + 1) % 2 == 0 ? '</div><div class="row">' : null) . '
			';
		}
	}
	
	/**
	 * Latest galleries
	 */
	$gallery_output								= null;
	if($latest_galleries)
	{
		$cover									= null;
		foreach($latest_galleries as $key => $val)
		{
			$image								= json_decode($val->gallery_images);
			if(!$image) continue;
			
			foreach($image as $src => $alt)
			{
				$cover							.= '<div class="col-6"><a href="' . base_url('galleries/' . $val->gallery_slug) . '" class="--xhr"><img src="' . get_image('galleries', $src, 'thumb') . '" class="img-fluid rounded" alt="' . $alt . '" /></a></div>';
				break;
			}
		}
		
		$gallery_output							= '
			<div class="row">
				' . $cover . '
			</div>
		';
	}
?>
<div class="jumbotron jumbotron-fluid bg-light">
	<div class="container">
		<div class="row">
			<div class="col-0 col-sm-2 col-md-1 d-none d-sm-block">
				<i class="<?php echo $meta->icon; ?> mdi-4x text-muted"></i>
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

<?php if($headline_news) { ?>
<!-- HEADLINE NEWS -->
<div class="container pb-5">
	<div class="row">
		<div class="col-md-6 offset-md-1 mb-5">
			<?php echo $headline_news; ?>
		</div>
		<div class="col-md-4 mb-5">
			<?php echo $gallery_output; ?>
		</div>
	</div>
</div>
<?php } ?>

<div class="container">
	<?php echo ($output ? '<div class="row">' . $output . '</div>' : null); ?>
</div>