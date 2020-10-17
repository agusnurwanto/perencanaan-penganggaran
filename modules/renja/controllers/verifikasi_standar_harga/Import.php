<?php defined('BASEPATH') OR exit('No direct script access allowed');
/**
 * @version			2.1.0
 * @author			Aby Dahana
 * @author			Ganjar Nugraha
 * @profile			abydahana.github.io
 * @profile			www.ganjar.id
 */
ini_set('max_execution_time', 0);
ini_set('memory_limit', '20000M');

class Import extends Aksara
{
	private $_table									= 'ref__standar_harga';
	private $_data									= array();
	
	function __construct()
	{
		parent::__construct();
		
		$this->set_theme('backend');
		$this->parent_module('standar/harga');
		$this->set_permission();
	}
	
	public function index()
	{
		if($this->input->post('token'))
		{
			$this->load->library('form_validation');
			
			$this->form_validation->set_rules('file', 'Berkas', 'required|callback_validate_import');
			
			if($this->form_validation->run() === false)
			{
				return throw_exception(400, $this->form_validation->error_array());
			}
		}
		
		$this->set_title('Import data')
		->set_icon('fa fa-upload')
		->render(null, 'import');
	}
	
	public function validate_import()
	{
		$config['upload_path']						= 'uploads/_import_tmp';
		$config['allowed_types']					= 'csv|xls|xlsx';
		$config['max_size']							= 10480;
		$config['encrypt_name']						= true;
		
		$this->load->library('upload');
		$this->upload->initialize($config);
		
		if($this->upload->do_upload('file'))
		{
			$uploaded								= $this->upload->data();
			$this->_import_data($uploaded['full_path']);
		}
		
		$this->form_validation->set_message('validate_import', $this->upload->display_errors('', ''));
		return false;
	}
	
