<?php
	$output											= null;
	if($results)
	{
		foreach($results as $key => $val)
		{
			$images									= json_decode($val->gallery_images, true);
			$labels									= explode(',', $val->gallery_tags);
			if(is_array($images) && sizeof($images) > 0)
			{
				foreach($images as $src => $alt)
				{
					if(!$src) continue;
					$output							.= '
						<div class="col-sm-6 col-md-4">
							<a href="' . go_to(array($val->gallery_slug, $src)) . '" class="--modal">
								<img src="' . get_image('galleries', $src, 'thumb') . '" class="rounded w-100 mb-4" alt="' . $alt . '" />
							</a>
						</div>
					';
				}
			}
			
			$output									= '<div class="row">' . $output . '</div>';
		}
	}
	else
	{
		$output										= '
			<div class="alert alert-warning mt-5">
				<i class="mdi mdi-information-outline"></i>
				' . phrase('no_image_found_in_this_album') . '
			</div>
		';
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