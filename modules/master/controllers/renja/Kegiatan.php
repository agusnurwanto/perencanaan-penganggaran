<?php defined('BASEPATH') OR exit('No direct script access allowed');
class Kegiatan extends Aksara
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
				ref__urusan.kd_urusan,
				ref__bidang.kd_bidang,
				ref__unit.kd_unit,
				ref__sub.kd_sub,
				ref__program.id AS id_program,
				ref__program.kd_program,
				ta__kegiatan.id,
				ta__kegiatan.kd_keg
			FROM
				ta__kegiatan
			INNER JOIN ta__program ON ta__kegiatan.id_prog = ta__program.id
			INNER JOIN ref__program ON ta__program.id_prog = ref__program.id
			INNER JOIN ref__bidang ON ref__program.id_bidang = ref__bidang.id
			INNER JOIN ref__urusan ON ref__bidang.id_urusan = ref__urusan.id
			INNER JOIN ref__sub ON ta__program.id_sub = ref__sub.id
			INNER JOIN ref__unit ON ref__sub.id_unit = ref__unit.id
			WHERE
				ta__kegiatan.flag = 1 AND
				ref__program.kd_program >= 15
			ORDER BY
				ref__urusan.kd_urusan ASC,
				ref__bidang.kd_bidang ASC,
				ref__program.id ASC,
				ref__program.kd_program ASC,
				ref__unit.kd_unit ASC,
				ref__sub.kd_sub ASC,
				ta__kegiatan.kd_keg ASC
		')
		->result();
		$count								= sizeof($query);
		$error								= array();
		if($query)
		{
			$kd_keg							= 1;
			$id_prog						= 0;
			foreach($query as $key => $val)
			{
				if($id_prog == $val->id_program)
				{
					$kd_keg					+= 1;
				}
				else
				{
					$kd_keg					= 1;
				}
				
				$execute					= $this->model->update('ta__kegiatan', array('kd_keg' => $kd_keg), array('id' => $val->id), 1);
				if(!$execute)
				{
					$error[]				= $val->id;
				}
				$id_prog					= $val->id_program;
			}
		}
		if(!$error)
		{
			generateMessages(301, 'Berhasil merubah <b>' . $count . '</b> data kode kegiatan secara massal...', go_to('../generators'));
		}
		else
		{
			generateMessages(500, 'Sebanyak <b>' . sizeof($error) . '</b> dari <b>' . $count . '</b> data kode kegiatan tidak dapat diubah...', go_to('../generators'));
		}
	}
}