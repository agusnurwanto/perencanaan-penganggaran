<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Koneksi extends Aksara
{
	private $_table									= 'ref__koneksi';
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->set_theme('backend');
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
		->set_title(phrase('master_koneksi'))
		->set_icon('fa fa-calendar')
		//->add_action('option', 'query', 'Query', 'btn-success ajaxLoad', 'fa fa-code', array('connection' => 'id'))
		->set_alias
		(
			array
			(
				'name'								=> 'Nama Koneksi',
				'description'						=> 'Keterangan',
				'database_driver'					=> 'Driver Database',
				'username'							=> 'Nama Pengguna',
				'password'							=> 'Kata Sandi',
				'database_name'						=> 'Nama Database'
			)
		)
		
		->set_field
		(
			array
			(
				'description'						=> 'textarea',
				'hostname'							=> 'encryption',
				'username'							=> 'encryption',
				'password'							=> 'encryption',
				'database_name'						=> 'encryption',
				'port'								=> 'encryption',
				'status'							=> 'boolean'
			)
		)
		->set_field
		(
			'database_driver',
			'dropdown',
			array
			(
				'pdo'								=> 'PDO',
				'mssql'								=> 'Microsoft SQL Server (MSSQL)',
				'sqlsrv'							=> 'Microsoft SQL Server (SQLSRV)'
			)
		)
		->set_relation
		(
			'tahun',
			'ref__tahun.tahun',
			'{ref__tahun.tahun}',
			array
			(
				'ref__tahun.aktif'					=> 1
			)
		)
		->field_position
		(
			array
			(
				'port'								=> 2,
				'username'							=> 2,
				'password'							=> 2,
				'database_name'						=> 2,
				'status'							=> 2
			)
		)
		->set_validation
		(
			array
			(
				'tahun'								=> 'required',
				'name'								=> 'required'
			)
		)
		->unset_column('id')
		->unset_field('id, tahun_ref__tahun')
		->unset_view('id, tahun_ref__tahun')
		->render($this->_table);
	}
	
	public function cek_query()
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '20000M');
		$query										= $this->input->post('query');
		$connection									= $this->input->get('connection');
		$connection									= $this->db->limit(1)->where('id', $connection)->get('ref__koneksi')->result_array();
		if($connection)
		{
			foreach($connection as $key => $val)
			{
				$configs							= array
				(
					'dsn'							=> '',
					'hostname' 						=> $this->encryption->decrypt($val['hostname']),
					'username'						=> $this->encryption->decrypt($val['username']),
					'password' 						=> $this->encryption->decrypt($val['password']),
					'database' 						=> $this->encryption->decrypt($val['database_name']),
					'dbdriver' 						=> $val['database_driver'],
					'dbprefix' 						=> '',
					'pconnect' 						=> FALSE,
					'db_debug' 						=> (ENVIRONMENT !== 'production'),
					'cache_on' 						=> FALSE,
					'cachedir' 						=> '',
					'char_set' 						=> 'utf8',
					'dbcollat' 						=> 'utf8_unicode_ci',
					'swap_pre' 						=> '',
					'encrypt' 						=> FALSE,
					'compress' 						=> FALSE,
					'stricton' 						=> FALSE,
					'failover' 						=> array(),
					'save_queries' 					=> TRUE
				);
				$db									= $this->load->database($configs, TRUE);
				$run								= $db->query($query)->result_array();
				if($query && $run)
				{
					make_json
					(
						array
						(
							'status'				=> 200,
							'html'					=> '
								<div class="alert alert-success alert-dismissable">
									<button type="button" class="close pull-right" data-dismiss="alert">
										<i class="fa fa-times"></i>
									</button>
									<i class="fa fa-info-circle"></i>
									Koneksi terhubung dan query dapat dijalankan!
									<br />
									<pre>' . $db->last_query() . '</pre>
								</div>
							'
						)
					);
				}
				else
				{
					make_json
					(
						array
						(
							'status'				=> 500,
							'html'					=> '
								<div class="alert alert-danger alert-dismissable">
									<button type="button" class="close pull-right" data-dismiss="alert">
										<i class="fa fa-times"></i>
									</button>
									<i class="fa fa-info-circle"></i>
									Tidak dapat menjalankan query!
									<br />
									<pre>' . $db->last_query() . '</pre>
								</div>
							'
						)
					);
				}
			}
		}
	}
}