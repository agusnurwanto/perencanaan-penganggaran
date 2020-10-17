<?php
	/**
	 * Carousel
	 */
	if(isset($carousels) && $carousels)
	{
		$navigation								= null;
		$carousel_items							= null;
		foreach($carousels as $key => $val)
		{
			$navigation							.= '<li data-target="#carouselExampleIndicators" data-slide-to="' . $key . '"' . ($key == 0 ? ' class="active"' : '') . '></li>';
			$carousel_items						.= '
				<div class="carousel-item full-height bg-dark gradient d-flex align-items-center justify-content-center' . ($key == 0 ? ' active' : '') . '" style="background:#333 url(\'' . get_image('carousels', (isset($val->background) ? $val->background : 'placeholder.png')) . '\') center center no-repeat;background-size:cover;background-attachment:fixed">
					<div style="position:absolute;top:0;right:0;bottom:0;left:0;background:rgba(0,0,0,.75)"></div>
					<div class="carousel-caption" style="position:inherit">
						<div class="row">
							' . ($val->thumbnail ? '
								<div class="col-md-4 offset-md-1 text-sm-center">
									<img src="' . get_image('carousels', $val->thumbnail) . '" class="img-fluid rounded" />
								</div>
							' : null) . '
							<div class="text-sm-center' . ($val->thumbnail ? ' col-md-6 text-md-left' : ' col-md-8 offset-md-2') . '">
								<h2 class="text-light text-shadow mb-3">
									' . (isset($val->title) ? $val->title : phrase('title_was_not_set')) . '
								</h2>
								<p class="lead text-light text-shadow mb-5">
									' . (isset($val->description) ? $val->description : phrase('description_was_not_set')) . '
								</p>
								' . (isset($val->link) && isset($val->label) && $val->link && $val->label ? '
								<p>
									<a href="' . (isset($val->link) ? $val->link : '#') . '" class="btn btn-success btn-lg rounded-pill" data-animation="animated bounceInLeft">
										' . (isset($val->label) ? $val->label : phrase('read_more')) . '
										&nbsp;
										<i class="mdi mdi-chevron-right"></i>
									</a>
								</p>
								' : '') . '
							</div>
						</div>
					</div>
				</div>
			';
		}
		
		echo '
			<div id="carouselExampleIndicators" class="carousel slide carousel-fade" data-ride="carousel">
				' . (sizeof($carousels) > 1 ? '
				<ol class="carousel-indicators">
					' . $navigation . '
				</ol>
				' : '') . '
				<div class="carousel-inner">
					' . $carousel_items . '
				</div>
				' . (sizeof($carousels) > 1 ? '
				<a class="carousel-control-prev" href="#carouselExampleIndicators" role="button" data-slide="prev">
					<span class="carousel-control-prev-icon" aria-hidden="true"></span>
					<span class="sr-only">
						' . phrase('previous') . '
					</span>
				</a>
				<a class="carousel-control-next" href="#carouselExampleIndicators" role="button" data-slide="next">
					<span class="carousel-control-next-icon" aria-hidden="true"></span>
					<span class="sr-only">
						' . phrase('next') . '
					</span>
				</a>
				' : '') . '
			</div>
		';
	}
	
	/**
	 * Headline news
	 */
	$headline_output							= null;
	$indicators									= null;
	if($headline_news)
	{
		foreach($headline_news as $key => $val)
		{
			$headline_output					.= '
				<div class="carousel-item' . (!$key ? ' active' : null) . '">
					<a href="' . base_url('blogs/' . $val->category_slug . '/' . $val->post_slug) . '" class="--xhr">
						<div class="d-block w-100 bg-secondary rounded" style="background:url(' . get_image('blogs', $val->featured_image) . ') center center no-repeat;background-size:cover;height:320px"></div>
						<div class="carousel-caption text-secondary">
							<h5 class="text-light text-shadow">
								' . $val->post_title . '
							</h5>
							<p class="text-light text-shadow">
								' . truncate($val->post_excerpt, 160) . '
							</p>
						</div>
					</a>
				</div>
			';
			$indicators							.= '<li data-target="#carouselExampleControls" data-slide-to="' . $key . '" class="' . (!$key ? ' active' : null) . '"></li>';
		}
		$headline_output						= '
			<div id="carouselExampleControls" class="carousel slide" data-ride="carousel">
				<ol class="carousel-indicators">
					' . $indicators . '
				</ol>
				<div class="carousel-inner">
					' . $headline_output . '
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
	
	/**
	 * Latest news
	 */
	$news_output									= null;
	if($news_categories)
	{
		foreach($news_categories as $key => $val)
		{
			$news									= null;
			if(sizeof($news_items) > 0)
			{
				foreach($news_items as $num => $item)
				{
					if($item->category_id != $val->category_id) continue;
					$news						.= '
						<div class="row form-group">
							<div class="col-3 col-md-2 pr-0">
								<a href="' . base_url(array('blogs', $item->category_slug, $item->post_slug)) . '" class="--xhr">
									<img src="' . get_image('blogs', $item->featured_image, 'icon') . '" class="img-fluid rounded w-100" alt="' . $item->post_title . '" />
								</a>
							</div>
							<div class="col-9 col-md-10">
								<a href="' . base_url(array('blogs', $item->category_slug, $item->post_slug)) . '" class="--xhr">
									<b>
										' . truncate($item->post_title, 160) . '
									</b>
									<p class="text-muted">
										' . truncate($item->post_excerpt, 160) . '
									</p>
								</a>
							</div>
						</div>
					';
				}
			}
			$news_output							.= '
				<div class="col-sm-6 col-md-5' . (($key + 1) % 2 != 0 ? ' offset-md-1' : null) . ' mb-5">
					<div class="row form-group">
						<div class="col-3 pr-0">
							<a href="' . base_url(array('blogs', $item->category_slug)) . '" class="--xhr">
								<img src="' . get_image('blogs', $val->category_image, 'thumb') . '" class="img-fluid rounded w-100" alt="..." />
							</a>
						</div>
						<div class="col-9">
							<a href="' . base_url(array('blogs', $item->category_slug)) . '" class="--xhr">
								<h4>
									<span class="badge badge-primary float-right">
										' . $val->total_data . '
									</span>
									' . $val->category_title . '
								</h4>
							</a>
							<p>
								<a href="' . base_url(array('blogs', $item->category_slug)) . '" class="--xhr text-muted">
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
	
	/**
	 * Peoples
	 */
	$people_items								= null;
	if($latest_peoples)
	{
		foreach($latest_peoples as $key => $val)
		{
			$people_items						.= '
				<div class="col-3">
					<a href="' . base_url('peoples/' . $val->people_slug) . '" class="--xhr">
						<img src="' . get_image('peoples', $val->photo, 'thumb') . '" class="rounded-circle w-100" alt="' . $val->first_name . ' '  . $val->last_name . '" />
						<h6 class="text-center mt-3">
							' . $val->first_name . ' '  . $val->last_name . '
						</h6>
					</a>
				</div>
			';
		}
		$people_items							= '
			<div class="row mb-5">
				' . $people_items . '
			</div>
			<p class="text-center">
				<a href="' . base_url('peoples') . '" class="--xhr btn btn-outline-primary btn-lg rounded-pill">
					<i class="mdi mdi-magnify"></i>
					Meet Another
				</a>
			</p>
		';
	}
	
	/**
	 * Latest testimonials
	 */
	$testimonial_items							= null;
	$indicators									= null;
	if($latest_testimonials)
	{
		foreach($latest_testimonials as $key => $val)
		{
			$testimonial_items					.= '
				<div class="carousel-item' . (!$key ? ' active' : null) . '">
					<div class="row">
						<div class="col-3 col-md-1 offset-md-3">
							<img src="' . get_image('users', 'placeholder.png', 'icon') . '" class="rounded-circle" />
						</div>
						<div class="col col-md-5">
							<h6>
								' . $val->testimonial_title . '
							</h6>
							<p>
								' . truncate($val->testimonial_content, 160) . '
							</p>
							<p class="blockquote-footer">
								<b>' . $val->first_name . ' ' . $val->last_name . '</b>, ' . $val->timestamp . '
							</p>
						</div>
					</div>
				</div>
			';
			$indicators							.= '<li data-target="#testimonialCarousel" data-slide-to="' . $key . '" class="' . (!$key ? ' active' : null) . '"></li>';
		}
		$testimonial_items						= '
			<div id="testimonialCarousel" class="carousel slide mb-3" data-ride="carousel">
				<div class="carousel-inner pb-5">
					' . $testimonial_items . '
				</div>
				<ol class="carousel-indicators">
					' . $indicators . '
				</ol>
			</div>
		';
	}
?>

<div class="container-fluid pt-3 pb-3 full-height-callout bg-light">
	<div class="row">
		<div class="col-md-6 col-lg-7 offset-md-1 offset-lg-1">
			<h1 class="text-sm-center font-weight-normal text-md-left m-0 p-0 mt-1 mb-2 text-dark">
				Proses Perencanaan dan Penganggaran
			</h1>
		</div>
		<div class="col-md-4 col-lg-3">
			<a href="#" class="btn btn-outline-dark btn-lg btn-block rounded-pill mt-1 mb-2">
				<i class="mdi mdi-store"></i>
				Selengkapnya
			</a>
		</div>
	</div>
</div>

<div class="bg-info">
	<div class="container-fluid pt-4 pb-4">
		<div class="row">
			<div class="col-md-8 offset-md-2">
				<div class="row">
					<div class="col-sm-4 text-center pt-5 pb-5">
						<i class="mdi mdi-monitor-dashboard mdi-5x text-light rounded"></i>
						<br />
						<h4 class="font-weight-light text-light mt-3">
							Easy Monitoring and Informative
						</h4>
					</div>
					<div class="col-sm-4 text-center pt-5 pb-5">
						<i class="mdi mdi-progress-wrench mdi-5x text-light"></i>
						<br />
						<h4 class="font-weight-light text-light mt-3">
							Sustainable Development
						</h4>
					</div>
					<div class="col-sm-4 text-center pt-5 pb-5">
						<i class="mdi mdi-thumb-up-outline mdi-5x text-light"></i>
						<br />
						<h4 class="font-weight-light text-light mt-3">
							Direct Treatment and Services
						</h4>
					</div>
				</div>
			</div>
		</div>
	</div>
</div>

<?php if($headline_output) { ?>
<!-- HEADLINE NEWS -->
<div class="container-fluid pt-5 pb-5">
	<div class="row">
		<div class="col-md-6 offset-md-1 mb-5">
			<?php echo $headline_output; ?>
		</div>
		<div class="col-md-4 mb-5">
			<?php echo $gallery_output; ?>
		</div>
	</div>
</div>
<?php } ?>

<div class="container-fluid">
	<?php echo ($news_output ? '<div class="row">' . $news_output . '</div>' : null); ?>
</div>

<div class="container-fluid pt-5 pb-5">
	<div class="row form-group">
		<div class="col-md-10 offset-md-1">
			<div class="text-center">
				<p>
					<?php echo get_setting('app_description'); ?>
				</p>
			</div>
		</div>
	</div>
	<h3 class="font-weight-normal text-secondary text-center mt-5">
		In the Labs
	</h3>
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<?php echo $people_items; ?>
		</div>
	</div>
</div>

<?php if($testimonial_items) { ?>
<div class="bg-light">
	<div class="container-fluid pt-5">
		<h3 class="font-weight-normal text-center text-muted mb-5">
			They're talking about it
		</h3>
		<?php echo $testimonial_items; ?>
	</div>
</div>
<?php } ?>
