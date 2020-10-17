<div class="container-fluid pt-3 pb-3">
	<form action="<?php echo current_page(); ?>" method="POST" class="--validate-form" enctype="multipart/form-data">
		<div class="row">
			<div class="col-md-2">
				<img src="<?php echo get_image('users', (isset($results->photo) ? $results->photo : null), 'thumb'); ?>" class="img-fluid rounded" />
			</div>
			<div class="col-md-8">
				<div class="row">
					<label class="col-4 col-md-3 text-muted">
						User ID
					</label>
					<label class="col-8 col-md-9">
						<?php echo (isset($results->user_id) ? $results->user_id : null); ?>
					</label>
				</div>
				<div class="row">
					<label class="col-4 col-md-3 text-muted">
						Username
					</label>
					<label class="col-8 col-md-9">
						<?php echo (isset($results->username) ? $results->username : null); ?>
					</label>
				</div>
				<div class="row">
					<label class="col-4 col-md-3 text-muted">
						Full Name
					</label>
					<label class="col-8 col-md-9">
						<?php echo (isset($results->first_name) ? $results->first_name : null); ?> <?php echo (isset($results->last_name) ? $results->last_name : null); ?>
					</label>
				</div>
				<div class="row">
					<label class="col-4 col-md-3 text-muted">
						Grup
					</label>
					<label class="col-8 col-md-9">
						<b>
							<?php echo (isset($results->group_name) ? $results->group_name : null); ?>
						</b>
					</label>
				</div>
				<div class="row">
					<label class="col-4 col-md-3 text-muted">
						Access Year
					</label>
					<label class="col-8 col-md-4">
						<?php
							$year					= get_active_years();
							$options				= null;
							if($year)
							{
								foreach($year as $key => $val)
								{
									$options		.= '<option value="' . $val->year . '"' . (isset($form_data->access_year) && $form_data->access_year == $val->year ? ' selected' : null) . '>' . $val->year . '</option>';
								}
							}
						?>
						<select name="access_year" class="form-control" placeholder="<?php //echo phrase('please_choose'); ?>">
							<?php echo $options; ?>
						</select>
					</label>
				</div>
			</div>
		</div>
		<hr />
		<div class="row">
			<div class="col-md-4">
				<div class="form-group">
					<label class="text-muted d-block" for="menus_input">
						<?php echo phrase('accessible_menus'); ?>
					</label>
					<?php echo $visible_menu; ?>
				</div>
			</div>
			<div class="col-md-4">
				<div class="form-group">
					<label class="text-muted d-block" for="kegiatan_input">
						<i class="mdi mdi-check"></i>
						<?php 
							echo 
								(isset($results->group_id) && 6 == $results->group_id ? 'RW' : 
								(isset($results->group_id) && 7 == $results->group_id ? 'Kelurahan' : 
								(isset($results->group_id) && 8 == $results->group_id ? 'Kecamatan' : 
								(isset($results->group_id) && 9 == $results->group_id ? 'Fraksi' : 
								(isset($results->group_id) && 10 == $results->group_id ? 'DPRD' : 
								(isset($results->group_id) && in_array($results->group_id, array(11, 12)) ? 'Sub Unit' : 
								(isset($results->group_id) && in_array($results->group_id, array(13, 14)) ? 'Unit' : 
								(isset($results->group_id) && in_array($results->group_id, array(16, 17, 18)) ? 'Bidang Bappeda' : 
								(isset($results->group_id) && in_array($results->group_id, array(19)) ? 'Tim Anggaran' : null)))))))));
							?>
							yang dapat diakses
					</label>
					<?php
						if($sub_unit)
						{
							$options					= null;
							foreach($sub_unit as $key => $val)
							{
								$options				.= '<option value="' . $val->id . '"' . ($val->id == $results->id_sub ? ' selected' : null) . '>' . (isset($val->kode_gabung) ? $val->kode_gabung : null) . (isset($val->nama_gabung) ? $val->nama_gabung : null) . '</option>';
							}
							echo '
								<select name="sub_level_1" class="form-control">
									' . $options . '
								</select>
							';
						}
						else
						{
							echo '
								<div class="alert alert-warning">
									Belum ada satupun sub unit yang diinput
								</div>
							';
						}
					?>
				</div>
			</div>
		</div>
		<div class="opt-btn-overlap-fix"></div><!-- fix the overlap -->
		<div class="row opt-btn">
			<div class="col-md-8">
			
				<div class="--validation-callback mb-0"></div>
				
				<input type="hidden" name="token" value="<?php echo $token; ?>" />
				<a href="<?php echo go_to(null, array('user_id' => null)); ?>" class="btn btn-light --xhr">
					<i class="mdi mdi-arrow-left"></i>
					&nbsp;
					<?php echo phrase('back'); ?>
				</a>
				<button type="submit" class="btn btn-primary float-right">
					<i class="mdi mdi-check"></i>
					&nbsp;
					<?php echo phrase('update'); ?>
					<em class="text-sm">(ctrl+s)</em>
				</button>
			</div>
		</div>
	</form>
</div>