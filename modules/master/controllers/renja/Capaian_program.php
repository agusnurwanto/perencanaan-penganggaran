<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Capaian_program extends Aksara
{
	function __construct()
	{
		parent::__construct();
		$this->set_permission();
		$this->permission->must_ajax();
	}
	
	public function index()
	{
		$query								= $this->model->query
		('
			SELECT
				ta__program_capaian.id,
				ta__program_capaian.id_prog,
				ta__program_capaian.kode
			FROM
				ta__program_capaian
			ORDER BY
				ta__program_capaian.id_prog ASC,
				ta__program_capaian.kode ASC
		')
		->result();
		
		$count								= sizeof($query);
		$error								= array();
		if($query)
		{
			$kode							= 1;
			$id_prog						= 0;
			foreach($query as $key => $val)
			{
				if($id_prog == $val->id_prog)
				{
					$kode					+= 1;
				}
				else
				{
					$kode					= 1;
				}
				
				$execute					= $this->model->update('ta__program_capaian', array('kode' => $kode), array('id' => $val->id), 1);
				if(!$execute)
				{
					$error[]				= $val->id;
				}
				$id_prog					= $val->id_prog;
			}
		}
		if(!$error)
		{
			generateMessages(301, 'Berhasil merubah <b>' . $count . '</b> data kode capaian program secara massal...', go_to('../generators'));
		}
		else
		{
			generateMessages(500, 'Sebanyak <b>' . sizeof($error) . '</b> dari <b>' . $count . '</b> data kode capaian program tidak dapat diubah...', go_to('../generators'));
		}
	}
}