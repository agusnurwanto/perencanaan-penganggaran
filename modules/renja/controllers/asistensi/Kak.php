<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kak extends Aksara
{
	public function __construct()
	{
		parent::__construct();
		
		//$this->set_permission();
		$this->set_theme('backend');
		
		$this->_primary								= $this->input->get('kegiatan_sub');
		
		if('comment' == $this->input->post('do'))
		{
			return $this->_validate_form();
		}
	}
	
	public function index()
	{
		$this->set_title('Asistensi KAK')
		->set_icon('fa fa-car')
		->set_breadcrumb
		(
			array
			(
				'renja'								=> 'Renja',
				'asistensi'							=> 'Asistensi',
				'sub_unit'							=> 'Sub Unit',
				'../kegiatan'						=> 'Kegiatan',
				'../sub_kegiatan'					=> 'Sub Kegiatan'
			)
		)
		->set_output($this->_kak())
		->modal_size('modal-xl')
		->render();
	}
	
	private function _validate_form()
	{
		$data										= $this->input->post();
		
		$jenis										= 0;
		$sub_jenis									= 0;
		$sub_jenis_id								= 0;
		$sub_sub_jenis_id							= 0;
		$sub_sub_sub_jenis_id						= 0;
		$comments									= '';
		$uraian										= '';
		
		$query										= $this->model->select
		('
			dasar_hukum,
			gambaran_umum,
			penerima_manfaat,
			metode_pelaksanaan,
			tahapan_pelaksanaan,
			waktu_pelaksanaan,
			biaya
		')
		->get_where
		(
			'ta__kak',
			array
			(
				'id_keg_sub'						=> $this->_primary
			),
			1
		)
		->row();
		
		if($query)
		{
			$query->dasar_hukum						= json_decode($query->dasar_hukum, true);
			$query->gambaran_umum					= json_decode($query->gambaran_umum, true);
			$query->metode_pelaksanaan				= json_decode($query->metode_pelaksanaan, true);
			$query->tahapan_pelaksanaan				= json_decode($query->tahapan_pelaksanaan, true);
			$query->waktu_pelaksanaan				= json_decode($query->waktu_pelaksanaan, true);
			$query->biaya							= json_decode($query->biaya, true);
		}
		
		if(isset($data['dasar_hukum']) && is_array($data['dasar_hukum']) && sizeof($data['dasar_hukum']) > 0)
		{
			$jenis									= 1;
			
			foreach($data['dasar_hukum'] as $key => $val)
			{
				$sub_jenis							= $key;
				$comments							= $val;
				$uraian								= (isset($query->dasar_hukum[$key]) ? $query->dasar_hukum[$key] : '');
			}
		}
		
		

		elseif(isset($data['gambaran_umum']) && is_array($data['gambaran_umum']) && sizeof($data['gambaran_umum']) > 0)
		{
			$jenis									= 2;
			
			foreach($data['gambaran_umum'] as $key => $val)
			{
				$sub_jenis							= $key;
				
				if(is_array($val) && sizeof($val) > 0)
				{
					foreach($val as $_key => $_val)
					{
						$sub_jenis_id				= $_key;
						$comments					= $_val;
						$uraian						= (isset($query->gambaran_umum[$key][$_key]) ? $query->gambaran_umum[$key][$_key] : '');
					}
				}
				else
				{
					$sub_jenis						= $key;
					$comments						= $val;
					$uraian							= (isset($query->gambaran_umum[$key]) ? $query->gambaran_umum[$key] : '');
				}
			}
		}
		
		elseif(isset($data['penerima_manfaat']))
		{
			$jenis									= 3;
			$comments								= $data['penerima_manfaat'];
			$uraian									= (isset($query->penerima_manfaat) ? $query->penerima_manfaat : '');
		}
		elseif(isset($data['metode_pelaksanaan']) && is_array($data['metode_pelaksanaan']) && sizeof($data['metode_pelaksanaan']) > 0)
		{
			$jenis									= 4;
			
			foreach($data['metode_pelaksanaan'] as $key => $val)
			{
				$sub_jenis							= $key;
				
				if(is_array($val) && sizeof($val) > 0)
				{
					foreach($val as $_key => $_val)
					{
						$sub_jenis_id				= $_key;
						
						if(is_array($_val) && sizeof($_val) > 0)
						{
							foreach($_val as $__key => $__val)
							{
								$sub_sub_jenis_id	= $__key;
								
								if(is_array($__val) && sizeof($__val) > 0)
								{
									foreach($__val as $___key => $___val)
									{
										$sub_sub_sub_jenis_id	= $___key;
										$comments	= $___val;
										
										$uraian		= (isset($query->metode_pelaksanaan[$key][$_key][$__key]['label']) ? $query->metode_pelaksanaan[$key][$_key][$__key]['label'] : '');
										
										if($_key == 1)
										{
											$uraian	= 'Pegawai (' . $uraian . ')';
										}
										elseif($_key == 2)
										{
											$uraian	= 'Belanja Barang (' . $uraian . ')';
										}
										elseif($_key == 3)
										{
											$uraian	= 'Belanja Jasa Lainnya (' . $uraian . ')';
										}
										elseif($_key == 4)
										{
											$uraian	= 'Belanja Modal (' . $uraian . ')';
										}
										
										if($___key == 'isi' && isset($query->metode_pelaksanaan[$key][$_key][$__key][$___key]))
										{
											$uraian	= $uraian . ' - Isi (' . $query->metode_pelaksanaan[$key][$_key][$__key][$___key] . ')';
										}
										elseif($___key == 'volume' && isset($query->metode_pelaksanaan[$key][$_key][$__key][$___key]))
										{
											$uraian	= $uraian . ' - Volume (' . $query->metode_pelaksanaan[$key][$_key][$__key][$___key] . ')';
										}
										elseif($___key == 'satuan' && isset($query->metode_pelaksanaan[$key][$_key][$__key][$___key]))
										{
											$uraian	= $uraian . ' - Satuan (' . $query->metode_pelaksanaan[$key][$_key][$__key][$___key] . ')';
										}
									}
								}
								else
								{
									$comments		= $__val;
									$uraian			= (isset($query->metode_pelaksanaan[$key][$_key][$__key]) ? $query->metode_pelaksanaan[$key][$_key][$__key] : '');
								}
							}
						}
						else
						{
							$comments				= $_val;
							
							if($key == 'a' && isset($val[1]))
							{
								$uraian				= 'Kontraktual';
							}
							elseif($key == 'a' && isset($val[2]))
							{
								$uraian				= 'Swakelola';
							}
							elseif($key == 'a' && isset($val[3]))
							{
								$uraian				= 'Lainnya';
							}
							elseif($key == 'b' && isset($val[1]))
							{
								$uraian				= 'Pembangunan Fisik';
							}
							elseif($key == 'b' && isset($val[2]))
							{
								$uraian				= 'Sosialisasi / Bimtek / Workshop';
							}
							elseif($key == 'b' && isset($val[3]))
							{
								$uraian				= 'Pengujian Laboratorium';
							}
							elseif($key == 'b' && isset($val[4]))
							{
								$uraian				= 'Kajian / Analisa / Perencanaan';
							}
							elseif($key == 'b' && isset($val[5]))
							{
								$uraian				= 'Jaringan IT';
							}
							elseif($key == 'b' && isset($val[6]))
							{
								$uraian				= 'Pengawasan / Penertiban';
							}
							elseif($key == 'b' && isset($val[7]))
							{
								$uraian				= 'Pelayanan Kesehatan / Pendidikan / Kebersihan';
							}
							elseif($key == 'b' && isset($val[8]))
							{
								$uraian				= 'Lainnya';
							}
							else
							{
								$uraian				= (isset($query->metode_pelaksanaan[$key][$_key]) ? $query->metode_pelaksanaan[$key][$_key] : '');
							}
						}
					}
				}
				else
				{
					$comments						= $val;
					$uraian							= (isset($query->metode_pelaksanaan[$key]) ? $query->metode_pelaksanaan[$key] : '');
				}
			}
		}
		elseif(isset($data['tahapan_pelaksanaan']) && is_array($data['tahapan_pelaksanaan']) && sizeof($data['tahapan_pelaksanaan']) > 0)
		{
			$jenis									= 5;
			foreach($data['tahapan_pelaksanaan'] as $key => $val)
			{
				$sub_jenis							= $key;
				
				if(is_array($val) && sizeof($val) > 0)
				{
					$num							= 0;
					
					foreach($val as $_key => $_val)
					{
						$sub_jenis_id				= $_key;
						
						$uraian						= (isset($query->tahapan_pelaksanaan[$key]['label'][$num]) ? $query->tahapan_pelaksanaan[$key]['label'][$num] : null);
						
						if($key == 'persiapan')
						{
							$uraian					= 'Persiapan (' . $uraian . ')';
						}
						elseif($key == 'pelaksanaan')
						{
							$uraian					= 'Pelaksanaan (' . $uraian . ')';
						}
						elseif($key == 'pelaporan')
						{
							$uraian					= 'Pelaporan (' . $uraian . ')';
						}
						
						if($_key == 1 && isset($query->tahapan_pelaksanaan[$key][1][$num]))
						{
							$uraian					= $uraian . ' - Bulan kesatu';
						}
						elseif($_key == 2 && isset($query->tahapan_pelaksanaan[$key][2][$num]))
						{
							$uraian					= $uraian . ' - Bulan kedua';
						}
						elseif($_key == 3 && isset($query->tahapan_pelaksanaan[$key][3][$num]))
						{
							$uraian					= $uraian . ' - Bulan ketiga';
						}
						elseif($_key == 4 && isset($query->tahapan_pelaksanaan[$key][4][$num]))
						{
							$uraian					= $uraian . ' - Bulan keempat';
						}
						elseif($_key == 5 && isset($query->tahapan_pelaksanaan[$key][5][$num]))
						{
							$uraian					= $uraian . ' - Bulan kelima';
						}
						elseif($_key == 6 && isset($query->tahapan_pelaksanaan[$key][6][$num]))
						{
							$uraian					= $uraian . ' - Bulan keenam';
						}
						elseif($_key == 7 && isset($query->tahapan_pelaksanaan[$key][7][$num]))
						{
							$uraian					= $uraian . ' - Bulan ketujuh';
						}
						elseif($_key == 8 && isset($query->tahapan_pelaksanaan[$key][8][$num]))
						{
							$uraian					= $uraian . ' - Bulan kedelapan';
						}
						elseif($_key == 9 && isset($query->tahapan_pelaksanaan[$key][9][$num]))
						{
							$uraian					= $uraian . ' - Bulan kesembilan';
						}
						elseif($_key == 10 && isset($query->tahapan_pelaksanaan[$key][10][$num]))
						{
							$uraian					= $uraian . ' - Bulan kesepuluh';
						}
						elseif($_key == 11 && isset($query->tahapan_pelaksanaan[$key][11][$num]))
						{
							$uraian					= $uraian . ' - Bulan kesebelas';
						}
						elseif($_key == 12 && isset($query->tahapan_pelaksanaan[$key][12][$num]))
						{
							$uraian					= $uraian . ' - Bulan kedua belas';
						}
						elseif($_key == 'bobot' && isset($query->tahapan_pelaksanaan[$key]['bobot'][$num]))
						{
							$uraian					= $uraian . ' - Bobot (' . $query->tahapan_pelaksanaan[$key]['bobot'][$num] . ')';
						}
						
						if(is_array($_val) && sizeof($_val) > 0)
						{
							foreach($_val as $__key => $__val)
							{
								$sub_sub_jenis_id	= $__key;
								$comments			= $__val;
							}
						}
						else
						{
							$sub_sub_jenis_id		= $_key;
							$comments				= $_val;
						}
						
						$num++;
					}
				}
			}
		}
		elseif(isset($data['biaya']))
		{
			$jenis									= 6;
			
			foreach($data['biaya'] as $key => $val)
			{
				if(is_array($val))
				{
					foreach($val as $_key => $_val)
					{
						$sub_jenis					= $_key;
						$comments					= $_val;
					}
				}
				else
				{
					$sub_jenis						= $key;
					$comments						= $val;
				}
				
				$uraian								= 'Sumber Dana';
				
				if($key == 'b' && isset($val[1]))
				{
					$uraian							= $uraian . ' (PAD/DLL)';
				}
				elseif($key == 'b' && isset($val[2]))
				{
					$uraian							= $uraian . ' (Dana Alokasi Khusus)';
				}
				elseif($key == 'b' && isset($val[3]))
				{
					$uraian							= $uraian . ' (Dana Alokasi Umum)';
				}
				elseif($key == 'b' && isset($val[4]))
				{
					$uraian							= $uraian . ' (Dana Insentif Daerah)';
				}
				elseif($key == 'b' && isset($val[5]))
				{
					$uraian							= $uraian . ' (Bantuan Provinsi DKI Jakarta)';
				}
				elseif($key == 'b' && isset($val[6]))
				{
					$uraian							= $uraian . ' (Bantuan Provinsi Jawa Barat)';
				}
				elseif($key == 'b' && isset($val[7]))
				{
					$uraian							= $uraian . ' (DANA JKN)';
				}
				else
				{
					$uraian							= 'Perkiraan Anggaran (' . (isset($query->biaya[$key]) ? (is_numeric($query->biaya[$key]) ? number_format($query->biaya[$key]) : $query->biaya[$key]) : '') . ')';
				}
			}
		}
		elseif(isset($data['tolak_ukur']) && is_array($data['tolak_ukur']) && sizeof($data['tolak_ukur']) > 0)
		{
			$jenis									= 7;
			
			foreach($data['tolak_ukur'] as $key => $val)
			{
				$sub_jenis							= $key;
				$comments							= $val;
				$uraian								= (isset($query->tolak_ukur[$key]) ? $query->tolak_ukur[$key] : '');
			}
		}
		
		$prepare									= array
		(
			'id_keg_sub'							=> $this->_primary,
			'jenis'									=> $jenis,
			'sub_jenis'								=> $sub_jenis,
			'sub_jenis_id'							=> $sub_jenis_id,
			'sub_sub_jenis_id'						=> $sub_sub_jenis_id,
			'sub_sub_sub_jenis_id'					=> $sub_sub_sub_jenis_id,
			'comments'								=> $comments,
			'id_operator'							=> get_userdata('user_id'),
			'operator'								=> get_userdata('first_name') . ' ' . get_userdata('last_name'),
			'uraian'								=> $uraian,
			'tanggal'								=> date('Y-m-d H:i:s'),
			'tanggapan'								=> '',
			'tanggal_tanggapan'						=> '',
			'dibaca'								=> 0,
			'komentar_dibaca'						=> 0,
			'tanggapan_dibaca'						=> 0
		);
		
		$query										= $this->model->insert('ta__asistensi_kak', $prepare);
		
		if($query && $this->model->affected_rows() > 0)
		{
			return make_json
			(
				array
				(
					'status'						=> 200,
					'thread'						=> array
					(
						array
						(
							'comments'				=> $prepare['comments'],
							'id_operator'			=> $prepare['id_operator'],
							'operator'				=> $prepare['operator'],
							'tanggal'				=> $prepare['tanggal'],
							'tanggapan'				=> '',
							'tanggal_tanggapan'		=> ''
						)
					)
				)
			);
		}
		
		return make_json
		(
			array
			(
				'status'							=> 500
			)
		);
	}
	
	public function _get_comment($jenis = 0, $sub_jenis = 0, $sub_jenis_id = 0, $sub_sub_jenis_id = 0, $sub_sub_sub_jenis_id = 0)
	{
		if('dasar_hukum' == $jenis)
		{
			$jenis									= 1;
		}
	
		elseif('gambaran_umum' == $jenis)
		{
			$jenis									= 2;
		}
		elseif('penerima_manfaat' == $jenis)
		{
			$jenis									= 3;
		}
		elseif('metode_pelaksanaan' == $jenis)
		{
			$jenis									= 4;
		}
		elseif('tahapan_pelaksanaan' == $jenis)
		{
			$jenis									= 5;
		}
		elseif('tolak_ukur' == $jenis)
		{
			$jenis									= 6;
		}
		
		else
		{
			$jenis									= 7;
		}
		$query										= $this->model->select
		('
			comments,
			id_operator,
			operator,
			tanggal,
			tanggapan,
			tanggal_tanggapan
		')
		->get_where
		(
			'ta__asistensi_kak',
			array
			(
				'jenis'								=> $jenis,
				'sub_jenis'							=> $sub_jenis,
				'sub_jenis_id'						=> $sub_jenis_id,
				'sub_sub_jenis_id'					=> $sub_sub_jenis_id,
				'sub_sub_sub_jenis_id'				=> $sub_sub_sub_jenis_id,
				'id_keg_sub'						=> $this->_primary
			)
		)
		->result();
		
		return json_encode($query);
	}
	
	private function _kak()
	{
		$header										= $this->model->select
		('
			ta__kegiatan.kd_keg,
			ta__kegiatan.kegiatan,
			ta__kegiatan_sub.kegiatan_sub,
			ref__program.kd_program,
			ref__program.nm_program,
			ref__sub.kd_sub,
			ref__sub.nm_sub
		')
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id = ta__kegiatan_sub.id_keg'
		)
		->join
		(
			'ta__program',
			'ta__program.id = ta__kegiatan.id_prog'
		)
		->join
		(
			'ref__program',
			'ref__program.id = ta__program.id_prog'
		)
		->join
		(
			'ref__sub',
			'ref__sub.id = ta__program.id_sub'
		)
		->get_where
		(
			'ta__kegiatan_sub',
			array
			(
				'ta__kegiatan_sub.id'				=> $this->_primary
			),
			1
		)
		->row();
		
		$capaian_program							= $this->model->select
		('
			ta__program_capaian.kode,
			ta__program_capaian.tolak_ukur,
			ta__program_capaian.tahun_2_target,
			ta__program_capaian.tahun_2_satuan
		')
		->join
		(
			'ta__program',
			'ta__program.id = ta__program_capaian.id_prog'
		)
		->join
		(
			'ta__kegiatan',
			'ta__kegiatan.id_prog = ta__program.id'
		)
		->get_where
		(
			'ta__program_capaian',
			array
			(
				'ta__kegiatan.id'					=> $this->_primary
			)
		)
		->result();
		
		$indikator									= $this->model->select
		('
			ta__indikator.kd_indikator,
			ta__indikator.jns_indikator,
			ta__indikator.tolak_ukur,
			ta__indikator.target,
			ta__indikator.satuan
		')
		->get_where
		(
			'ta__indikator',
			array
			(
				'ta__indikator.id_keg'				=> $this->_primary
				
			)
		)
		->result();
		
		$kak										= $this->model->get_where
		(
			'ta__kak',
			array
			(
				'id_keg_sub'						=> $this->_primary
			),
			1
		)
		->row();
		
		$verified									= $this->model->get_where
		(
			'ta__asistensi_kak_setuju',
			array
			(
				'id_keg_sub'						=> $this->_primary
			),
			1
		)
		->row();
		
		//print_r($indikator);exit;
		return array
		(
			'header'								=> $header,
			'capaian_program'						=> $capaian_program,
			'indikator'								=> $indikator,
			'kak'									=> $kak,
			'verified'								=> $verified
		);
	}
}
