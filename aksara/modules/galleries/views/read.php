<?php
	$images									= json_decode($results[0]->gallery_images);
	$current								= $this->uri->segment(3);
?>
<style type="text/css">
	@media (max-width: 640px)
	{
		.photo-view
		{
			margin-left: -15px;
			margin-right: -15px
		}
	}
</style>
<div class="container">
	<div class="row">
		<div class="col-md-8 offset-md-2">
			<div class="card shadow">
				<div class="photo-view">
					<div style="margin-top:-1px; margin-right:-1px; margin-left:-1px">
						<a href="<?php echo get_image('galleries', $current); ?>" target="_blank">
							<img src="<?php echo get_image('galleries', $current); ?>" class="img-fluid card-img-top rounded-0" alt="<?php echo $this->input->get('item'); ?>" />
						</a>
					</div>
				</div>
				<div class="card-body">
					<div class="row mb-3">
						<div class="col-2 col-md-1 pr-0">
							<a href="<?php echo base_url('user/' . $results[0]->username); ?>" class="--xhr">
								<img src="<?php echo get_image('users', $results[0]->photo, 'thumb'); ?>" class="img-fluid rounded" />
							</a>
						</div>
						<div class="col-10 col-md-7">
							<a href="<?php echo base_url('user/' . $results[0]->username); ?>" class="--xhr">
								<b class="mb-0">
									<?php echo $results[0]->first_name . ' ' . $results[0]->last_name; ?>
								</b>
							</a>
							<p>
								<span class="text-sm text-muted" data-toggle="tooltip" title="<?php echo $results[0]->updated_timestamp; ?>">
									<?php echo time_ago($results[0]->updated_timestamp); ?>
								</span>
							</p>
						</div>
						<div class="col-md-4">
							<div class="btn-group btn-group-sm d-flex">
								<a href="//www.facebook.com/sharer/sharer.php?u=<?php echo current_page(); ?>" class="btn btn-primary" data-toggle="tooltip" title="<?php echo phrase('share_to_facebook'); ?>" target="_blank">
									<i class="mdi mdi-facebook"></i>
								</a>
								<a href="//www.twitter.com/share?url=<?php echo current_page(); ?>" class="btn btn-info" data-toggle="tooltip" title="<?php echo phrase('share_to_twitter'); ?>" target="_blank">
									<i class="mdi mdi-twitter"></i>
								</a>
								<a href="//wa.me/?text=<?php echo current_page(); ?>" class="btn btn-success" data-toggle="tooltip" title="<?php echo phrase('send_to_whatsapp'); ?>" target="_blank">
									<i class="mdi mdi-whatsapp"></i>
								</a>
							</div>
						</div>
					</div>
					<div class="text-break-word mb-3">
						<?php echo (isset($images->$current) ? $images->$current : null); ?>
					</div>
					<div>
						<?php echo $results[0]->gallery_description; ?>
					</div>
				</div>
				<div class="card-footer">
					<div class="fb-comments-container">
						<div class="fb-comments" data-href="<?php echo current_page(); ?>" data-numposts="5" data-width="100%"></div>
					</div>
				</div>
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