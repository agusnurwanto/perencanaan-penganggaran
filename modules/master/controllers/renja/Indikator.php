<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Indikator extends Aksara
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
				ta__indikator.id,
				ta__indikator.id_keg,
				ta__indikator.jns_indikator,
				ta__indikator.kd_indikator
			FROM
				ta__indikator
			INNER JOIN ta__kegiatan ON ta__indikator.id_keg = ta__kegiatan.id
			WHERE
				ta__kegiatan.flag = 1
			ORDER BY
				ta__indikator.id_keg ASC,
				ta__indikator.jns_indikator ASC,
				ta__indikator.kd_indikator ASC
		')
		->result();
		
		$count								= sizeof($query);
		$error								= array();
		if($query)
		{
			$kd_indikator					= 1;
			$jns_indikator					= 0;
			$id_keg							= 0;
			foreach($query as $key => $val)
			{
				if($id_keg == $val->id_keg && $jns_indikator == $val->jns_indikator)
				{
					$kd_indikator			+= 1;
				}
				else
				{
					$kd_indikator			= 1;
				}
				
				$execute					= $this->model->update('ta__indikator', array('kd_indikator' => $kd_indikator), array('id' => $val->id), 1);
				if(!$execute)
				{
					$error[]				= $val->id;
				}
				$jns_indikator				= $val->jns_indikator;
				$id_keg						= $val->id_keg;
			}
		}
		if(!$error)
		{
			generateMessages(301, 'Berhasil merubah <b>' . $count . '</b> data kode indikator secara massal...', go_to('../generators'));
		}
		else
		{
			generateMessages(500, 'Sebanyak <b>' . sizeof($error) . '</b> dari <b>' . $count . '</b> data kode indikator tidak dapat diubah...', go_to('../generators'));
		}
	}
}