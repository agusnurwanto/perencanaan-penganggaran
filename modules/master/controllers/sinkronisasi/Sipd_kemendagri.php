<?php defined('BASEPATH') OR exit('No direct script access allowed');
ini_set('max_execution_time', 0);
ini_set('memory_limit', '-1');

class Sipd_kemendagri extends Aksara
{
	private $_query									= array();
	
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
		
		$this->unset_action('create, read, update, delete, export, print, pdf');
		
		$this->permission->must_ajax('master/sinkronisasi');
		
		$this->_year								= ($this->input->post('tahun') ? $this->input->post('tahun') : get_userdata('year'));
		$this->_unit								= ($this->input->post('unit') ? $this->input->post('unit') : 0);
		
		/**
		 * API Key
		 */
		
		$setting									= $this->model->select
		('
			server_api_sipd,
			kode_pemda,
			token_api_sipd
		')
		->get_where
		(
			'ref__settings',
			array
			(
				'tahun'								=> get_userdata('year')
			),
			1
		)
		->row();
		
		$this->_api_server							= 'https://' . (isset($setting->server_api_sipd) ? preg_replace('(^https?://)', '', strtok($setting->server_api_sipd, '?')) : 'bekasi.sipd.kemendagri.go.id/run/serv/push_ranwal.php');
		$this->_kodepemda							= (isset($setting->kode_pemda) ? $setting->kode_pemda : 1018);
		$this->_bearer								= (isset($setting->token_api_sipd) ? $setting->token_api_sipd : null);
	}
	
	public function index()
	{
		$this->load->model('Sinkronisasi_model', 'sinkronisasi');
		
		$this->_query								= $this->sinkronisasi->sipd_kemendagri($this->_year, $this->_unit);
		
		//file_put_contents('uploads/INTEGRASI-SIPD.json', $this->_data());
		
		$curl										= curl_init();
		curl_setopt_array
		(
			$curl,   
			array
			(
				CURLOPT_FAILONERROR					=> true,
				CURLOPT_URL							=> $this->_api_server . '?' . http_build_query(array('tahun' => $this->_year, 'kodepemda' => $this->_kodepemda)),
				CURLOPT_RETURNTRANSFER				=> true,
				CURLOPT_POST						=> true,
				CURLOPT_SSL_VERIFYHOST				=> false,
				CURLOPT_SSL_VERIFYPEER				=> false,
				CURLOPT_POSTFIELDS					=> $this->_data(),
				CURLOPT_HTTPHEADER					=> array
				(
					'Content-Type: application/json',
					'Authorization: Bearer ' . $this->_bearer,
				)
			)
		);
		
		$response									= json_decode(curl_exec($curl));
		
		if($response)
		{
			$logs									= null;
		}
		else
		{
			$logs									= '<li>' . curl_error($curl) . '</li>';
		}
		
		curl_close($curl);
		
		if(isset($response->log))
		{
			foreach($response->log as $key => $val)
			{
				$logs								.= '<li>' . $val . '</li>';
			}
		}
		
		$html										= '
			<p class="text-center no-margin">
				<i class="fa ' . (isset($response->code) && 200 == $response->code ? 'fa-check-circle-o text-success' : 'fa fa-times text-danger') . ' fa-5x"></i>
			</p>
			<p class="text-center ' . (isset($response->code) && 200 == $response->code ? 'text-success' : 'text-danger') . '">
				<b>' . (isset($response->msg) ? $response->msg : 'Gagal melakukan sinkronisasi!') . '</b>
			</p>
			<hr />
			<ul>
				' . ($logs ? $logs : '<li>Log untuk sinkronisasi yang dijalankan tidak tercatat...</li>') . '
			</ul>
			<hr />
			<p class="text-right no-margin">
				<button type="button" class="btn btn-light" data-dismiss="modal">
					<i class="mdi mdi-check"></i>
					Tutup
				</button>
			</p>
		';
		
		return make_json
		(
			array
			(
				'status'							=> 206,
				'exception'							=> array
				(
					'size'							=> 720,
					'title'							=> ($response ? 'Sinkronisasi Berhasil!' : 'Sinkronisasi Gagal!'),
					'icon'							=> ($response ? 'mdi mdi-check' : 'mdi mdi-alert'),
					'html'							=> $html
				)
			)
		);
	}
	
