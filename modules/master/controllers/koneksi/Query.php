<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Query extends Aksara
{
	private $_table									= 'ref_query';
	function __construct()
	{
		parent::__construct();
		$this->_primary								= $this->input->get('connection');
		$this->set_permission();
		$this->set_theme('backend');
	}
	
	public function index()
	{
		$this->set_title(phrase('database_queries'))
		->set_icon('fa fa-code')
		->set_field
		(
			array
			(
				'query'								=> 'textarea',
				'status'							=> 'boolean'
			)
		)
		->unset_column('id, connection_id')
		->unset_view('id')
		->unset_field('id')
		->set_default('connection_id', $this->_primary)
		->where('connection_id', $this->_primary)
		->set_template('form', 'form')
		->render($this->_table);
	}
	
	public function run_query()
	{
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '20000M');
		$query										= $this->input->post('query');
		$connection									= $this->input->get('connection');
		$connection									= $this->db->get_where('ref_koneksi', array('id' => $connection), 1)->result_array();
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
									' . phrase('query_has_executed_successfully') . '
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
									' . phrase('unable_to_execute_query') . '
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