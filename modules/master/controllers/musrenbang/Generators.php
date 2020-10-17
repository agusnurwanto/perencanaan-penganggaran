<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Generators extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_theme('backend')
		->set_permission();
		if(1 != get_userdata('group_id'))
		{
			generateMessages(403, 'Anda tidak diizinkan untuk mengakses halaman yang diminta.');
		}
	}
	
	public function index()
	{
		$this->set_title('Generator Massal')
		->set_icon('fa fa-refresh')
		->render();
	}
	
	public function update_nilai()
	{
		$query											= $this->model
		->select
		('
			ta__musrenbang.id,
			ta__musrenbang.variabel_usulan,
			ta__musrenbang.variabel_kelurahan,
			ta__musrenbang.variabel_kecamatan,
			ta__musrenbang.variabel_skpd,
			ref__musrenbang_jenis_pekerjaan.nilai_satuan
		')
		->join('ref__musrenbang_jenis_pekerjaan', 'ref__musrenbang_jenis_pekerjaan.id = ta__musrenbang.jenis_pekerjaan', 'INNER')
		->get('ta__musrenbang')->result();
		$message										= 'Nilai berhasi diubah: ';
		$message_usulan									= null;
		$message_kelurahan								= null;
		$message_kecamatan								= null;
		$message_skpd									= null;
		foreach($query as $key => $val)
		{
			$variabel_usulan							= json_decode($val->variabel_usulan);
			$variabel_kelurahan							= json_decode($val->variabel_kelurahan);
			$variabel_kecamatan							= json_decode($val->variabel_kecamatan);
			$variabel_skpd								= json_decode($val->variabel_skpd);
			if(is_object($variabel_usulan) && sizeof($variabel_usulan) > 0)
			{
				$nilai_usulan							= 1;
				foreach($variabel_usulan as $key_usulan => $val_usulan)
				{
					$nilai_usulan						*= $val_usulan;
				}
				if($this->model->update('ta__musrenbang', array('nilai_usulan' => $val->nilai_satuan * $nilai_usulan), array('id' => $val->id)))
				{
					if(!$message_usulan)
					{
						$message_usulan					= ' <b class="text-info">Nilai Usulan</b> ';
					}
				}
			}
			if(is_object($variabel_kelurahan) && sizeof($variabel_kelurahan) > 0)
			{
				$nilai_kelurahan						= 1;
				foreach($variabel_kelurahan as $key_kelurahan => $val_kelurahan)
				{
					$nilai_kelurahan					*= $val_kelurahan;
				}
				if($this->model->update('ta__musrenbang', array('nilai_kelurahan' => $val->nilai_satuan * $nilai_kelurahan), array('id' => $val->id)))
				{
					if(!$message_kelurahan)
					{
						$message_kelurahan				= ' <b class="text-primary">Nilai Kelurahan</b> ';
					}
				}
			}
			if(is_object($variabel_kecamatan) && sizeof($variabel_kecamatan) > 0)
			{
				$nilai_kecamatan						= 1;
				foreach($variabel_kecamatan as $key_kecamatan => $val_kecamatan)
				{
					$nilai_kecamatan					*= $val_kecamatan;
				}
				if($this->model->update('ta__musrenbang', array('nilai_kecamatan' => $val->nilai_satuan * $nilai_kecamatan), array('id' => $val->id)))
				{
					if(!$message_kecamatan)
					{
						$message_kecamatan				= ' <b class="text-success">Nilai Kecamatan</b> ';
					}
				}
			}
			if(is_object($variabel_skpd) && sizeof($variabel_skpd) > 0)
			{
				$nilai_skpd								= 1;
				foreach($variabel_skpd as $key_skpd => $val_skpd)
				{
					$nilai_skpd							*= $val_skpd;
				}
				if($this->model->update('ta__musrenbang', array('nilai_skpd' => $val->nilai_satuan * $nilai_skpd), array('id' => $val->id)))
				{
					if(!$message_skpd)
					{
						$message_skpd					= ' <b class="text-danger">Nilai SKPD</b> ';
					}
				}
			}
		}
		generateMessages(301, $message . $message_usulan . $message_kelurahan . $message_kecamatan . $message_skpd);
	}
}