	private function _data()
	{		
		$output										= array();
		
		if(isset($this->_query['skpd']))
		{
			foreach($this->_query['skpd'] as $key => $val)
			{
				$output[]							= array
				(
					'kodepemda'						=> $this->_kodepemda,
					'tahun'							=> $this->_year,
					'kodebidang'					=> $val->kodebidang,
					'uraibidang'					=> $val->uraibidang,
					'kodeskpd'						=> $val->kodeskpd,
					'uraiskpd'						=> $val->uraiskpd,
					'pejabat'						=> $this->_pejabat($val->id_unit),
					'pilihanbidang'					=> array
					(
						$val->pilihanbidang
					),
					'uraiurusan'					=> $val->uraiurusan,
					'program'						=> $this->_program($val->id_unit)
				);
			}
		}
		
		return json_encode($output);
	}
	
	private function _pejabat($id_unit = null)
	{
		$output										= array();
		
		foreach($this->_query['pejabat'] as $key => $val)
		{
			if($val->id_unit == $id_unit)
			{
				$output								= array
				(
					'kepalanip'						=> $val->kepalanip,
					'kepalanama'					=> $val->kepalanama,
					'kepalajabatan'					=> $val->kepalajabatan,
					'kepalapangkat'					=> $val->kepalapangkat
				);
				
				break;
			}
		}
		
		return $output;
	}
	
	private function _program($id_unit = null)
	{
		$output										= array();
		
		foreach($this->_query['program'] as $key => $val)
		{
			if($val->id_unit != $id_unit) continue;
			
			$kode									= array
			(
				'kode_urusan'						=> $val->kode_urusan,
				'kode_bidang'						=> $val->kode_bidang,
				'kode_unit'							=> $val->kode_unit,
				'kode_sub'							=> $val->kode_sub,
				'kode_prog'							=> $val->kode_prog,
				'id_prog'							=> $val->id_prog
			);
			
			$output[]								= array
			(
				'kodebidang'						=> $val->kodebidang,
				'uraibidang'						=> $val->uraibidang,
				'kodeprogram'						=> $val->kodeprogram,
				'uraiprogram'						=> $val->uraiprogram,
				'prioritas'							=> array(),
				'capaian'							=> $this->_capaian($kode),
				'kegiatan'							=> $this->_kegiatan($kode)
			);
			
			// unset "already rendered data" to prevent re-looping
			unset($this->_query['program'][$key]);
		}
		
		return $output;
	}
	
	private function _capaian($kode = array())
	{
		$output										= array();
		
		foreach($this->_query['capaian_program'] as $key => $val)
		{
			if($val->kode_urusan != $kode['kode_urusan'] || $val->kode_bidang != $kode['kode_bidang'] || $val->kode_unit != $kode['kode_unit'] || $val->kode_sub != $kode['kode_sub'] || $val->kode_prog != $kode['kode_prog'] || $val->id_prog != $kode['id_prog']) continue;
			
			$output[]								= array
			(
				'kodeindikator'						=> $val->kodeindikator,
				'tolokukur'							=> $val->tolakukur,
				'satuan'							=> $val->satuan,
				'pagu'								=> $val->pagu,
				'pagu_p'							=> $val->pagu_p,
				'pagu_p1'							=> $val->pagu_p1,
				'real_p1'							=> $val->real_p1,
				'pagu_p2'							=> $val->pagu_p2,
				'real_p2'							=> $val->real_p2,
				'pagu_p3'							=> $val->pagu_p3,
				'real_p3'							=> $val->real_p3,
				'pagu_n1'							=> $val->pagu_n1,
				'target'							=> $val->target,
				'target_n1'							=> $val->target_n1
			);
			
			// unset "already rendered data" to prevent re-looping
			unset($this->_query['capaian_program'][$key]);
		}
		
		return $output;
	}
	
