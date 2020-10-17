<!DOCTYPE html>
<html>
	<head>
		<title>
			Reses DPRD Per SKPD
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
	<?php
		if($this->input->get('status') != null)
		{
			$status 				= $this->input->get('status');
			if($status == 1)
			{
				$status_text	= '<b>Usulan DPRD</b>';
			}
			elseif($status == 2)
			{
				$status_text	= '<b>Diterima SKPD</b>';
			}
			elseif($status == 3)
			{
				$status_text	= '<b>Ditolak SKPD</b>';
			}
			elseif($status == 4)
			{
				$status_text	= '<b>Pilih Semua</b>';
			}
			else
			{
				$status_text	= '<b>Anda Harus Pilih Status</b>';
			}
		}
		else
		{
			$status_text	= '<b>Anda Harus Pilih Status</b>';
		}
	?>
	<body>
		<table align="center">
			<tr>
				<td>
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</td>
				<td align="center" width="100%" colspan="3">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						HASIL RESES DPRD PER SKPD
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year')?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<table class="table">
			<tr>
				<td width="180" colspan="2">
					SKPD
				</td>
				<td width="1">
					:
				</td>
				<td width="100">
					<?php 
						if($this->input->get('id_skpd') != 999)
						{
							echo $results['header'][0]['kode_urusan']; ?>.<?php echo sprintf('%02d', $results['header'][0]['kode_bidang']); ?>.<?php echo sprintf('%02d', $results['header'][0]['kode_unit']);
						}
						else
						{
							echo "Semua SKPD"; 
						}
					?>
				</td>
				<td>
					<?php 
						if($this->input->get('id_skpd') != 999)
						{
							echo $results['header'][0]['nama_unit'];
						}
					?>
				</td>
			</tr>
			<tr>
				<td colspan="2">
					Status
				</td>
				<td>
					:
				</td>
				<td colspan="2">
					<?php echo $status_text; ?>
				</td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="bordered" width="10%">
						KODE
					</th>
					<th class="bordered" width="30%">
						PROGRAM / PEKERJAAN
					</th>
					<th class="bordered" width="35%">
						ALAMAT
					</th>
					
				<?php 
					if($this->input->get('id_dprd') == 99)
					{
				?>
					<th class="bordered" width="10%">
						DPRD
					</th>
				<?php
					}
					
					if($status == 4)
					{
				?>
					<th class="bordered" width="10%">
						STATUS
					</th>
				<?php
					}
				?>
				</tr>
			</thead>
			<tbody>
				<?php
					$num									= 1;
					$id_urusan								= 0;
					$id_bidang								= 0;
					$id_unit								= 0;
					$id_program								= 0;
					$nilai									= 0;
					$total									= 0;
					foreach($results['data'] as $key => $val)
					{
						if($this->input->get('status') != null)
						{
							$status 					= $this->input->get('status');
							if($status == 1) // Usulan DPRD
							{
								$nilai					= $val['nilai'];
								$total					+= $val['nilai'];
							}
							elseif($status == 3) // Ditolak SKPD
							{
								$nilai					= $val['nilai'];
								$total					+= $val['nilai'];
							}
							elseif($status == 2) // Diterima SKPD
							{
								$nilai					= $val['nilai'];
								$total					+= $val['nilai'];
							}
							elseif($status == 4) // Pilih Semua
							{
								if($val['flag'] == 0)
								{
									$nilai				= $val['nilai'];
									$total				+= $val['nilai'];
								}
								elseif($val['flag'] == 1)
								{
									$nilai				= $val['nilai'];
									$total				+= $val['nilai'];
								}
								elseif($val['flag'] == 2 )
								{
									$nilai				= $val['nilai'];
									$total				+= $val['nilai'];
								}
								else
								{
									$nilai				= $val['nilai'];
									$total				+= $val['nilai'];
								}
							}
						}
						if($val['id_urusan'] != $id_urusan)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_urusan'] . '
										</b>
									</td>
									<td class="bordered" colspan="2">
										<b>
											' . $val['nama_urusan'] . '
										</b>
									</td>
									
									';
								if($this->input->get('id_dprd') == 99)
								{
							
								}
								if($status == 4)
								{
							
								}
							echo '
								</tr>
							';
						}
						if($val['id_bidang'] != $id_bidang)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '
										</b>
									</td>
									<td class="bordered" colspan="2" style="padding-left:5px">
										<b>
											' . $val['nama_bidang'] . '
										</b>
									</td>
									
									';
								if($this->input->get('id_dprd') == 99)
								{
							
								}
								if($status == 4)
								{
							
								}
							echo '
								</tr>
							';
						}
						if($val['id_unit'] != $id_unit)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '.' . sprintf('%02d', $val['kode_unit']) . '
										</b>
									</td>
									<td class="bordered" colspan="2" style="padding-left:10px">
										<b>
											' . ucwords(strtolower($val['nama_unit'])) . '
										</b>
									</td>
									
									';
								if($this->input->get('id_dprd') == 99)
								{
							echo'	<td class="bordered">
										
									</td>
									';
								}
								if($status == 4)
								{
							
								}
							echo '
								</tr>
							';
						}
						if($val['id_program'] != $id_program)
						{
							echo '
								<tr>
									<td class="bordered">
										<b>
											' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '.' . sprintf('%02d', $val['kode_unit']) . '.' . sprintf('%02d', $val['kode_program']) . '
										</b>
									</td>
									<td class="bordered" colspan="2" style="padding-left:15px">
										<b>
											' . $val['nama_program'] . '
										</b>
									</td>
									
									';
								if($this->input->get('id_dprd') == 99)
								{
							echo'	<td class="bordered">
										
									</td>
									';
								}
								if($status == 4)
								{
							echo'	<td class="bordered">
									</td>
									';
								}
							echo '
								</tr>
							';
						}
						echo '
							<tr>
								<td class="bordered">
									' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '.' . sprintf('%02d', $val['kode_unit']) . '.' . sprintf('%02d', $val['kode_program']) . '.' . sprintf('%02d', $num) . '
								</td>
								<td class="bordered" style="padding-left:20px">
									' . $val['nama_pekerjaan'] . '
								</td>
								<td class="bordered">
									' . $val['map_address'] . ' - ' . $val['alamat_detail'] . '
								</td>
								
								';
							if($this->input->get('id_dprd') == 99)
							{
						echo'	<td class="bordered">
									' . $val['nama_dewan'] . '
								</td>
								';
							}
							if($status == 4)
							{
						echo'	<td class="bordered">
								';
								if($val['flag'] == 0)
								{
									echo "Usulan Reses";
								}
								elseif($val['flag'] == 1)
								{
									echo "Diterima SKPD";
								}
								elseif($val['flag'] == 2)
								{
									echo "Ditolak SKPD";
								}
								elseif($val['flag'] > 2)
								{
									echo "Diverifikasi SKPD";
								}
						echo '		
								</td>
								';
							}
						echo '
							</tr>
						';
						$num++;
						$id_urusan								= $val['id_urusan'];
						$id_bidang								= $val['id_bidang'];
						$id_unit								= $val['id_unit'];
						$id_program								= $val['id_program'];
					}
				?>
			</tbody>
			
		</table>
		<br />
		<br />
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="text-center" width="50%" colspan="2">
				</td>
				<td class="text-center" width="50%" colspan="2">
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo $tanggal_cetak; ?>,
					
			<?php 
				if($this->input->get('id_skpd') != 999)
				{	?>				
					<br />
						<b>
							<?php echo $results['header'][0]['nama_jabatan']; ?>
						</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php echo $results['header'][0]['nama_pejabat']; ?>
						</b>
					</u>
					<br />
					NIP. <?php echo $results['header'][0]['nip_pejabat']; 				
				}
			?>
				</td>
			</tr>
		</table>
		<htmlpagefooter name="footer">
			<table class="print">
				<tr>
					<td class="text-muted text-sm">
						<i>
							<?php //echo phrase('document_has_generated_from') . ' ' . get_setting('app_name') . ' ' . phrase('at') . ' {DATE F d Y, H:i:s}'; ?>
						</i>
					</td>
					<td class="text-muted text-sm" align="right">
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>