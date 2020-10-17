<div class="jumbotron jumbotron-fluid bg-transparent">
	<div class="container">
		<div class="row">
			<div class="col-0 col-sm-2 col-md-1 offset-md-1 d-none d-sm-block">
				<i class="<?php echo $meta->icon; ?> mdi-4x text-muted"></i>
			</div>
			<div class="col-12 col-sm-10 col-md-9 text-center text-sm-left">
				<h3 class="mb-0<?php echo (!$meta->description ? ' mt-3' : null); ?>">
					<?php echo $meta->title; ?>
				</h3>
				<p class="lead">
					<?php echo truncate($meta->description, 256); ?>
				</p>
			</div>
		</div>
	</div>
</div>
<div class="container">
	<div class="row">
		<div class="col-lg-8 offset-lg-2">
			<h6>
				<?php echo phrase('local_variable'); ?>
			</h6>
			<div class="form-group">
				<label class="d-block text-muted">
					Aksara Version
				</label>
				<label class="d-block">
					<?php echo SOFTWARE_VERSION; ?>
				</label>
			</div>
			<div class="form-group">
				<label class="d-block text-muted">
					<?php echo phrase('build_version'); ?>
				</label>
				<label class="d-block">
					<?php echo SOFTWARE_VERSION . CI_VERSION; ?>
				</label>
			</div>
			<div class="form-group">
				<label class="d-block text-muted">
					<?php echo phrase('release_date'); ?>
				</label>
				<label class="d-block">
					2019-01-19
				</label>
			</div>
			<br />
			<h6>
				<?php echo phrase('the_laborant'); ?>
			</h6>
			<div class="form-group">
				<label class="d-block text-muted">
					<a href="//abydahana.github.io" target="_blank">
						Aby Dahana
						<i class="mdi mdi-arrow-top-right"></i>
					</a>
				</label>
				<label class="d-block text-muted">
					<a href="//ganjar.id" target="_blank">
						Ganjar Nugraha
						<i class="mdi mdi-arrow-top-right"></i>
					</a>
				</label>
			</div>
			<br />
			<h6>
				<?php echo phrase('contributors'); ?>
			</h6>
			<div class="form-group">
				<label class="d-block text-muted">
					<?php echo phrase('not_yet_available'); ?>
				</label>
			</div>
			<br />
			<h6>
				<?php echo phrase('dependencies'); ?>
			</h6>
			<div class="form-group">
				<a href="//php.net" target="_blank">
					PHP
				</a>
				&middot;
				<a href="//codeigniter.com" target="_blank">
					Codeigniter
				</a>
				&middot;
				<a href="//getcomposer.org" target="_blank">
					Composer
				</a>
				&middot;
				<a href="//bitbucket.org/wiredesignz/codeigniter-modular-extensions-hmvc" target="_blank">
					HMVC
				</a>
				&middot;
				<a href="//mpdf.github.io" target="_blank">
					mPDF
				</a>
			</div>
			<br />
			<h6>
				JS/CSS <small class="text-muted">(<?php echo phrase('sorted_ascending'); ?>)</small>
			</h6>
			<div class="form-group">
				<a href="//github.com/dreamerslab/jquery.actual" target="_blank">
					Actual
				</a>
				&middot;
				<a href="//devbridge.com/sourcery/components/jquery-autocomplete/" target="_blank">
					Autocomplete
				</a>
				&middot;
				<a href="//github.com/BobKnothe/autoNumeric" target="_blank">
					autoNumeric
				</a>
				&middot;
				<a href="//getbootstrap.com" target="_blank">
					Bootstrap
				</a>
				&middot;
				<a href="//victor-valencia.github.io/bootstrap-iconpicker/" target="_blank">
					Bootstrap Iconpicker
				</a>
				&middot;
				<a href="//itsjavi.com/bootstrap-colorpicker/" target="_blank">
					Bootstrap Colorpicker
				</a>
				&middot;
				<a href="//bootstrap-datepicker.readthedocs.io/en/latest/" target="_blank">
					Bootstrap Datepicker
				</a>
				&middot;
				<a href="//blueimp.github.io/jQuery-File-Upload/" target="_blank">
					FileUploader
				</a>
				&middot;
				<a href="//highcharts.com" target="_blank">
					HighCharts
				</a>
				&middot;
				<a href="//github.com/ematsakov/highlight" target="_blank">
					Highlight
				</a>
				&middot;
				<a href="//infinite-scroll.com" target="_blank">
					Infinite Scroll
				</a>
				&middot;
				<a href="//jquery.com" target="_blank">
					jQuery
				</a>
				&middot;
				<a href="//jqueryui.com/draggable/" target="_blank">
					jQuery UI Draggable
				</a>
				&middot;
				<a href="//github.com/tuupola/lazyload" target="_blank">
					LazyLoad
				</a>
				&middot;
				<a href="//materialdesignicons.com" target="_blank">
					Materialdesignicons
				</a>
				&middot;
				<a href="//manos.malihu.gr/jquery-custom-content-scroller/" target="_blank">
					mCustomScrollbar
				</a>
				&middot;
				<a href="//www.mediaelementjs.com/" target="_blank">
					Mediaelementjs
				</a>
				&middot;
				<a href="//openlayers.org" target="_blank">
					OpenLayers
				</a>
				&middot;
				<a href="//github.com/OwlCarousel2" target="_blank">
					Owl Carousel
				</a>
				&middot;
				<a href="//popper.js.org" target="_blank">
					Popper
				</a>
				&middot;
				<a href="//select2.org" target="_blank">
					Select2
				</a>
				&middot;
				<a href="//camohub.github.io/jquery-sortable-lists/" target="_blank">
					Sortable
				</a>
				&middot;
				<a href="//summernote.org" target="_blank">
					Summernote
				</a>
				&middot;
				<a href="javascript:void(0)">
					Typewritter
				</a>
				&middot;
				<a href="//github.com/customd/jquery-visible" target="_blank">
					Visible
				</a>
				&middot;
				<a href="//maze.digital/webticker/" target="_blank">
					Webticker
				</a>
			</div>
		</div>
	</div>
</div>