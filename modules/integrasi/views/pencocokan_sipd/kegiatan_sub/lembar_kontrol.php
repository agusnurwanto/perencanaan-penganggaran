<!DOCTYPE html>
<html>
	<head>
		<title>
			<?php echo $title; ?>
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		
		<style type="text/css">
			@page
			{
				sheet-size: 13in 8.5in;
				footer: html_footer
			}
			.print
			{
				display: none
			}
			@media print
			{
				.no-print
				{
					display: none
				}
				.print
				{
					display: block
				}
			}
			body
			{
				font-family: 'Oxygen';
				font-size: 13px
			}
			label,
			h4
			{
				display: block
			}
			a,
			a:hover,
			a:focus,
			a:visited,
			a:link
			{
				text-decoration: none;
				color: #000
			}
			hr
			{
				border-top: 1px solid #999999;
				border-bottom: 0;
				margin-bottom: 15px
			}
			.separator
			{
				border-top: 3px solid #000000;
				border-bottom: 1px solid #000000;
				padding: 1px;
				margin-bottom: 15px
			}
			.text-sm
			{
				font-size: 10px
			}
			.text-uppercase
			{
				text-transform: uppercase
			}
			.text-muted
			{
				color: #888888
			}
			.text-left
			{
				text-align: left
			}
			.text-right
			{
				text-align: right
			}
			.text-center
			{
				text-align: center
			}
			.text-justify
			{
				text-align: justify
			}
			table
			{
				width: 100%
			}
			th
			{
				text-align:center;
				font-size: 12px;
				white-space: nowrap
			}
			td
			{
				font-size: 12px;
				padding: 5px;
				vertical-align: top
			}
			.table
			{
				border-collapse: collapse
			}
			.bordered
			{
				border: 1px solid #000
			}
			.no-border-left
			{
				border-left: 0
			}
			.no-border-top
			{
				border-top: 0
			}
			.no-border-right
			{
				border-right: 0
			}
			.no-border-bottom
			{
				border-bottom: 0
			}
			.no-padding
			{
				padding: 0;
				border: 0
			}
			h1
			{
				font-size: 24px
			}
			h2
			{
				font-size: 22px
			}
			h3
			{
				font-size: 20px
			}
			h4
			{
				font-size: 18px
			}
			h1, h2, h3, h4, h5
			{
				margin-top: 0;
				margin-bottom: 0
			}
		</style>
	</head>
	<body>
		<table class="table">
			<thead>
				<tr>
					<th colspan="4">
						<h1>
							<?php echo $title; ?>
						</h1>
						<p>
							Data antara SIPD dan Siencang yang belum cocok ditandai dengan warna merah muda biar unyu-unyu
						</p>
					</th>
				</tr>
				<tr>
					<th colspan="2" class="bordered">
						SIPD
					</th>
					<th colspan="2" class="bordered">
						SIENCANG
					</th>
				</tr>
				<tr>
					<th class="bordered">
						KODE
					</th>
					<th class="bordered">
						LABEL
					</th>
					<th class="bordered">
						KODE
					</th>
					<th class="bordered">
						LABEL
					</th>
				</tr>
			</thead>
			<tbody>
				<?php
					if($results)
					{
						foreach($results as $key => $val)
						{
							$color					= null;
							
							if($val->kode_sipd != $val->kode_siencang && $val->label_sipd != $val->label_siencang)
							{
								$color				= '#ffaaaa';
							}
							
							echo '
								<tr' . ($color ? ' style="background:' . $color . '"' : null) . '>
									<td class="bordered">
										' . $val->kode_sipd . '
									</td>
									<td class="bordered">
										' . $val->label_sipd . '
									</td>
									<td class="bordered">
										' . $val->kode_siencang . '
									</td>
									<td class="bordered">
										' . $val->label_siencang . '
									</td>
								</tr>
							';
						}
					}
				?>
			</tbody>
		</table>
	</body>
</html>