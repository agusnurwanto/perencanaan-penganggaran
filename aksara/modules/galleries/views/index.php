<?php
	$output											= null;
	if($results)
	{
		$output										= null;
		foreach($results as $key => $val)
		{
			$album_cover							= null;
			$image_thumb							= null;
			$images									= json_decode($val->gallery_images, true);
			if(!empty($images))
			{
				$num								= 1;
				foreach($images as $src => $alt)
				{
					if($num >= 5) break;
					
					if(1 == $num)
					{
						$album_cover				= $src;
					}
					elseif($num > 1)
					{
						$image_thumb				.= '<a href="' . go_to(array($val->gallery_slug, $src)) . '" class="--modal"><img src="' . get_image('galleries', $src, (count($images) > 2 ? 'thumb' : null)) . '" class="w-100" /></a>';
					}
					$num++;
				}
			}
			
			$output									.= '
				<div class="row mb-5 no-gutters">
					<div class="col-' . (count($images) <= 2 ? 'sm-' : null) . (count($images) == 2 ? 6 : (count($images) == 1 ? 12 : 9)) . ' text-center d-flex align-items-center justify-content-center" style="background:url(' . get_image('galleries', $album_cover) . ') center center no-repeat; background-size:cover">
						<div class="p-3 w-100 d-flex align-items-center" style="background:rgba(0, 0, 0, .5); min-height:320px">
							<div>
								<h4 class="text-light">
									<span class="badge badge-primary float-right">
										' . count($images) . '
									</span>
									' . $val->gallery_title . '
								</h4>
								<p class="text-light">
									' . truncate($val->gallery_description, 160) . '
								</p>
								<p class="text-light">
									' . (count($images) > 4 ? '<a href="' . go_to($val->gallery_slug) . '" class="btn btn-outline-primary rounded-pill --xhr"><i class="mdi mdi-folder-multiple-image"></i> ' . phrase('show_all') . '</a>' : '<a href="' . go_to(array($val->gallery_slug, $album_cover)) . '" class="btn btn-outline-primary rounded-pill --modal"><i class="mdi mdi-magnify"></i> ' . phrase('show') . '</a>') . '
								</p>
							</div>
						</div>
					</div>
					' . (count($images) > 1 ? '
					<div class="col-' . (count($images) <= 2 ? 'sm-' : null) . (count($images) > 2 ? 3 : 6) . ' bg-dark d-flex align-items-center">
						' . $image_thumb . '
					</div>
					' : '') . '
				</div>
			';
		}
	}
?>
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
			<?php echo $output; ?>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<?php echo $this->template->pagination($pagination); ?>
		</div>
	</div>
</div>