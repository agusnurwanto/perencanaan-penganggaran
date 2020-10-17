<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * Integrasi > SIPD > Pengaturan
 *
 * @version			1.0.0
 * @author			Aby Dahana
 * @profile			abydahana.github.io
 */

class Massal extends Aksara
{
	private $_cookie_file							= null;
	private $_sipd_hostname							= null;
	private $_sipd_username							= null;
	private $_sipd_password							= null;
	private $_id_daerah								= 0;
	
	public function __construct()
	{
		parent::__construct();
		
		$this->set_theme('backend');
		
		/* set cookie file (must be rewritable) */
		$this->_cookie_file							= '/tmp/sipd-cookie-' . time();
		
		$pengaturan_sipd							= $this->model->get_where
		(
			'ref__pengaturan_sipd',
			array
			(
				'id'								=> 1
			),
			1
		)
		->row();
		
		if(!$pengaturan_sipd)
		{
			return throw_exception(404, 'Silakan lakukan pengaturan SIPD terlebih dahulu...', curent_page('../pengaturan'));
		}
		
		$this->_sipd_hostname						= 'https://' . str_replace(array('https://', 'http://'), array(null, null), $pengaturan_sipd->hostname);
		$this->_sipd_logout_url						= parse_url($pengaturan_sipd->logout_url, PHP_URL_QUERY);
		$this->_sipd_logout_url						= ($this->_sipd_logout_url ? $this->_sipd_logout_url : 'idu=MjU=');
		$this->_sipd_username						= $this->encryption->decrypt($pengaturan_sipd->username);
		$this->_sipd_password						= $this->encryption->decrypt($pengaturan_sipd->password);
		$this->_id_sub								= ($this->input->post('sub_unit') ? $this->input->post('sub_unit') : 0);
		$this->_year								= get_userdata('year');
	}
	
	public function index()
	{
		$this->set_title('Integrasi SIPD')
		->set_icon('mdi mdi-refresh')
		
		->set_output
		(
			array
			(
				'sub_unit'							=> $this->_sub_unit()
			)
		)
		
		->render();
	}
	
	public function referensi_kegiatan()
	{
		$success									= false;
		
		/* session initialization */
		$this->_initialize_session();
		
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> false,
				CURLOPT_RETURNTRANSFER				=> true,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah/main/budget/subgiat/' . $this->_year . '/tampil-sub-giat/' . $this->_id_daerah . '/' . $this->_id_sub,
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* simulate the request through AJAX */
				CURLOPT_HTTPHEADER					=> array
				(
					'X-Requested-With: XMLHttpRequest'
				),
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= json_decode(curl_exec($curl));
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		if(isset($output->data) && sizeof($output->data) > 0)
		{
			/* define variable to store existing data */
			$urusan									= array();
			$bidang									= array();
			$program								= array();
			$kegiatan								= array();
			$kegiatan_sub							= array();
			
			/* prepare insert data */
			$prepare_urusan							= array();
			$prepare_bidang							= array();
			$prepare_program						= array();
			$prepare_kegiatan						= array();
			$prepare_kegiatan_sub					= array();
			
			foreach($output->data as $key => $val)
			{
				/* check if key already exist, push to prepared statement if not yet exist */
				if(!in_array($val->id_urusan, $urusan))
				{
					$label							= trim(strstr($val->nama_urusan, ' '));
					
					$prepare_urusan[]				= array
					(
						'id'						=> $val->id_urusan,
						'kd_urusan'					=> $val->kode_urusan,
						'nm_urusan'					=> ($label ? $label : 'Urusan'),
						'tahun'						=> $this->_year
					);
					$urusan[]						= $val->id_urusan;
				}
				
				/* check if key already exist, push to prepared statement if not yet exist */
				if(!in_array($val->id_bidang_urusan, $bidang))
				{
					$label							= trim(strstr($val->nama_bidang_urusan, ' '));
					
					$prepare_bidang[]				= array
					(
						'id'						=> $val->id_bidang_urusan,
						'id_urusan'					=> $val->id_urusan,
						'kd_bidang'					=> substr($val->kode_bidang_urusan, strrpos($val->kode_bidang_urusan, '.') + 1),
						'nm_bidang'					=> ($label ? $label : 'Bidang'),
						'tahun'						=> $this->_year
					);
					$bidang[]						= $val->id_bidang_urusan;
				}
				
				/* check if key already exist, push to prepared statement if not yet exist */
				if(!in_array($val->id_program, $program))
				{
					$label							= trim(strstr($val->nama_program, ' '));
					
					$prepare_program[]				= array
					(
						'id'						=> $val->id_program,
						'id_bidang'					=> $val->id_bidang_urusan,
						'kd_program'				=> substr($val->kode_program, strrpos($val->kode_program, '.') + 1),
						'nm_program'				=> ($label ? $label : 'Program'),
						'tahun'						=> $this->_year
					);
					$program[]						= $val->id_program;
				}
				
				/* check if key already exist, push to prepared statement if not yet exist */
				if(!in_array($val->id_giat, $kegiatan))
				{
					$label							= trim(strstr($val->nama_giat, ' '));
					
					$prepare_kegiatan[]				= array
					(
						'id'						=> $val->id_giat,
						'id_program'				=> $val->id_program,
						'kd_kegiatan'				=> str_replace('.', null, substr($val->kode_giat, strrpos($val->kode_giat, '.') - 1)),
						'nm_kegiatan'				=> ($label ? $label : 'Kegiatan'),
						'tahun'						=> $this->_year
					);
					$kegiatan[]						= $val->id_giat;
				}
				
				/* check if key already exist, push to prepared statement if not yet exist */
				if(!in_array($val->id_sub_giat, $kegiatan_sub))
				{
					$label							= trim(strstr($val->nama_sub_giat, ' '));
					
					$prepare_kegiatan_sub[]			= array
					(
						'id'						=> $val->id_sub_giat,
						'id_kegiatan'				=> $val->id_giat,
						'kd_kegiatan_sub'			=> substr($val->kode_sub_giat, strrpos($val->kode_sub_giat, '.') + 1),
						'nm_kegiatan_sub'			=> ($label ? $label : 'Sub Kegiatan'),
						'tahun'						=> $this->_year
					);
					$kegiatan_sub[]					= $val->id_sub_giat;
				}
			}
			
			/* truncate database table from children to parent */
			$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
			
			$this->model->truncate('sipd__ref__kegiatan_sub');
			$this->model->truncate('sipd__ref__kegiatan');
			$this->model->truncate('sipd__ref__program');
			$this->model->truncate('sipd__ref__sub');
			$this->model->truncate('sipd__ref__unit');
			$this->model->truncate('sipd__ref__bidang');
			$this->model->truncate('sipd__ref__urusan');
			
			$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
			/* end truncate */
			
			$this->model->insert_batch('sipd__ref__urusan', $prepare_urusan, sizeof($prepare_urusan));
			$this->model->insert_batch('sipd__ref__bidang', $prepare_bidang, sizeof($prepare_bidang));
			$this->model->insert_batch('sipd__ref__program', $prepare_program, sizeof($prepare_program));
			$this->model->insert_batch('sipd__ref__kegiatan', $prepare_kegiatan, sizeof($prepare_kegiatan));
			$this->model->insert_batch('sipd__ref__kegiatan_sub', $prepare_kegiatan_sub, sizeof($prepare_kegiatan_sub));
			
			$success								= true;
			$html									= '
				<div class="alert alert-success">
					Integrasi dengan data SIPD berhasil dijalankan
				</div>
				<br />
				<ul>
					<li>
						Data Urusan: <b>' . number_format(sizeof($prepare_urusan)) . '</b> data disimpan;
					</li>
					<li>
						Data Bidang: <b>' . number_format(sizeof($prepare_bidang)) . '</b> data disimpan;
					</li>
					<li>
						Data Program: <b>' . number_format(sizeof($prepare_program)) . '</b> data disimpan;
					</li>
					<li>
						Data Kegiatan: <b>' . number_format(sizeof($prepare_kegiatan)) . '</b> data disimpan;
					</li>
					<li>
						Data Sub Kegiatan: <b>' . number_format(sizeof($prepare_kegiatan_sub)) . '</b> data disimpan.
					</li>
				</ul>
			';
		}
		else
		{
			$html									= '
				<div class="alert alert-warning">
					Tidak dapat mengambil data dari SIPD disebabkan oleh beberapa faktor berikut:
				</div>
				<ul>
					<li>
						Kesalahan dalam memilih data;
					</li>
					<li>
						Gagal mengakses server SIPD.
					</li>
				</ul>
			';
		}
		
		return make_json
		(
			array
			(
				'status'							=> 206,
				'exception'							=> array
				(
					'size'							=> 720,
					'title'							=> ($success ? 'Integrasi Referensi berhasil!' : 'Integrasi Referensi gagal!'),
					'icon'							=> ($success ? 'mdi mdi-check' : 'mdi mdi-alert'),
					'html'							=> $html
				)
			)
		);
	}
	
