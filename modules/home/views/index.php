<?php
	$carousels									= carousel($carousels);
	$news_items									= null;
	$gallery_items								= null;
	$people_items								= null;
	$navigator									= null;
	if(sizeof($latest_news) > 0)
	{
		foreach($latest_news as $key => $val)
		{
			$news_items							.= '
				<div class="item ' . random_bg() . ($key == 0 ? ' active' : null) . '">
					<a href="' . base_url('blogs/read?category_id=' . $val['post_category'] . '&post_id=' . $val['post_id']) . '" class="text-danger ajaxLoad">
						<div class="text-center" style="background:url(' . get_image('blogs', $val['featured_image']) . ') center center no-repeat; background-size: cover; height: 400px">
						</div>
						<div class="carousel-caption">
							<h3>
								' . $val['post_title'] . '
							</h3>
							<p>
								' . truncate($val['post_excerpt'], 160) . '
							</p>
						</div>
					</a>
				</div>
			';
			$navigator							.= '
				<li data-target="#myCarousel" data-slide-to="' . $key . '" class="list-group-item' . ($key == 0 ? ' active' : null) . '">
					<h4>
						' . truncate($val['post_title'], 64) . '
					</h4>
				</li>
			';
		}
		$news_items								= '
			<div id="myCarousel" class="carousel slide" data-ride="carousel" role="headline">
				<div class="carousel-inner">
					' . $news_items . '
				</div>
				<ul class="list-group">
					' . $navigator . '
				</ul>
			</div>
		';
	}
	if($latest_galleries)
	{
		foreach($latest_galleries as $key => $val)
		{
			$img								= 'placeholder.png';
			$json								= json_decode($val['gallery_images'], true);
			if(is_array($json) && sizeof($json) > 0)
			{
				foreach($json as $img => $src)
				{
				}
			}
			$gallery_items						.= '
				<div class="col-xs-6 text-center animated zoomIn">
					<a href="' . base_url('galleries/category?gallery_id=' . $val['gallery_id']) . '" class="ajaxLoad" data-reveal-animation="fadeInUp">
						<img src="' . get_image('galleries', $img, 'thumb') . '" class="img-responsive" width="100%" />
					</a>
				</div>
			';
		}
	}
	if($latest_peoples)
	{
		foreach($latest_peoples as $key => $val)
		{
			$people_items						.= '
				<div class="col-sm-3 col-xs-6 text-center" data-reveal-animation="fadeInUp">
					<div class="card">
						<div class="card-img">
							<a href="' . base_url('peoples/read?people_id=' . $val['people_id']) . '" class="ajaxLoad">
								<img src="' . get_image('peoples', $val['photo'], 'thumb') . '" class="img-responsive" alt="' . $val['first_name'] . ' ' . $val['last_name'] . '" width="100%" />
							</a>
						</div>
						<div class="card-block">
							<h4 class="card-title no-wrap">
								<a href="' . base_url('peoples/read?people_id=' . $val['people_id']) . '" class="ajaxLoad">
									' . $val['first_name'] . ' ' . $val['last_name'] . '
								</a>
							</h4>
							<div class="card-text no-wrap">
								<a href="' . base_url('peoples/read?people_id=' . $val['people_id']) . '" class="ajaxLoad">
									' . $val['position'] . '
								</a>
							</div>
						</div>
					</div>
				</div>
			';
		}
		$people_items							= '
			<div class="row">
				' . $people_items . '
			</div>
			<br />
			<div class="row">
				<div class="col-md-12 text-center">
					<a href="' . base_url('peoples') . '" class="btn btn-primary btn-holo btn-lg ajaxLoad">
						<i class="fa fa-search"></i>
						Temukan Lainnya
					</a>
				</div>
			</div>
		';
	}
	echo $carousels;
?>
<?php /*
<div class="slider-callout bg-blue container-fluid">
	<div style="padding: 30px 0">
		<div class="row">
			<div class="col-sm-6 col-sm-offset-2">
				<div class="input-group">
					<input type="text" class="form-control input-lg bordered" placeholder="Pencarian Data">
					<div class="input-group-btn">
						<button type="button" class="btn btn-default btn-holo btn-lg">
							<i class="fa fa-search"></i>
							&nbsp;
							Cari
						</button>
					</div>
				</div>
			</div>
			<div class="col-sm-2">
				<a href="" class="btn btn-default btn-holo btn-lg btn-block ajaxLoad">
					<i class="fa fa-hashtag"></i>
					&nbsp;
					Telusuri
				</a>
			</div>
		</div>
	</div>
</div>
*/ ?>
<div class="bg-primary">
	<div class="container-fluid">
		<div style="padding: 50px 0">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2 text-center">
					<h1 class="no-margin" data-reveal-animation="fadeInLeft">
						A SIMPLIFICATION MODEL
					</h1>
					<h1 class="no-margin" data-reveal-animation="fadeInRight">
						MORE THAN e-Budgeting!
					</h1>
					<h4 data-reveal-animation="fadeInUp">
						Sebuah teknologi aplikasi yang digunakan untuk membantu proses penyusunan RKA berbasis kinerja secara instant, cepat dan mudah.
					</h4>
				</div>
			</div>
		</div>
	</div>
