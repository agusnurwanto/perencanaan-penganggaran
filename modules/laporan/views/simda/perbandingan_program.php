<!DOCTYPE html>
<html>
	<head>
		<title>
			Perbandingan Program
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
						PERBANDINGAN PROGRAM
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
				<?php
					$kd_urusan						= 0;
					$kd_bidang						= 0;
					$kd_unit						= 0;
					$kd_sub							= 0;
					$kd_program						= 0;
					$kd_id_prog						= 0;
					foreach($results as $key => $val)
					{
						$urusan						= ((isset($val['kd_urusan']) && isset($val['kd_urusan_simda']) && $val['kd_urusan'] != $val['kd_urusan_simda']) || (!isset($val['kd_urusan']) || !isset($val['kd_urusan_simda'])) ? false : true);
						
						$bidang						= ((isset($val['kd_bidang']) && isset($val['kd_bidang_simda']) && $val['kd_bidang'] != $val['kd_bidang_simda']) || (!isset($val['kd_bidang']) || !isset($val['kd_bidang_simda'])) ? false : true);
						
						$unit						= ((isset($val['kd_unit']) && isset($val['kd_unit_simda']) && $val['kd_unit'] != $val['kd_unit_simda']) || (!isset($val['kd_unit']) || !isset($val['kd_unit_simda'])) ? false : true);
						
						$sub						= ((isset($val['kd_sub']) && isset($val['kd_sub_simda']) && $val['kd_sub'] != $val['kd_sub_simda']) || (!isset($val['kd_sub']) || !isset($val['kd_sub_simda'])) ? false : true);
						
						$program					= ((isset($val['kd_program']) && isset($val['kd_program_simda']) && $val['kd_program'] != $val['kd_program_simda']) || (!isset($val['kd_program']) || !isset($val['kd_program_simda'])) ? false : true);
						
						$id_prog					= ((isset($val['kd_id_prog']) && isset($val['kd_id_prog_simda']) && $val['kd_id_prog'] != $val['kd_id_prog_simda']) || (!isset($val['kd_id_prog']) || !isset($val['kd_id_prog_simda'])) ? false : true);
						
						if(isset($val['kd_urusan']) && $val['kd_urusan'] != $kd_urusan || isset($val['kd_urusan_simda']) && $val['kd_urusan_simda'] != $kd_urusan)
						{
							echo '
								<tr' . (!$urusan || (!isset($val['nm_urusan']) || !isset($val['nm_urusan_simda'])) || $val['nm_urusan'] != $val['nm_urusan_simda'] ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										<b>' . (isset($val['kd_urusan']) ? $val['kd_urusan'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['nm_urusan']) ? $val['nm_urusan'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['kd_urusan_simda']) ? $val['kd_urusan_simda'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['nm_urusan_simda']) ? $val['nm_urusan_simda'] : null) . '</b>
									</td>
								</tr>
							';
						}
						if(isset($val['kd_urusan']) && $val['kd_urusan'] != $kd_urusan || isset($val['kd_urusan_simda']) && $val['kd_urusan_simda'] != $kd_urusan && isset($val['kd_bidang']) && $val['kd_bidang'] != $kd_bidang || isset($val['kd_bidang_simda']) && $val['kd_bidang_simda'] != $kd_bidang)
						{
							echo '
								<tr' . (!$urusan || !$bidang || (!isset($val['nm_bidang']) || !isset($val['nm_bidang_simda'])) || $val['nm_bidang'] != $val['nm_bidang_simda'] ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										<b>' . (isset($val['kd_urusan']) ? $val['kd_urusan'] : null) . (isset($val['kd_bidang']) ? '.' . $val['kd_bidang'] : null) . '</b>
									</td>
									<td style="padding-left:5px" class="bordered">
										<b>' . (isset($val['nm_bidang']) ? $val['nm_bidang'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['kd_urusan_simda']) ? $val['kd_urusan_simda'] : null) . (isset($val['kd_bidang_simda']) ? '.' . $val['kd_bidang_simda'] : null) . '</b>
									</td>
									<td style="padding-left:5px" class="bordered">
										<b>' . (isset($val['nm_bidang_simda']) ? $val['nm_bidang_simda'] : null) . '</b>
									</td>
								</tr>
							';
						}
						if(isset($val['kd_urusan']) && $val['kd_urusan'] != $kd_urusan || isset($val['kd_urusan_simda']) && $val['kd_urusan_simda'] != $kd_urusan && isset($val['kd_bidang']) && $val['kd_bidang'] != $kd_bidang || isset($val['kd_bidang_simda']) && $val['kd_bidang_simda'] != $kd_bidang && isset($val['kd_unit']) && $val['kd_unit'] != $kd_unit || isset($val['kd_unit_simda']) && $val['kd_unit_simda'] != $kd_unit)
						{
							echo '
								<tr' . (!$urusan || !$bidang || !$unit || (!isset($val['nm_unit']) || !isset($val['nm_unit_simda'])) || $val['nm_unit'] != $val['nm_unit_simda'] ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										<b>' . (isset($val['kd_urusan']) ? $val['kd_urusan'] : null) . (isset($val['kd_bidang']) ? '.' . $val['kd_bidang'] : null) . (isset($val['kd_unit']) ? '.' . $val['kd_unit'] : null) . '</b>
									</td>
									<td style="padding-left:10px" class="bordered">
										<b>' . (isset($val['nm_unit']) ? $val['nm_unit'] : null) . '</b>
									</td>
									<td class="bordered">
										<b>' . (isset($val['kd_urusan_simda']) ? $val['kd_urusan_simda'] : null) . (isset($val['kd_bidang_simda']) ? '.' . $val['kd_bidang_simda'] : null) . (isset($val['kd_unit_simda']) ? '.' . $val['kd_unit_simda'] : null) . '</b>
									</td>
									<td style="padding-left:10px" class="bordered">
										<b>' . (isset($val['nm_unit_simda']) ? $val['nm_unit_simda'] : null) . '</b>
									</td>
								</tr>
							';
						}
						if(isset($val['kd_urusan']) && $val['kd_urusan'] != $kd_urusan || isset($val['kd_urusan_simda']) && $val['kd_urusan_simda'] != $kd_urusan && isset($val['kd_bidang']) && $val['kd_bidang'] != $kd_bidang || isset($val['kd_bidang_simda']) && $val['kd_bidang_simda'] != $kd_bidang && isset($val['kd_unit']) && $val['kd_unit'] != $kd_unit || isset($val['kd_unit_simda']) && $val['kd_unit_simda'] != $kd_unit && isset($val['kd_sub']) && $val['kd_sub'] != $kd_sub || isset($val['kd_sub_simda']) && $val['kd_sub_simda'] != $kd_sub)
						{
							echo '
								<tr' . (!$urusan || !$bidang || !$unit || !$sub || (!isset($val['nm_sub']) || !isset($val['nm_sub_simda'])) || $val['nm_sub'] != $val['nm_sub_simda'] ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										' . (isset($val['kd_urusan']) ? $val['kd_urusan'] : null) . (isset($val['kd_bidang']) ? '.' . $val['kd_bidang'] : null) . (isset($val['kd_unit']) ? '.' . $val['kd_unit'] : null) . (isset($val['kd_sub']) ? '.' . sprintf('%02d', $val['kd_sub']) : null) . '
									</td>
									<td style="padding-left:15px" class="bordered">
										' . (isset($val['nm_sub']) ? $val['nm_sub'] : null) . '
									</td>
									<td class="bordered">
										' . (isset($val['kd_urusan_simda']) ? $val['kd_urusan_simda'] : null) . (isset($val['kd_bidang_simda']) ? '.' . $val['kd_bidang_simda'] : null) . (isset($val['kd_unit_simda']) ? '.' . $val['kd_unit_simda'] : null) . (isset($val['kd_sub_simda']) ? '.' . sprintf('%02d', $val['kd_sub_simda']) : null) . '
									</td>
									<td style="padding-left:15px" class="bordered">
										' . (isset($val['nm_sub_simda']) ? $val['nm_sub_simda'] : null) . '
									</td>
								</tr>
							';
						}
						if(isset($val['kd_urusan']) && $val['kd_urusan'] != $kd_urusan || isset($val['kd_urusan_simda']) && $val['kd_urusan_simda'] != $kd_urusan && isset($val['kd_bidang']) && $val['kd_bidang'] != $kd_bidang || isset($val['kd_bidang_simda']) && $val['kd_bidang_simda'] != $kd_bidang && isset($val['kd_unit']) && $val['kd_unit'] != $kd_unit || isset($val['kd_unit_simda']) && $val['kd_unit_simda'] != $kd_unit && isset($val['kd_sub']) && $val['kd_sub'] != $kd_sub || isset($val['kd_sub_simda']) && $val['kd_sub_simda'] != $kd_sub && isset($val['kd_program']) && $val['kd_program'] != $kd_program || isset($val['kd_program_simda']) && $val['kd_program_simda'] != $kd_program)
						{
							echo '
								<tr' . (!$urusan || !$bidang || !$unit || !$sub || !$program || !$id_prog || (!isset($val['nm_program']) || !isset($val['nm_program_simda'])) || $val['nm_program'] != $val['nm_program_simda'] ? ' bgcolor="#ffcccc"' : null) . '>
									<td class="bordered">
										' . (isset($val['kd_urusan']) ? $val['kd_urusan'] : null) . (isset($val['kd_bidang']) ? '.' . $val['kd_bidang'] : null) . (isset($val['kd_unit']) ? '.' . $val['kd_unit'] : null) . (isset($val['kd_sub']) ? '.' . sprintf('%02d', $val['kd_sub']) : null) . (isset($val['kd_program']) ? '.' . sprintf('%02d', $val['kd_program']) : null) . (isset($val['kd_id_prog']) ? '.' . sprintf('%02d', $val['kd_id_prog']) : null) . '
									</td>
									<td style="padding-left:20px" class="bordered">
										' . (isset($val['nm_program']) ? $val['nm_program'] : null) . '
									</td>
									<td class="bordered">
										' . (isset($val['kd_urusan_simda']) ? $val['kd_urusan_simda'] : null) . (isset($val['kd_bidang_simda']) ? '.' . $val['kd_bidang_simda'] : null) . (isset($val['kd_unit_simda']) ? '.' . $val['kd_unit_simda'] : null) . (isset($val['kd_sub_simda']) ? '.' . sprintf('%02d', $val['kd_sub_simda']) : null) . (isset($val['kd_program_simda']) ? '.' . sprintf('%02d', $val['kd_program_simda']) : null) . (isset($val['kd_id_prog_simda']) ? '.' . sprintf('%02d', $val['kd_id_prog_simda']) : null) . '
									</td>
									<td style="padding-left:20px" class="bordered">
										' . (isset($val['nm_program_simda']) ? $val['nm_program_simda'] : null) . '
									</td>
								</tr>
							';
						}
						$kd_urusan					= (isset($val['kd_urusan']) ? $val['kd_urusan'] : (isset($val['kd_urusan_simda']) ? $val['kd_urusan_simda'] : 0));
						$kd_bidang					= (isset($val['kd_bidang']) ? $val['kd_bidang'] : (isset($val['kd_bidang_simda']) ? $val['kd_bidang_simda'] : 0));
						$kd_unit					= (isset($val['kd_unit']) ? $val['kd_unit'] : (isset($val['kd_unit_simda']) ? $val['kd_unit_simda'] : 0));
						$kd_sub						= (isset($val['kd_sub']) ? $val['kd_sub'] : (isset($val['kd_sub_simda']) ? $val['kd_sub_simda'] : 0));
						$kd_program					= (isset($val['kd_program']) ? $val['kd_program'] : (isset($val['kd_program_simda']) ? $val['kd_program_simda'] : 0));
						$kd_id_prog					= (isset($val['kd_id_prog']) ? $val['kd_id_prog'] : (isset($val['kd_id_prog_simda']) ? $val['kd_id_prog_simda'] : 0));
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