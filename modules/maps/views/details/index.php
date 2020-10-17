<?php
	$field_data								= (isset($results[0]) ? $results[0] : array());
	if(!$field_data)
	{
		redirect();
	}
?>
<div class="container-fluid">
	<div class="row">
		<div class="drawing-placeholder preloader">
			<div id="map_coordinates" role="maps" data-initialize="marker" data-draggable="false" data-coordinate="<?php echo strip_tags(htmlspecialchars(($field_data['map_coordinates'] ? $field_data['map_coordinates'] : get_setting('office_map')))); ?>" style="height:260px"></div>
		</div>
	</div>
</div>