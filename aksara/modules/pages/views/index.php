<?php
	if($results)
	{
		foreach($results as $key => $val)
		{
			$carousels					= json_decode($val->carousel_content);
			$faqs						= json_decode($val->faq_content);
			
			if($carousels)
			{
				$navigation				= null;
				$carousel_items			= null;
				foreach($carousels as $_key => $_val)
				{
					$navigation			.= '<li data-target="#carouselExampleIndicators" data-slide-to="' . $_key . '"' . ($_key == 0 ? ' class="active"' : '') . '></li>';
					$carousel_items						.= '
						<div class="carousel-item full-height bg-dark gradient d-flex align-items-center' . ($_key == 0 ? ' active' : '') . '" style="background:#333 url(\'' . get_image('carousels', (isset($_val->background) ? $_val->background : 'placeholder.png')) . '\') center center no-repeat;background-size:cover;background-attachment:fixed">
							<div class="area">
								<ul class="circles">
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
									<li></li>
								</ul>
							</div>
							<div class="absolute top right bottom left" style="background:rgba(0, 0, 0, .75)"></div>
							<div class="carousel-caption" style="position:inherit">
								<div class="row">
									' . ($_val->thumbnail ? '
										<div class="col-md-3 offset-md-2 text-sm-center text-md-right">
											<img src="' . get_image('carousels', $_val->thumbnail, 'thumb') . '" class="img-fluid rounded" />
										</div>
									' : null) . '
									<div class="text-sm-center' . ($_val->thumbnail ? ' col-md-5 text-md-left' : ' col-md-6 offset-md-3') . '">
										<h2 class="text-light mb-3">
											' . (isset($_val->title) ? $_val->title : phrase('title_was_not_set')) . '
										</h2>
										<p class="lead text-light mb-5">
											' . (isset($_val->description) ? truncate($_val->description, 260) : phrase('description_was_not_set')) . '
										</p>
										' . (isset($_val->link) && isset($_val->label) && $_val->link && $_val->label ? '
										<p>
											<a href="' . (isset($_val->link) ? $_val->link : '#') . '" class="btn btn-outline-light btn-lg rounded-pill" data-animation="animated bounceInLeft" style="border-width:2px">
												' . (isset($_val->label) ? $_val->label : phrase('read_more')) . '
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
			
			if($faqs)
			{
				$output					= null;
				foreach($faqs as $_key => $_val)
				{
					if(!isset($_val->question) || !$_val->answer) continue;
					$output				.= '
					  <div class="card">
							<div class="card-header" id="heading_' . $_key . '">
								<a href="#" class="d-block font-weight-bold" data-toggle="collapse" data-target="#collapse_' . $_key . '" aria-expanded="' . (!$_key ? 'true' : 'false') . '" aria-controls="collapse_' . $_key . '">
									' . $_val->question . '
								</a>
							</div>
							<div id="collapse_' . $_key . '" class="collapse" aria-labelledby="heading_' . $_key . '" data-parent="#accordionExample">
								<div class="card-body">
									' . $_val->answer . '
								</div>
							</div>
						</div>
					';
				}
				$faqs					= '
					<div class="accordion" id="accordionExample">
						' . $output . '
					</div>
				';
			}
			
			echo '
				<div class="jumbotron jumbotron-fluid bg-transparent">
					<div class="container">
						<div class="row">
							<div class="col-0 col-sm-2 col-md-1 offset-md-1 d-none d-sm-block">
								<i class="' . $meta->icon . ' mdi-4x text-muted"></i>
							</div>
							<div class="col-12 col-sm-10 col-md-9 text-center text-sm-left">
								<h3 class="mb-0' . (!$meta->description ? ' mt-3' : null) . '">
									' . $meta->title . '
								</h3>
								<p class="lead font-weight-normal">
									' . truncate($meta->description, 256) . '
								</p>
							</div>
						</div>
					</div>
				</div>
				<div class="container">
					<div class="row">
						<div class="col-lg-8 offset-lg-2">
							<div class="mb-3">
								' . preg_replace('/(<[^>]+) style=".*?"/i', '$1', preg_replace('/<img src="(.*?)"/i', '<img id="og-image" src="$1" class="img-fluid rounded"', $val->page_content)) . '
							</div>
							<div class="mb-3">
								' . $faqs . '
							</div>
							<p>
								<i class="text-muted text-sm">
									' . phrase('updated_at') . ' ' . phrase(strtolower(date('l', strtotime($val->updated_timestamp)))) . ', ' . $val->updated_timestamp . '
								</i>
							</p>
						</div>
					</div>
				</div>
			';
		}
	}
	else
	{
		$link_left						= null;
		$link_right						= null;
		if(isset($suggestions) && $suggestions)
		{
			$num						= 1;
			foreach($suggestions as $key => $val)
			{
				if($num % 2 == 0)
				{
					$link_right			.= '
						<li>
							<a href="' . base_url('pages/' . $val->page_slug) . '" class="--xhr">
								' . $val->page_title . '
							</a>
						</li>
					';
				}
				else
				{
					$link_left			.= '
						<li>
							<a href="' . base_url('pages/' . $val->page_slug) . '" class="--xhr">
								' . $val->page_title . '
							</a>
						</li>
					';
				}
				$num++;
			}
		}
		echo '
			<div class="container pt-5">
				<div class="text-center pt-5 pb-5">
					<i class="mdi mdi-dropbox mdi-5x text-muted"></i>
				</div>
				<div class="row mb-5">
					<div class="col-md-6 offset-md-3">
						<h2 class="text-center">
							' . phrase('page_not_found') . '
						</h2>
						<p class="lead font-weight-normal text-center mb-5">
							' . phrase('the_page_you_requested_was_not_found_or_it_is_already_removed') . '
						</p>
						<div class="text-center mt-5">
							<a href="' . base_url() . '" class="btn btn-outline-primary rounded-pill --xhr">
								<i class="mdi mdi-arrow-left"></i>
								&nbsp;
								' . phrase('back_to_home') . '
							</a>
						</div>
					</div>
				</div>
				<div class="row mb-2">
					<div class="col-lg-8 offset-lg-2">
						<h5>
							<i class="mdi mdi-lightbulb-on-outline"></i>
							' . phrase('our_suggestions') . '
							<blink>_</blink>
						</h5>
					</div>
				</div>
				<div class="row">
					<div class="col-md-4 offset-md-2">
						<ul>
							' . $link_left . '
						</ul>
					</div>
					<div class="col-md-4">
						<ul>
							' . $link_right . '
						</ul>
					</div>
				</div>
			</div>
		';
	}