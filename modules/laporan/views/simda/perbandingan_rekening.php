<!DOCTYPE html>
<html>
	<head>
		<title>
			Perbandingan Rekening
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		<style type="text/css">
			@import url('<?php echo base_url('themes/assets/fonts/Oxygen/Oxygen.css'); ?>');
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
		<table align="center">
			<tr>
				<td>
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</td>
				<td align="center" width="100%">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						PERBANDINGAN REKENING
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year'); ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" colspan="2" width="50%">
						SIENCANG
					</th>
					<th class="bordered" colspan="2" width="50%">
						SIMDA
					</th>
				</tr>
				<tr>
					<th class="bordered">
						KODE
					</th>
					<th class="bordered">
						ORGANISASI
					</th>
					<th class="bordered">
						KODE
					</th>
					<th class="bordered">
						ORGANISASI
					</th>
				</tr>
			</thead>
			<tbody>
			<tbody>
				<?php
					$kd_rek_1						= 0;
					$kd_rek_2						= 0;
					$kd_rek_3						= 0;
					$kd_rek_4						= 0;
					$kd_rek_5						= 0;
					foreach($results as $key => $val)
					{
						if((isset($val['kd_rek_1']) ? $val['kd_rek_1'] : (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : 0)) != $kd_rek_1)
						{
							echo '
								<tr' . ((isset($val['nm_rek_1']) && isset($val['nm_rek_1_simda']) && $val['nm_rek_1'] != $val['nm_rek_1_simda']) || (!isset($val['kd_rek_1']) || !isset($val['nm_rek_1']) || !isset($val['kd_rek_1_simda']) || !isset($val['nm_rek_1_simda'])) ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										<b>' . (isset($val['kd_rek_1']) ? $val['kd_rek_1'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['nm_rek_1']) ? $val['nm_rek_1'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['nm_rek_1_simda']) ? $val['nm_rek_1_simda'] : null) . '</b>
									</td>
								</tr>
							';
						}
						if((isset($val['kd_rek_1']) ? $val['kd_rek_1'] : (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : 0)) != $kd_rek_1 || (isset($val['kd_rek_2']) ? $val['kd_rek_2'] : (isset($val['kd_rek_2_simda']) ? $val['kd_rek_2_simda'] : 0)) != $kd_rek_2)
						{
							echo '
								<tr' . ((isset($val['nm_rek_2']) && isset($val['nm_rek_2_simda']) && $val['nm_rek_2'] != $val['nm_rek_2_simda']) || (!isset($val['kd_rek_1']) || !isset($val['kd_rek_2']) || !isset($val['nm_rek_1']) || !isset($val['kd_rek_1_simda']) || !isset($val['kd_rek_2_simda']) || !isset($val['nm_rek_1_simda'])) ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										<b>' . (isset($val['kd_rek_1']) ? $val['kd_rek_1'] : null) . (isset($val['kd_rek_2']) ? '.' . $val['kd_rek_2'] : null) . '</b>
									</td>
									<td style="padding-left:5px" class="bordered">
										<b>' . (isset($val['nm_rek_2']) ? $val['nm_rek_2'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : null) . (isset($val['kd_rek_2_simda']) ? '.' . $val['kd_rek_2_simda'] : null) . '</b>
									</td>
									<td style="padding-left:5px" class="bordered">
										<b>' . (isset($val['nm_rek_2_simda']) ? $val['nm_rek_2_simda'] : null) . '</b>
									</td>
								</tr>
							';
						}
						if((isset($val['kd_rek_1']) ? $val['kd_rek_1'] : (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : 0)) != $kd_rek_1 || (isset($val['kd_rek_2']) ? $val['kd_rek_2'] : (isset($val['kd_rek_2_simda']) ? $val['kd_rek_2_simda'] : 0)) != $kd_rek_2 || (isset($val['kd_rek_3']) ? $val['kd_rek_3'] : (isset($val['kd_rek_3_simda']) ? $val['kd_rek_3_simda'] : 0)) != $kd_rek_3)
						{
							echo '
								<tr' . ((isset($val['nm_rek_3']) && isset($val['nm_rek_3_simda']) && $val['nm_rek_3'] != $val['nm_rek_3_simda']) || (!isset($val['kd_rek_1']) || !isset($val['kd_rek_2']) || !isset($val['kd_rek_3']) || !isset($val['nm_rek_1']) || !isset($val['kd_rek_1_simda']) || !isset($val['kd_rek_2_simda']) || !isset($val['kd_rek_3_simda']) || !isset($val['nm_rek_1_simda'])) ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										<b>' . (isset($val['kd_rek_1']) ? $val['kd_rek_1'] : null) . (isset($val['kd_rek_2']) ? '.' . $val['kd_rek_2'] : null) . (isset($val['kd_rek_3']) ? '.' . $val['kd_rek_3'] : null) . '</b>
									</td>
									<td style="padding-left:10px" class="bordered">
										<b>' . (isset($val['nm_rek_3']) ? $val['nm_rek_3'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : null) . (isset($val['kd_rek_2_simda']) ? '.' . $val['kd_rek_2_simda'] : null) . (isset($val['kd_rek_3_simda']) ? '.' . $val['kd_rek_3_simda'] : null) . '</b>
									</td>
									<td style="padding-left:10px" class="bordered">
										<b>' . (isset($val['nm_rek_3_simda']) ? $val['nm_rek_3_simda'] : null) . '</b>
									</td>
								</tr>
							';
						}
						if((isset($val['kd_rek_1']) ? $val['kd_rek_1'] : (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : 0)) != $kd_rek_1 || (isset($val['kd_rek_2']) ? $val['kd_rek_2'] : (isset($val['kd_rek_2_simda']) ? $val['kd_rek_2_simda'] : 0)) != $kd_rek_2 || (isset($val['kd_rek_3']) ? $val['kd_rek_3'] : (isset($val['kd_rek_3_simda']) ? $val['kd_rek_3_simda'] : 0)) != $kd_rek_3 || (isset($val['kd_rek_4']) ? $val['kd_rek_4'] : (isset($val['kd_rek_4_simda']) ? $val['kd_rek_4_simda'] : 0)) != $kd_rek_4)
						{
							echo '
								<tr' . ((isset($val['nm_rek_4']) && isset($val['nm_rek_4_simda']) && $val['nm_rek_4'] != $val['nm_rek_4_simda']) || (!isset($val['kd_rek_1']) || !isset($val['kd_rek_2']) || !isset($val['kd_rek_3']) || !isset($val['kd_rek_4']) || !isset($val['nm_rek_1']) || !isset($val['kd_rek_1_simda']) || !isset($val['kd_rek_2_simda']) || !isset($val['kd_rek_3_simda']) || !isset($val['kd_rek_4_simda']) || !isset($val['nm_rek_1_simda'])) ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										' . (isset($val['kd_rek_1']) ? $val['kd_rek_1'] : null) . (isset($val['kd_rek_2']) ? '.' . $val['kd_rek_2'] : null) . (isset($val['kd_rek_3']) ? '.' . $val['kd_rek_3'] : null) . (isset($val['kd_rek_4']) ? '.' . sprintf('%02d', $val['kd_rek_4']) : null) . '
									</td>
									<td style="padding-left:15px" class="bordered">
										' . (isset($val['nm_rek_4']) ? $val['nm_rek_4'] : null) . '
									</td>
									<td class="bordered">
										' . (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : null) . (isset($val['kd_rek_2_simda']) ? '.' . $val['kd_rek_2_simda'] : null) . (isset($val['kd_rek_3_simda']) ? '.' . $val['kd_rek_3_simda'] : null) . (isset($val['kd_rek_4_simda']) ? '.' . sprintf('%02d', $val['kd_rek_4_simda']) : null) . '
									</td>
									<td style="padding-left:15px" class="bordered">
										' . (isset($val['nm_rek_4_simda']) ? $val['nm_rek_4_simda'] : null) . '
									</td>
								</tr>
							';
						}
						if((isset($val['kd_rek_1']) ? $val['kd_rek_1'] : (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : 0)) != $kd_rek_1 || (isset($val['kd_rek_2']) ? $val['kd_rek_2'] : (isset($val['kd_rek_2_simda']) ? $val['kd_rek_2_simda'] : 0)) != $kd_rek_2 || (isset($val['kd_rek_3']) ? $val['kd_rek_3'] : (isset($val['kd_rek_3_simda']) ? $val['kd_rek_3_simda'] : 0)) != $kd_rek_3 || (isset($val['kd_rek_4']) ? $val['kd_rek_4'] : (isset($val['kd_rek_4_simda']) ? $val['kd_rek_4_simda'] : 0)) != $kd_rek_4 || (isset($val['kd_rek_5']) ? $val['kd_rek_5'] : (isset($val['kd_rek_5_simda']) ? $val['kd_rek_5_simda'] : 0)) != $kd_rek_5)
						{
							echo '
								<tr' . ((isset($val['nm_rek_5']) && isset($val['nm_rek_5_simda']) && $val['nm_rek_5'] != $val['nm_rek_5_simda']) || (!isset($val['kd_rek_1']) || !isset($val['kd_rek_2']) || !isset($val['kd_rek_3']) || !isset($val['kd_rek_4']) || !isset($val['kd_rek_5']) || !isset($val['nm_rek_1']) || !isset($val['kd_rek_1_simda']) || !isset($val['kd_rek_2_simda']) || !isset($val['kd_rek_3_simda']) || !isset($val['kd_rek_4_simda']) || !isset($val['kd_rek_5_simda']) || !isset($val['nm_rek_1_simda'])) ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										' . (isset($val['kd_rek_1']) ? $val['kd_rek_1'] : null) . (isset($val['kd_rek_2']) ? '.' . $val['kd_rek_2'] : null) . (isset($val['kd_rek_3']) ? '.' . $val['kd_rek_3'] : null) . (isset($val['kd_rek_4']) ? '.' . sprintf('%02d', $val['kd_rek_4']) : null) . (isset($val['kd_rek_5']) ? '.' . sprintf('%02d', $val['kd_rek_5']) : null) . '
									</td>
									<td style="padding-left:20px" class="bordered">
										' . (isset($val['nm_rek_5']) ? $val['nm_rek_5'] : null) . '
									</td>
									<td class="bordered">
										' . (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : null) . (isset($val['kd_rek_2_simda']) ? '.' . $val['kd_rek_2_simda'] : null) . (isset($val['kd_rek_3_simda']) ? '.' . $val['kd_rek_3_simda'] : null) . (isset($val['kd_rek_4_simda']) ? '.' . sprintf('%02d', $val['kd_rek_4_simda']) : null) . (isset($val['kd_rek_5_simda']) ? '.' . sprintf('%02d', $val['kd_rek_5_simda']) : null) . '
									</td>
									<td style="padding-left:20px" class="bordered">
										' . (isset($val['nm_rek_5_simda']) ? $val['nm_rek_5_simda'] : null) . '
									</td>
								</tr>
							';
						}
						$kd_rek_1					= (isset($val['kd_rek_1']) ? $val['kd_rek_1'] : (isset($val['kd_rek_1_simda']) ? $val['kd_rek_1_simda'] : 0));
						$kd_rek_2					= (isset($val['kd_rek_2']) ? $val['kd_rek_2'] : (isset($val['kd_rek_2_simda']) ? $val['kd_rek_2_simda'] : 0));
						$kd_rek_3					= (isset($val['kd_rek_3']) ? $val['kd_rek_3'] : (isset($val['kd_rek_3_simda']) ? $val['kd_rek_3_simda'] : 0));
						$kd_rek_4					= (isset($val['kd_rek_4']) ? $val['kd_rek_4'] : (isset($val['kd_rek_4_simda']) ? $val['kd_rek_4_simda'] : 0));
						$kd_rek_5					= (isset($val['kd_rek_5']) ? $val['kd_rek_5'] : (isset($val['kd_rek_5_simda']) ? $val['kd_rek_5_simda'] : 0));
					}
				?>
			</tbody>
		</table>
		<br />
		<br />
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
						</i>
					</td>
					<td class="text-muted text-sm text-right">
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>