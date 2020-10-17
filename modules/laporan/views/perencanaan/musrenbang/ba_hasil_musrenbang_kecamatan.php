<!DOCTYPE html>
<html>
	<head>
		<title>
			Berita Acara Musrenbang Kecamatan
		</title>
		<link rel="icon" type="image/x-icon" href="<?php echo get_image('settings', get_setting('app_icon'), 'icon'); ?>" />
		
		<style type="text/css">
			@page
			{
				footer: html_footer; /* !!! apply only when the htmlpagefooter is sets !!! */
				sheet-size: 8.5in 13in;
				margin: 50, 80, 50, 80
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
						BERITA ACARA
					</h4>
					<h4>
						HASIL MUSYAWARAH PERENCANAAN PEMBANGUNAN
					</h4>
					<h4>
						KECAMATAN <?php echo (isset($results['header']->nama_kecamatan) ? strtoupper($results['header']->nama_kecamatan) : null); ?> TAHUN <?php echo get_userdata('year'); ?>
					</h4>
					<h4>
						<?php echo (isset($nama_pemda) ? strtoupper($nama_pemda) : "") ; ?>
					</h4>
				</td>
			</tr>
		</table>
		<div class="separator"></div>
		<div class="text-justify">
			<p>
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Pada hari ini, <?php echo phrase(date("l",strtotime($this->input->get('tanggal_cetak')))); ?> Tanggal <?php echo spell_number(date("j",strtotime($this->input->get('tanggal_cetak')))); ?> Bulan <?php echo phrase(date("F",strtotime($this->input->get('tanggal_cetak')))); ?> Tahun <?php echo spell_number(date("Y",strtotime($this->input->get('tanggal_cetak')))); ?> telah dilaksanakan Musyawarah Perencanaan Pembangunan (Musrenbang) Kecamatan <?php echo (isset($results['header']->nama_kecamatan) ? $results['header']->nama_kecamatan : null); ?> bertempat di kecamatan <?php echo (isset($results['header']->nama_kecamatan) ? $results['header']->nama_kecamatan : null); ?> dan berlangsung sesuai dengan acara pada Lampiran I.
			</p>
			<p>			
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Musrenbang Kecamatan <?php echo (isset($results['header']->nama_kecamatan) ? $results['header']->nama_kecamatan : null); ?> tersebut dipimpin oleh <?php echo (isset($results['header']->camat) ? $results['header']->camat : null); ?>, serta dihadiri oleh ........ orang peserta, sebagaimana daftar hadir tersebut pada Lampiran II, yang terdiri dari atas unsur-unsur sebagai berikut :
			</p>
			<table border="0" cellpadding="0">
				<tr>
					<td width="5%">
						a.
					</td>
					<td width="65%">
						Anggota DPRD
					</td>
					<td width="5%">
						:
					</td>
					<td width="10%">
						...........
					</td>
					<td width="15%">
						Orang
					</td>
				</tr>
				<tr>
					<td>
						b.
					</td>
					<td>
						Unsur PERANGKAT DAERAH/UPTD
					</td>
					<td>
						:
					</td>
					<td>
						...........
					</td>
					<td>
						Orang
					</td>
				</tr>
				<tr>
					<td>
						c.
					</td>
					<td>
						Unsur Perangkat Kecamatan
					</td>
					<td>
						:
					</td>
					<td>
						...........
					</td>
					<td>
						Orang
					</td>
				</tr>
				<tr>
					<td>
						d.
					</td>
					<td>
						Delegasi Rukun Tetangga (RT)/Rukun Warga (RW)
					</td>
					<td>
						:
					</td>
					<td>
						...........
					</td>
					<td>
						Orang
					</td>
				</tr>
				<tr>
					<td>
						e.
					</td>
					<td>
						Wakil dari kelompok-kelompok masyarakat yang lingkup kegiatannya dalam skala Kecamatan, meliputi:
					</td>
					<td>
						
					</td>
					<td>
						
					</td>
					<td>
						
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
					<td>
						1) LPM/BKM
					</td>
					<td>
						:
					</td>
					<td>
						...........
					</td>
					<td>
						Orang
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
					<td>
						2) PKK
					</td>
					<td>
						:
					</td>
					<td>
						...........
					</td>
					<td>
						Orang
					</td>
				</tr>
				<tr>
					<td>
						
					</td>
					<td>
						3) Organisasi/Kelompok/Tokoh Masyarakat
					</td>
					<td>
						:
					</td>
					<td>
						...........
					</td>
					<td>
						Orang
					</td>
				</tr>
			</table>
			<p>
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Setelah memperhatikan dan mengkaji bahan Musrenbang serta paparan dari Narasumber dengan notulen (catatan) sebagaimana tersebut dalam Lampiran III, khususnya tentang :
			</p>
			<table border="0" cellpadding="0">
				<tr>
					<td width="5%">
						a.
					</td>
					<td width="95%">
						Evaluasi pembangunan tahun sebelumnya dan prioritas kegiatan tahun yang akan datang;
					</td>
				</tr>
				<tr>
					<td>
						b.
					</td>
					<td>
						Rancangan prioritas kegiatan di kecamatan serta perkiraan jumlah alokasi dana kecamatan;
					</td>
				</tr>
				<tr>
					<td>
						c.
					</td>
					<td>
						Daftar prioritas kegiatan pembangunan masing-masing RT dan RW yang dihasilkan dari Musyawarah Perencanaan Pembangunan Tingkat Kelurahan.
					</td>
				</tr>
			</table>
			<p>
			Telah disepakati beberapa hal sebagai berikut :
			</p>
			<table border="0" cellpadding="0">
				<tr>
					<td width="5%">
						a.
					</td>
					<td width="95%">
						Daftar penyelesaian masalah serta usulan kegiatan kecamatan yang akan dibiayai oleh APBD Kota, APBD Provinsi, APBN dan sumber dana lainnya (Lampiran IV);
					</td>
				</tr>
				<tr>
					<td>
						b.
					</td>
					<td>
						Daftar usulan kegiatan yang belum dapat diakomodir dalam rancangan RKPD Kota Bekasi Tahun 2019 beserta alasannya (Lampiran V);
					</td>
				</tr>
				<tr>
					<td>
						c.
					</td>
					<td>
						Mendelegasikan dan memberikan mandat kepada 3 (tiga) orang anggota masyarakat untuk memperjuangkan usulan kegiatan pembangunan pada Musrenbang SKPD dengan Surat Mandat sebagaimana terlampir (Lampiran VI).
					</td>
				</tr>
			</table>
			<p>
			&nbsp; &nbsp; &nbsp; &nbsp; &nbsp; &nbsp; Demikian berita acara ini kami buat sesuai dengan keadaan yang sesungguhnya, untuk diketahui dan dipergunakan oleh semua pihak yang terkait.
			<p/>
		</div>
		<br />
		<table class="table" style="page-break-inside:avoid">
			<tr>
				<td class="text-center" width="50%">
					Mengetahui,
					<br />
					<b>
						<?php echo (isset($results['header']->jabatan_camat) ? $results['header']->jabatan_camat : null); ?>
						<br />
						Selaku Penanggungjawab Musrenbang Kecamatan,
					</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							<?php echo (isset($results['header']->camat) ? $results['header']->camat : null); ?>
						</b>
					</u>
					<br />
					NIP. <?php echo (isset($results['header']->nip_camat) ? $results['header']->nip_camat : null); ?>
				</td>
				<td class="text-center" width="50%">
					<?php echo (isset($nama_daerah) ? $nama_daerah : "") ; ?>, <?php echo $tanggal_cetak; ?>
					<br />
						<b>
							KETUA TIM PENYELENGGARA MUSRENBANG KECAMATAN <?php echo (isset($results['header']->nama_kecamatan) ? $results['header']->nama_kecamatan : null); ?>
						</b>
					<br />
					<br />
					<br />
					<br />
					<br />
					<br />
					<u>
						<b>
							..........................
						</b>
					</u>
					<br />
					
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
					<td class="text-muted text-sm text-right">
						<?php //echo phrase('page') . ' {PAGENO} ' . phrase('of') . ' {nb}'; ?>
					</td>
				</tr>
			</table>
		</htmlpagefooter>
	</body>
</html>