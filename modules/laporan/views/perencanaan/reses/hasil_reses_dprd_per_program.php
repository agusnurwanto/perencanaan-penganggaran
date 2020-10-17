<!DOCTYPE html>
<html>
	<head>
		<title>
			Reses DPRD Per Program
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		
		<style type="text/css">
			@page
			{
				footer: html_footer; /* !!! apply only when the htmlpagefooter is sets !!! */
				sheet-size: 8.5in 13in;
				margin: 50, 40, 40, 40
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
				font-family: Tahoma
			}
			.divider
			{
				display: block;
				border-top: 3px solid #000;
				border-bottom: 1px solid #000;
				padding: 1px;
				margin-bottom: 15px
			}
			.text-sm-2
			{
				font-size: 10px
			}
			.text-sm
			{
				font-size: 8px
			}
			.text-uppercase
			{
				text-transform: uppercase
			}
			.text-muted
			{
				color: #888
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
			table
			{
				width: 100%
			}
			th
			{
				font-weight: bold;
				font-size: 12px;
				padding: 6px;
			}
			td
			{
				vertical-align: top;
				font-size: 10px;
				padding: 5px;
			}
			.v-middle
			{
				vertical-align: middle
			}
			.table
			{
				border-collapse: collapse
			}
			.border
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
				padding: 0
			}
			.no-margin
			{
				margin: 0
			}
			h1
			{
				font-size: 18px
			}
			p
			{
				margin: 0
			}
			.dotted-bottom
			{
				border-bottom: 1px dotted #000
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
		<table class="table" align="center">
			<tr>
				<th width="100" class="border no-border-right">
					<img src="<?php echo get_image('settings', get_setting('reports_logo'), 'thumb'); ?>" alt="..." height="80" />
				</th>
				<th class="border no-border-left" align="center" width="100%" colspan="4">
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : '-'); ?>
					</h4>
					<h4>
						HASIL RESES DPRD PER PROGRAM
					</h4>
					<h4>
						TAHUN <?php echo get_userdata('year')?>
					</h4>
				</th>
			</tr>
		</table>
		<table class="table">
			<tr>
				<td class="border no-border-right no-border-bottom no-border-top no-margin" width="15%" colspan="2">
					DPRD
				</td>
				<td class="no-margin" width="3%" align="center">
					:
				</td>
				<td class="no-margin" width="10%">
					<?php 
						if($this->input->get('id_dprd') != 99)
						{
							echo $results['header']->kode_fraksi; ?>.<?php echo sprintf('%02d', $results['header']->kode_dprd);
						}
						else
						{
							echo "Semua Anggota"; 
						}
					?>
				</td>
				<td class="border no-border-left no-border-bottom no-border-top no-margin" width="72%">
					<?php 
						if($this->input->get('id_dprd') != 99)
						{
							echo $results['header']->nama_dewan;
						}
					?>
				</td>
			</tr>
			<tr>
				<td class="border no-border-right no-border-bottom no-border-top no-margin" colspan="2">
					Status
				</td>
				<td class="no-margin" align="center">
					:
				</td>
				<td class="border no-border-left no-border-bottom no-border-top no-margin" colspan="2">
					<?php echo $status_text; ?>
				</td>
			</tr>
		</table>
		<table class="table">
			<thead>
				<tr>
					<th class="border">
						KODE
					</th>
					<th class="border">
						PROGRAM / PEKERJAAN
					</th>
					<th class="border">
						ALAMAT
					</th>
					<th class="border">
						SKPD
					</th>
					
				<?php 
					if($this->input->get('id_dprd') == 99)
					{
				?>
					<th class="border">
						DPRD
					</th>
				<?php
					}
					
					if($status == 4)
					{
				?>
					<th class="border">
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
									<td class="border">
										<b>
											' . $val['kode_urusan'] . '
										</b>
									</td>
									<td class="border">
										<b>
											' . $val['nama_urusan'] . '
										</b>
									</td>
									<td class="border" align="center">
										<b>
											
										</b>
									</td>
									<td class="border" align="center">
										<b>
											
										</b>
									</td>
									
									';
								if($this->input->get('id_dprd') == 99)
								{
							echo'	<td class="border">
										
									</td>
									';
								}
								if($status == 4)
								{
							echo'	<td class="border">
									</td>
									';
								}
							echo '
								</tr>
							';
						}
						if($val['id_bidang'] != $id_bidang)
						{
							echo '
								<tr>
									<td class="border">
										<b>
											' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '
										</b>
									</td>
									<td class="border">
										<b>
											' . $val['nama_bidang'] . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											
										</b>										
									</td>
									<td class="border" align="right">
										<b>
											
										</b>										
									</td>
									
									';
								if($this->input->get('id_dprd') == 99)
								{
							echo'	<td class="border">
										
									</td>
									';
								}
								if($status == 4)
								{
							echo'	<td class="border">
									</td>
									';
								}
							echo '
								</tr>
							';
						}
						if($val['id_program'] != $id_program)
						{
							echo '
								<tr>
									<td class="border">
										<b>
											' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '.' . sprintf('%02d', $val['kode_program']) . '
										</b>
									</td>
									<td class="border">
										<b>
											' . $val['nama_program'] . '
										</b>
									</td>
									<td class="border" align="right">
										<b>
											
										</b>										
									</td>
									<td class="border" align="right">
										<b>
											
										</b>										
									</td>
									
									';
								if($this->input->get('id_dprd') == 99)
								{
							echo'	<td class="border">
										
									</td>
									';
								}
								if($status == 4)
								{
							echo'	<td class="border">
									</td>
									';
								}
							echo '
								</tr>
							';
						}
						echo '
							<tr>
								<td class="border">
									' . $val['kode_urusan'] . '.' . sprintf('%02d', $val['kode_bidang']) . '.' . sprintf('%02d', $val['kode_program']) . '.' . sprintf('%02d', $num) . '
								</td>
								<td class="border">
									' . $val['nama_pekerjaan'] . '
								</td>
								<td class="border">
									' . $val['map_address'] . ' - ' . $val['alamat_detail'] . '
								</td>
								<td class="border" align="center">
									' . ucwords(strtolower($val['nm_unit'])) . '
								</td>
								
								';
							if($this->input->get('id_dprd') == 99)
							{
						echo'	<td class="border">
									' . $val['nama_dewan'] . '
								</td>
								';
							}
							if($status == 4)
							{
						echo'	<td class="border">
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
						$id_program								= $val['id_program'];
					}
				?>
			</tbody>
			
		</table>
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="border no-border-right" width="60%">
				</td>
				<td class="border no-border-left" width="40%">
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo $tanggal_cetak; ?>
					<br />
						<b>
							<?php echo $results['header']->jabatan_dewan; ?>
						</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php echo $results['header']->nama_dewan; ?>
						</b>
					</u>
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