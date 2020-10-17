<div class="container-fluid pt-3 pb-3">
	<div class="row">
		<div class="col-md-6">
			<div class="update-wrapper">
				<i class="mdi mdi-sync mdi-spin"></i>
				Checking version...
			</div>
		</div>
	</div>
</div>

<script type="text/javascript">
	$(document).ready(function()
	{
		$.ajax
		({
			url: '<?php echo current_page('check'); ?>'
		})
		.done(function(response)
		{
			console.log(response)
		})
	})
</script>