	private function _import_data($source = null)
	{
		/* load excel reader library */
		$this->load->library('excel');
		
		$spreadsheet								= PHPExcel_IOFactory::load($source);
		$data										= $spreadsheet->getActiveSheet()->toArray(null, true, true, true);
		
		/* unlink the previous uploaded file */
		if(file_exists($source))
		{
			unlink($source);
		}
		
		/* check if data is not empty */
		if($data)
		{
			$prepare								= array();
			$total_data								= 0;
			$success								= 0;
			$error									= 0;
			$error_data								= array();
			$last_kode								= array();
			
			foreach($data as $key => $val)
			{
				/* skip the header of excel template */
				if($key == 1)
				{
					continue;
				}
				
				/* check if the columns count is not meet the requirements */
				if(!$val['A'] && !$val['B'] && !$val['D'] && !$val['E'] && !$val['F'])
				{
					break;
				}
				
				$total_data++;
				
				if(!$val['A'] || !$val['B'] || !$val['D'] || !$val['E'] || !$val['F'])
				{
					/* product is not exists, continue */
					$error++;
					
					if(!$val['A'])
					{
						$val['message']				= 'Kode tidak diisi';
					}
					elseif(!$val['B'])
					{
						$val['message']				= 'Uraian tidak diisi';
					}
					elseif(!$val['D'])
					{
						$val['message']				= 'Satuan tidak diisi';
					}
					elseif(!$val['E'])
					{
						$val['message']				= 'Harga tidak diisi';
					}
					elseif(!$val['F'])
					{
						$val['message']				= 'Rekening tidak diisi';
					}
					
					$error_data[]					= $val;
					
					continue;
				}
				
				$kode								= explode('.', $val['A']);
				
				if(!isset($kode[6]))
				{
					$error++;
					
					$val['message']					= 'Format kode yang diberikan salah';
					
					$error_data[]					= $val;
					
					continue;
				}
				
				$id_standar_7						= $this->model->select
				('
					ref__standar_harga_7.id
				')
				->join
				(
					'ref__standar_harga_6',
					'ref__standar_harga_6.id = ref__standar_harga_7.id_standar_harga_6'
				)
				->join
				(
					'ref__standar_harga_5',
					'ref__standar_harga_5.id = ref__standar_harga_6.id_standar_harga_5'
				)
				->join
				(
					'ref__standar_harga_4',
					'ref__standar_harga_4.id = ref__standar_harga_5.id_standar_harga_4'
				)
				->join
				(
					'ref__standar_harga_3',
					'ref__standar_harga_3.id = ref__standar_harga_4.id_standar_harga_3'
				)
				->join
				(
					'ref__standar_harga_2',
					'ref__standar_harga_2.id = ref__standar_harga_3.id_standar_harga_2'
				)
				->join
				(
					'ref__standar_harga_1',
					'ref__standar_harga_1.id = ref__standar_harga_2.id_standar_harga_1'
				)
				->get_where
				(
					'ref__standar_harga_7',
					array
					(
						'ref__standar_harga_7.kd_standar_harga_7'		=> (int) $kode[6],
						'ref__standar_harga_6.kd_standar_harga_6'		=> (int) $kode[5],
						'ref__standar_harga_5.kd_standar_harga_5'		=> (int) $kode[4],
						'ref__standar_harga_4.kd_standar_harga_4'		=> (int) $kode[3],
						'ref__standar_harga_3.kd_standar_harga_3'		=> (int) $kode[2],
						'ref__standar_harga_2.kd_standar_harga_2'		=> (int) $kode[1],
						'ref__standar_harga_1.kd_standar_harga_1'		=> (int) $kode[0]
					),
					1
				)
				->row('id');
				
				if(!$id_standar_7)
				{
					$error++;
					
					$val['message']					= 'Referensi standar harga berdasarkan kode yang diberikan tidak ditemukan';
					
					$error_data[]					= $val;
					
					continue;
				}
				
				if(isset($last_kode[$id_standar_7]))
				{
					$last_kode[$id_standar_7]++;
				}
				else
				{
					$last_kode_query				= $this->model->select_max
					('
						kode
					')
					->get_where
					(
						'ref__standar_harga',
						array
						(
							'id_standar_harga_7'	=> $id_standar_7
						),
						1
					)
					->row('kode');
					
					$last_kode[$id_standar_7]		= $last_kode_query + 1;
				}
				
				/* everything's well, put into prepared data */
				$prepare							= array
				(
					'id_standar_harga_7'			=> $id_standar_7,
					'id_sub'						=> (get_userdata('sub_level_1') ? get_userdata('sub_level_1') : 0),
					'flag'							=> ($val['P'] == 4 ? 1 : 0),
					'kode'							=> $last_kode[$id_standar_7],
					'uraian'						=> $val['B'],
					'spesifikasi'					=> $val['C'],
					'satuan_1'						=> $val['D'],
					'nilai'							=> $val['E'],
					'operator'						=> get_userdata('user_id'),
					'approve'						=> 1,
					'tahun'							=> get_userdata('year')
				);
				
				if($this->model->insert($this->_table, $prepare))
				{
					$last_insert					= $this->model->insert_id();
					$rekening						= array('F', 'G', 'H', 'I', 'J', 'K', 'L', 'M', 'N', 'O');
					
					foreach($rekening as $_key => $_val)
					{
						if($val[$_val])
						{
							$kode_rekening_1		= explode('.', $val[$_val]);
							
							if(isset($kode_rekening_1[5]))
							{
								$id_rek_6			= $this->model->select
								('
									ref__rek_6.id
								')
								->join
								(
									'ref__rek_5',
									'ref__rek_5.id = ref__rek_6.id_ref_rek_5'
								)
								->join
								(
									'ref__rek_4',
									'ref__rek_4.id = ref__rek_5.id_ref_rek_4'
								)
								->join
								(
									'ref__rek_3',
									'ref__rek_3.id = ref__rek_4.id_ref_rek_3'
								)
								->join
								(
									'ref__rek_2',
									'ref__rek_2.id = ref__rek_3.id_ref_rek_2'
								)
								->join
								(
									'ref__rek_1',
									'ref__rek_1.id = ref__rek_2.id_ref_rek_1'
								)
								->get_where
								(
									'ref__rek_6',
									array
									(
										'ref__rek_6.kd_rek_6'				=> $kode_rekening_1[5],
										'ref__rek_5.kd_rek_5'				=> $kode_rekening_1[4],
										'ref__rek_4.kd_rek_4'				=> $kode_rekening_1[3],
										'ref__rek_3.kd_rek_3'				=> $kode_rekening_1[2],
										'ref__rek_2.kd_rek_2'				=> $kode_rekening_1[1],
										'ref__rek_1.kd_rek_1'				=> $kode_rekening_1[0]
									),
									1
								)
								->row('id');
								
								if($id_rek_6)
								{
									$this->model->insert
									(
										'ref__standar_rekening',
										array
										(
											'id_standar_harga'				=> $last_insert,
											'no_urut'						=> ($_key + 1),
											'id_rek_6'						=> $id_rek_6
										)
									);
								}
							}
						}
					}
					
					$success++;
				}
				else
				{
					$error++;
				}
			}
			
			/* check if prepared data isn't empty */
			if($success || $error_data)
			{
				$error_list									= null;
				
				$html										= '<div class="alert alert-' . ($success ? ($error ? 'warning' : 'success') : 'danger') . '"><b>' . number_format($success) . ' ' . phrase('of') . ' ' . number_format($total_data) . '</b> data berhasil diimport.' . ($error ? ' <b>' . number_format($error) . '</b> ' . 'item diabaikan dikarenakan ada kesalahan format ketik' : null) . '</div>';
				
				if($error_data)
				{
					foreach($error_data as $key => $val)
					{
						$error_list							.= '<li>Kode <b>' . $val['A'] . '</b>' . ($val['B'] ? ' [' . $val['B'] . ']' : null) . ' (Kesalahan: ' . $val['message'] . ')</li>';
					}
				}
				
				if($error_list)
				{
					$html									.= '<ul>' . $error_list . '</ul>';
				}
				
				return make_json
				(
					array
					(
						'status'							=> 206,
						'exception'							=> array
						(
							'size'							=> 720,
							'title'							=> ($success ? 'Import berhasil!' : 'Import!'),
							'icon'							=> ($success ? 'mdi mdi-check' : 'mdi mdi-alert'),
							'html'							=> $html
						)
					)
				);
			}
		}
		
		/* otherwise data cannot be processed */
		return throw_exception(403, 'Berkas berhasil diimport akan tetapi data tidak dapat diproses');
	}
}