	private function _kegiatan($kode = array())
	{
		$output										= array();
		
		foreach($this->_query['kegiatan'] as $key => $val)
		{
			if($val->kode_urusan != $kode['kode_urusan'] || $val->kode_bidang != $kode['kode_bidang'] || $val->kode_unit != $kode['kode_unit'] || $val->kode_sub != $kode['kode_sub'] || $val->kode_prog != $kode['kode_prog'] || $val->id_prog != $kode['id_prog']) continue;
			
			$kode['kode_keg']						= $val->kode_keg;
			
			$output[]								= array
			(
				'kodekegiatan'						=> $val->kodekegiatan,
				'uraikegiatan'						=> $val->uraikegiatan,
				'pagu'								=> $val->pagu,
				'pagu_p'							=> $val->pagu_p,
				'sumberdana'						=> $this->_sumber_dana($kode),
				'prioritas'							=> array
				(
					array
					(
						'prioritasnasional'			=> 'Prioritas Nasional'
					),
					array
					(
						'prioritasprovinsi'			=> 'Meningkatkan indeks provinsi'
					),
					array
					(
						'prioritasdaerah'			=> 'Meningkatkan kesejahteraan rakyat'
					),
					array
					(
						'prioritasdaerah'			=> 'Pemantapan tata kelola pemerintahan'
					)
				),
				'lokasi'							=> $this->_lokasi($kode),
				'indikator'							=> $this->_indikator($kode),
				'subkegiatan'						=> $this->_sub_kegiatan($kode)
			);
			
			// unset "already rendered data" to prevent re-looping
			unset($this->_query['kegiatan'][$key]);
		}
		
		return $output;
	}
	
	private function _sumber_dana($kode = array())
	{
		$output										= array();
		
		foreach($this->_query['sumber_dana'] as $key => $val)
		{
			//if($val->kode_urusan != $kode['kode_urusan'] || $val->kode_bidang != $kode['kode_bidang'] || $val->kode_unit != $kode['kode_unit'] || $val->kode_sub != $kode['kode_sub'] || $val->kode_prog != $kode['kode_prog'] || $val->id_prog != $kode['id_prog'] || $val->kode_keg != $kode['kode_keg']) continue;
			
			$output[]								= array
			(
				'pagu'								=> $val->pagu,
				'sumberdana'						=> $val->sumberdana,
				'kodesumberdana'					=> $val->kodesumberdana
			);
			
			// unset "already rendered data" to prevent re-looping
			unset($this->_query['sumber_dana'][$key]);
		}
		
		return $output;
	}
	
	private function _lokasi($kode = array())
	{
		$output										= array();
		
		foreach($this->_query['lokasi'] as $key => $val)
		{
			if($val->kode_urusan != $kode['kode_urusan'] || $val->kode_bidang != $kode['kode_bidang'] || $val->kode_unit != $kode['kode_unit'] || $val->kode_sub != $kode['kode_sub'] || $val->kode_prog != $kode['kode_prog'] || $val->id_prog != $kode['id_prog'] || $val->kode_keg != $kode['kode_keg']) continue;
			
			$output[]								= array
			(
				'lokasi'							=> $val->lokasi,
				'kodelokasi'						=> $val->kodelokasi,
				'detaillokasi'						=> $val->detaillokasi
			);
			
			// unset "already rendered data" to prevent re-looping
			unset($this->_query['lokasi'][$key]);
		}
		
		return $output;
	}
	