</div><!--
<form id="carousel-placeholder" action="<?php echo current_url(); ?>" method="post" role="maps">
	<div id="maps" style="height:400px"></div>
	<div class="panel-group" id="filter">
		<div class="panel panel-default" style="background:rgba(0,0,0,.1)">
			<div id="collapse_1" class="panel-collapse collapse">
				<div class="panel-body">
					<?php
					/*	if($data_categories)
						{
							foreach($data_categories as $key => $val)
							{
								echo '
									<label class="control-label big-label">
										<input type="checkbox" name="data[]" value="' . $val['category_id'] . '" checked />
										' . $val['category_title'] . ' (' . $val['total'] . ')
									</label>
								';
							}
						}*/
					?>
				</div>
			</div>
		</div>
	</div>
</form>-->
<div style="background-image:url(<?php echo base_url('themes/default/img/maps.png'); ?>);background-position:center center;background-repeat:no-repeat">
	<div class="container-fluid">
		<div style="padding: 50px 0">
			<div class="row">
				<div class="col-lg-8 col-lg-offset-2 text-center">
					<div class="row form-group">
						<div class="col-sm-4 text-center" data-reveal-animation="fadeInLeft">
							<i class="fa fa-magic fa-5x animated "></i>
							<br />
							<h2>
								MUDAH
							</h2>
							<h4>
								Dalam mengoperasikan aplikasi e-prodget model sangat simple /user friendly.
							</h4>
						</div>
						<div class="col-sm-4 text-center" data-reveal-animation="fadeInDown">
							<i class="fa fa-bolt fa-5x"></i>
							<br />
							<h2>
								CEPAT
							</h2>
							<h4>
								Proses input data sangat cepat, hanya membutuhkan waktu 2 menit saja.
							</h4>
						</div>
						<div class="col-sm-4 text-center" data-reveal-animation="fadeInRight">
							<i class="fa fa-list-ol fa-5x"></i>
							<br />
							<h2>
								LENGKAP
							</h2>
							<h4>
								Dokumen yang dihasilkan lebih lengkap yaitu Kerangka Logis Kegiatan, Lembar Kerja dan Rencana Kerja dan Anggaran (RKA).
							</h4>
						</div>
					</div>
					<div class="row form-group">
						<div class="col-sm-4 text-center" data-reveal-animation="fadeInLeft">
							<i class="fa fa-bullseye fa-5x"></i>
							<br />
							<h2>
								AKURAT
							</h2>
							<h4>
								Perhitungan anggaran dalam RKA lebih akurat karena model RKA telah diuji dengan ASB dan standar harga serta standar biaya masukan.
							</h4>
						</div>
						<div class="col-sm-4 text-center" data-reveal-animation="fadeInUp">
							<i class="fa fa-spin fa-globe fa-5x"></i>
							<br />
							<h2>
								BERBASIS WEB
							</h2>
							<h4>
								Kemudahan akses aplikasi e-prodget model, di manapun, kapanpun dan dengan gawai apapun.
							</h4>
						</div>
						<div class="col-sm-4 text-center" data-reveal-animation="fadeInRight">
							<i class="fa fa-check-square-o fa-5x"></i>
							<br />
							<h2>
								TERSTANDAR
							</h2>
							<h4>
								Model RKA ditetapkan dengan peraturan Wali kota
								<br />
								Model RKA diberlakukan untuk semua jenis kegiatan yang sama.
							</h4>
						</div>
					</div>
					<div class="text-center" data-reveal-animation="bounceInDown">
						<a href="<?php echo base_url('pages'); ?>" class="btn btn-lg btn-primary btn-holo ajaxLoad">
							Pelajari Selengkapnya
							<i class="fa fa-arrow-right"></i>
						</a>
					</div>
					<br />
					<!--<br />
					<br />
					<iframe id="ytplayer" type="text/html" width="100%" height="500" src="https://www.youtube.com/embed/7ifWJcY7X5g" frameborder="0"></iframe>
					<br />
					<iframe id="ytplayer" type="text/html" width="100%" height="500" src="https://www.youtube.com/embed/5Vk9z5Hcn6Q" frameborder="0"></iframe> -->
				</div>
			</div>
		</div>
	</div>
</div>
<div class="<?php echo random_bg(); ?>" style="background-image:url(<?php echo base_url('themes/default/img/maps.png'); ?>);background-position:center center;background-repeat:no-repeat">
	<div class="container-fluid">
		<div style="padding: 50px 0">
			<div class="row">
				<div class="col-lg-10 col-lg-offset-1">
					<h2 class="text-center no-margin" data-reveal-animation="fadeInUp">
						<i class="fa fa-refresh"></i>
						Tetap Pantau Pemberitaan Dari Kami!
					</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div style="padding: 50px 0">
		<div class="row form-group">
			<div class="col-lg-7 col-lg-offset-1">
			
				<?php echo $news_items; ?>
				
			</div>
			<div class="col-lg-3">
				<div class="row form-group">
					<div class="col-sm-12 text-center">
						<h3 class="strike">
							<span>
								Galeri Terkini
							</span>
						</h3>
					</div>
					<?php echo $gallery_items; ?>
					
				</div>
			</div>
		</div>
	</div>
</div>
<div class="bg-blue">
	<div class="container-fluid">
		<div style="padding: 50px 0">
			<div class="row">
				<div class="col-lg-10 col-lg-offset-1">
					<h2 class="text-center no-margin" data-reveal-animation="fadeInUp">
						PEOPLE BEHIND THE SCENE
					</h2>
				</div>
			</div>
		</div>
	</div>
</div>
<div class="container-fluid">
	<div style="padding: 50px 0">
		<div class="row">
			<div class="col-lg-10 col-lg-offset-1">
				<?php echo $people_items; ?>
			</div>
		</div>
	</div>
</div>