	public function unit()
	{
		$success									= false;
		
		/* session initialization */
		$this->_initialize_session();
		
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> false,
				CURLOPT_RETURNTRANSFER				=> true,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah/main/plan/skpd/' . $this->_year . '/tampil-skpd/' . $this->_id_daerah . '/' . $this->_id_sub,
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* simulate the request through AJAX */
				CURLOPT_HTTPHEADER					=> array
				(
					'X-Requested-With: XMLHttpRequest'
				),
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= json_decode(curl_exec($curl));
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		if(isset($output->data) && sizeof($output->data) > 0)
		{
			/* prepare insert data */
			$prepare_unit							= array();
			$prepare_sub_unit						= array();
			
			foreach($output->data as $key => $val)
			{
				/* destructure merged data */
				$kode								= explode('.', $val->kode_skpd);
				
				if(!isset($kode[7]) || empty($kode[7]))
				{
					$kode[7]						= '00';
				}
				
				/* check if key already exist, push to prepared statement if not yet exist */
				if($val->status == 'SKPD')
				{
					$id_bidang						= $this->model->select
					('
						sipd__ref__bidang.id
					')
					->join
					(
						'sipd__ref__urusan',
						'sipd__ref__urusan.id = sipd__ref__bidang.id_urusan'
					)
					->get_where
					(
						'sipd__ref__bidang',
						array
						(
							'sipd__ref__urusan.kd_urusan'		=> (isset($kode[0]) ? (int) $kode[0] : 0),
							'sipd__ref__bidang.kd_bidang'		=> (isset($kode[1]) ? (int) $kode[1] : 0)
						),
						1
					)
					->row('id');
					
					$id_bidang_2					= $this->model->select
					('
						sipd__ref__bidang.id
					')
					->join
					(
						'sipd__ref__urusan',
						'sipd__ref__urusan.id = sipd__ref__bidang.id_urusan'
					)
					->get_where
					(
						'sipd__ref__bidang',
						array
						(
							'sipd__ref__urusan.kd_urusan'		=> (isset($kode[2]) ? (int) $kode[2] : 0),
							'sipd__ref__bidang.kd_bidang'		=> (isset($kode[3]) ? (int) $kode[3] : 0)
						),
						1
					)
					->row('id');
					
					$id_bidang_3					= $this->model->select
					('
						sipd__ref__bidang.id
					')
					->join
					(
						'sipd__ref__urusan',
						'sipd__ref__urusan.id = sipd__ref__bidang.id_urusan'
					)
					->get_where
					(
						'sipd__ref__bidang',
						array
						(
							'sipd__ref__urusan.kd_urusan'		=> (isset($kode[4]) ? (int) $kode[4] : 0),
							'sipd__ref__bidang.kd_bidang'		=> (isset($kode[5]) ? (int) $kode[5] : 0)
						),
						1
					)
					->row('id');
					
					if($id_bidang)
					{
						$prepare_unit[]				= array
						(
							'id'					=> $val->id_skpd,
							'id_bidang'				=> $id_bidang,
							'id_bidang_2'			=> $id_bidang_2,
							'id_bidang_3'			=> $id_bidang_3,
							'kd_unit'				=> (isset($kode[6]) ? $kode[6] : 0),
							'nm_unit'				=> $val->nama_skpd,
							'tahun'					=> $this->_year
						);
					}
				}
				
				/* check if key already exist, push to prepared statement if not yet exist */
				$prepare_sub_unit[]				= array
				(
					'id'							=> $val->id_skpd,
					'id_unit'						=> $val->id_unit,
					'kd_sub'						=> (isset($kode[7]) ? $kode[7] : 0),
					'nm_sub'						=> $val->nama_skpd,
					'tahun'							=> $this->_year
				);
			}
			
			/* truncate database table from children to parent */
			$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
			
			$this->model->truncate('sipd__ref__sub');
			$this->model->truncate('sipd__ref__unit');
			
			$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
			/* end truncate */
			
			$this->model->insert_batch('sipd__ref__unit', $prepare_unit, sizeof($prepare_unit));
			$this->model->insert_batch('sipd__ref__sub', $prepare_sub_unit, sizeof($prepare_sub_unit));
			
			$success								= true;
			$html									= '
				<div class="alert alert-success">
					Integrasi dengan data SIPD berhasil dijalankan
				</div>
				<ul>
					<li>
						Data Unit: <b>' . number_format(sizeof($prepare_unit)) . '</b> data disimpan;
					</li>
					<li>
						Data Sub Unit: <b>' . number_format(sizeof($prepare_sub_unit)) . '</b> data disimpan;
					</li>
				</ul>
			';
		}
		else
		{
			$html									= '
				<div class="alert alert-warning">
					Tidak dapat mengambil data dari SIPD disebabkan oleh beberapa faktor berikut:
				</div>
				<br />
				<ul>
					<li>
						Kesalahan dalam memilih data;
					</li>
					<li>
						Gagal mengakses server SIPD.
					</li>
				</ul>
			';
		}
		
		return make_json
		(
			array
			(
				'status'							=> 206,
				'exception'							=> array
				(
					'size'							=> 720,
					'title'							=> ($success ? 'Integrasi Sub Kegiatan berhasil!' : 'Integrasi Sub Kegiatan gagal!'),
					'icon'							=> ($success ? 'mdi mdi-check' : 'mdi mdi-alert'),
					'html'							=> $html
				)
			)
		);
	}
	
	public function rekening()
	{
		$success									= false;
		
		/* session initialization */
		$this->_initialize_session();
		
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> false,
				CURLOPT_RETURNTRANSFER				=> true,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah/main/plan/akun/' . $this->_year . '/tampil-akun/' . $this->_id_daerah . '/' . $this->_id_sub,
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* simulate the request through AJAX */
				CURLOPT_HTTPHEADER					=> array
				(
					'X-Requested-With: XMLHttpRequest'
				),
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= json_decode(curl_exec($curl));
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		if(isset($output->data) && sizeof($output->data) > 0)
		{
			/* truncate database table from children to parent */
			$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
			
			$this->model->truncate('sipd__ref__rek_6');
			$this->model->truncate('sipd__ref__rek_5');
			$this->model->truncate('sipd__ref__rek_4');
			$this->model->truncate('sipd__ref__rek_3');
			$this->model->truncate('sipd__ref__rek_2');
			$this->model->truncate('sipd__ref__rek_1');
			
			$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
			/* end truncate */
			
			$kd_rek_1_collection					= array();
			$kd_rek_2_collection					= array();
			$kd_rek_3_collection					= array();
			$kd_rek_4_collection					= array();
			$kd_rek_5_collection					= array();
			$kd_rek_6_collection					= array();
			
			$count_rek_1							= 0;
			$count_rek_2							= 0;
			$count_rek_3							= 0;
			$count_rek_4							= 0;
			$count_rek_5							= 0;
			$count_rek_6							= 0;
			
			foreach($output->data as $key => $val)
			{
				$kode								= explode('.', $val->kode_akun);
				$kd_rek_1							= (isset($kode[0]) ? (int) $kode[0] : null);
				$kd_rek_2							= (isset($kode[1]) ? (int) $kode[1] : null);
				$kd_rek_3							= (isset($kode[2]) ? (int) $kode[2] : null);
				$kd_rek_4							= (isset($kode[3]) ? (int) $kode[3] : null);
				$kd_rek_5							= (isset($kode[4]) ? (int) $kode[4] : null);
				$kd_rek_6							= (isset($kode[5]) ? (int) $kode[5] : null);
				
				if($kd_rek_1 && !$kd_rek_2)
				{
					$prepare						= array
					(
						'id'						=> $val->id_akun,
						'id_jns_kas'				=> 1,
						'kd_rek_1'					=> $kd_rek_1,
						'uraian'					=> $val->nama_akun,
						'tahun'						=> $this->_year
					);
					
					if($this->model->insert('sipd__ref__rek_1', $prepare))
					{
						$count_rek_1++;
					}
				}
				if($kd_rek_1 && $kd_rek_2 && !$kd_rek_3)
				{
					$id_rek_1						= $this->model->select
					('
						sipd__ref__rek_1.id
					')
					->get_where
					(
						'sipd__ref__rek_1',
						array
						(
							'sipd__ref__rek_1.kd_rek_1'		=> $kd_rek_1
						),
						1
					)
					->row('id');
					
					$prepare						= array
					(
						'id'						=> $val->id_akun,
						'id_ref_rek_1'				=> $id_rek_1,
						'id_jns_kas'				=> 1,
						'kd_rek_2'					=> $kd_rek_2,
						'uraian'					=> $val->nama_akun,
						'tahun'						=> $this->_year
					);
					
					if($id_rek_1 && $this->model->insert('sipd__ref__rek_2', $prepare))
					{
						$count_rek_2++;
					}
				}
				if($kd_rek_1 && $kd_rek_2 && $kd_rek_3 && !$kd_rek_4)
				{
					$id_rek_2						= $this->model->select
					('
						sipd__ref__rek_2.id
					')
					->join
					(
						'sipd__ref__rek_1',
						'sipd__ref__rek_1.id = sipd__ref__rek_2.id_ref_rek_1'
					)
					->get_where
					(
						'sipd__ref__rek_2',
						array
						(
							'sipd__ref__rek_1.kd_rek_1'		=> $kd_rek_1,
							'sipd__ref__rek_2.kd_rek_2'		=> $kd_rek_2
						),
						1
					)
					->row('id');
					
					$prepare						= array
					(
						'id'						=> $val->id_akun,
						'id_ref_rek_2'				=> $id_rek_2,
						'id_jns_kas'				=> 1,
						'kd_rek_3'					=> $kd_rek_3,
						'uraian'					=> $val->nama_akun,
						'tahun'						=> $this->_year
					);
					
					if($id_rek_2 && $this->model->insert('sipd__ref__rek_3', $prepare))
					{
						$count_rek_3++;
					}
				}
				if($kd_rek_1 && $kd_rek_2 && $kd_rek_3 && $kd_rek_4 && !$kd_rek_5)
				{
					$id_rek_3						= $this->model->select
					('
						sipd__ref__rek_3.id
					')
					->join
					(
						'sipd__ref__rek_2',
						'sipd__ref__rek_2.id = sipd__ref__rek_3.id_ref_rek_2'
					)
					->join
					(
						'sipd__ref__rek_1',
						'sipd__ref__rek_1.id = sipd__ref__rek_2.id_ref_rek_1'
					)
					->get_where
					(
						'sipd__ref__rek_3',
						array
						(
							'sipd__ref__rek_1.kd_rek_1'		=> $kd_rek_1,
							'sipd__ref__rek_2.kd_rek_2'		=> $kd_rek_2,
							'sipd__ref__rek_3.kd_rek_3'		=> $kd_rek_3
						),
						1
					)
					->row('id');
					
					$prepare						= array
					(
						'id'						=> $val->id_akun,
						'id_ref_rek_3'				=> $id_rek_3,
						'id_jns_kas'				=> 1,
						'kd_rek_4'					=> $kd_rek_4,
						'uraian'					=> $val->nama_akun,
						'tahun'						=> $this->_year
					);
					
					if($id_rek_3 && $this->model->insert('sipd__ref__rek_4', $prepare))
					{
						$count_rek_4++;
					}
				}
				if($kd_rek_1 && $kd_rek_2 && $kd_rek_3 && $kd_rek_4 && $kd_rek_5 && !$kd_rek_6)
				{
					$id_rek_4						= $this->model->select
					('
						sipd__ref__rek_4.id
					')
					->join
					(
						'sipd__ref__rek_3',
						'sipd__ref__rek_3.id = sipd__ref__rek_4.id_ref_rek_3'
					)
					->join
					(
						'sipd__ref__rek_2',
						'sipd__ref__rek_2.id = sipd__ref__rek_3.id_ref_rek_2'
					)
					->join
					(
						'sipd__ref__rek_1',
						'sipd__ref__rek_1.id = sipd__ref__rek_2.id_ref_rek_1'
					)
					->get_where
					(
						'sipd__ref__rek_4',
						array
						(
							'sipd__ref__rek_1.kd_rek_1'		=> $kd_rek_1,
							'sipd__ref__rek_2.kd_rek_2'		=> $kd_rek_2,
							'sipd__ref__rek_3.kd_rek_3'		=> $kd_rek_3,
							'sipd__ref__rek_4.kd_rek_4'		=> $kd_rek_4
						),
						1
					)
					->row('id');
					
					$prepare						= array
					(
						'id'						=> $val->id_akun,
						'id_ref_rek_4'				=> $id_rek_4,
						'id_jns_kas'				=> 1,
						'kd_rek_5'					=> $kd_rek_5,
						'uraian'					=> $val->nama_akun,
						'keterangan'				=> $val->nama_akun,
						'tahun'						=> $this->_year
					);
					
					if($id_rek_4 && $this->model->insert('sipd__ref__rek_5', $prepare))
					{
						$count_rek_5++;
					}
				}
				if($kd_rek_1 && $kd_rek_2 && $kd_rek_3 && $kd_rek_4 && $kd_rek_5 && $kd_rek_6)
				{
					$id_rek_5						= $this->model->select
					('
						sipd__ref__rek_5.id
					')
					->join
					(
						'sipd__ref__rek_4',
						'sipd__ref__rek_4.id = sipd__ref__rek_5.id_ref_rek_4'
					)
					->join
					(
						'sipd__ref__rek_3',
						'sipd__ref__rek_3.id = sipd__ref__rek_4.id_ref_rek_3'
					)
					->join
					(
						'sipd__ref__rek_2',
						'sipd__ref__rek_2.id = sipd__ref__rek_3.id_ref_rek_2'
					)
					->join
					(
						'sipd__ref__rek_1',
						'sipd__ref__rek_1.id = sipd__ref__rek_2.id_ref_rek_1'
					)
					->get_where
					(
						'sipd__ref__rek_5',
						array
						(
							'sipd__ref__rek_1.kd_rek_1'		=> $kd_rek_1,
							'sipd__ref__rek_2.kd_rek_2'		=> $kd_rek_2,
							'sipd__ref__rek_3.kd_rek_3'		=> $kd_rek_3,
							'sipd__ref__rek_4.kd_rek_4'		=> $kd_rek_4,
							'sipd__ref__rek_5.kd_rek_5'		=> $kd_rek_5
						),
						1
					)
					->row('id');
					
					$prepare						= array
					(
						'id'						=> $val->id_akun,
						'id_ref_rek_5'				=> $id_rek_5,
						'id_jns_kas'				=> 1,
						'kd_rek_6'					=> $kd_rek_6,
						'uraian'					=> $val->nama_akun,
						'keterangan'				=> $val->nama_akun,
						'tahun'						=> $this->_year
					);
					
					if($id_rek_5 && $this->model->insert('sipd__ref__rek_6', $prepare))
					{
						$count_rek_6++;
					}
				}
				
				if(!in_array($kd_rek_1, $kd_rek_1_collection))
				{
					$kd_rek_1_collection[]			= $kd_rek_1;
				}
				if(!in_array($kd_rek_2, $kd_rek_2_collection))
				{
					$kd_rek_2_collection[]			= $kd_rek_2;
				}
				if(!in_array($kd_rek_3, $kd_rek_3_collection))
				{
					$kd_rek_3_collection[]			= $kd_rek_3;
				}
				if(!in_array($kd_rek_4, $kd_rek_4_collection))
				{
					$kd_rek_4_collection[]			= $kd_rek_4;
				}
				if(!in_array($kd_rek_5, $kd_rek_5_collection))
				{
					$kd_rek_5_collection[]			= $kd_rek_5;
				}
				if(!in_array($kd_rek_6, $kd_rek_6_collection))
				{
					$kd_rek_6_collection[]			= $kd_rek_6;
				}
			}
			
			$success								= true;
			$html									= '
				<div class="alert alert-success">
					Integrasi dengan data SIPD berhasil dijalankan
				</div>
				<ul>
					<li>
						Data Rekening 1: <b>' . number_format($count_rek_1) . '</b> data disimpan;
					</li>
					<li>
						Data Rekening 2: <b>' . number_format($count_rek_2) . '</b> data disimpan;
					</li>
					<li>
						Data Rekening 3: <b>' . number_format($count_rek_3) . '</b> data disimpan;
					</li>
					<li>
						Data Rekening 4: <b>' . number_format($count_rek_4) . '</b> data disimpan;
					</li>
					<li>
						Data Rekening 5: <b>' . number_format($count_rek_5) . '</b> data disimpan;
					</li>
					<li>
						Data Rekening 6: <b>' . number_format($count_rek_6) . '</b> data disimpan.
					</li>
				</ul>
			';
		}
		else
		{
			$html									= '
				<div class="alert alert-warning">
					Tidak dapat mengambil data dari SIPD disebabkan oleh beberapa faktor berikut:
				</div>
				<br />
				<ul>
					<li>
						Kesalahan dalam memilih data;
					</li>
					<li>
						Gagal mengakses server SIPD.
					</li>
				</ul>
			';
		}
		
		return make_json
		(
			array
			(
				'status'							=> 206,
				'exception'							=> array
				(
					'size'							=> 720,
					'title'							=> ($success ? 'Integrasi Sub Kegiatan berhasil!' : 'Integrasi Sub Kegiatan gagal!'),
					'icon'							=> ($success ? 'mdi mdi-check' : 'mdi mdi-alert'),
					'html'							=> $html
				)
			)
		);
	}
	
	public function sumber_dana()
	{
		$success									= false;
		
		/* session initialization */
		$this->_initialize_session();
		
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> false,
				CURLOPT_RETURNTRANSFER				=> true,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah/main/plan/dana/' . $this->_year . '/tampil-dana/' . $this->_id_daerah . '/' . $this->_id_sub,
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* simulate the request through AJAX */
				CURLOPT_HTTPHEADER					=> array
				(
					'X-Requested-With: XMLHttpRequest'
				),
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= json_decode(curl_exec($curl));
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		//$output										= json_decode(file_get_contents('Sumber Dana.json'));
		
		if(isset($output->data) && sizeof($output->data) > 0)
		{
			/* truncate database table from children to parent */
			$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
			
			$this->model->truncate('sipd__ref__sumber_dana_rek_6');
			$this->model->truncate('sipd__ref__sumber_dana_rek_5');
			$this->model->truncate('sipd__ref__sumber_dana_rek_4');
			$this->model->truncate('sipd__ref__sumber_dana_rek_3');
			$this->model->truncate('sipd__ref__sumber_dana_rek_2');
			$this->model->truncate('sipd__ref__sumber_dana_rek_1');
			
			$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
			/* end truncate */
			
			$collected_id							= array();
			
			/* prepare insert data */
			$prepare_sumber_dana_1					= 0;
			$prepare_sumber_dana_2					= 0;
			$prepare_sumber_dana_3					= 0;
			$prepare_sumber_dana_4					= 0;
			$prepare_sumber_dana_5					= 0;
			$prepare_sumber_dana_6					= 0;
			
			foreach($output->data as $key => $val)
			{
				/* destructure merged data */
				$kode								= explode('.', $val->kode_dana);
				$kode_induk							= substr($val->kode_dana, 0, strrpos($val->kode_dana, '.'));
				
				if(!isset($kode[1]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_1',
						array
						(
							'id'					=> $val->id_dana,
							'kd_sumber_dana_rek_1'	=> $kode[0],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_1++;
				}
				elseif(!isset($kode[2]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_2',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_1'	=> $collected_id[$kode_induk],
							'kd_sumber_dana_rek_2'	=> $kode[1],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_2++;
				}
				elseif(!isset($kode[3]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_3',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_2'	=> $collected_id[$kode_induk],
							'kd_sumber_dana_rek_3'	=> $kode[2],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_3++;
				}
				elseif(!isset($kode[4]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_4',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_3'	=> $collected_id[$kode_induk],
							'kd_sumber_dana_rek_4'	=> $kode[3],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_4++;
				}
				elseif(!isset($kode[5]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_5',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_4'	=> $collected_id[$kode_induk],
							'kd_sumber_dana_rek_5'	=> $kode[4],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_5++;
				}
				else
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_6',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_5'	=> $collected_id[$kode_induk],
							'kode'					=> $kode[5],
							'nama_sumber_dana'		=> $val->nama_dana,
							'singkat'				=> '',
							'keterangan'			=> '',
							'tahun'					=> $this->_year
						)
					);
					
					$prepare_sumber_dana_6++;
				}
			}
			
			$success								= true;
			$html									= '
				<div class="alert alert-success">
					Integrasi dengan data SIPD berhasil dijalankan
				</div>
				<ul>
					<li>
						Data Sumber Dana Rekening 1: <b>' . number_format($prepare_sumber_dana_1) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 2: <b>' . number_format($prepare_sumber_dana_2) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 3: <b>' . number_format($prepare_sumber_dana_3) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 4: <b>' . number_format($prepare_sumber_dana_4) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 5: <b>' . number_format($prepare_sumber_dana_5) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 6: <b>' . number_format($prepare_sumber_dana_6) . '</b> data disimpan;
					</li>
				</ul>
			';
		}
		else
		{
			$html									= '
				<div class="alert alert-warning">
					Tidak dapat mengambil data dari SIPD disebabkan oleh beberapa faktor berikut:
				</div>
				<br />
				<ul>
					<li>
						Kesalahan dalam memilih data;
					</li>
					<li>
						Gagal mengakses server SIPD.
					</li>
				</ul>
			';
		}
		
		return make_json
		(
			array
			(
				'status'							=> 206,
				'exception'							=> array
				(
					'size'							=> 720,
					'title'							=> ($success ? 'Integrasi Sub Kegiatan berhasil!' : 'Integrasi Sub Kegiatan gagal!'),
					'icon'							=> ($success ? 'mdi mdi-check' : 'mdi mdi-alert'),
					'html'							=> $html
				)
			)
		);
	}
	
	public function standar_harga()
	{
		return false;
		$success									= false;
		
		/* session initialization */
		$this->_initialize_session();
		
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> false,
				CURLOPT_RETURNTRANSFER				=> true,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah/main/plan/dana/' . $this->_year . '/tampil-dana/' . $this->_id_daerah . '/' . $this->_id_sub,
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* simulate the request through AJAX */
				CURLOPT_HTTPHEADER					=> array
				(
					'X-Requested-With: XMLHttpRequest'
				),
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= json_decode(curl_exec($curl));
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		$output										= json_decode(file_get_contents('Sumber Dana.json'));
		
		if(isset($output->data) && sizeof($output->data) > 0)
		{
			/* truncate database table from children to parent */
			$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
			
			$this->model->truncate('sipd__ref__sumber_dana_rek_6');
			$this->model->truncate('sipd__ref__sumber_dana_rek_5');
			$this->model->truncate('sipd__ref__sumber_dana_rek_4');
			$this->model->truncate('sipd__ref__sumber_dana_rek_3');
			$this->model->truncate('sipd__ref__sumber_dana_rek_2');
			$this->model->truncate('sipd__ref__sumber_dana_rek_1');
			
			$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
			/* end truncate */
			
			$collected_id							= array();
			
			/* prepare insert data */
			$prepare_sumber_dana_1					= 0;
			$prepare_sumber_dana_2					= 0;
			$prepare_sumber_dana_3					= 0;
			$prepare_sumber_dana_4					= 0;
			$prepare_sumber_dana_5					= 0;
			$prepare_sumber_dana_6					= 0;
			
			foreach($output->data as $key => $val)
			{
				/* destructure merged data */
				$kode								= explode('.', $val->kode_dana);
				$kode_induk							= substr($val->kode_dana, 0, strrpos($val->kode_dana, '.'));
				
				if(!isset($kode[1]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_1',
						array
						(
							'id'					=> $val->id_dana,
							'kd_sumber_dana_rek_1'	=> $kode[0],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_1++;
				}
				elseif(!isset($kode[2]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_2',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_1'	=> $collected_id[$kode_induk],
							'kd_sumber_dana_rek_2'	=> $kode[1],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_2++;
				}
				elseif(!isset($kode[3]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_3',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_2'	=> $collected_id[$kode_induk],
							'kd_sumber_dana_rek_3'	=> $kode[2],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_3++;
				}
				elseif(!isset($kode[4]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_4',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_3'	=> $collected_id[$kode_induk],
							'kd_sumber_dana_rek_4'	=> $kode[3],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_4++;
				}
				elseif(!isset($kode[5]))
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_5',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_4'	=> $collected_id[$kode_induk],
							'kd_sumber_dana_rek_5'	=> $kode[4],
							'uraian'				=> $val->nama_dana,
							'tahun'					=> $this->_year
						)
					);
					
					$collected_id[$val->kode_dana]	= $val->id_dana;
					
					$prepare_sumber_dana_5++;
				}
				else
				{
					$this->model->insert
					(
						'sipd__ref__sumber_dana_rek_6',
						array
						(
							'id'					=> $val->id_dana,
							'id_sumber_dana_rek_5'	=> $collected_id[$kode_induk],
							'kode'					=> $kode[5],
							'nama_sumber_dana'		=> $val->nama_dana,
							'singkat'				=> '',
							'keterangan'			=> '',
							'tahun'					=> $this->_year
						)
					);
					
					$prepare_sumber_dana_6++;
				}
			}
			
			$success								= true;
			$html									= '
				<div class="alert alert-success">
					Integrasi dengan data SIPD berhasil dijalankan
				</div>
				<ul>
					<li>
						Data Sumber Dana Rekening 1: <b>' . number_format($prepare_sumber_dana_1) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 2: <b>' . number_format($prepare_sumber_dana_2) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 3: <b>' . number_format($prepare_sumber_dana_3) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 4: <b>' . number_format($prepare_sumber_dana_4) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 5: <b>' . number_format($prepare_sumber_dana_5) . '</b> data disimpan;
					</li>
					<li>
						Data Sumber Dana Rekening 6: <b>' . number_format($prepare_sumber_dana_6) . '</b> data disimpan;
					</li>
				</ul>
			';
		}
		else
		{
			$html									= '
				<div class="alert alert-warning">
					Tidak dapat mengambil data dari SIPD disebabkan oleh beberapa faktor berikut:
				</div>
				<br />
				<ul>
					<li>
						Kesalahan dalam memilih data;
					</li>
					<li>
						Gagal mengakses server SIPD.
					</li>
				</ul>
			';
		}
		
		return make_json
		(
			array
			(
				'status'							=> 206,
				'exception'							=> array
				(
					'size'							=> 720,
					'title'							=> ($success ? 'Integrasi Sub Kegiatan berhasil!' : 'Integrasi Sub Kegiatan gagal!'),
					'icon'							=> ($success ? 'mdi mdi-check' : 'mdi mdi-alert'),
					'html'							=> $html
				)
			)
		);
	}
	
	public function kegiatan()
	{
		if(!$this->_id_sub)
		{
			$sub_units								= $this->model->select
			('
				id
			')
			->get_where
			(
				'sipd__ref__sub',
				array
				(
					'tahun'							=> $this->_year
				)
			)
			->result_array();
		}
		else
		{
			$sub_units								= array
			(
				array
				(
					'id'							=> $this->_id_sub
				)
			);
		}
		
		$success									= false;
		
		/* prepare insert data */
		$prepare_program							= 0;
		$prepare_kegiatan							= 0;
		$prepare_kegiatan_sub						= 0;
		
		$html										= null;
		
		if($sub_units)
		{
			/* truncate database table from children to parent */
			$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
			
			$this->model->truncate('ta__indikator_sub');
			$this->model->truncate('sipd__ta__kegiatan_sub');
			$this->model->truncate('sipd__ta__kegiatan');
			$this->model->truncate('sipd__ta__program');
			
			$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
			/* end truncate */
			
			/* session initialization */
			$this->_initialize_session();
			
			/* define variable to store existing data */
			$program								= array();
			$kegiatan								= array();
			$sub_kegiatan							= array();
			
			foreach($sub_units as $abc => $xyz)
			{
				/* initialize cURL */
				$curl								= curl_init();
				
				/* set cURL options */
				curl_setopt_array
				(
					$curl,
					array
					(
						CURLOPT_HEADER				=> false,
						CURLOPT_RETURNTRANSFER		=> true,
						CURLOPT_URL					=> $this->_sipd_hostname . '/daerah/main/plan/belanja/' . $this->_year . '/giat/tampil-giat/' . $this->_id_daerah . '/' . $xyz['id'],
						CURLOPT_FOLLOWLOCATION		=> true,
						
						/* simulate the request through AJAX */
						CURLOPT_HTTPHEADER			=> array
						(
							'X-Requested-With: XMLHttpRequest'
						),
						
						/* cookie handling */
						CURLOPT_COOKIEJAR			=> $this->_cookie_file,
						CURLOPT_COOKIEFILE			=> $this->_cookie_file
					)
				);
				
				/* execute cURL */
				$output								= json_decode(curl_exec($curl));
				
				/* close a cURL session and frees all resources */
				curl_close($curl);
				
				if(isset($output->data) && sizeof($output->data) > 0)
				{
					foreach($output->data as $key => $val)
					{
						$primary					= null;
						
						$kode						= explode('.', substr($val->nama_sub_giat->nama_sub_giat, 0, strpos($val->nama_sub_giat->nama_sub_giat, ' ')));
						
						if(!in_array($val->id_giat, $kegiatan))
						{
							if($kode[2] == 1)
							{
								$_kode				= explode('.', $val->kode_skpd);
								
								$kode[0]			= (int) $_kode[0];
								$kode[1]			= (int) $_kode[1];
								
								$id_program			= $this->model->select
								('
									"' . $kode[0] . '" AS kd_urusan,
									"' . $kode[1] . '" AS kd_bidang,
									sipd__ref__program.id
								')
								->get_where
								(
									'sipd__ref__program',
									array
									(
										'sipd__ref__program.kd_program'	=> 1
									)
								)
								->row();
							}
							else
							{
								$id_program			= $this->model->select
								('
									sipd__ref__urusan.kd_urusan,
									sipd__ref__bidang.kd_bidang,
									sipd__ref__program.id
								')
								->join
								(
									'sipd__ref__bidang',
									'sipd__ref__bidang.id = sipd__ref__program.id_bidang'
								)
								->join
								(
									'sipd__ref__urusan',
									'sipd__ref__urusan.id = sipd__ref__bidang.id_urusan'
								)
								->get_where
								(
									'sipd__ref__program',
									array
									(
										'sipd__ref__urusan.kd_urusan'	=> (int) $kode[0],
										'sipd__ref__bidang.kd_bidang'	=> (int) $kode[1],
										'sipd__ref__program.kd_program'	=> (int) $kode[2]
									),
									1
								)
								->row();
							}
							
							if(!$id_program) continue;
							
							$initial								= $id_program->id . '_' . $val->id_unit;
							
							if(isset($program[$initial]))
							{
								$id_prog							= $program[$initial];
							}
							else
							{
								$query								= $this->model->insert
								(
									'sipd__ta__program',
									array
									(
										'id_prog'					=> $id_program->id,
										'id_sub'					=> $val->id_unit,
										'kd_id_prog'				=> $id_program->kd_urusan . sprintf('%02d', $id_program->kd_bidang),
										'id_sasaran_indikator'		=> 1,
										'tahun'						=> $this->_year
									)
								);
								
								$id_prog							= $this->model->insert_id();
								
								$program[$initial]					= $id_prog;
								
								if($query)
								{
									$prepare_program++;
								}
							}
							
							if($id_prog)
							{
								$primary							= $this->model->select
								('
									sipd__ref__kegiatan.id AS id_kegiatan,
									sipd__ref__kegiatan_sub.id AS id_kegiatan_sub
								')
								->join
								(
									'sipd__ref__kegiatan',
									'sipd__ref__kegiatan.id = sipd__ref__kegiatan_sub.id_kegiatan'
								)
								->join
								(
									'sipd__ref__program',
									'sipd__ref__program.id = sipd__ref__kegiatan.id_program'
								)
								->join
								(
									'sipd__ref__bidang',
									'sipd__ref__bidang.id = sipd__ref__program.id_bidang'
								)
								->join
								(
									'sipd__ref__urusan',
									'sipd__ref__urusan.id = sipd__ref__bidang.id_urusan'
								)
								->get_where
								(
									'sipd__ref__kegiatan_sub',
									array
									(
										'sipd__ref__kegiatan_sub.kd_kegiatan_sub'		=> (int) $kode[5],
										'sipd__ref__kegiatan.kd_kegiatan'				=> (int) $kode[3] . sprintf('%02d', (int) $kode[4]),
										'sipd__ref__program.kd_program'					=> (int) $kode[2],
										'sipd__ref__bidang.kd_bidang'					=> (int) $kode[1],
										'sipd__ref__urusan.kd_urusan'					=> (int) $kode[0]
									),
									1
								)
								->row();
								
								if(!$primary) continue;
								
								if(in_array($val->nama_giat->gtLock, array(3))) continue;
								
								if(strpos($val->nama_giat->nama_giat, '||') !== false)
								{
									$val->nama_giat->nama_giat		= substr($val->nama_giat->nama_giat, 0, strrpos($val->nama_giat->nama_giat, '||'));
								}
								
								$query								= $this->model->insert
								(
									'sipd__ta__kegiatan',
									array
									(
										'id'						=> $val->id_giat,
										'id_prog'					=> $id_prog, //ta__program
										'capaian_program'			=> '',
										'kd_keg'					=> (int) $kode[3] . sprintf('%02d', (int) $kode[4]),
										'kegiatan'					=> trim(strstr($val->nama_giat->nama_giat, ' ')),
										'id_kegiatan'				=> $primary->id_kegiatan, // ref__kegiatan
										'files'						=> '[]',
										'created'					=> date('Y-m-d H:i:s'),
										'riwayat_skpd'				=> '[]',
										'tahun'						=> $this->_year
									)
								);
								
								if($query)
								{
									$prepare_kegiatan++;
								}
							}
							
							$kegiatan[]								= $val->id_giat;
						}
						
						if(strpos($val->nama_sub_giat->nama_sub_giat, '||') !== false)
						{
							$val->nama_sub_giat->nama_sub_giat		= substr($val->nama_sub_giat->nama_sub_giat, 0, strrpos($val->nama_sub_giat->nama_sub_giat, '||'));
						}
						
						if(!$primary) continue;
						
						if(in_array($val->id_sub_giat, $sub_kegiatan)) continue;
						
						if(!$val->nama_sub_giat->pagu) continue;
						
						$query										= $this->model->insert
						(
							'sipd__ta__kegiatan_sub',
							array
							(
								'id'								=> $val->id_sub_giat,
								'id_keg'							=> $val->id_giat, // ta__kegiatan
								'capaian_kegiatan'					=> '',
								'id_musrenbang'						=> '',
								'id_reses'							=> '',
								'id_sumber_dana'					=> 1,
								'id_kel'							=> '',
								'pengusul'							=> '',
								'flag'								=> 1,
								'pilihan'							=> '',
								'map_coordinates'					=> '[]',
								'map_address'						=> '',
								'kelurahan'							=> '',
								'kecamatan'							=> '',
								'files'								=> '[]',
								'kd_keg_sub'						=> (int) $kode[5],
								'jenis_kegiatan'					=> '',
								'jenis_kegiatan_renja'				=> '',
								'id_kegiatan_sub'					=> $primary->id_kegiatan_sub, // ref__kegiatan_sub
								'kegiatan_sub'						=> trim(strstr($val->nama_sub_giat->nama_sub_giat, ' ')),
								'kelompok_sasaran'					=> '',
								'waktu_pelaksanaan_mulai'			=> '',
								'waktu_pelaksanaan_sampai'			=> '',
								'survey'							=> '',
								'variabel_usulan'					=> '',
								'nilai_usulan'						=> '',
								'pagu'								=> $val->nama_sub_giat->pagu,
								'pagu_1'							=> '',
								'id_model'							=> '',
								'variabel'							=> '',
								'tahun'								=> $this->_year,
								'created'							=> date('Y-m-d H:i:s'),
								'riwayat_skpd'						=> '[]',
								'jenis_usulan'						=> '',
								'id_jenis_anggaran'					=> '',
								'latar_belakang_perubahan'			=> '',
								'lock_kegiatan_sub'					=> 0,
								'asistensi_ready'					=> 0
							)
						);
						
						if($query)
						{
							$prepare_kegiatan_sub++;
						}
						
						$sub_kegiatan[]								= $val->id_sub_giat;
					}
					
					$success										= true;
				}
			}
		}
		
		if($success)
		{
			$html									= '
				<div class="alert alert-success">
					Integrasi dengan data SIPD berhasil dijalankan
				</div>
				<ul>
					<li>
						Data Program: <b>' . number_format($prepare_program) . '</b> data disimpan;
					</li>
					<li>
						Data Kegiatan: <b>' . number_format($prepare_kegiatan) . '</b> data disimpan;
					</li>
					<li>
						Data Sub Kegiatan: <b>' . number_format($prepare_kegiatan_sub) . '</b> data disimpan;
					</li>
				</ul>
			';
		}
		else
		{
			$html									= '
				<div class="alert alert-warning">
					Tidak dapat mengambil data dari SIPD disebabkan oleh beberapa faktor berikut:
				</div>
				<br />
				<ul>
					<li>
						Kesalahan dalam memilih data;
					</li>
					<li>
						Gagal mengakses server SIPD.
					</li>
				</ul>
			';
		}
		
		return make_json
		(
			array
			(
				'status'							=> 206,
				'exception'							=> array
				(
					'size'							=> 720,
					'title'							=> ($success ? 'Integrasi Sub Kegiatan berhasil!' : 'Integrasi Sub Kegiatan gagal!'),
					'icon'							=> ($success ? 'mdi mdi-check' : 'mdi mdi-alert'),
					'html'							=> $html
				)
			)
		);
	}
	
	public function detail_kegiatan()
	{
		if(!$this->_id_sub)
		{
			$sub_units								= $this->model->select
			('
				id
			')
			->get_where
			(
				'sipd__ref__sub',
				array
				(
					'tahun'							=> $this->_year
				)
			)
			->result_array();
		}
		else
		{
			$sub_units								= array
			(
				array
				(
					'id'							=> $this->_id_sub
				)
			);
		}
		
		$success									= false;
		
		/* prepare insert data */
		$prepare_program							= 0;
		$prepare_kegiatan							= 0;
		$prepare_kegiatan_sub						= 0;
		
		$html										= null;
		
		if($sub_units)
		{
			/* truncate database table from children to parent */
			$this->model->query('SET FOREIGN_KEY_CHECKS = 0');
			
			$this->model->truncate('ta__indikator_sub');
			$this->model->truncate('sipd__ta__kegiatan_sub');
			$this->model->truncate('sipd__ta__kegiatan');
			$this->model->truncate('sipd__ta__program');
			
			$this->model->query('SET FOREIGN_KEY_CHECKS = 1');
			/* end truncate */
			
			/* session initialization */
			$this->_initialize_session();
			
			/* define variable to store existing data */
			$program								= array();
			$kegiatan								= array();
			$sub_kegiatan							= array();
			
			foreach($sub_units as $abc => $xyz)
			{
				/* initialize cURL */
				$curl								= curl_init();
				
				/* set cURL options */
				curl_setopt_array
				(
					$curl,
					array
					(
						CURLOPT_HEADER				=> false,
						CURLOPT_RETURNTRANSFER		=> true,
						CURLOPT_URL					=> $this->_sipd_hostname . '/daerah/main/plan/belanja/' . $this->_year . '/giat/list/' . $this->_id_daerah . '/' . $xyz['id'],
						CURLOPT_FOLLOWLOCATION		=> true,
						
						/* cookie handling */
						CURLOPT_COOKIEJAR			=> $this->_cookie_file,
						CURLOPT_COOKIEFILE			=> $this->_cookie_file
					)
				);
				
				/* execute cURL */
				$output								= curl_exec($curl);
				$error								= curl_error($curl);
				
				/* close a cURL session and frees all resources */
				curl_close($curl);
				
				if($error)
				{
					return throw_exception(403, $error);
				}
				
				/* extract the CSRF Token */
				preg_match('/<meta name="_token" content="(.*?)"/', $output, $token);
				
				/* initialize cURL */
				$curl								= curl_init();
				
				/* set cURL options */
				curl_setopt_array
				(
					$curl,
					array
					(
						CURLOPT_HEADER				=> false,
						CURLOPT_RETURNTRANSFER		=> true,
						CURLOPT_URL					=> $this->_sipd_hostname . '/daerah/main/plan/belanja/' . $this->_year . '/giat/tampil-giat/' . $this->_id_daerah . '/' . $xyz['id'],
						CURLOPT_FOLLOWLOCATION		=> true,
						
						/* simulate the request through AJAX */
						CURLOPT_HTTPHEADER			=> array
						(
							'X-Requested-With: XMLHttpRequest'
						),
						
						/* cookie handling */
						CURLOPT_COOKIEJAR			=> $this->_cookie_file,
						CURLOPT_COOKIEFILE			=> $this->_cookie_file
					)
				);
				
				/* execute cURL */
				$output								= json_decode(curl_exec($curl));
				
				/* close a cURL session and frees all resources */
				curl_close($curl);
				
				if(isset($output->data) && sizeof($output->data) > 0)
				{
					foreach($output->data as $key => $val)
					{
						/* initialize cURL */
						$curl								= curl_init();
						
						/* set cURL options */
						curl_setopt_array
						(
							$curl,
							array
							(
								CURLOPT_HEADER				=> false,
								CURLOPT_RETURNTRANSFER		=> true,
								CURLOPT_URL					=> $this->_sipd_hostname . '/daerah/main/plan/belanja/' . $this->_year . '/giat/detil-giat/' . $this->_id_daerah . '/' . $xyz['id'],
								CURLOPT_FOLLOWLOCATION		=> true,
								
								/* simulate the request through AJAX */
								CURLOPT_HTTPHEADER			=> array
								(
									'X-Requested-With: XMLHttpRequest'
								),
								
								/* simulate POST method of form submission */
								CURLOPT_CUSTOMREQUEST		=> 'POST',
								
								/* set the form submission parameter */
								CURLOPT_POSTFIELDS			=> http_build_query
								(
									array
									(
										'_token'			=> (isset($token[1]) ? $token[1] : 0),
										'idsubbl'			=> $val->id_sub_bl,
									)
								),
								
								/* cookie handling */
								CURLOPT_COOKIEJAR			=> $this->_cookie_file,
								CURLOPT_COOKIEFILE			=> $this->_cookie_file
							)
						);
						
						/* execute cURL */
						$output								= json_decode(curl_exec($curl));
						
						/* close a cURL session and frees all resources */
						curl_close($curl);
						
						print_r($output);exit;
					}
					
					$success										= true;
				}
			}
		}
		
		if($success)
		{
			$html									= '
				<div class="alert alert-success">
					Integrasi dengan data SIPD berhasil dijalankan
				</div>
				<ul>
					<li>
						Data Detail Kegiatan: <b>' . number_format($prepare_program) . '</b> data disimpan;
					</li>
				</ul>
			';
		}
		else
		{
			$html									= '
				<div class="alert alert-warning">
					Tidak dapat mengambil data dari SIPD disebabkan oleh beberapa faktor berikut:
				</div>
				<br />
				<ul>
					<li>
						Kesalahan dalam memilih data;
					</li>
					<li>
						Gagal mengakses server SIPD.
					</li>
				</ul>
			';
		}
		
		return make_json
		(
			array
			(
				'status'							=> 206,
				'exception'							=> array
				(
					'size'							=> 720,
					'title'							=> ($success ? 'Integrasi Sub Kegiatan berhasil!' : 'Integrasi Sub Kegiatan gagal!'),
					'icon'							=> ($success ? 'mdi mdi-check' : 'mdi mdi-alert'),
					'html'							=> $html
				)
			)
		);
	}
	
	private function _sub_unit()
	{
		$query										= $this->model->select
		('
			ref__sub.id,
			IFNULL(ref__urusan.kd_urusan, 0) AS kd_urusan,
			IFNULL(ref__bidang.kd_bidang, 0) AS kd_bidang,
			IFNULL(ref__urusan_2.kd_urusan, 0) AS kd_urusan_2,
			IFNULL(ref__bidang_2.kd_bidang, 0) AS kd_bidang_2,
			IFNULL(ref__urusan_3.kd_urusan, 0) AS kd_urusan_3,
			IFNULL(ref__bidang_3.kd_bidang, 0) AS kd_bidang_3,
			IFNULL(ref__unit.kd_unit, 0) AS kd_unit,
			IFNULL(ref__sub.kd_sub, 0) AS kd_sub,
			ref__sub.nm_sub
		')
		->join
		(
			'ref__unit',
			'ref__unit.id = ref__sub.id_unit'
		)
		->join
		(
			'ref__bidang',
			'ref__bidang.id = ref__unit.id_bidang'
		)
		->join
		(
			'ref__bidang ref__bidang_2',
			'ref__bidang_2.id = ref__unit.id_bidang_2',
			'LEFT'
		)
		->join
		(
			'ref__bidang ref__bidang_3',
			'ref__bidang_3.id = ref__unit.id_bidang_3',
			'LEFT'
		)
		->join
		(
			'ref__urusan',
			'ref__urusan.id = ref__bidang.id_urusan'
		)
		->join
		(
			'ref__urusan ref__urusan_2',
			'ref__urusan_2.id = ref__bidang_2.id_urusan',
			'LEFT'
		)
		->join
		(
			'ref__urusan ref__urusan_3',
			'ref__urusan_3.id = ref__bidang_3.id_urusan',
			'LEFT'
		)
		->get_where
		(
			'ref__sub',
			array
			(
				'ref__sub.tahun'					=> $this->_year
			)
		)
		->result();
		
		return $query;
	}
	
	private function _initialize_session()
	{
		/* initialize cURL */
		$curl										= curl_init();
		
		/* set cURL options */
		curl_setopt_array
		(
			$curl,
			array
			(
				CURLOPT_HEADER						=> false,
				CURLOPT_RETURNTRANSFER				=> true,
				CURLOPT_URL							=> $this->_sipd_hostname . '/daerah/logout?' . $this->_sipd_logout_url,
				CURLOPT_FOLLOWLOCATION				=> true,
				
				/* cookie handling */
				CURLOPT_COOKIEJAR					=> $this->_cookie_file,
				CURLOPT_COOKIEFILE					=> $this->_cookie_file
			)
		);
		
		/* execute cURL */
		$output										= curl_exec($curl);
		$error										= curl_error($curl);
		
		/* close a cURL session and frees all resources */
		curl_close($curl);
		
		if($error)
		{
			return throw_exception(403, $error);
		}
		
		/* extract the CSRF Token */
		preg_match('/<meta name="_token" content="(.*?)"/', $output, $token);
		
		if(isset($token[1]))
		{
			/* initialize cURL */
			$curl									= curl_init();
			
			/* set cURL options */
			curl_setopt_array
			(
				$curl,
				array
				(
					CURLOPT_TIMEOUT					=> 10,
					CURLOPT_HEADER					=> false,
					CURLOPT_RETURNTRANSFER			=> true,
					CURLOPT_URL						=> $this->_sipd_hostname . '/daerah/login',
					CURLOPT_FOLLOWLOCATION			=> true,
					
					/* simulate the request through AJAX */
					CURLOPT_HTTPHEADER				=> array
					(
						'Content-Type: application/x-www-form-urlencoded',
						'X-Requested-With: XMLHttpRequest'
					),
					
					/* simulate POST method of form submission */
					CURLOPT_CUSTOMREQUEST			=> 'POST',
					
					/* set the form submission parameter */
					CURLOPT_POSTFIELDS				=> http_build_query
					(
						array
						(
							'_token'				=> $token[1],
							'env'					=> 'main',
							'region'				=> 'daerah',
							'skrim'					=> base64_encode('user_name=' . $this->_sipd_username . '&user_password=' . $this->_sipd_password)
						)
					),
					
					/* cookie handling */
					CURLOPT_COOKIEJAR				=> $this->_cookie_file,
					CURLOPT_COOKIEFILE				=> $this->_cookie_file
				)
			);
			
			/* execute cURL */
			$output									= json_decode(curl_exec($curl));
			
			/* close a cURL session and frees all resources */
			curl_close($curl);
			
			if(isset($output->result))
			{
				if($output->result == 'success')
				{
					$this->_id_daerah				= $output->id_daerah;
				}
				elseif(isset($output->message))
				{
					/* failed to sign in because user already signed in */
					return throw_exception(403, $output->message);
				}
				else
				{
					/* failed to sign in with unknown exception */
					return make_json($output);
				}
			}
			else
			{
				/* failed to sign in with unknown exception */
				return throw_exception(403, 'Failed to getting the session. Request timed out.');
			}
		}
		else
		{
			/* failed to get the token, it's maybe caused by server timed out */
			return throw_exception(403, 'Failed to getting the CSRF token. Request timed out.');
		}
	}
}