	private function _indikator($kode = array())
	{
		$output										= array();
		
		foreach($this->_query['indikator'] as $key => $val)
		{
			if($val->kode_urusan != $kode['kode_urusan'] || $val->kode_bidang != $kode['kode_bidang'] || $val->kode_unit != $kode['kode_unit'] || $val->kode_sub != $kode['kode_sub'] || $val->kode_prog != $kode['kode_prog'] || $val->id_prog != $kode['id_prog'] || $val->kode_keg != $kode['kode_keg']) continue;
			
			$output[]								= array
			(
				'kodeindikator'						=> $val->kodeindikator,
				'jenis'								=> $val->jenis,
				'tolokukur'							=> $val->tolakukur,
				'satuan'							=> $val->satuan,
				'target'							=> $val->target,
				'pagu'								=> $val->pagu,
				'pagu_p'							=> $val->pagu_p,
				'pagu_p1'							=> $val->pagu_p1,
				'real_p1'							=> $val->real_p1,
				'pagu_p2'							=> $val->pagu_p2,
				'real_p2'							=> $val->real_p2,
				'pagu_p3'							=> $val->pagu_p3,
				'real_p3'							=> $val->real_p3,
				'pagu_n1'							=> $val->pagu_n1,
				'target'							=> $val->target,
				'target_n1'							=> $val->target_n1
			);
			
			// unset "already rendered data" to prevent re-looping
			unset($this->_query['indikator'][$key]);
		}
		
		return $output;
	}
	
	private function _sub_kegiatan($kode = array())
	{
		$output										= array();
		
		foreach($this->_query['sub_kegiatan'] as $key => $val)
		{
			if($val->kode_urusan != $kode['kode_urusan'] || $val->kode_bidang != $kode['kode_bidang'] || $val->kode_unit != $kode['kode_unit'] || $val->kode_sub != $kode['kode_sub'] || $val->kode_prog != $kode['kode_prog'] || $val->id_prog != $kode['id_prog'] || $val->kode_keg != $kode['kode_keg']) continue;
			
			$output[]								= array
			(
				'kodesubkegiatan'					=> $val->kodesubkegiatan,
				'uraisubkegiatan'					=> $val->uraisubkegiatan,
				'pagu'								=> $val->pagu,
				'pagu_p'							=> $val->pagu_p,
				'sumberdana'						=> array(),
				'prioritas'							=> array(),
				'lokasi'							=> array(),
				'indikator'							=> $this->_indikator_sub_kegiatan($kode)
			);
			
			// unset "already rendered data" to prevent re-looping
			unset($this->_query['sub_kegiatan'][$key]);
		}
		
		return $output;
	}
	
	private function _indikator_sub_kegiatan($kode = array())
	{
		$output										= array();
		
		foreach($this->_query['indikator_sub_kegiatan'] as $key => $val)
		{
			if($val->kode_urusan != $kode['kode_urusan'] || $val->kode_bidang != $kode['kode_bidang'] || $val->kode_unit != $kode['kode_unit'] || $val->kode_sub != $kode['kode_sub'] || $val->kode_prog != $kode['kode_prog'] || $val->id_prog != $kode['id_prog'] || $val->kode_keg != $kode['kode_keg']) continue;
			
			$output[]								= array
			(
				'kodeindikator'						=> $val->kodeindikator,
				'jenis'								=> $val->jenis,
				'tolokukur'							=> $val->tolakukur,
				'satuan'							=> $val->satuan,
				'target'							=> $val->target,
				'pagu'								=> $val->pagu,
				'pagu_p'							=> $val->pagu_p,
				'pagu_p1'							=> $val->pagu_p1,
				'real_p1'							=> $val->real_p1,
				'pagu_p2'							=> $val->pagu_p2,
				'real_p2'							=> $val->real_p2,
				'pagu_p3'							=> $val->pagu_p3,
				'real_p3'							=> $val->real_p3,
				'pagu_n1'							=> $val->pagu_n1,
				'target'							=> $val->target,
				'target_n1'							=> $val->target_n1
			);
			
			// unset "already rendered data" to prevent re-looping
			unset($this->_query['indikator_sub_kegiatan'][$key]);
		}
		
		return $output;
	}
}