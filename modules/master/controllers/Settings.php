<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Settings extends Aksara
{
	private $_table									= 'ref__settings';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		$this->insert_on_update_fail();
	}
	
	public function index()
	{
		$this->set_breadcrumb
		(
			array
			(
				'master'							=> phrase('master')
			)
		)
		->set_method('update')
		->set_title(phrase('settings'))
		->set_icon('fa fa-toggle-on')
		->unset_column('tahun')
		->unset_field('tahun')
		->unset_view('tahun')
		->set_field
		(
			'anggaran_kunci_plafon',
			'radio',
			array
			(
				0									=> '<label class="badge badge-warning" data-toggle="tooltip" title="Jika dipilih Tidak maka Anggaran Belanja Bisa Melebihi Plafon Sub Kegiatan">Tidak</label>',
				1									=> '<label class="badge badge-success" data-toggle="tooltip" title="Jika dipilih Kunci maka Anggaran Belanja Tidak Bisa Melebihi Plafon Sub Kegiatan">Kunci</label>'
			)
		)
		->set_field
		(
			'anggaran_standar_harga',
			'radio',
			array
			(
				0									=> '<label class="badge badge-warning" data-toggle="tooltip" title="Jika dipilih Tidak maka Anggaran Tidak ada Validasi dari Standar Harga">Tidak</label>',
				1									=> '<label class="badge badge-success" data-toggle="tooltip" title="Jika dipilih Gunakan maka Anggaran harus berdasarkan Standar Harga">Gunakan</label>'
			)
		)
		->set_field
		(
			'anggaran_kunci_satuan',
			'radio',
			array
			(
				0									=> '<label class="badge badge-warning" data-toggle="tooltip" title="Jika dipilih Tidak maka field Satuan bisa diisi bebas">Tidak</label>',
				1									=> '<label class="badge badge-success" data-toggle="tooltip" title="Jika dipilih Kunci maka field Satuan akan readonly dan hanya terisi dari satuan yang ada di standar harga">Kunci</label>'
			)
		)
		->set_field
		(
			'anggaran_kunci_standar_ke_rekening',
			'radio',
			array
			(
				0									=> '<label class="badge badge-warning" data-toggle="tooltip" title="Jika dipilih Tidak maka tidak ada proses cek standar harga sesuai dengan rekeningnya">Tidak</label>',
				1									=> '<label class="badge badge-success" data-toggle="tooltip" title="Jika dipilih Kunci maka ada proses cek mapping standar harga ke rekening dan ada pilihan untuk di pindahkan ke rekening yang seharusnya">Kunci</label>'
			)
		)
		->set_field
		(
			'bank_indikator',
			'radio',
			array
			(
				0									=> '<label class="badge badge-warning" data-toggle="tooltip" title="Jika dipilih Tidak maka Indikator Kegiatan dan Sub Kegiatan Mengisi Manual">Tidak</label>',
				1									=> '<label class="badge badge-success" data-toggle="tooltip" title="Jika dipilih Gunakan maka Indikator Kegiatan dan Sub Kegiatan memilih dari Bank Indikator">Gunakan</label>'
			)
		)
		->field_position
		(
			array
			(
				'jabatan_kepala_daerah'				=> 1,
				'nama_kepala_daerah'				=> 1,
				'jabatan_sekretaris_daerah'			=> 1,
				'nama_sekretaris_daerah'			=> 1,
				'nip_sekretaris_daerah'				=> 1,
				'jabatan_kepala_perencanaan'		=> 2,
				'nama_kepala_perencanaan'			=> 2,
				'nip_kepala_perencanaan'			=> 2,
				'jabatan_kepala_keuangan'			=> 3,
				'nama_kepala_keuangan'				=> 3,
				'nip_kepala_keuangan'				=> 3,
				'anggaran_kunci_plafon'				=> 4,
				'anggaran_standar_harga'			=> 4,
				'anggaran_kunci_satuan'				=> 4,
				'anggaran_kunci_standar_ke_rekening'	=> 4
			)
		)
		->set_validation
		(
			array
			(
				'server_api_sipd'					=> 'valid_url'
			)
		)
		->set_default('tahun', get_userdata('year'))
		->where('tahun', get_userdata('year'))
		->render($this->_table);
	}
}