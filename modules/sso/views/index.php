<div class="container-fluid pt-3 pb-3">
	<?php
		if(isset($results->is_logged) && $results->is_logged)
		{
			echo '
				<div class="text-center pt-5 pb-5">
					Anda telah login...
				</div>
			';
		}
		else
		{
			echo '
				<div class="text-center pt-5 pb-5">
					<a href="' . current_page('handshake') . '" class="btn btn-primary btn-lg --xhr">
						Login dengan SSO
					</a>
				</div>
			';
		}
	?>
</div>