<?php
	$user_language								= null;
	$translations								= array();
	$language									= get_languages();
	$language_list								= null;
	if($language && sizeof((array) $language) > 1)
	{
		foreach($language as $key => $val)
		{
			if($this->session->userdata('language') == $val->code)
			{
				$user_language					= $val->language;
			}
			
			$translations[$val->code]			= $val->language;
			
			$language_list						.= '
				<li class="nav-item">
					<a class="nav-link --xhr" href="' . base_url('xhr/language/' . $val->code) . '">
						<i class="mdi mdi-flag-outline"></i>
						' . $val->language . '
					</a>
				</li>
			';
		}
	}
?>
<nav class="navbar fixed-top navbar-expand-lg navbar-dark bg-dark">
	<a class="navbar-brand desktop-logo d-none d-md-block d-lg-block d-xl-block" href="<?php echo base_url(); ?>" target="_blank">
		<img src="<?php echo get_image('settings', get_setting('app_logo')); ?>" class="img-fluid img-logo" />
		<img src="<?php echo get_image('settings', get_setting('app_icon')); ?>" class="img-fluid img-icon" />
		<?php echo ($this->session->userdata('year') ? '<span class="badge badge-warning">' . $this->session->userdata('year') . '</span>' : ''); ?>
	</a>
	<a href="#" class="navbar-brand --xhr d-md-none d-lg-none d-xl-none text-truncate text-white w-75 will-be-replace-with-title">
		<?php echo $template['title']; ?>
	</a>
	<button type="button" class="navbar-toggler collapsed pr-0 pl-0 d-none d-lg-block d-xl-block" data-toggle="sidebar" title="Toggle responsive left sidebar">
		<span class="navbar-toggler-icon desktop-toggler"></span>
	</button>
	<button type="button" class="navbar-toggler collapsed pr-0 pl-0" data-toggle="sidebar" title="Toggle responsive left sidebar">
		<span class="navbar-toggler-icon"></span>
	</button>
	<button type="button" class="navbar-toggler collapsed d-none" data-toggle="collapse" data-target="#navbarExpand" aria-label="Toggle navigation">
		<span class="navbar-toggler-icon"></span>
	</button>
	<div class="collapse navbar-collapse" id="navbarExpand">
		<ul class="navbar-nav ml-auto">
			<li class="nav-item">
				<a href="<?php echo base_url('forum'); ?>" class="nav-link" target="_blank">
					<i class="mdi mdi-bullhorn"></i>
					<?php echo phrase('forum'); ?>
				</a>
			</li>
			<?php if(in_array(get_userdata('group_id'), array(1))) { ?>
			<li class="nav-item">
				<a href="<?php echo base_url('master/standar_harga', array('backend' => 1, 'sub_unit' => null, 'id_keg' => null)); ?>" class="nav-link --modal">
					<span class="badge badge-danger ssh-count float-right">0</span>
					<i class="mdi mdi-comment-multiple-outline"></i>
					SSH
				</a>
			</li>
			<?php } ?>
			<?php if(in_array(get_userdata('group_id'), array(1, 2))) { ?>
			<li class="nav-item">
				<a href="<?php echo base_url('master/renja/tanggapan', array('backend' => 1, 'sub_unit' => null, 'id_keg' => null)); ?>" class="nav-link --modal">
					<span class="badge badge-danger rka-count float-right">0</span>
					<i class="mdi mdi-comment-multiple-outline"></i>
					RKA
				</a>
			</li>
			<?php } ?>
			<?php if(in_array(get_userdata('group_id'), array(1))) { ?>
			<li class="nav-item">
				<a href="<?php echo base_url('master/renja/tanggapan_kak', array('backend' => 1, 'sub_unit' => null, 'id_keg' => null)); ?>" class="nav-link --modal">
					<span class="badge badge-danger kak-count float-right">0</span>
					<i class="mdi mdi-comment-multiple-outline"></i>
					KAK
				</a>
			</li>
			<?php } ?>
			<?php if($language_list) { ?>
			<li class="nav-item dropdown">
				<a class="nav-link dropdown-toggle" href="#" id="languageDropdown" role="button" data-toggle="dropdown" aria-haspopup="true" aria-expanded="false">
					<i class="mdi mdi-translate"></i>
					<?php echo $user_language; ?>
				</a>
				<ul class="dropdown-menu dropdown-menu-right">
					<?php echo $language_list; ?>
				</ul>
			</li>
			<?php } ?>
			<li class="nav-item">
				<a href="<?php echo base_url('administrative/account'); ?>" class="nav-link --xhr">
					<i class="mdi mdi-cogs"></i>
					<?php echo phrase('account'); ?>
				</a>
			</li>
			<li class="nav-item">
				<a href="<?php echo base_url('auth/sign_out'); ?>" class="nav-link --xhr">
					<i class="mdi mdi-logout"></i>
					<?php echo phrase('sign_out'); ?>
				</a>
			</li>
		</ul>
	</div>
</nav>