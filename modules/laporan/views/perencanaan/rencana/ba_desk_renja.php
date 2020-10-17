<!DOCTYPE html>
<html>
	<head>
		<title>
			Berita Acara Desk Renja
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		
		<style type="text/css">
			@page
			{
				footer: html_footer; /* !!! apply only when the htmlpagefooter is sets !!! */
				sheet-size: 8.5in 13in;
				margin: 50, 70, 50, 70
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
				font-weight: bold;
				font-size: 12px;
				padding: 6px;
			}
			td
			{
				vertical-align: top;
				font-size: 12px;
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
			p
			{
				margin: 0
			}
			.dotted-bottom
			{
				border-bottom: 1px dotted #000
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
						BERITA ACARA
					</h4>
					<h4>
						Kesepakatan Hasil Desk Renja Perangkat Daerah Lingkup
						<br />
						<?php echo (isset($results['header']->nama_bidang) ? $results['header']->nama_bidang : ''); ?>
						<br />
						Tahun <?php echo get_userdata('year')?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="divider"></div>
		<br />
		<div class="text-justify">
			<p>
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Pada hari ini, 
			<?php echo phrase(date("l",strtotime($this->input->get('tanggal_cetak')))); ?> Tanggal 
			<?php echo spell_number(date("j",strtotime($this->input->get('tanggal_cetak')))); ?> Bulan 
			<?php echo phrase(date("F",strtotime($this->input->get('tanggal_cetak')))); ?> Tahun 
			<?php echo spell_number(date("Y",strtotime($this->input->get('tanggal_cetak')))); ?> telah diselenggarakan Desk Renja Perangkat Daerah Lingkup Bidang Pembangunan Manusia dan Masyarakat di Kota <?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?> dengan 
			<?php echo (isset($results['header']->nm_unit) ? ucwords(strtolower($results['header']->nm_unit)) : ''); ?> yang dihadiri pemangku kepentingan  sesuai dengan daftar hadir sebagaimana tercantum dalam LAMPIRAN I berita acara ini.
			</p>
			<p>			
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp;  Setelah memperhatikan, mendengar dan mempertimbangkan :
			</p>
			<table border="0" cellpadding="0">
				<tr>
					<td width="5%">
						1.
					</td>
					<td width="95%" class="text-justify">
						Pemaparan materi (disesuaikan dengan materi dan nama pejabat yang menyampaikan)
					</td>
				</tr>
				<tr>
					<td>
						2.
					</td>
					<td class="text-justify">
						Tanggapan dan saran dari seluruh peserta Desk Renja Perangkat Daerah Lingkup Bidang Pembangunan Manusia dan Masyarakat terhadap materi yang dipaparkan oleh Perangkat Daerah yang bersangkutan sebagaimana telah disepakati sebagai berikut :
					</td>
				</tr>
			</table>
			<br />
			<p>
			MENYEPAKATI
			</p>
			<table class="table">
				<tr>
					<td width="10%">
						KESATU
					</td>
					<td width="3%">
						:
					</td>
					<td width="87%" class="text-justify">
						Menyepakati program dan kegiatan prioritas, dan indikator kinerja yang disertai target dan kebutuhan pendanaan, yang telah diselaraskan dengan usulan kegiatan prioritas dari hasil forum Perangkat Daerah Kota <?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>.
					</td>
				</tr>
				<tr>
					<td>
						KEDUA
					</td>
					<td>
						:
					</td>
					<td class="text-justify">
						Menyepakati rancangan Rencana Kerja Awal <?php echo (isset($results['header']->nm_unit) ? ucwords(strtolower($results['header']->nm_unit)) : ''); ?> Kota <?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?> Tahun <?php echo get_userdata('year')?> sebagaimana tercantum dalam LAMPIRAN II berita acara ini;
					</td>
				</tr>
				<tr>
					<td>
						KETIGA
					</td>
					<td>
						:
					</td>
					<td class="text-justify">
						Berita acara ini beserta lampirannya dijadikan sebagai bahan penyempurnaan rancangan RKPD Kota <?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?> Tahun <?php echo get_userdata('year')?>
					</td>
				</tr>
			</table>
			<p>
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Demikian berita acara ini dibuat dan disahkan untuk digunakan sebagaimana mestinya.
			<p/>
		</div>
		<br />
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td width="60%">
					
					<br />
					<b>
						<?php //echo strtoupper($header['jabatan_camat']); ?>
						<br />
					</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					
						<b>
							<?php //echo $header['camat']; ?>
						</b>
					
					<br />
					<?php //echo $header['nip_camat']; ?>
				</td>
				<td width="40%">
					<?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>, <?php echo $tanggal_cetak; ?>
					<br />
						<b>
							Pimpinan Sidang,
							<br />
							<?php echo $results['header']->jabatan_kepala; ?>
						</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php echo $results['header']->nama_kepala; ?>
						</b>
					</u>
					<br />
					NIP <?php echo $results['header']->nip_kepala; ?>
				</td>
			</tr>
		</table>
		<br />
		<pagebreak>
		<br />
		<br />
		<h4>
			LAMPIRAN I: DAFTAR HADIR
		</h4>
		<br />
		Menyetujui,
		<br />
		Peserta Desk Renja Perangkat Daerah Lingkup <?php echo (isset($results['header']->nama_bidang) ? $results['header']->nama_bidang : ''); ?> <?php echo (isset($nama_daerah) ? $nama_daerah : '-') ;?>
		<br />
		<br />
		<table class="table">
			<thead>
				<tr>
					<th class="border" width="5%">
						NO
					</th>
					<th class="border" width="25%">
						NAMA
					</th>
					<th class="border" width="25%">
						UNSUR
						<br />
						PERWAKILAN
					</th>
					<th class="border" width="25%">
						JABATAN
					</th>
					<th class="border" width="20%">
						TANDA
						<br />
						TANGAN
					</th>
				</tr>
			</thead>
			<tbody>
				<tr>
					<td class="border text-center">
						1
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
				</tr>
				<tr>
					<td class="border text-center">
						2
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
				</tr>
				<tr>
					<td class="border text-center">
						3
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
				</tr>
				<tr>
					<td class="border text-center">
						4
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
				</tr>
				<tr>
					<td class="border text-center">
						5
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
				</tr>
				<tr>
					<td class="border text-center">
						6
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
					<td class="border">
						
					</td>
				</tr>
			</tbody>
		</table>
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